<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use App\Services\GeminiDiagnosisService;

/**
 * VisualDiagnosis Livewire Component
 *
 * Handles the "صور مشكلتك" (Picture Your Problem) AI diagnosis flow:
 * 1. Shows a CTA button on the home screen
 * 2. Opens an image capture overlay (handled by Alpine.js + JS FileReader)
 * 3. Sends image to /api/diagnose → Gemini Vision API
 * 4. Displays elegantly formatted result bottom sheet
 * 5. On "Confirm", dispatches aiDiagnosisConfirmed event to ServiceGrid
 */
class VisualDiagnosis extends Component
{
    // ── Modal state ────────────────────────────────────────────────────────────
    public bool $showCaptureModal = false;   // Camera/gallery picker overlay
    public bool $showResultSheet  = false;   // AI result bottom sheet

    // ── Image data (set from JS via dispatch) ─────────────────────────────────
    public string $imageBase64  = '';
    public string $mimeType     = 'image/jpeg';
    public string $imagePreview = '';        // Data URL for <img> preview

    // ── User input ────────────────────────────────────────────────────────────
    public string $userContext = '';         // Optional text context from user

    // ── Processing state ──────────────────────────────────────────────────────
    public bool   $isAnalyzing = false;
    public string $analyzeError = '';

    // ── Diagnosis result (from Gemini) ─────────────────────────────────────────
    public array $diagnosisResult = [];

    // ── Open / close ──────────────────────────────────────────────────────────

    #[On('openAiModal')]
    public function openCaptureModal(): void
    {
        $this->reset(['imageBase64', 'mimeType', 'imagePreview', 'userContext',
                       'diagnosisResult', 'analyzeError', 'showResultSheet']);
        $this->showCaptureModal = true;
    }

    public function closeCaptureModal(): void
    {
        $this->showCaptureModal = false;
        $this->reset(['imageBase64', 'imagePreview', 'userContext', 'analyzeError']);
    }

    public function closeResultSheet(): void
    {
        $this->showResultSheet   = false;
        $this->showCaptureModal  = false;
        $this->diagnosisResult   = [];
        $this->imageBase64       = '';
        $this->imagePreview      = '';
        $this->userContext       = '';
    }

    /**
     * Receives base64 image data from the JS FileReader via Alpine dispatch.
     * Called via: $wire.receiveImage(base64String, mimeType, previewDataUrl)
     */
    public function receiveImage(string $base64, string $mimeType, string $preview): void
    {
        $this->imageBase64  = $base64;
        $this->mimeType     = $mimeType;
        $this->imagePreview = $preview;
        $this->analyzeError = '';
    }

    /**
     * Main analysis action: send image to our Gemini bridge endpoint.
     */
    public function analyzeImage(): void
    {
        if (empty($this->imageBase64)) {
            $this->analyzeError = app()->getLocale() === 'ar'
                ? 'يرجى التقاط صورة أولاً.'
                : 'Please capture an image first.';
            return;
        }

        $this->isAnalyzing  = true;
        $this->analyzeError = '';

        try {
            $diagnosisService = app(GeminiDiagnosisService::class);
            $result = $diagnosisService->analyze(
                $this->imageBase64,
                $this->mimeType,
                $this->userContext
            );

            if (!$result['success']) {
                $this->analyzeError = $result['error']
                    ?? (app()->getLocale() === 'ar'
                        ? 'فشل التحليل. يرجى المحاولة مرة أخرى.'
                        : 'Analysis failed. Please try again.');
                $this->isAnalyzing = false;
                return;
            }

            $this->diagnosisResult  = $result['diagnosis'];
            $this->showCaptureModal = false;
            $this->showResultSheet  = true;

        } catch (\Exception $e) {
            Log::error('[VisualDiagnosis] HTTP error', ['error' => $e->getMessage()]);
            $this->analyzeError = app()->getLocale() === 'ar'
                ? 'خطأ في الاتصال. تحقق من الإنترنت وأعد المحاولة.'
                : 'Connection error. Please check your internet and try again.';
        }

        $this->isAnalyzing = false;
    }

    /**
     * User confirmed the diagnosis — dispatch to ServiceGrid to route & auto-fill.
     */
    public function confirmAndRoute(): void
    {
        if (empty($this->diagnosisResult)) {
            return;
        }

        $slug        = $this->diagnosisResult['category_slug'] ?? 'home-maintenance';
        $serviceId   = $this->diagnosisResult['service_id'] ?? null;
        $optionId    = $this->diagnosisResult['service_option_id'] ?? null;
        $description = $this->diagnosisResult['pre_filled_description'] ?? '';
        $urgency     = $this->diagnosisResult['urgency_suggestion'] ?? 'scheduled';
        $budget      = $this->diagnosisResult['budget_estimate'] ?? null;
        $parts       = $this->diagnosisResult['suggested_parts'] ?? [];

        // Fire Livewire event → ServiceGrid listens and handles routing
        $this->dispatch('aiDiagnosisConfirmed', [
            'service_id'        => $serviceId,
            'service_option_id' => $optionId,
            'slug'              => $slug,
            'description'       => $description,
            'urgency'           => $urgency,
            'budget_estimate'   => $budget,
            'suggested_parts'   => $parts,
        ]);

        $this->closeResultSheet();
    }

    public function render()
    {
        return view('livewire.visual-diagnosis');
    }
}
