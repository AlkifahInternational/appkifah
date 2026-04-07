<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\SubService;
use App\Models\ServiceOption;
use App\Models\User;
use App\Enums\UserRole;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.admin')]
class AdminServiceManager extends Component
{
    public $services;
    public $managers;

    // ── Edit Manager Assignment ─────────────────────────
    public $editingServiceManagerId = null; // Service ID being edited
    public $serviceManagerSelect = null;    // Selected User ID

    // ── Edit Pricing ────────────────────────────────────
    public $editingOptionId = null;
    public $editBasePrice = 0;
    public $editMultiplier = 1.0;
    public $editMinQuantity = 1;
    public $editMaxQuantity = 10;

    // ── Add Sub-Service ────────────────────────────────
    public $addingSubServiceId = null; // parent Service ID
    public $newSubNameEn = '';
    public $newSubNameAr = '';
    public $newSubSlug = '';

    // ── Add Option ─────────────────────────────────────
    public $addingOptionSubId = null; // parent SubService ID
    public $newOptNameEn = '';
    public $newOptNameAr = '';
    public $newOptUnitEn = '';
    public $newOptUnitAr = '';
    public $newOptPrice = 0;
    public $newOptMultiplier = 1.5;
    public $newOptMinQty = 1;
    public $newOptMaxQty = 10;

    // ── Add Service (top-level) ─────────────────────────
    public $showAddService = false;
    public $newSvcNameEn = '';
    public $newSvcNameAr = '';
    public $newSvcDescEn = '';
    public $newSvcDescAr = '';
    public $newSvcColor = '#805AD5';
    public $newSvcIcon = '🔧';

    public function mount()
    {
        $this->loadServices();
    }

    public function loadServices()
    {
        $this->services = Service::with(['manager', 'subServices.serviceOptions' => fn($q) => $q->orderBy('sort_order')])
            ->orderBy('sort_order')->get();
        $this->managers = User::where('role', UserRole::TECHNICAL_MANAGER)->get();
    }

    // ── Manager Assignment ───────────────────────────────────────────────────

    public function editServiceManager($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $this->editingServiceManagerId = $serviceId;
        $this->serviceManagerSelect = $service->manager_id;
    }

    public function saveServiceManager()
    {
        Service::findOrFail($this->editingServiceManagerId)->update([
            'manager_id' => $this->serviceManagerSelect ?: null,
        ]);
        $this->editingServiceManagerId = null;
        $this->loadServices();
        session()->flash('message', __('Manager assigned successfully.'));
    }

    // ── Edit Pricing ─────────────────────────────────────────────────────────

    public function editOption($id)
    {
        $option = ServiceOption::findOrFail($id);
        $this->editingOptionId = $id;
        $this->editBasePrice = $option->base_price;
        $this->editMultiplier = $option->urgent_multiplier;
        $this->editMinQuantity = $option->min_quantity;
        $this->editMaxQuantity = $option->max_quantity;
    }

    public function saveOption()
    {
        $this->validate([
            'editBasePrice'    => 'required|numeric|min:0',
            'editMultiplier'   => 'required|numeric|min:1',
            'editMinQuantity'  => 'required|integer|min:1',
            'editMaxQuantity'  => 'required|integer|min:1',
        ]);
        ServiceOption::findOrFail($this->editingOptionId)->update([
            'base_price'        => $this->editBasePrice,
            'urgent_multiplier' => $this->editMultiplier,
            'min_quantity'      => $this->editMinQuantity,
            'max_quantity'      => $this->editMaxQuantity,
        ]);
        $this->editingOptionId = null;
        $this->loadServices();
        session()->flash('message', __('Pricing updated successfully.'));
    }

    public function cancelEdit()
    {
        $this->editingOptionId = null;
    }

    public function deleteOption($id)
    {
        ServiceOption::findOrFail($id)->delete();
        $this->loadServices();
        session()->flash('message', __('Option deleted.'));
    }

    // ── Add Sub-Service ──────────────────────────────────────────────────────

    public function startAddSubService($serviceId)
    {
        $this->addingSubServiceId = $serviceId;
        $this->newSubNameEn = '';
        $this->newSubNameAr = '';
        $this->newSubSlug = '';
    }

    public function saveSubService()
    {
        $this->validate([
            'newSubNameEn' => 'required|string|max:120',
            'newSubNameAr' => 'required|string|max:120',
        ]);
        $slug = $this->newSubSlug ?: \Illuminate\Support\Str::slug($this->newSubNameEn);
        $max = SubService::where('service_id', $this->addingSubServiceId)->max('sort_order') ?? 0;
        SubService::create([
            'service_id' => $this->addingSubServiceId,
            'name_en'    => $this->newSubNameEn,
            'name_ar'    => $this->newSubNameAr,
            'slug'       => $slug,
            'icon'       => '🔧',
            'sort_order' => $max + 1,
        ]);
        $this->addingSubServiceId = null;
        $this->loadServices();
        session()->flash('message', __('Sub-service added successfully.'));
    }

    public function cancelAddSubService()
    {
        $this->addingSubServiceId = null;
    }

    public function deleteSubService($id)
    {
        SubService::findOrFail($id)->delete();
        $this->loadServices();
        session()->flash('message', __('Sub-service deleted.'));
    }

    // ── Add Option ───────────────────────────────────────────────────────────

    public function startAddOption($subServiceId)
    {
        $this->addingOptionSubId = $subServiceId;
        $this->newOptNameEn = '';
        $this->newOptNameAr = '';
        $this->newOptUnitEn = '';
        $this->newOptUnitAr = '';
        $this->newOptPrice = 0;
        $this->newOptMultiplier = 1.5;
        $this->newOptMinQty = 1;
        $this->newOptMaxQty = 10;
    }

    public function saveOption2()
    {
        $this->validate([
            'newOptNameEn'     => 'required|string|max:120',
            'newOptNameAr'     => 'required|string|max:120',
            'newOptPrice'      => 'required|numeric|min:0',
            'newOptMultiplier' => 'required|numeric|min:1',
            'newOptMinQty'     => 'required|integer|min:1',
            'newOptMaxQty'     => 'required|integer|min:1',
        ]);
        $max = ServiceOption::where('sub_service_id', $this->addingOptionSubId)->max('sort_order') ?? 0;
        ServiceOption::create([
            'sub_service_id'    => $this->addingOptionSubId,
            'name_en'           => $this->newOptNameEn,
            'name_ar'           => $this->newOptNameAr,
            'unit_label_en'     => $this->newOptUnitEn ?: 'units',
            'unit_label_ar'     => $this->newOptUnitAr ?: 'وحدات',
            'base_price'        => $this->newOptPrice,
            'urgent_multiplier' => $this->newOptMultiplier,
            'min_quantity'      => $this->newOptMinQty,
            'max_quantity'      => $this->newOptMaxQty,
            'sort_order'        => $max + 1,
        ]);
        $this->addingOptionSubId = null;
        $this->loadServices();
        session()->flash('message', __('Option added successfully.'));
    }

    public function cancelAddOption()
    {
        $this->addingOptionSubId = null;
    }

    // ── Add Top-Level Service ────────────────────────────────────────────────

    public function saveService()
    {
        $this->validate([
            'newSvcNameEn' => 'required|string|max:120',
            'newSvcNameAr' => 'required|string|max:120',
            'newSvcColor'  => 'required|string|max:20',
        ]);
        $max = Service::max('sort_order') ?? 0;
        Service::create([
            'name_en'        => $this->newSvcNameEn,
            'name_ar'        => $this->newSvcNameAr,
            'slug'           => \Illuminate\Support\Str::slug($this->newSvcNameEn),
            'description_en' => $this->newSvcDescEn,
            'description_ar' => $this->newSvcDescAr,
            'color'          => $this->newSvcColor,
            'icon'           => $this->newSvcIcon ?: '🔧',
            'sort_order'     => $max + 1,
        ]);
        $this->showAddService = false;
        $this->newSvcNameEn = $this->newSvcNameAr = $this->newSvcDescEn = $this->newSvcDescAr = '';
        $this->newSvcColor = '#805AD5';
        $this->newSvcIcon = '🔧';
        $this->loadServices();
        session()->flash('message', __('Service added successfully.'));
    }

    public function deleteService($id)
    {
        Service::findOrFail($id)->delete();
        $this->loadServices();
        session()->flash('message', __('Service deleted.'));
    }

    public function render()
    {
        return view('livewire.admin-service-manager');
    }
}
