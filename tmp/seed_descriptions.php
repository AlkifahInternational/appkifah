<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ServiceOption;

$explanations = [
    // CCTV Hardware
    'Analog HD Camera (5MP)' => [
        'en' => 'High-definition analog camera with 5MP resolution, perfect for general monitoring with clear day/night vision.',
        'ar' => 'كاميرا أنالوج عالية الدقة 5 ميجا بكسل، مثالية للمراقبة العامة مع رؤية ليلية ونهارية واضحة.'
    ],
    'IP POE Camera (4MP)' => [
        'en' => 'Advanced network camera with Power over Ethernet (POE). Provides superior 4K-ready clarity and remote access features.',
        'ar' => 'كاميرا شبكية متطورة تدعم تقنية POE، توفر وضوحاً فائقاً ومميزات الوصول عن بعد.'
    ],
    'PTZ Moving Camera' => [
        'en' => 'Pan-Tilt-Zoom camera that can be controlled remotely to rotate 360 degrees and zoom into specific areas.',
        'ar' => 'كاميرا متحركة (PTZ) يمكن التحكم بها عن بعد للدوران 360 درجة والتقريب لمناطق محددة.'
    ],
    // recorders
    '4-Channel DVR/NVR unit' => [
        'en' => 'Basic recording unit that supports up to 4 cameras. Includes remote viewing capabilities on mobile apps.',
        'ar' => 'جهاز تسجيل أساسي يدعم حتى 4 كاميرات، مع خاصية المشاهدة عن بعد عبر تطبيق الجوال.'
    ],
    '8-Channel DVR/NVR unit' => [
        'en' => 'Professional recording unit for up to 8 cameras. Ideal for medium-sized villas and businesses.',
        'ar' => 'جهاز تسجيل احترافي يدعم حتى 8 كاميرات، مثالي للفلل والمحلات المتوسطة.'
    ],
    '16-Channel DVR/NVR unit' => [
        'en' => 'High-capacity recorder for up to 16 cameras. Best for warehouses and large commercial complexes.',
        'ar' => 'جهاز تسجيل عالي السعة يدعم حتى 16 كاميرا، مناسب للمستودعات والمجمعات التجارية الكبيرة.'
    ],
    // Web Dev
    'Corporate Website (5-10 Pages)' => [
        'en' => 'A professional business website including About Us, Services, Portfolio, and Contact forms. Responsive design for all devices.',
        'ar' => 'موقع احترافي للشركات يشمل التعريف، الخدمات، معرض الأعمال، ونماذج التواصل. متوافق مع كافة الأجهزة.'
    ],
    'E-Commerce Store (Full Setup)' => [
        'en' => 'Complete online store with product management, shopping cart, and secure payment gateway integration.',
        'ar' => 'متجر إلكتروني متكامل مع إدارة المنتجات، سلة المشتريات، وربط بوابات الدفع الإلكتروني.'
    ],
    'Custom Web Application/SaaS' => [
        'en' => 'Tailor-made software built for the web. Includes management dashboards, complex databases, and unique business logic.',
        'ar' => 'برمجيات خاصة مبنية للويب، تشمل لوحات تحكم إدارية، قواعد بيانات معقدة، وبرمجة مخصصة لأعمالك.'
    ],
    // Mobile dev
    'iOS & Android (Cross-platform)' => [
        'en' => 'Single codebase app (Flutter/React Native) that works perfectly on both iPhone and Android, saving time and cost.',
        'ar' => 'تطبيق واحد يعمل على آيفون وأندرويد معاً، يوفر الوقت والتكلفة مع أداء ممتاز.'
    ],
    'Native Mobile App (Performance)' => [
        'en' => 'Premium app built specifically for iOS or Android using official languages. Highest speed, security, and hardware access.',
        'ar' => 'تطبيق مخصص مبني بلغات آبل أو جوجل الرسمية. يوفر أعلى سرعة، أمان، ووصول كامل لخصائص الجوال.'
    ],
    'UI/UX Mobile Design Only' => [
        'en' => 'Professional visual design of app screens (Figma). Focuses on user journey, modern aesthetics, and ease of use.',
        'ar' => 'تصميم واجهات احترافي (Figma) يركز على سهولة الاستخدام، الجمالية الحديثة، ورحلة المستخدم.'
    ],
    // Marketing
    'Social Media Management (1 Mo)' => [
        'en' => 'Monthly management of 3 platforms, including content creation, graphic design, and community engagement.',
        'ar' => 'إدارة شهرية لـ 3 منصات تشمل صناعة المحتوى، التصميم الجرافيكي، والتفاعل مع المتابعين.'
    ],
    'SEO Optimization (Rank Boost)' => [
        'en' => 'Technical website optimization to improve your ranking on Google Search results and increase organic traffic.',
        'ar' => 'تحسين تقني للموقع لرفع ترتيبه في نتائج بحث جوجل وزيادة الزوار بشكل طبيعي.'
    ],
    'Google & Meta Ads Campaign' => [
        'en' => 'Targeted advertising campaigns on Google Search, Instagram, and Facebook to reach new customers instantly.',
        'ar' => 'حملات إعلانية مستهدفة على محرك بحث جوجل، إنستقرام، وفيسبوك للوصول لعملاء جدد فوراً.'
    ]
];

foreach ($explanations as $name => $txt) {
    ServiceOption::where('name_en', $name)->update([
        'description_en' => $txt['en'],
        'description_ar' => $txt['ar']
    ]);
}

echo "Service descriptions populated successfully.\n";
