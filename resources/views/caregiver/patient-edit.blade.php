@extends('layouts.app')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<section 
    id="caregiver-patient-edit-container" 
    class="max-w-5xl mx-auto py-8 px-4"
>
    <header id="caregiver-patient-edit-navigation-header" class="mb-8">
        <a 
            id="caregiver-back-to-dashboard-btn"
            href="{{ route('caregiver.dashboard') }}" 
            class="group inline-flex items-center text-slate-500 hover:text-indigo-600 font-medium transition-colors"
        >
            <figure class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-3 group-hover:border-indigo-200 group-hover:bg-indigo-50 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </figure>
            Back to Dashboard
        </a>
    </header>

    <article 
        id="caregiver-patient-edit-card"
        class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden"
    >
        <header 
            id="caregiver-patient-edit-banner"
            class="bg-indigo-900 px-8 py-8 relative overflow-hidden"
        >
            <figure class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-48 h-48 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </figure>
            <section class="relative z-10">
                <h1 
                    id="caregiver-patient-edit-title"
                    class="text-3xl font-extrabold text-white tracking-tight mb-2"
                >
                    Manage Profile
                </h1>
                <section 
                    id="caregiver-patient-edit-subtitle"
                    class="flex items-center text-indigo-200 font-medium"
                >
                    <span class="bg-indigo-800/50 px-3 py-1 rounded-lg border border-indigo-700/50 mr-3">
                        {{ $user->name }}
                    </span>
                    <span>Update personal details & accessibility preferences</span>
                </section>
            </section>
        </header>

        <form 
            id="caregiver-patient-edit-form-element"
            action="{{ route('caregiver.patient.update', $user) }}" 
            method="POST" 
            class="px-8 pb-8 pt-6 md:px-10 md:pb-10 md:pt-8 space-y-10"
        >
            @csrf
            @method('PUT')

            <section id="caregiver-patient-personal-info-section">
                <header class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-100">
                    <figure class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </figure>
                    <section>
                        <h2 class="text-xl font-bold text-slate-900">Personal Information</h2>
                        <p class="text-sm text-slate-500">Basic details for identification and contact.</p>
                    </section>
                </header>

                <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <section id="caregiver-patient-name-field-container">
                        <label 
                            id="caregiver-patient-name-label"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                        >
                            Full Name
                        </label>
                        <section class="px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-600 font-medium flex items-center justify-between">
                            {{ $user->name }}
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </section>
                    </section>

                    <section id="caregiver-patient-email-field-container">
                        <label 
                            id="caregiver-patient-email-label"
                            class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2"
                        >
                            Email Address
                        </label>
                        <section class="px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-slate-600 font-medium flex items-center justify-between">
                            {{ $user->email }}
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </section>
                    </section>

                    <section id="caregiver-patient-phone-field-container">
                        <label 
                            id="caregiver-patient-phone-input-label"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2" 
                            for="caregiver-patient-phone-input"
                        >
                            Phone Number
                        </label>
                        <input 
                            id="caregiver-patient-phone-input"
                            type="tel" 
                            name="phone_number" 
                            class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm"
                            value="{{ old('phone_number', $user->profile->phone_number ?? '') }}"
                            placeholder="+1 (555) 000-0000"
                        >
                    </section>

                    <section id="caregiver-patient-dob-field-container">
                        <label 
                            id="caregiver-patient-dob-input-label"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2" 
                            for="caregiver-patient-dob-input"
                        >
                            Date of Birth
                        </label>
                        <input 
                            id="caregiver-patient-dob-input"
                            type="date" 
                            name="date_of_birth" 
                            class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm"
                            value="{{ old('date_of_birth', $user->profile && $user->profile->date_of_birth ? $user->profile->date_of_birth->format('Y-m-d') : '') }}"
                        >
                    </section>

                    <section 
                        id="caregiver-patient-bio-field-container"
                        class="md:col-span-2"
                    >
                        <label 
                            id="caregiver-patient-bio-textarea-label"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2" 
                            for="caregiver-patient-bio-textarea"
                        >
                            Biography / About
                        </label>
                        <textarea 
                            id="caregiver-patient-bio-textarea"
                            name="bio" 
                            rows="3"
                            class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm resize-none"
                            placeholder="Share important details about {{ $user->name }}'s background or needs..."
                        >{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                    </section>

                    <section id="caregiver-patient-disability-field-container">
                        <label 
                            id="caregiver-patient-disability-input-label"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2" 
                            for="caregiver-patient-disability-input"
                        >
                            Disability Type
                        </label>
                        <section class="relative">
                            <input 
                                id="caregiver-patient-disability-input"
                                type="text" 
                                name="disability_type" 
                                class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm"
                                value="{{ old('disability_type', $user->profile->disability_type ?? '') }}"
                                placeholder="e.g. Visual Impairment"
                            >
                            <figure class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </figure>
                        </section>
                    </section>

                    <section id="caregiver-patient-address-field-container">
                        <label 
                            id="caregiver-patient-address-input-label"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2" 
                            for="caregiver-patient-address-input"
                        >
                            Address
                        </label>
                        <section class="relative">
                            <input 
                                id="caregiver-patient-address-input"
                                type="text" 
                                name="address" 
                                class="w-full pl-10 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm"
                                value="{{ old('address', $user->profile->address ?? '') }}"
                                placeholder="Full physical address"
                            >
                            <figure class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </figure>
                        </section>
                    </section>
                </section>

                <article 
                    id="caregiver-patient-emergency-contact-card"
                    class="mt-8 bg-slate-50 rounded-2xl p-6 border border-slate-200/60"
                >
                    <header>
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Emergency Contact
                        </h3>
                    </header>
                    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <section id="caregiver-patient-emergency-name-field-container">
                            <label 
                                id="caregiver-patient-emergency-name-input-label"
                                class="block text-xs font-semibold text-slate-500 mb-1.5" 
                                for="caregiver-patient-emergency-name-input"
                            >
                                Contact Name
                            </label>
                            <input 
                                id="caregiver-patient-emergency-name-input"
                                type="text" 
                                name="emergency_contact_name" 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm"
                                value="{{ old('emergency_contact_name', $user->profile->emergency_contact_name ?? '') }}"
                            >
                        </section>
                        <section id="caregiver-patient-emergency-phone-field-container">
                            <label 
                                id="caregiver-patient-emergency-phone-input-label"
                                class="block text-xs font-semibold text-slate-500 mb-1.5" 
                                for="caregiver-patient-emergency-phone-input"
                            >
                                Contact Phone
                            </label>
                            <input 
                                id="caregiver-patient-emergency-phone-input"
                                type="tel" 
                                name="emergency_contact_phone" 
                                class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all shadow-sm"
                                value="{{ old('emergency_contact_phone', $user->profile->emergency_contact_phone ?? '') }}"
                            >
                        </section>
                    </section>
                </article>
            </section>

            <section id="caregiver-patient-accessibility-prefs-section">
                <header class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-100">
                    <figure class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </figure>
                    <section>
                        <h2 class="text-xl font-bold text-slate-900">Accessibility Preferences</h2>
                        <p class="text-sm text-slate-500">Customize the experience based on patient needs.</p>
                    </section>
                </header>
                
                @php $prefs = $user->profile->accessibility_preferences ?? []; @endphp

                <section class="space-y-8">
                    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <section id="caregiver-patient-font-size-selection-container">
                            <label 
                                id="caregiver-patient-font-size-label"
                                class="block text-sm font-bold text-slate-800 mb-4"
                            >
                                Font Size
                            </label>
                            <section class="grid grid-cols-2 gap-3">
                                @foreach(['small', 'normal', 'large', 'extra_large'] as $size)
                                    <label class="cursor-pointer group relative">
                                        <input 
                                            id="caregiver-patient-font-size-input-{{ $size }}"
                                            type="radio" 
                                            name="accessibility_preferences[font_size]" 
                                            value="{{ $size }}" 
                                            @if(($prefs['font_size'] ?? 'normal') === $size) checked @endif
                                            class="peer sr-only"
                                        >
                                        <section class="border border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 rounded-xl p-3 text-center transition-all hover:border-indigo-300">
                                            <span class="block text-sm font-bold capitalize">{{ str_replace('_', ' ', $size) }}</span>
                                        </section>
                                    </label>
                                @endforeach
                            </section>
                        </section>

                        <section id="caregiver-patient-spacing-selection-container">
                            <label 
                                id="caregiver-patient-spacing-label"
                                class="block text-sm font-bold text-slate-800 mb-4"
                            >
                                Content Spacing
                            </label>
                            <section class="grid grid-cols-3 gap-3">
                                @foreach(['compact', 'normal', 'relaxed'] as $spacing)
                                    <label class="cursor-pointer">
                                        <input 
                                            id="caregiver-patient-spacing-input-{{ $spacing }}"
                                            type="radio" 
                                            name="accessibility_preferences[spacing]" 
                                            value="{{ $spacing }}" 
                                            @if(($prefs['spacing'] ?? 'normal') === $spacing) checked @endif
                                            class="peer sr-only"
                                        >
                                        <section class="border border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 rounded-xl p-3 text-center transition-all hover:border-indigo-300">
                                            <span class="block text-sm font-bold capitalize">{{ $spacing }}</span>
                                        </section>
                                    </label>
                                @endforeach
                            </section>
                        </section>
                    </section>

                    <hr class="border-t border-slate-100 my-6">

                    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <section id="caregiver-patient-contrast-selection-container">
                            <label 
                                id="caregiver-patient-contrast-label"
                                class="block text-sm font-bold text-slate-800 mb-4"
                            >
                                Contrast Mode
                            </label>
                            <section class="space-y-3">
                                @foreach(['normal', 'high', 'inverted'] as $mode)
                                    <label class="cursor-pointer block">
                                        <input 
                                            id="caregiver-patient-contrast-input-{{ $mode }}"
                                            type="radio" 
                                            name="accessibility_preferences[contrast_mode]" 
                                            value="{{ $mode }}" 
                                            @if(($prefs['contrast_mode'] ?? 'normal') === $mode) checked @endif
                                            class="peer sr-only"
                                        >
                                        <section class="border border-slate-200 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 rounded-xl p-3 flex items-center transition-all hover:border-indigo-300">
                                            <figure class="w-4 h-4 rounded-full border border-current mr-3 flex items-center justify-center">
                                                <figure class="w-2 h-2 rounded-full bg-current opacity-0 peer-checked:opacity-100"></figure>
                                            </figure>
                                            <span class="text-sm font-bold capitalize">{{ $mode }} Control</span>
                                        </section>
                                    </label>
                                @endforeach
                            </section>
                        </section>

                        <section id="caregiver-patient-color-blind-selection-container">
                            <label 
                                id="caregiver-patient-color-blind-label"
                                class="block text-sm font-bold text-slate-800 mb-4"
                            >
                                Color Blind Mode
                            </label>
                            <section 
                                class="relative" 
                                x-data="colorBlindModeHandler('{{ $prefs['color_blind_mode'] ?? 'none' }}')"
                            >
                                <select 
                                    id="caregiver-patient-color-blind-native-select"
                                    name="accessibility_preferences[color_blind_mode]" 
                                    class="sr-only" 
                                    tabindex="-1" 
                                    aria-hidden="true"
                                >
                                    <option value="none" @if(($prefs['color_blind_mode'] ?? 'none') == 'none') selected @endif>None</option>
                                    <option value="protanopia" @if(($prefs['color_blind_mode'] ?? '') == 'protanopia') selected @endif>Protanopia (Red-Blind)</option>
                                    <option value="deuteranopia" @if(($prefs['color_blind_mode'] ?? '') == 'deuteranopia') selected @endif>Deuteranopia (Green-Blind)</option>
                                    <option value="tritanopia" @if(($prefs['color_blind_mode'] ?? '') == 'tritanopia') selected @endif>Tritanopia (Blue-Blind)</option>
                                </select>
                                
                                <button 
                                    id="caregiver-patient-color-blind-dropdown-trigger"
                                    type="button" 
                                    @click="toggleDropdown()"
                                    @click.away="closeDropdown()"
                                    class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 flex items-center justify-between hover:bg-slate-50 transition-colors group"
                                >
                                    <span x-text="options[mode]"></span>
                                    <figure class="text-slate-500 group-hover:text-indigo-600 transition-colors">
                                        <svg 
                                            class="w-5 h-5 transition-transform duration-200" 
                                            :class="{'rotate-180': isOpen}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </figure>
                                </button>

                                <section 
                                    id="caregiver-patient-color-blind-dropdown-menu"
                                    x-show="isOpen" 
                                    x-cloak
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                                >
                                    <section class="py-1">
                                        <template x-for="(label, key) in options" :key="key">
                                            <button 
                                                type="button"
                                                @click="selectOption(key)"
                                                class="w-full text-left px-5 py-3.5 text-sm font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition-colors flex items-center justify-between"
                                            >
                                                <span x-text="label"></span>
                                                <svg x-show="mode == key" class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </template>
                                    </section>
                                </section>
                            </section>

                            <section 
                                id="caregiver-patient-toggles-container"
                                class="mt-6 space-y-3"
                            >
                                @foreach([
                                    'screen_reader_enabled' => 'Screen Reader Support',
                                    'text_to_speech_enabled' => 'Text-to-Speech',
                                    'voice_navigation_enabled' => 'Voice Navigation'
                                ] as $key => $label)
                                    <label class="flex items-center p-3 border border-slate-200 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                                        <section class="relative flex items-center">
                                            <input 
                                                id="caregiver-patient-toggle-{{ $key }}"
                                                type="checkbox" 
                                                name="accessibility_preferences[{{ $key }}]" 
                                                value="1"
                                                @if(($prefs[$key] ?? true)) checked @endif
                                                class="peer sr-only"
                                            >
                                            <figure class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></figure>
                                        </section>
                                        <span class="ml-3 text-sm font-bold text-slate-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </section>
                        </section>
                    </section>
                </section>
            </section>

            <footer 
                id="caregiver-patient-edit-form-footer"
                class="pt-8 border-t border-slate-100 flex flex-col-reverse md:flex-row justify-end gap-4"
            >
                <a 
                    id="caregiver-patient-edit-cancel-btn"
                    href="{{ route('caregiver.dashboard') }}" 
                    class="px-8 py-3.5 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 hover:text-slate-900 transition-all text-center"
                >
                    Cancel
                </a>
                <button 
                    id="caregiver-patient-edit-submit-btn"
                    type="submit" 
                    class="px-8 py-3.5 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all"
                >
                    Save Changes
                </button>
            </footer>
        </form>
    </article>
</section>

<script>
    function colorBlindModeHandler(initialMode) {
        return {
            mode: initialMode,
            isOpen: false,
            options: {
                'none': 'None',
                'protanopia': 'Protanopia (Red-Blind)',
                'deuteranopia': 'Deuteranopia (Green-Blind)',
                'tritanopia': 'Tritanopia (Blue-Blind)'
            },
            toggleDropdown() {
                this.isOpen = !this.isOpen;
            },
            closeDropdown() {
                this.isOpen = false;
            },
            selectOption(key) {
                this.mode = key;
                this.isOpen = false;
                var nativeSelectElement = document.getElementById('caregiver-patient-color-blind-native-select');
                if (nativeSelectElement) {
                    nativeSelectElement.value = key;
                    nativeSelectElement.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        var nativeSelectElement = document.getElementById('caregiver-patient-color-blind-native-select');
        if (nativeSelectElement) {
            nativeSelectElement.addEventListener('change', function(changeEvent) {
                var containerElement = document.querySelector('[x-data^="colorBlindModeHandler"]');
                if (containerElement && window.Alpine) {
                    var alpineData = Alpine.$data(containerElement);
                    if (alpineData) {
                        alpineData.mode = changeEvent.target.value;
                    }
                }
            });
        }
    });
</script>
@endsection
