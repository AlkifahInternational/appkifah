<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;
use App\Models\ServicePart;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class ServiceGrid extends Component
{
    // ── Construction Working Expense (base site visit/mobilisation fee) ──
    public const CONSTRUCTION_WORKING_EXPENSE_SAR = 500;

    private const DIRECT_GRID_SLUGS = [
        'camera-system-and-security', 
        'security-systems',
        'software-dev-marketing',
        'construction-contracting',
        'home-maintenance'
    ];

    // Navigation state
    public string $searchQuery = "";
    public ?int $activeServiceId = null;
    public ?int $activeSubServiceId = null;
    public bool $showModal = false;
    public bool $directMode = false; // Bypasses sub-service step for compatible services

    // Modal state
    public ?int $selectedOptionId = null;
    public int $quantity = 1;
    public array $cart = []; // [option_id => ['quantity' => QTY, 'option' => OBJECT]]
    public string $urgency = 'scheduled';
    public string $address = '';
    public $latitude = null;
    public $longitude = null;
    public string $clientName = '';
    public bool $needsRegistration = false;
    public int $constructionVisits = 1;

    // Computed data
    public float $calculatedPrice = 0;
    public float $workingExpense  = 0; // Working expense shown separately in UI
    public float $partsTotal      = 0; // Total cost of AI suggested parts

    // Checkout state
    public string $phone = '';
    public string $otp = '';
    public bool $showOtpInput = false;
    
    // UI Modals State
    public bool $showCheckoutModal = false;
    public bool $showPaymentModal = false;
    public string $paymentMethod = 'mada'; // mada, visa, apple, cash

    // ── AI Diagnosis auto-fill ─────────────────────────────────────────────
    public string  $aiProblemDescription = ''; // Pre-filled from Gemini result
    public array   $aiSuggestedParts     = []; // Original parts list from AI
    public ?array  $aiBudgetEstimate     = null;// Min/Max from AI/Catalog
    public ?float  $aiBasePrice          = null; // The original AI total
    public array   $scaledAiParts        = []; // Dynamically updated version of parts
    public int     $aiBaseQuantity       = 1;    // The quantity AI detected
    public bool    $prefillFromAi        = false;

    public function toggleOption(int $optionId): void
    {
        if (isset($this->cart[$optionId])) {
            unset($this->cart[$optionId]);
        } else {
            $option = ServiceOption::find($optionId);
            if ($option) {
                $this->cart[$optionId] = [
                    'quantity' => $option->min_quantity ?: 1,
                    'name_en' => $option->name_en,
                    'name_ar' => $option->name_ar,
                    'base_price' => $option->base_price,
                ];
            }
        }
        $this->calculatePrice();
    }

    public function updateCartQuantity(int $optionId, int $delta): void
    {
        if (!isset($this->cart[$optionId])) return;

        $option = ServiceOption::find($optionId);
        $newQty = $this->cart[$optionId]['quantity'] + $delta;

        if ($newQty < 1) {
            unset($this->cart[$optionId]);
        } else {
            $max = $option->max_quantity ?: 100;
            if ($newQty <= $max) {
                $this->cart[$optionId]['quantity'] = $newQty;
            }
        }
        $this->calculatePrice();
    }

    public function selectService(int $serviceId): void
    {
        $this->resetCheckoutState();
        $this->activeServiceId = $serviceId;
        $this->activeSubServiceId = null;
        $this->cart = [];
        
        $service = Service::find($serviceId);
        $this->directMode = $service ? in_array($service->slug, self::DIRECT_GRID_SLUGS) : false;
    }

    /**
     * Handles the aiDiagnosisConfirmed event from the VisualDiagnosis component.
     * Navigates to the correct service and stores the pre-filled description.
     *
     * @param  array $payload  ['service_id', 'slug', 'description', 'urgency']
     */
    #[On('aiDiagnosisConfirmed')]
    public function handleAiDiagnosis(array $payload): void
    {
        $serviceId   = $payload['service_id']   ?? null;
        $optionId    = $payload['service_option_id'] ?? null;
        $slug        = $payload['slug']         ?? null;
        $description = $payload['description']  ?? '';
        $urgency     = $payload['urgency']       ?? 'scheduled';
        $budget      = $payload['budget_estimate'] ?? null;
        $parts       = $payload['suggested_parts'] ?? [];
        $quantity    = $payload['quantity']      ?? 1;

        // Resolve service: try by ID first, fall back to slug
        $service = $serviceId
            ? Service::active()->find($serviceId)
            : Service::active()->where('slug', $slug)->first();

        if (!$service) {
            return;
        }

        // Store AI data for the booking modal
        $this->aiProblemDescription = $description;
        $this->aiSuggestedParts     = $parts;
        $this->aiBudgetEstimate     = $budget;
        $this->aiBasePrice          = (float) ($budget['min_sar'] ?? 0);
        $this->aiBaseQuantity       = (int) ($quantity > 0 ? $quantity : 1);
        $this->quantity             = $this->aiBaseQuantity;
        $this->prefillFromAi        = true;
        $this->urgency              = $urgency;

        // Navigate to the service — this sets directMode automatically
        $this->activeServiceId    = $service->id;
        $this->directMode         = in_array($service->slug, self::DIRECT_GRID_SLUGS);
        $this->activeSubServiceId = null;

        Log::info('[ServiceGrid] received AI diagnosis payload', ['optionId' => $optionId, 'directMode' => $this->directMode]);

        if ($optionId) {
            $option = ServiceOption::with('subService')->find($optionId);
            if ($option) {
                if ($this->directMode) {
                    // Camera direct mode: pre-add the suggested item to the cart, show the grid
                    if (!isset($this->cart[$option->id])) {
                        $this->toggleOption($option->id);
                    }
                } else {
                    // Standard flow: navigate to sub-service and open modal
                    $this->activeSubServiceId = $option->sub_service_id;
                    $this->openModal($option->id);
                    if ($this->prefillFromAi && $this->aiBaseQuantity > 1) {
                        $this->quantity = $this->aiBaseQuantity;
                    }
                    $this->calculatePrice();
                }
            } else {
                Log::warning('[ServiceGrid] option not found for optionId: ' . $optionId);
            }
        }

        // Scroll to services section
    }

    public function selectSubService(int $subServiceId): void
    {
        $this->activeSubServiceId = $subServiceId;
    }

    public function openModal(int $optionId = null): void
    {
        // If we have items in cart and no specific optionId, we're doing a Multi-Checkout
        if (empty($this->cart) && $optionId) {
            // Single item fallback (classic behavior)
            $this->toggleOption($optionId);
        }

        $this->showModal = true;
        $this->resetCheckoutState();
        $this->calculatePrice();

        // Trigger map initialization when modal opens.
        $this->dispatch('modalOpened');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function goBack(): void
    {
        if ($this->activeSubServiceId) {
            $this->activeSubServiceId = null;
        } elseif ($this->activeServiceId) {
            $this->activeServiceId = null;
            $this->directMode = false;
        }
        $this->cart = [];
        $this->showModal = false;
        $this->showCheckoutModal = false;
        $this->showPaymentModal = false;
    }

    public function incrementQuantity(): void
    {
        $option = ServiceOption::find($this->selectedOptionId);
        if ($option && $this->quantity < $option->max_quantity) {
            $this->quantity++;
            $this->calculatePrice();
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        } else {
            if ($this->selectedOptionId) {
                unset($this->cart[$this->selectedOptionId]);
                $this->showModal = false;
            }
        }
        $this->calculatePrice();
    }

    public function updatedUrgency(): void
    {
        $this->calculatePrice();
    }

    public function updatedConstructionVisits(): void
    {
        if ($this->constructionVisits < 1) $this->constructionVisits = 1;
        if ($this->constructionVisits > 30) $this->constructionVisits = 30; // sensible max limit
        $this->calculatePrice();
    }

    public function calculatePrice(): void
    {
        // 1. Base Installation / Service Fees
        $subTotal = 0;
        $cartOptionIds = array_map('intval', array_keys($this->cart));
        $options = ServiceOption::whereIn('id', $cartOptionIds)->get()->keyBy('id');

        foreach ($this->cart as $optionId => $item) {
            $option = $options->get((int) $optionId);
            if (!$option) continue;

            // Apply urgency multiplier only to the service labor cost (base_price)
            $unitPrice = $this->urgency === 'urgent'
                ? $option->base_price * $option->urgent_multiplier
                : $option->base_price;
            
            $subTotal += ($unitPrice * $item['quantity']);
        }

        // 2. Material Costs (AI Suggested Parts)
        $partsTotal = 0;
        if ($this->prefillFromAi && !empty($this->aiSuggestedParts)) {
            $this->scaledAiParts = $this->computeScaledAiParts();
            foreach ($this->scaledAiParts as $part) {
                $partsTotal += $part['total_price_sar'] ?? 0;
            }
        }
        $this->partsTotal = $partsTotal;

        // 3. Site Visit / Working Expense (Construction only)
        $workingExpense = $this->isConstructionService() ? (self::CONSTRUCTION_WORKING_EXPENSE_SAR * $this->constructionVisits) : 0;
        $this->workingExpense = $workingExpense;

        // 4. Final Total Calculation
        $this->calculatedPrice = $subTotal + $partsTotal + $workingExpense;
    }

    /**
     * Dynamically scales camera quantities and upgrades DVRs if quantity exceeds current ports.
     */
    private function computeScaledAiParts(): array
    {
        // Determine camera context first so we can preserve bundled camera+DVR rows.
        $contextOptionId = !empty($this->cart) ? array_key_first($this->cart) : $this->selectedOptionId;
        $contextOption = ServiceOption::with('subService.service')->find($contextOptionId);
        $contextServiceSlug = $contextOption?->subService?->service?->slug;
        $isCamera = in_array($contextServiceSlug, ['camera-system-and-security', 'security-systems'], true);

        $flattenedAiParts = [];
        // First pass: Split any bundled items (e.g. "2x Camera + 1x DVR") into individual rows
        foreach ($this->aiSuggestedParts as $part) {
            $nameEn = $part['part_name_en'] ?? '';
            $nameAr = $part['part_name_ar'] ?? '';
            
            // If the name contains " + " or " و ", it's likely a bundle that needs splitting
            if (!$isCamera && (str_contains($nameEn, ' + ') || str_contains($nameAr, ' + ') || str_contains($nameAr, ' و '))) {
                $delimiters = [' + ', ' و '];
                $partsEn = [$nameEn];
                $partsAr = [$nameAr];
                
                // Simple split logic
                foreach ($delimiters as $d) {
                    if (str_contains($nameEn, $d)) $partsEn = explode($d, $nameEn);
                    if (str_contains($nameAr, $d)) $partsAr = explode($d, $nameAr);
                }

                for ($i = 0; $i < max(count($partsEn), count($partsAr)); $i++) {
                    $newPart = $part;
                    $newPart['part_name_en'] = trim($partsEn[$i] ?? ($partsEn[0] ?? ''));
                    $newPart['part_name_ar'] = trim($partsAr[$i] ?? ($partsAr[0] ?? ''));
                    // Try to extract quantity from string like "2x" or "عدد 2"
                    if (preg_match('/(\d+)x|(\d+)\s*x|عدد\s*(\d+)/u', $newPart['part_name_ar'] . $newPart['part_name_en'], $matches)) {
                        $newPart['quantity'] = (int)($matches[1] ?: ($matches[2] ?: $matches[3]));
                    }
                    $flattenedAiParts[] = $newPart;
                }
            } else {
                $flattenedAiParts[] = $part;
            }
        }

        $scaledParts = [];
        
        // Sum total quantities in cart for scaling context
        $totalBaseQty = 0;
        foreach ($this->cart as $item) {
            $totalBaseQty += $item['quantity'];
        }
        $totalBaseQty = max(1, $totalBaseQty);

        foreach ($flattenedAiParts as $part) {
            $nameEn = strtolower($part['part_name_en'] ?? '');
            $nameAr = $part['part_name_ar'] ?? '';
            $unitPrice = $part['estimated_unit_price_sar'] ?? 0;
            $qty = (int) ($part['quantity'] ?? 1);

            if ($isCamera) {
                // Scale cameras accurately
                if (str_contains($nameEn, 'camera') || str_contains($nameAr, 'كاميرا') || str_contains($nameAr, 'كمرات')) {
                    $qty = $totalBaseQty; 
                }
                
                // Upgrade DVR/NVR based on number of cameras
                $isDvr = str_contains($nameEn, 'dvr') || str_contains($nameEn, 'nvr') || str_contains($nameAr, 'تسجيل');
                if ($isDvr) {
                    $requiredPorts = 4;
                    if ($totalBaseQty > 16) {
                        $requiredPorts = 32;
                    } elseif ($totalBaseQty > 8) {
                        $requiredPorts = 16;
                    } elseif ($totalBaseQty > 4) {
                        $requiredPorts = 8;
                    }

                    if ($requiredPorts === 4) {
                        $nameAr = str_replace(['8 قنوات', '16 قناة', '32 قناة', '8 منافذ'], '4 قنوات', $nameAr);
                        $nameEn = str_replace(['8-port', '16-port', '32-port', '8 port'], '4-port', $nameEn);
                        if (!str_contains($nameAr, '4 قنوات')) $nameAr .= ' (4 قنوات)';
                        $unitPrice = 650.00; // Reset to 4-port price
                    } elseif ($requiredPorts === 8) {
                        $nameAr = str_replace(['4 قنوات', '4 منافذ'], '8 قنوات', $nameAr);
                        $nameEn = str_replace(['4-port', '4 port'], '8-port', $nameEn);
                        if (!str_contains($nameAr, '8 قنوات')) $nameAr .= ' (8 قنوات)';
                        $unitPrice = max($unitPrice, 1250.00); 
                    } elseif ($requiredPorts === 16) {
                        $nameAr = str_replace(['4 قنوات', '4 منافذ', '8 قنوات'], '16 قناة', $nameAr);
                        $nameEn = str_replace(['4-port', '4 port', '8-port'], '16-port', $nameEn);
                        if (!str_contains($nameAr, '16 قناة')) $nameAr .= ' (16 قناة)';
                        $unitPrice = max($unitPrice, 1800.00);
                    } elseif ($requiredPorts === 32) {
                        $nameAr = str_replace(['4 قنوات', '4 منافذ', '8 قنوات', '16 قناة'], '32 قناة', $nameAr);
                        $nameEn = str_replace(['4-port', '4 port', '8-port', '16-port'], '32-port', $nameEn);
                        if (!str_contains($nameAr, '32 قناة')) $nameAr .= ' (32 قناة)';
                        $unitPrice = max($unitPrice, 2800.00);
                    }
                }
            }

            $part['quantity'] = $qty;
            $part['part_name_ar'] = $nameAr;
            $part['part_name_en'] = $nameEn;
            $part['display_name_ar'] = ($qty > 1 ? "({$qty}x) " : "") . $nameAr;
            $part['display_name_en'] = ($qty > 1 ? "({$qty}x) " : "") . $nameEn;
            $part['estimated_unit_price_sar'] = $unitPrice;
            $part['total_price_sar'] = $unitPrice * $qty;
            
            $scaledParts[] = $part;
        }

        return $scaledParts;
    }

    /**
     * Determines if the currently selected option belongs to Construction/Contracting service.
     * Always checks via the option's service relation first (most specific & reliable),
     * then falls back to activeServiceId. This prevents stale state from polluting the check.
     */
    private function isConstructionService(): bool
    {
        if (!empty($this->cart)) {
            $firstId = array_key_first($this->cart);
            $option = ServiceOption::with('subService.service')->find($firstId);
            $slug = $option?->subService?->service?->slug;
            return $slug === 'construction-contracting';
        }

        if ($this->activeServiceId) {
            $service = Service::find($this->activeServiceId);
            return $service && $service->slug === 'construction-contracting';
        }

        return false;
    }

    // ── Checkout Logic ──────────────────────────────

    private function resetCheckoutState(): void
    {
        $this->phone = '';
        $this->otp = '';
        $this->showOtpInput = false;
        $this->showCheckoutModal = false;
        $this->showPaymentModal = false;
        $this->needsRegistration = false;
        $this->clientName = '';
        // Don't reset address necessarily, they might want to keep it
    }

    public function openCheckoutModal()
    {
        if (empty($this->cart)) {
            $this->addError('cart', app()->getLocale() === 'ar' ? 'الرجاء اختيار خدمة واحدة على الأقل.' : 'Please select at least one service.');
            return;
        }
        $this->showCheckoutModal = true;
    }

    public function sendOtp()
    {
        $this->validate(['phone' => 'required|min:9']);
        
        $user = \App\Models\User::where('phone', $this->phone)->first();

        if (!$user) {
            if (!$this->needsRegistration) {
                $this->needsRegistration = true;
                return;
            }

            $this->validate(['clientName' => 'required|min:3']);
            $user = \App\Models\User::create([
                'phone' => $this->phone,
                'name' => $this->clientName,
                'email' => 'client_' . time() . '@kifah.app', // Dummy email for guests
                'password' => \Illuminate\Support\Facades\Hash::make(str()->random(16)),
                'role' => \App\Enums\UserRole::CLIENT
            ]);
        }

        $otp = $user->generateOtp();
        \Illuminate\Support\Facades\Log::info("OTP Code for {$this->phone} is: {$otp}");
        $this->showOtpInput = true;
    }

    public function verifyAndBook()
    {
        $this->validate(['otp' => 'required|min:4']);
        $user = \App\Models\User::where('phone', $this->phone)->first();

        if ($user && $user->verifyOtp($this->otp)) {
            \Illuminate\Support\Facades\Auth::login($user, true);
            $this->showCheckoutModal = false;
            $this->showOtpInput = false;
            $this->showPaymentModal = true;
            return;
        }

        $this->addError('otp', app()->getLocale() === 'ar' ? 'رمز التفعيل (OTP) غير صحيح.' : 'Invalid OTP code.');
    }

    public function processPaymentAndBook()
    {
        // Simple delay simulation for payment processing could be added in blade later
        $this->confirmBooking();
    }

    public function confirmBooking()
    {
        if (!\Illuminate\Support\Facades\Auth::check() || empty($this->cart)) {
            return;
        }

        // Guard against accidental double-submit creating duplicate orders.
        $recentDuplicate = Order::where('client_id', \Illuminate\Support\Facades\Auth::id())
            ->where('created_at', '>=', now()->subSeconds(20))
            ->whereIn('status', [
                \App\Enums\OrderStatus::PENDING,
                \App\Enums\OrderStatus::ASSIGNED,
                \App\Enums\OrderStatus::CONFIRMED,
                \App\Enums\OrderStatus::EN_ROUTE,
                \App\Enums\OrderStatus::IN_PROGRESS,
            ])
            ->exists();

        if ($recentDuplicate) {
            session()->flash('error', app()->getLocale() === 'ar' ? 'تم إنشاء طلب مشابه للتو. يرجى الانتظار قليلاً قبل المحاولة.' : 'A similar order was just created. Please wait a few seconds before retrying.');
            return;
        }

        // Add visits to notes for construction
        $finalNotes = $this->aiProblemDescription;
        if ($this->isConstructionService()) {
            $visitsText = app()->getLocale() === 'ar' ? 'عدد الزيارات للتقييم: ' : 'Number of evaluation visits: ';
            $finalNotes = $finalNotes ? $finalNotes . "\n\n" . $visitsText . $this->constructionVisits 
                                      : $visitsText . $this->constructionVisits;
        }

        // Critical integrity: never trust client-side total; recalculate from DB-backed prices.
        $trustedPricing = $this->recalculateServerPricing();
        $cartOptionIds = array_map('intval', array_keys($this->cart));
        $options = ServiceOption::whereIn('id', $cartOptionIds)->get()->keyBy('id');

        $order = \App\Models\Order::create([
            'order_number'  => \App\Models\Order::generateOrderNumber(),
            'client_id'     => \Illuminate\Support\Facades\Auth::id(),
            'status'        => \App\Enums\OrderStatus::PENDING,
            'total'         => $trustedPricing['total'],
            'subtotal'      => $trustedPricing['subtotal'],
            'address'       => $this->address ?: 'Pending Location',
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'urgency'       => $this->urgency === 'urgent' ? \App\Enums\UrgencyType::URGENT : \App\Enums\UrgencyType::SCHEDULED,
            'client_notes'  => $finalNotes, // Save AI diagnosis & visits info here
        ]);

        foreach ($this->cart as $optionId => $item) {
            $option = $options->get((int) $optionId);
            if (!$option) continue;

            $itemUnitPrice = $this->urgency === 'urgent'
                ? $option->base_price * $option->urgent_multiplier
                : $option->base_price;

            \App\Models\OrderItem::create([
                'order_id'          => $order->id,
                'service_option_id' => $option->id,
                'quantity'          => $item['quantity'],
                'unit_price'        => $itemUnitPrice,
                'total_price'       => $itemUnitPrice * $item['quantity'],
            ]);
        }

        // Orders now remain PENDING until manually assigned by manager or claimed by tech.
        // Removal of: \App\Services\DispatchService::dispatch($order);

        // ── Send Database Notifications to all Admins ──
        try {
            $admins = \App\Models\User::whereIn('role', [
                \App\Enums\UserRole::SUPER_ADMIN, 
                \App\Enums\UserRole::TECHNICAL_MANAGER
            ])->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewOrderNotification($order));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('[Notification] Failed to notify admins: ' . $e->getMessage());
        }

        // ── Broadcast instantly to admin + technician via Reverb ──
        $order->load(['client', 'technician']);
        try {
            \App\Events\OrderPlaced::dispatch($order);
        } catch (\Illuminate\Broadcasting\BroadcastException $e) {
            \Illuminate\Support\Facades\Log::warning('[Reverb] Broadcast failed, but order was saved successfully: ' . $e->getMessage());
        }

        return redirect()->route('client.dashboard');
    }

    /**
     * Server-side trusted pricing calculation.
     * Uses DB prices for options and store parts to prevent client-side tampering.
     */
    private function recalculateServerPricing(): array
    {
        $subTotal = 0.0;
        $cartOptionIds = array_map('intval', array_keys($this->cart));
        $options = ServiceOption::whereIn('id', $cartOptionIds)->get()->keyBy('id');

        foreach ($this->cart as $optionId => $item) {
            $option = $options->get((int) $optionId);
            if (!$option) {
                continue;
            }

            $qty = max((int) ($option->min_quantity ?: 1), (int) ($item['quantity'] ?? 1));
            $qty = min($qty, (int) ($option->max_quantity ?: 100));

            $unitPrice = $this->urgency === 'urgent'
                ? (float) $option->base_price * (float) $option->urgent_multiplier
                : (float) $option->base_price;

            $subTotal += ($unitPrice * $qty);
        }

        $partsTotal = $this->calculateTrustedAiPartsTotal();
        $workingExpense = $this->isConstructionService() ? (self::CONSTRUCTION_WORKING_EXPENSE_SAR * $this->constructionVisits) : 0;

        return [
            'subtotal' => $subTotal,
            'parts_total' => $partsTotal,
            'working_expense' => $workingExpense,
            'total' => $subTotal + $partsTotal + $workingExpense,
        ];
    }

    /**
     * Trusted AI parts total using catalog prices only.
     */
    private function calculateTrustedAiPartsTotal(): float
    {
        if (!$this->prefillFromAi || empty($this->aiSuggestedParts)) {
            return 0.0;
        }

        $trustedTotal = 0.0;
        $scaledParts = $this->computeScaledAiParts();

        foreach ($scaledParts as $part) {
            $qty = max(1, (int) ($part['quantity'] ?? 1));
            $sku = trim((string) ($part['store_sku'] ?? ''));
            $nameEn = trim((string) ($part['part_name_en'] ?? ''));
            $nameAr = trim((string) ($part['part_name_ar'] ?? ''));

            $match = ServicePart::query()
                ->where('is_active', true)
                ->where(function ($q) use ($sku, $nameEn, $nameAr) {
                    if ($sku !== '') {
                        $q->orWhere('sku', $sku);
                    }
                    if ($nameEn !== '') {
                        $q->orWhere('name_en', 'LIKE', "%{$nameEn}%");
                    }
                    if ($nameAr !== '') {
                        $q->orWhere('name_ar', 'LIKE', "%{$nameAr}%");
                    }
                })
                ->first();

            if (!$match) {
                continue;
            }

            $trustedTotal += ((float) $match->price_sar * $qty);
        }

        return $trustedTotal;
    }

    public function render()
    {
        $servicesQuery = Service::active()->ordered();
        
        if (!empty($this->searchQuery)) {
            $servicesQuery->where(function($q) {
                $q->where('name_ar', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('name_en', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description_ar', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description_en', 'like', '%' . $this->searchQuery . '%');
            });
        }
        
        $services = $servicesQuery->get();

        // ── Direct Mode: load all sub-services with their options ──
        $directGroupedOptions = collect();
        if ($this->directMode && $this->activeServiceId) {
            $allSubs = SubService::where('service_id', $this->activeServiceId)
                ->active()
                ->orderBy('sort_order')
                ->get();

            foreach ($allSubs as $sub) {
                $opts = ServiceOption::where('sub_service_id', $sub->id)
                    ->active()
                    ->orderBy('sort_order')
                    ->get();
                if ($opts->isNotEmpty()) {
                    $directGroupedOptions->push([
                        'sub'     => $sub,
                        'options' => $opts,
                        'layout'  => 'two-column'
                    ]);
                }
            }
            
            // Sort to ensure 'Packages' / 'باقات' appear at the very top of the items list
            $directGroupedOptions = $directGroupedOptions->sortBy(function($group) {
                $name = $group['sub']->name_en ?? '';
                $nameAr = $group['sub']->name_ar ?? '';
                $isPkg = stripos($name, 'package') !== false || stripos($nameAr, 'باق') !== false;
                return $isPkg ? -1 : 1;
            })->values();
        }

        $subServices = (!$this->directMode && $this->activeServiceId)
            ? SubService::where('service_id', $this->activeServiceId)
                ->where('slug', '!=', 'cctv-recorders')
                ->active()
                ->orderBy('sort_order')
                ->get()
            : collect();

        $subServiceOptionCounts = [];
        if ($subServices->isNotEmpty()) {
            foreach ($subServices as $subService) {
                if ($subService->slug === 'cctv-cameras') {
                    $linkedIds = SubService::where('service_id', $subService->service_id)
                        ->whereIn('slug', ['cctv-cameras', 'cctv-recorders'])
                        ->pluck('id')
                        ->all();

                    $subServiceOptionCounts[$subService->id] = ServiceOption::whereIn('sub_service_id', $linkedIds)
                        ->active()
                        ->count();

                    continue;
                }

                $subServiceOptionCounts[$subService->id] = ServiceOption::where('sub_service_id', $subService->id)
                    ->active()
                    ->count();
            }
        }

        $activeSubService = $this->activeSubServiceId
            ? SubService::find($this->activeSubServiceId)
            : null;

        $serviceOptions = collect();
        if ($this->activeSubServiceId) {
            $subServiceIds = [$this->activeSubServiceId];

            // For camera flow, show DVR/NVR options in the same options list.
            if ($activeSubService && $activeSubService->slug === 'cctv-cameras') {
                $linkedIds = SubService::where('service_id', $activeSubService->service_id)
                    ->whereIn('slug', ['cctv-cameras', 'cctv-recorders'])
                    ->pluck('id')
                    ->all();

                if (!empty($linkedIds)) {
                    $subServiceIds = $linkedIds;
                }
            }

            $serviceOptions = ServiceOption::whereIn('sub_service_id', $subServiceIds)
                ->active()
                ->orderBy('sort_order')
                ->get();
        }

        $activeService = $this->activeServiceId
            ? Service::find($this->activeServiceId)
            : null;

        $selectedOption = $this->selectedOptionId
            ? ServiceOption::with('subService.service')->find($this->selectedOptionId)
            : null;

        // Custom SVG icons for premium categories
        $svgIcons = [
            'construction' => '<path d="M3 21h18M3 10h18M5 10V7a3 3 0 013-3h8a3 3 0 013 3v3M9 21V10M15 21V10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
            'camera'       => '<path d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
            'maint'        => '<path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
            'it'           => '<path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
            'security'     => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>',
            'clean'        => '<path d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m11.314 11.314l.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
        ];

        // ── Dynamic SEO Metadata ─────────────────────────────────────
        $lang = app()->getLocale();
        $isAr = $lang === 'ar';
        
        if ($activeSubService) {
            $name = $isAr ? $activeSubService->name_ar : $activeSubService->name_en;
            $parentName = $isAr ? $activeService->name_ar : $activeService->name_en;
            $metaTitle = $name . ' | ' . $parentName . ' | ' . ($isAr ? 'الكفاح العالمية' : 'Al-Kifah Global');
            $metaDescription = $isAr 
                ? "احصل على أفضل خدمات {$name} في المملكة العربية السعودية. جودة احترافية وسرعة في التنفيذ."
                : "Best {$name} services in KSA. Professional quality and rapid execution by Al-Kifah Global.";
        } elseif ($activeService) {
            $name = $isAr ? $activeService->name_ar : $activeService->name_en;
            $metaTitle = $name . ' | ' . ($isAr ? 'الكفاح العالمية للمقاولات والصيانة' : 'Al-Kifah Global Contracting & Maintenance');
            $metaDescription = $isAr 
                ? "خدمات {$name} الشاملة في كافة أنحاء المملكة. حلول مبتكرة وفريق عمل خبير."
                : "Comprehensive {$name} services across KSA. Innovative solutions and expert team.";
        } else {
            $metaTitle = $isAr ? 'الكفاح العالمية | المقاولات، الصيانة، وأنظمة الأمان' : 'Al-Kifah Global | Construction, Maintenance, & Security';
            $metaDescription = $isAr 
                ? 'وجهتك الأولى للمقاولات العامة، الصيانة المنزلية، والأنظمة الأمنية الحديثة في السعودية.'
                : 'Your premier destination for construction, home maintenance, and modern security systems in Saudi Arabia.';
        }

        return view('livewire.service-grid', [
            'services'              => $services,
            'subServices'           => $subServices,
            'serviceOptions'        => $serviceOptions,
            'selectedOption'        => $selectedOption,
            'activeService'         => $activeService,
            'activeSubService'      => $activeSubService,
            'isConstruction'        => $this->isConstructionService(),
            'workingExpense'        => $this->workingExpense,
            'cartCount'             => count($this->cart),
            'subServiceOptionCounts'=> $subServiceOptionCounts,
            'directMode'            => $this->directMode,
            'directGroupedOptions'  => $directGroupedOptions,
            'svgIcons'              => $svgIcons,
            'metaTitle'             => $metaTitle,
            'metaDescription'       => $metaDescription,
        ]);
    }
}
