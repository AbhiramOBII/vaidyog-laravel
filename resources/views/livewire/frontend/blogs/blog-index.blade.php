<div>
    {{-- Hero --}}
    @include('partials.page-hero', ['title' => 'Healthcare Insights & Resources', 'subtitle' => 'Stay updated with the latest trends, career advice, and industry news in healthcare.'])

    {{-- Filters --}}
    <section class="bg-white border-b border-gray-100 sticky top-20 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                {{-- Search --}}
                <div class="relative w-full sm:max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search articles..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500"/>
                </div>

                {{-- Category pills --}}
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('category', '')" class="text-xs px-3 py-1.5 rounded-full border transition-colors {{ $category === '' ? 'bg-[#464d79] text-white border-[#464d79]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300' }}">All</button>
                    @foreach($categories as $cat)
                    <button wire:click="$set('category', '{{ $cat->slug }}')" class="text-xs px-3 py-1.5 rounded-full border transition-colors {{ $category === $cat->slug ? 'bg-[#464d79] text-white border-[#464d79]' : 'bg-white text-gray-600 border-gray-200 hover:border-gray-300' }}">{{ $cat->title }}</button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- Blog Grid --}}
    <section class="py-12 md:py-16" style="background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 24px 24px;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($blogs->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <p class="text-gray-500">No articles found.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($blogs as $blog)
                    <a href="{{ route('blogs.show', $blog->slug) }}" wire:navigate class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all group">
                        {{-- Thumbnail --}}
                        <div class="aspect-[16/10] bg-gradient-to-br from-[#464d79]/20 to-[#4ab098]/20 relative overflow-hidden">
                            @if($blog->thumbnail_image)
                                <img src="{{ asset('storage/' . $blog->thumbnail_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-[#464d79]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            @endif
                            @if($blog->category)
                            <span class="absolute top-3 left-3 text-[10px] px-2.5 py-1 rounded-full bg-white/90 text-[#464d79] font-semibold shadow-sm">{{ $blog->category->title }}</span>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="p-5">
                            <h2 class="text-base font-bold text-gray-900 line-clamp-2 group-hover:text-[#464d79] transition-colors mb-2">{{ $blog->title }}</h2>
                            @if($blog->short_description)
                            <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $blog->short_description }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-400">{{ $blog->published_at?->format('M d, Y') }}</span>
                                <span class="text-xs font-semibold text-teal-500 group-hover:text-teal-600 transition-colors">Read More →</span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $blogs->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
