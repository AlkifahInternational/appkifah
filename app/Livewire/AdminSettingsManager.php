<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin')]
class AdminSettingsManager extends Component
{
    public $terms_ar;
    public $terms_en;
    public $platform_commission;
    public $vat_rate;

    public function mount()
    {
        $this->terms_ar = DB::table('settings')->where('key', 'terms_ar')->value('value') ?? '';
        $this->terms_en = DB::table('settings')->where('key', 'terms_en')->value('value') ?? '';
        $this->platform_commission = DB::table('settings')->where('key', 'platform_commission')->value('value') ?? '15';
        $this->vat_rate = DB::table('settings')->where('key', 'vat_rate')->value('value') ?? '15';
    }

    public function saveSettings()
    {
        $this->validate([
            'terms_ar' => 'required|string',
            'terms_en' => 'required|string',
            'platform_commission' => 'required|numeric|min:0|max:100',
            'vat_rate' => 'required|numeric|min:0|max:100',
        ]);

        $settings = [
            'terms_ar' => $this->terms_ar,
            'terms_en' => $this->terms_en,
            'platform_commission' => $this->platform_commission,
            'vat_rate' => $this->vat_rate,
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'type' => is_numeric($value) ? 'number' : 'text', 'updated_at' => now()]
            );
        }

        session()->flash('message', __('Settings and Policy saved successfully.'));
    }

    public function render()
    {
        return view('livewire.admin-settings-manager');
    }
}
