<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\SubService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('subServices')->get();
        return response()->json([
            'data' => $services
        ]);
    }

    public function show($id)
    {
        $service = Service::with('subServices')->findOrFail($id);
        return response()->json([
            'data' => $service
        ]);
    }

    public function subServices($id)
    {
        $subServices = SubService::where('service_id', $id)->with('options')->get();
        return response()->json([
            'data' => $subServices
        ]);
    }
}
