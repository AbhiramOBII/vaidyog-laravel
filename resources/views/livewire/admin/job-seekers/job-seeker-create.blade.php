<div>
    {{-- Header with sticky save --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Add Job Seeker</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Create a new job seeker account and profile.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.job-seekers.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-neutral-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                Cancel
            </a>
            <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
                <span wire:loading wire:target="save" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                <span wire:loading.remove wire:target="save">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </span>
                Save Job Seeker
            </button>
        </div>
    </div>

    <form wire:submit="save" novalidate>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT COLUMN --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- SECTION 1: Account Info --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                        Account Information
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input wire:model="name" type="text" placeholder="Enter full name" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('name') border-red-400 @enderror"/>
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Phone Number <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-neutral-500 pointer-events-none">+91</span>
                                <input wire:model="phone" type="tel" maxlength="10" placeholder="9876543210" class="w-full h-10 pl-11 pr-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('phone') border-red-400 @enderror"/>
                            </div>
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Email ID</label>
                            <input wire:model="email" type="email" placeholder="name@example.com" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('email') border-red-400 @enderror"/>
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Auth Method --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Auth Method <span class="text-red-500">*</span></label>
                            <select wire:model.live="authMethod" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                                @foreach(\App\Enums\AuthProviderEnum::cases() as $provider)
                                    <option value="{{ $provider->value }}">{{ $provider->label() }}</option>
                                @endforeach
                            </select>
                            @error('authMethod') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Temporary Password (only if email) --}}
                        @if($authMethod === 'email')
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Temporary Password</label>
                                <input wire:model="tempPassword" type="text" placeholder="Min 6 characters" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('tempPassword') border-red-400 @enderror"/>
                                @error('tempPassword') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        {{-- Account Status --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Account Status <span class="text-red-500">*</span></label>
                            <select wire:model="accountStatus" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                                @foreach(\App\Enums\UserStatusEnum::cases() as $s)
                                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                                @endforeach
                            </select>
                            @error('accountStatus') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Professional Classification --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm-2 5a1 1 0 100 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Professional Classification
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Category <span class="text-red-500">*</span></label>
                            <select wire:model.live="categoryId" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('categoryId') border-red-400 @enderror">
                                <option value="">Select category...</option>
                                @foreach($this->categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('categoryId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Subcategory --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Sub-category <span class="text-red-500">*</span></label>
                            <select wire:model="subcategoryId" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all @error('subcategoryId') border-red-400 @enderror" {{ !$categoryId ? 'disabled' : '' }}>
                                <option value="">{{ $categoryId ? 'Select sub-category...' : 'Select category first' }}</option>
                                @foreach($this->subcategories as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                            @error('subcategoryId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Basic Profile --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        Basic Profile <span class="text-xs font-normal text-neutral-400 ml-1">(Optional)</span>
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        {{-- Gender --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Gender</label>
                            <select wire:model="gender" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                                <option value="">Select...</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        {{-- Date of Birth --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Date of Birth</label>
                            <input wire:model="dateOfBirth" type="date" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                            @error('dateOfBirth') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Experience --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Experience (years)</label>
                            <input wire:model="experienceYears" type="number" step="0.5" min="0" max="60" placeholder="e.g. 3.5" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                            @error('experienceYears') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">City</label>
                            <input wire:model="city" type="text" placeholder="City" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                        </div>

                        {{-- State --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">State</label>
                            <input wire:model="state" type="text" placeholder="State" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                        </div>

                        {{-- Pincode --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Pincode</label>
                            <input wire:model="pincode" type="text" maxlength="6" placeholder="110001" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                            @error('pincode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Highest Qualification --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Highest Qualification</label>
                            <input wire:model="highestQualification" type="text" placeholder="e.g. MBBS, B.Sc Nursing" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                        </div>

                        {{-- Current Employer --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Current Employer</label>
                            <input wire:model="currentEmployer" type="text" placeholder="Hospital / Clinic name" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"/>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="space-y-6">
                {{-- SECTION 4: Toggles --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                        Verification & Status
                    </h2>

                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 cursor-pointer hover:border-[#464d79]/40 transition-colors">
                            <div>
                                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Phone Verified</span>
                                <p class="text-xs text-neutral-500 mt-0.5">Mark phone as pre-verified</p>
                            </div>
                            <input wire:model="phoneVerified" type="checkbox" class="w-5 h-5 rounded border-neutral-300 dark:border-neutral-600 accent-[#4ab098] cursor-pointer"/>
                        </label>

                        <label class="flex items-center justify-between p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 cursor-pointer hover:border-[#464d79]/40 transition-colors">
                            <div>
                                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Profile Completed</span>
                                <p class="text-xs text-neutral-500 mt-0.5">Flag profile as complete</p>
                            </div>
                            <input wire:model="profileCompleted" type="checkbox" class="w-5 h-5 rounded border-neutral-300 dark:border-neutral-600 accent-[#4ab098] cursor-pointer"/>
                        </label>
                    </div>
                </div>

                {{-- Info card --}}
                <div class="bg-[#464d79]/5 dark:bg-[#464d79]/10 rounded-xl border border-[#464d79]/10 dark:border-[#464d79]/20 p-5">
                    <h3 class="text-sm font-semibold text-[#464d79] dark:text-[#4ab098] mb-2">Notes</h3>
                    <ul class="text-xs text-neutral-600 dark:text-neutral-400 space-y-1.5 leading-relaxed">
                        <li>• Phone number is mandatory for all job seekers.</li>
                        <li>• Password is only required for email auth method.</li>
                        <li>• Category & sub-category determine the job seeker's classification.</li>
                        <li>• Profiles created by admin are tracked with your admin ID.</li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
