# AbleLink File Authorship

This document lists the file authorship across the AbleLink project, organized by feature as defined in the SRS and Feature Mapping.

---

## Team Members

| Student ID | Name |
|:-----------|:-----|
| 23301692 | Farhan Zarif |
| 23301312 | Evan Yuvraj Munshi |
| 23101441 | Tarannum Al Akida |
| 23101446 | Roza Akter |

---

## Feature Attribution Summary

### Sprint 1: Foundation

| Feature | Description | Author |
|---------|-------------|--------|
| F1 | OTP Authentication | Tarannum Al Akida |
| F2 | Role-Based Dashboards & Access Control | Roza Akter |
| F3 | User Profile & Accessibility Preferences | Evan Yuvraj Munshi |
| F4 | Caregiver Dashboard and Management Control | Farhan Zarif |

### Sprint 2: Advanced Accessibility

| Feature | Description | Author |
|---------|-------------|--------|
| F5 | Adaptive UI Framework | Roza Akter |
| F6 | Voice Interaction System (TTS/STT) | Evan Yuvraj Munshi |
| F7 | AI Navigation & Assistance Chatbot | Farhan Zarif |
| F8 | Content Simplification & OCR | Tarannum Al Akida |

### Sprint 3: Employment Platform

| Feature | Description | Author |
|---------|-------------|--------|
| F9 | Accessible Job Search Engine | Evan Yuvraj Munshi |
| F10 | Employer Job Posting & Dashboard | Roza Akter |
| F11 | Accessible Course Library | Tarannum Al Akida |
| F12 | AI Recommendations & Certification | Farhan Zarif |

### Sprint 4: Community Features

| Feature | Description | Author |
|---------|-------------|--------|
| F13 | Accessible Community Forum with AI Moderation | Farhan Zarif |
| F14 | Volunteer Matching System | Roza Akter |
| F15 | Emergency SOS System | Tarannum Al Akida |
| F16 | Community Engagement Platform (Events & Matrimony) | Evan Yuvraj Munshi |

### Sprint 5: Personal Tools & Platform

| Feature | Description | Author |
|---------|-------------|--------|
| F17 | Doctor Appointments Management | Roza Akter |
| F18 | Autonomous AI Agent (Voice Copilot) | Farhan Zarif |
| F19 | Health & Medication Tracking | Evan Yuvraj Munshi |
| F20 | Admin Dashboard & PWA Directory | Tarannum Al Akida |

---

## File Authorship by Directory

### Controllers

#### `app/Http/Controllers/Auth/`
| File | Feature | Author |
|------|---------|--------|
| `LoginController.php` | F1 | Tarannum Al Akida |
| `RegistrationController.php` | F1 | Tarannum Al Akida |
| `OtpController.php` | F1 | Tarannum Al Akida |

#### `app/Http/Controllers/Profile/`
| File | Feature | Author |
|------|---------|--------|
| `ProfileController.php` | F3 | Evan Yuvraj Munshi |
| `AccessibilityController.php` | F3 | Evan Yuvraj Munshi |

#### `app/Http/Controllers/Caregiver/`
| File | Feature | Author |
|------|---------|--------|
| `CaregiverController.php` | F4 | Farhan Zarif |
| `ConnectionController.php` | F4 | Farhan Zarif |
| `DoctorAppointmentController.php` | F17 | Roza Akter |

#### `app/Http/Controllers/User/`
| File | Feature | Author |
|------|---------|--------|
| `DashboardController.php` | F2 | Roza Akter |
| `UserAssistanceController.php` | F14 | Roza Akter |
| `AppointmentController.php` | F17 | Roza Akter |

#### `app/Http/Controllers/Job/`
| File | Feature | Author |
|------|---------|--------|
| `JobController.php` | F9 | Evan Yuvraj Munshi |

#### `app/Http/Controllers/Employer/`
| File | Feature | Author |
|------|---------|--------|
| `EmployerJobController.php` | F10 | Roza Akter |
| `EmployerProfileController.php` | F10 | Roza Akter |
| `InterviewController.php` | F10 | Roza Akter |
| `ReportController.php` | F10 | Roza Akter |

#### `app/Http/Controllers/Candidate/`
| File | Feature | Author |
|------|---------|--------|
| `CandidateApplicationController.php` | F9 | Evan Yuvraj Munshi |

#### `app/Http/Controllers/Volunteer/`
| File | Feature | Author |
|------|---------|--------|
| `AssistanceRequestController.php` | F14 | Roza Akter |
| `VolunteerProfileController.php` | F14 | Roza Akter |

#### `app/Http/Controllers/Course/`
| File | Feature | Author |
|------|---------|--------|
| `CourseLibraryController.php` | F11 | Tarannum Al Akida |

#### `app/Http/Controllers/Education/`
| File | Feature | Author |
|------|---------|--------|
| `CertificateController.php` | F12 | Farhan Zarif |

#### `app/Http/Controllers/Recommendation/`
| File | Feature | Author |
|------|---------|--------|
| `RecommendationController.php` | F12 | Farhan Zarif |

#### `app/Http/Controllers/Document/`
| File | Feature | Author |
|------|---------|--------|
| `DocumentController.php` | F8 | Tarannum Al Akida |

#### `app/Http/Controllers/Community/`
| File | Feature | Author |
|------|---------|--------|
| `ForumController.php` | F13 | Farhan Zarif |
| `MessageController.php` | F13 | Farhan Zarif |
| `CommunityController.php` | F16 | Evan Yuvraj Munshi |
| `CommunityEventController.php` | F16 | Evan Yuvraj Munshi |
| `MatrimonyController.php` | F16 | Evan Yuvraj Munshi |

#### `app/Http/Controllers/Health/`
| File | Feature | Author |
|------|---------|--------|
| `HealthDashboardController.php` | F19 | Evan Yuvraj Munshi |
| `MedicationController.php` | F19 | Evan Yuvraj Munshi |
| `HealthGoalController.php` | F19 | Evan Yuvraj Munshi |
| `HealthMetricController.php` | F19 | Evan Yuvraj Munshi |

#### `app/Http/Controllers/Aid/`
| File | Feature | Author |
|------|---------|--------|
| `AidDirectoryController.php` | F20 | Tarannum Al Akida |

#### `app/Http/Controllers/Emergency/`
| File | Feature | Author |
|------|---------|--------|
| `EmergencySosController.php` | F15 | Tarannum Al Akida |

#### `app/Http/Controllers/Ai/`
| File | Feature | Author |
|------|---------|--------|
| `AiNavigationController.php` | F7, F18 | Farhan Zarif |

#### `app/Http/Controllers/Notification/`
| File | Feature | Author |
|------|---------|--------|
| `NotificationController.php` | F9 | Evan Yuvraj Munshi |

#### `app/Http/Controllers/Admin/`
| File | Feature | Author |
|------|---------|--------|
| `AdminController.php` | F20 | Tarannum Al Akida |
| `AdminDashboardController.php` | F20 | Tarannum Al Akida |
| `AdminStatsController.php` | F20 | Tarannum Al Akida |
| `AdminJobController.php` | F20 | Tarannum Al Akida |
| `CourseController.php` | F11 | Tarannum Al Akida |
| `CourseMediaController.php` | F11 | Tarannum Al Akida |
| `AdminAidProgramController.php` | F20 | Tarannum Al Akida |
| `AdminModerationController.php` | F13/F20 | Farhan Zarif / Tarannum Al Akida |
| `AdminCommunityController.php` | F20 | Tarannum Al Akida |

---

### Models

#### `app/Models/Auth/`
| File | Feature | Author |
|------|---------|--------|
| `User.php` | F1 | Tarannum Al Akida |
| `OtpCode.php` | F1 | Tarannum Al Akida |
| `UserProfile.php` | F3 | Evan Yuvraj Munshi |

#### `app/Models/Employment/`
| File | Feature | Author |
|------|---------|--------|
| `Job.php` | F10 | Roza Akter |
| `JobApplication.php` | F10 | Roza Akter |
| `Interview.php` | F10 | Roza Akter |
| `EmployerProfile.php` | F10 | Roza Akter |

#### `app/Models/Education/`
| File | Feature | Author |
|------|---------|--------|
| `Course.php` | F11 | Tarannum Al Akida |
| `CourseMedia.php` | F11 | Tarannum Al Akida |
| `Certificate.php` | F12 | Farhan Zarif |

#### `app/Models/Community/`
| File | Feature | Author |
|------|---------|--------|
| `ForumThread.php` | F13 | Farhan Zarif |
| `ForumReply.php` | F13 | Farhan Zarif |
| `Message.php` | F13 | Farhan Zarif |
| `Conversation.php` | F13 | Farhan Zarif |
| `AssistanceRequest.php` | F14 | Roza Akter |
| `VolunteerProfile.php` | F14 | Roza Akter |
| `VolunteerMatch.php` | F14 | Roza Akter |
| `CommunityEvent.php` | F16 | Evan Yuvraj Munshi |
| `MatrimonyProfile.php` | F16 | Evan Yuvraj Munshi |

#### `app/Models/Emergency/`
| File | Feature | Author |
|------|---------|--------|
| `EmergencySosEvent.php` | F15 | Tarannum Al Akida |

#### `app/Models/Health/`
| File | Feature | Author |
|------|---------|--------|
| `MedicationSchedule.php` | F19 | Evan Yuvraj Munshi |
| `MedicationLog.php` | F19 | Evan Yuvraj Munshi |
| `HealthGoal.php` | F19 | Evan Yuvraj Munshi |
| `HealthMetric.php` | F19 | Evan Yuvraj Munshi |
| `DoctorAppointment.php` | F17 | Roza Akter |

---

### Services

#### `app/Services/Auth/`
| File | Feature | Author |
|------|---------|--------|
| `OtpManager.php` | F1 | Tarannum Al Akida |

#### `app/Services/Ai/`
| File | Feature | Author |
|------|---------|--------|
| `AiService.php` | F7, F12, F18 | Farhan Zarif |
| `AiModerationService.php` | F13 | Farhan Zarif |
| `Recommendation/RecommendationEngine.php` | F12 | Farhan Zarif |

#### `app/Services/Core/`
| File | Feature | Author |
|------|---------|--------|
| `EncryptionService.php` | F13 | Farhan Zarif |

#### `app/Services/OcrAndSimplify/`
| File | Feature | Author |
|------|---------|--------|
| `DocumentProcessing/DocumentTextExtractor.php` | F8 | Tarannum Al Akida |
| `TextSimplification/TextSimplifier.php` | F8 | Tarannum Al Akida |
| `Ocr/TesseractOcrEngine.php` | F8 | Tarannum Al Akida |

---

### Middleware

#### `app/Http/Middleware/`
| File | Feature | Author |
|------|---------|--------|
| `AdminMiddleware.php` | F20 | Tarannum Al Akida |
| `CheckBanned.php` | F13/F20 | Tarannum Al Akida |
| `ApplyAccessibilityPreferences.php` | F5 | Roza Akter |
| `RoleMiddleware.php` | F2 | Roza Akter |

---

### JavaScript

#### `resources/js/`
| File | Feature | Author |
|------|---------|--------|
| `app.js` | F6 | Evan Yuvraj Munshi |
| `voice-interaction.js` | F6 | Evan Yuvraj Munshi |
| `bootstrap.js` | Core | Laravel |

---

### Views

#### `resources/views/auth/`
| Files | Feature | Author |
|-------|---------|--------|
| `login.blade.php`, `register.blade.php`, `otp.blade.php` | F1 | Tarannum Al Akida |
| `admin-login.blade.php` | F20 | Tarannum Al Akida |

#### `resources/views/profile/`
| Files | Feature | Author |
|-------|---------|--------|
| `show.blade.php`, `edit.blade.php` | F3 | Evan Yuvraj Munshi |

#### `resources/views/accessibility/`
| Files | Feature | Author |
|-------|---------|--------|
| `edit.blade.php` | F3 | Evan Yuvraj Munshi |

#### `resources/views/caregiver/`
| Files | Feature | Author |
|-------|---------|--------|
| `dashboard.blade.php`, `patient-edit.blade.php` | F4 | Farhan Zarif |
| `appointments/*` | F17 | Roza Akter |

#### `resources/views/employer/`
| Files | Feature | Author |
|-------|---------|--------|
| `jobs/*`, `applications/*`, `profile/*`, `interviews/*`, `reports/*` | F10 | Roza Akter |

#### `resources/views/volunteer/`
| Files | Feature | Author |
|-------|---------|--------|
| `requests/*`, `assistance/*`, `profile/*` | F14 | Roza Akter |

#### `resources/views/jobs/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F9 | Evan Yuvraj Munshi |

#### `resources/views/courses/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F11 | Tarannum Al Akida |

#### `resources/views/forum/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F13 | Farhan Zarif |

#### `resources/views/messages/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F13 | Farhan Zarif |

#### `resources/views/community/`
| Files | Feature | Author |
|-------|---------|--------|
| `events/*`, `matrimony/*`, `index.blade.php` | F16 | Evan Yuvraj Munshi |

#### `resources/views/health/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F19 | Evan Yuvraj Munshi |

#### `resources/views/aid/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F20 | Tarannum Al Akida |

#### `resources/views/admin/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F20 | Tarannum Al Akida |

#### `resources/views/documents/`
| Files | Feature | Author |
|-------|---------|--------|
| All files | F8 | Tarannum Al Akida |

#### `resources/views/user/`
| Files | Feature | Author |
|-------|---------|--------|
| `requests.blade.php` | F4 | Farhan Zarif |
| `assistance/*` | F14 | Roza Akter |
| `appointments/*` | F17 | Roza Akter |

#### `resources/views/layouts/`
| Files | Feature | Author |
|-------|---------|--------|
| `auth.blade.php` | F1 | Tarannum Al Akida |
| `dashboard.blade.php` | F2 | Roza Akter |
| `admin.blade.php` | F20 | Tarannum Al Akida |
| `app.blade.php` | Shared | Shared |

#### `resources/views/dashboards/`
| Files | Feature | Author |
|-------|---------|--------|
| `user.blade.php`, `employer.blade.php`, `volunteer.blade.php` | F2 | Roza Akter |
| `admin.blade.php` | F20 | Tarannum Al Akida |

#### `resources/views/emails/`
| Files | Feature | Author |
|-------|---------|--------|
| `otp.blade.php` | F1 | Tarannum Al Akida |
| `sos-alert.blade.php` | F15 | Tarannum Al Akida |

---

### Database Migrations

#### Auth & Users (F1, F3, F4)
| Migration | Feature | Author |
|-----------|---------|--------|
| `create_users_table` | F1 | Tarannum Al Akida |
| `create_otp_codes_table` | F1 | Tarannum Al Akida |
| `create_user_profiles_table` | F3 | Evan Yuvraj Munshi |
| `create_caregiver_user_table` | F4 | Farhan Zarif |
| `add_banned_at_to_users_table` | F13 | Farhan Zarif |

#### Employment (F9, F10)
| Migration | Feature | Author |
|-----------|---------|--------|
| `create_jobs_table` | F10 | Roza Akter |
| `create_employer_jobs_table` | F10 | Roza Akter |
| `create_job_applications_table` | F9/F10 | Evan/Roza |
| `create_employer_profiles_table` | F10 | Roza Akter |
| `create_interviews_table` | F10 | Roza Akter |

#### Education (F11, F12)
| Migration | Feature | Author |
|-----------|---------|--------|
| `create_courses_table` | F11 | Tarannum Al Akida |
| `create_course_media_table` | F11 | Tarannum Al Akida |
| `create_certificates_table` | F12 | Farhan Zarif |
| `create_advanced_recommendation_schema` | F12 | Farhan Zarif |

#### Community (F13, F14, F16)
| Migration | Feature | Author |
|-----------|---------|--------|
| `create_forum_tables` | F13 | Farhan Zarif |
| `create_messaging_tables` | F13 | Farhan Zarif |
| `add_flag_reason_to_forum_tables` | F13 | Farhan Zarif |
| `create_assistance_requests_table` | F14 | Roza Akter |
| `create_volunteer_profiles_table` | F14 | Roza Akter |
| `create_volunteer_matches_table` | F14 | Roza Akter |
| `create_community_events_table` | F16 | Evan Yuvraj Munshi |
| `create_matrimony_profiles_table` | F16 | Evan Yuvraj Munshi |

#### Emergency & Health (F15, F17, F19)
| Migration | Feature | Author |
|-----------|---------|--------|
| `create_emergency_sos_events_table` | F15 | Tarannum Al Akida |
| `create_doctor_appointments_table` | F17 | Roza Akter |
| `create_medication_schedules_table` | F19 | Evan Yuvraj Munshi |
| `create_medication_logs_table` | F19 | Evan Yuvraj Munshi |
| `create_health_goals_table` | F19 | Evan Yuvraj Munshi |
| `create_health_metrics_table` | F19 | Evan Yuvraj Munshi |

#### Admin & Aid (F20)
| Migration | Feature | Author |
|-----------|---------|--------|
| `create_aid_programs_table` | F20 | Tarannum Al Akida |
