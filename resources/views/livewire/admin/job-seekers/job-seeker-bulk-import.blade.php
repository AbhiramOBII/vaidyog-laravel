<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Bulk Import Job Seekers</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Upload a CSV file to import multiple job seekers at once.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.job-seekers.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-neutral-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700 transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main upload area --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Upload card --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-base font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    Upload CSV File
                </h2>

                @if(!$processed)
                    <div class="border-2 border-dashed border-neutral-200 dark:border-neutral-700 rounded-lg p-8 text-center hover:border-[#464d79]/40 transition-colors">
                        <input wire:model="csvFile" type="file" accept=".csv,.txt" id="csvUpload" class="hidden"/>
                        <label for="csvUpload" class="cursor-pointer">
                            <div class="w-14 h-14 rounded-full bg-[#464d79]/10 dark:bg-[#464d79]/20 flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Click to upload CSV file</p>
                            <p class="text-xs text-neutral-500">Max 5MB • .csv format only</p>
                        </label>
                    </div>

                    @error('csvFile') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror

                    @if($csvFile)
                        <div class="mt-4 flex items-center justify-between p-3 rounded-lg bg-[#4ab098]/5 border border-[#4ab098]/20">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-[#4ab098]/10 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4ab098]" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ $csvFile->getClientOriginalName() }}</p>
                                    <p class="text-xs text-neutral-500">{{ number_format($csvFile->getSize() / 1024, 1) }} KB</p>
                                </div>
                            </div>
                            <button wire:click="import" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors disabled:opacity-70">
                                <span wire:loading wire:target="import" class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                                <span wire:loading.remove wire:target="import">Start Import</span>
                                <span wire:loading wire:target="import">Processing...</span>
                            </button>
                        </div>
                    @endif
                @else
                    {{-- Results --}}
                    <div class="space-y-4">
                        {{-- Summary --}}
                        <div class="grid grid-cols-3 gap-3">
                            <div class="text-center p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700">
                                <div class="text-lg font-bold text-neutral-800 dark:text-white">{{ $totalRows }}</div>
                                <div class="text-xs text-neutral-500">Total Rows</div>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800/30">
                                <div class="text-lg font-bold text-green-600">{{ $successCount }}</div>
                                <div class="text-xs text-green-600">Imported</div>
                            </div>
                            <div class="text-center p-3 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30">
                                <div class="text-lg font-bold text-red-600">{{ $failCount }}</div>
                                <div class="text-xs text-red-600">Failed</div>
                            </div>
                        </div>

                        {{-- Errors --}}
                        @if(count($importErrors) > 0)
                            <div class="rounded-lg border border-red-200 dark:border-red-800/50 overflow-hidden">
                                <div class="px-4 py-2.5 bg-red-50 dark:bg-red-900/20 border-b border-red-200 dark:border-red-800/50">
                                    <h4 class="text-sm font-semibold text-red-700 dark:text-red-400">Errors ({{ count($importErrors) }})</h4>
                                </div>
                                <div class="max-h-60 overflow-y-auto divide-y divide-red-100 dark:divide-red-900/30">
                                    @foreach($importErrors as $error)
                                        <div class="px-4 py-2 text-xs">
                                            <span class="font-medium text-red-600">Row {{ $error['row'] }}:</span>
                                            <span class="text-red-500 dark:text-red-400">{{ $error['message'] }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex items-center gap-3 pt-2">
                            <button wire:click="resetImport" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-neutral-600 dark:text-neutral-400 bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 transition-colors">
                                Import Another File
                            </button>
                            <a href="{{ route('admin.job-seekers.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors">
                                View Job Seekers
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-6">
            {{-- Template download --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">CSV Template</h3>
                <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-4 leading-relaxed">Download the template with the correct column headers and a sample row.</p>
                <button wire:click="downloadTemplate" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-[#464d79] dark:text-[#4ab098] bg-[#464d79]/10 dark:bg-[#4ab098]/10 border border-[#464d79]/20 dark:border-[#4ab098]/20 rounded-lg hover:bg-[#464d79]/20 dark:hover:bg-[#4ab098]/20 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Download Template
                </button>
            </div>

            {{-- Column guide --}}
            <div class="bg-[#464d79]/5 dark:bg-[#464d79]/10 rounded-xl border border-[#464d79]/10 dark:border-[#464d79]/20 p-5">
                <h3 class="text-sm font-semibold text-[#464d79] dark:text-[#4ab098] mb-3">Required Columns</h3>
                <ul class="text-xs text-neutral-600 dark:text-neutral-400 space-y-1.5">
                    <li><span class="font-mono font-medium text-neutral-800 dark:text-neutral-200">name</span> — Full name *</li>
                    <li><span class="font-mono font-medium text-neutral-800 dark:text-neutral-200">phone</span> — 10-digit mobile *</li>
                    <li><span class="font-mono font-medium text-neutral-800 dark:text-neutral-200">category_slug</span> — e.g. doctors *</li>
                    <li><span class="font-mono font-medium text-neutral-800 dark:text-neutral-200">subcategory_name</span> — Exact match *</li>
                </ul>
                <h3 class="text-sm font-semibold text-[#464d79] dark:text-[#4ab098] mt-4 mb-3">Optional Columns</h3>
                <ul class="text-xs text-neutral-600 dark:text-neutral-400 space-y-1.5">
                    <li><span class="font-mono">email</span></li>
                    <li><span class="font-mono">auth_provider</span> — phone/google/email</li>
                    <li><span class="font-mono">gender</span> — male/female/other</li>
                    <li><span class="font-mono">city</span>, <span class="font-mono">state</span>, <span class="font-mono">pincode</span></li>
                    <li><span class="font-mono">experience_years</span></li>
                    <li><span class="font-mono">highest_qualification</span></li>
                    <li><span class="font-mono">current_employer</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
