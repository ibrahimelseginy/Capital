<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Report;
use App\Models\Document;
use App\Models\ExitRequest;
use App\Models\ExitRecord;
use App\Models\Consultation;
use App\Models\Event;
use App\Models\Nda;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users (Admin, Investor, Entrepreneur)
        $investor = User::firstOrCreate(
            ['email' => 'khalid@example.com'],
            ['name' => 'Khalid Al-Dosari', 'password' => bcrypt('password'), 'role' => 'investor']
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'System Admin', 'password' => bcrypt('password'), 'role' => 'admin']
        );

        $entrepreneur = User::firstOrCreate(
            ['email' => 'founder@example.com'],
            ['name' => 'Startup Founder', 'password' => bcrypt('password'), 'role' => 'entrepreneur']
        );

        // 2. Create Projects
        $projects = [
            ['title' => 'FinFlow', 'description' => 'FinTech · Series A', 'budget' => 800000, 'status' => 'Active', 'image' => 'F'],
            ['title' => 'DataPulse', 'description' => 'AI & Data · Seed+', 'budget' => 600000, 'status' => 'Scaling', 'image' => 'D'],
            ['title' => 'BuildOS', 'description' => 'PropTech · Seed', 'budget' => 400000, 'status' => 'Building', 'image' => 'B'],
            ['title' => 'HealthBridge', 'description' => 'HealthTech · Pre-Series A', 'budget' => 350000, 'status' => 'Active', 'image' => 'H'],
            ['title' => 'LogiFlow', 'description' => 'Logistics · Acquired', 'budget' => 250000, 'status' => 'Exited', 'image' => 'L']
        ];
        
        foreach ($projects as $p) {
            Project::create($p);
        }

        $finflow = Project::where('title', 'FinFlow')->first();
        $datapulse = Project::where('title', 'DataPulse')->first();
        $buildos = Project::where('title', 'BuildOS')->first();
        $logiflow = Project::where('title', 'LogiFlow')->first();

        // 3. Create Reports
        Report::create(['project_id' => $finflow->id, 'title' => 'Q1 2026 Performance Report', 'period' => 'Jan-Mar 2026', 'type' => 'Quarterly', 'status' => 'Published']);
        Report::create(['project_id' => $datapulse->id, 'title' => 'Monthly Update — May 2026', 'period' => 'May 2026', 'type' => 'Monthly', 'status' => 'Published']);
        Report::create(['project_id' => $buildos->id, 'title' => 'Due Diligence Report', 'period' => 'Mar 2026', 'type' => 'Due Diligence', 'status' => 'NDA Required']);

        // 4. Create Documents
        Document::create(['project_id' => $finflow->id, 'title' => 'Investment Agreement — FinFlow', 'type' => 'Legal', 'status' => 'Signed']);
        Document::create(['project_id' => $datapulse->id, 'title' => 'Share Certificate — DataPulse', 'type' => 'Financial', 'status' => 'Active']);
        Document::create(['title' => 'NDA — Project Alpha', 'type' => 'NDA', 'status' => 'Pending']);
        Document::create(['project_id' => $buildos->id, 'title' => 'Board Resolution — BuildOS', 'type' => 'Legal', 'status' => 'Signed']);

        // 5. Create NDAs
        Nda::create(['user_id' => $investor->id, 'project_id' => $finflow->id, 'status' => 'Active']);
        Nda::create(['user_id' => $investor->id, 'project_id' => $buildos->id, 'status' => 'Active']);

        // 6. Create Exit Requests
        ExitRequest::create(['project_id' => $finflow->id, 'user_id' => $investor->id, 'request_date' => '2026-06-01', 'type' => 'Partial Exit', 'amount' => 200000, 'status' => 'Under Review']);
        ExitRequest::create(['project_id' => $logiflow->id, 'user_id' => $investor->id, 'request_date' => '2025-10-01', 'type' => 'Full Exit', 'amount' => 250000, 'status' => 'Completed']);

        // 7. Create Exit Records
        ExitRecord::create(['project_id' => $logiflow->id, 'entry_date' => '2023-03-01', 'exit_date' => '2025-10-01', 'invested_amount' => 250000, 'returned_amount' => 1050000, 'multiple' => '4.2x', 'method' => 'Acquisition']);

        // 8. Create Consultations
        Consultation::create(['user_id' => $investor->id, 'title' => 'Portfolio Strategy Review', 'status' => 'Scheduled', 'scheduled_at' => '2026-06-15 14:00:00', 'with_name' => 'Ahmad Al-Rashid']);
        Consultation::create(['user_id' => $investor->id, 'title' => 'Exit Planning Discussion', 'status' => 'Pending Response', 'with_name' => 'Investment Committee']);
        Consultation::create(['user_id' => $investor->id, 'title' => 'Q2 Performance Deep Dive', 'status' => 'Completed', 'scheduled_at' => '2026-05-28 00:00:00', 'with_name' => 'Account Manager']);

        // 9. Create Events
        Event::create([
            'title' => 'Investor Briefing: Q2 Update', 
            'event_date' => '2026-07-28', 
            'location' => 'Online', 
            'status' => 'Registered', 
            'access_type' => 'Exclusive',
            'category' => 'Webinar',
            'time' => '02:00 PM - 04:00 PM (AST)',
            'description' => 'Exclusive update for our investors on Q2 performance and key milestones. Join us for a detailed breakdown of our portfolio companies and strategic direction.',
            'attendees_count' => 45,
            'speakers' => json_encode([
                ['name' => 'Fahad Al-Saud', 'name_ar' => 'فهد آل سعود', 'role' => 'Chief Investment Officer', 'role_ar' => 'الرئيس التنفيذي للاستثمار', 'image' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=100&h=100&fit=crop'],
            ]),
            'program' => json_encode([
                ['time' => '02:00 PM', 'title' => 'Q2 Financials Review', 'title_ar' => 'مراجعة الأداء المالي للربع الثاني'],
                ['time' => '03:00 PM', 'title' => 'Portfolio Company Updates', 'title_ar' => 'تحديثات شركات المحفظة'],
                ['time' => '03:45 PM', 'title' => 'Q&A Session', 'title_ar' => 'جلسة أسئلة وأجوبة'],
            ])
        ]);
        Event::create([
            'title' => 'Venture Demo Day 2026', 
            'event_date' => '2026-07-15', 
            'location' => 'Riyadh', 
            'status' => 'Registered', 
            'access_type' => 'VIP Access',
            'category' => 'Demo Day',
            'time' => '10:00 AM - 02:00 PM (AST)',
            'description' => 'Watch our latest ventures present to a curated audience of investors, partners, and industry leaders. Six companies, twelve minutes each, unlimited potential.',
            'attendees_count' => 150,
            'speakers' => json_encode([
                ['name' => 'Dr. Ahmed Abdullah', 'name_ar' => 'د. أحمد عبدالله', 'role' => 'CEO, STC', 'role_ar' => 'الرئيس التنفيذي، STC', 'image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&h=100&fit=crop'],
                ['name' => 'Sarah Al-Tamimi', 'name_ar' => 'سارة التميمي', 'role' => 'Partner, STC', 'role_ar' => 'شريك، STC', 'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=100&h=100&fit=crop'],
            ]),
            'program' => json_encode([
                ['time' => '10:00 AM', 'title' => 'Keynote Speech', 'title_ar' => 'الكلمة الافتتاحية'],
                ['time' => '10:30 AM', 'title' => 'Startup Pitches (Batch A)', 'title_ar' => 'عروض الشركات (المجموعة أ)'],
                ['time' => '12:00 PM', 'title' => 'Networking Break', 'title_ar' => 'استراحة تعارف'],
                ['time' => '12:30 PM', 'title' => 'Startup Pitches (Batch B)', 'title_ar' => 'عروض الشركات (المجموعة ب)'],
                ['time' => '01:30 PM', 'title' => 'Closing & Awards', 'title_ar' => 'الختام وتوزيع الجوائز'],
            ])
        ]);
        Event::create([
            'title' => 'Annual Investor Summit', 
            'event_date' => '2026-11-01', 
            'location' => 'Riyadh', 
            'status' => 'Coming Soon', 
            'access_type' => 'Exclusive',
            'category' => 'Conference',
            'time' => '09:00 AM - 05:00 PM (AST)',
            'description' => 'The premier gathering of top-tier founders, leading venture capitalists, and strategic partners. Discover the latest innovations shaping the future.',
            'attendees_count' => 300,
            'speakers' => json_encode([
                ['name' => 'Global Experts', 'name_ar' => 'خبراء عالميون', 'role' => 'TBA', 'role_ar' => 'يُعلن لاحقاً', 'image' => 'https://images.unsplash.com/photo-1531545514256-b1400bc00f31?w=100&h=100&fit=crop'],
            ]),
            'program' => json_encode([
                ['time' => '09:00 AM', 'title' => 'Registration & Breakfast', 'title_ar' => 'التسجيل والإفطار'],
                ['time' => '10:00 AM', 'title' => 'State of Venture Capital', 'title_ar' => 'حالة رأس المال الجريء'],
                ['time' => '02:00 PM', 'title' => 'Panel Discussions', 'title_ar' => 'حلقات النقاش'],
            ])
        ]);
    }
}
