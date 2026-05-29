@extends('layouts.public')
@section('title', 'About Vaidyog')

@section('content')
{{-- Hero --}}
@include('partials.page-hero', ['title' => 'About Vaidyog', 'subtitle' => 'Bridging the gap between talented healthcare professionals and leading healthcare institutions.'])

{{-- Why Vaidyog --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Why Vaidyog</h2>
            <div class="w-16 h-1 mx-auto mt-4 rounded-full" style="background: linear-gradient(90deg, #464d79, #4ab098);"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Card 1 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#464d79]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/helathcare.webp') }}" alt="Healthcare" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Healthcare-Focused Expertise</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Our platform is designed exclusively for the healthcare industry, providing targeted solutions for your hiring needs with the most verified profiles.</p>
            </div>

            {{-- Card 2 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#4ab098]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/ai-1.webp') }}" alt="AI Matching" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">AI-Powered Matching Technology</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Our intelligent algorithm precisely matches you with top-tier candidates based on education, experience, speciality, and qualifications.</p>
            </div>

            {{-- Card 3 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#464d79]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/talent-search.webp') }}" alt="Talent Pool" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Vast Medical Talent Pool</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Access to a large database of doctors, nurses, technicians, and allied health professionals, all vetted for quality and expertise.</p>
            </div>

            {{-- Card 4 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#4ab098]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/messages.webp') }}" alt="News & Events" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Stay Updated with Healthcare News & Events</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Get the latest news, medical industry updates, and events to stay at the forefront of the healthcare field.</p>
            </div>

            {{-- Card 5 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#464d79]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/social-media-management.webp') }}" alt="Community" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Community Engagement</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Join discussions and get answers to your queries with our Q&A community for healthcare professionals.</p>
            </div>

            {{-- Card 6 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#4ab098]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/fast-response.webp') }}" alt="Fast Recruitment" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Faster Recruitment Cycles</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Streamline your hiring process and reduce recruitment time by up to 50%.</p>
            </div>

            {{-- Card 7 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#464d79]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/equalizer-1.webp') }}" alt="Filters" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Customizable Filters</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Fine-tune your candidate search based on certifications, specialties, salary range, and experience, ensuring you engage with the most relevant professionals.</p>
            </div>

            {{-- Card 8 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#4ab098]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/dashboard-3.webp') }}" alt="Analytics" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Real-Time Analytics</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Our platform offers insights into your recruitment performance, helping you make data-driven decisions based on individual reports and statistics.</p>
            </div>

            {{-- Card 9 --}}
            <div class="group bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-xl hover:border-transparent transition-all duration-300">
                <div class="w-14 h-14 rounded-xl bg-[#464d79]/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <img src="{{ asset('images/online-chat-1.webp') }}" alt="Support" class="w-8 h-8 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Dedicated Support</h3>
                <p class="text-sm text-gray-500 leading-relaxed">We provide personalised assistance at every step, ensuring a smooth recruitment experience for your team with a dedicated account manager.</p>
            </div>
        </div>
    </div>
</section>

{{-- Our Values --}}
<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Our Values</h2>
            <div class="w-16 h-1 mx-auto mt-4 rounded-full" style="background: linear-gradient(90deg, #464d79, #4ab098);"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center">
                    <img src="{{ asset('images/integrity.webp') }}" alt="Integrity" class="w-10 h-10 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Integrity</h3>
                <p class="text-sm text-gray-500 leading-relaxed">We conduct ourselves with the highest standards of integrity, honesty, and transparency in all our interactions.</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center">
                    <img src="{{ asset('images/empowerment.webp') }}" alt="Empowerment" class="w-10 h-10 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Empowerment</h3>
                <p class="text-sm text-gray-500 leading-relaxed">We empower healthcare professionals to take control of their careers and pursue their passions with confidence and purpose.</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center">
                    <img src="{{ asset('images/idea.webp') }}" alt="Innovation" class="w-10 h-10 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Innovation</h3>
                <p class="text-sm text-gray-500 leading-relaxed">We embrace innovation and new technologies to continuously improve and enhance the user experience.</p>
            </div>

            <div class="text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-white shadow-sm border border-gray-100 flex items-center justify-center">
                    <img src="{{ asset('images/team.webp') }}" alt="Inclusivity" class="w-10 h-10 object-contain"/>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-2">Inclusivity</h3>
                <p class="text-sm text-gray-500 leading-relaxed">We celebrate diversity and inclusivity, recognizing the unique strengths and perspectives that each individual brings to the table.</p>
            </div>
        </div>
    </div>
</section>

{{-- Our Mission --}}
<section class="py-16 md:py-24 bg-white relative overflow-hidden">
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-[#4ab098]/5 rounded-full"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-[#464d79]/5 rounded-full"></div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Our Mission</h2>
        <div class="w-16 h-1 mx-auto mb-8 rounded-full" style="background: linear-gradient(90deg, #464d79, #4ab098);"></div>
        <p class="text-lg md:text-xl text-gray-600 leading-relaxed mb-4">At Vaidyog, our mission is simple yet profound: to bridge the gap between talented healthcare professionals and leading healthcare institutions, creating mutually beneficial partnerships that drive innovation and excellence in patient care.</p>
        <p class="text-lg md:text-xl text-gray-600 leading-relaxed">We believe that by connecting the right people with the right opportunities, we can positively impact the future of healthcare worldwide.</p>
    </div>
</section>

{{-- About Dr Chethan Raju --}}
<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">About the Founder</h2>
            <div class="w-16 h-1 mx-auto mt-4 rounded-full" style="background: linear-gradient(90deg, #464d79, #4ab098);"></div>
        </div>

        <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
            <div class="shrink-0">
                <div class="relative">
                    <div class="w-64 h-64 md:w-72 md:h-72 rounded-2xl overflow-hidden border-4 border-white shadow-xl">
                        <img src="{{ asset('images/dr-chethan-raju.jpg') }}" alt="Dr Chethan Raju" class="w-full h-full object-cover"/>
                    </div>
                    <div class="absolute -bottom-3 -right-3 px-4 py-2 rounded-xl text-white text-xs font-bold shadow-lg" style="background: linear-gradient(146deg, #464d79, #4ab098);">Founder & CEO</div>
                </div>
            </div>

            <div class="flex-1">
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-1">Dr Chethan Raju</h3>
                <p class="text-sm font-medium text-[#4ab098] mb-3">BDS | MBA in Hospital Administration | PG Diploma in Health & Hospital Management</p>
                <a href="https://www.linkedin.com/in/dr-chethan-raju-951a42168" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-medium text-[#0077B5] hover:underline mb-5">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    Connect on LinkedIn
                </a>

                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>Dr Chethan Raju is a healthcare professional, entrepreneur, and hospital management consultant with a strong focus on improving healthcare delivery, compliance, and workforce access in India. With a background in dentistry, hospital administration, and healthcare management, he brings together clinical understanding and operational expertise to support doctors, clinics, hospitals, and healthcare organisations.</p>
                    <p>He is the Founder of Vaidyog, a healthcare-focused job portal created to bridge the gap between healthcare institutions and qualified medical professionals. He also serves as the Managing Director at MEDASUS Healthcare Pvt. Ltd. and is associated with healthcare infrastructure, clinic setup, hospital operations, and NABH-related consulting.</p>
                    <p>Dr. Chethan holds qualifications including BDS, MBA in Hospital Administration, and PG Diploma in Health & Hospital Management. He has also been listed as a dentist and dental surgeon with experience in dental care and healthcare administration.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Awards --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Awards & Recognition</h2>
            <div class="w-16 h-1 mx-auto mt-4 rounded-full" style="background: linear-gradient(90deg, #464d79, #4ab098);"></div>
        </div>

        <div class="bg-gradient-to-br from-[#464d79]/5 to-[#4ab098]/5 rounded-3xl border border-gray-100 overflow-hidden">
            <div class="flex flex-col md:flex-row items-center gap-8 p-8 md:p-12">
                <div class="shrink-0">
                    <div class="w-64 md:w-80 rounded-2xl overflow-hidden shadow-lg border border-white">
                        <img src="{{ asset('images/ahpi-awards.jpg') }}" alt="AHPI Award" class="w-full h-auto object-cover"/>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold text-amber-700 bg-amber-100 mb-4">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        Award Winner
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">AHPI Award for Excellence in Digital Health</h3>
                    <p class="text-gray-600 leading-relaxed">Received an Honorary Award for Excellence in the Digital Category for Vaidyog at AHPI Global Conclave 2026.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="relative overflow-hidden py-16 md:py-20" style="background: linear-gradient(146deg, rgba(70,77,121,1) 26%, rgba(74,176,152,1) 100%);">
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">Ready to Transform Healthcare Hiring?</h2>
        <p class="text-white/80 mb-8 max-w-lg mx-auto">Join thousands of healthcare professionals and institutions who trust Vaidyog.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('jobs.index') }}" class="px-8 py-3 bg-white text-[#464d79] text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors">Browse Jobs</a>
            <a href="{{ route('recruiter.login') }}" class="px-8 py-3 bg-white/10 text-white border border-white/20 text-sm font-bold rounded-xl hover:bg-white/20 transition-colors">For Recruiters</a>
        </div>
    </div>
</section>
@endsection
