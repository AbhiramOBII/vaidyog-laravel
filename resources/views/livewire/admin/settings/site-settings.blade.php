<div>
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Site Settings</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Manage your website configuration, SEO, and branding</p>
        </div>
        <button wire:click="save" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white transition-all"
                style="background: linear-gradient(135deg, #464d79 0%, #4ab098 100%);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Save Changes
        </button>
    </div>

    {{-- Success --}}
    @if($showSuccess)
    <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm"
         x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; $wire.set('showSuccess', false) }, 4000)" x-transition>
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Settings saved successfully!
    </div>
    @endif

    {{-- Tabs --}}
    <div class="border-b border-neutral-200 dark:border-neutral-700 mb-6">
        <nav class="flex gap-1 -mb-px">
            @foreach(['general' => 'General', 'seo' => 'SEO', 'social' => 'Social Links', 'appearance' => 'Appearance', 'payment' => 'Payment'] as $tab => $label)
            <button wire:click="setTab('{{ $tab }}')"
                    class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors {{ $activeTab === $tab ? 'border-[#464d79] text-[#464d79] dark:text-white dark:border-white' : 'border-transparent text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300' }}">
                {{ $label }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- General Tab --}}
    @if($activeTab === 'general')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
            <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Site Identity</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Site Name</label>
                    <input type="text" wire:model="site_name" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('site_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Tagline</label>
                    <input type="text" wire:model="site_tagline" placeholder="India's #1 Healthcare Job Portal" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('site_tagline') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
            <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Contact Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Email</label>
                    <input type="email" wire:model="contact_email" placeholder="contact@vaidyog.com" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('contact_email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Phone</label>
                    <input type="text" wire:model="contact_phone" placeholder="+91 9876543210" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('contact_phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Address</label>
                    <textarea wire:model="contact_address" rows="2" placeholder="Office address..." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors resize-none"></textarea>
                    @error('contact_address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- SEO Tab --}}
    @if($activeTab === 'seo')
    <div class="space-y-6">
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
            <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-1">Search Engine Optimization</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-5">These defaults apply site-wide. Individual pages can override them.</p>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Default Meta Title <span class="text-neutral-400 font-normal">(max 70 chars)</span></label>
                    <input type="text" wire:model="meta_title" maxlength="70" placeholder="Vaidyog — Healthcare Jobs India" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    <p class="text-xs text-neutral-400 mt-1">{{ strlen($meta_title) }}/70 characters</p>
                    @error('meta_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Meta Keywords</label>
                    <input type="text" wire:model="meta_keywords" placeholder="healthcare jobs, doctor jobs, nurse jobs..." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('meta_keywords') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Default Meta Description <span class="text-neutral-400 font-normal">(max 160 chars)</span></label>
                    <textarea wire:model="meta_description" maxlength="160" rows="2" placeholder="Find healthcare jobs across India..." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors resize-none"></textarea>
                    <p class="text-xs text-neutral-400 mt-1">{{ strlen($meta_description) }}/160 characters</p>
                    @error('meta_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
            <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-1">Analytics & Verification</h3>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-5">Connect tracking and verification services.</p>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Google Analytics ID</label>
                    <input type="text" wire:model="google_analytics_id" placeholder="G-XXXXXXXXXX" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm font-mono focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('google_analytics_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Google Search Console Verification</label>
                    <input type="text" wire:model="google_search_console" placeholder="Verification meta tag content" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm font-mono focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('google_search_console') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Social Tab --}}
    @if($activeTab === 'social')
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-1">Social Media Links</h3>
        <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-5">These appear in the footer and in schema markup for search engines.</p>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            @foreach([
                'social_facebook' => ['Facebook', 'https://facebook.com/vaidyog', 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                'social_twitter' => ['X (Twitter)', 'https://x.com/vaidyog', 'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'],
                'social_linkedin' => ['LinkedIn', 'https://linkedin.com/company/vaidyog', 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z'],
                'social_instagram' => ['Instagram', 'https://instagram.com/vaidyog', 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 2h11A4.5 4.5 0 0122 6.5v11a4.5 4.5 0 01-4.5 4.5h-11A4.5 4.5 0 012 17.5v-11A4.5 4.5 0 016.5 2z'],
                'social_youtube' => ['YouTube', 'https://youtube.com/@vaidyog', 'M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19.08c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.25 29 29 0 00-.46-5.41z'],
            ] as $field => [$label, $placeholder, $svgPath])
            <div>
                <label class="flex items-center gap-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">
                    <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="{{ $svgPath }}"/></svg>
                    {{ $label }}
                </label>
                <input type="url" wire:model="{{ $field }}" placeholder="{{ $placeholder }}" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                @error($field) <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Appearance Tab --}}
    @if($activeTab === 'appearance')
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Logo --}}
            <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Site Logo</h3>
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 rounded-xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center overflow-hidden border border-neutral-200 dark:border-neutral-700 shrink-0">
                        @if($logo)
                            <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="w-full h-full object-contain p-1"/>
                        @elseif($existing_logo)
                            <img src="{{ asset('storage/' . $existing_logo) }}" alt="Site logo" class="w-full h-full object-contain p-1"/>
                        @else
                            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div>
                        <input type="file" wire:model="logo" accept="image/*" class="text-sm text-neutral-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20"/>
                        <p class="text-xs text-neutral-400 mt-2">PNG, JPG, WEBP. Max 2MB.</p>
                        @error('logo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Favicon --}}
            <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Favicon</h3>
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 rounded-xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center overflow-hidden border border-neutral-200 dark:border-neutral-700 shrink-0">
                        @if($favicon)
                            <img src="{{ $favicon->temporaryUrl() }}" alt="Favicon preview" class="w-full h-full object-contain p-2"/>
                        @elseif($existing_favicon)
                            <img src="{{ asset('storage/' . $existing_favicon) }}" alt="Favicon" class="w-full h-full object-contain p-2"/>
                        @else
                            <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div>
                        <input type="file" wire:model="favicon" accept="image/*" class="text-sm text-neutral-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20"/>
                        <p class="text-xs text-neutral-400 mt-2">ICO, PNG. Max 512KB. Recommended 32x32px.</p>
                        @error('favicon') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
            <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Footer & Announcements</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Footer Copyright Text</label>
                    <input type="text" wire:model="footer_text" placeholder="© 2024 Vaidyog. All rights reserved." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('footer_text') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Announcement Bar Text</label>
                    <input type="text" wire:model="announcement_bar" placeholder="We are hiring! Check out our latest opportunities." class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    @error('announcement_bar') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" wire:model="announcement_active" class="sr-only peer"/>
                        <div class="w-10 h-5 bg-neutral-200 dark:bg-neutral-700 rounded-full peer-checked:bg-[#4ab098] transition-colors"></div>
                        <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm text-neutral-700 dark:text-neutral-300">Show announcement bar</span>
                </label>
            </div>
        </div>
    </div>
    @endif

    {{-- Payment Tab --}}
    @if($activeTab === 'payment')
    <div class="space-y-6">
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
            <div class="flex items-center gap-3 mb-1">
                <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/></svg>
                <h3 class="text-base font-semibold text-neutral-900 dark:text-white">Razorpay Configuration</h3>
            </div>
            <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-5">Manage your Razorpay payment gateway credentials. These are used for processing subscription payments.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Key ID</label>
                    <input type="text" wire:model="razorpay_key_id" placeholder="rzp_live_xxxxxxxxxxxxxx" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm font-mono focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    <p class="text-xs text-neutral-400 mt-1">Your Razorpay API Key ID (starts with <code class="text-neutral-500">rzp_live_</code> or <code class="text-neutral-500">rzp_test_</code>)</p>
                    @error('razorpay_key_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Key Secret</label>
                    <input type="password" wire:model="razorpay_key_secret" placeholder="••••••••••••••••••••" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm font-mono focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    <p class="text-xs text-neutral-400 mt-1">Your Razorpay API Key Secret</p>
                    @error('razorpay_key_secret') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Webhook Secret</label>
                    <input type="password" wire:model="razorpay_webhook_secret" placeholder="••••••••••••••••••••" class="w-full px-3 py-2 rounded-lg border border-neutral-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-white text-sm font-mono focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors"/>
                    <p class="text-xs text-neutral-400 mt-1">Used to verify incoming webhook events from Razorpay</p>
                    @error('razorpay_webhook_secret') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-200 dark:border-amber-800 p-5">
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <div>
                    <h4 class="text-sm font-semibold text-amber-800 dark:text-amber-300">Webhook Setup</h4>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-1">Configure your Razorpay webhook URL in the <a href="https://dashboard.razorpay.com/app/webhooks" target="_blank" class="underline font-medium">Razorpay Dashboard</a> to:</p>
                    <code class="block mt-2 px-3 py-1.5 bg-white dark:bg-neutral-800 rounded text-xs text-neutral-700 dark:text-neutral-300 border border-amber-200 dark:border-amber-700 font-mono">{{ url('/razorpay/webhook') }}</code>
                    <p class="text-xs text-amber-700 dark:text-amber-400 mt-2">Events to subscribe: <span class="font-medium">payment.captured</span>, <span class="font-medium">payment.failed</span></p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Mobile save --}}
    <div class="mt-6 lg:hidden">
        <button wire:click="save" class="w-full py-2.5 rounded-lg text-sm font-semibold text-white"
                style="background: linear-gradient(135deg, #464d79 0%, #4ab098 100%);">
            Save Changes
        </button>
    </div>
</div>
