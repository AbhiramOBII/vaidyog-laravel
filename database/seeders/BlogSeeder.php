<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Create categories
        $categories = [
            ['title' => 'Career Advice', 'status' => 'active'],
            ['title' => 'Industry News', 'status' => 'active'],
            ['title' => 'Interview Tips', 'status' => 'active'],
            ['title' => 'Health & Wellness', 'status' => 'active'],
        ];

        foreach ($categories as $cat) {
            BlogCategory::firstOrCreate(['title' => $cat['title']], $cat);
        }

        $careerAdvice = BlogCategory::where('title', 'Career Advice')->first();
        $industryNews = BlogCategory::where('title', 'Industry News')->first();
        $interviewTips = BlogCategory::where('title', 'Interview Tips')->first();
        $healthWellness = BlogCategory::where('title', 'Health & Wellness')->first();

        $blogs = [
            [
                'category_id' => $careerAdvice->id,
                'title' => '10 Tips to Build a Successful Career in Healthcare',
                'short_description' => 'Looking to advance your healthcare career? Here are proven strategies that top professionals use to climb the ladder.',
                'full_description' => '<h2>Start With a Strong Foundation</h2><p>A successful healthcare career begins with the right education and certifications. Whether you\'re a doctor, nurse, or allied health professional, continuous learning is key to staying relevant in this rapidly evolving field.</p><h2>1. Get Certified</h2><p>Certifications demonstrate your commitment to excellence. Employers look for candidates who go above and beyond the minimum requirements. Consider specialized certifications in your area of expertise.</p><h2>2. Network Actively</h2><p>Attend medical conferences, join professional associations, and connect with colleagues on platforms like Vaidyog. Networking opens doors to opportunities that are never publicly advertised.</p><h2>3. Stay Updated</h2><p>Subscribe to medical journals, follow thought leaders, and participate in continuing medical education (CME) programs. The healthcare landscape changes rapidly with new technologies and treatment protocols.</p><h2>4. Develop Soft Skills</h2><p>Communication, empathy, leadership, and teamwork are just as important as clinical skills. These soft skills differentiate good healthcare professionals from great ones.</p><h2>5. Seek Mentorship</h2><p>Find a mentor who has walked the path you want to follow. Their guidance can help you avoid common mistakes and accelerate your career growth.</p><h2>6. Be Open to Relocation</h2><p>Sometimes the best opportunities are in cities or states you haven\'t considered. Being flexible with location can dramatically expand your career options.</p><h2>7. Build an Online Presence</h2><p>Create a professional profile on healthcare job portals, maintain an updated resume, and consider publishing articles or case studies in your field.</p><h2>8. Take on Leadership Roles</h2><p>Volunteer for committees, lead quality improvement projects, or mentor junior colleagues. Leadership experience sets you apart.</p><h2>9. Focus on Patient Outcomes</h2><p>At the end of the day, healthcare is about patients. Professionals who consistently deliver excellent patient outcomes earn recognition and advancement.</p><h2>10. Plan Long-Term</h2><p>Have a 5-year and 10-year career plan. Know where you want to be and work backwards to identify the steps needed to get there.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'category_id' => $careerAdvice->id,
                'title' => 'How to Write a Medical Resume That Gets Noticed',
                'short_description' => 'Your resume is your first impression. Learn how to craft a compelling medical resume that lands interviews.',
                'full_description' => '<h2>The Anatomy of a Great Medical Resume</h2><p>A medical resume differs significantly from resumes in other industries. Recruiters spend an average of 6 seconds on initial screening, so every word must count.</p><h2>Key Sections to Include</h2><p><strong>Professional Summary:</strong> A 2-3 line snapshot of your experience, specialty, and key achievements. Make it specific to the role you\'re applying for.</p><p><strong>Clinical Experience:</strong> List your positions in reverse chronological order. Include hospital/clinic name, department, dates, and key responsibilities. Quantify achievements where possible — "Managed 30+ patients daily" is better than "Managed patients."</p><p><strong>Education & Certifications:</strong> List your degrees, medical licenses, and specialized certifications. Include the institution and year of completion.</p><p><strong>Skills:</strong> Highlight both clinical skills (procedures, equipment) and soft skills (patient communication, team leadership).</p><h2>Common Mistakes to Avoid</h2><ul><li>Using generic objectives instead of tailored summaries</li><li>Listing duties instead of achievements</li><li>Including irrelevant personal information</li><li>Poor formatting or excessive length</li></ul><h2>Pro Tips</h2><p>Tailor your resume for each application. Use keywords from the job description. Keep it to 2 pages maximum for experienced professionals, 1 page for freshers.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
            ],
            [
                'category_id' => $industryNews->id,
                'title' => 'Healthcare Hiring Trends in India for 2025',
                'short_description' => 'The Indian healthcare sector is booming. Discover which specialties are in highest demand and where the jobs are.',
                'full_description' => '<h2>A Growing Industry</h2><p>India\'s healthcare sector is projected to reach $372 billion by 2025, driven by increasing health awareness, rising incomes, and an aging population. This growth is creating unprecedented demand for healthcare professionals.</p><h2>Top Hiring Specialties</h2><p><strong>Critical Care & Emergency Medicine:</strong> Post-pandemic, hospitals are investing heavily in ICU and emergency departments. Demand for intensivists and emergency physicians has grown 40% year-over-year.</p><p><strong>Nursing:</strong> India faces a shortage of over 2 million nurses. Staff nurses, ICU nurses, and nurse practitioners are among the most sought-after roles.</p><p><strong>Digital Health:</strong> Telemedicine, health informatics, and medical coding professionals are seeing a surge in demand as hospitals digitize operations.</p><p><strong>Mental Health:</strong> With growing awareness of mental health issues, clinical psychologists and psychiatrists are increasingly in demand.</p><h2>Geographic Hotspots</h2><p>Tier-1 cities like Mumbai, Delhi, Bangalore, and Hyderabad continue to lead in healthcare hiring. However, tier-2 cities like Pune, Jaipur, Lucknow, and Kochi are emerging as significant hubs as hospital chains expand.</p><h2>Salary Trends</h2><p>Healthcare salaries have seen a 15-25% increase across specialties in the past two years. Experienced specialists in cardiology, orthopedics, and neurology command premium packages.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(7),
            ],
            [
                'category_id' => $industryNews->id,
                'title' => 'Telemedicine Revolution: How Digital Health is Changing Jobs',
                'short_description' => 'Telemedicine is not just changing how patients receive care — it\'s creating entirely new career paths for healthcare professionals.',
                'full_description' => '<h2>The Digital Shift</h2><p>Telemedicine consultations in India grew by 300% in the past three years. This isn\'t just a temporary trend — it represents a fundamental shift in healthcare delivery that\'s creating new job roles and transforming existing ones.</p><h2>New Roles in Telemedicine</h2><p><strong>Teleconsultation Physicians:</strong> Doctors who specialize in virtual consultations, requiring excellent communication skills and tech-savviness.</p><p><strong>Health Tech Coordinators:</strong> Professionals who bridge the gap between clinical staff and technology systems.</p><p><strong>Remote Patient Monitoring Specialists:</strong> Nurses and technicians who monitor patients remotely using wearable devices and IoT sensors.</p><p><strong>Medical Coders (Remote):</strong> With hospital digitization, medical coding has become a fully remote-capable career with strong demand.</p><h2>Skills in Demand</h2><ul><li>Electronic Health Records (EHR) proficiency</li><li>Video consultation etiquette</li><li>Data privacy and HIPAA/DISHA compliance</li><li>Digital diagnostic tools</li></ul><h2>Opportunities for Professionals</h2><p>Telemedicine allows healthcare professionals to practice from anywhere, offer second opinions across state lines, and maintain a better work-life balance. Many doctors now supplement their in-person practice with telemedicine hours.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(10),
            ],
            [
                'category_id' => $interviewTips->id,
                'title' => 'Cracking the Hospital Interview: What Recruiters Look For',
                'short_description' => 'Hospital interviews can be intimidating. Here\'s what hiring managers actually evaluate and how to prepare.',
                'full_description' => '<h2>Beyond Clinical Knowledge</h2><p>While your clinical expertise gets you the interview, it\'s your overall personality, communication style, and cultural fit that seals the deal. Here\'s what hospital recruiters evaluate:</p><h2>Clinical Competence (40%)</h2><p>Expect scenario-based questions testing your clinical decision-making. Practice common case presentations in your specialty. Be prepared to discuss recent cases you\'ve handled and the outcomes.</p><h2>Communication Skills (25%)</h2><p>Can you explain complex medical information clearly? How do you handle difficult conversations with patients and families? Recruiters assess this throughout the interview.</p><h2>Team Collaboration (20%)</h2><p>Healthcare is a team sport. Share examples of successful collaboration, conflict resolution, and how you handle disagreements with colleagues.</p><h2>Cultural Fit (15%)</h2><p>Every hospital has a unique culture. Research the institution beforehand. Understand their mission, values, and patient population. Show genuine interest in their work.</p><h2>Common Questions</h2><ul><li>"Walk me through how you would handle [clinical scenario]"</li><li>"Tell me about a difficult patient interaction and how you resolved it"</li><li>"Why do you want to work at our hospital specifically?"</li><li>"Where do you see yourself in 5 years?"</li><li>"How do you handle work-related stress?"</li></ul><h2>Tips to Stand Out</h2><p>Arrive 15 minutes early. Dress professionally. Bring extra copies of your resume and certificates. Ask thoughtful questions about the department, patient volume, and growth opportunities.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'category_id' => $interviewTips->id,
                'title' => '5 Questions You Should Always Ask in a Healthcare Interview',
                'short_description' => 'The questions you ask reveal as much about you as your answers. Here are smart questions that impress recruiters.',
                'full_description' => '<h2>Why Your Questions Matter</h2><p>At the end of every interview, you\'ll hear: "Do you have any questions for us?" This isn\'t a formality — it\'s your opportunity to demonstrate genuine interest and evaluate if the role is right for you.</p><h2>1. "What does a typical day look like in this department?"</h2><p>This shows you\'re thinking practically about the role. The answer reveals workload, patient volume, and team dynamics that the job description might not cover.</p><h2>2. "How does the hospital support professional development?"</h2><p>Asking about CME funding, conference attendance, and skill development programs shows you\'re committed to growth. Top hospitals invest in their staff.</p><h2>3. "What are the biggest challenges the department is currently facing?"</h2><p>This demonstrates strategic thinking. It also gives you insight into potential issues you\'d face. The interviewer\'s response reveals a lot about the work environment.</p><h2>4. "Can you describe the team I\'d be working with?"</h2><p>Understanding team composition, reporting structure, and collaboration patterns helps you assess cultural fit.</p><h2>5. "What does success look like in this role after 6 months?"</h2><p>This shows you\'re results-oriented and want to hit the ground running. It also clarifies expectations and helps you plan your onboarding.</p><h2>Questions to Avoid</h2><p>Don\'t ask about salary in the first interview (unless they bring it up). Avoid questions easily answered by reading their website. Never ask about time off or minimum hours in early stages.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(8),
            ],
            [
                'category_id' => $healthWellness->id,
                'title' => 'Preventing Burnout: Self-Care Tips for Healthcare Workers',
                'short_description' => 'Healthcare professionals face unique stress. Learn evidence-based strategies to protect your mental health while caring for others.',
                'full_description' => '<h2>The Burnout Epidemic</h2><p>Studies show that over 50% of healthcare workers experience burnout symptoms. Long hours, emotional labor, and high-stakes decisions take a toll that many professionals ignore until it\'s too late.</p><h2>Recognizing the Signs</h2><ul><li>Emotional exhaustion and feeling drained</li><li>Depersonalization — feeling detached from patients</li><li>Reduced sense of personal accomplishment</li><li>Physical symptoms: insomnia, headaches, frequent illness</li><li>Irritability and relationship strain</li></ul><h2>Evidence-Based Strategies</h2><h3>1. Set Boundaries</h3><p>Learn to say no. Not every shift swap, committee, or extra duty needs your yes. Protect your off-hours fiercely.</p><h3>2. Build a Support Network</h3><p>Connect with colleagues who understand your challenges. Peer support groups, both formal and informal, provide a safe space to decompress.</p><h3>3. Practice Mindfulness</h3><p>Even 5 minutes of mindful breathing between shifts can reduce cortisol levels. Apps like Headspace offer healthcare-specific programs.</p><h3>4. Prioritize Sleep</h3><p>Night shifts and irregular schedules disrupt circadian rhythms. Use blackout curtains, maintain a sleep schedule on off-days, and avoid screens before bed.</p><h3>5. Move Your Body</h3><p>Exercise is one of the most effective anti-burnout interventions. Even a 20-minute walk counts. Find movement you enjoy.</p><h3>6. Seek Professional Help</h3><p>There\'s no shame in a healthcare worker seeking therapy. Many hospitals now offer employee assistance programs (EAPs) with confidential counseling.</p><h2>For Employers</h2><p>Hospitals can reduce burnout by ensuring adequate staffing, offering flexible scheduling, creating rest spaces, and fostering a culture where seeking help is normalized.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
            ],
            [
                'category_id' => $healthWellness->id,
                'title' => 'Night Shift Survival Guide for Nurses and Doctors',
                'short_description' => 'Working nights doesn\'t have to wreck your health. Here\'s how to thrive on the night shift with science-backed strategies.',
                'full_description' => '<h2>The Night Shift Challenge</h2><p>Approximately 30% of healthcare workers regularly work night shifts. While essential for patient care, night work fights against our natural circadian biology. But with the right strategies, you can maintain your health and performance.</p><h2>Before Your Shift</h2><p><strong>Sleep strategically:</strong> Aim for 7-8 hours of sleep before your shift. Some prefer one long sleep, others split it into two blocks. Find what works for you.</p><p><strong>Eat a balanced meal:</strong> Have a proper dinner before leaving. Avoid heavy, greasy foods that cause sluggishness.</p><p><strong>Caffeine timing:</strong> Have coffee at the start of your shift, but avoid caffeine in the last 4 hours to protect post-shift sleep.</p><h2>During Your Shift</h2><p><strong>Stay hydrated:</strong> Dehydration amplifies fatigue. Keep a water bottle at your station.</p><p><strong>Snack smart:</strong> Pack protein-rich snacks — nuts, yogurt, boiled eggs. Avoid vending machine sugary snacks that cause energy crashes.</p><p><strong>Take strategic breaks:</strong> Even 10-minute breaks with bright light exposure can boost alertness.</p><p><strong>Move around:</strong> Use stairs, stretch between tasks. Physical movement combats drowsiness.</p><h2>After Your Shift</h2><p><strong>Wear sunglasses home:</strong> Blocking morning sunlight helps maintain your "night" melatonin levels.</p><p><strong>Create a dark, cool bedroom:</strong> Invest in blackout curtains and keep the room at 18-20°C.</p><p><strong>Avoid alcohol:</strong> While it may help you fall asleep, alcohol disrupts sleep quality and recovery.</p><h2>Days Off</h2><p>Gradually transition back to a day schedule on your off days. Maintain social connections — isolation is a major risk factor for night shift workers.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(12),
            ],
            [
                'category_id' => $careerAdvice->id,
                'title' => 'From MBBS to Specialist: Navigating the Post-Graduation Path',
                'short_description' => 'Choosing the right specialization after MBBS is one of the most critical career decisions. Here\'s a comprehensive guide.',
                'full_description' => '<h2>The Crossroads</h2><p>After completing MBBS, every doctor faces the big question: Which specialty should I pursue? With over 30 MD/MS branches and numerous super-specialties, the choice can be overwhelming.</p><h2>Factors to Consider</h2><h3>Interest & Aptitude</h3><p>Which subjects fascinated you during MBBS? Which clinical rotations excited you? Your genuine interest will sustain you through the demanding years of residency.</p><h3>Work-Life Balance</h3><p>Some specialties (Surgery, Emergency Medicine) demand unpredictable hours. Others (Dermatology, Radiology, Pathology) offer more predictable schedules. Be honest about your lifestyle preferences.</p><h3>Financial Prospects</h3><p>While all medical specialties offer good income potential, some have higher earning ceilings. However, choosing purely based on money often leads to career dissatisfaction.</p><h3>Availability of Seats</h3><p>Consider the competition for NEET-PG seats in your preferred specialty. Have backup options that also align with your interests.</p><h2>Popular Pathways</h2><p><strong>Clinical Sciences:</strong> Medicine, Surgery, OB-GYN, Pediatrics — direct patient care with high demand.</p><p><strong>Diagnostic Sciences:</strong> Radiology, Pathology — growing with technology, excellent work-life balance.</p><p><strong>Surgical Super-Specialties:</strong> Cardiothoracic, Neurosurgery, Plastic Surgery — longer training but high reward.</p><p><strong>Emerging Fields:</strong> Sports Medicine, Interventional Radiology, Genomics — fewer seats but growing demand.</p><h2>Preparation Strategy</h2><p>Start NEET-PG preparation early. Join a test series, create a study group, and maintain consistency over intensity. Clinical experience during internship also helps you make an informed choice.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(4),
            ],
        ];

        foreach ($blogs as $blogData) {
            Blog::firstOrCreate(
                ['title' => $blogData['title']],
                $blogData
            );
        }

        $this->command->info('Blogs seeded successfully: ' . count($blogs) . ' articles created.');
    }
}
