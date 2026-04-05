<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\GeminiDiagnosisService;

class GeminiDiagnosisController extends Controller
{
    protected GeminiDiagnosisService $diagnosisService;

    public function __construct(GeminiDiagnosisService $diagnosisService)
    {
        $this->diagnosisService = $diagnosisService;
    }

    public function analyze(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image_base64' => 'required|string|max:7340032',
            'mime_type'    => 'required|string|in:image/jpeg,image/jpg,image/png,image/webp,image/heic',
            'user_context' => 'nullable|string|max:500',
        ]);

        $result = $this->diagnosisService->analyze(
            $validated['image_base64'],
            $validated['mime_type'],
            $validated['user_context'] ?? null
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error'   => $result['error'],
            ], 422); // using 422 to simplify error handling on client side
        }

        return response()->json([
            'success'   => true,
            'diagnosis' => $result['diagnosis'],
        ]);
    }
}
