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
    // 1. Re-create/Identify the broad sub-service
    $cctv = SubService::updateOrCreate(
        ['slug' => 'cctv-installation', 'service_id' => $security->id],
        ['name_en' => 'Camera System Installation', 'name_ar' => 'تركيب أنظمة الكاميرات', 'icon' => 'camera', 'sort_order' => 1]
    );

    // 2. Clear all options for this sub-service
    ServiceOption::where('sub_service_id', $cctv->id)->delete();

    // 3. Add ALL required items as options under this single sub-service
    // Cameras
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => 'Analog HD Camera (5MP)', 'name_ar' => 'كاميرا أنالوج 5 ميجا', 'base_price' => 120, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 32, 'unit_label_en' => 'units', 'unit_label_ar' => 'كاميرا', 'sort_order' => 1]);
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => 'IP POE Camera (4MP)', 'name_ar' => 'كاميرا شبكية IP مع POE', 'base_price' => 250, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 32, 'unit_label_en' => 'units', 'unit_label_ar' => 'كاميرا', 'sort_order' => 2]);
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => 'PTZ Moving Camera', 'name_ar' => 'كاميرا متحركة PTZ', 'base_price' => 850, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'units', 'unit_label_ar' => 'كاميرا', 'sort_order' => 3]);

    // DVRs
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => '4-Channel DVR/NVR unit', 'name_ar' => 'جهاز تسجيل 4 قنوات', 'base_price' => 450, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز', 'sort_order' => 4]);
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => '8-Channel DVR/NVR unit', 'name_ar' => 'جهاز تسجيل 8 قنوات', 'base_price' => 750, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز', 'sort_order' => 5]);
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => '16-Channel DVR/NVR unit', 'name_ar' => 'جهاز تسجيل 16 قناة', 'base_price' => 1350, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز', 'sort_order' => 6]);

    // Storage & Packages
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => 'Hard Drive (2TB)', 'name_ar' => 'هاردسك 2 تيرابايت للقرص', 'base_price' => 350, 'urgent_multiplier' => 1, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'units', 'unit_label_ar' => 'حبة', 'sort_order' => 7]);
    ServiceOption::create(['sub_service_id' => $cctv->id, 'name_en' => 'Cabling & Installation Package', 'name_ar' => 'باقة التمديدات والتركيب الكاملة', 'base_price' => 200, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 10, 'unit_label_en' => 'pts', 'unit_label_ar' => 'نقطة', 'sort_order' => 8]);

    // 4. Delete the categorized sub-services
    SubService::whereIn('slug', ['cctv-cameras', 'cctv-recorders', 'cctv-accessories'])->delete();
    
    echo "Consolidated CCTV catalog with DVR and Packages created successfully.\n";
} else {
    echo "Security service not found.\n";
}
