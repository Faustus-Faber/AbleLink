@extends('layouts.app')

@section('content')
<a href="#profile-content" class="skip-to-content">Skip to profile content</a>

<section class="profile-page" id="profile-content">
    <div class="profile-header">
        <h1 class="page-title">My Profile & Accessibility Settings</h1>
        <p class="page-subtitle">Manage your personal information and customize AbleLink to work best for you.</p>
    </div>

    <div class="profile-grid">
        <!-- Profile Information -->
        <section class="profile-card" aria-labelledby="profile-info-heading">
            <h2 id="profile-info-heading">Profile Information</h2>
            
            <form method="POST" action="{{ route('profile.update') }}" aria-label="Update profile information">
                @csrf
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required aria-required="true">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled aria-label="Email address (cannot be changed)">
                    <span class="field-hint">Email cannot be changed</span>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+1234567890">
                    <span class="field-hint">For SMS OTP verification</span>
                </div>

                <div class="form-group">
                    <label for="disability_type">Disability Type</label>
                    <input type="text" id="disability_type" name="disability_type" value="{{ old('disability_type', $user->disability_type) }}" list="disability-types">
                    <datalist id="disability-types">
                        <option value="Blind">
                        <option value="Deaf">
                        <option value="Hard of Hearing">
                        <option value="Mobility Impairment">
                        <option value="Cognitive Disability">
                        <option value="Visual Impairment">
                        <option value="Other">
                    </datalist>
                    <span class="field-hint">This helps us recommend accessible opportunities</span>
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" aria-describedby="bio-hint">{{ old('bio', $user->profile?->bio) }}</textarea>
                    <span id="bio-hint" class="field-hint">Tell employers about yourself</span>
                </div>

                <div class="form-group">
                    <label for="skills">Skills</label>
                    <input type="text" id="skills" name="skills" value="{{ old('skills', implode(', ', $user->profile?->skills ?? [])) }}" aria-describedby="skills-hint">
                    <span id="skills-hint" class="field-hint">Comma-separated list of your skills</span>
                </div>

                <button type="submit" class="btn-primary">Save Profile</button>
            </form>
        </section>

        <!-- Accessibility Settings -->
        <section class="profile-card accessibility-settings" aria-labelledby="accessibility-heading">
            <h2 id="accessibility-heading">Accessibility Settings</h2>
            <p class="card-subtitle">Customize how AbleLink looks and works for you.</p>
            
            @php
                $settings = $user->accessibility_settings ?? [
                    'font_size' => 'medium',
                    'contrast' => 'normal',
                    'high_contrast' => false,
                    'spacing' => 'normal',
                    'screen_reader' => false,
                    'reduced_motion' => false,
                    'keyboard_only' => false,
                    'large_fonts' => false,
                ];
            @endphp

            <form method="POST" action="{{ route('accessibility.update') }}" aria-label="Update accessibility settings">
                @csrf

                <fieldset class="form-group">
                    <legend>Text & Font</legend>
                    
                    <div class="form-group">
                        <label for="font_size">Font Size</label>
                        <select id="font_size" name="font_size" required aria-required="true">
                            <option value="small" @selected($settings['font_size'] === 'small')>Small (A-)</option>
                            <option value="medium" @selected($settings['font_size'] === 'medium')>Medium (A)</option>
                            <option value="large" @selected($settings['font_size'] === 'large')>Large (A+)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="large_fonts" value="1" @checked($settings['large_fonts'] ?? false)>
                            Use extra large fonts
                        </label>
                    </div>
                </fieldset>

                <fieldset class="form-group">
                    <legend>Colors & Contrast</legend>
                    
                    <div class="form-group">
                        <label for="contrast">Contrast Level</label>
                        <select id="contrast" name="contrast" required aria-required="true">
                            <option value="normal" @selected($settings['contrast'] === 'normal')>Normal</option>
                            <option value="high" @selected($settings['contrast'] === 'high')>High Contrast</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="high_contrast" value="1" @checked($settings['high_contrast'] ?? false)>
                            Maximum high contrast mode
                        </label>
                    </div>
                </fieldset>

                <fieldset class="form-group">
                    <legend>Layout & Spacing</legend>
                    
                    <div class="form-group">
                        <label for="spacing">Spacing</label>
                        <select id="spacing" name="spacing" required aria-required="true">
                            <option value="normal" @selected($settings['spacing'] === 'normal')>Normal</option>
                            <option value="wide" @selected($settings['spacing'] === 'wide')>Wide spacing</option>
                        </select>
                    </div>
                </fieldset>

                <fieldset class="form-group">
                    <legend>Navigation & Interaction</legend>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="keyboard_only" value="1" @checked($settings['keyboard_only'] ?? false)>
                            Keyboard-only navigation mode
                        </label>
                        <span class="field-hint">Optimize interface for keyboard navigation</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="screen_reader" value="1" @checked($settings['screen_reader'] ?? false)>
                            Screen reader optimized
                        </label>
                        <span class="field-hint">Enhanced ARIA labels and descriptions</span>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="reduced_motion" value="1" @checked($settings['reduced_motion'] ?? false)>
                            Reduce motion and animations
                        </label>
                        <span class="field-hint">Minimize animations for motion sensitivity</span>
                    </div>
                </fieldset>

                <button type="submit" class="btn-primary">Save Accessibility Settings</button>
            </form>
        </section>

        <!-- Caregiver Information -->
        @if($user->primaryCaregiver)
        <section class="profile-card" aria-labelledby="caregiver-heading">
            <h2 id="caregiver-heading">Caregiver Information</h2>
            <dl class="caregiver-details">
                <dt>Name:</dt>
                <dd>{{ $user->primaryCaregiver->name }}</dd>
                <dt>Email:</dt>
                <dd>{{ $user->primaryCaregiver->email }}</dd>
            </dl>
        </section>
        @endif
    </div>
</section>
@endsection





