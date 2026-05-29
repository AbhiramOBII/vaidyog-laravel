@extends('layouts.public')
@section('title', 'Terms of Use')

@section('content')
@include('partials.page-hero', ['title' => 'Terms of Use', 'subtitle' => 'Please read these terms carefully before using our platform.'])

<div class="py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl border border-gray-200 p-8 prose prose-gray max-w-none">
            <p class="text-sm text-gray-500 mb-6">Last updated: {{ date('F d, Y') }}</p>

            <h2>1. Acceptance of Terms</h2>
            <p>By accessing and using Vaidyog ("the Platform"), you agree to be bound by these Terms of Use. If you do not agree to these terms, please do not use the Platform.</p>

            <h2>2. Description of Service</h2>
            <p>Vaidyog is a healthcare job portal that connects healthcare professionals (Job Seekers) with healthcare institutions (Recruiters) across India. The Platform provides job listing, application management, profile building, and subscription services.</p>

            <h2>3. User Accounts</h2>
            <p>You are responsible for maintaining the confidentiality of your account credentials. You agree to provide accurate, current, and complete information during registration and to update such information to keep it accurate.</p>

            <h2>4. User Conduct</h2>
            <p>Users agree not to:</p>
            <ul>
                <li>Post false, misleading, or fraudulent content</li>
                <li>Impersonate any person or entity</li>
                <li>Upload harmful or malicious content</li>
                <li>Violate any applicable local, state, or national law</li>
                <li>Harvest or collect user data without consent</li>
            </ul>

            <h2>5. Job Postings</h2>
            <p>Recruiters are solely responsible for the accuracy of their job postings. Vaidyog reserves the right to remove any job posting that violates these terms or is deemed inappropriate.</p>

            <h2>6. Intellectual Property</h2>
            <p>All content on the Platform, including logos, text, graphics, and software, is the property of Vaidyog or its licensors and is protected by intellectual property laws.</p>

            <h2>7. Limitation of Liability</h2>
            <p>Vaidyog shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the Platform.</p>

            <h2>8. Modifications</h2>
            <p>Vaidyog reserves the right to modify these Terms of Use at any time. Continued use of the Platform constitutes acceptance of any modifications.</p>

            <h2>9. Contact</h2>
            <p>For questions regarding these terms, please contact us at <a href="mailto:support@vaidyog.com">support@vaidyog.com</a>.</p>
        </div>
    </div>
</div>
@endsection
