<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Vaidyog',
    'url' => url('/'),
    'logo' => asset('images/Vaidyog-Logo.webp'),
    'description' => "India's leading healthcare job portal connecting doctors, nurses, and allied health professionals with top hospitals and clinics.",
    'foundingDate' => '2024',
    'founder' => [
        '@type' => 'Person',
        'name' => 'Dr. Chethan Raju',
        'url' => 'https://www.linkedin.com/in/dr-chethan-raju-951a42168',
    ],
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'contactType' => 'customer support',
        'url' => url('/'),
    ],
    'sameAs' => [
        'https://www.linkedin.com/company/vaidyog',
    ],
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
