<footer class="relative z-10 border-t border-slate-200 bg-white py-10 mt-auto">
    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
        <div class="text-slate-500 font-medium text-sm">
            &copy; {{ date('Y') }} <span class="text-slate-900 font-bold">AbleLink</span>. Building bridges for better care.
        </div>
        <div class="flex items-center space-x-6 text-sm font-medium">
            <a href="#" class="text-slate-500 hover:text-blue-600 transition-colors">Privacy Policy</a>
            <a href="#" class="text-slate-500 hover:text-blue-600 transition-colors">Terms of Service</a>
            @guest
                <a href="{{ route('admin.login') }}" class="text-slate-400 hover:text-slate-600 transition-colors text-xs uppercase tracking-wider">
                    Admin Portal
                </a>
            @endguest
        </div>
    </div>
</footer>
