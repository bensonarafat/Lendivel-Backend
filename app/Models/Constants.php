<?php

namespace App\Models;

use Illuminate\Validation\Rules\Enum;

final class Constants
{
    // Doctor Sorting
    const sortTypePriceLow = 1;
    const sortTypePriceHigh = 2;
    const sortTypeRating = 3;
    // Gender
    const genderMale = 1;
    const genderFemale = 0;

    // doctor Status
    const statusDoctorPending = 0;
    const statusDoctorApproved = 1;
    const statusDoctorBanned = 2;

    // Doctor vacation
    const doctorNotOnVacation = 0;
    const doctorOnVacation = 1;

    // Tax Type
    const taxPercent = 0;
    const taxFixed = 1;

    // Device Type
    const deviceAndroid = 1;
    const deviceIOS = 2;

    // Payment Gateways
    const addedByAdmin = 2;
    const stripe = 1;
    const razorPay = 3;
    const payStack = 4;
    const payPal = 5;
    const flutterWave = 6;
    const sslCommerze = 7;

    // Credit/Debit
    const credit = 1;
    const debit = 0;

    //User Statement Entries
    const deposit = 0;
    const purchase = 1;
    const withdraw = 2;
    const refund = 3;

    // Notification Type
    const notifyAppointment = 0;
    const notifyReel = 1;

    // Prefixes
    const prefixDoctorNumber = "DR";
    const prefixPlatformEarningHistory = "PLEAR";
    const prefixDoctorEarningHistory = "DREAR";
    const prefixUserWithDrawRequestNumber = "URWTH";
    const prefixDoctorWithDrawRequestNumber = "DRWTH";
    const prefixAppointmentNumber = "APT";
    const prefixDoctorTransactionId = "DRTRID";
    const prefixUserTransactionId = "URTRID";

    // Appointment status
    const orderPlacedPending = 0;
    const orderAccepted = 1;
    const orderCompleted = 2;
    const orderDeclined = 3;
    const orderCancelled = 4;


    // Doctor Statement Entries
    const doctorWalletEarning = 0;
    const doctorWalletCommission = 1;
    const doctorWalletWithdraw = 2;
    const doctorWalletOrderRefund = 3;
    const doctorWalletPayoutReject = 4;


    // Withdrawals Status
    const statusWithdrawalPending = 0;
    const statusWithdrawalCompleted = 1;
    const statusWithdrawalRejected = 2;
}
