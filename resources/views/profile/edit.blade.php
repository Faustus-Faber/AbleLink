@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-white font-sans selection:bg-black selection:text-white">
    <div class="container mx-auto px-6 py-12 max-w-4xl">
        
        <div class="flex flex-col md:flex-row items-center justify-between mb-12 gap-6">
            <div>
                 <a href="{{ route('profile.show') }}" class="inline-flex items-center text-sm font-bold text-zinc-400 hover:text-zinc-900 transition-colors mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Profile
                </a>
                <h1 class="text-4xl md:text-5xl font-black text-zinc-900 tracking-tight">Edit Profile.</h1>
            </div>
            <div class="hidden md:block w-16 h-16 bg-zinc-50 rounded-2xl flex items-center justify-center text-zinc-300">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-12">
            @csrf

            <div class="bg-zinc-50 rounded-[2.5rem] p-10 border border-zinc-100 flex flex-col md:flex-row items-center gap-10">
                <div class="relative group cursor-pointer">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl shadow-zinc-200/50 bg-white">
                        <img id="avatar-preview" 
                             src="{{ $user->profile && $user->profile->avatar ? asset('storage/' . $user->profile->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=18181b&color=fff' }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                
                <div class="flex-1 text-center md:text-left">
                    <h3 class="text-xl font-black text-zinc-900 mb-2">Profile Picture</h3>
                    <p class="text-zinc-500 font-medium mb-6 text-sm">Upload a high-quality image (JPG/PNG). Max 2MB.</p>
                    
                    <label class="inline-flex items-center px-6 py-3 rounded-xl bg-white border-2 border-zinc-200 text-zinc-900 font-bold hover:bg-zinc-900 hover:text-white hover:border-zinc-900 transition-all cursor-pointer shadow-sm">
                        <span class="mr-2">Choose File</span>
                        <input type="file" name="avatar" class="hidden" onchange="previewImage(this)">
                         <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </label>
                    @error('avatar')
                        <p class="text-red-500 text-sm font-bold mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="space-y-8">
                <h3 class="text-2xl font-black text-zinc-900 border-b border-zinc-100 pb-4">Personal Details</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-6 py-4 rounded-2xl bg-zinc-50 border-transparent focus:bg-white focus:ring-4 focus:ring-zinc-100 focus:border-zinc-900 font-bold text-zinc-900 transition-all" placeholder="Your Name">
                         @error('name') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email" value="{{ $user->email }}" readonly class="w-full px-6 py-4 rounded-2xl bg-zinc-100 border-transparent text-zinc-400 font-bold cursor-not-allowed">
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->profile->phone_number ?? '') }}" class="w-full px-6 py-4 rounded-2xl bg-zinc-50 border-transparent focus:bg-white focus:ring-4 focus:ring-zinc-100 focus:border-zinc-900 font-bold text-zinc-900 transition-all" placeholder="+1 (555) 000-0000">
                         @error('phone_number') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', optional($user->profile->date_of_birth ?? null)->format('Y-m-d')) }}" class="w-full px-6 py-4 rounded-2xl bg-zinc-50 border-transparent focus:bg-white focus:ring-4 focus:ring-zinc-100 focus:border-zinc-900 font-bold text-zinc-900 transition-all">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Address</label>
                        <input type="text" name="address" value="{{ old('address', $user->profile->address ?? '') }}" class="w-full px-6 py-4 rounded-2xl bg-zinc-50 border-transparent focus:bg-white focus:ring-4 focus:ring-zinc-100 focus:border-zinc-900 font-bold text-zinc-900 transition-all" placeholder="Street address, City, Country">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Disability Type (Optional)</label>
                        <input type="text" name="disability_type" value="{{ old('disability_type', $user->profile->disability_type ?? '') }}" class="w-full px-6 py-4 rounded-2xl bg-zinc-50 border-transparent focus:bg-white focus:ring-4 focus:ring-zinc-100 focus:border-zinc-900 font-bold text-zinc-900 transition-all" placeholder="e.g. Visual Impairment, Mobility">
                    </div>

                    <div class="col-span-1 md:col-span-2 space-y-3">
                        <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest">Bio</label>
                        <textarea name="bio" rows="4" class="w-full px-6 py-4 rounded-2xl bg-zinc-50 border-transparent focus:bg-white focus:ring-4 focus:ring-zinc-100 focus:border-zinc-900 font-medium text-zinc-900 transition-all resize-none leading-relaxed" placeholder="Write a short bio about yourself...">{{ old('bio', $user->profile->bio ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-rose-50 rounded-[2.5rem] p-10 border border-rose-100">
                <h3 class="text-xl font-black text-rose-950 mb-6 flex items-center gap-3">
                    <div class="p-2 bg-white rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M12 12h.01M12 6h.01M12 12a9 9 0 110-18 9 9 0 010 18z"/></svg>
                    </div>
                    Emergency Contact
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                     <div class="space-y-3">
                        <label class="block text-xs font-black text-rose-400 uppercase tracking-widest">Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $user->profile->emergency_contact_name ?? '') }}" class="w-full px-6 py-4 rounded-2xl bg-white border border-rose-100 focus:border-rose-400 focus:ring-4 focus:ring-rose-100 font-bold text-zinc-900 transition-all placeholder-rose-200" placeholder="Contact Name">
                    </div>
                     <div class="space-y-3">
                        <label class="block text-xs font-black text-rose-400 uppercase tracking-widest">Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $user->profile->emergency_contact_phone ?? '') }}" class="w-full px-6 py-4 rounded-2xl bg-white border border-rose-100 focus:border-rose-400 focus:ring-4 focus:ring-rose-100 font-bold text-zinc-900 transition-all placeholder-rose-200" placeholder="Contact Phone">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-6 pt-4">
                <a href="{{ route('profile.show') }}" class="px-6 py-3 rounded-xl font-bold text-zinc-400 hover:text-zinc-600 transition-colors">Cancel</a>
                <button type="submit" class="px-10 py-4 rounded-2xl bg-zinc-900 text-white font-black text-lg hover:bg-black hover:scale-105 hover:shadow-xl hover:shadow-zinc-900/20 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
