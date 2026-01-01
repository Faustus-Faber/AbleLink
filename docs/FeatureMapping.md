# Feature-to-File Mapping

## F1: OTP Authentication
**Feature Author:** Tarannum Al Akida
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/Auth/RegistrationController.php`
- `app/Http/Controllers/Auth/OtpController.php`
- `app/Services/Auth/OtpManager.php`
- `app/Mail/Auth/OtpCodeMail.php`
- `app/Models/Auth/OtpCode.php`
- `app/Models/Auth/User.php` (Shared)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_12_09_121735_create_otp_codes_table.php`
- `database/migrations/2025_12_10_133425_add_otp_fields_to_users_table.php`
- `database/migrations/0001_01_01_000000_create_users_table.php` (Shared Base)

### Views & Layouts
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/otp.blade.php`
- `resources/views/emails/otp.blade.php` (Email Template)
- `resources/views/layouts/auth.blade.php` (Shared Feature Layout)
- `resources/views/partials/footer.blade.php` (Shared Partial)

### Frontend Assets
- `resources/css/app.css` (Shared)
- `public/css/accessibility.css` (Shared)
- `public/css/dashboard.css` (Shared)
- `resources/js/app.js` (Shared)
- `resources/js/bootstrap.js` (Shared)
- `tailwind.config.js` (Shared)
- `vite.config.js` (Shared)

**Feature Full Workflow:**
1. **Registration:** `RegistrationController` creates user, triggers `OtpManager`.
2. **Login:** `LoginController` validates credentials, triggers `OtpManager`.
3. **Verification:** `OtpController` verifies code using `OtpManager`.

---

## F2: Role-Based Dashboards & Access Control
**Feature Author:** Roza Akter
**Feature Files:**
### Core Logic
- `app/Http/Middleware/RoleMiddleware.php`
- `app/Http/Controllers/User/DashboardController.php`
- `app/Models/Auth/User.php` (Shared)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/0001_01_01_000000_create_users_table.php` (Shared)

### Views & Layouts
- `resources/views/dashboards/user.blade.php`
- `resources/views/dashboards/employer.blade.php`
- `resources/views/dashboards/volunteer.blade.php`
- `resources/views/layouts/app.blade.php` (Shared Main Layout)
- `resources/views/layouts/dashboard.blade.php` (Dashboard Layout)
- `resources/views/partials/footer.blade.php` (Shared Partial)

### Frontend Assets
- `resources/css/app.css` (Shared)
- `public/css/accessibility.css` (Shared)
- `public/css/dashboard.css` (Shared)
- `resources/js/app.js` (Shared)
- `tailwind.config.js` (Shared)

**Feature Full Workflow:**
1. **Access Enforcement:** `RoleMiddleware` checks `User` role.
2. **Dashboard Routing:** `DashboardController` routes based on role.
3. **Frontend:** `layouts/app.blade.php` adapts UI based on role.

---

## F3: User Profile & Accessibility Preferences
**Feature Author:** Evan Yuvraj Munshi
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Profile/ProfileController.php`
- `app/Http/Controllers/Profile/AccessibilityController.php`
- `app/Models/Auth/UserProfile.php`
- `app/Models/Auth/User.php` (Shared)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_01_01_000001_create_user_profiles_table.php`
- `database/migrations/2025_12_12_000001_add_f3_fields_to_user_profiles_table.php`

### Views & Layouts
- `resources/views/profile/show.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/accessibility/edit.blade.php`
- `resources/views/layouts/app.blade.php` (Shared Main Layout)
- `resources/views/partials/footer.blade.php` (Shared Partial)

### Frontend Assets
- `resources/css/app.css` (Shared)
- `public/css/accessibility.css` (Shared)
- `resources/js/app.js` (Shared)
- `tailwind.config.js` (Shared)

**Feature Full Workflow:**
1. **Profile Management:** `ProfileController` updates `User` and `UserProfile`.
2. **Accessibility:** `AccessibilityController` stores prefs in `UserProfile` and Session.
3. **UI Sync:** Layout applies prefs from Session.

---

## F4: Caregiver Dashboard and Management Control
**Feature Author:** Farhan Zarif
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Caregiver/CaregiverController.php`
- `app/Http/Controllers/Caregiver/ConnectionController.php` (User Side Approval)
- `app/Models/Auth/User.php` (Shared - Patients Relationship)
- `app/Models/Emergency/EmergencySosEvent.php` (Shared - Used/Resolved by Caregiver)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_01_01_000002_create_caregiver_user_table.php` (Pivot Table)

### Views & Layouts
- `resources/views/caregiver/dashboard.blade.php`
- `resources/views/caregiver/patient-edit.blade.php`
- `resources/views/user/requests.blade.php` (User Connection Requests)
- `resources/views/layouts/app.blade.php` (Shared Main Layout)
- `resources/views/partials/footer.blade.php` (Shared Partial)

### Frontend Assets
- `resources/css/app.css` (Shared)
- `public/css/accessibility.css` (Shared)
- `public/css/dashboard.css` (Shared)
- `resources/js/app.js` (Shared)
- `tailwind.config.js` (Shared)

**Feature Full Workflow:**
1. **Dashboard:** `CaregiverController@index` aggregates patient list, SOS alerts, and appointments.
2. **Connection Request:** `CaregiverController@sendRequest` creates `pending` record in `caregiver_user`.
3. **Approval:** User approves request via `ConnectionController` (`requests.blade.php`).
4. **Patient Management:** `CaregiverController@updatePatient` modifies `UserProfile`.
5. **Emergency Resolution:** `CaregiverController@resolveSos` updates `EmergencySosEvent`.

---

## F5: Adaptive UI Framework
**Feature Author:** Roza Akter
**Feature Files:**
### Core Logic
- `app/Http/Middleware/ApplyAccessibilityPreferences.php`
- `resources/views/layouts/app.blade.php` (Shared - Contains F5 UI Logic and `speak()` function)
- `routes/web.php` (Shared)

### Database Migrations
- (None - Relies on F3 usage of `user_profiles` table)

### Views & Layouts
- `resources/views/layouts/app.blade.php` (Main Implementation)
- `resources/views/partials/footer.blade.php` (Shared Partial)

### Frontend Assets
- `public/css/accessibility.css` (Core F5 Stylesheet)
- `resources/js/app.js` (Shared)

**Feature Full Workflow:**
1. **Middleware Injection:** `ApplyAccessibilityPreferences` reads session/profile prefs and shares `$bodyClasses` string.
2. **CSS Adaptation:** `layouts/app.blade.php` applies the `$bodyClasses` to `<body>`. `accessibility.css` defines overrides.
3. **Text-to-Speech:** `layouts/app.blade.php` defines a global `speak(text)` function using `window.ableLinkPrefs`.

---

## F6: Voice Interaction System (TTS/STT)
**Feature Author:** Evan Yuvraj Munshi
**Feature Files:**
### Core Logic
- `resources/js/voice-interaction.js` (Javascript Class for Hover Reader)
- `resources/js/app.js` (Shared - Integration)
- `resources/views/layouts/app.blade.php` (Shared - Context Injection `ableLinkIsDisabled`)
- `routes/web.php` (Shared)

### Database Migrations
- (None)

### Views & Layouts
- `resources/views/layouts/app.blade.php` (Integration)
- `resources/views/partials/footer.blade.php` (Shared - if active)

### Frontend Assets
- `resources/js/voice-interaction.js`
- `resources/js/app.js`

**Feature Full Workflow:**
1. **Initialization:** `app.js` imports `voice-interaction.js`.
2. **Context Check:** Script checks `window.ableLinkIsDisabled` (injected by Layout/F5 logic).
3. **Activation:** If disabled, `VoiceInteraction` class creates a floating "Hover Reader" widget.
4. **Interaction:** User toggles "Reader Mode". Script detects hover events on text elements (p, h1, etc.) and uses `SpeechSynthesis` to read content.

---

## F7: AI Navigation & Assistance Chatbot
**Feature Author:** Farhan Zarif
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Ai/AiNavigationController.php` (Handling Chat API)
- `app/Services/Ai/AiService.php` (RAG, Tool Execution, Routing Context)
- `routes/web.php` (Shared)

### Database Migrations
- (None - Chat is session-based or stateless).

### Views & Layouts
- `resources/views/layouts/app.blade.php` (Contains Chat Widget HTML and JS Logic)

### Frontend Assets
- `resources/js/app.js` (Shared)
- `resources/views/layouts/app.blade.php` (JS Block for `fetch('{{ route('ai.chat') }}')`)

**Feature Full Workflow:**
1. **User Input:** User Types/Speaks in Chat Widget (`app.blade.php`).
2. **Request:** JS sends POST to `ai.chat`.
3. **Processing:** `AiNavigationController` calls `AiService`. `AiService` builds context, determines intent, and executes tools/methods.
4. **Action execution:** returns JSON. JS executes `window.location.href = ...` for navigation or calls `speak()` for feedback.

---

## F8: Content Simplification & OCR
**Feature Author:** Tarannum Al Akida
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Document/DocumentController.php`
- `app/Services/OcrAndSimplify/DocumentProcessing/DocumentTextExtractor.php` (OCR Service)
- `app/Services/OcrAndSimplify/DocumentProcessing/ExtractionResult.php` (DTO)
- `app/Services/OcrAndSimplify/TextSimplification/TextSimplifier.php` (Simplification Service)
- `app/Services/OcrAndSimplify/TextSimplification/SimplifiedText.php` (DTO)
- `app/Services/OcrAndSimplify/Ocr/OcrEngine.php` (Interface)
- `app/Services/OcrAndSimplify/Ocr/TesseractOcrEngine.php` (Implementation)
- `routes/web.php` (Shared)

### Database Migrations
- (None - Files are processed temporarily, no DB storage for this feature).

### Views & Layouts
- `resources/views/documents/upload.blade.php`
- `resources/views/documents/result.blade.php`
- `resources/views/layouts/app.blade.php` (Shared Main Layout)

### Frontend Assets
- `resources/css/app.css` (Shared)
- `resources/js/app.js` (Shared)

**Feature Full Workflow:**
1. **Upload:** User uploads document/image at `/upload`.
2. **Extraction:** `DocumentController` uses `DocumentTextExtractor` to get raw text (OCR).
3. **Simplification:** If selected, `TextSimplifier` calls AI to generate simplified text/bullets.
4. **Display:** Results shown in `result.blade.php`.

---

## F9: Accessible Job Search Engine
**Feature Author:** Evan Yuvraj Munshi
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Job/JobController.php` (Search & Filtering)
- `app/Models/Employment/Job.php`
- `app/Http/Controllers/Candidate/CandidateApplicationController.php` (Application Tracking)
- `app/Models/Employment/JobApplication.php`
- `app/Http/Controllers/Notification/NotificationController.php` (System-wide User Notifications)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/0001_01_01_000002_create_jobs_table.php`
- `database/migrations/2025_01_15_000002_create_job_applications_table.php`
- `database/migrations/2025_12_24_143809_create_notifications_table.php`

### Views & Layouts
- `resources/views/jobs/index.blade.php` (Search Interface)
- `resources/views/jobs/show.blade.php` (Job Details)
- `resources/views/candidate/applications/index.blade.php`
- `resources/views/notifications/index.blade.php` (Notification Center)

### Frontend Assets
- `resources/css/app.css` (Shared)
- `resources/js/app.js` (Shared)

**Feature Full Workflow:**
1. **Search:** `JobController@index` queries `Job` model with accessibility filters.
2. **View:** `JobController@show` displays job details.
3. **Apply:** `JobController@apply` handles CV upload and creates `JobApplication`.
4. **My Apps:** `CandidateApplicationController@index` shows user's application history.

---

## F10: Employer Job Posting & Dashboard
**Feature Author:** Roza Akter
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Employer/EmployerJobController.php` (CRUD)
- `app/Http/Controllers/Employer/EmployerProfileController.php`
- `app/Http/Controllers/Employer/InterviewController.php`
- `app/Http/Controllers/Employer/ReportController.php` (Analytics)
- `app/Models/Employment/Job.php` (Author)
- `app/Models/Employment/JobApplication.php`
- `app/Models/Employment/EmployerProfile.php`
- `app/Models/Employment/Interview.php`
- `app/Notifications/Employment/ApplicationStatusChanged.php` (Candidate Alert)
- `app/Notifications/Employment/InterviewScheduled.php` (Interview Invite)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/0001_01_01_000002_create_jobs_table.php` (Shared)
- `database/migrations/2025_01_15_000001_create_employer_jobs_table.php`
- `database/migrations/2025_01_15_000002_create_job_applications_table.php`
- `database/migrations/2025_01_15_000003_create_interviews_table.php`
- `database/migrations/2025_01_15_000004_create_employer_profiles_table.php`

### Views & Layouts
- `resources/views/employer/jobs/create.blade.php`
- `resources/views/employer/jobs/edit.blade.php`
- `resources/views/employer/jobs/index.blade.php`
- `resources/views/employer/jobs/show.blade.php`
- `resources/views/employer/applications/index.blade.php`
- `resources/views/employer/profile/edit.blade.php`
- `resources/views/employer/profile/show.blade.php`
- `resources/views/employer/reports/index.blade.php`
- `resources/views/employer/interviews/create.blade.php`
- `resources/views/employer/interviews/index.blade.php`

### Frontend Assets
- `resources/css/app.css` (Shared)
- `public/css/dashboard.css` (Shared)
- `resources/js/app.js` (Shared)

**Feature Full Workflow:**
1. **Posting:** `EmployerJobController@store` creates a `Job`.
2. **Dashboard:** `EmployerJobController@index` lists jobs with application counts.
3. **Management:** `EmployerJobController@updateApplicationStatus` moves applications through stages.
4. **Interviews:** `InterviewController` schedules interviews for applicants.

---

## F11: Accessible Course Library
**Feature Author:** Tarannum Al Akida
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Course/CourseLibraryController.php` (User View)
- `app/Http/Controllers/Admin/CourseController.php` (Admin Management)
- `app/Http/Controllers/Admin/CourseMediaController.php` (Admin Media)
- `app/Models/Education/Course.php` (Model)
- `app/Models/Education/CourseMedia.php` (Model)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_12_20_000001_create_courses_table.php`
- `database/migrations/2025_12_20_000002_create_course_media_table.php`

### Views & Layouts
- `resources/views/courses/index.blade.php`
- `resources/views/courses/show.blade.php`
- `resources/views/admin/courses/create.blade.php`
- `resources/views/admin/courses/edit.blade.php`
- `resources/views/admin/courses/index.blade.php`
- `resources/views/admin/courses/media/create.blade.php`
- `resources/views/admin/courses/media/edit.blade.php`

### Frontend Assets
- `resources/css/app.css` (Shared)
- `resources/js/app.js` (Shared)

**Feature Full Workflow:**
1. **Admin Creation:** `Admin/CourseController` creates `Course`. `CourseMediaController` adds videos/lessons.
2. **Public View:** `CourseLibraryController@index` lists courses.
3. **Consumption:** `CourseLibraryController@show` displays course content/media.

---

## F12: AI Recommendations & Certification
**Feature Author:** Farhan Zarif
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Recommendation/RecommendationController.php`
- `app/Http/Controllers/Education/CertificateController.php` (Certification Logic)
- `app/Services/Ai/Recommendation/RecommendationEngine.php` (AI Logic)
- `app/Models/Auth/User.php` (Fields: skills, interests)
- `app/Models/Employment/Job.php` (Fields: skills_required, embedding_vector)
- `app/Models/Education/Course.php` (Fields: category, tags)
- `app/Models/Education/Certificate.php` (Model)
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_12_31_123434_create_certificates_table.php`
- `database/migrations/2025_12_24_152410_create_advanced_recommendation_schema.php`
- (Columns added via F12 specific migrations or added to existing tables. Code checks `skills_required` on Jobs and `category` on Courses).

### Views & Layouts
- `resources/views/components/recommendation-modal.blade.php` (Shared Component)
- `resources/views/jobs/index.blade.php` (Host Integration)
- `resources/views/courses/index.blade.php` (Host Integration)
- `resources/views/education/certificate.blade.php` (PDF Template)

### Frontend Assets
- `resources/js/app.js` (Shared - AlpineJS logic often inline or shared)

**Feature Full Workflow:**
1. **Trigger:** User clicks "Smart Recommendations" in Jobs/Courses view.
2. **API Call:** Modal calls `RecommendationController@getJobs` / `getCourses`.
3. **Execution:** `RecommendationEngine` builds prompt details (User Skills vs Job Descriptions), calls LLM (Gemini/OpenAI), and returns scored list.
4. **Display:** Modal renders matching items with AI-generated explanations.
5. **Certification:** Upon course completion, `CertificateController` is triggered (via button in `courses/show`).
6. **Generation:** `AiService@generateCertificateMessage` creates a personal message. `DomPDF` renders `education/certificate.blade.php` into a downloadable PDF.

---

## F13: Accessible Community Forum with AI Moderation and Private Messaging
**Feature Author:** Farhan Zarif
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Community/MessageController.php` (Private)
- `app/Http/Controllers/Community/ForumController.php` (Public)
- `app/Http/Controllers/Admin/AdminModerationController.php` (Admin - Flag Review & User Banning)
- `app/Services/Ai/AiModerationService.php` (AI Logic)
- `app/Services/Core/EncryptionService.php` (Message Encryption)
- `app/Http/Middleware/CheckBanned.php` (Enforcement)
- `app/Models/Community/Conversation.php`
- `app/Models/Community/Message.php`
- `app/Models/Community/ForumThread.php`
- `app/Models/Community/ForumReply.php`
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_12_25_094802_create_forum_tables.php`
- `database/migrations/2025_12_25_094802_create_messaging_tables.php`
- `database/migrations/2025_12_30_120429_add_attachment_to_messages_table.php`
- `database/migrations/2025_12_31_175000_add_flag_reason_to_forum_tables.php`
- `database/migrations/2025_12_31_125507_add_banned_at_to_users_table.php`

### Views & Layouts
- `resources/views/messages/index.blade.php`
- `resources/views/messages/show.blade.php`
- `resources/views/forum/create.blade.php`
- `resources/views/forum/index.blade.php`
- `resources/views/forum/show.blade.php`
- `resources/views/admin/moderation/index.blade.php`
- `resources/views/errors/banned.blade.php` (User Ban Screen)

### Frontend Assets
- `resources/css/app.css` (Shared)

**Feature Full Workflow:**
1. **Creation:** User sends message (Private) or posts thread (Forum).
2. **Moderation:** `AiModerationService` checks content (F13 logic). If flagged, implementation blocks or flags for review.
3. **Delivery:** Message stored in DB.
4. **Admin:** Admin reviews flagged/reported content via `AdminModerationController`. Admin can ban/unban users for violations; `CheckBanned` middleware enforces this restriction.

---

## F14: Volunteer Matching System
**Feature Author:** Roza Akter
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Volunteer/AssistanceRequestController.php` (Volunteer View)
- `app/Http/Controllers/Volunteer/VolunteerProfileController.php`
- `app/Http/Controllers/User/UserAssistanceController.php` (User View - Creation)
- `app/Models/Community/AssistanceRequest.php`
- `app/Models/Community/VolunteerMatch.php`
- `app/Models/Community/VolunteerProfile.php`
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_01_15_000006_create_assistance_requests_table.php`
- `database/migrations/2025_01_15_000007_create_volunteer_matches_table.php`
- `database/migrations/2025_01_15_000005_create_volunteer_profiles_table.php`

### Views & Layouts
- `resources/views/volunteer/requests/index.blade.php`
- `resources/views/volunteer/assistance/active.blade.php`
- `resources/views/volunteer/assistance/history.blade.php`
- `resources/views/user/assistance/create.blade.php`
- `resources/views/user/assistance/index.blade.php`
- `resources/views/user/assistance/show.blade.php`
- `resources/views/volunteer/profile/edit.blade.php`
- `resources/views/volunteer/profile/show.blade.php`

### Frontend Assets
- `resources/css/app.css` (Shared)

**Feature Full Workflow:**
1. **Request:** Disabled User creates request via `UserAssistanceController`.
2. **Matching:** Volunteer browses via `AssistanceRequestController`.
3. **Acceptance:** Volunteer accepts -> `VolunteerMatch` created.
4. **Completion:** Volunteer/User marks complete.

---

## F15: Emergency SOS
**Feature Author:** Tarannum Al Akida
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Emergency/EmergencySosController.php`
- `app/Models/Emergency/EmergencySosEvent.php`
- `app/Mail/Emergency/EmergencySosAlertMail.php`
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_12_25_000001_create_emergency_sos_events_table.php`

### Views & Layouts
- `resources/views/layouts/app.blade.php` (Trigger UI integration)
- `resources/views/profile/show.blade.php` (Status view)
- `resources/views/emails/sos-alert.blade.php` (Email)

### Frontend Assets
- `resources/js/app.js` (Shared - Geolocation logic likely here or inline)

**Feature Full Workflow:**
1. **Trigger:** User presses SOS button in `app.blade.blade.php`.
2. **Processing:** `EmergencySosController@store` captures location/notes, creates `EmergencySosEvent`.
3. **Notification:** Controller queues/sends `EmergencySosAlertMail` to Admins/Caregivers.
4. **Resolution:** Admin/Caregiver resolves event via Dashboard.

---

## F16: Community Hub, Events, Matrimony
**Feature Author:** Evan Yuvraj Munshi
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Community/CommunityController.php` (Hub)
- `app/Http/Controllers/Community/MatrimonyController.php` (Matrimony)
- `app/Http/Controllers/Community/CommunityEventController.php` (Events)
- `app/Models/Community/MatrimonyProfile.php`
- `app/Models/Community/CommunityEvent.php`
- `routes/web.php` (Shared)

### Database Migrations
- `database/migrations/2025_12_25_185809_create_matrimony_profiles_table.php`
- `database/migrations/2025_12_25_185806_create_community_events_table.php`
- `database/migrations/2025_12_26_033623_add_photo_to_matrimony_profiles_table.php`
- `database/migrations/2025_12_26_041223_add_unique_constraint_to_matrimony_profiles_user_id.php`
- `database/migrations/2025_12_26_045603_add_gender_and_age_to_matrimony_profiles_table.php`

### Views & Layouts
- `resources/views/community/index.blade.php`
- `resources/views/community/matrimony/create.blade.php`
- `resources/views/community/matrimony/edit.blade.php`
- `resources/views/community/matrimony/index.blade.php`
- `resources/views/community/matrimony/show.blade.php`
- `resources/views/community/events/create.blade.php`
- `resources/views/community/events/index.blade.php`
- `resources/views/community/events/show.blade.php`

### Frontend Assets
- `resources/css/app.css` (Shared)

**Feature Full Workflow:**
1. **Matrimony:** User creates profile (`MatrimonyController@store`).
2. **Browsing:** Users browse profiles/events.
3. **Events:** Admin/Organizer posts events via `CommunityEventController`.

---

## F17: Doctor Appointments & Management
**Feature Author:** Roza Akter
**Feature Files:**
### Core Logic
- `app/Http/Controllers/User/AppointmentController.php` (User View)
- `app/Http/Controllers/Caregiver/DoctorAppointmentController.php` (Caregiver Management)
- `app/Models/Health/DoctorAppointment.php`
- `app/Notifications/Health/AppointmentScheduled.php`
- `app/Notifications/Health/AppointmentReminder.php`
- `routes/web.php` (Shared)
- `routes/console.php` (Scheduled: appointments:check-reminders)

### Database Migrations
- `database/migrations/2025_12_31_051523_create_doctor_appointments_table.php`

### Views & Layouts
- `resources/views/caregiver/appointments/create.blade.php`
- `resources/views/caregiver/appointments/edit.blade.php`
- `resources/views/caregiver/appointments/index.blade.php`
- `resources/views/user/appointments/index.blade.php`

### Frontend Assets
- `resources/js/app.js` (Shared - FullCalendar integration)

**Feature Full Workflow:**
1. **Scheduling:** Caregiver schedules appointment via `DoctorAppointmentController@store`.
2. **Notification:** User notified (System/Email).
3. **Viewing:** Caregiver sees calendar view (JSON feed from `getCalendarData`). User sees list view.

---

## F18: Autonomous AI Agent (Voice Copilot)
**Feature Author:** Farhan Zarif
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Ai/AiNavigationController.php` (Extends F7 with `upload`, `serveFile` for AI)
- `app/Services/Ai/AiService.php` (Voice Summary Logic & Tool Definitions)
- `routes/web.php` (Shared)

### Database Migrations
- (None)

### Views & Layouts
- `resources/views/layouts/app.blade.php` (Integration in Chat Widget)

### Frontend Assets
- `resources/js/voice-interaction.js` (Shared with F6 - TTS/Voice Interface)
- `resources/js/app.js` (Shared)

**Feature Full Workflow:**
1. **Perception (Vision):** `app.blade.php` uses `serializePage()` to scan the DOM. It captures visible text, modals, and interactive elements (buttons, inputs), assigning temporary IDs (`#ai-gen-N`) to headless elements.
2. **Analysis:** `AiService` receives `page_structure` & user message. It uses the LLM to understand intent and page context (via `page_dom`).
3. **Tool Execution:** The Agent selects tools to perform actions:
    - `navigate`: Moves to a new URL.
    - `click_element`: Clicks buttons/links via ID.
    - `fill_input` / `fill_form`: Fills data into fields (handles checkboxes & selects).
    - `upload_file`: Processes attachments via `AiNavigationController@upload`.
    - `read_page`: Generates a spoken summary of the page.
    - `store_field`: Manages interactive form filling session.
    - `confirm_action`: Intercepts destructive actions for user approval.
4. **Response:** Returns a text reply and/or a `voice_summary` which is spoken via `voice-interaction.js`. Actions (like clicks) are executed by the frontend (`app.blade.php` script).

---

## F19: Caregiver Health Management
**Feature Author:** Evan Yuvraj Munshi
**Feature Files:**
### Core Logic
- `app/Http/Controllers/Health/HealthDashboardController.php`
- `app/Http/Controllers/Health/MedicationController.php`
- `app/Http/Controllers/Health/HealthGoalController.php`
- `app/Http/Controllers/Health/HealthMetricController.php`
- `app/Models/Health/MedicationSchedule.php`
- `app/Models/Health/HealthGoal.php`
- `app/Models/Health/HealthMetric.php`
- `app/Models/Health/MedicationLog.php`
- `app/Notifications/Health/MissedMedicationNotification.php`
- `routes/web.php` (Shared)
- `routes/console.php` (Scheduled: health:check-missed)

### Database Migrations
- `database/migrations/2025_12_29_173434_create_medication_schedules_table.php`
- `database/migrations/2025_12_29_173435_create_medication_logs_table.php`
- `database/migrations/2025_12_29_173436_create_health_goals_table.php`
- `database/migrations/2025_12_29_173436_create_health_metrics_table.php`
- `database/migrations/2025_12_30_062056_add_diagnosis_to_user_profiles_table.php`

### Views & Layouts
- `resources/views/health/dashboard.blade.php`
- `resources/views/health/caregiver/manage.blade.php`
- `resources/views/health/partials/medication-list.blade.php`

### Frontend Assets
- `resources/css/app.css` (Shared)
- `resources/js/app.js` (Shared - Chart.js for metrics)

**Feature Full Workflow:**
1. **Setup:** Caregiver adds medication/goals via `MedicationController`/`HealthGoalController`.
2. **Tracking:** User/Caregiver logs intake/metrics.
3. **Analysis:** `HealthDashboardController` displays adherence and charts.

---

## F20: Admin Dashboard & PWA Directory
**Feature Author:** Tarannum Al Akida
**Feature Files:**
### Core Logic
- `app/Http/Middleware/AdminMiddleware.php` (Admin Access Control)
- `app/Http/Controllers/Admin/AdminController.php` (Dashboard Base)
- `app/Http/Controllers/Admin/AdminDashboardController.php`
- `app/Http/Controllers/Admin/AdminStatsController.php` (User Management: Users, Volunteers, Employers, Caregivers, Jobs)
- `app/Http/Controllers/Admin/AdminCommunityController.php` (Community Moderation)
- `app/Http/Controllers/Admin/AdminJobController.php` (Job Management)
- `app/Http/Controllers/Admin/AdminAidProgramController.php` (Aid CRUD)
- `app/Http/Controllers/Aid/AidDirectoryController.php` (Public Directory)

### Database Migrations
- `database/migrations/2025_12_29_000001_create_aid_programs_table.php`

### Views & Layouts
- `resources/views/auth/admin-login.blade.php` (Admin Login)
- `resources/views/layouts/admin.blade.php` (Admin Layout)
- `resources/views/dashboards/admin.blade.php` (Main Dashboard)
- `resources/views/admin/stats/count.blade.php`
- `resources/views/admin/stats/user-form.blade.php`
- `resources/views/admin/stats/users.blade.php` (User Lists)
- `resources/views/admin/community/index.blade.php` (Forum Management)
- `resources/views/admin/jobs/index.blade.php` (Job Management)
- `resources/views/admin/aid/form.blade.php`
- `resources/views/admin/aid/index.blade.php`
- `resources/views/aid/index.blade.php` (Public View)
- `resources/views/aid/show.blade.php` (Public View)

### Frontend Assets
- `public/manifest.json` (PWA Manifest)
- `public/service-worker.js` (PWA Worker)
- `public/offline.html` (PWA Offline Page)
- `public/css/dashboard.css` (Admin Dashboard Styles)

**Feature Full Workflow:**
1. **Admin Dashboard:** Admin logs in to view stats (Jobs, Users). Managed by `AdminController`.
2. **User Management:** Admin views/adds/deletes Users, Volunteers, Employers, Caregivers via `AdminStatsController` (`/admin/users`, etc.).
3. **Content Management:** Admin manages Community Threads (`AdminCommunityController`) and Jobs (`AdminJobController`).
4. **Aid Directory:** Admin manages Aid Programs (CRUD) via `AdminAidProgramController`.
5. **Public Access:** Users browse Aid Directory (`AidDirectoryController`).
6. **PWA:** App is installable via `manifest.json` and works offline via `service-worker.js`.
