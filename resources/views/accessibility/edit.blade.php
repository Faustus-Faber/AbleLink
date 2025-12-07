@extends('layouts.app')

@section('title', 'Accessibility Settings')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <div class="mb-12">
        <a href="{{ route('profile.show') }}" class="inline-flex items-center text-zinc-500 hover:text-zinc-900 font-bold mb-6 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Profile
        </a>
        <h1 class="text-4xl font-black text-black tracking-tight mb-4">Accessibility.</h1>
        <p class="text-lg text-zinc-600">Customize your experience with our assistive technologies.</p>
    </div>

    <form action="{{ route('accessibility.update') }}" method="POST" class="space-y-12">
        @csrf
        @method('PUT')

        @php
           $prefs = $user->profile->accessibility_preferences ?? [];
           $voiceNav = isset($prefs['voice_navigation_enabled']) ? $prefs['voice_navigation_enabled'] : true;
           $tts = isset($prefs['text_to_speech_enabled']) ? $prefs['text_to_speech_enabled'] : true;
           $screenReader = isset($prefs['screen_reader_enabled']) ? $prefs['screen_reader_enabled'] : true;
        @endphp

        <div class="bg-zinc-50 rounded-[2.5rem] p-10 border border-zinc-100">
             <div class="flex items-center gap-4 mb-8">
                 <div class="p-3 bg-black text-white rounded-2xl">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                 </div>
                 <h2 class="text-2xl font-bold text-black">Assistive Features</h2>
             </div>

             <div class="space-y-4">
                <label class="flex items-center justify-between p-6 bg-white border border-zinc-200 rounded-2xl cursor-pointer hover:border-zinc-400 transition-all group">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 group-hover:bg-zinc-200 transition-colors mr-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-zinc-900 text-lg">Voice Navigation</span>
                            <span class="block text-zinc-500 text-sm">Control the interface with voice commands.</span>
                        </div>
                    </div>
                    <input type="checkbox" name="voice_navigation_enabled" value="1"
                        {{ $voiceNav ? 'checked' : '' }}
                        class="w-6 h-6 text-black rounded-md border-zinc-300 focus:ring-black">
                </label>

                <label class="flex items-center justify-between p-6 bg-white border border-zinc-200 rounded-2xl cursor-pointer hover:border-zinc-400 transition-all group">
                    <div class="flex items-center">
                         <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 group-hover:bg-zinc-200 transition-colors mr-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-zinc-900 text-lg">Enable Text-to-Speech</span>
                            <span class="block text-zinc-500 text-sm">Read aloud content and agent responses.</span>
                        </div>
                    </div>
                    <input type="checkbox" name="text_to_speech_enabled" value="1"
                        {{ $tts ? 'checked' : '' }}
                        class="w-6 h-6 text-black rounded-md border-zinc-300 focus:ring-black">
                </label>

                <label class="flex items-center justify-between p-6 bg-white border border-zinc-200 rounded-2xl cursor-pointer hover:border-zinc-400 transition-all group">
                    <div class="flex items-center">
                         <div class="w-12 h-12 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-600 group-hover:bg-zinc-200 transition-colors mr-4">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-zinc-900 text-lg">Screen Reader Support</span>
                            <span class="block text-zinc-500 text-sm">Optimize page structure for screen readers.</span>
                        </div>
                    </div>
                    <input type="checkbox" name="screen_reader_enabled" value="1"
                        {{ $screenReader ? 'checked' : '' }}
                        class="w-6 h-6 text-black rounded-md border-zinc-300 focus:ring-black">
                </label>
             </div>
        </div>

        <div class="bg-zinc-50 rounded-[2.5rem] p-10 border border-zinc-100">
            <div class="flex items-center gap-4 mb-8">
                 <div class="p-3 bg-black text-white rounded-2xl">
                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                 </div>
                 <h2 class="text-2xl font-bold text-black">Display Settings</h2>
             </div>

             <div class="space-y-8">
                 <div>
                     <label class="block font-bold text-zinc-900 mb-4">Font Size</label>
                     <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                         @foreach(['small' => 'Small', 'normal' => 'Normal', 'large' => 'Large', 'extra_large' => 'Extra Large'] as $value => $label)
                             <label class="cursor-pointer relative group">
                                 <input type="radio" name="font_size" value="{{ $value }}" 
                                     {{ ($prefs['font_size'] ?? 'normal') === $value ? 'checked' : '' }}
                                     class="peer sr-only">
                                 <div class="border border-zinc-300 bg-white peer-checked:border-black peer-checked:ring-1 peer-checked:ring-black rounded-xl p-4 text-center hover:border-zinc-400 transition-all h-full flex items-center justify-center">
                                     <p class="font-bold text-zinc-700 peer-checked:text-black"
                                        style="font-size: {{ $value === 'small' ? '0.8rem' : ($value === 'large' ? '1.1rem' : ($value === 'extra_large' ? '1.2rem' : '1rem')) }}">
                                         {{ $label }}
                                     </p>
                                 </div>
                             </label>
                         @endforeach
                     </div>
                 </div>

                 <div>
                     <label class="block font-bold text-zinc-900 mb-4">Contrast Mode</label>
                     <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                         @foreach(['normal' => 'Normal', 'high' => 'High Contrast', 'inverted' => 'Inverted'] as $value => $label)
                             <label class="cursor-pointer relative group">
                                 <input type="radio" name="contrast_mode" value="{{ $value }}" 
                                     {{ ($prefs['contrast_mode'] ?? 'normal') === $value ? 'checked' : '' }}
                                     class="peer sr-only">
                                 <div class="border border-zinc-300 bg-white peer-checked:border-black peer-checked:ring-1 peer-checked:ring-black rounded-xl p-4 hover:border-zinc-400 transition-all">
                                     <div class="flex items-center justify-center h-12 rounded-lg mb-2 {{ $value === 'high' ? 'bg-black text-white' : ($value === 'inverted' ? 'bg-white invert border border-black' : 'bg-white border border-zinc-200') }}">
                                         <span class="font-bold">Aa</span>
                                     </div>
                                     <p class="font-bold text-center text-zinc-700 peer-checked:text-black">{{ $label }}</p>
                                 </div>
                             </label>
                         @endforeach
                     </div>
                 </div>

                 <div>
                     <label class="block font-bold text-zinc-900 mb-4">Color Blind Mode</label>
                     <div class="relative" x-data="{
                         mode: '{{ $prefs['color_blind_mode'] ?? 'none' }}',
                         open: false,
                         options: {
                             'none': 'None',
                             'protanopia': 'Protanopia (Red-Blind)',
                             'deuteranopia': 'Deuteranopia (Green-Blind)',
                             'tritanopia': 'Tritanopia (Blue-Blind)'
                         }
                     }">
                         <input type="hidden" name="color_blind_mode" :value="mode">
                         
                         <button type="button" 
                                 @click="open = !open"
                                 @click.away="open = false"
                                 class="w-full px-5 py-4 bg-white border border-zinc-300 rounded-xl font-bold text-zinc-900 focus:outline-none focus:ring-2 focus:ring-black flex items-center justify-between hover:bg-zinc-50 transition-colors group">
                             <span x-text="options[mode]"></span>
                             <div class="text-zinc-500 group-hover:text-black transition-colors">
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
                              class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-xl border border-zinc-100 overflow-hidden ring-1 ring-black ring-opacity-5"
                              style="display: none;">
                             <div class="py-1">
                                 <template x-for="(label, key) in options" :key="key">
                                     <button type="button"
                                             @click="mode = key; open = false"
                                             class="w-full text-left px-5 py-3.5 text-base font-bold text-zinc-600 hover:bg-zinc-50 hover:text-black transition-colors flex items-center justify-between">
                                         <span x-text="label"></span>
                                         <svg x-show="mode == key" class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                         </svg>
                                     </button>
                                 </template>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
        </div>

        <div class="flex flex-col md:flex-row justify-end items-center gap-4">
            <a href="{{ route('profile.show') }}" class="w-full md:w-auto px-8 py-4 rounded-xl text-zinc-500 font-bold hover:bg-zinc-100 transition-all text-center">Cancel</a>
            <button type="submit" class="w-full md:w-auto px-10 py-4 rounded-xl bg-black text-white font-bold hover:bg-zinc-800 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Save Preferences
            </button>
        </div>
    </form>
</div>
@endsection
