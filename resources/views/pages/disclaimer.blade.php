@extends('layouts.public')
@section('title', 'Disclaimer')

@section('content')
@include('partials.page-hero', ['title' => 'Disclaimer', 'subtitle' => 'Important information about the use of our platform.'])

<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl border border-gray-200 p-8 prose prose-gray max-w-none">
            <p class="text-sm text-gray-500 mb-6">Last updated: {{ date('F d, Y') }}</p>

            <h2>1. General Information</h2>
            <p>The information provided on Vaidyog is for general informational purposes only. While we strive to keep the information up-to-date and accurate, we make no representations or warranties of any kind about the completeness, accuracy, reliability, or suitability of the information.</p>

            <h2>2. No Employment Guarantee</h2>
            <p>Vaidyog serves as a platform connecting job seekers and recruiters. We do not guarantee employment or hiring outcomes. The final hiring decisions rest solely with the recruiting institutions.</p>

            <h2>3. Third-Party Content</h2>
            <p>Job postings, company profiles, and other content are provided by third-party recruiters. Vaidyog does not endorse or verify the accuracy of such content. Users should exercise their own judgment when interacting with recruiters.</p>

            <h2>4. Professional Advice</h2>
            <p>Content on this platform does not constitute professional medical, legal, or career advice. Users should seek appropriate professional counsel for specific situations.</p>

            <h2>5. External Links</h2>
            <p>Our platform may contain links to external websites. We have no control over the content and nature of these sites and are not responsible for their content or privacy practices.</p>

            <h2>6. Limitation</h2>
            <p>In no event shall Vaidyog be liable for any loss or damage, including without limitation, indirect or consequential loss or damage arising from the use of this platform.</p>

            <h2>7. Changes</h2>
            <p>This disclaimer may be updated from time to time. Continued use of the Platform after changes constitutes acceptance of the revised disclaimer.</p>
        </div>
    </div>
</div>
@endsection
