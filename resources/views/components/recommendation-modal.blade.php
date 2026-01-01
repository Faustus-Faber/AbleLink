{{-- F12 - Farhan Zarif --}}
@props(['type' => 'job'])

<div x-data="recommendationModal('{{ $type }}')" x-show="isOpen" 
     @open-recommendations.window="if($event.detail.type === '{{ $type }}') open()"
     @keydown.escape.window="close()"
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    {{-- Glassmorphic Backdrop --}}
    <div x-show="isOpen" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="close()"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        {{-- Modal Panel --}}
        <div x-show="isOpen" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" 
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative transform overflow-hidden rounded-3xl bg-white/95 backdrop-blur-xl text-left shadow-2xl ring-1 ring-black/5 transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white/20">
            
            {{-- Header with Dynamic Gradient --}}
            <div class="relative px-6 py-6 bg-gradient-to-br {{ $type === 'job' ? 'from-violet-50 via-white to-fuchsia-50' : 'from-emerald-50 via-white to-teal-50' }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="p-3 rounded-2xl shadow-sm bg-gradient-to-br {{ $type === 'job' ? 'from-violet-500 to-fuchsia-500 shadow-violet-200' : 'from-emerald-500 to-teal-500 shadow-emerald-200' }}">
                            @if($type === 'job')
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z" /></svg>
                            @else
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r {{ $type === 'job' ? 'from-violet-600 to-fuchsia-600' : 'from-emerald-600 to-teal-600' }}" id="modal-title">
                                {{ $type === 'job' ? 'Smart Match' : 'Learning Path' }}
                            </h3>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">AI Powered Recommendations</p>
                        </div>
                    </div>
                    
                    <button @click="close()" class="p-2 rounded-full hover:bg-black/5 text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {{-- Preferences Toggle --}}
                <div class="absolute bottom-4 right-6" x-show="!needsSetup && !editMode && !loading && !needsLogin">
                     <button @click="editMode = true" 
                        class="flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-indigo-600 transition-colors bg-white/50 hover:bg-white px-3 py-1.5 rounded-full border border-slate-200/50 hover:border-indigo-200">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Adjust Filters
                    </button>
                </div>
            </div>

            <div class="px-6 py-6 max-h-[60vh] overflow-y-auto custom-scrollbar">
                
                {{-- Loading State --}}
                <div x-show="loading" class="flex flex-col items-center justify-center py-12 space-y-4">
                    <div class="relative w-16 h-16">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-tr {{ $type === 'job' ? 'from-violet-500 to-fuchsia-500' : 'from-emerald-500 to-teal-500' }} opacity-20 animate-ping"></div>
                        <div class="relative w-full h-full rounded-full border-4 border-t-transparent {{ $type === 'job' ? 'border-violet-500' : 'border-emerald-500' }} animate-spin"></div>
                    </div>
                    <p class="text-sm font-medium text-slate-500 animate-pulse">Finding your perfect matches...</p>
                </div>

                {{-- Login Required State --}}
                <div x-show="!loading && needsLogin" class="text-center py-12">
                    <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-6 ring-4 ring-slate-50">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Unlock Smart Recommendations</h3>
                    <p class="text-slate-500 text-sm mb-8 max-w-xs mx-auto leading-relaxed">Sign in to get personalized entries tailored to your profile.</p>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-slate-200 transition-all hover:scale-105 hover:bg-black">
                        Log In Now
                    </a>
                </div>

                {{-- Preference Setup / Edit Form --}}
                <div x-show="!loading && !needsLogin && (needsSetup || editMode)" class="text-left">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100 mb-8 text-center">
                        <h4 class="font-bold text-slate-900 text-lg mb-2" x-text="editMode ? 'Refine Results' : 'Let\'s Get Started'"></h4>
                        <p class="text-sm text-slate-500 leading-relaxed max-w-sm mx-auto">
                            Help our AI understand what you're looking for to get the best matches possible.
                        </p>
                    </div>

                    <div class="space-y-6">
                        @if($type === 'job')
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Your Skills</label>
                                <div class="relative">
                                    <input type="text" x-model="prefs.skills" placeholder="e.g. PHP, Laravel, Accessibility" 
                                        class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3.5 text-slate-900 focus:bg-white focus:border-violet-500 focus:ring-4 focus:ring-violet-500/10 sm:text-sm transition-all shadow-sm outline-none">
                                </div>
                                <p class="text-xs text-slate-400 font-medium ml-1">Separate multiple skills with commas</p>
                            </div>
                        @else
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Your Interests</label>
                                <input type="text" x-model="prefs.interests" placeholder="e.g. Web Development, Design, Business" 
                                    class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3.5 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 sm:text-sm transition-all shadow-sm outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Learning Style</label>
                                <select x-model="prefs.learning_style" class="w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-3.5 text-slate-900 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 sm:text-sm transition-all shadow-sm outline-none cursor-pointer">
                                    <option value="">Select your preferred style...</option>
                                    <option value="visual">Visual (Video/Images)</option>
                                    <option value="auditory">Auditory (Audio/Lectures)</option>
                                    <option value="text">Text (Articles/Books)</option>
                                </select>
                            </div>
                        @endif
                        
                        <div class="pt-4 flex gap-3">
                            <button @click="savePreferences()" 
                                class="flex-1 inline-flex justify-center items-center rounded-xl bg-gradient-to-r {{ $type === 'job' ? 'from-violet-600 to-fuchsia-600 hover:from-violet-700 hover:to-fuchsia-700 shadow-violet-500/25' : 'from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-emerald-500/25' }} px-4 py-4 text-sm font-bold text-white shadow-lg transition-all hover:-translate-y-0.5 active:translate-y-0">
                                <span x-text="editMode ? 'Update & Refresh' : 'Find My Matches'"></span>
                            </button>
                            <button x-show="editMode" @click="editMode = false" 
                                class="flex-none inline-flex justify-center items-center rounded-xl bg-white px-6 py-4 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Results List --}}
                <template x-if="!loading && !needsLogin && !needsSetup && !editMode && items.length > 0">
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="group relative bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1 hover:border-{{ $type === 'job' ? 'violet' : 'emerald' }}-100 w-full overflow-hidden">
                                
                                {{-- Card Accent Top --}}
                                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r {{ $type === 'job' ? 'from-violet-500 to-fuchsia-500' : 'from-emerald-500 to-teal-500' }} opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                
                                <div class="flex flex-col items-center mb-5">
                                    <div class="mb-3">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-bold ring-1 ring-inset shadow-sm" 
                                              :class="'{{ $type === 'job' ? 'bg-violet-50 text-violet-700 ring-violet-500/10' : 'bg-emerald-50 text-emerald-700 ring-emerald-500/10' }}'">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            <span x-text="Math.round(item.score) + '% Match'"></span>
                                        </span>
                                    </div>
                                    <h4 class="text-xl font-bold text-slate-900 text-center mb-1 group-hover:text-transparent group-hover:bg-clip-text group-hover:bg-gradient-to-r {{ $type === 'job' ? 'group-hover:from-violet-600 group-hover:to-fuchsia-600' : 'group-hover:from-emerald-600 group-hover:to-teal-600' }} transition-all" x-text="getTitle(item)"></h4>
                                    <p class="text-sm font-semibold text-slate-400" x-text="getSubtitle(item)"></p>
                                </div>
                                
                                <div class="relative rounded-xl p-5 mb-5 text-left w-full {{ $type === 'job' ? 'bg-violet-50/50' : 'bg-emerald-50/50' }}">
                                    <div class="flex gap-3 justify-center text-center">
                                        <p class="text-sm text-slate-600 leading-relaxed">
                                            <span class="font-bold text-slate-900 block mb-1.5 flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                Why this fits you
                                            </span> 
                                            <span x-text="item.explanation" class="opacity-90"></span>
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-center gap-3 pt-1">
                                    <button @click="dismiss(item)" class="text-xs font-bold text-slate-400 hover:text-rose-500 transition-colors flex items-center gap-1.5 py-2 px-3 rounded-lg hover:bg-rose-50">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        Dismiss
                                    </button>
                                    <a :href="getLink(item)" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white shadow-lg hover:shadow-xl hover:bg-black transition-all hover:-translate-y-0.5 min-w-[140px] group-hover:scale-105">
                                        View Details
                                        <svg class="w-4 h-4 ml-2 -mr-1 text-slate-400 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </a>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                 {{-- Empty Results --}}
                <template x-if="!loading && !needsLogin && !needsSetup && !editMode && items.length === 0">
                    <div class="text-center py-12">
                         <div class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-6 ring-4 ring-slate-50">
                            <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">No Matches Yet</h3>
                        <p class="text-slate-500 text-sm mb-8 max-w-xs mx-auto leading-relaxed">We couldn't find any perfect matches right now. Try adjusting your preferences to see more results.</p>
                        <button @click="editMode = true" class="inline-flex items-center justify-center rounded-xl border-2 border-slate-200 bg-white px-8 py-3 text-sm font-bold text-slate-700 transition-all hover:border-slate-300 hover:bg-slate-50">
                            Adjust Preferences
                        </button>
                    </div>
                </template>
            </div>
            
             {{-- Footer Removed as per request (Close button moved to header/backdrop) --}}
        </div>
    </div>
</div>

<script>
    class RecommendationModalStrictController {
        /**
         * @param {string} initialTypeIdentifier
         */
        constructor(initialTypeIdentifier) {
            this.isOpen = false;
            this.loading = false;
            this.items = [];
            this.needsSetup = false;
            this.needsLogin = false;
            this.editMode = false;
            this.type = initialTypeIdentifier;
            this.prefs = {
                skills: '',
                interests: '',
                learning_style: ''
            };
        }

        /**
         * @return {void}
         */
        open() {
            this.isOpen = true;
            this.fetchRecommendations();
        }

        /**
         * @return {void}
         */
        close() {
            this.isOpen = false;
        }

        /**
         * @return {Promise<void>}
         */
        async fetchRecommendations() {
            this.loading = true;
            
            let targetEndpointUrl = '';
            let serverResponse = null;
            let responseDataPayload = null;
            
            try {
                if (this.type === 'job') {
                    targetEndpointUrl = '{{ route("recommendations.jobs") }}';
                } else {
                    targetEndpointUrl = '{{ route("recommendations.courses") }}';
                }

                serverResponse = await fetch(targetEndpointUrl);
                responseDataPayload = await serverResponse.json();

                if (responseDataPayload.needsSetup) {
                    this.needsSetup = true;
                } else {
                    this.needsSetup = false;
                }

                if (responseDataPayload.needsLogin) {
                    this.needsLogin = true;
                } else {
                    this.needsLogin = false;
                }

                if (responseDataPayload.items) {
                    this.items = responseDataPayload.items;
                } else {
                    this.items = [];
                }

            } catch (caughtException) {
                console.error('Error fetching recommendations:', caughtException);
            }
            
            this.loading = false;
        }

        /**
         * @return {Promise<void>}
         */
        async savePreferences() {
            this.loading = true;

            let processedSkillsList = [];
            let processedInterestsList = [];
            let rawSkillsArray = [];
            let rawInterestsArray = [];
            let payloadData = {};
            let serverResponse = null;
            let currentSkillItem = '';
            let currentInterestItem = '';

            try {
                if (this.prefs.skills) {
                    rawSkillsArray = this.prefs.skills.split(',');
                    for (let i = 0; i < rawSkillsArray.length; i++) {
                        currentSkillItem = rawSkillsArray[i];
                        processedSkillsList.push(currentSkillItem.trim());
                    }
                } else {
                    processedSkillsList = [];
                }

                if (this.prefs.interests) {
                    rawInterestsArray = this.prefs.interests.split(',');
                    for (let i = 0; i < rawInterestsArray.length; i++) {
                        currentInterestItem = rawInterestsArray[i];
                        processedInterestsList.push(currentInterestItem.trim());
                    }
                } else {
                    processedInterestsList = [];
                }

                payloadData = {
                    skills: processedSkillsList,
                    interests: processedInterestsList,
                    learning_style: this.prefs.learning_style
                };

                serverResponse = await fetch('{{ route("recommendations.preferences") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payloadData)
                });
                
                if (serverResponse.ok) {
                    this.editMode = false;
                    await this.fetchRecommendations();
                } else {
                    console.error('Save failed:', serverResponse.status);
                }

            } catch (caughtException) {
                console.error('Error saving preferences:', caughtException);
            }
            
            this.loading = false;
        }

        /**
         * @param {object} recommendationItemObject
         * @return {string}
         */
        getTitle(recommendationItemObject) {
            let titleString = '';
            if (this.type === 'job') {
                titleString = recommendationItemObject.job.title;
            } else {
                titleString = recommendationItemObject.course.title;
            }
            return titleString;
        }

        /**
         * @param {object} recommendationItemObject
         * @return {string}
         */
        getSubtitle(recommendationItemObject) {
            let subtitleString = '';
            if (this.type === 'job') {
                if (recommendationItemObject.company_name) {
                    subtitleString = recommendationItemObject.company_name;
                } else {
                    subtitleString = 'Company';
                }
            } else {
                if (recommendationItemObject.course.category) {
                    subtitleString = recommendationItemObject.course.category;
                } else {
                    subtitleString = 'Course';
                }
            }
            return subtitleString;
        }

        /**
         * @param {object} recommendationItemObject
         * @return {string}
         */
        getLink(recommendationItemObject) {
            let urlString = '';
            if (this.type === 'job') {
                urlString = '/jobs/' + recommendationItemObject.job.id;
            } else {
                urlString = '/courses/' + recommendationItemObject.course.slug;
            }
            return urlString;
        }

        /**
         * @param {object} targetItemToDismiss
         * @return {void}
         */
        dismiss(targetItemToDismiss) {
            let newItemsList = [];
            let currentItem = null;
            
            for (let i = 0; i < this.items.length; i++) {
                currentItem = this.items[i];
                if (currentItem !== targetItemToDismiss) {
                    newItemsList.push(currentItem);
                }
            }
            this.items = newItemsList;
        }
    }

    function recommendationModal(initialType) {
        return new RecommendationModalStrictController(initialType);
    }
</script>
