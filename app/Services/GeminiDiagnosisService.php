<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceOption;
use App\Models\ServicePart;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GeminiDiagnosisService
{
    // ── Category to service slug fallbacks (handles legacy slug variants) ─────
    private const CATEGORY_SERVICE_SLUGS = [
        'maintenance'  => ['home-maintenance'],
        'contracting'  => ['construction-contracting'],
        'programming'  => ['software-dev-marketing', 'software-development'],
        'cameras'      => ['camera-system-and-security', 'security-systems'],
    ];

    private const GEMINI_ENDPOINT =
        'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';

    /**
     * Analyzes an image via Google Gemini API and maps it to a database service.
     *
     * @return array { success: bool, error?: string, diagnosis?: array }
     */
    public function analyze(string $imageBase64, string $mimeType, ?string $userContext = null): array
    {
        $apiKey = config('services.gemini.api_key');
        if (empty($apiKey)) {
            Log::error('[Gemini] GEMINI_API_KEY is not configured.');
            return ['success' => false, 'error' => 'AI service is not configured. Please contact support.'];
        }

        $userContext = $userContext ?? '';
        $prompt = $this->buildDiagnosisPrompt($userContext);

        $geminiPayload = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data'      => $imageBase64,
                            ],
                        ],
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
                'response_schema'    => $this->getResponseSchema(),
                'temperature'        => 0.2,
                'max_output_tokens'  => 4096,
            ],
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT',       'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH',      'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT','threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT','threshold' => 'BLOCK_ONLY_HIGH'],
            ],
        ];

        try {
            $response = Http::timeout(45)
                ->withQueryParameters(['key' => $apiKey])
                ->post(self::GEMINI_ENDPOINT, $geminiPayload);

            if (!$response->successful()) {
                Log::error('[Gemini] API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return ['success' => false, 'error' => 'AI analysis failed. Please try again.'];
            }

            $geminiData = $response->json();
            $rawJson = $geminiData['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$rawJson) {
                Log::warning('[Gemini] Empty response from API', ['body' => $geminiData]);
                return ['success' => false, 'error' => 'Could not analyze this image. Please try a clearer photo.'];
            }

            $diagnosis = json_decode($rawJson, true);

            if (!$diagnosis || !isset($diagnosis['category'])) {
                Log::warning('[Gemini] Invalid JSON structure', ['raw' => $rawJson]);
                return ['success' => false, 'error' => 'Analysis returned an unexpected format. Please try again.'];
            }

             // ── Safety Override Logic (MUST run BEFORE catalog sync) ──────────
            $desc = strtolower($diagnosis['pre_filled_description']);
            $title = strtolower($diagnosis['issue_title_en'] ?? '');
            $haystack = $desc . ' ' . $title;

            $securityKeywords = [
                // English
                'camera', 'cctv', 'security', 'ezviz', 'hikvision', 'dvr', 'nvr',
                'surveillance', 'intercom', 'smart lock', 'door sensor', 'access control',
                'doorbell', 'ip camera', 'ptz', 'baby monitor', 'motion sensor',
                // Arabic
                'كاميرا', 'كاميرات', 'مراقبة', 'انتركم', 'قفل ذكي', 'جرس', 'حضور وانصراف',
            ];
            $isSecurity = collect($securityKeywords)->contains(fn($kw) => str_contains($haystack, $kw));

            if ($isSecurity && $diagnosis['category'] !== 'cameras') {
                Log::info('[Gemini] Safety override: re-categorized to cameras', ['original' => $diagnosis['category'], 'haystack' => substr($haystack, 0, 200)]);
                $diagnosis['category'] = 'cameras';
            }

            // ── Real Catalog Sync (Camera Store) ──────────────────────────────
            // Now runs after override, so correctly-categorized cameras always get synced
            if ($diagnosis['category'] === 'cameras') {
                $this->ensureCameraSuggestionQuality($diagnosis);
                $this->syncCameraPrices($diagnosis);
            }

            // Enrich with real service data using ordered slug fallbacks.
            $service = $this->resolveServiceForCategory($diagnosis['category']);
            $categorySlug = $service?->slug ?? 'home-maintenance';

            $diagnosis['category_slug']    = $categorySlug;
            $diagnosis['service_id']       = $service?->id;
            $diagnosis['service_name_ar']  = $service?->name_ar;
            $diagnosis['service_name_en']  = $service?->name_en;
            $diagnosis['service_color']    = $service?->color;

            // ── If no option was set by syncCameraPrices, attempt a DB-based fallback ──
            if (empty($diagnosis['service_option_id']) && $service) {
                $diagnosis['service_option_id'] = $this->mapToServiceOption($diagnosis, $service->id);
            }

            return ['success' => true, 'diagnosis' => $diagnosis];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('[Gemini] Connection timeout', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Connection to AI service timed out. Please check your internet and try again.'];
        } catch (\Exception $e) {
            Log::error('[Gemini] Unexpected error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'An unexpected error occurred. Please try again.'];
        }
    }

    /**
     * Overwrites AI-generated prices with real values from Al-Kifah Store database.
     */
    private function syncCameraPrices(array &$diagnosis): void
    {
        $partsTotal = 0;
        $matchedAny = false;
        $firstMatchedOptionId = null;

        if (empty($diagnosis['suggested_parts'])) {
            $this->ensureCameraSuggestionQuality($diagnosis);
        }

        // Pre-load all active camera ServiceOptions for fast matching
        $cameraService = Service::active()
            ->whereIn('slug', ['camera-system-and-security', 'security-systems'])
            ->first();

        $allCameraOptions = $cameraService
            ? ServiceOption::whereHas('subService', fn($q) => $q->where('service_id', $cameraService->id))
                ->where('is_active', true)->get()
            : collect();

        foreach ($diagnosis['suggested_parts'] as &$part) {
            $pNameEn = $part['part_name_en'] ?? '';
            $pNameAr = $part['part_name_ar'] ?? '';
            $pQty    = (int) ($part['quantity'] ?? 1);

            $cleanNameEn = trim(preg_replace('/(\d+)x|(\d+)\s*x|bundle:?\s*/i', '', $pNameEn));
            $cleanNameAr = trim(preg_replace('/عدد\s*(\d+)|\d+\s*x|طقم\s*/u', '', $pNameAr));

            // 1. Match against our ServiceOption store catalog (primary)
            $matchedOption = $allCameraOptions->first(function ($opt) use ($cleanNameEn, $cleanNameAr) {
                $optEn = Str::lower($opt->name_en);
                $optAr = $opt->name_ar;
                return ($cleanNameEn !== '' && str_contains($optEn, Str::lower($cleanNameEn)))
                    || ($cleanNameAr !== '' && str_contains($optAr, $cleanNameAr));
            });

            if ($matchedOption) {
                $part['estimated_unit_price_sar'] = (float) $matchedOption->base_price;
                $part['total_price_sar']          = (float) ($matchedOption->base_price * $pQty);
                $part['store_sku']     = 'OPT-' . $matchedOption->id;
                $part['part_name_en']  = $matchedOption->name_en;
                $part['part_name_ar']  = $matchedOption->name_ar;
                $partsTotal += ($matchedOption->base_price * $pQty);
                $matchedAny = true;
                $firstMatchedOptionId ??= $matchedOption->id;
                continue;
            }

            // 2. Fallback: legacy ServicePart table
            $match = ServicePart::query()->where('is_active', true)
                ->where(function ($q) use ($cleanNameEn, $cleanNameAr) {
                    $q->where('name_en', 'LIKE', "%{$cleanNameEn}%")
                      ->orWhere('name_ar', 'LIKE', "%{$cleanNameAr}%");
                })->first();

            if ($match) {
                $part['estimated_unit_price_sar'] = (float) $match->price_sar;
                $part['total_price_sar']          = (float) ($match->price_sar * $pQty);
                $part['store_sku']    = $match->sku;
                $part['part_name_en'] = $match->name_en;
                $part['part_name_ar'] = $match->name_ar;
                $partsTotal += ($match->price_sar * $pQty);
                $matchedAny = true;
            }
        }
        unset($part);

        if ($matchedAny) {
            $diagnosis['budget_estimate']['min_sar'] = $partsTotal;
            $diagnosis['budget_estimate']['max_sar'] = round($partsTotal * 1.1);
            $diagnosis['budget_estimate']['note_ar'] = 'الأسعار مطابقة لمتجر الكفاح للأنظمة الأمنية';
        } else {
            $diagnosis['budget_estimate']['min_sar'] = max(999,  (int) ($diagnosis['budget_estimate']['min_sar'] ?? 999));
            $diagnosis['budget_estimate']['max_sar'] = max(1500, (int) ($diagnosis['budget_estimate']['max_sar'] ?? 1500));
        }

        // Route to the best-matched ServiceOption
        if ($firstMatchedOptionId) {
            $diagnosis['service_option_id'] = $firstMatchedOptionId;
        } elseif ($cameraService) {
            $diagnosis['service_option_id'] = $this->mapToServiceOption($diagnosis, $cameraService->id);
        }
    }

    /**
     * Guards against weak camera suggestions by forcing a practical package row.
     */
    private function ensureCameraSuggestionQuality(array &$diagnosis): void
    {
        $msg = Str::lower(($diagnosis['pre_filled_description'] ?? '') . ' ' . ($diagnosis['issue_title_en'] ?? ''));
        $qty = max(1, (int) ($diagnosis['suggested_quantity'] ?? 1));

        // Intercom
        if (str_contains($msg, 'intercom') || str_contains($msg, 'انتركم')) {
            $diagnosis['suggested_parts'] = [[
                'part_name_ar' => 'تركيب وبرمجة انتركم فيديو',
                'part_name_en' => 'Video Intercom Installation & Programming',
                'quantity' => 1, 'store_sku' => null, 'estimated_unit_price_sar' => 450,
            ]];
            return;
        }

        // Attendance / smart lock
        if (str_contains($msg, 'attendance') || str_contains($msg, 'حضور') ||
            str_contains($msg, 'fingerprint') || str_contains($msg, 'بصمة')) {
            $diagnosis['suggested_parts'] = [[
                'part_name_ar' => 'جهاز بصمة الحضور F18',
                'part_name_en' => 'Fingerprint Attendance F18',
                'quantity' => max(1, $qty), 'store_sku' => null, 'estimated_unit_price_sar' => 850,
            ]];
            return;
        }

        // Dashcam
        if (str_contains($msg, 'dashcam') || str_contains($msg, 'داش كام') ||
            str_contains($msg, 'car camera') || str_contains($msg, 'كاميرا سيارة')) {
            $diagnosis['suggested_parts'] = [[
                'part_name_ar' => 'داش كام Hikvision AE-DC4328-K5',
                'part_name_en' => 'Hikvision Dashcam AE-DC4328-K5',
                'quantity' => 1, 'store_sku' => null, 'estimated_unit_price_sar' => 490,
            ]];
            return;
        }

        // Camera packages — sized by detected quantity
        $pkgs = [
            8 => ['ar' => 'باقة 8 كاميرا 8MP + DVR',                          'en' => '8-Camera 8MP Bundle + DVR',                              'price' => 1899],
            6 => ['ar' => 'باقة 6 كاميرا 5MP + DVR (شاملة التركيب)',          'en' => '6-Camera 5MP Bundle + DVR (Includes Installation)',       'price' => 2250],
            5 => ['ar' => 'باقة 5 كاميرا 5MP + DVR (شاملة التركيب)',          'en' => '5-Camera 5MP Bundle + DVR (Includes Installation)',       'price' => 1950],
            4 => ['ar' => 'باقة 4 كاميرا 5MP + DVR (شاملة التركيب)',          'en' => '4-Camera 5MP Bundle + DVR (Includes Installation)',       'price' => 1500],
            3 => ['ar' => 'باقة 3 كاميرا 5MP + DVR (شاملة التركيب)',          'en' => '3-Camera 5MP Bundle + DVR (Includes Installation)',       'price' => 1400],
            2 => ['ar' => 'باقة 2 كاميرا 8MP + DVR',                          'en' => '2-Camera 8MP Bundle + DVR',                              'price' => 999],
        ];

        $chosen = null;
        foreach ($pkgs as $threshold => $pkg) {
            if ($qty >= $threshold) { $chosen = $pkg; break; }
        }
        $chosen = $chosen ?? $pkgs[2];

        $diagnosis['suggested_parts'] = [[
            'part_name_ar' => $chosen['ar'],
            'part_name_en' => $chosen['en'],
            'quantity' => 1, 'store_sku' => null,
            'estimated_unit_price_sar' => $chosen['price'],
        ]];
    }

    /**
     * Finds the best-matching ServiceOption ID using real DB queries — no hardcoded IDs.
     * Falls back to the first active option for the service if no keyword match found.
     */
    private function mapToServiceOption(array $diagnosis, int $serviceId): ?int
    {
        $cat = $diagnosis['category'];
        $msg = Str::lower(($diagnosis['pre_filled_description'] ?? '') . ' ' . ($diagnosis['issue_title_en'] ?? ''));

        // Build keyword hints to prefer specific sub-service names
        $keywords = match($cat) {
            'cameras'      => $this->cameraKeywords($msg),
            'maintenance'  => $this->maintenanceKeywords($msg),
            'contracting'  => $this->contractingKeywords($msg),
            'programming'  => $this->programmingKeywords($msg),
            default        => [],
        };

        $options = ServiceOption::with('subService')
            ->whereHas('subService', fn($q) => $q->where('service_id', $serviceId))
            ->where('is_active', true)
            ->get();

        if ($options->isEmpty()) {
            return null;
        }

        $keywords = array_values(array_unique(array_filter($keywords)));
        $scored = $options->map(function (ServiceOption $option) use ($keywords, $msg) {
            $optionNameEn = Str::lower((string) $option->name_en);
            $optionNameAr = Str::lower((string) $option->name_ar);
            $subNameEn = Str::lower((string) optional($option->subService)->name_en);
            $subNameAr = Str::lower((string) optional($option->subService)->name_ar);
            $haystack = trim("{$optionNameEn} {$optionNameAr} {$subNameEn} {$subNameAr}");

            $score = 0;
            foreach ($keywords as $kw) {
                $needle = Str::lower((string) $kw);
                if ($needle !== '' && str_contains($haystack, $needle)) {
                    $score += 2;
                }
            }

            if ($optionNameEn !== '' && str_contains($msg, $optionNameEn)) {
                $score += 1;
            }

            return ['option' => $option, 'score' => $score];
        })->sortByDesc('score')->values();

        $best = $scored->first();
        if (($best['score'] ?? 0) > 0) {
            Log::info('[Gemini] mapToServiceOption: matched via scoring', [
                'option_id' => $best['option']->id,
                'option' => $best['option']->name_en,
                'score' => $best['score'],
                'category' => $cat,
            ]);
            return $best['option']->id;
        }

        // Fallback: first active option for this service
        $fallback = ServiceOption::with('subService')
            ->whereHas('subService', fn($q) => $q->where('service_id', $serviceId))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        if ($fallback) {
            Log::info('[Gemini] mapToServiceOption: fallback to first option', ['option_id' => $fallback->id, 'option' => $fallback->name_en]);
        }

        return $fallback?->id;
    }

    private function cameraKeywords(string $msg): array
    {
        $kws = ['Camera', 'Bundle', 'Package', 'Installation'];

        if (str_contains($msg, 'wifi') || str_contains($msg, 'wireless') || str_contains($msg, 'واي فاي')) {
            array_unshift($kws, 'WiFi', 'Wireless');
        }
        if (str_contains($msg, 'intercom') || str_contains($msg, 'انتركم')) {
            array_unshift($kws, 'Intercom', 'Video Intercom');
        }
        if (str_contains($msg, 'attendance') || str_contains($msg, 'fingerprint') ||
            str_contains($msg, 'حضور') || str_contains($msg, 'بصمة')) {
            array_unshift($kws, 'Fingerprint', 'Attendance', 'F18', 'ZKTeco');
        }
        if (str_contains($msg, 'lock') || str_contains($msg, 'قفل') || str_contains($msg, 'access')) {
            array_unshift($kws, 'Lock', 'Access Control');
        }
        if (str_contains($msg, 'dashcam') || str_contains($msg, 'داش كام') || str_contains($msg, 'car')) {
            array_unshift($kws, 'Dashcam', 'Hikvision Dashcam');
        }
        if (str_contains($msg, 'gps') || str_contains($msg, 'tracking') || str_contains($msg, 'تتبع')) {
            array_unshift($kws, 'GPS', 'Tracking');
        }
        if (str_contains($msg, 'inspection') || str_contains($msg, 'report') || str_contains($msg, 'فحص')) {
            array_unshift($kws, 'Inspection', 'Report');
        }

        return $kws;
    }

    private function maintenanceKeywords(string $msg): array
    {
        if (str_contains($msg, 'plumb') || str_contains($msg, 'water') || str_contains($msg, 'leak') || str_contains($msg, 'مياه') || str_contains($msg, 'سباك')) {
            return ['Plumbing', 'سباكة'];
        }
        if (str_contains($msg, 'ac') || str_contains($msg, 'air') || str_contains($msg, 'cool') || str_contains($msg, 'تكييف')) {
            return ['Air Conditioning', 'AC', 'تكييف'];
        }
        if (str_contains($msg, 'elec') || str_contains($msg, 'light') || str_contains($msg, 'power') || str_contains($msg, 'كهرباء')) {
            return ['Electrical', 'كهرباء'];
        }
        if (str_contains($msg, 'paint') || str_contains($msg, 'دهان')) {
            return ['Painting', 'دهان'];
        }
        if (str_contains($msg, 'wood') || str_contains($msg, 'carpenter') || str_contains($msg, 'نجار')) {
            return ['Carpentry', 'Woodwork', 'نجارة'];
        }
        return ['Plumbing'];
    }

    private function contractingKeywords(string $msg): array
    {
        if (str_contains($msg, 'waterproof') || str_contains($msg, 'عزل مائي')) {
            return ['Waterproof', 'عزل'];
        }
        if (str_contains($msg, 'gypsum') || str_contains($msg, 'جبس')) {
            return ['Gypsum', 'جبس'];
        }
        if (str_contains($msg, 'floor') || str_contains($msg, 'tile') || str_contains($msg, 'بلاط')) {
            return ['Flooring', 'Tile', 'بلاط'];
        }
        return ['Renovation', 'Construction'];
    }

    private function programmingKeywords(string $msg): array
    {
        if (
            str_contains($msg, 'mobile') || str_contains($msg, 'app') || str_contains($msg, 'android') ||
            str_contains($msg, 'ios') || str_contains($msg, 'تطبيق')
        ) {
            return ['Mobile', 'App', 'Android', 'iOS', 'Cross Platform', 'تطبيقات'];
        }
        if (
            str_contains($msg, 'seo') || str_contains($msg, 'market') || str_contains($msg, 'ads') ||
            str_contains($msg, 'campaign') || str_contains($msg, 'social') || str_contains($msg, 'google ads') ||
            str_contains($msg, 'meta') || str_contains($msg, 'تسويق') || str_contains($msg, 'إعلانات')
        ) {
            return ['SEO', 'Marketing', 'Ads', 'Social Media', 'Campaign', 'Branding', 'تسويق'];
        }
        if (
            str_contains($msg, 'ecommerce') || str_contains($msg, 'shopify') || str_contains($msg, 'woocommerce') ||
            str_contains($msg, 'متجر') || str_contains($msg, 'متجر الكتروني')
        ) {
            return ['E-Commerce', 'Shopify', 'WooCommerce', 'Store', 'متجر'];
        }
        if (str_contains($msg, 'pos') || str_contains($msg, 'نقاط بيع') || str_contains($msg, 'cashier')) {
            return ['POS', 'Point of Sale', 'نقاط البيع'];
        }
        if (
            str_contains($msg, 'crm') || str_contains($msg, 'erp') || str_contains($msg, 'automation') ||
            str_contains($msg, 'workflow') || str_contains($msg, 'أتمتة')
        ) {
            return ['ERP', 'CRM', 'Automation', 'Workflow', 'أتمتة'];
        }
        if (str_contains($msg, 'ui') || str_contains($msg, 'ux') || str_contains($msg, 'design') || str_contains($msg, 'واجهة')) {
            return ['UI/UX', 'Design', 'Wireframe', 'واجهة'];
        }
        return ['Web', 'Website'];
    }

    /**
     * Resolve a service by category using ordered slug fallbacks.
     */
    private function resolveServiceForCategory(string $category): ?Service
    {
        $slugs = self::CATEGORY_SERVICE_SLUGS[$category] ?? self::CATEGORY_SERVICE_SLUGS['maintenance'];
        $services = Service::active()->whereIn('slug', $slugs)->get();

        if ($services->isEmpty()) {
            return Service::active()->where('slug', 'home-maintenance')->first();
        }

        return collect($slugs)
            ->map(fn(string $slug) => $services->firstWhere('slug', $slug))
            ->first(fn($service) => $service !== null);
    }

    private function buildDiagnosisPrompt(string $userContext): string
    {
        $contextLine = $userContext
            ? "Additional context from the user: \"{$userContext}\""
            : 'No additional context provided.';

        return <<<PROMPT
You are an expert field technician and estimator for Al-Kifah International Company in Saudi Arabia.
We provide exactly 4 service categories. Read the definitions carefully:

- "maintenance"   → REPAIRS to EXISTING systems in a home or building.
                    Examples: fixing a broken AC, repairing a water leak, electrical fault, painting over old walls, replacing a worn-out door.
                    KEY: The item already EXISTS and needs REPAIR or REPLACEMENT of a component.
                    
- "contracting"   → NEW CONSTRUCTION, structural changes, or large-scale renovation projects.
                    Examples: building a new room, installing gypsum ceilings from scratch, waterproofing a new terrace, floor tiling a new space, major structural renovation.
                    KEY: This is NEW BUILD work or major structural changes, NOT simple repairs to an existing surface.

- "programming"   → IT, POS systems, software development, networking infrastructure (cables, routers as a tech service — not hardware installation).
                    Examples: building a website, setting up a POS system, configuring a server.

- "cameras"       → ANY security or surveillance hardware and its installation.
                    Examples: CCTV cameras, IP cameras, DVR/NVR recorders, intercoms, smart locks, door sensors, access control, baby monitors, doorbell cameras.
                    CRITICAL: Any request involving a CAMERA, APPLIANCE SENSOR, or SECURITY DEVICE belongs HERE — NEVER in contracting or maintenance.

Analyze the provided image and return a diagnosis.
{$contextLine}

## CRITICAL CLASSIFICATION RULES (apply in order):
1. If you see ANY camera, CCTV, DVR, NVR, intercom, smart lock, or security device → category MUST be "cameras", even if mounted on a building.
2. If the request is about building, laying tile/flooring, putting up gypsum/drywall from scratch, or waterproofing → category MUST be "contracting", do NOT use maintenance.
3. If you see a BROKEN or FAULTY existing component (pipe, AC unit, switch, etc.) → category is "maintenance".
4. If you see NEW CONSTRUCTION, a bare structural surface for a brand-new build, or major renovation in progress → category is "contracting".
4. If you see software, POS, or IT equipment → category is "programming".
5. When in doubt between maintenance and contracting: if the problem is a FAULT in something that EXISTS → maintenance. If it is BUILDING something NEW → contracting.

## FORMAT RULES:
- Return ONLY valid JSON. No markdown, no code blocks, no extra text.
- "category" MUST be one of: maintenance, contracting, programming, cameras
- "urgency_suggestion" MUST be one of: urgent, scheduled
- All Arabic text must be professional, clear Saudi dialect.
- If the image is unclear or unrelated to our services, set "is_unclear" to true.
- Cost estimates are rough SAR ranges based on Riyadh market rates.
- "suggested_parts" should be specific but not brand-specific for non-camera categories — max 4 items.
- "suggested_quantity" is the total number of primary units detected or needed.

## FOR "cameras" CATEGORY — USE OUR EXACT STORE CATALOG:
Match the user's need to ONE item from this catalog. Use the exact Arabic & English names listed.

**Camera Packages (recommend based on number of cameras needed):**
- 2-Camera 8MP Bundle + DVR / باقة 2 كاميرا 8MP + DVR → 999 SAR
- 4-Camera 8MP Bundle + DVR / باقة 4 كاميرا 8MP + DVR → 1,300 SAR
- 6-Camera 8MP Bundle + DVR / باقة 6 كاميرا 8MP + DVR → 1,799 SAR
- 8-Camera 8MP Bundle + DVR / باقة 8 كاميرا 8MP + DVR → 1,899 SAR
- 3-Camera 5MP Bundle + DVR (Includes Installation) / باقة 3 كاميرا 5MP + DVR (شاملة التركيب) → 1,400 SAR
- 4-Camera 5MP Bundle + DVR (Includes Installation) / باقة 4 كاميرا 5MP + DVR (شاملة التركيب) → 1,500 SAR
- 5-Camera 5MP Bundle + DVR (Includes Installation) / باقة 5 كاميرا 5MP + DVR (شاملة التركيب) → 1,950 SAR
- 6-Camera 5MP Bundle + DVR (Includes Installation) / باقة 6 كاميرا 5MP + DVR (شاملة التركيب) → 2,250 SAR

**Installation Services:**
- Camera Installation (Includes Cable & Wiring) / تركيب كاميرا (شامل كابل وتوصيل) → 400 SAR/camera
- Camera Installation (Labor Only) / تركيب كاميرا (عمالة فقط) → 180 SAR/camera
- WiFi Camera Installation & Programming / تركيب وبرمجة كاميرا واي فاي → 200 SAR/camera
- Video Intercom Installation & Programming / تركيب وبرمجة انتركم فيديو → 450 SAR
- Camera Inspection & Technical Report / فحص الكاميرات وتقرير فني → 500 SAR

**Attendance & Smart Locks (ZKTeco):**
- Fingerprint Attendance F18 / جهاز بصمة الحضور F18 → 850 SAR
- Face Recognition UFACE800-ID / جهاز التعرف على الوجه UFACE800-ID → 1,450 SAR
- Access Control Lock TF1700 / قفل تحكم بالوصول TF1700 → 1,170 SAR

**Dashcams & GPS:**
- GPS Tracking Device + 6 Months Subscription / جهاز GPS للتتبع (+ 6 أشهر اشتراك) → 670 SAR
- Hikvision Dashcam AE-DC4328-K5 / داش كام Hikvision AE-DC4328-K5 → 490 SAR

RULE: For a house/home camera request, count cameras visible or mentioned → pick the matching package bundle.
RULE: Do NOT put multiple parts rows for a camera bundle — it is ONE item (bundle includes DVR).
PROMPT;
    }

    private function getResponseSchema(): array
    {
        return [
            'type'       => 'OBJECT',
            'properties' => [
                'category' => [
                    'type' => 'STRING',
                    'enum' => ['maintenance', 'contracting', 'programming', 'cameras'],
                ],
                'is_unclear' => ['type' => 'BOOLEAN'],
                'confidence_score' => ['type' => 'NUMBER'],
                'issue_title_ar'   => ['type' => 'STRING'],
                'issue_title_en'   => ['type' => 'STRING'],
                'dialogue_message' => ['type' => 'STRING'],
                'urgency_suggestion' => [
                    'type' => 'STRING',
                    'enum' => ['urgent', 'scheduled'],
                ],
                'suggested_parts' => [
                    'type'  => 'ARRAY',
                    'items' => [
                        'type'       => 'OBJECT',
                        'properties' => [
                            'part_name_ar'           => ['type' => 'STRING'],
                            'part_name_en'           => ['type' => 'STRING'],
                            'quantity'               => ['type' => 'NUMBER'],
                            'store_sku'              => ['type' => 'STRING', 'nullable' => true],
                            'estimated_unit_price_sar' => ['type' => 'NUMBER', 'nullable' => true],
                        ],
                    ],
                ],
                'budget_estimate' => [
                    'type'       => 'OBJECT',
                    'properties' => [
                        'min_sar'           => ['type' => 'NUMBER'],
                        'max_sar'           => ['type' => 'NUMBER'],
                        'currency'          => ['type' => 'STRING'],
                        'pricing_engine_id' => ['type' => 'STRING', 'nullable' => true],
                        'note_ar'           => ['type' => 'STRING'],
                    ],
                ],
                'pre_filled_description' => ['type' => 'STRING'],
                'suggested_quantity'     => ['type' => 'NUMBER'],
            ],
            'required' => [
                'category',
                'is_unclear',
                'issue_title_ar',
                'dialogue_message',
                'urgency_suggestion',
                'budget_estimate',
                'pre_filled_description',
            ],
        ];
    }
}
