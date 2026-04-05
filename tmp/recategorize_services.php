<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;

// ── 1. Re-structure CCTV ───────────────────────────────────────────
$security = Service::where('slug', 'camera-system-and-security')->first();
if ($security) {
    // Create SubServices
    $cameraSub = SubService::updateOrCreate(['slug' => 'cctv-cameras', 'service_id' => $security->id], ['name_en' => 'CCTV Hardware (Cameras)', 'name_ar' => 'كاميرات المراقبة', 'icon' => 'camera', 'sort_order' => 1]);
    $recorderSub = SubService::updateOrCreate(['slug' => 'cctv-recorders', 'service_id' => $security->id], ['name_en' => 'DVR/NVR Recorders', 'name_ar' => 'أجهزة التسجيل (DVR/NVR)', 'icon' => 'hard-drive', 'sort_order' => 2]);
    $accSub = SubService::updateOrCreate(['slug' => 'cctv-accessories', 'service_id' => $security->id], ['name_en' => 'Storage & Installation', 'name_ar' => 'التخزين والتركيب', 'icon' => 'layers', 'sort_order' => 3]);

    // Move options to logical homes
    ServiceOption::where('name_en', 'like', '%Camera%')->update(['sub_service_id' => $cameraSub->id]);
    ServiceOption::where('name_en', 'like', '%DVR%')->orWhere('name_en', 'like', '%NVR%')->update(['sub_service_id' => $recorderSub->id]);
    ServiceOption::where('name_en', 'like', '%Hard Drive%')->orWhere('name_en', 'like', '%Cabling%')->update(['sub_service_id' => $accSub->id]);

    // Add extra variety
    ServiceOption::updateOrCreate(['sub_service_id' => $cameraSub->id, 'name_en' => 'Wireless IP Camera (WiFi)'], ['name_ar' => 'كاميرا لاسلكية WiFi', 'base_price' => 200, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 10, 'unit_label_en' => 'unit', 'unit_label_ar' => 'جهاز']);
    
    // Clean up Consolidated sub-service
    SubService::where('slug', 'cctv-installation')->delete();
}

// ── 2. Re-structure Software ─────────────────────────────────────────
$software = Service::where('slug', 'software-dev-marketing')->first();
if ($software) {
    // Create SubServices
    $webSub = SubService::updateOrCreate(['slug' => 'web-development', 'service_id' => $software->id], ['name_en' => 'Web Development', 'name_ar' => 'تطوير المواقع', 'icon' => 'globe', 'sort_order' => 1]);
    $mobSub = SubService::updateOrCreate(['slug' => 'mobile-development', 'service_id' => $software->id], ['name_en' => 'Mobile App Development', 'name_ar' => 'تطوير تطبيقات الجوال', 'icon' => 'smartphone', 'sort_order' => 2]);
    $mktSub = SubService::updateOrCreate(['slug' => 'digital-marketing', 'service_id' => $software->id], ['name_en' => 'Digital Marketing', 'name_ar' => 'التسويق الرقمي', 'icon' => 'trending-up', 'sort_order' => 3]);
    $itSub  = SubService::updateOrCreate(['slug' => 'it-solutions', 'service_id' => $software->id], ['name_en' => 'POS & IT Networking', 'name_ar' => 'أنظمة الكاشير والشبكات', 'icon' => 'server', 'sort_order' => 4]);

    // Redistribute options
    ServiceOption::where('name_en', 'like', '%Website%')->orWhere('name_en', 'like', '%Web Application%')->update(['sub_service_id' => $webSub->id]);
    ServiceOption::where('name_en', 'like', '%Mobile App%')->orWhere('name_en', 'like', '%iOS%')->update(['sub_service_id' => $mobSub->id]);
    ServiceOption::where('name_en', 'like', '%Marketing%')->orWhere('name_en', 'like', '%SEO%')->orWhere('name_en', 'like', '%Ads%')->update(['sub_service_id' => $mktSub->id]);
    ServiceOption::where('name_en', 'like', '%POS%')->orWhere('name_en', 'like', '%Network%')->update(['sub_service_id' => $itSub->id]);

    // Clean up Consolidated sub-service
    SubService::where('slug', 'software-solutions')->delete();
}

echo "Sub-services re-categorized logically with expanded options.\n";
