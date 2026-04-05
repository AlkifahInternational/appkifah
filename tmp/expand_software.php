<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;

$software = Service::where('slug', 'software-dev-marketing')->first();

if ($software) {
    // 1. Create Categorized SubServices
    $webDev = SubService::updateOrCreate(
        ['slug' => 'web-development', 'service_id' => $software->id],
        ['name_en' => 'Web Development', 'name_ar' => 'تطوير المواقع الإلكترونية', 'icon' => 'globe', 'sort_order' => 1]
    );

    $mobDev = SubService::updateOrCreate(
        ['slug' => 'mobile-development', 'service_id' => $software->id],
        ['name_en' => 'Mobile App Development', 'name_ar' => 'تطوير تطبيقات الجوال', 'icon' => 'smartphone', 'sort_order' => 2]
    );

    $marketing = SubService::updateOrCreate(
        ['slug' => 'digital-marketing', 'service_id' => $software->id],
        ['name_en' => 'Digital Marketing & SEO', 'name_ar' => 'التسويق الرقمي والسيو', 'icon' => 'trending-up', 'sort_order' => 3]
    );

    // 2. Clear and Add Options
    ServiceOption::whereIn('sub_service_id', [$webDev->id, $mobDev->id, $marketing->id])->delete();

    // Web Development Options
    ServiceOption::create(['sub_service_id' => $webDev->id, 'name_en' => 'Corporate Website (5-10 Pages)', 'name_ar' => 'موقع تعريفي للشركات', 'base_price' => 2500, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'site', 'unit_label_ar' => 'موقع', 'sort_order' => 1]);
    ServiceOption::create(['sub_service_id' => $webDev->id, 'name_en' => 'E-Commerce Store (Full Setup)', 'name_ar' => 'متجر إلكتروني متكامل', 'base_price' => 4500, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'site', 'unit_label_ar' => 'موقع', 'sort_order' => 2]);
    ServiceOption::create(['sub_service_id' => $webDev->id, 'name_en' => 'Custom Web Application/SaaS', 'name_ar' => 'تطبيق ويب مخصص (SaaS)', 'base_price' => 8500, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'project', 'unit_label_ar' => 'مشروع', 'sort_order' => 3]);

    // Mobile App Development Options
    ServiceOption::create(['sub_service_id' => $mobDev->id, 'name_en' => 'iOS & Android (Cross-platform)', 'name_ar' => 'تطبيق iOS وأندرويد (هجين)', 'base_price' => 6500, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'app', 'unit_label_ar' => 'تطبيق', 'sort_order' => 1]);
    ServiceOption::create(['sub_service_id' => $mobDev->id, 'name_en' => 'Native Mobile App (Performance)', 'name_ar' => 'تطبيق موبايل أصيل (Native)', 'base_price' => 12000, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'app', 'unit_label_ar' => 'تطبيق', 'sort_order' => 2]);
    ServiceOption::create(['sub_service_id' => $mobDev->id, 'name_en' => 'UI/UX Mobile Design Only', 'name_ar' => 'تصميم واجهات تطبيقات الجوال', 'base_price' => 1500, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'screens', 'unit_label_ar' => 'واجهات', 'sort_order' => 3]);

    // Digital Marketing Options
    ServiceOption::create(['sub_service_id' => $marketing->id, 'name_en' => 'Social Media Management (1 Mo)', 'name_ar' => 'إدارة شبكات التواصل (شهر)', 'base_price' => 1200, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 12, 'unit_label_en' => 'months', 'unit_label_ar' => 'شهر', 'sort_order' => 1]);
    ServiceOption::create(['sub_service_id' => $marketing->id, 'name_en' => 'SEO Optimization (Rank Boost)', 'name_ar' => 'تحسين محركات البحث SEO', 'base_price' => 2000, 'urgent_multiplier' => 1, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'months', 'unit_label_ar' => 'شهر', 'sort_order' => 2]);
    ServiceOption::create(['sub_service_id' => $marketing->id, 'name_en' => 'Google & Meta Ads Campaign', 'name_ar' => 'حملات إعلانية جوجل وميتا', 'base_price' => 500, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'campaigns', 'unit_label_ar' => 'حملة', 'sort_order' => 3]);

    echo "Software & Marketing Catalog expanded successfully.\n";
} else {
    echo "Software service not found.\n";
}
