<?php

namespace Database\Seeders;

use App\Models\Designation;
use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Doctor / Physician' => [
                'General Practitioner', 'Cardiologist', 'Neurologist', 'Orthopedic Surgeon',
                'Pediatrician', 'Dermatologist', 'Psychiatrist', 'Oncologist', 'Radiologist',
                'Anesthesiologist', 'Emergency Medicine', 'Gynecologist', 'Urologist',
                'ENT Specialist', 'Ophthalmologist', 'Gastroenterologist', 'Pulmonologist',
                'Endocrinologist', 'Rheumatologist', 'Nephrologist',
            ],
            'Nurse / Nursing Professional' => [
                'Staff Nurse', 'Senior Nurse', 'Nurse Practitioner', 'ICU Nurse', 'OT Nurse',
                'Pediatric Nurse', 'Psychiatric Nurse', 'Community Health Nurse', 'Midwife',
                'Nurse Educator', 'Head Nurse', 'Nursing Superintendent',
            ],
            'Dentist / Dental Professional' => [
                'General Dentist', 'Orthodontist', 'Oral Surgeon', 'Periodontist',
                'Endodontist', 'Prosthodontist', 'Pedodontist',
            ],
            'Pharmacist' => [
                'Clinical Pharmacist', 'Hospital Pharmacist', 'Retail Pharmacist',
                'Pharmaceutical Researcher', 'Drug Regulatory Affairs',
            ],
            'Allied Health Professional' => [
                'Physiotherapist', 'Occupational Therapist', 'Speech Therapist',
                'Dietitian / Nutritionist', 'Medical Lab Technician', 'Radiographer / X-ray Technician',
                'ECG Technician', 'OT Technician', 'Dialysis Technician', 'Optometrist', 'Audiologist',
            ],
            'Healthcare Administrator' => [
                'Hospital Administrator', 'Healthcare Manager', 'Medical Coordinator',
                'Patient Care Coordinator', 'Health Information Manager', 'Medical Billing Specialist',
                'Hospital HR Manager', 'Quality Assurance Officer',
            ],
            'Medical Research & Education' => [
                'Medical Researcher', 'Clinical Research Associate', 'Biomedical Scientist',
                'Medical Professor / Lecturer', 'Clinical Trainer', 'Public Health Specialist',
            ],
            'Mental Health Professional' => [
                'Psychologist', 'Counselor', 'Psychotherapist', 'Behavioral Therapist',
            ],
            'Emergency & Paramedical' => [
                'Paramedic', 'Emergency Medical Technician (EMT)', 'First Responder',
                'Ambulance Driver', 'Critical Care Paramedic',
            ],
            'Veterinary' => [
                'Veterinary Doctor', 'Veterinary Nurse', 'Veterinary Pharmacist',
            ],
        ];

        $sortOrder = 1;
        foreach ($data as $designationName => $subDesignations) {
            $designation = Designation::firstOrCreate(
                ['name' => $designationName],
                ['category' => 'healthcare', 'is_active' => true, 'sort_order' => $sortOrder]
            );

            $subSort = 1;
            foreach ($subDesignations as $subName) {
                $designation->subDesignations()->firstOrCreate(
                    ['name' => $subName],
                    ['is_active' => true, 'sort_order' => $subSort]
                );
                $subSort++;
            }
            $sortOrder++;
        }
    }
}
