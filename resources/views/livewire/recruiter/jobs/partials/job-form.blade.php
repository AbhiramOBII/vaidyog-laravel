{{-- Reusable tag input Alpine component with autocomplete suggestions --}}
<script>
function tagInput(fieldName, initial = [], suggestions = []) {
    return {
        tags: initial,
        input: '',
        suggestions: suggestions,
        filteredSuggestions: [],
        showSuggestions: false,
        highlightIndex: -1,
        add(val) {
            val = (val || this.input).trim();
            if (val && !this.tags.includes(val) && this.tags.length < 20) {
                this.tags.push(val);
                this.sync();
            }
            this.input = '';
            this.showSuggestions = false;
            this.highlightIndex = -1;
        },
        remove(index) {
            this.tags.splice(index, 1);
            this.sync();
        },
        sync() {
            this.$wire.set(fieldName, [...this.tags], false);
        },
        filter() {
            const q = this.input.toLowerCase().trim();
            if (!q) { this.filteredSuggestions = []; this.showSuggestions = false; return; }
            this.filteredSuggestions = this.suggestions
                .filter(s => s.toLowerCase().includes(q) && !this.tags.includes(s))
                .slice(0, 8);
            this.showSuggestions = this.filteredSuggestions.length > 0;
            this.highlightIndex = -1;
        },
        onKeydown(e) {
            if (e.key === 'ArrowDown') { e.preventDefault(); this.highlightIndex = Math.min(this.highlightIndex + 1, this.filteredSuggestions.length - 1); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); this.highlightIndex = Math.max(this.highlightIndex - 1, -1); }
            else if (e.key === 'Enter') { e.preventDefault(); if (this.highlightIndex >= 0) { this.add(this.filteredSuggestions[this.highlightIndex]); } else { this.add(); } }
            else if (e.key === 'Escape') { this.showSuggestions = false; }
        }
    }
}
</script>

<form wire:submit="save" class="space-y-8">
    {{-- Re-approval warning --}}
    @if(isset($wasApproved) && $wasApproved)
    <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">This job is live</p>
                <p class="text-xs text-amber-600 dark:text-amber-300 mt-0.5">Editing will require re-approval from admin before it appears in public listings again.</p>
            </div>
        </div>
    </div>
    @endif

    {{-- SECTION 1: Job Basics --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Job Basics</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Job Title <span class="text-red-500">*</span></label>
                <input wire:model="jobTitle" type="text" maxlength="150" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 @error('jobTitle') border-red-400 @enderror"/>
                @error('jobTitle') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Employment Type <span class="text-red-500">*</span></label>
                <select wire:model="employmentType" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                    @foreach($employmentTypes as $et)<option value="{{ $et->value }}">{{ $et->label() }}</option>@endforeach
                </select>
                @error('employmentType') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Number of Vacancies <span class="text-red-500">*</span></label>
                <input wire:model="numberOfVacancies" type="number" min="1" max="500" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                @error('numberOfVacancies') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Category <span class="text-red-500">*</span></label>
                <select wire:model.live="categorySlug" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                    <option value="">Select category...</option>
                    @foreach($this->categories as $cat)<option value="{{ $cat['slug'] }}">{{ $cat['name'] }}</option>@endforeach
                </select>
                @error('categorySlug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Sub-category <span class="text-red-500">*</span></label>
                <select wire:model="subcategoryName" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20" @if(!$categorySlug) disabled @endif>
                    <option value="">Select sub-category...</option>
                    @foreach($this->subcategories as $sub)<option value="{{ $sub['name'] }}">{{ $sub['name'] }}</option>@endforeach
                </select>
                @error('subcategoryName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Specialty</label>
                <select wire:model="specialtyId" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                    <option value="">Select specialty...</option>
                    @foreach($this->specialtiesList as $spec)<option value="{{ $spec['id'] }}">{{ $spec['name'] }}</option>@endforeach
                </select>
                @error('specialtyId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- SECTION 2: Experience & Salary --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Experience & Salary</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Min Experience (years)</label>
                <input wire:model="experienceMin" type="number" step="0.5" min="0" max="50" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                @error('experienceMin') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Max Experience (years)</label>
                <input wire:model="experienceMax" type="number" step="0.5" min="0" max="50" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                @error('experienceMax') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2" x-data="{ disclosed: @entangle('salaryDisclosed') }">
                <label class="flex items-center gap-2 mb-3 cursor-pointer">
                    <input type="checkbox" x-model="disclosed" class="w-4 h-4 rounded border-neutral-300 text-[#464d79] focus:ring-[#464d79]"/>
                    <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Disclose salary</span>
                </label>
                <div x-show="disclosed" x-collapse class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-neutral-500 mb-1">Min Salary (INR)</label>
                        <input wire:model="salaryMin" type="number" min="0" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                    </div>
                    <div>
                        <label class="block text-xs text-neutral-500 mb-1">Max Salary (INR)</label>
                        <input wire:model="salaryMax" type="number" min="0" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                    </div>
                </div>
                <p x-show="!disclosed" class="text-sm text-neutral-500 italic">Displayed as: "As per industry norms"</p>
                @error('salaryMin') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @error('salaryMax') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Posting Duration</label>
                <select wire:model="postingDurationDays" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                    <option value="15">15 days</option>
                    <option value="30">30 days</option>
                    <option value="60">60 days</option>
                    <option value="90">90 days</option>
                </select>
            </div>
        </div>
    </div>

    {{-- SECTION: Thumbnail --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Job Thumbnail</h3>
        <div x-data="{ preview: null }" class="flex items-start gap-6">
            {{-- Preview --}}
            <div class="shrink-0">
                <div class="w-32 h-32 rounded-xl border-2 border-dashed border-neutral-300 dark:border-neutral-600 overflow-hidden bg-neutral-50 dark:bg-neutral-900 flex items-center justify-center">
                    <template x-if="preview">
                        <img :src="preview" class="w-full h-full object-cover"/>
                    </template>
                    <template x-if="!preview">
                        @if(isset($existingThumbnail) && $existingThumbnail)
                        <img src="{{ asset('storage/' . $existingThumbnail) }}" class="w-full h-full object-cover"/>
                        @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-neutral-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                        @endif
                    </template>
                </div>
            </div>
            {{-- Upload --}}
            <div class="flex-1">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Upload Image</label>
                <input wire:model="thumbnail" type="file" accept="image/*"
                    @change="const f = $event.target.files[0]; if(f) { const r = new FileReader(); r.onload = e => preview = e.target.result; r.readAsDataURL(f); }"
                    class="w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20"/>
                <p class="text-xs text-neutral-500 mt-2">Max 2MB. JPG, PNG, or WebP. Falls back to recruiter logo, then default image.</p>
                @error('thumbnail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <div wire:loading wire:target="thumbnail" class="text-xs text-[#464d79] mt-1">Uploading...</div>
            </div>
        </div>
    </div>

    {{-- SECTION 3: Description & Skills --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Description & Skills</h3>
        <div class="space-y-4">
            <div wire:ignore>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Job Description <span class="text-red-500">*</span></label>
                <div
                    x-data="{
                        editor: null,
                        init() {
                            ClassicEditor
                                .create(this.$refs.editor, {
                                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable', 'undo', 'redo'],
                                    placeholder: 'Write a detailed job description (minimum 100 characters)...'
                                })
                                .then(editor => {
                                    this.editor = editor;
                                    editor.setData(@js($jobDescription));
                                    editor.model.document.on('change:data', () => {
                                        @this.set('jobDescription', editor.getData());
                                    });
                                })
                                .catch(error => console.error(error));
                        }
                    }"
                >
                    <div x-ref="editor"></div>
                </div>
            </div>
            @error('jobDescription') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

            {{-- Key Skills --}}
            <div x-data="tagInput('keySkills', @js($keySkills), @js($this->standardKeySkills))">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Key Skills</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <template x-for="(tag, i) in tags" :key="i">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 rounded-lg text-xs font-medium">
                            <span x-text="tag"></span>
                            <button type="button" @click="remove(i)" class="hover:text-red-500">&times;</button>
                        </span>
                    </template>
                </div>
                <div class="relative">
                    <div class="flex gap-2">
                        <input x-model="input" @input="filter()" @keydown="onKeydown($event)" @blur="setTimeout(() => showSuggestions = false, 200)" type="text" placeholder="Type to search skills..." class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
                        <button type="button" @click="add()" class="h-9 px-3 bg-neutral-200 dark:bg-neutral-700 rounded-lg text-xs font-medium hover:bg-neutral-300 dark:hover:bg-neutral-600">Add</button>
                    </div>
                    <div x-show="showSuggestions" x-transition class="absolute z-20 left-0 right-12 mt-1 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="(s, i) in filteredSuggestions" :key="i">
                            <button type="button" @mousedown.prevent="add(s)" class="w-full text-left px-3 py-2 text-sm hover:bg-[#464d79]/10 transition-colors" :class="i === highlightIndex && 'bg-[#464d79]/10'" x-text="s"></button>
                        </template>
                    </div>
                </div>
                @error('keySkills') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Educational Requirements --}}
            <div x-data="tagInput('educationalRequirements', @js($educationalRequirements), @js($this->standardEducationalRequirements))">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Educational Requirements</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <template x-for="(tag, i) in tags" :key="i">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs font-medium">
                            <span x-text="tag"></span>
                            <button type="button" @click="remove(i)" class="hover:text-red-500">&times;</button>
                        </span>
                    </template>
                </div>
                <div class="relative">
                    <div class="flex gap-2">
                        <input x-model="input" @input="filter()" @keydown="onKeydown($event)" @blur="setTimeout(() => showSuggestions = false, 200)" type="text" placeholder="Type to search qualifications..." class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
                        <button type="button" @click="add()" class="h-9 px-3 bg-neutral-200 dark:bg-neutral-700 rounded-lg text-xs font-medium hover:bg-neutral-300 dark:hover:bg-neutral-600">Add</button>
                    </div>
                    <div x-show="showSuggestions" x-transition class="absolute z-20 left-0 right-12 mt-1 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="(s, i) in filteredSuggestions" :key="i">
                            <button type="button" @mousedown.prevent="add(s)" class="w-full text-left px-3 py-2 text-sm hover:bg-[#4ab098]/10 transition-colors" :class="i === highlightIndex && 'bg-[#4ab098]/10'" x-text="s"></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Medical Qualifications --}}
            <div x-data="tagInput('medicalQualifications', @js($medicalQualifications), @js($this->standardMedicalQualifications))">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Medical Qualifications</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <template x-for="(tag, i) in tags" :key="i">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs font-medium">
                            <span x-text="tag"></span>
                            <button type="button" @click="remove(i)" class="hover:text-red-500">&times;</button>
                        </span>
                    </template>
                </div>
                <div class="relative">
                    <div class="flex gap-2">
                        <input x-model="input" @input="filter()" @keydown="onKeydown($event)" @blur="setTimeout(() => showSuggestions = false, 200)" type="text" placeholder="Type to search qualifications..." class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
                        <button type="button" @click="add()" class="h-9 px-3 bg-neutral-200 dark:bg-neutral-700 rounded-lg text-xs font-medium hover:bg-neutral-300 dark:hover:bg-neutral-600">Add</button>
                    </div>
                    <div x-show="showSuggestions" x-transition class="absolute z-20 left-0 right-12 mt-1 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="(s, i) in filteredSuggestions" :key="i">
                            <button type="button" @mousedown.prevent="add(s)" class="w-full text-left px-3 py-2 text-sm hover:bg-[#4ab098]/10 transition-colors" :class="i === highlightIndex && 'bg-[#4ab098]/10'" x-text="s"></button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Certifications --}}
            <div x-data="tagInput('certificationsRequired', @js($certificationsRequired), @js($this->standardCertifications))">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Certifications Required</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    <template x-for="(tag, i) in tags" :key="i">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 rounded-lg text-xs font-medium">
                            <span x-text="tag"></span>
                            <button type="button" @click="remove(i)" class="hover:text-red-500">&times;</button>
                        </span>
                    </template>
                </div>
                <div class="relative">
                    <div class="flex gap-2">
                        <input x-model="input" @input="filter()" @keydown="onKeydown($event)" @blur="setTimeout(() => showSuggestions = false, 200)" type="text" placeholder="Type to search certifications..." class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
                        <button type="button" @click="add()" class="h-9 px-3 bg-neutral-200 dark:bg-neutral-700 rounded-lg text-xs font-medium hover:bg-neutral-300 dark:hover:bg-neutral-600">Add</button>
                    </div>
                    <div x-show="showSuggestions" x-transition class="absolute z-20 left-0 right-12 mt-1 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                        <template x-for="(s, i) in filteredSuggestions" :key="i">
                            <button type="button" @mousedown.prevent="add(s)" class="w-full text-left px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors" :class="i === highlightIndex && 'bg-neutral-100 dark:bg-neutral-700'" x-text="s"></button>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SECTION 4: Location --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Location</h3>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">State</label>
                <select wire:model.live="locationState" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                    <option value="">Select state...</option>
                    @foreach($this->statesList as $state)
                        <option value="{{ $state }}">{{ $state }}</option>
                    @endforeach
                </select>
                @error('locationState') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">City</label>
                <select wire:model="locationCity" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20" @if(!$locationState) disabled @endif>
                    <option value="">Select city...</option>
                    @foreach($this->citiesList as $city)
                        <option value="{{ $city }}">{{ $city }}</option>
                    @endforeach
                </select>
                @error('locationCity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Office Address</label>
                <input wire:model="locationOfficeAddress" type="text" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Pincode</label>
                <input wire:model="locationPincode" type="text" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            </div>
            <div class="flex items-end">
                <label class="flex items-center gap-2 cursor-pointer h-11">
                    <input wire:model="isRemote" type="checkbox" class="w-4 h-4 rounded border-neutral-300 text-[#464d79] focus:ring-[#464d79]"/>
                    <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Remote position</span>
                </label>
            </div>
        </div>
    </div>

    {{-- SECTION 5: Contact --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Contact Details</h3>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Contact Person</label>
                <input wire:model="contactName" type="text" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Email</label>
                <input wire:model="contactEmail" type="email" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                @error('contactEmail') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Phone</label>
                <input wire:model="contactPhone" type="tel" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            </div>
        </div>
    </div>

    {{-- SECTION 6: Perks --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-base font-semibold text-neutral-900 dark:text-white mb-4">Perks & Benefits</h3>
        <div x-data="tagInput('perksAndBenefits', @js($perksAndBenefits))">
            <div class="flex flex-wrap gap-2 mb-2">
                <template x-for="(tag, i) in tags.slice(0, 5)" :key="i">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs font-medium">
                        <span x-text="tag"></span>
                        <button type="button" @click="remove(i)" class="hover:text-red-500">&times;</button>
                    </span>
                </template>
                <span x-show="tags.length > 5" class="text-xs text-neutral-400 self-center" x-text="'+' + (tags.length - 5) + ' more'"></span>
            </div>
            <div class="flex gap-2">
                <input x-model="input" @keydown.enter.prevent="add()" type="text" placeholder="e.g. Health Insurance, PF, Flexible hours" class="flex-1 h-9 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
                <button type="button" @click="add()" class="h-9 px-3 bg-neutral-200 dark:bg-neutral-700 rounded-lg text-xs font-medium hover:bg-neutral-300 dark:hover:bg-neutral-600">Add</button>
            </div>
        </div>
    </div>

    {{-- Action bar --}}
    <div class="sticky bottom-0 bg-white/90 dark:bg-neutral-900/90 backdrop-blur border-t border-neutral-200 dark:border-neutral-700 -mx-4 lg:-mx-6 px-4 lg:px-6 py-4 flex items-center justify-end gap-3">
        <a href="{{ route('recruiter.jobs.index') }}" wire:navigate class="h-10 px-5 inline-flex items-center rounded-lg text-sm font-medium text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">Cancel</a>
        <button type="submit" class="h-10 px-6 bg-[#464d79] hover:bg-[#3a4169] text-white font-semibold rounded-lg text-sm transition-colors">
            {{ isset($wasApproved) && $wasApproved ? 'Update & Re-submit' : (isset($job) ? 'Update Job' : 'Post Job') }}
        </button>
    </div>
</form>
