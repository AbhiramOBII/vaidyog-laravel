@use('Illuminate\Support\Facades\Storage')
<div class="max-w-4xl mx-auto space-y-8">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Edit Profile</h1>
            <p class="text-sm text-neutral-500 mt-1">Update your personal and professional information</p>
        </div>
        <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            View Profile
        </a>
    </div>

    {{-- Profile Picture Section --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4">Profile Picture</h2>
        <div class="flex items-center gap-6">
            <div class="relative">
                @if ($currentProfilePicture)
                    <img src="{{ Storage::url($currentProfilePicture) }}" class="w-24 h-24 rounded-full object-cover border-2 border-neutral-200" alt="Profile">
                @else
                    <div class="w-24 h-24 rounded-full bg-[#4ab098]/10 flex items-center justify-center border-2 border-dashed border-[#4ab098]/30">
                        <svg class="w-8 h-8 text-[#4ab098]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <input type="file" wire:model="profilePicture" accept="image/jpeg,image/png,image/webp" class="text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20">
                <p class="text-xs text-neutral-400 mt-2">JPEG, PNG or WebP. Max 2MB.</p>
                <div wire:loading wire:target="profilePicture" class="text-xs text-[#464d79] mt-1 font-medium">Uploading file...</div>
                @error('profilePicture') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <div class="flex items-center gap-3 mt-3">
                    @if ($profilePicture)
                        <button type="button" wire:click="uploadProfilePicture" wire:loading.attr="disabled" wire:target="uploadProfilePicture" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="uploadProfilePicture">Upload</span>
                            <span wire:loading wire:target="uploadProfilePicture">Saving...</span>
                        </button>
                    @endif
                    @if ($currentProfilePicture)
                        <button type="button" wire:click="removeProfilePicture" wire:confirm="Remove profile picture?" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Remove</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Personal Information --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-6" x-data="{ saved: false }" @saved-personal.window="saved = true; setTimeout(() => saved = false, 3000)">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-neutral-900">Personal Information</h2>
            <span x-show="saved" x-transition.opacity class="text-sm font-medium text-green-600">Saved &#10003;</span>
        </div>
        <form wire:submit="savePersonalInfo" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="first_name" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="First name">
                    @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="last_name" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="Last name">
                    @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Date of Birth <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="date_of_birth" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                    @error('date_of_birth') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Gender</label>
                    <select wire:model="gender" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer_not_to_say">Prefer not to say</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Phone <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="phone" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="10-digit mobile">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Email</label>
                    <input type="email" value="{{ $email }}" disabled class="w-full px-3 py-2.5 border border-neutral-200 rounded-lg text-sm bg-neutral-50 text-neutral-500 cursor-not-allowed">
                    <p class="text-xs text-neutral-400 mt-1">Email cannot be changed here.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">City <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="city" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="City">
                    @error('city') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">State <span class="text-red-500">*</span></label>
                    <select wire:model="state" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="">Select State</option>
                        @foreach ($indianStates as $st)
                            <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                    @error('state') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Pincode</label>
                    <input type="text" wire:model="pincode" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="6-digit pincode">
                    @error('pincode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Nationality <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nationality" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="Nationality">
                    @error('nationality') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="pt-4 border-t border-neutral-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Save Personal Info</button>
            </div>
        </form>
    </div>

    {{-- Professional Information --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-6" x-data="{ saved: false }" @saved-professional.window="saved = true; setTimeout(() => saved = false, 3000)">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-neutral-900">Professional Identity</h2>
            <span x-show="saved" x-transition.opacity class="text-sm font-medium text-green-600">Saved &#10003;</span>
        </div>
        <form wire:submit="saveProfessionalInfo" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Designation <span class="text-red-500">*</span></label>
                    <select wire:model.live="designation" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="">Select Designation</option>
                        @foreach ($designations as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                    @error('designation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Sub-designation <span class="text-red-500">*</span></label>
                    <select wire:model="subdesignation" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" {{ empty($subDesignations) ? 'disabled' : '' }}>
                        <option value="">Select Sub-designation</option>
                        @foreach ($subDesignations as $sd)
                            <option value="{{ $sd }}">{{ $sd }}</option>
                        @endforeach
                    </select>
                    @error('subdesignation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Experience (Years)</label>
                    <input type="number" wire:model="experience_years" step="0.5" min="0" max="60" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="e.g. 3.5">
                    @error('experience_years') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Current Employer</label>
                    <input type="text" wire:model="current_employer" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="Hospital / Institution name">
                    @error('current_employer') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Highest Qualification</label>
                    <select wire:model="highest_qualification" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="">Select Qualification</option>
                        <option value="10th">10th</option>
                        <option value="12th">12th</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Bachelor's">Bachelor's</option>
                        <option value="Master's">Master's</option>
                        <option value="MD">MD</option>
                        <option value="MS">MS</option>
                        <option value="MBBS">MBBS</option>
                        <option value="BDS">BDS</option>
                        <option value="BAMS">BAMS</option>
                        <option value="BHMS">BHMS</option>
                        <option value="BPT">BPT</option>
                        <option value="B.Sc Nursing">B.Sc Nursing</option>
                        <option value="GNM">GNM</option>
                        <option value="ANM">ANM</option>
                        <option value="Ph.D">Ph.D</option>
                        <option value="DM">DM</option>
                        <option value="MCh">MCh</option>
                        <option value="DNB">DNB</option>
                        <option value="Other">Other</option>
                    </select>
                    @error('highest_qualification') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Job Category</label>
                    <select wire:model.live="category_slug" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="">Select Category</option>
                        @foreach ($jobCategories as $slug => $name)
                            <option value="{{ $slug }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('category_slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Sub-category</label>
                    <select wire:model="subcategory_name" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" {{ empty($jobSubcategories) ? 'disabled' : '' }}>
                        <option value="">Select Sub-category</option>
                        @foreach ($jobSubcategories as $sc)
                            <option value="{{ $sc }}">{{ $sc }}</option>
                        @endforeach
                    </select>
                    @error('subcategory_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-1">Specialty</label>
                    <select wire:model="specialty_id" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors">
                        <option value="">Select Specialty</option>
                        @foreach ($specialtiesList as $spec)
                            <option value="{{ $spec['id'] }}">{{ $spec['name'] }}</option>
                        @endforeach
                    </select>
                    @error('specialty_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Skills --}}
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-1">Skills <span class="text-red-500">*</span></label>
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach ($skills as $i => $skill)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#4ab098]/10 text-[#4ab098] text-sm font-medium rounded-full">
                            {{ $skill }}
                            <button type="button" wire:click="removeSkill({{ $i }})" class="ml-1 text-[#4ab098]/60 hover:text-red-500 transition-colors">&times;</button>
                        </span>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <input type="text" wire:model="skillInput" wire:keydown.enter.prevent="addSkill" class="flex-1 px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors" placeholder="Type a skill and press Enter">
                    <button type="button" wire:click="addSkill" class="px-4 py-2.5 text-sm font-medium text-[#464d79] border border-[#464d79]/30 rounded-lg hover:bg-[#464d79]/5 transition-colors">Add</button>
                </div>
                @error('skills') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-neutral-400 mt-1">{{ count($skills) }}/30 skills added. Minimum 1 required.</p>
            </div>

            {{-- About --}}
            <div x-data="{ chars: $wire.about.length }">
                <label class="block text-sm font-medium text-neutral-700 mb-1">About / Summary</label>
                <textarea wire:model="about" x-on:input="chars = $event.target.value.length" rows="4" maxlength="1000" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm focus:ring-2 focus:ring-[#464d79]/20 focus:border-[#464d79] transition-colors resize-none" placeholder="Write a short bio about yourself (min 50 characters)"></textarea>
                <div class="flex justify-between mt-1">
                    @error('about') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                    <p class="text-xs text-neutral-400 ml-auto"><span x-text="chars"></span>/1000</p>
                </div>
            </div>

            {{-- Open to Work --}}
            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_open_to_work" class="sr-only peer">
                    <div class="w-11 h-6 bg-neutral-200 peer-focus:ring-2 peer-focus:ring-[#4ab098]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4ab098]"></div>
                </label>
                <span class="text-sm font-medium text-neutral-700">Open to Work</span>
                @if ($is_open_to_work)
                    <span class="px-2 py-0.5 text-xs font-medium text-green-700 bg-green-100 rounded-full">Visible to recruiters</span>
                @endif
            </div>

            <div class="pt-4 border-t border-neutral-100 flex justify-end">
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Save Professional Info</button>
            </div>
        </form>
    </div>

    {{-- Languages Known --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4">Languages Known</h2>
        <livewire:job-seeker.profile.sub-models.language-manager />
    </div>

    {{-- Resume Section --}}
    <div class="bg-white rounded-xl border border-neutral-200 p-6">
        <h2 class="text-lg font-semibold text-neutral-900 mb-4">Resume</h2>
        @if ($currentResume)
            <div class="flex items-center gap-4 p-4 bg-neutral-50 rounded-lg border border-neutral-200 mb-4">
                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                <div class="flex-1">
                    <p class="text-sm font-medium text-neutral-900">Resume uploaded</p>
                    <p class="text-xs text-neutral-500">{{ basename($currentResume) }}</p>
                </div>
                <a href="{{ Storage::url($currentResume) }}" target="_blank" class="px-3 py-1.5 text-xs font-medium text-[#464d79] border border-[#464d79]/30 rounded-lg hover:bg-[#464d79]/5">Download</a>
                <button wire:click="removeResume" wire:confirm="Delete resume?" class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50">Delete</button>
            </div>
        @endif
        <div>
            <input type="file" wire:model="resume" accept="application/pdf" class="text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20">
            <p class="text-xs text-neutral-400 mt-2">PDF only. Max 5MB.</p>
            @error('resume') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            @if ($resume)
                <button wire:click="uploadResume" class="mt-3 px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Upload Resume</button>
            @endif
        </div>
    </div>
</div>
