<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['hero_title'] ?? 'SkillRide' }} - Fleet Management Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-gray-800 antialiased">

    {{-- Navbar --}}
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md border-b border-gray-100 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">SR</span>
                    </div>
                    <span class="text-xl font-bold text-gray-900">SkillRide</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#about" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">About</a>
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">How it Works</a>
                    <a href="#contact" class="text-sm font-medium text-gray-600 hover:text-emerald-600 transition">Contact</a>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-emerald-600 transition">Log in</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition shadow-sm">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 sm:pt-40 sm:pb-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-white to-blue-50"></div>
        <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-pulse"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight">
                <span class="bg-gradient-to-r from-emerald-600 to-blue-600 bg-clip-text text-transparent">
                    {{ $settings['hero_title'] ?? 'Manage Your Fleet with Confidence' }}
                </span>
            </h1>
            <p class="mt-6 text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                {{ $settings['hero_subtitle'] ?? 'The all-in-one platform for managing keke, tricycle, and commercial vehicle fleets. Track vehicles, collect payments, and grow your business.' }}
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 text-base">
                    {{ $settings['hero_cta_text'] ?? 'Start Free Today' }}
                </a>
                <a href="#features" class="px-8 py-3.5 bg-white text-gray-700 font-semibold rounded-xl border border-gray-200 hover:border-emerald-300 hover:text-emerald-600 transition text-base">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    {{-- About Section --}}
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">About SkillRide</span>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">
                        {{ $settings['about_title'] ?? 'Built for Nigerian Fleet Operators' }}
                    </h2>
                    <p class="mt-6 text-lg text-gray-600 leading-relaxed">
                        {{ $settings['about_description'] ?? 'SkillRide is a comprehensive fleet management platform designed specifically for keke napep, tricycle, and commercial vehicle operators across Nigeria. We help fleet owners track their vehicles, manage riders, collect daily payments, and monitor operations in real-time.' }}
                    </p>
                    <div class="mt-8 grid grid-cols-2 gap-6">
                        <div><p class="text-3xl font-bold text-emerald-600">500+</p><p class="text-sm text-gray-500 mt-1">Active Vehicles</p></div>
                        <div><p class="text-3xl font-bold text-emerald-600">98%</p><p class="text-sm text-gray-500 mt-1">Collection Rate</p></div>
                        <div><p class="text-3xl font-bold text-emerald-600">24/7</p><p class="text-sm text-gray-500 mt-1">GPS Tracking</p></div>
                        <div><p class="text-3xl font-bold text-emerald-600">&#8358;0</p><p class="text-sm text-gray-500 mt-1">Setup Cost</p></div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-emerald-100 to-blue-100 rounded-2xl p-8 shadow-inner">
                        <div class="bg-white rounded-xl shadow-xl p-6 space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div><p class="text-sm font-semibold text-gray-800">Payment Received</p><p class="text-xs text-gray-500">Rider Musa - &#8358;2,500 daily</p></div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                </div>
                                <div><p class="text-sm font-semibold text-gray-800">Vehicle Tracked</p><p class="text-xs text-gray-500">ABC-123-XY moving on Route 5</p></div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div><p class="text-sm font-semibold text-gray-800">Rider Clocked In</p><p class="text-xs text-gray-500">Audu started work at 6:30 AM</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">Features</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Everything You Need to Run Your Fleet</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">From GPS tracking to automated payment collection, SkillRide gives you complete control.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $settings['feature_1_title'] ?? 'Real-Time Fleet Tracking' }}</h3>
                    <p class="mt-3 text-gray-600 leading-relaxed">{{ $settings['feature_1_description'] ?? 'Monitor all your keke and tricycles in real-time with GPS tracking. Know where every vehicle is at any moment.' }}</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $settings['feature_2_title'] ?? 'Automated Payment Collection' }}</h3>
                    <p class="mt-3 text-gray-600 leading-relaxed">{{ $settings['feature_2_description'] ?? 'Collect daily, weekly, or monthly payments via Paystack. Automatic reminders, arrears tracking, and receipt generation.' }}</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $settings['feature_3_title'] ?? 'Revenue Analytics & Reports' }}</h3>
                    <p class="mt-3 text-gray-600 leading-relaxed">{{ $settings['feature_3_description'] ?? 'Get detailed reports on revenue, rider performance, and fleet utilization. Export to CSV or PDF anytime.' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-sm font-semibold text-emerald-600 uppercase tracking-wider">How It Works</span>
                <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Get Started in 3 Simple Steps</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto">1</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Register Your Fleet</h3>
                    <p class="mt-3 text-gray-600">Sign up and add your vehicles, keke napep, or tricycles to the platform. Set up payment plans and routes.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto">2</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Assign Riders & Managers</h3>
                    <p class="mt-3 text-gray-600">Add your riders and managers. Assign vehicles to riders and routes to managers for organized operations.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto">3</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Track, Collect & Grow</h3>
                    <p class="mt-3 text-gray-600">Monitor your fleet in real-time, collect payments automatically, and watch your business grow with detailed analytics.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-br from-emerald-600 to-blue-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">
                {{ $settings['cta_title'] ?? 'Ready to Transform Your Fleet Operations?' }}
            </h2>
            <p class="mt-6 text-lg text-emerald-100 max-w-2xl mx-auto">
                {{ $settings['cta_description'] ?? 'Join hundreds of fleet operators across Nigeria who are using SkillRide to streamline their business and increase revenue.' }}
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-white text-emerald-700 font-semibold rounded-xl hover:bg-gray-50 transition shadow-lg text-base">Create Free Account</a>
                <a href="{{ route('login') }}" class="px-8 py-3.5 bg-transparent text-white font-semibold rounded-xl border-2 border-white/30 hover:border-white/60 transition text-base">Sign In</a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer id="contact" class="bg-gray-900 text-gray-400 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-lg flex items-center justify-center"><span class="text-white font-bold text-sm">SR</span></div>
                        <span class="text-xl font-bold text-white">SkillRide</span>
                    </div>
                    <p class="text-gray-400 max-w-md leading-relaxed">The all-in-one fleet management platform for keke napep, tricycle, and commercial vehicle operators across Nigeria.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#about" class="hover:text-emerald-400 transition">About</a></li>
                        <li><a href="#features" class="hover:text-emerald-400 transition">Features</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-emerald-400 transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-emerald-400 transition">Register</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $settings['contact_email'] ?? 'hello@skillride.ng' }}</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <span>{{ $settings['contact_phone'] ?? '+234 800 SKILL RIDE' }}</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <svg class="w-4 h-4 text-emerald-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>{{ $settings['contact_address'] ?? 'Abuja, Nigeria' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm">
                <p>&copy; {{ date('Y') }} SkillRide. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
