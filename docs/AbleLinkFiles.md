# AbleLink Project File Structure

## Application Core (`app/`)

### Console (`app/Console/`)
- `Commands/` (Various scheduled commands)

### Http Controllers (`app/Http/Controllers/`)
- `Controller.php`

**Admin** (`app/Http/Controllers/Admin/`)
- `AdminAidProgramController.php`
- `AdminCommunityController.php`
- `AdminController.php`
- `AdminDashboardController.php`
- `AdminJobController.php`
- `AdminModerationController.php`
- `AdminStatsController.php`
- `CourseController.php`
- `CourseMediaController.php`

**Ai** (`app/Http/Controllers/Ai/`)
- `AiNavigationController.php`

**Aid** (`app/Http/Controllers/Aid/`)
- `AidDirectoryController.php`

**Auth** (`app/Http/Controllers/Auth/`)
- `LoginController.php`
- `OtpController.php`
- `RegistrationController.php`

**Candidate** (`app/Http/Controllers/Candidate/`)
- `CandidateApplicationController.php`

**Caregiver** (`app/Http/Controllers/Caregiver/`)
- `CaregiverController.php`
- `ConnectionController.php`
- `DoctorAppointmentController.php`

**Community** (`app/Http/Controllers/Community/`)
- `CommunityController.php`
- `CommunityEventController.php`
- `ForumController.php`
- `MatrimonyController.php`
- `MessageController.php`

**Course** (`app/Http/Controllers/Course/`)
- `CourseLibraryController.php`

**Document** (`app/Http/Controllers/Document/`)
- `DocumentController.php`

**Education** (`app/Http/Controllers/Education/`)
- `CertificateController.php`

**Emergency** (`app/Http/Controllers/Emergency/`)
- `EmergencySosController.php`

**Employer** (`app/Http/Controllers/Employer/`)
- `EmployerJobController.php`
- `EmployerProfileController.php`
- `InterviewController.php`
- `ReportController.php`

**Health** (`app/Http/Controllers/Health/`)
- `HealthDashboardController.php`
- `HealthGoalController.php`
- `HealthMetricController.php`
- `MedicationController.php`

**Job** (`app/Http/Controllers/Job/`)
- `JobController.php`

**Notification** (`app/Http/Controllers/Notification/`)
- `NotificationController.php`

**Profile** (`app/Http/Controllers/Profile/`)
- `AccessibilityController.php`
- `ProfileController.php`

**Recommendation** (`app/Http/Controllers/Recommendation/`)
- `RecommendationController.php`

**User** (`app/Http/Controllers/User/`)
- `AppointmentController.php`
- `DashboardController.php`
- `UserAssistanceController.php`

**Volunteer** (`app/Http/Controllers/Volunteer/`)
- `AssistanceRequestController.php`
- `VolunteerProfileController.php`

### Middleware (`app/Http/Middleware/`)
- `AdminMiddleware.php`
- `ApplyAccessibilityPreferences.php`
- `CheckBanned.php`
- `RoleMiddleware.php`

### Models (`app/Models/`)
**Auth**
- `OtpCode.php`
- `User.php`
- `UserProfile.php`

**Community**
- `AssistanceRequest.php`
- `CommunityEvent.php`
- `Conversation.php`
- `ForumReply.php`
- `ForumThread.php`
- `MatrimonyProfile.php`
- `Message.php`
- `VolunteerMatch.php`
- `VolunteerProfile.php`

**Education**
- `Certificate.php`
- `Course.php`
- `CourseMedia.php`

**Emergency**
- `EmergencySosEvent.php`

**Employment**
- `EmployerProfile.php`
- `Interview.php`
- `Job.php`
- `JobApplication.php`

**Health**
- `DoctorAppointment.php`
- `HealthGoal.php`
- `HealthMetric.php`
- `MedicationLog.php`
- `MedicationSchedule.php`

### Services (`app/Services/`)
**Ai**
- `AiModerationService.php`
- `AiService.php`
- `Recommendation/RecommendationEngine.php`

**Auth**
- `OtpManager.php`

**Core**
- `EncryptionService.php`

**OcrAndSimplify**
- `DocumentProcessing/DocumentTextExtractor.php`
- `Ocr/TesseractOcrEngine.php`
- `TextSimplification/TextSimplifier.php`

---

## Database (`database/`)

### Migrations (`database/migrations/`)
- `0001_01_01_000000_create_users_table.php`
- `0001_01_01_000002_create_jobs_table.php`
- `2025_01_01_000001_create_user_profiles_table.php`
- `2025_01_01_000002_create_caregiver_user_table.php`
- `2025_01_15_000001_create_employer_jobs_table.php`
- `2025_01_15_000002_create_job_applications_table.php`
- `2025_01_15_000003_create_interviews_table.php`
- `2025_01_15_000004_create_employer_profiles_table.php`
- `2025_01_15_000005_create_volunteer_profiles_table.php`
- `2025_01_15_000006_create_assistance_requests_table.php`
- `2025_01_15_000007_create_volunteer_matches_table.php`
- `2025_12_09_121735_create_otp_codes_table.php`
- `2025_12_20_000001_create_courses_table.php`
- `2025_12_20_000002_create_course_media_table.php`
- `2025_12_24_143809_create_notifications_table.php`
- `2025_12_24_152410_create_advanced_recommendation_schema.php`
- `2025_12_25_000001_create_emergency_sos_events_table.php`
- `2025_12_25_094802_create_forum_tables.php`
- `2025_12_25_094802_create_messaging_tables.php`
- `2025_12_25_185806_create_community_events_table.php`
- `2025_12_25_185809_create_matrimony_profiles_table.php`
- `2025_12_26_033623_add_photo_to_matrimony_profiles_table.php`
- `2025_12_26_041223_add_unique_constraint_to_matrimony_profiles_user_id.php`
- `2025_12_26_045603_add_gender_and_age_to_matrimony_profiles_table.php`
- `2025_12_29_000001_create_aid_programs_table.php`
- `2025_12_29_173434_create_medication_schedules_table.php`
- `2025_12_29_173435_create_medication_logs_table.php`
- `2025_12_29_173436_create_health_goals_table.php`
- `2025_12_29_173436_create_health_metrics_table.php`
- `2025_12_30_062056_add_diagnosis_to_user_profiles_table.php`
- `2025_12_30_120429_add_attachment_to_messages_table.php`
- `2025_12_31_051523_create_doctor_appointments_table.php`
- `2025_12_31_123434_create_certificates_table.php`
- `2025_12_31_125507_add_banned_at_to_users_table.php`
- `2025_12_31_175000_add_flag_reason_to_forum_tables.php`

---

## Resources (`resources/`)

### CSS (`resources/css/`)
- `app.css`

### JavaScript (`resources/js/`)
- `app.js`
- `bootstrap.js`
- `voice-interaction.js`

### Views (`resources/views/`)
**Accessibility**
- `accessibility/edit.blade.php`

**Admin**
- `admin/aid/form.blade.php`
- `admin/aid/index.blade.php`
- `admin/community/index.blade.php`
- `admin/courses/create.blade.php`
- `admin/courses/edit.blade.php`
- `admin/courses/index.blade.php`
- `admin/courses/media/create.blade.php`
- `admin/courses/media/edit.blade.php`
- `admin/dashboard.blade.php`
- `admin/jobs/index.blade.php`
- `admin/moderation/index.blade.php`
- `admin/stats/users.blade.php`

**Aid**
- `aid/index.blade.php`
- `aid/show.blade.php`

**Auth**
- `auth/admin-login.blade.php`
- `auth/login.blade.php`
- `auth/otp.blade.php`
- `auth/register.blade.php`

**Candidate**
- `candidate/applications/index.blade.php`

**Caregiver**
- `caregiver/appointments/create.blade.php`
- `caregiver/appointments/edit.blade.php`
- `caregiver/appointments/index.blade.php`
- `caregiver/dashboard.blade.php`
- `caregiver/patient-edit.blade.php`

**Community**
- `community/events/create.blade.php`
- `community/events/edit.blade.php`
- `community/events/index.blade.php`
- `community/events/show.blade.php`
- `community/index.blade.php`
- `community/matrimony/create.blade.php`
- `community/matrimony/edit.blade.php`
- `community/matrimony/index.blade.php`
- `community/matrimony/show.blade.php`

**Components**
- `components/recommendation-modal.blade.php`

**Courses**
- `courses/index.blade.php`
- `courses/show.blade.php`

**Dashboards**
- `dashboards/admin.blade.php`
- `dashboards/employer.blade.php`
- `dashboards/user.blade.php`
- `dashboards/volunteer.blade.php`

**Documents**
- `documents/result.blade.php`
- `documents/upload.blade.php`

**Education**
- `education/certificate.blade.php`

**Emails**
- `emails/otp.blade.php`
- `emails/sos-alert.blade.php`

**Employer**
- `employer/applications/index.blade.php`
- `employer/interviews/create.blade.php`
- `employer/interviews/index.blade.php`
- `employer/jobs/create.blade.php`
- `employer/jobs/edit.blade.php`
- `employer/jobs/index.blade.php`
- `employer/jobs/show.blade.php`
- `employer/profile/edit.blade.php`
- `employer/profile/show.blade.php`
- `employer/reports/index.blade.php`

**Errors**
- `errors/banned.blade.php`

**Forum**
- `forum/create.blade.php`
- `forum/index.blade.php`
- `forum/show.blade.php`

**Health**
- `health/caregiver/manage.blade.php`
- `health/dashboard.blade.php`
- `health/partials/medication-list.blade.php`

**Jobs**
- `jobs/index.blade.php`
- `jobs/show.blade.php`

**Layouts**
- `layouts/admin.blade.php`
- `layouts/app.blade.php`
- `layouts/auth.blade.php`
- `layouts/dashboard.blade.php`
- `layouts/guest.blade.php`

**Messages**
- `messages/index.blade.php`
- `messages/show.blade.php`

**Notifications**
- `notifications/index.blade.php`

**Partials**
- `partials/footer.blade.php`

**Profile**
- `profile/edit.blade.php`
- `profile/show.blade.php`

**User**
- `user/appointments/index.blade.php`
- `user/assistance/create.blade.php`
- `user/assistance/index.blade.php`
- `user/assistance/show.blade.php`
- `user/requests.blade.php`

**Volunteer**
- `volunteer/assistance/active.blade.php`
- `volunteer/assistance/history.blade.php`
- `volunteer/profile/edit.blade.php`
- `volunteer/profile/show.blade.php`
- `volunteer/requests/index.blade.php`

---

## Routes (`routes/`)
- `api.php`
- `channels.php`
- `console.php`
- `web.php`
