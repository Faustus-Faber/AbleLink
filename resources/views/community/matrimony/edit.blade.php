@extends('layouts.app')


@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Edit Your Profile</h1>
        <p class="text-slate-500 font-medium mt-1">Update your information to keep your profile fresh.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <form action="{{ route('community.matrimony.update', $profile) }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-8">
            @csrf
            @method('PUT')
            
            <!-- Photo Upload -->
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 bg-rose-50 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-rose-300 overflow-hidden mb-4 relative group">
                     @if($profile->photo_path)
                        <img src="{{ asset('storage/' . $profile->photo_path) }}" class="w-full h-full object-cover">
                    @else
                         <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    @endif
                    <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                </div>
                <label for="photo" class="cursor-pointer bg-slate-900 text-white px-4 py-2 rounded-full text-sm font-bold hover:bg-slate-800 transition-all">
                    Change Photo
                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                </label>
                <p class="text-xs text-slate-400 mt-2">Max file size: 2MB</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="gender" class="block text-sm font-bold text-slate-700">Gender</label>
                    {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                    <div class="relative" x-data="{
                        gender: '{{ $profile->gender }}',
                        open: false,
                        options: {
                            '': 'Select Gender...',
                            'Male': 'Male',
                            'Female': 'Female'
                        },
                        select(key) {
                            this.gender = key;
                            this.open = false;
                            document.getElementById('gender').value = key;
                            document.getElementById('gender').dispatchEvent(new Event('change', { bubbles: true }));
                        },
                        init() {
                            document.getElementById('gender').addEventListener('change', (e) => {
                                this.gender = e.target.value;
                            });
                        }
                    }">
                        {{-- Hidden Native Select (AI-Compatible) --}}
                        <select name="gender" id="gender" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="">Select Gender...</option>
                            <option value="Male" {{ $profile->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $profile->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        
                        {{-- Premium Visual Dropdown --}}
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 transition-all font-medium text-slate-900 flex items-center justify-between group hover:bg-white text-left">
                            <span x-text="options[gender] || 'Select Gender...'"></span>
                            <div class="text-slate-400 group-hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5 transition-transform duration-200" 
                                     :class="{'rotate-180': open}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button"
                                            @click="select(key)"
                                            class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center justify-between">
                                        <span x-text="label"></span>
                                        <svg x-show="gender == key" class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="age" class="block text-sm font-bold text-slate-700">Age</label>
                    <input type="number" name="age" id="age" value="{{ $profile->age }}" min="18" max="100" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="occupation" class="block text-sm font-bold text-slate-700">Occupation</label>
                    <input type="text" name="occupation" id="occupation" value="{{ $profile->occupation }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400">
                </div>
                <div class="space-y-2">
                    <label for="education" class="block text-sm font-bold text-slate-700">Education</label>
                    <input type="text" name="education" id="education" value="{{ $profile->education }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div class="space-y-2">
                    <label for="marital_status" class="block text-sm font-bold text-slate-700">Marital Status</label>
                    {{-- Hybrid Dropdown: Hidden native select (AI) + Premium visual dropdown --}}
                    <div class="relative" x-data="{
                        status: '{{ $profile->marital_status }}',
                        open: false,
                        options: {
                            '': 'Select Status...',
                            'Single': 'Single',
                            'Divorced': 'Divorced',
                            'Widowed': 'Widowed'
                        },
                        select(key) {
                            this.status = key;
                            this.open = false;
                            document.getElementById('marital_status').value = key;
                            document.getElementById('marital_status').dispatchEvent(new Event('change', { bubbles: true }));
                        },
                        init() {
                            document.getElementById('marital_status').addEventListener('change', (e) => {
                                this.status = e.target.value;
                            });
                        }
                    }">
                        {{-- Hidden Native Select (AI-Compatible) --}}
                        <select name="marital_status" id="marital_status" class="sr-only" tabindex="-1" aria-hidden="true">
                            <option value="">Select Status...</option>
                            <option value="Single" {{ $profile->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Divorced" {{ $profile->marital_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ $profile->marital_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                        
                        {{-- Premium Visual Dropdown --}}
                        <button type="button" 
                                @click="open = !open"
                                @click.away="open = false"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 transition-all font-medium text-slate-900 flex items-center justify-between group hover:bg-white text-left">
                            <span x-text="options[status] || 'Select Status...'"></span>
                            <div class="text-slate-400 group-hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5 transition-transform duration-200" 
                                     :class="{'rotate-180': open}"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                             style="display: none;">
                            <div class="py-1">
                                <template x-for="(label, key) in options" :key="key">
                                    <button type="button"
                                            @click="select(key)"
                                            class="w-full text-left px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors flex items-center justify-between">
                                        <span x-text="label"></span>
                                        <svg x-show="status == key" class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="religion" class="block text-sm font-bold text-slate-700">Religion</label>
                    <input type="text" name="religion" id="religion" value="{{ $profile->religion }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400">
                </div>
            </div>

            <div class="space-y-2">
                <label for="bio" class="block text-sm font-bold text-slate-700">Bio / About Me</label>
                <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400 resize-none">{{ $profile->bio }}</textarea>
            </div>

           <div class="space-y-2">
                <label for="partner_preferences" class="block text-sm font-bold text-slate-700">Partner Preferences</label>
                <textarea name="partner_preferences" id="partner_preferences" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:ring-4 focus:ring-rose-50 focus:border-rose-500 outline-none transition-all font-medium text-slate-900 placeholder:text-slate-400 resize-none">{{ $profile->partner_preferences }}</textarea>
            </div>

            <!-- Privacy Level Removed: Default Global -->

            <div class="pt-6 border-t border-slate-50 flex items-center justify-end gap-4">
                <a href="{{ route('community.matrimony.index') }}" class="px-6 py-3 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-200">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('photo').addEventListener('change', function(event) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (this.files[0]) {
                 if (this.files[0].size > maxSize) {
                    alert('File is too big! Maximum file size is 2MB.');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</div>
@endsection
