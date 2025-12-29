@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('caregiver.appointments.index') }}" class="inline-flex items-center text-slate-500 hover:text-slate-900 font-medium transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Appointments
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-8 py-8 border-b border-slate-100 bg-white">
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Schedule New Appointment</h1>
                <div class="mt-2 flex items-center text-slate-500">
                    <span class="mr-2">For Patient:</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-50 text-indigo-700">
                        {{ $user->name }}
                    </span>
                </div>
            </div>

            <form action="{{ route('caregiver.appointments.store', $user) }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Doctor & Clinic Section -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-100 pb-2">Medical Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Doctor Name <span class="text-red-500">*</span></label>
                            <input type="text" name="doctor_name" required value="{{ old('doctor_name', request('doctor_name', '')) }}"
                                   placeholder="e.g. Dr. Sarah Smith"
                                   class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">
                            @error('doctor_name')
                                <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Specialization</label>
                            <input type="text" name="specialization" value="{{ old('specialization', request('specialization', '')) }}"
                                   placeholder="e.g. Cardiologist"
                                   class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Clinic / Hospital Name</label>
                            <input type="text" name="clinic_name" value="{{ old('clinic_name', request('clinic_name', '')) }}"
                                   placeholder="e.g. City General Hospital"
                                   class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Contact Phone</label>
                            <input type="tel" name="contact_phone" value="{{ old('contact_phone', request('contact_phone', '')) }}"
                                   placeholder="e.g. +1 234 567 8900"
                                   class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Clinic Address</label>
                        <textarea name="clinic_address" rows="2"
                                  placeholder="Full address of the clinic or hospital"
                                  class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">{{ old('clinic_address', request('clinic_address', '')) }}</textarea>
                    </div>
                </div>

                <!-- Date & Time Section -->
                <div class="space-y-6 pt-4">
                    <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-100 pb-2">Schedule</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="appointment_date" required value="{{ old('appointment_date', request('appointment_date', '')) }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">
                            @error('appointment_date')
                                <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Time <span class="text-red-500">*</span></label>
                            <input type="time" name="appointment_time" required value="{{ old('appointment_time', request('appointment_time', '')) }}"
                                   class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">
                            @error('appointment_time')
                                <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="space-y-6 pt-4">
                    <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wide border-b border-slate-100 pb-2">Additional Info</h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Reason for Visit</label>
                        <textarea name="reason" rows="3"
                                  placeholder="Briefly describe the purpose of the appointment..."
                                  class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">{{ old('reason', request('reason', '')) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Internal Notes</label>
                        <textarea name="notes" rows="3"
                                  placeholder="Any private notes for your reference (not visible to patient)..."
                                  class="block w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-slate-900 placeholder-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-0 transition-all font-medium">{{ old('notes', request('notes', '')) }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-4 pt-8 border-t border-slate-100 mt-8">
                    <a href="{{ route('caregiver.appointments.index') }}"
                       class="px-8 py-4 border border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-all text-base">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-4 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 shadow-lg hover:shadow-xl transition-all text-base">
                        Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
