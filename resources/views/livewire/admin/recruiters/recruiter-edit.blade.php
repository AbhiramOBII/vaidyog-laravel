<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Edit Recruiter</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">{{ $recruiter->profile?->institution_name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.recruiters.show', $recruiter) }}" wire:navigate class="px-4 py-2.5 text-sm font-medium text-neutral-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 transition-colors">Cancel</a>
            <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors disabled:opacity-70">
                <span wire:loading wire:target="save" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                <span wire:loading.remove wire:target="save">Save Changes</span>
            </button>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-[#4ab098]/10 border border-[#4ab098]/20 text-[#4ab098] text-sm font-medium">{{ session('message') }}</div>
    @endif

    <form wire:submit="save" novalidate>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                {{-- Account --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Account</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Institution Name <span class="text-red-500">*</span></label>
                            <input wire:model="institutionName" type="text" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 @error('institutionName') border-red-400 @enderror"/>
                            @error('institutionName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input wire:model="email" type="email" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 @error('email') border-red-400 @enderror"/>
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Phone</label>
                            <input wire:model="phone" type="tel" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                            @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Institution --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Institution Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Med Type <span class="text-red-500">*</span></label>
                            <select wire:model="medType" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                                @foreach($medTypes as $mt)<option value="{{ $mt->value }}">{{ $mt->label() }}</option>@endforeach
                            </select>
                            @error('medType') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Industry Type</label>
                            <input wire:model="industryType" type="text" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Contact Person</label>
                            <input wire:model="contactPersonName" type="text" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Contact Email</label>
                            <input wire:model="contactPersonEmail" type="email" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Contact Phone</label>
                            <input wire:model="contactPersonPhone" type="tel" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Website</label>
                            <input wire:model="websiteUrl" type="url" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                            @error('websiteUrl') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Profile --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Profile</h2>
                    <div class="space-y-4">
                        {{-- Logo Upload --}}
                        <div x-data="{ preview: null }" class="flex items-start gap-5">
                            <div class="shrink-0">
                                <div class="w-20 h-20 rounded-xl border-2 border-dashed border-neutral-300 dark:border-neutral-600 overflow-hidden bg-neutral-50 dark:bg-neutral-900 flex items-center justify-center">
                                    <template x-if="preview">
                                        <img :src="preview" class="w-full h-full object-cover"/>
                                    </template>
                                    <template x-if="!preview">
                                        @if($existingLogo)
                                        <img src="{{ asset('storage/' . $existingLogo) }}" class="w-full h-full object-cover"/>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-neutral-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </template>
                                </div>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Institution Logo</label>
                                <input wire:model="logo" type="file" accept="image/*"
                                    @change="const f = $event.target.files[0]; if(f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }"
                                    class="w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20"/>
                                <p class="text-xs text-neutral-500 mt-1">Max 2MB. JPG, PNG, or WebP.</p>
                                @error('logo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                <div wire:loading wire:target="logo" class="text-xs text-[#464d79] mt-1">Uploading...</div>
                            </div>
                        </div>

                        {{-- Description with CKEditor --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Description</label>
                            <div wire:ignore>
                                <div
                                    x-data="{
                                        init() {
                                            ClassicEditor
                                                .create(this.$refs.editor, {
                                                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'undo', 'redo'],
                                                    placeholder: 'Describe the institution...'
                                                })
                                                .then(editor => {
                                                    editor.setData(@js($description));
                                                    editor.model.document.on('change:data', () => {
                                                        @this.set('description', editor.getData());
                                                    });
                                                })
                                                .catch(error => console.error(error));
                                        }
                                    }"
                                >
                                    <div x-ref="editor"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Employee Count</label>
                            <input wire:model="employeeCount" type="number" min="0" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                        </div>
                        {{-- Specialties --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Specialties</label>
                            <div class="flex flex-wrap gap-2 mb-2">
                                @foreach($specialties as $i => $tag)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 text-xs font-medium">{{ $tag }}<button type="button" wire:click="removeSpecialty({{ $i }})" class="hover:text-red-500">&times;</button></span>
                                @endforeach
                            </div>
                            <div class="flex gap-2">
                                <input wire:model="specialtyInput" @keydown.enter.prevent="$wire.addSpecialty()" type="text" placeholder="Add specialty" class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                                <button type="button" wire:click="addSpecialty" class="px-3 h-9 text-xs font-medium bg-neutral-100 dark:bg-neutral-700 rounded-lg hover:bg-neutral-200">Add</button>
                            </div>
                        </div>
                        {{-- Accreditations --}}
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1.5">Accreditations</label>
                            <div class="flex flex-wrap gap-2 mb-2">
                                @foreach($accreditations as $i => $tag)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-[#4ab098]/10 text-[#4ab098] text-xs font-medium">{{ $tag }}<button type="button" wire:click="removeAccreditation({{ $i }})" class="hover:text-red-500">&times;</button></span>
                                @endforeach
                            </div>
                            <div class="flex gap-2">
                                <input wire:model="accreditationInput" @keydown.enter.prevent="$wire.addAccreditation()" type="text" placeholder="Add accreditation" class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                                <button type="button" wire:click="addAccreditation" class="px-3 h-9 text-xs font-medium bg-neutral-100 dark:bg-neutral-700 rounded-lg hover:bg-neutral-200">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Address</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2"><input wire:model="addressLine1" type="text" placeholder="Address Line 1" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/></div>
                        <div class="sm:col-span-2"><input wire:model="addressLine2" type="text" placeholder="Address Line 2" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/></div>
                        <div><input wire:model="city" type="text" placeholder="City" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/></div>
                        <div><input wire:model="state" type="text" placeholder="State" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/></div>
                        <div><input wire:model="pincode" type="text" maxlength="6" placeholder="Pincode" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>@error('pincode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">
                {{-- Status panel --}}
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Status & Flags</h2>
                    <div class="space-y-3">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="setStatus('active')" wire:confirm="Set status to Active?" class="px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ $accountStatus === 'active' ? 'bg-[#4ab098] text-white' : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200' }}">Active</button>
                            <button type="button" wire:click="setStatus('inactive')" wire:confirm="Set status to Inactive?" class="px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ $accountStatus === 'inactive' ? 'bg-neutral-500 text-white' : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200' }}">Inactive</button>
                            <button type="button" wire:click="setStatus('blocked')" wire:confirm="Block this recruiter?" class="px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ $accountStatus === 'blocked' ? 'bg-red-500 text-white' : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200' }}">Blocked</button>
                            <button type="button" wire:click="setStatus('pending_verification')" wire:confirm="Set to Pending?" class="px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ $accountStatus === 'pending_verification' ? 'bg-amber-500 text-white' : 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400 hover:bg-neutral-200' }}">Pending</button>
                        </div>
                        <hr class="border-neutral-100 dark:border-neutral-700">
                        <label class="flex items-center justify-between p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 cursor-pointer">
                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Email Verified</span>
                            <input wire:model="emailVerified" type="checkbox" class="w-5 h-5 rounded accent-[#4ab098] cursor-pointer"/>
                        </label>
                        <label class="flex items-center justify-between p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 cursor-pointer">
                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Featured</span>
                            <input wire:model="isFeatured" type="checkbox" class="w-5 h-5 rounded accent-[#4ab098] cursor-pointer"/>
                        </label>
                        <label class="flex items-center justify-between p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 cursor-pointer">
                            <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Profile Completed</span>
                            <input wire:model="profileCompleted" type="checkbox" class="w-5 h-5 rounded accent-[#4ab098] cursor-pointer"/>
                        </label>
                    </div>
                </div>

                {{-- Referral --}}
                @if($referralCode || in_array($medType, ['larger_hospital', 'enterprise', 'enterprise_branch']))
                <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-3">Referral Code</h2>
                    @if($referralCode)
                        <code class="block px-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm font-mono font-bold text-[#464d79] mb-3">{{ $referralCode }}</code>
                        <button type="button" wire:click="regenerateReferralCode" wire:confirm="Regenerate? The old code will stop working." class="text-xs font-medium text-red-500 hover:text-red-700">Regenerate</button>
                    @else
                        <p class="text-xs text-neutral-500">Will be auto-generated on save.</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
