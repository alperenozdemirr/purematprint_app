<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Default;

use App\Enums\OrderStatus;
use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DefaultController extends Controller
{
    public function toggleSidebar(Request $request): JsonResponse
    {
        $collapsed = $request->boolean('collapsed');
        $request->session()->put('admin_sidebar_collapsed', $collapsed);

        return response()->json([
            'collapsed' => $collapsed,
        ]);
    }

    public function index(): View
    {
        $totalOrders = Order::query()->count();
        $monthlyRevenue = Order::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', OrderStatus::CANCELLED->value)
            ->sum('total');
        $totalProducts = Product::query()->count();
        $activeProducts = Product::query()->where('status', Status::ACTIVE->value)->count();
        $totalUsers = User::query()->where('type', UserType::USER->value)->count();

        $recentOrders = Order::query()
            ->with('user')
            ->latest()
            ->limit(5)
            ->get();

        $lowStockProducts = Product::query()
            ->where('stock_count', '<=', 10)
            ->orderBy('stock_count')
            ->limit(5)
            ->get();

        return view('admin.index', compact(
            'totalOrders',
            'monthlyRevenue',
            'totalProducts',
            'activeProducts',
            'totalUsers',
            'recentOrders',
            'lowStockProducts',
        ));
    }
}
