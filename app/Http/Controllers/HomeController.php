<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\CashFlow;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $viewData = [];

        if ($user->hasRole('manajer')) {
            $viewData = $this->getManagerDashboardData();
        } elseif ($user->hasRole('kasir')) {
            $viewData = $this->getCashierDashboardData();
        } elseif ($user->hasRole('designer')) {
            $viewData = $this->getDesignerDashboardData();
        } elseif ($user->hasRole('operator')) {
            $viewData = $this->getOperatorDashboardData();
        }

        return view('dashboard', $viewData);
    }

    private function getManagerDashboardData()
    {
        $today = now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $totalIncome = CashFlow::where('type', 'in')->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount');
        $newOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $completedOrders = Order::where('status', 'selesai')->whereBetween('paid_at', [$startOfMonth, $endOfMonth])->count();

        return [
            'totalIncome' => $totalIncome,
            'newOrders' => $newOrders,
            'completedOrders' => $completedOrders,
        ];
    }

    private function getCashierDashboardData()
    {
        $readyForPayment = Order::where('status', 'produksi_selesai')->count();
        $totalToday = CashFlow::where('type', 'in')->whereDate('created_at', today())->sum('amount');

        return [
            'readyForPayment' => $readyForPayment,
            'totalToday' => $totalToday,
        ];
    }

    private function getDesignerDashboardData()
    {
        $ordersToDesign = Order::where('status', 'baru')->where('has_design', false)->count();
        $ordersToVerify = Order::where('status', 'baru')->where('has_design', true)->count();

        return [
            'ordersToDesign' => $ordersToDesign,
            'ordersToVerify' => $ordersToVerify,
        ];
    }

    private function getOperatorDashboardData()
    {
        $productionQueue = Order::where('status', 'siap_produksi')->count();
        $inProduction = Order::where('status', 'sedang_diproduksi')->count();

        return [
            'productionQueue' => $productionQueue,
            'inProduction' => $inProduction,
        ];
    }
}