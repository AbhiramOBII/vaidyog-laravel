{{-- Standard page hero with brand gradient, decorative circles --}}
<section class="py-12 md:py-16 text-white relative overflow-hidden" style="background: linear-gradient(146deg, rgba(70,77,121,1) 26%, rgba(74,176,152,1) 100%);">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold mb-3">{{ $title }}</h1>
        @if(isset($subtitle))
        <p class="text-white/80 max-w-xl mx-auto">{{ $subtitle }}</p>
        @endif
    </div>
</section>
