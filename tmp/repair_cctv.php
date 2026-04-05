<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;

$security = Service::where('slug', 'camera-system-and-security')->first();

if ($security) {
    // 1. Create Categorized SubServices
    $cameraHardware = SubService::updateOrCreate(
        ['slug' => 'cctv-cameras', 'service_id' => $security->id],
        ['name_en' => 'CCTV Cameras', 'name_ar' => 'كاميرات المراقبة', 'icon' => 'camera', 'sort_order' => 1]
    );

    $recorders = SubService::updateOrCreate(
        ['slug' => 'cctv-recorders', 'service_id' => $security->id],
        ['name_en' => 'DVR/NVR Recorders', 'name_ar' => 'أجهزة التسجيل (DVR/NVR)', 'icon' => 'hard-drive', 'sort_order' => 2]
    );

    $acc = SubService::updateOrCreate(
        ['slug' => 'cctv-accessories', 'service_id' => $security->id],
        ['name_en' => 'Storage & Accessories', 'name_ar' => 'التخزين والملحقات', 'icon' => 'layers', 'sort_order' => 3]
    );

    // 2. Clear and Add Options
    ServiceOption::whereIn('sub_service_id', [$cameraHardware->id, $recorders->id, $acc->id])->delete();

    // Cameras
    ServiceOption::create(['sub_service_id' => $cameraHardware->id, 'name_en' => 'Analog HD Camera (5MP)', 'name_ar' => 'كاميرا أنالوج 5 ميجا', 'base_price' => 120, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 32, 'unit_label_en' => 'units', 'unit_label_ar' => 'كاميرا']);
    ServiceOption::create(['sub_service_id' => $cameraHardware->id, 'name_en' => 'IP POE Camera (4K Ultra)', 'name_ar' => 'كاميرا IP بدقة 4K', 'base_price' => 280, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 32, 'unit_label_en' => 'units', 'unit_label_ar' => 'كاميرا']);
    ServiceOption::create(['sub_service_id' => $cameraHardware->id, 'name_en' => 'PTZ Speed Dome Camera', 'name_ar' => 'كاميرا متحركة PTZ', 'base_price' => 850, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'units', 'unit_label_ar' => 'كاميرا']);

    // Recorders
    ServiceOption::create(['sub_service_id' => $recorders->id, 'name_en' => '4-Channel DVR/NVR', 'name_ar' => 'جهاز تسجيل 4 قنوات', 'base_price' => 450, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز']);
    ServiceOption::create(['sub_service_id' => $recorders->id, 'name_en' => '8-Channel DVR/NVR', 'name_ar' => 'جهاز تسجيل 8 قنوات', 'base_price' => 750, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز']);
    ServiceOption::create(['sub_service_id' => $recorders->id, 'name_en' => '16-Channel DVR/NVR', 'name_ar' => 'جهاز تسجيل 16 قناة', 'base_price' => 1350, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز']);

    // Accessories
    ServiceOption::create(['sub_service_id' => $acc->id, 'name_en' => 'Surveillance HDD (2TB)', 'name_ar' => 'هاردسك 2 تيرابايت', 'base_price' => 350, 'urgent_multiplier' => 1, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'units', 'unit_label_ar' => 'قطعة']);
    ServiceOption::create(['sub_service_id' => $acc->id, 'name_en' => 'Full Cabling & Install Pack', 'name_ar' => 'باقة التمديدات والتركيب', 'base_price' => 200, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 10, 'unit_label_en' => 'pts', 'unit_label_ar' => 'نقطة']);

    // 3. Clean up the old broad sub-service
    SubService::where('slug', 'cctv-installation')->delete();
    
    echo "Categorized CCTV catalog created successfully.\n";
} else {
    echo "Security service not found.\n";
}
