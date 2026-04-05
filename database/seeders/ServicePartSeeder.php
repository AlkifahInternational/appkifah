<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServicePart;

class ServicePartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parts = [
            // Hikvision
            [
                'name_ar' => 'كاميرا مراقبة على الطاقة الشمسية 4MP (DS-2DE2C400IWG-K)',
                'name_en' => 'Hikvision 4MP Solar Camera (DS-2DE2C400IWG-K)',
                'brand'   => 'Hikvision',
                'price_sar' => 910,
                'sku'     => 'DS-2DE2C400IWG-K',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'كاميرا مراقبة خارجية 8 ميجا - رؤية ليلية 60 متر',
                'name_en' => 'Hikvision 8MP Outdoor Camera 60m Night Vision',
                'brand'   => 'Hikvision',
                'price_sar' => 340,
                'sku'     => 'HIK-8MP-OUT-60M',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'انتركوم هيكفجن موديل DS-KIS213',
                'name_en' => 'Hikvision Intercom System DS-KIS213',
                'brand'   => 'Hikvision',
                'price_sar' => 500,
                'sku'     => 'DS-KIS213',
                'category' => 'Intercom',
            ],
            [
                'name_ar' => 'جهاز تسجيل شبكي 8 قنوات POE يدعم دقة 8 ميجا',
                'name_en' => 'Hikvision 8-Port POE NVR 4K (DS-7608NXI-I2/8P/S)',
                'brand'   => 'Hikvision',
                'price_sar' => 1250,
                'sku'     => 'DS-7608NXI-I2/8P/S',
                'category' => 'DVR',
            ],

            // EZVIZ
            [
                'name_ar' => 'كاميرا طاقة شمسية EZVIZ EB5 (Solar)',
                'name_en' => 'EZVIZ EB5 Solar Powered Smart Camera',
                'brand'   => 'EZVIZ',
                'price_sar' => 580,
                'sku'     => 'EZVIZ-EB5',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'كاميرا خارجية بالطاقة الشمسية EZVIZ EB8 2K',
                'name_en' => 'EZVIZ EB8 2K Battery/Solar Outdoor PTZ',
                'brand'   => 'EZVIZ',
                'price_sar' => 780,
                'sku'     => 'EZVIZ-EB8',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'كاميرا واي فاي 5G ذكية دقة 5 ميجا بكسل Ezviz H6',
                'name_en' => 'EZVIZ H6 5MP Smart WiFi Camera (5G)',
                'brand'   => 'EZVIZ',
                'price_sar' => 320,
                'sku'     => 'EZVIZ-H6',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'كامير واي فاي داخلية EZVIZ H6C 2K (4MP)',
                'name_en' => 'EZVIZ H6C 2K Interior WiFi Camera',
                'brand'   => 'EZVIZ',
                'price_sar' => 290,
                'sku'     => 'EZVIZ-H6C',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'كاميرا مراقبة اطفال ذكية H6C (C6N)',
                'name_en' => 'EZVIZ C6N Smart Baby Monitor',
                'brand'   => 'EZVIZ',
                'price_sar' => 220,
                'sku'     => 'EZVIZ-C6N',
                'category' => 'Camera',
            ],
            [
                'name_ar' => 'كاميرا مراقبة خارجية ذكية H3 3K (5MP)',
                'name_en' => 'EZVIZ H3 3K 5MP Outdoor Smart Camera',
                'brand'   => 'EZVIZ',
                'price_sar' => 320,
                'sku'     => 'EZVIZ-H3',
                'category' => 'Camera',
            ],

            // Bundles
            [
                'name_ar' => 'عدد 2 كاميرا 8 ميجا + جهاز تسجيل 4 قنوات',
                'name_en' => 'Bundle: 2x 8MP Cameras + 4CH DVR',
                'brand'   => 'Hikvision',
                'price_sar' => 890,
                'sku'     => 'BUNDLE-2CAM-8MP',
                'category' => 'Package',
            ],
            [
                'name_ar' => 'عدد 4 كاميرات 5 ميجا + جهاز تسجيل 4 قنوات (شامل التركيب)',
                'name_en' => 'Bundle: 4x 5MP Cameras + DVR + Installation',
                'brand'   => 'Hikvision',
                'price_sar' => 1500,
                'sku'     => 'BUNDLE-4CAM-5MP-INST',
                'category' => 'Package',
            ],
        ];

        foreach ($parts as $part) {
            ServicePart::updateOrCreate(['sku' => $part['sku']], $part);
        }
    }
}
