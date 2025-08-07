# Date: 30 Nov 2024

## Summary
- Med Reels function added
- AI HealthBot function added
- Appointment Reminders Notifications Added

#### Updated Files
- [AppointmentController.php](app\Http\Controllers\AppointmentController.php)
- [DoctorController.php](app/Http/Controllers/DoctorController.php)
- [SettingsController.php](app/Http/Controllers/SettingsController.php)
- [UsersController.php](app/Http/Controllers/UsersController.php)
- [Constants.php](app/Models/Constants.php)
- [GlobalFunction.php](app/Models/GlobalFunction.php)

- [doctorCategories.js](public/asset/script/doctorCategories.js)
- [settings.js](public/asset/script/settings.js)
- [viewDoctorProfile.js](public/asset/script/viewDoctorProfile.js)

- [settings.blade.php](resources/views/settings.blade.php)
- [viewDoctorProfile.blade.php](resources/views/viewDoctorProfile.blade.php)
- [app.blade.php](resources/views/include/app.blade.php)

- [api.php](routes/api.php)
- [web.php](routes/web.php)

#### Added Files
- [ReelController.php](app/Http/Controllers/ReelController.php)
- [AppointmentReminders.php](app/Models/AppointmentReminders.php)

- [ReelComments.php](app/Models/ReelComments.php)
- [ReelLikes.php](app/Models/ReelLikes.php)
- [ReelReports.php](app/Models/ReelReports.php)
- [Reels.php](app/Models/Reels.php)
- [ScheduledReminders.php](app/Models/ScheduledReminders.php)

- [reels.js](public/asset/script/reels.js)
- [reports.js](public/asset/script/reports.js)

- [reels.blade.php](resources/views/reels.blade.php)
- [reports.blade.php](resources/views/reports.blade.php)

#### Deleted Files
None

#### Database
reels : new table
reel_comments : new table
reel_likes : new table
reel_reports : new table
scheduled_reminders : new table
appointment_reminders : new table
doctors : fields added : saved_reels, device_type
users : fields added : saved_reels
global_settings : fields added : enable_chatbot, chatbot_name, chatbot_thumb, chatgpt_token

------------------------------------
# Date: 16 July 2024

## Summary
-SSLCommerze : Payment Gateway Added

#### Updated Files
- [DoctorController.php](app/Http/Controllers/DoctorController.php)
- [SettingsController.php](app/Http/Controllers/SettingsController.php)
- [Constants.php](app/Models/Constants.php)
- [GlobalFunction.php](app/Models/GlobalFunction.php)

- [settings.blade.php](resources/views/settings.blade.php)
- [viewDoctorProfile.blade.php](resources/views/viewDoctorProfile.blade.php)

#### Added Files
None

#### Deleted Files
None

#### Database
global_settings : fields added :  sslcommerz_store_id, sslcommerz_store_passwd, sslcommerz_currency_code
