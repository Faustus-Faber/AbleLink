# AbleLink Platform

This repository contains the implementation of the AbleLink platform, a comprehensive accessibility-focused web application.

## 👥 Team Members & Features

### **Tarannum Al Akida (23101441)**
*   **F1: OTP Authentication** - Secure login system using email OTPs with role-based redirection (Admin, User, Caregiver, Employer, Volunteer).
*   **F8: Content Simplification & OCR** - Image-to-text conversion and AI-powered text simplification for cognitive accessibility.
*   **F11: Accessible Course Library** - Educational platform with video/media support for upskilling users.
*   **F15: Emergency SOS System** - One-click emergency alert system that notifies caregivers and admins with location data.
*   **F20: Admin Dashboard & PWA** - Centralized admin control, user management, aid directory, and Progressive Web App (PWA) support.

### **Roza Akter (23101446)**
*   **F2: Role-Based Dashboards** - Dedicated, specialized dashboard layouts for every user role (Volunteer, Employer, Admin, User).
*   **F5: Adaptive UI Framework** - System-wide high-contrast modes, font-scaling, and color-blindness accommodations.
*   **F10: Employer Job Posting** - specialized dashboard for employers to post jobs, manage applications, and schedule interviews.
*   **F14: Volunteer Matching** - System connecting volunteers with disabled users for real-world assistance tasks.
*   **F17: Doctor Appointments** - Scheduler for medical appointments with reminders and calendar views.

### **Evan Yuvraj Munshi (23301312)**
*   **F3: User Profile & Accessibility** - Comprehensive profile management with granular accessibility preference settings.
*   **F6: Voice Interaction (TTS/STT)** - "Hover Reader" that speaks UI text on hover and voice command interface.
*   **F9: Accessible Job Search** - Job board with specialized filters for accessibility requirements.
*   **F16: Community Hub** - Event management system and Matrimony platform for social connection.
*   **F19: Health & Medication** - Tracking tools for daily health metrics, medication schedules, and adherence logs.

### **Farhan Zarif (23301692)**
*   **F4: Caregiver Management** - Delegated access system allowing caregivers to manage patient profiles and settings.
*   **F7: AI Navigation Assistant** - Chat-based AI that can navigate the site and answer context-aware questions.
*   **F12: Recommendations & Certificates** - AI engine matching users to jobs/courses and generating PDF certificates.
*   **F13: Moderator & Messaging** - Safe community forum and private messaging with automated AI toxicity detection.
*   **F18: Autonomous AI Agent** - Advanced "Voice Copilot" that can perform actions (clicking, form filling) on behalf of the user.

---

## 💻 How to Run on PC

Follow these steps to set up the project locally.

### 1. Prerequisites
Ensure you have the following installed:
*   PHP 8.2+
*   Composer
*   Node.js & NPM
*   MySQL

### 2. Initial Setup
Clone the repository and install dependencies:
```bash
composer install
npm install
```

Copy the environment file and generate the application key:
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ablelink
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Migration & Seeding
To set up the database structure and populate it with sample data:
```bash
# Fresh migration with seeders (Recommended for first run)
php artisan migrate:fresh --seed --class=ComprehensiveSeeder
```
*   **Migrating**: `php artisan migrate` runs pending migrations.
*   **Seeding**: `php artisan db:seed` runs seeders to fill tables with test data.

### 4. Storage Linking (Critical for Images)
If images (avatars, course media) are not loading, you need to link the storage. 
**If a storage link already exists, you must UNLINK it first.**

**How to Unlink & Relink:**
```bash
# Windows (Command Prompt)
rmdir /s /q "public\storage"
php artisan storage:link

# Windows (PowerShell)
Remove-Item -Recurse -Force public/storage
php artisan storage:link

# Linux / Mac
rm -rf public/storage
php artisan storage:link
```

### 5. Caching
If you make changes to configurations or routes and they don't reflect, clear the cache:
```bash
# Clear all caches (Config, Route, Cache, View)
php artisan optimize:clear

# Clear specific caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 6. Running the Server
You need two terminals running simultaneously:

**Terminal 1 (Backend):**
```bash
php artisan serve
```

**Terminal 2 (Frontend compilation):**
```bash
npm run dev
```

Visit `http://127.0.0.1:8000` in your browser.

---

## 📚 Documentation
Check the `docs/` folder for:
*   `FeatureMapping.md`: Detailed technical breakdown of every feature.
*   `FileAuthorship.md`: Granular list of files created by each developer.
