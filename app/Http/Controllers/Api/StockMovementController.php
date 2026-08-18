<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockMovementResource;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    /**
     * Display a listing of stock movements.
     */
    public function index()
    {
        $movements = StockMovement::with([
            'product',
            'user',
        ])
            ->latest()
            ->paginate(10);

        return StockMovementResource::collection($movements);
    }

    /**
     * Add stock.
     */
    public function storeIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        $movement = DB::transaction(function () use ($validated, $request) {
            $product = Product::lockForUpdate()->findOrFail(
                $validated['product_id']
            );

            $stockBefore = $product->stock;
            $stockAfter = $stockBefore + $validated['quantity'];

            $product->update([
                'stock' => $stockAfter,
            ]);

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'note' => $validated['note'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock added successfully',
            'data' => new StockMovementResource(
                $movement->load(['product', 'user'])
            ),
        ], 201);
    }

    /**
     * Remove stock.
     */
    public function storeOut(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
        ]);

        $movement = DB::transaction(function () use ($validated, $request) {
            $product = Product::lockForUpdate()->findOrFail(
                $validated['product_id']
            );

            $stockBefore = $product->stock;

            if ($validated['quantity'] > $stockBefore) {
                abort(422, 'Insufficient stock');
            }

            $stockAfter = $stockBefore - $validated['quantity'];

            $product->update([
                'stock' => $stockAfter,
            ]);

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'out',
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'note' => $validated['note'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock removed successfully',
            'data' => new StockMovementResource(
                $movement->load(['product', 'user'])
            ),
        ], 201);
    }

    /**
     * Adjust stock to a specific quantity.
     */
    public function adjustment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string'],
        ]);

        $movement = DB::transaction(function () use ($validated, $request) {
            $product = Product::lockForUpdate()->findOrFail(
                $validated['product_id']
            );

            $stockBefore = $product->stock;
            $stockAfter = $validated['quantity'];

            $product->update([
                'stock' => $stockAfter,
            ]);

            return StockMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => 'adjustment',
                'quantity' => $validated['quantity'],
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'note' => $validated['note'] ?? null,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock adjusted successfully',
            'data' => new StockMovementResource(
                $movement->load(['product', 'user'])
            ),
        ], 201);
    }
}
