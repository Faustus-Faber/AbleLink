@extends('layouts.app')

@section('content')
    <section class="auth-section">
        <h1 class="page-title">Create an AbleLink account</h1>
        <p class="page-subtitle">
            For people with disabilities, caregivers, volunteers, and employers.
        </p>

        <form class="card auth-card" method="POST" action="{{ route('register.post') }}" aria-describedby="register-help">
            @csrf

            <p id="register-help" class="sr-only">
                All fields marked with * are required. You can use a screen reader, keyboard only, or high contrast modes.
            </p>

            <div class="form-group">
                <label for="name">Full name *</label>
                <input
                    id="name"
                    name="name"
                    type="text"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email address *</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone number (optional)</label>
                <input
                    id="phone"
                    name="phone"
                    type="tel"
                    value="{{ old('phone') }}"
                    autocomplete="tel"
                    placeholder="+1234567890"
                >
                <span class="field-hint">For SMS OTP verification (optional)</span>
            </div>

            <fieldset class="form-group">
                <legend>Who are you? *</legend>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="role" value="user" @checked(old('role', 'user') === 'user')>
                        User (person with disability)
                    </label>
                    <label>
                        <input type="radio" name="role" value="caregiver" @checked(old('role') === 'caregiver')>
                        Caregiver
                    </label>
                    <label>
                        <input type="radio" name="role" value="volunteer" @checked(old('role') === 'volunteer')>
                        Volunteer
                    </label>
                    <label>
                        <input type="radio" name="role" value="employer" @checked(old('role') === 'employer')>
                        Employer / Recruiter
                    </label>
                </div>
            </fieldset>

            <div class="form-group">
                <label for="disability_type">
                    Disability type
                    <span class="field-hint">(e.g., blind, deaf, hard of hearing, mobility impairment)</span>
                </label>
                <input
                    id="disability_type"
                    name="disability_type"
                    type="text"
                    value="{{ old('disability_type') }}"
                    autocomplete="off"
                >
            </div>

            <div class="form-group">
                <label for="primary_caregiver_email">
                    Caregiver email (optional)
                    <span class="field-hint">If a caregiver will help manage this account, enter their email.</span>
                </label>
                <input
                    id="primary_caregiver_email"
                    name="primary_caregiver_email"
                    type="email"
                    value="{{ old('primary_caregiver_email') }}"
                    autocomplete="off"
                >
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    Sign up with OTP
                </button>

                <p class="form-footer-text">
                    Already have an account?
                    <a href="{{ route('login') }}">Request a new OTP to sign in</a>.
                </p>
            </div>
        </form>
    </section>
@endsection


