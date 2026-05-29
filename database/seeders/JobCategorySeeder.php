<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Models\JobSubcategory;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'slug' => 'doctors',
                'name' => 'Doctors',
                'sort_order' => 1,
                'subcategories' => [
                    'General Physician',
                    'Cardiologist',
                    'Dermatologist',
                    'Orthopedic Surgeon',
                    'Pediatrician',
                    'Gynecologist',
                    'Neurologist',
                    'Oncologist',
                    'Psychiatrist',
                    'Radiologist',
                    'Anesthesiologist',
                    'ENT Specialist',
                    'Ophthalmologist',
                    'Urologist',
                    'Pulmonologist',
                    'Gastroenterologist',
                    'Nephrologist',
                    'Endocrinologist',
                    'Surgeon (General)',
                    'Dentist',
                ],
            ],
            [
                'slug' => 'nursing',
                'name' => 'Nursing',
                'sort_order' => 2,
                'subcategories' => [
                    'Staff Nurse',
                    'Head Nurse',
                    'ICU Nurse',
                    'Operation Theatre Nurse',
                    'Nursing Superintendent',
                    'Home Care Nurse',
                    'Neonatal Nurse',
                    'Psychiatric Nurse',
                    'Community Health Nurse',
                    'Dialysis Nurse',
                ],
            ],
            [
                'slug' => 'allied-health',
                'name' => 'Allied Health',
                'sort_order' => 3,
                'subcategories' => [
                    'Physiotherapist',
                    'Occupational Therapist',
                    'Speech Therapist',
                    'Dietitian / Nutritionist',
                    'Optometrist',
                    'Audiologist',
                    'Podiatrist',
                    'Clinical Psychologist',
                    'Respiratory Therapist',
                    'Prosthetist & Orthotist',
                ],
            ],
            [
                'slug' => 'lab-diagnostics',
                'name' => 'Lab & Diagnostics',
                'sort_order' => 4,
                'subcategories' => [
                    'Lab Technician',
                    'Pathologist',
                    'Microbiologist',
                    'Radiology Technician',
                    'MRI Technician',
                    'CT Scan Technician',
                    'Phlebotomist',
                    'Histopathologist',
                    'Biochemist',
                    'Cytotechnologist',
                ],
            ],
            [
                'slug' => 'pharmacy',
                'name' => 'Pharmacy',
                'sort_order' => 5,
                'subcategories' => [
                    'Pharmacist',
                    'Clinical Pharmacist',
                    'Hospital Pharmacist',
                    'Retail Pharmacist',
                    'Pharmacy Manager',
                    'Drug Inspector',
                    'Pharmaceutical Sales',
                    'Quality Assurance',
                ],
            ],
            [
                'slug' => 'hospital-admin',
                'name' => 'Hospital Administration',
                'sort_order' => 6,
                'subcategories' => [
                    'Hospital Administrator',
                    'Medical Records Officer',
                    'Billing Executive',
                    'Front Office Executive',
                    'Insurance Coordinator',
                    'Quality Manager',
                    'Operations Manager',
                    'Patient Relations Executive',
                    'Medical Superintendent',
                    'HR Manager (Healthcare)',
                ],
            ],
            [
                'slug' => 'public-health',
                'name' => 'Public Health',
                'sort_order' => 7,
                'subcategories' => [
                    'Epidemiologist',
                    'Public Health Officer',
                    'Health Educator',
                    'Community Health Worker',
                    'Biostatistician',
                    'Environmental Health Specialist',
                    'Health Program Manager',
                    'Surveillance Officer',
                ],
            ],
            [
                'slug' => 'ayush',
                'name' => 'AYUSH',
                'sort_order' => 8,
                'subcategories' => [
                    'Ayurvedic Doctor',
                    'Homeopathic Doctor',
                    'Unani Practitioner',
                    'Siddha Practitioner',
                    'Yoga Therapist',
                    'Naturopath',
                    'Panchakarma Therapist',
                ],
            ],
            [
                'slug' => 'research-academics',
                'name' => 'Research & Academics',
                'sort_order' => 9,
                'subcategories' => [
                    'Medical Researcher',
                    'Clinical Trial Coordinator',
                    'Medical Writer',
                    'Professor (Medical)',
                    'Associate Professor',
                    'Lecturer (Medical)',
                    'Research Assistant',
                    'Biomedical Engineer',
                ],
            ],
            [
                'slug' => 'emergency-critical-care',
                'name' => 'Emergency & Critical Care',
                'sort_order' => 10,
                'subcategories' => [
                    'Emergency Medicine Doctor',
                    'Intensivist',
                    'Paramedic / EMT',
                    'Trauma Surgeon',
                    'Critical Care Nurse',
                    'Flight Nurse',
                    'Disaster Management Specialist',
                ],
            ],
        ];

        foreach ($categories as $catData) {
            $category = JobCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'sort_order' => $catData['sort_order'],
                    'is_active' => true,
                ]
            );

            foreach ($catData['subcategories'] as $index => $subName) {
                JobSubcategory::updateOrCreate(
                    [
                        'job_category_id' => $category->id,
                        'slug' => \Illuminate\Support\Str::slug($subName),
                    ],
                    [
                        'name' => $subName,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
