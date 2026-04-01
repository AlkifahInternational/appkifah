<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;

class ExtendedServicesSeeder extends Seeder
{
    public function run()
    {
        // 1. Get Parent Services
        $maintenance = Service::where('slug', 'home-maintenance')->first();
        $security = Service::where('slug', 'security-systems')->first();
        $software = Service::where('slug', 'software-development')->first();
        $construction = Service::where('slug', 'construction-contracting')->first();

        if (!$maintenance || !$security || !$software || !$construction) {
            return;
        }

        // --- EXTEND MAINTENANCE ---
        $pestControl = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'Pest Control',
            'name_ar' => 'مكافحة الحشرات',
            'slug' => 'pest-control',
            'icon' => 'bug',
            'sort_order' => 5,
        ]);
        ServiceOption::create(['sub_service_id' => $pestControl->id, 'name_en' => 'Apartment Spraying', 'name_ar' => 'رش شقة', 'base_price' => 150, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'apt', 'unit_label_ar' => 'شقة']);
        ServiceOption::create(['sub_service_id' => $pestControl->id, 'name_en' => 'Villa Deep Treatment', 'name_ar' => 'معالجة فيلا', 'base_price' => 350, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'villa', 'unit_label_ar' => 'فيلا']);

        $carpentry = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'Carpentry',
            'name_ar' => 'النجارة والأخشاب',
            'slug' => 'carpentry',
            'icon' => 'table',
            'sort_order' => 6,
        ]);
        ServiceOption::create(['sub_service_id' => $carpentry->id, 'name_en' => 'Furniture Assembly (IKEA)', 'name_ar' => 'تركيب أثاث', 'base_price' => 100, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 10, 'unit_label_en' => 'pieces', 'unit_label_ar' => 'قطع']);
        ServiceOption::create(['sub_service_id' => $carpentry->id, 'name_en' => 'Door Repair/Lock Change', 'name_ar' => 'إصلاح باب أو تغيير قفل', 'base_price' => 80, 'urgent_multiplier' => 1.75, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'doors', 'unit_label_ar' => 'أبواب']);

        $appliance = SubService::create([
            'service_id' => $maintenance->id,
            'name_en' => 'Appliance Repair',
            'name_ar' => 'إصلاح الأجهزة المنزلية',
            'slug' => 'appliance-repair',
            'icon' => 'zap',
            'sort_order' => 7,
        ]);
        ServiceOption::create(['sub_service_id' => $appliance->id, 'name_en' => 'Washing Machine Repair', 'name_ar' => 'إصلاح غسالة', 'base_price' => 150, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'units', 'unit_label_ar' => 'وحدة']);
        ServiceOption::create(['sub_service_id' => $appliance->id, 'name_en' => 'Refrigerator Gas Refill', 'name_ar' => 'تعبئة فريون ثلاجة', 'base_price' => 200, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'units', 'unit_label_ar' => 'وحدة']);

        // --- EXTEND SECURITY ---
        $intercom = SubService::create([
            'service_id' => $security->id,
            'name_en' => 'Intercom & Access Control',
            'name_ar' => 'الإنتركم وأنظمة الدخول',
            'slug' => 'intercom-access',
            'icon' => 'door-closed',
            'sort_order' => 3,
        ]);
        ServiceOption::create(['sub_service_id' => $intercom->id, 'name_en' => 'Video Intercom Install', 'name_ar' => 'تركيب إنتركم مرئي', 'base_price' => 300, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز']);
        ServiceOption::create(['sub_service_id' => $intercom->id, 'name_en' => 'Fingerprint Door Lock', 'name_ar' => 'قفل باب بالبصمة', 'base_price' => 250, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'locks', 'unit_label_ar' => 'أقفال']);

        $smartHome = SubService::create([
            'service_id' => $security->id,
            'name_en' => 'Smart Home Automation',
            'name_ar' => 'أتمتة المنازل الذكية',
            'slug' => 'smart-home',
            'icon' => 'wifi',
            'sort_order' => 4,
        ]);
        ServiceOption::create(['sub_service_id' => $smartHome->id, 'name_en' => 'Smart Lighting Setup', 'name_ar' => 'برمجة الإضاءة الذكية', 'base_price' => 50, 'urgent_multiplier' => 1.25, 'min_quantity' => 5, 'max_quantity' => 50, 'unit_label_en' => 'switches', 'unit_label_ar' => 'مفتاح']);

        // --- EXTEND SOFTWARE ---
        $pos = SubService::create([
            'service_id' => $software->id,
            'name_en' => 'Point of Sale (POS)',
            'name_ar' => 'أنظمة نقاط البيع',
            'slug' => 'pos-systems',
            'icon' => 'printer',
            'sort_order' => 3,
        ]);
        ServiceOption::create(['sub_service_id' => $pos->id, 'name_en' => 'POS Software Install', 'name_ar' => 'تثبيت نظام كاشير', 'base_price' => 500, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'registers', 'unit_label_ar' => 'جهاز']);
        
        $networking = SubService::create([
            'service_id' => $software->id,
            'name_en' => 'Networking & IT',
            'name_ar' => 'الشبكات والدعم الفني',
            'slug' => 'networking',
            'icon' => 'server',
            'sort_order' => 4,
        ]);
        ServiceOption::create(['sub_service_id' => $networking->id, 'name_en' => 'Office Network Setup', 'name_ar' => 'تسليك شبكة مكتب', 'base_price' => 800, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'points', 'unit_label_ar' => 'نقطة']);

        // --- EXTEND CONSTRUCTION ---
        $gypsum = SubService::create([
            'service_id' => $construction->id,
            'name_en' => 'Gypsum & Ceilings',
            'name_ar' => 'أسقف وجبس بورد',
            'slug' => 'gypsum-ceilings',
            'icon' => 'layers',
            'sort_order' => 3,
        ]);
        ServiceOption::create(['sub_service_id' => $gypsum->id, 'name_en' => 'Gypsum Board Install', 'name_ar' => 'تركيب جبس بورد', 'base_price' => 60, 'urgent_multiplier' => 1.25, 'min_quantity' => 10, 'max_quantity' => 200, 'unit_label_en' => 'sqm', 'unit_label_ar' => 'متر مكعب']);

        $waterproofing = SubService::create([
            'service_id' => $construction->id,
            'name_en' => 'Water & Heat Proofing',
            'name_ar' => 'العزل المائي والحراري',
            'slug' => 'waterproofing',
            'icon' => 'umbrella',
            'sort_order' => 4,
        ]);
        ServiceOption::create(['sub_service_id' => $waterproofing->id, 'name_en' => 'Roof Waterproofing', 'name_ar' => 'عزل أسطح مائي', 'base_price' => 45, 'urgent_multiplier' => 1.25, 'min_quantity' => 50, 'max_quantity' => 500, 'unit_label_en' => 'sqm', 'unit_label_ar' => 'متر مكعب']);
    }
}
