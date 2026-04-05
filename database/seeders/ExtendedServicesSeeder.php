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
        $security = Service::whereIn('slug', ['security-systems', 'camera-system-and-security'])->first();
        $software = Service::whereIn('slug', ['software-dev-marketing', 'software-development'])->first();
        $construction = Service::where('slug', 'construction-contracting')->first();

        if (!$maintenance || !$security || !$software || !$construction) {
            return;
        }

        $upsertSubService = function (array $data): SubService {
            return SubService::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        };

        $upsertOption = function (SubService $subService, array $data): void {
            ServiceOption::updateOrCreate(
                ['sub_service_id' => $subService->id, 'name_en' => $data['name_en']],
                array_merge(['sub_service_id' => $subService->id], $data)
            );
        };

        // --- EXTEND MAINTENANCE ---
        $pestControl = $upsertSubService([
            'service_id' => $maintenance->id,
            'name_en' => 'Pest Control',
            'name_ar' => 'مكافحة الحشرات',
            'slug' => 'pest-control',
            'icon' => 'bug',
            'sort_order' => 5,
        ]);
        $upsertOption($pestControl, ['name_en' => 'Apartment Spraying', 'name_ar' => 'رش شقة', 'base_price' => 150, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'apt', 'unit_label_ar' => 'شقة']);
        $upsertOption($pestControl, ['name_en' => 'Villa Deep Treatment', 'name_ar' => 'معالجة فيلا', 'base_price' => 350, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'villa', 'unit_label_ar' => 'فيلا']);

        $carpentry = $upsertSubService([
            'service_id' => $maintenance->id,
            'name_en' => 'Carpentry',
            'name_ar' => 'النجارة والأخشاب',
            'slug' => 'carpentry',
            'icon' => 'table',
            'sort_order' => 6,
        ]);
        $upsertOption($carpentry, ['name_en' => 'Furniture Assembly (IKEA)', 'name_ar' => 'تركيب أثاث', 'base_price' => 100, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 10, 'unit_label_en' => 'pieces', 'unit_label_ar' => 'قطع']);
        $upsertOption($carpentry, ['name_en' => 'Door Repair/Lock Change', 'name_ar' => 'إصلاح باب أو تغيير قفل', 'base_price' => 80, 'urgent_multiplier' => 1.75, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'doors', 'unit_label_ar' => 'أبواب']);

        $appliance = $upsertSubService([
            'service_id' => $maintenance->id,
            'name_en' => 'Appliance Repair',
            'name_ar' => 'إصلاح الأجهزة المنزلية',
            'slug' => 'appliance-repair',
            'icon' => 'zap',
            'sort_order' => 7,
        ]);
        $upsertOption($appliance, ['name_en' => 'Washing Machine Repair', 'name_ar' => 'إصلاح غسالة', 'base_price' => 150, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'units', 'unit_label_ar' => 'وحدة']);
        $upsertOption($appliance, ['name_en' => 'Refrigerator Gas Refill', 'name_ar' => 'تعبئة فريون ثلاجة', 'base_price' => 200, 'urgent_multiplier' => 1.5, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'units', 'unit_label_ar' => 'وحدة']);

        // --- EXTEND SECURITY ---
        $intercom = $upsertSubService([
            'service_id' => $security->id,
            'name_en' => 'Intercom & Access Control',
            'name_ar' => 'الإنتركم وأنظمة الدخول',
            'slug' => 'intercom-access',
            'icon' => 'door-closed',
            'sort_order' => 3,
        ]);
        $upsertOption($intercom, ['name_en' => 'Video Intercom Install', 'name_ar' => 'تركيب إنتركم مرئي', 'base_price' => 300, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'units', 'unit_label_ar' => 'جهاز']);
        $upsertOption($intercom, ['name_en' => 'Fingerprint Door Lock', 'name_ar' => 'قفل باب بالبصمة', 'base_price' => 250, 'urgent_multiplier' => 1.25, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'locks', 'unit_label_ar' => 'أقفال']);

        $smartHome = $upsertSubService([
            'service_id' => $security->id,
            'name_en' => 'Smart Home Automation',
            'name_ar' => 'أتمتة المنازل الذكية',
            'slug' => 'smart-home',
            'icon' => 'wifi',
            'sort_order' => 4,
        ]);
        $upsertOption($smartHome, ['name_en' => 'Smart Lighting Setup', 'name_ar' => 'برمجة الإضاءة الذكية', 'base_price' => 50, 'urgent_multiplier' => 1.25, 'min_quantity' => 5, 'max_quantity' => 50, 'unit_label_en' => 'switches', 'unit_label_ar' => 'مفتاح']);

        // --- EXTEND SOFTWARE / MARKETING (idempotent and admin-manageable) ---
        $webDev = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'Web Development',
            'name_ar' => 'تطوير المواقع',
            'slug' => 'web-development',
            'icon' => 'globe',
            'sort_order' => 1,
        ]);

        $mobileDev = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'Mobile Apps',
            'name_ar' => 'تطبيقات الجوال',
            'slug' => 'mobile-apps',
            'icon' => 'smartphone',
            'sort_order' => 2,
        ]);

        $pos = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'Point of Sale (POS)',
            'name_ar' => 'أنظمة نقاط البيع',
            'slug' => 'pos-systems',
            'icon' => 'printer',
            'sort_order' => 3,
        ]);

        $networking = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'Networking & IT',
            'name_ar' => 'الشبكات والدعم الفني',
            'slug' => 'networking',
            'icon' => 'server',
            'sort_order' => 4,
        ]);

        $marketing = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'Digital Marketing',
            'name_ar' => 'التسويق الرقمي',
            'slug' => 'digital-marketing',
            'icon' => 'megaphone',
            'sort_order' => 5,
        ]);

        $seo = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'SEO & Analytics',
            'name_ar' => 'تحسين الظهور والتحليلات',
            'slug' => 'seo-analytics',
            'icon' => 'line-chart',
            'sort_order' => 6,
        ]);

        $ecommerce = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'E-Commerce Solutions',
            'name_ar' => 'حلول التجارة الإلكترونية',
            'slug' => 'ecommerce-solutions',
            'icon' => 'shopping-cart',
            'sort_order' => 7,
        ]);

        $branding = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'Branding & Content',
            'name_ar' => 'الهوية البصرية وصناعة المحتوى',
            'slug' => 'branding-content',
            'icon' => 'pen-tool',
            'sort_order' => 8,
        ]);

        $automation = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'ERP / CRM Automation',
            'name_ar' => 'أتمتة ERP و CRM',
            'slug' => 'erp-crm-automation',
            'icon' => 'cpu',
            'sort_order' => 9,
        ]);

        $uiux = $upsertSubService([
            'service_id' => $software->id,
            'name_en' => 'UI/UX Design',
            'name_ar' => 'تصميم واجهات وتجربة المستخدم',
            'slug' => 'ui-ux-design',
            'icon' => 'layout',
            'sort_order' => 10,
        ]);


        // Web Development options
        $upsertOption($webDev, ['name_en' => 'Personal Portfolio (3 pages)', 'name_ar' => 'بورتفوليو شخصي (3 صفحات)', 'base_price' => 1000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'sites', 'unit_label_ar' => 'موقع', 'sort_order' => 1]);
        $upsertOption($webDev, ['name_en' => 'Corporate Website (5 pages)', 'name_ar' => 'موقع تعريفي لشركة (5 صفحات)', 'base_price' => 1500, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'sites', 'unit_label_ar' => 'موقع', 'sort_order' => 2]);
        $upsertOption($webDev, ['name_en' => 'Full Corporate Website (Store + Dashboard)', 'name_ar' => 'موقع ويب شركة متكامل (متجر + لوحة تحكم)', 'base_price' => 5000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'sites', 'unit_label_ar' => 'موقع', 'sort_order' => 3]);
        $upsertOption($webDev, ['name_en' => 'Store with Dashboard', 'name_ar' => 'متجر مع لوحة تحكم', 'base_price' => 2000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'stores', 'unit_label_ar' => 'متجر', 'sort_order' => 4]);
        $upsertOption($webDev, ['name_en' => 'Educational Platform', 'name_ar' => 'منصة تعليمية', 'base_price' => 10000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'platforms', 'unit_label_ar' => 'منصة', 'sort_order' => 5]);
        $upsertOption($webDev, ['name_en' => 'Streaming / Service Platform', 'name_ar' => 'منصة مشاهدة / خدمات', 'base_price' => 12000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'platforms', 'unit_label_ar' => 'منصة', 'sort_order' => 6]);
        $upsertOption($webDev, ['name_en' => 'Social Media Website', 'name_ar' => 'موقع تواصل اجتماعي', 'base_price' => 7000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'sites', 'unit_label_ar' => 'موقع', 'sort_order' => 7]);

        // Mobile Apps options
        $upsertOption($mobileDev, ['name_en' => 'Mobile App', 'name_ar' => 'تطبيق موبايل', 'base_price' => 5000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'apps', 'unit_label_ar' => 'تطبيقات', 'sort_order' => 8]);
        $upsertOption($mobileDev, ['name_en' => 'Cross-Platform Mobile App (Android + iOS)', 'name_ar' => 'تطبيق موبايل (Android + iOS)', 'base_price' => 7000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 2, 'unit_label_en' => 'apps', 'unit_label_ar' => 'تطبيقات', 'sort_order' => 9]);
        $upsertOption($mobileDev, ['name_en' => 'Professional App (All Requirements)', 'name_ar' => 'تطبيق احترافي (يلبي كافة الاحتياجات)', 'base_price' => 12000, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 1, 'unit_label_en' => 'apps', 'unit_label_ar' => 'تطبيقات', 'sort_order' => 10]);

        // POS options
        $upsertOption($pos, ['name_en' => 'POS Software Install', 'name_ar' => 'تثبيت نظام كاشير', 'base_price' => 500, 'urgent_multiplier' => 1.50, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'registers', 'unit_label_ar' => 'أجهزة', 'sort_order' => 1]);
        $upsertOption($pos, ['name_en' => 'POS Integration with Inventory', 'name_ar' => 'ربط نقاط البيع بالمخزون', 'base_price' => 1300, 'urgent_multiplier' => 1.30, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'branches', 'unit_label_ar' => 'فروع', 'sort_order' => 2]);
        $upsertOption($pos, ['name_en' => 'POS Staff Training Session', 'name_ar' => 'تدريب فريق العمل على النظام', 'base_price' => 450, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'sessions', 'unit_label_ar' => 'جلسات', 'sort_order' => 3]);

        // Networking options
        $upsertOption($networking, ['name_en' => 'Office Network Setup', 'name_ar' => 'تجهيز شبكة مكتب', 'base_price' => 800, 'urgent_multiplier' => 1.50, 'min_quantity' => 1, 'max_quantity' => 8, 'unit_label_en' => 'points', 'unit_label_ar' => 'نقاط', 'sort_order' => 1]);
        $upsertOption($networking, ['name_en' => 'Server / NAS Configuration', 'name_ar' => 'إعداد السيرفر أو NAS', 'base_price' => 1500, 'urgent_multiplier' => 1.35, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'servers', 'unit_label_ar' => 'أجهزة', 'sort_order' => 2]);
        $upsertOption($networking, ['name_en' => 'Remote IT Support (Monthly)', 'name_ar' => 'دعم فني عن بعد (شهري)', 'base_price' => 600, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 12, 'unit_label_en' => 'months', 'unit_label_ar' => 'شهور', 'sort_order' => 3]);

        // Digital Marketing options
        $upsertOption($marketing, ['name_en' => 'Social Media Management (Monthly)', 'name_ar' => 'إدارة حسابات التواصل (شهري)', 'base_price' => 1800, 'urgent_multiplier' => 1.15, 'min_quantity' => 1, 'max_quantity' => 12, 'unit_label_en' => 'months', 'unit_label_ar' => 'شهور', 'sort_order' => 1]);
        $upsertOption($marketing, ['name_en' => 'Paid Ads Management (Google/Meta)', 'name_ar' => 'إدارة الإعلانات المدفوعة', 'base_price' => 2000, 'urgent_multiplier' => 1.15, 'min_quantity' => 1, 'max_quantity' => 12, 'unit_label_en' => 'months', 'unit_label_ar' => 'شهور', 'sort_order' => 2]);
        $upsertOption($marketing, ['name_en' => 'Content Plan + 20 Creatives', 'name_ar' => 'خطة محتوى + 20 تصميم', 'base_price' => 1600, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'packages', 'unit_label_ar' => 'باقات', 'sort_order' => 3]);

        // SEO options
        $upsertOption($seo, ['name_en' => 'SEO Audit', 'name_ar' => 'تدقيق SEO', 'base_price' => 900, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'audits', 'unit_label_ar' => 'تقارير', 'sort_order' => 1]);
        $upsertOption($seo, ['name_en' => 'On-Page SEO Optimization', 'name_ar' => 'تحسين صفحات الموقع', 'base_price' => 1400, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'packages', 'unit_label_ar' => 'باقات', 'sort_order' => 2]);
        $upsertOption($seo, ['name_en' => 'Technical SEO Fixes', 'name_ar' => 'حل مشاكل SEO التقنية', 'base_price' => 1700, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'packages', 'unit_label_ar' => 'باقات', 'sort_order' => 3]);
        $upsertOption($seo, ['name_en' => 'Local SEO (Google Business)', 'name_ar' => 'تحسين الظهور المحلي', 'base_price' => 850, 'urgent_multiplier' => 1.15, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'locations', 'unit_label_ar' => 'مواقع', 'sort_order' => 4]);

        // E-Commerce options
        $upsertOption($ecommerce, ['name_en' => 'Shopify Store Setup', 'name_ar' => 'إعداد متجر Shopify', 'base_price' => 2600, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'stores', 'unit_label_ar' => 'متاجر', 'sort_order' => 1]);
        $upsertOption($ecommerce, ['name_en' => 'WooCommerce Store Setup', 'name_ar' => 'إعداد متجر WooCommerce', 'base_price' => 2200, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'stores', 'unit_label_ar' => 'متاجر', 'sort_order' => 2]);
        $upsertOption($ecommerce, ['name_en' => 'Products Upload & Catalog Build', 'name_ar' => 'رفع المنتجات وبناء الكتالوج', 'base_price' => 700, 'urgent_multiplier' => 1.15, 'min_quantity' => 1, 'max_quantity' => 20, 'unit_label_en' => 'batches', 'unit_label_ar' => 'دفعات', 'sort_order' => 3]);
        $upsertOption($ecommerce, ['name_en' => 'Payment & Shipping Integration', 'name_ar' => 'ربط الدفع والشحن', 'base_price' => 1100, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'integrations', 'unit_label_ar' => 'عمليات ربط', 'sort_order' => 4]);

        // Branding & Content options
        $upsertOption($branding, ['name_en' => 'Brand Identity Kit', 'name_ar' => 'باقة الهوية البصرية', 'base_price' => 2500, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 3, 'unit_label_en' => 'kits', 'unit_label_ar' => 'باقات', 'sort_order' => 1]);
        $upsertOption($branding, ['name_en' => 'Logo Design (3 concepts)', 'name_ar' => 'تصميم شعار (3 نماذج)', 'base_price' => 900, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 5, 'unit_label_en' => 'logos', 'unit_label_ar' => 'شعارات', 'sort_order' => 2]);
        $upsertOption($branding, ['name_en' => 'Monthly Content Production', 'name_ar' => 'إنتاج محتوى شهري', 'base_price' => 1400, 'urgent_multiplier' => 1.15, 'min_quantity' => 1, 'max_quantity' => 12, 'unit_label_en' => 'months', 'unit_label_ar' => 'شهور', 'sort_order' => 3]);

        // ERP / CRM Automation options
        $upsertOption($automation, ['name_en' => 'CRM Setup & Pipeline Design', 'name_ar' => 'إعداد CRM وتصميم المبيعات', 'base_price' => 2100, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'systems', 'unit_label_ar' => 'أنظمة', 'sort_order' => 1]);
        $upsertOption($automation, ['name_en' => 'ERP Workflow Automation', 'name_ar' => 'أتمتة تدفقات ERP', 'base_price' => 3200, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'modules', 'unit_label_ar' => 'وحدات', 'sort_order' => 2]);
        $upsertOption($automation, ['name_en' => 'WhatsApp/API Integration', 'name_ar' => 'تكامل واتساب وواجهات API', 'base_price' => 1700, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 8, 'unit_label_en' => 'integrations', 'unit_label_ar' => 'عمليات ربط', 'sort_order' => 3]);

        // UI/UX options
        $upsertOption($uiux, ['name_en' => 'UX Audit + Improvements Plan', 'name_ar' => 'تدقيق تجربة المستخدم وخطة تحسين', 'base_price' => 1200, 'urgent_multiplier' => 1.15, 'min_quantity' => 1, 'max_quantity' => 6, 'unit_label_en' => 'audits', 'unit_label_ar' => 'تقارير', 'sort_order' => 1]);
        $upsertOption($uiux, ['name_en' => 'Wireframes for New Product', 'name_ar' => 'تصميم Wireframes لمنتج جديد', 'base_price' => 1800, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'projects', 'unit_label_ar' => 'مشاريع', 'sort_order' => 2]);
        $upsertOption($uiux, ['name_en' => 'High-Fidelity UI Kit', 'name_ar' => 'تصميم واجهات عالية الدقة', 'base_price' => 2400, 'urgent_multiplier' => 1.20, 'min_quantity' => 1, 'max_quantity' => 4, 'unit_label_en' => 'kits', 'unit_label_ar' => 'باقات', 'sort_order' => 3]);

        // --- EXTEND CONSTRUCTION ---
        $gypsum = $upsertSubService([
            'service_id' => $construction->id,
            'name_en' => 'Gypsum & Ceilings',
            'name_ar' => 'أسقف وجبس بورد',
            'slug' => 'gypsum-ceilings',
            'icon' => 'layers',
            'sort_order' => 3,
        ]);
        $upsertOption($gypsum, ['name_en' => 'Gypsum Board Install', 'name_ar' => 'تركيب جبس بورد', 'base_price' => 60, 'urgent_multiplier' => 1.25, 'min_quantity' => 10, 'max_quantity' => 200, 'unit_label_en' => 'sqm', 'unit_label_ar' => 'متر مكعب']);

        $waterproofing = $upsertSubService([
            'service_id' => $construction->id,
            'name_en' => 'Water & Heat Proofing',
            'name_ar' => 'العزل المائي والحراري',
            'slug' => 'waterproofing',
            'icon' => 'umbrella',
            'sort_order' => 4,
        ]);
        $upsertOption($waterproofing, ['name_en' => 'Roof Waterproofing', 'name_ar' => 'عزل أسطح مائي', 'base_price' => 45, 'urgent_multiplier' => 1.25, 'min_quantity' => 50, 'max_quantity' => 500, 'unit_label_en' => 'sqm', 'unit_label_ar' => 'متر مكعب']);
    }
}
