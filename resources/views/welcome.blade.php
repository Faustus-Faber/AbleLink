@extends('layouts.app')

@section('title', 'Ablelink · Empowering Connections')

@section('content')
<div class="relative overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12 pt-10 pb-20">
        
        <div class="lg:w-1/2 text-center lg:text-left space-y-8">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/30 backdrop-blur-md border border-white/40 text-blue-900 font-semibold text-sm shadow-sm">
                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                #1 Accessibility Network
            </div>
            
            <h1 class="text-5xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Connecting <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">Care</span> <br>
                With <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-600">Freedom.</span>
            </h1>
            
            <p class="text-xl text-slate-600 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                AbleLink is the intelligent platform bridging the gap between care professionals, volunteers, and the people who need them most. Experience seamless, secure, and inclusive assistance.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                @auth
                    <a href="{{ route('dashboard') }}" class="group relative px-8 py-4 bg-slate-900 text-white rounded-full font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Go to Dashboard</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ route('documents.upload') }}" class="px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-full font-bold text-lg shadow-md hover:shadow-lg hover:bg-slate-50 hover:-translate-y-1 transition-all duration-300">
                        OCR & Simplify
                    </a>
                @else
                    <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-slate-900 text-white rounded-full font-bold text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Get Started Now</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-slate-900 border border-slate-200 rounded-full font-bold text-lg shadow-md hover:shadow-lg hover:bg-slate-50 hover:-translate-y-1 transition-all duration-300">
                        Login
                    </a>
                    <a href="{{ route('documents.upload') }}" class="px-8 py-4 bg-emerald-600 text-white rounded-full font-bold text-lg shadow-md hover:shadow-lg hover:bg-emerald-700 hover:-translate-y-1 transition-all duration-300">
                        Try OCR & Simplify
                    </a>
                @endauth
            </div>
            
            <div class="pt-8 flex items-center justify-center lg:justify-start gap-8 opacity-80">
                <div class="flex -space-x-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-white"></div>
                    <div class="w-10 h-10 rounded-full bg-purple-100 border-2 border-white"></div>
                    <div class="w-10 h-10 rounded-full bg-pink-100 border-2 border-white"></div>
                    <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white flex items-center justify-center text-xs font-bold text-slate-600">+2k</div>
                </div>
                <div class="text-sm font-medium text-slate-600">
                    Trusted by <span class="font-bold text-slate-900">2,000+</span> families
                </div>
            </div>
        </div>

        <div class="lg:w-1/2 relative">
            <div class="absolute -top-20 -right-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
            
            <div class="relative grid grid-cols-2 gap-6">
                <div class="space-y-6 mt-12">
                    <div class="bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-lg border border-white/50 hover:scale-105 transition-transform duration-500">
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600 mb-4 text-2xl">
                            👨‍⚕️
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg">Expert Care</h3>
                        <p class="text-slate-500 text-sm mt-2">Verified professionals ready to assist 24/7.</p>
                    </div>
                     <div class="bg-white/60 backdrop-blur-xl p-6 rounded-3xl shadow-lg border border-white/50 hover:scale-105 transition-transform duration-500">
                        <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-600 mb-4 text-2xl">
                            🛡️
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg">Secure & Safe</h3>
                        <p class="text-slate-500 text-sm mt-2">OTP-verified interactions for complete peace of mind.</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-xl border border-white/50 hover:scale-105 transition-transform duration-500">
                        <div class="w-12 h-12 bg-pink-100 rounded-2xl flex items-center justify-center text-pink-600 mb-4 text-2xl">
                            🤝
                        </div>
                        <h3 class="font-bold text-slate-900 text-lg">Community</h3>
                        <p class="text-slate-500 text-sm mt-2">Join a supportive network of volunteers and neighbors.</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-600 to-purple-600 p-6 rounded-3xl shadow-xl hover:scale-105 transition-transform duration-500 text-white">
                        <h3 class="font-bold text-2xl">98%</h3>
                        <p class="text-blue-100 text-sm mt-1">Satisfaction Rate</p>
                        <div class="mt-4 h-1 w-full bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white w-[98%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endsection
