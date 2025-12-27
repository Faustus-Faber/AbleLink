@extends('layouts.app')


@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-rose-950 tracking-tight">Create Your Profile</h1>
        <p class="text-rose-700/80 font-medium mt-1">Share your story and find your perfect match.</p>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden relative">
         <div class="absolute top-0 right-0 w-32 h-32 bg-rose-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <form action="{{ route('community.matrimony.store') }}" method="POST" enctype="multipart/form-data" class="p-8 md:p-10 space-y-8 relative z-10">
            @csrf
            
            <!-- Photo Upload -->
            <div class="flex flex-col items-center">
                <div class="w-32 h-32 bg-rose-50 rounded-full border-4 border-white shadow-lg flex items-center justify-center text-rose-300 overflow-hidden mb-4 relative group">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden">
                </div>
                <label for="photo" class="cursor-pointer bg-rose-950 text-white px-4 py-2 rounded-full text-sm font-bold hover:bg-rose-900 transition-all">
                    Upload Photo
                    <input type="file" name="photo" id="photo" class="hidden" accept="image/*">
                </label>
                <p class="text-xs text-rose-400 mt-2">Max file size: 2MB</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="gender" class="block text-sm font-bold text-rose-900">Gender</label>
                    <div class="relative">
                        <select name="gender" id="gender" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 appearance-none cursor-pointer">
                            <option value="">Select Gender...</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-rose-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="age" class="block text-sm font-bold text-rose-900">Age</label>
                    <input type="number" name="age" id="age" min="18" max="100" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 placeholder:text-rose-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="occupation" class="block text-sm font-bold text-rose-900">Occupation</label>
                    <input type="text" name="occupation" id="occupation" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 placeholder:text-rose-300">
                </div>
                <div class="space-y-2">
                    <label for="education" class="block text-sm font-bold text-rose-900">Education</label>
                    <input type="text" name="education" id="education" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 placeholder:text-rose-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="marital_status" class="block text-sm font-bold text-rose-900">Marital Status</label>
                    <div class="relative">
                        <select name="marital_status" id="marital_status" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 appearance-none cursor-pointer">
                            <option value="">Select Status...</option>
                            <option value="Single">Single</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                         <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-rose-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label for="religion" class="block text-sm font-bold text-rose-900">Religion</label>
                    <input type="text" name="religion" id="religion" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 placeholder:text-rose-300">
                </div>
            </div>

            <div class="space-y-2">
                <label for="bio" class="block text-sm font-bold text-rose-900">Bio / About Me</label>
                <textarea name="bio" id="bio" rows="4" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 placeholder:text-rose-300 resize-none"></textarea>
            </div>

            <div class="space-y-2">
                <label for="partner_preferences" class="block text-sm font-bold text-rose-900">Partner Preferences</label>
                <textarea name="partner_preferences" id="partner_preferences" rows="4" class="w-full px-4 py-3 rounded-xl bg-rose-50/50 border border-rose-100 focus:bg-white focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all font-medium text-rose-950 placeholder:text-rose-300 resize-none"></textarea>
            </div>

            <!-- Privacy Level Removed: Default Global -->

            <div class="pt-6 border-t border-rose-50 flex items-center justify-end gap-4">
                <a href="{{ route('community.matrimony.index') }}" class="px-6 py-3 rounded-xl text-rose-700 font-bold hover:bg-rose-50 transition-all">Cancel</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-rose-600 text-white font-bold hover:bg-rose-700 transition-all shadow-lg hover:shadow-rose-200">
                    Create Profile
                </button>
            </div>
        </form>
    </div>
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
@endsection
