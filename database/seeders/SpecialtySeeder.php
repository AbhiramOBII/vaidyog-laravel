<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Doctors & Physicians',       'search_term' => 'doctor',          'sort_order' => 1,  'is_featured' => true],
            ['name' => 'Nursing Professionals',       'search_term' => 'nurse',           'sort_order' => 2,  'is_featured' => true],
            ['name' => 'Dental Professionals',        'search_term' => 'dentist',         'sort_order' => 3,  'is_featured' => true],
            ['name' => 'Pharmacy',                    'search_term' => 'pharmacist',      'sort_order' => 4,  'is_featured' => true],
            ['name' => 'Physiotherapy',               'search_term' => 'physiotherapist', 'sort_order' => 5,  'is_featured' => true],
            ['name' => 'Radiology & Imaging',         'search_term' => 'radiology',       'sort_order' => 6,  'is_featured' => true],
            ['name' => 'Laboratory & Pathology',      'search_term' => 'lab',             'sort_order' => 7],
            ['name' => 'Ayurveda & AYUSH',            'search_term' => 'ayurveda',        'sort_order' => 8],
            ['name' => 'Mental Health & Psychology',  'search_term' => 'psychology',      'sort_order' => 9],
            ['name' => 'Optometry & Ophthalmology',   'search_term' => 'optometry',       'sort_order' => 10],
            ['name' => 'Dietetics & Nutrition',       'search_term' => 'dietitian',       'sort_order' => 11],
            ['name' => 'Healthcare Administration',   'search_term' => 'admin',           'sort_order' => 12],
            ['name' => 'Allied Health Sciences',      'search_term' => 'allied health',   'sort_order' => 13],
            ['name' => 'Medical Research',            'search_term' => 'research',        'sort_order' => 14],
            ['name' => 'Emergency & Critical Care',   'search_term' => 'emergency',       'sort_order' => 15],
            ['name' => 'Surgery & Anaesthesiology',   'search_term' => 'surgery',         'sort_order' => 16, 'is_featured' => true],
            ['name' => 'Cardiology',                  'search_term' => 'cardiology',      'sort_order' => 17, 'is_featured' => true],
            ['name' => 'Dermatology',                 'search_term' => 'dermatology',     'sort_order' => 18],
            ['name' => 'Paediatrics',                 'search_term' => 'paediatrics',     'sort_order' => 19],
            ['name' => 'Obstetrics & Gynaecology',    'search_term' => 'gynaecology',     'sort_order' => 20],
            ['name' => 'Orthopaedics',                'search_term' => 'orthopaedics',    'sort_order' => 21],
            ['name' => 'ENT (Otorhinolaryngology)',   'search_term' => 'ent',             'sort_order' => 22],
            ['name' => 'Oncology',                    'search_term' => 'oncology',        'sort_order' => 23],
            ['name' => 'Nephrology & Urology',        'search_term' => 'nephrology',      'sort_order' => 24],
            ['name' => 'Pulmonology',                 'search_term' => 'pulmonology',     'sort_order' => 25],
            ['name' => 'Gastroenterology',            'search_term' => 'gastroenterology','sort_order' => 26],
            ['name' => 'Neurology & Neurosurgery',    'search_term' => 'neurology',       'sort_order' => 27],
            ['name' => 'Public Health & Epidemiology','search_term' => 'public health',   'sort_order' => 28],
            ['name' => 'Home Healthcare & Geriatrics','search_term' => 'geriatrics',      'sort_order' => 29],
            ['name' => 'Medical Coding & Billing',    'search_term' => 'medical coding',  'sort_order' => 30],
        ];

        foreach ($specialties as $data) {
            Specialty::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($data['name'])],
                $data
            );
        }
    }
}
