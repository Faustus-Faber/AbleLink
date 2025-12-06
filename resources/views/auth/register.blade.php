@extends('layouts.auth')

@section('title', 'Join Ablelink')

@section('content')
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 mb-4">Create your account</h1>
        <p class="text-slate-500 text-lg leading-relaxed">Join our community of employers, volunteers, and caregivers. Simple, secure, and accessible.</p>
    </div>

    <form method="POST" action="{{ route('register.store') }}" aria-label="Ablelink registration form" class="space-y-6">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Full Name</label>
            <input id="name" name="name" type="text" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg placeholder-slate-400"
                   placeholder="e.g. John Doe"
                   value="{{ old('name') }}" required autofocus>
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Email Address</label>
            <input id="email" name="email" type="email" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg placeholder-slate-400"
                   placeholder="you@example.com"
                   value="{{ old('email') }}" required inputmode="email">
        </div>

        <div>
            <label for="phone" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Mobile Number</label>
            <input id="phone" name="phone" type="tel" 
                   class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg placeholder-slate-400"
                   placeholder="+1 (555) 000-0000"
                   value="{{ old('phone') }}" required inputmode="tel">
            <p class="text-xs text-slate-400 mt-2 ml-1">We'll use this for secure OTP verification.</p>
        </div>

        <div>
            <label for="role" class="block text-sm font-bold text-slate-700 mb-2 ml-1">I am registering as</label>
                {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                <div x-data="{ 
                        open: false, 
                        selected: '{{ old('role') }}', 
                        roles: {
                            @foreach ($roles as $role)
                                '{{ $role }}': '{{ ucfirst($role) }}',
                            @endforeach
                        },
                        get label() {
                            return this.selected ? this.roles[this.selected] : 'Select your role...';
                        },
                        select(value) {
                            this.selected = value;
                            this.open = false;
                            document.getElementById('role').value = value;
                            document.getElementById('role').dispatchEvent(new Event('change', { bubbles: true }));
                        },
                        init() {
                            document.getElementById('role').addEventListener('change', (e) => {
                                this.selected = e.target.value;
                            });
                        }
                     }" 
                     class="relative">
                    
                    {{-- Hidden Native Select (AI-Compatible) --}}
                    <select name="role" id="role" class="sr-only" tabindex="-1" aria-hidden="true" required>
                        <option value="">Select your role...</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>

                    {{-- Premium Visual Dropdown Trigger --}}
                    <button type="button" 
                            @click="open = !open" 
                            @click.away="open = false"
                            class="w-full px-5 py-4 rounded-2xl border-2 border-slate-200 bg-slate-50 text-slate-900 font-medium focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 transition-all outline-none text-lg text-left flex justify-between items-center group">
                        <span x-text="label" :class="{'text-slate-400': !selected, 'text-slate-900': selected}"></span>
                        <svg class="w-6 h-6 text-slate-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    {{-- Premium Dropdown Options --}}
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute z-50 w-full mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden" 
                         style="display: none;">
                        <ul class="max-h-60 overflow-auto py-1">
                            <template x-for="(label, value) in roles" :key="value">
                                <li>
                                    <button type="button" 
                                            @click="select(value)"
                                            class="w-full px-6 py-3 text-left hover:bg-blue-50 text-slate-700 hover:text-blue-700 font-medium transition-colors flex items-center justify-between group">
                                        <span x-text="label"></span>
                                        <svg x-show="selected === value" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
        </div>

        <button type="submit" 
                class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:brightness-110 transition-all duration-300 transform">
            Create Account
        </button>
    </form>
@endsection
