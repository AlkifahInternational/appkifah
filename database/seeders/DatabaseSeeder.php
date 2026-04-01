<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\SubService;
use App\Models\User;
use App\Models\Wallet;
use App\Models\TechnicianProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Create Users ──────────────────────────────────────────

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@alkifah.com',
            'phone' => '+966500000001',
            'password' => Hash::make('password'),
            'role' => UserRole::SUPER_ADMIN,
            'phone_verified' => true,
            'email_verified_at' => now(),
        ]);

        $manager = User::create([
            'name' => 'Ahmed Al-Rashid',
            'email' => 'manager@alkifah.com',
            'phone' => '+966500000002',
            'password' => Hash::make('password'),
            'role' => UserRole::TECHNICAL_MANAGER,
            'phone_verified' => true,
            'email_verified_at' => now(),
        ]);

        $technician1 = User::create([
            'name' => 'Mohammed Hassan',
            'email' => 'tech1@alkifah.com',
            'phone' => '+966500000003',
            'password' => Hash::make('password'),
            'role' => UserRole::TECHNICIAN,
            'phone_verified' => true,
            'email_verified_at' => now(),
        ]);

        $technician2 = User::create([
            'name' => 'Khalid Omar',
            'email' => 'tech2@alkifah.com',
            'phone' => '+966500000004',
            'password' => Hash::make('password'),
            'role' => UserRole::TECHNICIAN,
            'phone_verified' => true,
            'email_verified_at' => now(),
        ]);

        $client = User::create([
            'name' => 'Sara Abdullah',
            'email' => 'client@alkifah.com',
            'phone' => '+966500000005',
            'password' => Hash::make('password'),
            'role' => UserRole::CLIENT,
            'phone_verified' => true,
            'email_verified_at' => now(),
        ]);

        // ── Create Technician Profiles ──────────────────────

        TechnicianProfile::create([
            'user_id' => $technician1->id,
            'bio_en' => 'Expert AC and plumbing technician with 10+ years of experience.',
            'bio_ar' => 'فني تكييف وسباكة خبير بخبرة تفوق 10 سنوات.',
            'is_available' => true,
            'is_verified' => true,
            'rating' => 4.8,
            'total_jobs' => 156,
            'completed_jobs' => 148,
        ]);

        TechnicianProfile::create([
            'user_id' => $technician2->id,
            'bio_en' => 'Specialized in electrical systems and CCTV installation.',
            'bio_ar' => 'متخصص في الأنظمة الكهربائية وتركيب كاميرات المراقبة.',
            'is_available' => true,
            'is_verified' => true,
            'rating' => 4.6,
            'total_jobs' => 98,
            'completed_jobs' => 92,
        ]);

        // ── Create Wallets ──────────────────────

        Wallet::create(['user_id' => $technician1->id, 'balance' => 3500, 'total_earned' => 15200]);
        Wallet::create(['user_id' => $technician2->id, 'balance' => 2100, 'total_earned' => 9800]);

        // ── Create Services (Level 1) ──────────────────────

        $construction = Service::create([
            'name_en' => 'Construction & Contracting',
            'name_ar' => 'البناء والمقاولات',
            'slug' => 'construction-contracting',
            'description_en' => 'Professional construction and contracting services for commercial and residential projects.',
            'description_ar' => 'خدمات البناء والمقاولات الاحترافية للمشاريع التجارية والسكنية.',
            'icon'           => '🏗️',
            'color' => '#2B6CB0',
            'sort_order' => 1,
        ]);

        $maintenance = Service::create([
            'name_en' => 'Home Maintenance',
            'name_ar' => 'صيانة المنازل',
            'slug' => 'home-maintenance',
            'description_en' => 'Complete home repair and maintenance services at your doorstep.',
            'description_ar' => 'خدمات إصلاح وصيانة المنازل الشاملة عند باب منزلك.',
            'icon'           => '🏠',
            'color' => '#38A169',
            'sort_order' => 2,
        ]);

        $security = Service::create([
            'name_en' => 'Security Systems',
            'name_ar' => 'أنظمة الأمان',
            'slug' => 'security-systems',
            'description_en' => 'Advanced security and surveillance solutions for your property.',
            'description_ar' => 'حلول أمنية ومراقبة متقدمة لممتلكاتك.',
            'icon'           => '🛡️',
            'color' => '#E53E3E',
            'sort_order' => 3,
        ]);

        $software = Service::create([
            'name_en' => 'Software Development',
            'name_ar' => 'تطوير البرمجيات',
            'slug' => 'software-development',
            'description_en' => 'Custom software and web development solutions for your business.',
            'description_ar' => 'حلول تطوير البرمجيات والمواقع المخصصة لعملك.',
            'icon'           => '💻',
            'color' => '#805AD5',
            'sort_order' => 4,
        ]);

        // ── Create Sub-Services (Level 2) ──────────────────────

        // Construction Sub-Services
        $generalConst = SubService::create([
            'service_id' => $construction->id,
            'name_en' => 'General Construction',
            'name_ar' => 'البناء العام',
            'slug' => 'general-construction',
            'icon' => 'hammer',
            'sort_order' => 1,
        ]);

        $renovation = SubService::create([
            'service_id' => $construction->id,
            'name_en' => 'Renovation',
            'name_ar' => 'التجديد',
            'slug' => 'renovation',
            'icon' => 'paint-roller',
            'sort_order' => 2,
        ]);

        // Home Maintenance Sub-Services
        $acRepair = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'AC Repair & Maintenance',
            'name_ar' => 'إصلاح وصيانة المكيفات',
            'slug' => 'ac-repair',
            'icon' => 'snowflake',
            'sort_order' => 1,
        ]);

        $plumbing = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'Plumbing',
            'name_ar' => 'السباكة',
            'slug' => 'plumbing',
            'icon' => 'droplet',
            'sort_order' => 2,
        ]);

        $electrical = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'Electrical',
            'name_ar' => 'الكهرباء',
            'slug' => 'electrical',
            'icon' => 'zap',
            'sort_order' => 3,
        ]);

        $painting = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'Painting',
            'name_ar' => 'الدهان',
            'slug' => 'painting',
            'icon' => 'paintbrush',
            'sort_order' => 4,
        ]);

        // Security Sub-Services
        $cctv = SubService::create([
            'service_id' => $security->id,
            'name_en' => 'CCTV Installation',
            'name_ar' => 'تركيب كاميرات المراقبة',
            'slug' => 'cctv-installation',
            'icon' => 'camera',
            'sort_order' => 1,
        ]);

        $alarm = SubService::create([
            'service_id' => $security->id,
            'name_en' => 'Alarm Systems',
            'name_ar' => 'أنظمة الإنذار',
            'slug' => 'alarm-systems',
            'icon' => 'bell',
            'sort_order' => 2,
        ]);

        // Software Sub-Services
        $webDev = SubService::create([
            'service_id' => $software->id,
            'name_en' => 'Web Development',
            'name_ar' => 'تطوير المواقع',
            'slug' => 'web-development',
            'icon' => 'globe',
            'sort_order' => 1,
        ]);

        $mobileDev = SubService::create([
            'service_id' => $software->id,
            'name_en' => 'Mobile Apps',
            'name_ar' => 'تطبيقات الجوال',
            'slug' => 'mobile-apps',
            'icon' => 'smartphone',
            'sort_order' => 2,
        ]);

        // ── Create Service Options (Level 3) ──────────────────────

        // AC Repair Options
        ServiceOption::create([
            'sub_service_id' => $acRepair->id,
            'name_en' => 'AC Unit Repair',
            'name_ar' => 'إصلاح وحدة التكييف',
            'unit_label_en' => 'units',
            'unit_label_ar' => 'وحدات',
            'base_price' => 150.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'sort_order' => 1,
        ]);

        ServiceOption::create([
            'sub_service_id' => $acRepair->id,
            'name_en' => 'AC Deep Cleaning',
            'name_ar' => 'تنظيف عميق للمكيف',
            'unit_label_en' => 'units',
            'unit_label_ar' => 'وحدات',
            'base_price' => 100.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 15,
            'sort_order' => 2,
        ]);

        ServiceOption::create([
            'sub_service_id' => $acRepair->id,
            'name_en' => 'AC Installation',
            'name_ar' => 'تركيب مكيف',
            'unit_label_en' => 'units',
            'unit_label_ar' => 'وحدات',
            'base_price' => 250.00,
            'urgent_multiplier' => 1.75,
            'min_quantity' => 1,
            'max_quantity' => 5,
            'sort_order' => 3,
        ]);

        // Plumbing Options
        ServiceOption::create([
            'sub_service_id' => $plumbing->id,
            'name_en' => 'Pipe Repair',
            'name_ar' => 'إصلاح الأنابيب',
            'unit_label_en' => 'points',
            'unit_label_ar' => 'نقاط',
            'base_price' => 120.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 5,
            'sort_order' => 1,
        ]);

        ServiceOption::create([
            'sub_service_id' => $plumbing->id,
            'name_en' => 'Drain Cleaning',
            'name_ar' => 'تنظيف المجاري',
            'unit_label_en' => 'drains',
            'unit_label_ar' => 'مجارٍ',
            'base_price' => 80.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 5,
            'sort_order' => 2,
        ]);

        // Electrical Options
        ServiceOption::create([
            'sub_service_id' => $electrical->id,
            'name_en' => 'Wiring & Outlets',
            'name_ar' => 'الأسلاك والمنافذ',
            'unit_label_en' => 'points',
            'unit_label_ar' => 'نقاط',
            'base_price' => 90.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'sort_order' => 1,
        ]);

        // Painting Options
        ServiceOption::create([
            'sub_service_id' => $painting->id,
            'name_en' => 'Room Painting',
            'name_ar' => 'دهان الغرف',
            'unit_label_en' => 'rooms',
            'unit_label_ar' => 'غرف',
            'base_price' => 300.00,
            'urgent_multiplier' => 1.25,
            'min_quantity' => 1,
            'max_quantity' => 10,
            'sort_order' => 1,
        ]);

        // CCTV Options
        ServiceOption::create([
            'sub_service_id' => $cctv->id,
            'name_en' => 'Camera Installation',
            'name_ar' => 'تركيب الكاميرات',
            'unit_label_en' => 'cameras',
            'unit_label_ar' => 'كاميرات',
            'base_price' => 200.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 16,
            'sort_order' => 1,
        ]);

        // Alarm Options
        ServiceOption::create([
            'sub_service_id' => $alarm->id,
            'name_en' => 'Alarm System Setup',
            'name_ar' => 'إعداد نظام الإنذار',
            'unit_label_en' => 'zones',
            'unit_label_ar' => 'مناطق',
            'base_price' => 350.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 8,
            'sort_order' => 1,
        ]);

        // Web Dev Options
        ServiceOption::create([
            'sub_service_id' => $webDev->id,
            'name_en' => 'Custom Website',
            'name_ar' => 'موقع مخصص',
            'unit_label_en' => 'pages',
            'unit_label_ar' => 'صفحات',
            'base_price' => 2000.00,
            'urgent_multiplier' => 1.25,
            'min_quantity' => 1,
            'max_quantity' => 20,
            'sort_order' => 1,
        ]);

        // Mobile Dev Options
        ServiceOption::create([
            'sub_service_id' => $mobileDev->id,
            'name_en' => 'Mobile Application',
            'name_ar' => 'تطبيق جوال',
            'unit_label_en' => 'platforms',
            'unit_label_ar' => 'منصات',
            'base_price' => 5000.00,
            'urgent_multiplier' => 1.25,
            'min_quantity' => 1,
            'max_quantity' => 3,
            'sort_order' => 1,
        ]);

        // General Construction Options
        ServiceOption::create([
            'sub_service_id' => $generalConst->id,
            'name_en' => 'Site Assessment',
            'name_ar' => 'تقييم الموقع',
            'unit_label_en' => 'visits',
            'unit_label_ar' => 'زيارات',
            'base_price' => 500.00,
            'urgent_multiplier' => 1.50,
            'min_quantity' => 1,
            'max_quantity' => 3,
            'sort_order' => 1,
        ]);

        // Renovation Options
        ServiceOption::create([
            'sub_service_id' => $renovation->id,
            'name_en' => 'Room Renovation',
            'name_ar' => 'تجديد الغرف',
            'unit_label_en' => 'rooms',
            'unit_label_ar' => 'غرف',
            'base_price' => 3000.00,
            'urgent_multiplier' => 1.25,
            'min_quantity' => 1,
            'max_quantity' => 8,
            'sort_order' => 1,
        ]);

        $this->call([ExtendedServicesSeeder::class]);
    }
}
