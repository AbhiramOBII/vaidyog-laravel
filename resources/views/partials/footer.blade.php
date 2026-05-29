<footer class="bg-[#1a1f3d] text-gray-300" itemscope itemtype="https://schema.org/Organization">
    <meta itemprop="name" content="Vaidyog"/>
    <meta itemprop="url" content="{{ url('/') }}"/>
    <meta itemprop="logo" content="{{ asset('images/Vaidyog-Logo.webp') }}"/>

    {{-- Top CTA strip --}}
    <div class="border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Get the latest healthcare job alerts delivered to your inbox</h3>
                    <p class="text-sm text-gray-400 mt-1">Join 50,000+ healthcare professionals. No spam, unsubscribe anytime.</p>
                </div>
                <livewire:newsletter-subscribe />
            </div>
        </div>
    </div>

    {{-- Main footer grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8">
            {{-- Brand & Description --}}
            <div class="lg:col-span-2">
                <a href="/" class="inline-block mb-4" itemprop="url">
                    <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog - India's Healthcare Job Portal" class="h-14 w-auto brightness-0 invert" itemprop="logo"/>
                </a>
                <p class="text-sm text-gray-400 mb-4 max-w-sm leading-relaxed">Vaidyog is India's leading healthcare job portal connecting doctors, nurses, pharmacists, and allied health professionals with top hospitals and clinics across 500+ cities.</p>

                {{-- Contact info --}}
                <div class="space-y-2 mb-5" itemprop="contactPoint" itemscope itemtype="https://schema.org/ContactPoint">
                    <meta itemprop="contactType" content="customer service"/>
                    <p class="text-sm text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:contact@vaidyog.com" class="hover:text-teal-300 transition-colors" itemprop="email">contact@vaidyog.com</a>
                    </p>
                    <p class="text-sm text-gray-400 flex items-center gap-2">
                        <svg class="w-4 h-4 text-teal-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span itemprop="address">India</span>
                    </p>
                </div>

                {{-- Social --}}
                <div class="flex items-center gap-3">
                    @if(\App\Models\SiteSetting::get('social_linkedin'))
                    <a href="{{ \App\Models\SiteSetting::get('social_linkedin') }}" target="_blank" aria-label="LinkedIn" rel="noopener" class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 001.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg></a>
                    @endif
                    @if(\App\Models\SiteSetting::get('social_facebook'))
                    <a href="{{ \App\Models\SiteSetting::get('social_facebook') }}" target="_blank" aria-label="Facebook" rel="noopener" class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg></a>
                    @endif
                    @if(\App\Models\SiteSetting::get('social_instagram'))
                    <a href="{{ \App\Models\SiteSetting::get('social_instagram') }}" target="_blank" aria-label="Instagram" rel="noopener" class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg></a>
                    @endif
                    @if(\App\Models\SiteSetting::get('social_twitter'))
                    <a href="{{ \App\Models\SiteSetting::get('social_twitter') }}" target="_blank" aria-label="Twitter / X" rel="noopener" class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>
                    @endif
                    @if(\App\Models\SiteSetting::get('social_youtube'))
                    <a href="{{ \App\Models\SiteSetting::get('social_youtube') }}" target="_blank" aria-label="YouTube" rel="noopener" class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                    @endif
                </div>
            </div>

            {{-- For Job Seekers --}}
            <nav aria-label="Job Seeker Links">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wide mb-4">For Job Seekers</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-teal-300 transition-colors">Browse Healthcare Jobs</a></li>
                    <li><a href="{{ route('jobseeker.register') }}" class="hover:text-teal-300 transition-colors">Register as Job Seeker</a></li>
                    <li><a href="{{ route('plans.index') }}" class="hover:text-teal-300 transition-colors">Job Seeker Plans</a></li>
                    <li><a href="{{ route('blogs.index') }}" class="hover:text-teal-300 transition-colors">Career Advice & Blog</a></li>
                    <li><a href="{{ route('jobs.index', ['employment_type' => 'full-time']) }}" class="hover:text-teal-300 transition-colors">Full-Time Jobs</a></li>
                    <li><a href="{{ route('jobs.index', ['employment_type' => 'part-time']) }}" class="hover:text-teal-300 transition-colors">Part-Time Jobs</a></li>
                    <li><a href="{{ route('jobs.index', ['employment_type' => 'contractual']) }}" class="hover:text-teal-300 transition-colors">Contractual Jobs</a></li>
                </ul>
            </nav>

            {{-- For Recruiters --}}
            <nav aria-label="Recruiter Links">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wide mb-4">For Recruiters</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('recruiter.register') }}" class="hover:text-teal-300 transition-colors">Post Healthcare Jobs</a></li>
                    <li><a href="{{ route('recruiter.login') }}" class="hover:text-teal-300 transition-colors">Recruiter Login</a></li>
                    <li><a href="{{ route('plans.index') }}" class="hover:text-teal-300 transition-colors">Recruiter Plans & Pricing</a></li>
                    <li><a href="{{ route('recruiter.register') }}" class="hover:text-teal-300 transition-colors">Hospital Registration</a></li>
                    <li><a href="{{ route('recruiter.register') }}" class="hover:text-teal-300 transition-colors">Clinic Registration</a></li>
                    <li><a href="{{ route('blogs.index') }}" class="hover:text-teal-300 transition-colors">Hiring Tips</a></li>
                </ul>
            </nav>

            {{-- Company & Legal --}}
            <nav aria-label="Company Links">
                <h4 class="text-sm font-semibold text-white uppercase tracking-wide mb-4">Company</h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('blogs.index') }}" class="hover:text-teal-300 transition-colors">Blog</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-teal-300 transition-colors">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-teal-300 transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('disclaimer') }}" class="hover:text-teal-300 transition-colors">Disclaimer</a></li>
                    <li><a href="mailto:contact@vaidyog.com" class="hover:text-teal-300 transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Account Deletion</a></li>
                    <li><a href="#" class="hover:text-teal-300 transition-colors">Sitemap</a></li>
                </ul>
            </nav>
        </div>

        {{-- App download & Popular searches --}}
        <div class="mt-10 pt-8 border-t border-white/10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Download App --}}
                <div>
                    <h4 class="text-sm font-semibold text-white mb-3">Download the Vaidyog App</h4>
                    <div class="flex gap-3">
                        <a href="#"><img src="{{ asset('images/playstore.svg') }}" alt="Download Vaidyog on Google Play" class="h-10"/></a>
                        <a href="#"><img src="{{ asset('images/istore.svg') }}" alt="Download Vaidyog on App Store" class="h-10"/></a>
                    </div>
                </div>

                {{-- Popular Searches (SEO-rich internal links) --}}
                <div>
                    <h4 class="text-sm font-semibold text-white mb-3">Popular Searches</h4>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Doctor Jobs</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Nursing Jobs</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Pharmacist Jobs</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Physiotherapy Jobs</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Hospital Jobs Delhi</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Healthcare Jobs Mumbai</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">MBBS Jobs India</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Ayurveda Jobs</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Lab Technician Jobs</a>
                        <a href="{{ route('jobs.index') }}" class="text-xs px-3 py-1.5 rounded-full bg-white/5 text-gray-400 hover:text-teal-300 hover:bg-white/10 transition-colors">Radiology Jobs</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-400">&copy; {{ date('Y') }} Vaidyog Healthcare Private Limited. All rights reserved.</p>
            <div class="flex items-center gap-4 text-xs text-gray-500">
                <a href="{{ route('terms') }}" class="hover:text-gray-300 transition-colors">Terms</a>
                <a href="{{ route('privacy') }}" class="hover:text-gray-300 transition-colors">Privacy</a>
                <a href="{{ route('disclaimer') }}" class="hover:text-gray-300 transition-colors">Disclaimer</a>
                <span>Made in India 🇮🇳</span>
                <span>Powered by <a href="https://www.obiikriationz.com" target="_blank" rel="noopener" class="hover:text-teal-300 transition-colors">Obii Kriationz Web LLP</a></span>
            </div>
        </div>
    </div>
</footer>
