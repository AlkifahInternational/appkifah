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
    // 1. Create a single broad sub-service
    $mainSub = SubService::updateOrCreate(
        ['slug' => 'software-solutions', 'service_id' => $software->id],
        ['name_en' => 'Software & Mobile App Solutions', 'name_ar' => 'حلول البرمجيات وتطبيقات الجوال', 'icon' => 'code', 'sort_order' => 1]
    );

    // 2. Identify all related sub-services for consolidation
    $slugs = ['web-development', 'mobile-apps', 'mobile-development', 'pos-systems', 'networking', 'digital-marketing'];
    $subIds = SubService::whereIn('slug', $slugs)->pluck('id')->toArray();

    // 3. Reassign existing options to the new main sub-service
    ServiceOption::whereIn('sub_service_id', $subIds)->update(['sub_service_id' => $mainSub->id]);

    // 4. Delete the now-empty sub-services
    SubService::whereIn('id', $subIds)->delete();

    echo "Software sub-services consolidated into a single professional menu successfully.\n";
} else {
    echo "Software service not found.\n";
}
