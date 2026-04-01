<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.admin', ['title' => 'Payments'])]
class AdminPaymentSettings extends Component
{
    // ── Gateway Toggles ────────────────────────────────
    public bool $enable_mada       = true;
    public bool $enable_apple_pay  = false;
    public bool $enable_stc_pay    = false;
    public bool $enable_cash       = true;

    // ── Moyasar (Saudi Payment Gateway) ───────────────
    public string $moyasar_publishable_key = '';
    public string $moyasar_secret_key      = '';
    public string $moyasar_mode            = 'test'; // test | live

    // ── Bank Transfer ──────────────────────────────────
    public string $bank_name           = '';
    public string $bank_account_name   = '';
    public string $bank_account_number = '';
    public string $bank_iban           = '';
    public bool   $enable_bank_transfer = false;

    // ── Commission ─────────────────────────────────────
    public string $commission_rate  = '15';
    public string $vat_rate         = '15';

    public ?string $savedMessage = null;

    public function mount(): void
    {
        $settings = DB::table('settings')
            ->where('group', 'payment')
            ->pluck('value', 'key')
            ->toArray();

        $this->enable_mada           = (bool) ($settings['enable_mada']           ?? true);
        $this->enable_apple_pay      = (bool) ($settings['enable_apple_pay']      ?? false);
        $this->enable_stc_pay        = (bool) ($settings['enable_stc_pay']        ?? false);
        $this->enable_cash           = (bool) ($settings['enable_cash']           ?? true);
        $this->enable_bank_transfer  = (bool) ($settings['enable_bank_transfer']  ?? false);
        $this->moyasar_publishable_key = $settings['moyasar_publishable_key'] ?? '';
        $this->moyasar_secret_key      = $settings['moyasar_secret_key']      ?? '';
        $this->moyasar_mode            = $settings['moyasar_mode']            ?? 'test';
        $this->bank_name               = $settings['bank_name']               ?? '';
        $this->bank_account_name       = $settings['bank_account_name']       ?? '';
        $this->bank_account_number     = $settings['bank_account_number']     ?? '';
        $this->bank_iban               = $settings['bank_iban']               ?? '';
        $this->commission_rate         = $settings['commission_rate']         ?? '15';
        $this->vat_rate                = $settings['vat_rate']                ?? '15';
    }

    public function savePaymentMethods(): void
    {
        $keys = ['enable_mada', 'enable_apple_pay', 'enable_stc_pay', 'enable_cash', 'enable_bank_transfer'];
        foreach ($keys as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $this->$key ? '1' : '0', 'group' => 'payment', 'type' => 'boolean', 'updated_at' => now()]
            );
        }
        $this->savedMessage = __('Payment methods saved.');
    }

    public function saveGatewayKeys(): void
    {
        $this->validate([
            'moyasar_publishable_key' => 'nullable|string|max:255',
            'moyasar_secret_key'      => 'nullable|string|max:255',
            'moyasar_mode'            => 'required|in:test,live',
        ]);

        $keys = ['moyasar_publishable_key', 'moyasar_secret_key', 'moyasar_mode'];
        foreach ($keys as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $this->$key, 'group' => 'payment', 'type' => 'string', 'updated_at' => now()]
            );
        }
        $this->savedMessage = __('Gateway keys saved.');
    }

    public function saveBankDetails(): void
    {
        $this->validate([
            'bank_iban' => 'nullable|string|max:34',
        ]);

        $keys = ['bank_name', 'bank_account_name', 'bank_account_number', 'bank_iban'];
        foreach ($keys as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $this->$key, 'group' => 'payment', 'type' => 'string', 'updated_at' => now()]
            );
        }
        $this->savedMessage = __('Bank details saved.');
    }

    public function saveCommission(): void
    {
        $this->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'vat_rate'        => 'required|numeric|min:0|max:100',
        ]);

        foreach (['commission_rate', 'vat_rate'] as $key) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $this->$key, 'group' => 'payment', 'type' => 'number', 'updated_at' => now()]
            );
        }
        $this->savedMessage = __('Commission rates saved.');
    }

    public function render()
    {
        return view('livewire.admin-payment-settings');
    }
}
