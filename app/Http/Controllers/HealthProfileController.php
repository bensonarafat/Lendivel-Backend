<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Allergy;
use App\Models\Medication;
use Illuminate\Http\Request;
use App\Models\HealthProfile;
use App\Models\ChronicCondition;
use Illuminate\Support\Facades\Validator;

class HealthProfileController extends Controller
{

    public function updateHealthProfile(Request $request)
    {
        $rules = [
            'user_id'   => 'required',
            'height_cm' => 'required|numeric|min:50|max:300',   // human height range
            'weight_kg' => 'required|numeric|min:2|max:500',    // human weight range
            'allergies' => 'nullable|array',                    // must be an array if present
            'allergies.*' => 'string|max:255',                  // each allergy string
            'conditions' => 'nullable|array',
            'conditions.*' => 'string|max:255',
            'medications' => 'nullable|array',
            'medications.*.name' => 'required_with:medications|string|max:255',
            'medications.*.dosage' => 'nullable|string|max:255',
        ];

        $userId = $request->user_id;
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        // --- Save or Update Health Profile ---
        $healthProfile = HealthProfile::updateOrCreate(
            ['user_id' => $userId],
            [
                'height_cm' => $request->height_cm,
                'weight_kg' => $request->weight_kg,
            ]
        );

        if ($request->filled('allergies')) {
            Allergy::where('user_id', $userId)->delete(); // clear old
            foreach ($request->allergies as $allergy) {
                Allergy::create([
                    'user_id' => $userId,
                    'allergy' => $allergy,
                ]);
            }
        }

        // --- Save Chronic Conditions ---
        if ($request->filled('conditions')) {
            ChronicCondition::where('user_id', $userId)->delete();
            foreach ($request->conditions as $condition) {
                ChronicCondition::create([
                    'user_id' => $userId,
                    'condition_name' => $condition,
                ]);
            }
        }

        // --- Save Medications ---
        if ($request->filled('medications')) {
            Medication::where('user_id', $userId)->delete();
            foreach ($request->medications as $med) {
                Medication::create([
                    'user_id' => $userId,
                    'medication_name' => $med['name'],
                    'dosage' => $med['dosage'] ?? null,
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Health profile saved successfully',
            'data' => $healthProfile
        ]);
    }


    public function getHealthProfile(Request $request)
    {

        $rules = [
            'userId' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->errors()->all();
            $msg = $messages[0];
            return response()->json(['status' => false, 'message' => $msg]);
        }

        $user = User::with([
            'healthProfile',
            'allergies',
            'chronicConditions',
            'medications'
        ])->find($request->userId);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Health profile fetched successfully',
            'data' => $user
        ]);
    }
}
