<div class="min-h-screen grid grid-cols-1 lg:grid-cols-12">
    {{-- LEFT PANEL (5 cols) — Progress & Brand --}}
    <div class="hidden lg:flex lg:flex-col lg:col-span-5 relative overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1920&q=80')"></div>
        <div class="absolute inset-0 bg-[#464d79]/90"></div>
        <div class="absolute inset-0" style="background: radial-gradient(ellipse at 20% 90%, rgba(0,0,0,0.3) 0%, transparent 60%)"></div>

        <div class="relative z-10 flex flex-col h-full p-10 xl:p-14">
            <div class="mb-auto">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-12 brightness-0 invert">
            </div>

            <div class="max-w-sm">
                <h1 class="text-white font-bold text-3xl leading-tight mb-4">Complete Your Setup</h1>
                <p class="text-white/75 text-base leading-relaxed mb-10">Just two quick steps to get your recruiter dashboard ready.</p>

                {{-- Steps --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-sm font-bold {{ $step >= 1 ? 'bg-white text-[#464d79]' : 'bg-white/20 text-white/60' }} transition-colors">1</div>
                        <div>
                            <div class="text-white font-semibold text-sm">Institution Profile</div>
                            <div class="text-white/60 text-xs mt-0.5">Contact details, address & description</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-sm font-bold {{ $step >= 2 ? 'bg-white text-[#464d79]' : 'bg-white/20 text-white/60' }} transition-colors">2</div>
                        <div>
                            <div class="text-white font-semibold text-sm {{ $step < 2 ? 'opacity-60' : '' }}">Choose a Plan</div>
                            <div class="text-white/60 text-xs mt-0.5">Select a subscription plan to get started</div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-white/15">
                    <p class="text-white/50 text-xs">Logged in as <span class="text-white/80 font-medium">{{ auth()->user()->email }}</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT PANEL (7 cols) — Form --}}
    <div class="lg:col-span-7 bg-white flex items-start justify-center p-6 sm:p-8 lg:p-12 overflow-y-auto min-h-screen">
        <div class="w-full max-w-xl py-6">
            {{-- Mobile logo --}}
            <div class="flex lg:hidden items-center gap-2 mb-6">
                <img src="{{ asset('images/Vaidyog-Logo.webp') }}" alt="Vaidyog" class="h-10">
            </div>

            {{-- Mobile step indicator --}}
            <div class="flex lg:hidden items-center gap-3 mb-6">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $step >= 1 ? 'bg-[#464d79] text-white' : 'bg-neutral-200 text-neutral-500' }}">1</span>
                    <span class="text-xs font-medium {{ $step == 1 ? 'text-neutral-900' : 'text-neutral-400' }}">Profile</span>
                </div>
                <div class="w-8 h-px bg-neutral-200"></div>
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold {{ $step >= 2 ? 'bg-[#464d79] text-white' : 'bg-neutral-200 text-neutral-500' }}">2</span>
                    <span class="text-xs font-medium {{ $step == 2 ? 'text-neutral-900' : 'text-neutral-400' }}">Plan</span>
                </div>
            </div>

            {{-- ======================= STEP 1: PROFILE ======================= --}}
            @if ($step === 1)
                <div class="mb-6">
                    <h2 class="text-2xl font-bold tracking-tight text-neutral-900 mb-1">Complete your profile</h2>
                    <p class="text-sm text-neutral-500">Fill in your institution details so candidates can find you.</p>
                </div>

                <form wire:submit="submitProfile" novalidate class="space-y-5">
                    {{-- Institution Name --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">Institution name <span class="text-red-500">*</span></label>
                        <input wire:model="institutionName" type="text" placeholder="City Hospital" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('institutionName') border-red-400 @enderror"/>
                        @error('institutionName') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Contact Person --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1.5">Contact person <span class="text-red-500">*</span></label>
                            <input wire:model="contactPersonName" type="text" placeholder="Dr. Sharma" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('contactPersonName') border-red-400 @enderror"/>
                            @error('contactPersonName') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1.5">Contact phone</label>
                            <input wire:model="contactPersonPhone" type="tel" placeholder="9876543210" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('contactPersonPhone') border-red-400 @enderror"/>
                            @error('contactPersonPhone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">Contact email</label>
                        <input wire:model="contactPersonEmail" type="email" placeholder="hr@hospital.com" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('contactPersonEmail') border-red-400 @enderror"/>
                        @error('contactPersonEmail') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">About your institution</label>
                        <textarea wire:model="description" rows="3" placeholder="Brief description of your hospital, clinic or organization..." class="w-full px-4 py-3 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all resize-none @error('description') border-red-400 @enderror"></textarea>
                        @error('description') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Website --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">Website</label>
                        <input wire:model="websiteUrl" type="url" placeholder="https://www.hospital.com" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('websiteUrl') border-red-400 @enderror"/>
                        @error('websiteUrl') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Address --}}
                    <div class="pt-2">
                        <h3 class="text-sm font-semibold text-neutral-800 mb-3">Address</h3>
                        <div class="space-y-4">
                            <input wire:model="addressLine1" type="text" placeholder="Address line 1" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                            <input wire:model="addressLine2" type="text" placeholder="Address line 2 (optional)" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                            <div class="grid grid-cols-3 gap-3">
                                <input wire:model="city" type="text" placeholder="City" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                                <input wire:model="state" type="text" placeholder="State" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                                <input wire:model="pincode" type="text" maxlength="6" placeholder="Pincode" class="w-full h-11 px-4 bg-neutral-50 border border-neutral-200 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('pincode') border-red-400 @enderror"/>
                            </div>
                            @error('pincode') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Logo upload --}}
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1.5">Institution logo</label>
                        <label class="flex items-center justify-center w-full h-24 border-2 border-dashed border-neutral-200 rounded-lg cursor-pointer hover:border-[#464d79]/40 hover:bg-neutral-50 transition-colors">
                            <div class="text-center">
                                @if ($logo)
                                    <p class="text-sm text-[#464d79] font-medium">{{ $logo->getClientOriginalName() }}</p>
                                    <p class="text-xs text-neutral-400 mt-0.5">Click to change</p>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-auto text-neutral-400 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <p class="text-xs text-neutral-500">Upload logo (max 2MB)</p>
                                @endif
                            </div>
                            <input wire:model="logo" type="file" accept="image/*" class="hidden"/>
                        </label>
                        @error('logo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full h-12 bg-[#464d79] hover:bg-[#3a4169] active:bg-[#2f3357] text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 transition-colors shadow-sm hover:shadow-md disabled:opacity-70 disabled:cursor-not-allowed mt-2">
                        <span wire:loading wire:target="submitProfile" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                        <span wire:loading.remove wire:target="submitProfile" class="flex items-center gap-2">
                            Continue to Plans
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L11.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 11-1.04-1.08l3.158-2.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                        </span>
                    </button>
                </form>

            {{-- ======================= STEP 2: PLAN ======================= --}}
            @elseif ($step === 2)
                <div class="mb-6">
                    <h2 class="text-2xl font-bold tracking-tight text-neutral-900 mb-1">Choose your plan</h2>
                    <p class="text-sm text-neutral-500">Pick a plan that suits your hiring needs. You can upgrade anytime.</p>
                </div>

                @if ($plans->isEmpty())
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto text-neutral-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <p class="text-neutral-500 text-sm mb-4">No plans available for your institution type yet.</p>
                        <button wire:click="skipPlan" class="h-11 px-8 bg-[#464d79] hover:bg-[#3a4169] text-white font-semibold rounded-lg text-sm transition-colors">
                            Continue to Dashboard
                        </button>
                    </div>
                @else
                    <div class="space-y-4 mb-6">
                        @foreach ($plans as $plan)
                            @foreach ($plan->options as $option)
                                <label wire:click="$set('selectedPlanOptionId', '{{ $option->id }}')"
                                    class="block p-5 rounded-xl border-2 cursor-pointer transition-all
                                    {{ $selectedPlanOptionId == $option->id
                                        ? 'border-[#464d79] bg-[#464d79]/5 shadow-sm'
                                        : 'border-neutral-200 hover:border-neutral-300 hover:bg-neutral-50' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-neutral-900">{{ $plan->name }}</span>
                                                @if ($option->price <= 0)
                                                    <span class="text-xs font-semibold bg-green-100 text-green-700 px-2 py-0.5 rounded-full">FREE</span>
                                                @endif
                                                <span class="text-xs text-neutral-400">{{ $option->label }}</span>
                                            </div>
                                            <div class="flex items-center gap-3 text-xs text-neutral-500 mt-1">
                                                @if ($option->is_unlimited_postings)
                                                    <span class="flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        Unlimited job postings
                                                    </span>
                                                @elseif ($option->job_postings_per_month)
                                                    <span class="flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        {{ $option->job_postings_per_month }} jobs/month
                                                    </span>
                                                @endif
                                                <span>{{ $option->duration_type->label() }}</span>
                                            </div>
                                            @if (is_array($plan->description) && count($plan->description) > 0)
                                                <ul class="mt-2 space-y-1">
                                                    @foreach ($plan->description as $feature)
                                                        <li class="flex items-center gap-1.5 text-xs text-neutral-500">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-green-500 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                            {{ $feature }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        <div class="text-right ml-4 shrink-0">
                                            @if ($option->price <= 0)
                                                <span class="text-2xl font-bold text-neutral-900">Free</span>
                                            @else
                                                <span class="text-2xl font-bold text-neutral-900">&#8377;{{ number_format($option->price, 0) }}</span>
                                                <span class="text-xs text-neutral-400 block">/{{ $option->duration_type->label() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <input type="radio" name="plan" value="{{ $option->id }}" class="hidden" {{ $selectedPlanOptionId == $option->id ? 'checked' : '' }}/>
                                </label>
                            @endforeach
                        @endforeach
                    </div>

                    @error('selectedPlanOptionId') <p class="text-xs text-red-600 mb-4">{{ $message }}</p> @enderror

                    <div class="flex items-center gap-3">
                        <button wire:click="goBack" type="button" class="h-12 px-6 border border-neutral-200 rounded-lg text-sm font-medium text-neutral-700 hover:bg-neutral-50 transition-colors">
                            Back
                        </button>
                        <button wire:click="selectPlan" wire:loading.attr="disabled" class="flex-1 h-12 bg-[#464d79] hover:bg-[#3a4169] active:bg-[#2f3357] text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2 transition-colors shadow-sm hover:shadow-md disabled:opacity-70 disabled:cursor-not-allowed">
                            <span wire:loading wire:target="selectPlan" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span wire:loading.remove wire:target="selectPlan" class="flex items-center gap-2">
                                Activate Plan & Continue
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L11.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 11-1.04-1.08l3.158-2.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
                            </span>
                        </button>
                    </div>

                    <p class="mt-4 text-center">
                        <button wire:click="skipPlan" class="text-sm text-neutral-400 hover:text-neutral-600 transition-colors">Skip for now — I'll choose later</button>
                    </p>
                @endif
            @endif
        </div>
    </div>
</div>
