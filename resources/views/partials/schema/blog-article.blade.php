{{-- Schema.org Article structured data --}}
@php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $blog->title,
        'description' => $blog->short_description ?? Str::limit(strip_tags($blog->full_description ?? ''), 160),
        'datePublished' => ($blog->published_at ?? $blog->created_at)->toW3cString(),
        'dateModified' => $blog->updated_at->toW3cString(),
        'author' => ['@type' => 'Organization', 'name' => 'Vaidyog'],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'Vaidyog',
            'logo' => ['@type' => 'ImageObject', 'url' => asset('images/Vaidyog-Logo.webp')],
        ],
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
    ];
    if ($blog->thumbnail_image) {
        $schema['image'] = asset('storage/' . $blog->thumbnail_image);
    }
@endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
