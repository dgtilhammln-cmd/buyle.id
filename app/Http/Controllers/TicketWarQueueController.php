<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TicketWarQueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketWarQueueController extends Controller
{
    protected TicketWarQueueService $queueService;

    public function __construct(TicketWarQueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    /**
     * Polling status antrean war tiket.
     * Didesain ultra-cepat dengan Cache, ZERO write ke database untuk melindungi worker PHP.
     */
    public function check(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id'    => 'required|integer',
            'queue_id'      => 'required|string|max:100',
            'current_token' => 'nullable|string',
        ]);

        $productId    = (int) $validated['product_id'];
        $queueId      = (string) $validated['queue_id'];
        $currentToken = $validated['current_token'] ?? null;

        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Produk tiket tidak ditemukan.',
            ], 404);
        }

        // Jika bukan tiket atau tidak mengaktifkan jadwal war tiket, langsung loloskan
        if (!$product->isTicketProduct() || empty($product->ticket_war_start)) {
            return response()->json([
                'status'  => 'admitted',
                'token'   => null,
                'is_war'  => false,
                'message' => 'Produk dapat langsung dipesan.',
            ]);
        }

        // Jika penjualan belum dimulai, kembalikan status not_started
        if (now()->lt($product->ticket_war_start)) {
            return response()->json([
                'status'   => 'not_started',
                'opens_at' => $product->ticket_war_start->toIso8601String(),
                'message'  => 'Penjualan tiket belum dibuka. Mohon tunggu hitung mundur.',
            ]);
        }

        // Evaluasi alokasi slot melalui TicketWarQueueService
        $result = $this->queueService->getQueueStatus($productId, $queueId, $currentToken);

        return response()->json($result);
    }
}

