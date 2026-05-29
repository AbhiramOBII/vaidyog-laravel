@section('title', $blog->meta_title ?? $blog->title)
@section('description', $blog->meta_description ?? Str::limit(strip_tags($blog->full_description ?? ''), 155))

@push('schema')
    @include('partials.schema.blog-article', ['blog' => $blog])
    @include('partials.schema.breadcrumb', ['breadcrumbs' => [
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Blog', 'url' => route('blogs.index')],
        ['name' => $blog->title, 'url' => route('blogs.show', $blog->slug)],
    ]])
@endpush

<div>
    {{-- Breadcrumb --}}
    <section class="bg-gray-50 border-b border-gray-100">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex items-center gap-2 text-sm text-gray-500">
                <a href="/" class="hover:text-gray-700">Home</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <a href="{{ route('blogs.index') }}" wire:navigate class="hover:text-gray-700">Blog</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-gray-700 truncate max-w-[200px]">{{ $blog->title }}</span>
            </nav>
        </div>
    </section>

    {{-- Article --}}
    <article class="py-10 md:py-14">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <header class="mb-8">
                @if($blog->category)
                <span class="inline-block text-xs font-semibold text-teal-600 bg-teal-50 px-3 py-1 rounded-full mb-4">{{ $blog->category->title }}</span>
                @endif
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-4">{{ $blog->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                    <span>{{ $blog->published_at?->format('F d, Y') }}</span>
                    @if($blog->full_description)
                    <span>{{ ceil(str_word_count(strip_tags($blog->full_description)) / 200) }} min read</span>
                    @endif
                </div>
            </header>

            {{-- Featured Image --}}
            @if($blog->thumbnail_image)
            <div class="rounded-2xl overflow-hidden mb-8 border border-gray-100">
                <img src="{{ asset('storage/' . $blog->thumbnail_image) }}" alt="{{ $blog->title }}" class="w-full h-auto object-cover max-h-[400px]"/>
            </div>
            @endif

            {{-- Body --}}
            <div class="prose prose-gray max-w-none prose-headings:text-gray-900 prose-a:text-teal-600 prose-img:rounded-xl">
                {!! $blog->full_description !!}
            </div>

            {{-- Share --}}
            <div class="mt-10 pt-6 border-t border-gray-100 flex items-center gap-4">
                <span class="text-sm font-medium text-gray-600">Share:</span>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
            </div>
        </div>
    </article>

    {{-- Related Articles --}}
    @if($relatedBlogs->isNotEmpty())
    <section class="py-12 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $related)
                <a href="{{ route('blogs.show', $related->slug) }}" wire:navigate class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow group">
                    <div class="aspect-[16/10] bg-gradient-to-br from-[#464d79]/10 to-[#4ab098]/10 overflow-hidden">
                        @if($related->thumbnail_image)
                        <img src="{{ asset('storage/' . $related->thumbnail_image) }}" alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-[#464d79] transition-colors">{{ $related->title }}</h3>
                        <span class="text-xs text-gray-400 mt-2 block">{{ $related->published_at?->format('M d, Y') }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
