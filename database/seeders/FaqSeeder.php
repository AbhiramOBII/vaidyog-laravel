<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::updateOrCreate(['id' => 1], [
            'items' => [
                [
                    'question' => 'What is Vaidyog?',
                    'answer' => 'Vaidyog is India\'s dedicated healthcare job portal connecting doctors, nurses, and allied health professionals with hospitals, clinics, and healthcare organisations across the country.',
                ],
                [
                    'question' => 'Is it free to register as a job seeker?',
                    'answer' => 'Yes! Registration is completely free for all healthcare professionals. You can create your profile, upload your resume, and start applying to jobs immediately.',
                ],
                [
                    'question' => 'What types of healthcare jobs are available?',
                    'answer' => 'We list opportunities across 30+ specialties including Doctors & Physicians, Nursing, Pharmacy, Physiotherapy, Radiology, Dentistry, Ayurveda, Hospital Administration, and many more.',
                ],
                [
                    'question' => 'How do I apply for a job?',
                    'answer' => 'Simply create an account, complete your profile, and click "Apply Now" on any job listing. You can track all your applications from your dashboard.',
                ],
                [
                    'question' => 'How can hospitals post jobs on Vaidyog?',
                    'answer' => 'Hospitals and clinics can register as recruiters, choose a subscription plan, and start posting jobs. We offer plans for clinics, multi-specialty hospitals, and enterprise healthcare groups.',
                ],
                [
                    'question' => 'Are the job listings verified?',
                    'answer' => 'Yes. Every job posted on Vaidyog goes through an admin approval process to ensure legitimacy and quality before it becomes visible to job seekers.',
                ],
                [
                    'question' => 'Can I receive job alerts?',
                    'answer' => 'Yes, once you set your preferred specialty, location, and job type in your profile, you\'ll receive relevant job notifications via email.',
                ],
                [
                    'question' => 'Is my personal data secure?',
                    'answer' => 'Absolutely. We follow industry-standard security practices to protect your data. Your resume is only shared with recruiters when you explicitly apply to their job.',
                ],
            ],
        ]);

        $this->command->info('FAQ seeded successfully.');
    }
}
