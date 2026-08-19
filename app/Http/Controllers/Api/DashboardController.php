<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        $totalStock = Product::sum('stock');
        $lowStockProducts = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $stockIn = StockMovement::where('type', 'in')->sum('quantity');
        $stockOut = StockMovement::where('type', 'out')->sum('quantity');
        $recentMovement = StockMovement::latest()->take(5)->get()->map(function ($movement) {
            return [
                'id' => $movement->id,
                'product' => $movement->product->name,
                'type' => $movement->type,
                'quantity' => $movement->quantity,
                'note' => $movement->note,
                'created_at' => $movement->created_at,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_suppliers' => $totalSuppliers,
                'total_stock' => $totalStock,
                'low_stock_products' => $lowStockProducts,
                'stock_in' => $stockIn,
                'stock_out' => $stockOut,
                'recent_movements' => $recentMovement,
            ],
        ]);
    }

    public function movements(Request $request)
    {
        $period = $request->input('period', 7);

        if (! in_array($period, [7, 30, 90])) {
            return response()->json([
                'success' => false,
                'message' => 'Period must be 7, 30, or 90 days.',
            ], 422);
        }

        $startDate = now()->subDays($period - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $movements = StockMovement::whereBetween('created_at', [$startDate, $endDate])->selectRaw
        ("DATE(created_at) as date, SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) as stock_in,
        SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) as stock_out")
        ->groupByRaw('DATE(created_at)')->orderBy('date')->get();
        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'movements' => $movements,
            ],
        ]);
    }
}
