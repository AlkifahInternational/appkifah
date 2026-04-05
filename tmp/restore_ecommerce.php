<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SubService;
use App\Models\ServiceOption;

$webSub = SubService::where('slug', 'web-development')->first();

if ($webSub) {
    ServiceOption::updateOrCreate(
        ['sub_service_id' => $webSub->id, 'name_en' => 'E-Commerce Store (Full Setup)'],
        ['name_ar' => 'متجر إلكتروني متكامل', 'base_price' => 4500, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'project', 'unit_label_ar' => 'مشروع', 'sort_order' => 2]
    );

    echo "E-Commerce option restored successfully.\n";
} else {
    echo "Web Development sub-service not found.\n";
}
