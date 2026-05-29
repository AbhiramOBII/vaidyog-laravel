<?php

namespace Database\Seeders;

use App\Models\JobCategory;
use App\Models\JobSubcategory;
use Illuminate\Database\Seeder;

class JobTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        $taxonomy = [
            'doctors' => [
                'General Physician',
                'Cardiologist',
                'Dermatologist',
                'Neurologist',
                'Orthopedic Surgeon',
                'Pediatrician',
                'Gynecologist',
                'Ophthalmologist',
                'ENT Specialist',
                'Psychiatrist',
                'Oncologist',
                'Radiologist',
                'Anesthesiologist',
                'Urologist',
                'Gastroenterologist',
            ],
            'nursing' => [
                'Staff Nurse',
                'Head Nurse',
                'ICU Nurse',
                'OT Nurse',
                'Nursing Supervisor',
                'Home Care Nurse',
                'Pediatric Nurse',
                'Dialysis Nurse',
                'Oncology Nurse',
                'Emergency Nurse',
            ],
            'pharmacy' => [
                'Clinical Pharmacist',
                'Hospital Pharmacist',
                'Retail Pharmacist',
                'Pharmacy Manager',
                'Drug Inspector',
                'Pharmaceutical Sales',
            ],
            'lab-technicians' => [
                'Pathology Technician',
                'Radiology Technician',
                'X-Ray Technician',
                'MRI Technician',
                'CT Scan Technician',
                'Dialysis Technician',
                'OT Technician',
                'ECG Technician',
                'Cardiac Catheterization Technician',
                'Blood Bank Technician',
            ],
            'allied-health' => [
                'Physiotherapist',
                'Occupational Therapist',
                'Speech Therapist',
                'Dietitian',
                'Optometrist',
                'Audiologist',
                'Medical Social Worker',
                'Clinical Psychologist',
                'Respiratory Therapist',
            ],
            'hospital-administration' => [
                'Hospital Administrator',
                'Medical Records Officer',
                'Front Office Executive',
                'Billing Executive',
                'Insurance Coordinator',
                'Quality Manager',
                'Operations Manager',
                'HR Manager (Healthcare)',
                'Patient Relationship Manager',
            ],
            'dental' => [
                'Dentist',
                'Orthodontist',
                'Periodontist',
                'Endodontist',
                'Oral Surgeon',
                'Dental Hygienist',
                'Dental Technician',
                'Prosthodontist',
            ],
            'ayurveda-and-alternative-medicine' => [
                'Ayurvedic Practitioner',
                'Homeopathic Doctor',
                'Unani Practitioner',
                'Siddha Practitioner',
                'Naturopathy Doctor',
                'Yoga Therapist',
                'Panchakarma Therapist',
            ],
            'public-health' => [
                'Epidemiologist',
                'Community Health Officer',
                'Health Inspector',
                'Public Health Nurse',
                'Program Coordinator',
                'Health Educator',
                'Biostatistician',
            ],
            'biomedical-engineering' => [
                'Biomedical Engineer',
                'Medical Equipment Technician',
                'Clinical Engineer',
                'Service Engineer (Medical Devices)',
                'Application Specialist',
            ],
            'mental-health' => [
                'Psychiatrist',
                'Clinical Psychologist',
                'Counseling Psychologist',
                'Psychiatric Social Worker',
                'De-addiction Counselor',
                'Rehabilitation Therapist',
            ],
            'emergency-and-critical-care' => [
                'Emergency Medicine Physician',
                'Paramedic',
                'EMT (Emergency Medical Technician)',
                'Critical Care Specialist',
                'Trauma Surgeon',
                'Flight Nurse',
            ],
        ];

        $categorySortOrder = 1;

        foreach ($taxonomy as $slug => $subcategories) {
            $name = str($slug)->replace('-', ' ')->title()->toString();

            $category = JobCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'sort_order' => $categorySortOrder++,
                    'is_active' => true,
                ]
            );

            $subSortOrder = 1;
            foreach ($subcategories as $subcategoryName) {
                $subSlug = str($subcategoryName)->slug()->toString();

                JobSubcategory::updateOrCreate(
                    ['job_category_id' => $category->id, 'slug' => $subSlug],
                    [
                        'name' => $subcategoryName,
                        'sort_order' => $subSortOrder++,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
