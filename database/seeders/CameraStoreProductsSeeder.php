<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubService;
use App\Models\ServiceOption;

class CameraStoreProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Service ID 3 = camera-system-and-security
        // We'll create 2 new SubServices and add all store products

        // ── 1. Camera Packages SubService (or reuse cctv-cameras = 27) ──────
        $cameraPkgSub = SubService::firstOrCreate(
            ['slug' => 'camera-packages', 'service_id' => 3],
            [
                'name_ar'     => 'باقات الكاميرات الكاملة',
                'name_en'     => 'Complete Camera Packages',
                'icon'        => 'camera',
                'sort_order'  => 1,
                'is_active'   => true,
            ]
        );

        // ── 2. Store Installation Services SubService ─────────────────────
        $installSub = SubService::firstOrCreate(
            ['slug' => 'store-installation', 'service_id' => 3],
            [
                'name_ar'     => 'خدمات التركيب',
                'name_en'     => 'Installation Services',
                'icon'        => 'hammer',
                'sort_order'  => 2,
                'is_active'   => true,
            ]
        );

        // ── 3. Attendance & Smart Locks SubService ────────────────────────
        $attendanceSub = SubService::firstOrCreate(
            ['slug' => 'store-attendance-locks', 'service_id' => 3],
            [
                'name_ar'     => 'أجهزة الحضور والأقفال الذكية',
                'name_en'     => 'Attendance Devices & Smart Locks',
                'icon'        => 'bell',
                'sort_order'  => 3,
                'is_active'   => true,
            ]
        );

        // ── 4. Dashcam & GPS Tracking SubService ─────────────────────────
        $dashcamSub = SubService::firstOrCreate(
            ['slug' => 'store-dashcams', 'service_id' => 3],
            [
                'name_ar'     => 'داش كام وأجهزة التتبع',
                'name_en'     => 'Dashcams & GPS Tracking',
                'icon'        => 'smartphone',
                'sort_order'  => 4,
                'is_active'   => true,
            ]
        );

        // ── Camera Packages ───────────────────────────────────────────────
        $cameraPackages = [
            [
                'name_ar'        => 'باقة 2 كاميرا 8MP + DVR',
                'name_en'        => '2-Camera 8MP Bundle + DVR',
                'description_ar' => 'رؤية ليلية 60م، كاميرات خارجية عالية الدقة، جهاز تسجيل مشمول. السعر الأصلي 1,150 ريال.',
                'description_en' => '60m night vision, outdoor HD cameras, DVR included. Original price 1,150 SAR.',
                'base_price'     => 999,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 1,
            ],
            [
                'name_ar'        => 'باقة 4 كاميرا 8MP + DVR',
                'name_en'        => '4-Camera 8MP Bundle + DVR',
                'description_ar' => 'كاميرات داخلية وخارجية عالية الدقة 8 ميجابكسل مع DVR. السعر الأصلي 1,680 ريال.',
                'description_en' => 'Indoor/outdoor 8MP cameras with DVR. Original price 1,680 SAR.',
                'base_price'     => 1300,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 2,
            ],
            [
                'name_ar'        => 'باقة 6 كاميرا 8MP + DVR',
                'name_en'        => '6-Camera 8MP Bundle + DVR',
                'description_ar' => 'DVR 8 قنوات، 6 كاميرات 8 ميجابكسل. السعر الأصلي 1,990 ريال.',
                'description_en' => '8-Channel DVR, 6 cameras at 8MP. Original price 1,990 SAR.',
                'base_price'     => 1799,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 3,
            ],
            [
                'name_ar'        => 'باقة 8 كاميرا 8MP + DVR',
                'name_en'        => '8-Camera 8MP Bundle + DVR',
                'description_ar' => 'DVR 8 قنوات، 8 كاميرات 8 ميجابكسل. السعر الأصلي 2,250 ريال.',
                'description_en' => '8-Channel DVR, 8 cameras at 8MP. Original price 2,250 SAR.',
                'base_price'     => 1899,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 4,
            ],
            [
                'name_ar'        => 'باقة 3 كاميرا 5MP + DVR (شاملة التركيب)',
                'name_en'        => '3-Camera 5MP Bundle + DVR (Includes Installation)',
                'description_ar' => 'شاملة التركيب بالرياض. السعر الأصلي 1,600 ريال.',
                'description_en' => 'Includes installation in Riyadh. Original price 1,600 SAR.',
                'base_price'     => 1400,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 5,
            ],
            [
                'name_ar'        => 'باقة 4 كاميرا 5MP + DVR (شاملة التركيب)',
                'name_en'        => '4-Camera 5MP Bundle + DVR (Includes Installation)',
                'description_ar' => 'شاملة التركيب بالرياض. السعر الأصلي 1,600 ريال.',
                'description_en' => 'Includes installation in Riyadh. Original price 1,600 SAR.',
                'base_price'     => 1500,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 6,
            ],
            [
                'name_ar'        => 'باقة 5 كاميرا 5MP + DVR (شاملة التركيب)',
                'name_en'        => '5-Camera 5MP Bundle + DVR (Includes Installation)',
                'description_ar' => 'شاملة التركيب بالرياض. السعر الأصلي 2,300 ريال.',
                'description_en' => 'Includes installation in Riyadh. Original price 2,300 SAR.',
                'base_price'     => 1950,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 7,
            ],
            [
                'name_ar'        => 'باقة 6 كاميرا 5MP + DVR (شاملة التركيب)',
                'name_en'        => '6-Camera 5MP Bundle + DVR (Includes Installation)',
                'description_ar' => 'شاملة التركيب بالرياض. السعر الأصلي 2,400 ريال.',
                'description_en' => 'Includes installation in Riyadh. Original price 2,400 SAR.',
                'base_price'     => 2250,
                'unit_label_ar'  => 'باقة',
                'unit_label_en'  => 'package',
                'sort_order'     => 8,
            ],
        ];

        foreach ($cameraPackages as $data) {
            ServiceOption::firstOrCreate(
                ['name_ar' => $data['name_ar'], 'sub_service_id' => $cameraPkgSub->id],
                array_merge($data, [
                    'sub_service_id'    => $cameraPkgSub->id,
                    'urgent_multiplier' => 1.3,
                    'min_quantity'      => 1,
                    'max_quantity'      => 1,
                    'is_active'         => true,
                ])
            );
        }

        // ── Installation Services ─────────────────────────────────────────
        $installOptions = [
            [
                'name_ar'        => 'فحص الكاميرات وتقرير فني',
                'name_en'        => 'Camera Inspection & Technical Report',
                'description_ar' => 'فحص شامل للمنظومة الأمنية وإصدار تقرير تقني مفصل.',
                'description_en' => 'Full security system inspection with detailed technical report.',
                'base_price'     => 500,
                'unit_label_ar'  => 'تقرير',
                'unit_label_en'  => 'report',
                'sort_order'     => 1,
            ],
            [
                'name_ar'        => 'تركيب كاميرا (شامل كابل وتوصيل)',
                'name_en'        => 'Camera Installation (Includes Cable & Wiring)',
                'description_ar' => 'يشمل الكابلات والموصلات والتركيب الاحترافي.',
                'description_en' => 'Includes cables, connectors, and professional mounting.',
                'base_price'     => 400,
                'unit_label_ar'  => 'كاميرا',
                'unit_label_en'  => 'camera',
                'sort_order'     => 2,
                'min_quantity'   => 1,
                'max_quantity'   => 32,
            ],
            [
                'name_ar'        => 'تركيب كاميرا (عمالة فقط)',
                'name_en'        => 'Camera Installation (Labor Only)',
                'description_ar' => 'تركيب فقط بدون تمديدات أو كابلات.',
                'description_en' => 'Installation only, excluding cables and wiring.',
                'base_price'     => 180,
                'unit_label_ar'  => 'كاميرا',
                'unit_label_en'  => 'camera',
                'sort_order'     => 3,
                'min_quantity'   => 1,
                'max_quantity'   => 32,
            ],
            [
                'name_ar'        => 'تركيب وبرمجة كاميرا واي فاي',
                'name_en'        => 'WiFi Camera Installation & Programming',
                'description_ar' => 'تركيب وإعداد الكاميرا اللاسلكية والاتصال بالشبكة.',
                'description_en' => 'WiFi camera installation, setup, and network connection.',
                'base_price'     => 200,
                'unit_label_ar'  => 'كاميرا',
                'unit_label_en'  => 'camera',
                'sort_order'     => 4,
                'min_quantity'   => 1,
                'max_quantity'   => 20,
            ],
            [
                'name_ar'        => 'تركيب موزع واي فاي / معزز إشارة',
                'name_en'        => 'WiFi Booster/Extender Installation',
                'description_ar' => 'تركيب وإعداد مقوي أو موزع الشبكة اللاسلكية.',
                'description_en' => 'WiFi extender or access point installation and setup.',
                'base_price'     => 200,
                'unit_label_ar'  => 'جهاز',
                'unit_label_en'  => 'device',
                'sort_order'     => 5,
            ],
            [
                'name_ar'        => 'معاينة موقع التركيب',
                'name_en'        => 'Installation Site Survey',
                'description_ar' => 'زيارة الموقع لتحديد أفضل تصميم وخطة للتركيب.',
                'description_en' => 'Site visit to identify the best installation design and plan.',
                'base_price'     => 200,
                'unit_label_ar'  => 'زيارة',
                'unit_label_en'  => 'visit',
                'sort_order'     => 6,
            ],
            [
                'name_ar'        => 'تركيب وبرمجة انتركم فيديو',
                'name_en'        => 'Video Intercom Installation & Programming',
                'description_ar' => 'تركيب وإعداد وبرمجة نظام الإنتركم المرئي الكامل.',
                'description_en' => 'Full video intercom installation, setup, and programming.',
                'base_price'     => 450,
                'unit_label_ar'  => 'نظام',
                'unit_label_en'  => 'system',
                'sort_order'     => 7,
            ],
        ];

        foreach ($installOptions as $data) {
            $data = array_merge([
                'min_quantity'   => 1,
                'max_quantity'   => 10,
                'urgent_multiplier' => 1.5,
            ], $data);

            ServiceOption::firstOrCreate(
                ['name_ar' => $data['name_ar'], 'sub_service_id' => $installSub->id],
                array_merge($data, [
                    'sub_service_id' => $installSub->id,
                    'is_active'      => true,
                ])
            );
        }

        // ── Attendance & Smart Locks ──────────────────────────────────────
        $attendanceOptions = [
            ['name_ar' => 'قفل تحكم بالوصول TF1700',    'name_en' => 'Access Control Lock TF1700',          'base_price' => 1170, 'description_ar' => 'قفل ذكي من ZKTeco لتحكم متكامل بالوصول.', 'description_en' => 'ZKTeco smart lock for full access control.', 'sort_order' => 1],
            ['name_ar' => 'جهاز التعرف على الوجه UFACE800-ID', 'name_en' => 'Face Recognition UFACE800-ID', 'base_price' => 1450, 'description_ar' => 'جهاز حضور وانصراف بتقنية التعرف على الوجه من ZKTeco.', 'description_en' => 'ZKTeco face recognition attendance device.', 'sort_order' => 2],
            ['name_ar' => 'جهاز بصمة الحضور F18',        'name_en' => 'Fingerprint Attendance F18',          'base_price' => 850,  'description_ar' => 'جهاز بصمة حضور ZKTeco F18 احترافي للشركات.', 'description_en' => 'ZKTeco F18 professional fingerprint attendance.', 'sort_order' => 3],
            ['name_ar' => 'بصمة/وجه MB1000',              'name_en' => 'Fingerprint/Face MB1000',            'base_price' => 850,  'description_ar' => 'جهاز حضور ZKTeco MB1000 بصمة ووجه.', 'description_en' => 'ZKTeco MB1000 fingerprint and face attendance.', 'sort_order' => 4],
            ['name_ar' => 'جهاز حضور رفيع F22',          'name_en' => 'Ultra-Thin Attendance F22',          'base_price' => 750,  'description_ar' => 'جهاز حضور ZKTeco F22 رفيع التصميم.', 'description_en' => 'ZKTeco F22 ultra-thin attendance device.', 'sort_order' => 5],
            ['name_ar' => 'جهاز بصمة حضور MB20',         'name_en' => 'Fingerprint Attendance MB20',        'base_price' => 620,  'description_ar' => 'جهاز حضور ZKTeco MB20 اقتصادي وفعّال.', 'description_en' => 'ZKTeco MB20 economical attendance device.', 'sort_order' => 6],
            ['name_ar' => 'قفل بصمة TL300B',              'name_en' => 'Fingerprint Lock TL300B',            'base_price' => 950,  'description_ar' => 'قفل باب ذكي بالبصمة من ZKTeco TL300B.', 'description_en' => 'ZKTeco TL300B smart fingerprint door lock.', 'sort_order' => 7],
            ['name_ar' => 'قفل مغناطيسي 500 كجم',        'name_en' => 'Magnetic Lock 500kg',                'base_price' => 380,  'description_ar' => 'قفل مغناطيسي قوة 500 كيلوجرام للأبواب الثقيلة.', 'description_en' => '500kg magnetic lock for heavy-duty doors.', 'sort_order' => 8],
            ['name_ar' => 'زر خروج معدني',                'name_en' => 'Metal Exit Button',                  'base_price' => 110,  'description_ar' => 'زر خروج احترافي معدني متين.', 'description_en' => 'Durable professional metal exit button.', 'sort_order' => 9],
            ['name_ar' => 'زر خروج بلاستيكي',             'name_en' => 'Plastic Exit Button',                'base_price' => 70,   'description_ar' => 'زر خروج اقتصادي بلاستيكي.', 'description_en' => 'Economical plastic exit button.', 'sort_order' => 10],
            ['name_ar' => 'زر خروج لاتلامسي K2S',         'name_en' => 'Touchless Exit Button K2S',          'base_price' => 170,  'description_ar' => 'زر خروج لاتلامسي بالاستشعار بالحركة.', 'description_en' => 'Motion-sensing touchless exit button.', 'sort_order' => 11],
        ];

        foreach ($attendanceOptions as $data) {
            ServiceOption::firstOrCreate(
                ['name_ar' => $data['name_ar'], 'sub_service_id' => $attendanceSub->id],
                array_merge($data, [
                    'sub_service_id'    => $attendanceSub->id,
                    'urgent_multiplier' => 1.2,
                    'min_quantity'      => 1,
                    'max_quantity'      => 20,
                    'unit_label_ar'     => 'وحدة',
                    'unit_label_en'     => 'unit',
                    'is_active'         => true,
                ])
            );
        }

        // ── Dashcams & GPS Tracking ───────────────────────────────────────
        $dashcamOptions = [
            ['name_ar' => 'جهاز GPS للتتبع (+ 6 أشهر اشتراك)', 'name_en' => 'GPS Tracking Device (+ 6 Months Subscription)', 'base_price' => 670, 'description_ar' => 'جهاز تتبع GPS مع اشتراك 6 أشهر مجاني في خدمة البيانات.', 'description_en' => 'GPS tracking device with 6-month free data subscription.', 'sort_order' => 1],
            ['name_ar' => 'داش كام AE-DC5113-F6S',             'name_en' => 'Dashcam AE-DC5113-F6S',                         'base_price' => 580, 'description_ar' => 'كاميرا سيارة داش كام AE-DC5113-F6S احترافية.', 'description_en' => 'Professional dashcam AE-DC5113-F6S.', 'sort_order' => 2],
            ['name_ar' => 'داش كام AE-DC4928-N6pro',           'name_en' => 'Dashcam AE-DC4928-N6pro',                       'base_price' => 565, 'description_ar' => 'كاميرا سيارة داش كام N6pro بمواصفات متقدمة.', 'description_en' => 'Advanced spec dashcam N6pro.', 'sort_order' => 3],
            ['name_ar' => 'داش كام Hikvision AE-DC4328-K5',    'name_en' => 'Hikvision Dashcam AE-DC4328-K5',               'base_price' => 490, 'description_ar' => 'داش كام هيكفيجن K5 جودة تسجيل عالية.', 'description_en' => 'Hikvision K5 dashcam with high-quality recording.', 'sort_order' => 4],
            ['name_ar' => 'داش كام Hikvision AE-DC2018-K2',    'name_en' => 'Hikvision Dashcam AE-DC2018-K2',               'base_price' => 310, 'description_ar' => 'داش كام هيكفيجن K2 اقتصادي وموثوق.', 'description_en' => 'Economical and reliable Hikvision K2 dashcam.', 'sort_order' => 5],
        ];

        foreach ($dashcamOptions as $data) {
            ServiceOption::firstOrCreate(
                ['name_ar' => $data['name_ar'], 'sub_service_id' => $dashcamSub->id],
                array_merge($data, [
                    'sub_service_id'    => $dashcamSub->id,
                    'urgent_multiplier' => 1.2,
                    'min_quantity'      => 1,
                    'max_quantity'      => 10,
                    'unit_label_ar'     => 'جهاز',
                    'unit_label_en'     => 'device',
                    'is_active'         => true,
                ])
            );
        }

        $this->command->info('✅ Camera store products seeded successfully!');
    }
}
