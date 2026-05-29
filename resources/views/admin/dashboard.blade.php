<x-layouts.admin :pageTitle="$pageTitle">

    {{-- Greeting --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Welcome back, {{ auth('admin')->user()->name ?? 'Admin' }}</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Here's what's happening on Vaidyog today.</p>
    </div>

    {{-- Module Stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Job Seekers Card --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#4ab098]/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4ab098]" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                    </div>
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Job Seekers</h2>
                </div>
                <a href="{{ route('admin.job-seekers.index') }}" class="text-sm font-medium text-[#464d79] dark:text-[#4ab098] hover:underline">View all &rarr;</a>
            </div>
            <div class="grid grid-cols-4 divide-x divide-neutral-100 dark:divide-neutral-700">
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ number_format($jobSeekers['total']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Total</div>
                </div>
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-[#4ab098]">{{ number_format($jobSeekers['active']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Active</div>
                </div>
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-amber-500">{{ number_format($jobSeekers['pending']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Pending</div>
                </div>
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-red-500">{{ number_format($jobSeekers['blocked']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Blocked</div>
                </div>
            </div>
        </div>

        {{-- Recruiters Card --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 dark:border-neutral-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#464d79]/10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79] dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                    </div>
                    <h2 class="text-base font-semibold text-neutral-900 dark:text-white">Recruiters</h2>
                </div>
                <a href="{{ route('admin.recruiters.index') }}" class="text-sm font-medium text-[#464d79] dark:text-[#4ab098] hover:underline">View all &rarr;</a>
            </div>
            <div class="grid grid-cols-4 divide-x divide-neutral-100 dark:divide-neutral-700">
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ number_format($recruiters['total']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Total</div>
                </div>
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-[#4ab098]">{{ number_format($recruiters['active']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Active</div>
                </div>
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-amber-500">{{ number_format($recruiters['pending']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Pending</div>
                </div>
                <div class="px-4 py-5 text-center">
                    <div class="text-2xl font-bold text-[#464d79] dark:text-indigo-400">{{ number_format($recruiters['featured']) }}</div>
                    <div class="text-xs text-neutral-500 mt-1">Featured</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mb-8">
        <h2 class="text-sm font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider mb-3">Quick Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
            <a href="{{ route('admin.job-seekers.create') }}" class="group flex flex-col items-center gap-2 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 hover:border-[#4ab098]/40 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-[#4ab098]/10 group-hover:bg-[#4ab098]/20 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4ab098]" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/></svg>
                </div>
                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 text-center">Add Job Seeker</span>
            </a>
            <a href="{{ route('admin.job-seekers.bulk-import') }}" class="group flex flex-col items-center gap-2 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 hover:border-[#4ab098]/40 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-[#4ab098]/10 group-hover:bg-[#4ab098]/20 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#4ab098]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 text-center">Bulk Import</span>
            </a>
            <a href="{{ route('admin.recruiters.create') }}" class="group flex flex-col items-center gap-2 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 hover:border-[#464d79]/40 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-[#464d79]/10 group-hover:bg-[#464d79]/20 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79] dark:text-indigo-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 text-center">Add Recruiter</span>
            </a>
            <a href="{{ route('admin.job-seekers.index') }}" class="group flex flex-col items-center gap-2 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 hover:border-neutral-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-neutral-100 dark:bg-neutral-700 group-hover:bg-neutral-200 dark:group-hover:bg-neutral-600 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-600 dark:text-neutral-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                </div>
                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 text-center">All Job Seekers</span>
            </a>
            <a href="{{ route('admin.recruiters.index') }}" class="group flex flex-col items-center gap-2 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 hover:border-neutral-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-neutral-100 dark:bg-neutral-700 group-hover:bg-neutral-200 dark:group-hover:bg-neutral-600 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-600 dark:text-neutral-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 text-center">All Recruiters</span>
            </a>
            <a href="{{ route('recruiter.register') }}" target="_blank" class="group flex flex-col items-center gap-2 p-4 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 hover:border-neutral-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-neutral-100 dark:bg-neutral-700 group-hover:bg-neutral-200 dark:group-hover:bg-neutral-600 flex items-center justify-center transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-neutral-600 dark:text-neutral-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.706 2.142-.766 3.556h3.936c-.06-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.06 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.706-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"/></svg>
                </div>
                <span class="text-xs font-medium text-neutral-700 dark:text-neutral-300 text-center">Recruiter Signup</span>
            </a>
        </div>
    </div>

    {{-- Placeholder modules --}}
    <div>
        <h2 class="text-sm font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider mb-3">Upcoming Modules</h2>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 opacity-50">
                <div class="w-8 h-8 rounded-lg bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Job Postings</h3>
                <p class="text-xs text-neutral-400 mt-1">Coming soon</p>
            </div>
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 opacity-50">
                <div class="w-8 h-8 rounded-lg bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Applications</h3>
                <p class="text-xs text-neutral-400 mt-1">Coming soon</p>
            </div>
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 opacity-50">
                <div class="w-8 h-8 rounded-lg bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Subscriptions</h3>
                <p class="text-xs text-neutral-400 mt-1">Coming soon</p>
            </div>
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 opacity-50">
                <div class="w-8 h-8 rounded-lg bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Reports</h3>
                <p class="text-xs text-neutral-400 mt-1">Coming soon</p>
            </div>
        </div>
    </div>

</x-layouts.admin>
