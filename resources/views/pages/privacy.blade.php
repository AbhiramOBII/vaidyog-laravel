@extends('layouts.public')
@section('title', 'Privacy Policy')

@section('content')
@include('partials.page-hero', ['title' => 'Privacy Policy', 'subtitle' => 'How we collect, use, and protect your personal information.'])

<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl border border-gray-200 p-8 prose prose-gray max-w-none">
            <p class="text-sm text-gray-500 mb-6">Last updated: {{ date('F d, Y') }}</p>

            <h2>1. Information We Collect</h2>
            <p>We collect information you provide directly to us, including:</p>
            <ul>
                <li>Personal details (name, email, phone number, date of birth)</li>
                <li>Professional information (qualifications, work experience, skills)</li>
                <li>Profile photos and resumes</li>
                <li>Payment information (processed securely via Razorpay)</li>
                <li>Usage data and device information</li>
            </ul>

            <h2>2. How We Use Your Information</h2>
            <p>We use the collected information to:</p>
            <ul>
                <li>Provide and maintain our services</li>
                <li>Match job seekers with relevant job opportunities</li>
                <li>Process transactions and send related information</li>
                <li>Send notifications about job matches, application updates</li>
                <li>Improve and personalize your experience</li>
            </ul>

            <h2>3. Information Sharing</h2>
            <p>We may share your information with:</p>
            <ul>
                <li>Recruiters (when you apply to jobs or enable "Open to Work")</li>
                <li>Service providers who assist in our operations</li>
                <li>Legal authorities when required by law</li>
            </ul>
            <p>We do not sell your personal information to third parties.</p>

            <h2>4. Data Security</h2>
            <p>We implement appropriate technical and organizational measures to protect your personal data. However, no method of electronic transmission is 100% secure.</p>

            <h2>5. Data Retention</h2>
            <p>We retain your information as long as your account is active or as needed to provide services. You may request deletion of your account and data at any time.</p>

            <h2>6. Your Rights</h2>
            <p>You have the right to:</p>
            <ul>
                <li>Access and update your personal information</li>
                <li>Delete your account and associated data</li>
                <li>Opt out of marketing communications</li>
                <li>Withdraw consent for data processing</li>
            </ul>

            <h2>7. Cookies</h2>
            <p>We use cookies and similar technologies to enhance your experience, analyze usage, and provide personalized content.</p>

            <h2>8. Contact Us</h2>
            <p>For privacy-related inquiries, contact us at <a href="mailto:privacy@vaidyog.com">privacy@vaidyog.com</a>.</p>
        </div>
    </div>
</div>
@endsection
