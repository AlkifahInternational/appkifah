<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Events\OrderPlaced;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with(['service', 'subService'])->latest()->get();
        return response()->json([
            'data' => $orders
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = $request->user()->orders()->with(['service', 'subService', 'items.serviceOption'])->findOrFail($id);
        return response()->json([
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'sub_service_id' => 'required|exists:sub_services,id',
            'address' => 'required|string|max:1000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'total_price' => 'numeric|min:0',
            'items' => 'nullable|array',
            'items.*.sub_service_option_id' => 'required|exists:service_options,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric'
        ]);

        $order = Order::create([
            'uuid' => Str::uuid(),
            'user_id' => $request->user()->id,
            'service_id' => $validated['service_id'],
            'sub_service_id' => $validated['sub_service_id'],
            'status' => 'pending',
            'address' => $validated['address'],
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'total_price' => $validated['total_price'] ?? 0,
        ]);

        if (isset($validated['items']) && count($validated['items']) > 0) {
            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'service_option_id' => $item['sub_service_option_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
        }

        // Trigger real-time notifications for the admin dashboard
        event(new OrderPlaced($order));

        return response()->json([
            'message' => 'Order created successfully',
            'data' => $order->load('items')
        ], 201);
    }
}
