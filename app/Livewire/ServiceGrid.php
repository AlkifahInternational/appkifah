<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;
use Livewire\Component;

class ServiceGrid extends Component
{
    // Navigation state
    public string $searchQuery = "";
    public ?int $activeServiceId = null;
    public ?int $activeSubServiceId = null;
    public bool $showModal = false;

    // Modal state
    public ?int $selectedOptionId = null;
    public int $quantity = 1;
    public string $urgency = 'scheduled';
    public string $address = '';
    public float $latitude = 0;
    public float $longitude = 0;

    // Computed data
    public float $calculatedPrice = 0;

    // Checkout state
    public string $phone = '';
    public string $otp = '';
    public bool $showOtpInput = false;

    public function selectService(int $serviceId): void
    {
        $this->activeServiceId = $serviceId;
        $this->activeSubServiceId = null;
        $this->showModal = false;
        $this->resetCheckoutState();
    }

    public function selectSubService(int $subServiceId): void
    {
        $this->activeSubServiceId = $subServiceId;
    }

    public function openModal(int $optionId): void
    {
        $this->selectedOptionId = $optionId;
        $this->quantity = 1;
        $this->urgency = 'scheduled';
        $this->showModal = true;
        $this->resetCheckoutState();
        $this->calculatePrice();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedOptionId = null;
    }

    public function goBack(): void
    {
        if ($this->activeSubServiceId) {
            $this->activeSubServiceId = null;
        } elseif ($this->activeServiceId) {
            $this->activeServiceId = null;
        }
        $this->showModal = false;
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
        $option = ServiceOption::find($this->selectedOptionId);
        if ($option && $this->quantity > $option->min_quantity) {
            $this->quantity--;
            $this->calculatePrice();
        }
    }

    public function updatedUrgency(): void
    {
        $this->calculatePrice();
    }

    public function calculatePrice(): void
    {
        $option = ServiceOption::find($this->selectedOptionId);
        if ($option) {
            $unitPrice = $this->urgency === 'urgent'
                ? $option->base_price * $option->urgent_multiplier
                : $option->base_price;
            $this->calculatedPrice = $unitPrice * $this->quantity;
        }
    }

    // ── Checkout Logic ──────────────────────────────

    private function resetCheckoutState(): void
    {
        $this->phone = '';
        $this->otp = '';
        $this->showOtpInput = false;
    }

    public function sendOtp()
    {
        $this->validate(['phone' => 'required|min:9']);
        
        $user = \App\Models\User::firstOrCreate(
            ['phone' => $this->phone],
            [
                'name' => 'Guest_' . rand(1000, 9999), 
                'email' => 'guest_' . time() . '@kifah.app', // Dummy email for guests
                'password' => \Illuminate\Support\Facades\Hash::make(str()->random(16)),
                'role' => \App\Enums\UserRole::CLIENT
            ]
        );

        $user->generateOtp();
        $this->showOtpInput = true;
    }

    public function verifyAndBook()
    {
        $this->validate(['otp' => 'required|min:4']);
        $user = \App\Models\User::where('phone', $this->phone)->first();

        if ($user && $user->verifyOtp($this->otp)) {
            \Illuminate\Support\Facades\Auth::login($user, true);
            return $this->confirmBooking();
        }

        $this->addError('otp', __('Invalid OTP code.'));
    }

    public function confirmBooking()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return;
        }

        $option = ServiceOption::find($this->selectedOptionId);
        
        $order = \App\Models\Order::create([
            'order_number'  => \App\Models\Order::generateOrderNumber(),
            'client_id'     => \Illuminate\Support\Facades\Auth::id(),
            'status'        => \App\Enums\OrderStatus::PENDING,
            'total'         => $this->calculatedPrice,
            'address'       => $this->address ?: 'Pending Location',
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'urgency'       => $this->urgency === 'urgent' ? \App\Enums\UrgencyType::URGENT : \App\Enums\UrgencyType::SCHEDULED,
        ]);

        \App\Models\OrderItem::create([
            'order_id'          => $order->id,
            'service_option_id' => $option->id,
            'quantity'          => $this->quantity,
            'unit_price'        => $option->base_price,
            'total_price'       => $this->calculatedPrice,
        ]);

        // ── Auto-dispatch to nearest available technician ──
        \App\Services\DispatchService::dispatch($order);

        // ── Broadcast instantly to admin + technician via Reverb ──
        $order->load(['client', 'technician']);
        \App\Events\OrderPlaced::dispatch($order);

        return redirect()->route('client.dashboard');
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

        $subServices = $this->activeServiceId
            ? SubService::where('service_id', $this->activeServiceId)->active()->orderBy('sort_order')->get()
            : collect();

        $serviceOptions = $this->activeSubServiceId
            ? ServiceOption::where('sub_service_id', $this->activeSubServiceId)->active()->orderBy('sort_order')->get()
            : collect();

        $selectedOption = $this->selectedOptionId
            ? ServiceOption::with('subService.service')->find($this->selectedOptionId)
            : null;

        $activeService = $this->activeServiceId
            ? Service::find($this->activeServiceId)
            : null;

        $activeSubService = $this->activeSubServiceId
            ? SubService::find($this->activeSubServiceId)
            : null;

        return view('livewire.service-grid', [
            'services' => $services,
            'subServices' => $subServices,
            'serviceOptions' => $serviceOptions,
            'selectedOption' => $selectedOption,
            'activeService' => $activeService,
            'activeSubService' => $activeSubService,
        ]);
    }
}
