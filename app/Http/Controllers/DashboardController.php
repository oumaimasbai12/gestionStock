<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            if (auth()->user()->hasRole('storekeeper')) {
                return redirect()->route('entries.index');
            } elseif (auth()->user()->hasRole('site_manager')) {
                return redirect()->route('exits.index');
            }
            abort(403, "Accès interdit.");
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 1. Valeur du Stock: SUM(stock * purchase_price)
        $totalInventoryValue = 0;
        try {
            $totalInventoryValue = Product::select(
                DB::raw('SUM(CAST(stock AS DECIMAL(15,2)) * CAST(purchase_price AS DECIMAL(15,2))) as total')
            )->first()->total ?? 0;
        } catch (\Exception $e) {
            $totalInventoryValue = 0;
        }

        // 2. Global Debt (all time): SUM(amount_due) WHERE payment_status IN ('unpaid', 'partial')
        $globalDebt = 0;
        try {
            $globalDebt = DB::table('stock_exits')
                ->whereNull('deleted_at')
                ->whereIn('payment_status', ['unpaid', 'partial'])
                ->select(DB::raw('SUM(CAST(amount_due AS DECIMAL(15,2))) as total'))
                ->first()->total ?? 0;
        } catch (\Exception $e) {
            $globalDebt = 0;
        }

        // 3. Ventes du Mois / Period: SUM(quantity * unit_price)
        $monthlySales = 0;
        try {
            $monthlySales = DB::table('stock_exits')
                ->whereNull('deleted_at')
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
                ->select(DB::raw('SUM(CAST(quantity AS DECIMAL(15,2)) * CAST(unit_price AS DECIMAL(15,2))) as total'))
                ->first()->total ?? 0;
        } catch (\Exception $e) {
            $monthlySales = 0;
        }

        // 4. Alertes Stock: products where stock <= 20
        $stockAlerts = 0;
        $healthyPercentage = 100;
        try {
            $stockAlerts = Product::where('stock', '<=', 20)->count();
            $totalProductsCount = Product::count();
            if ($totalProductsCount > 0) {
                $healthyPercentage = round((Product::where('stock', '>', 20)->count() / $totalProductsCount) * 100);
            }
        } catch (\Exception $e) {
            $stockAlerts = 0;
            $healthyPercentage = 100;
        }

        // 5. New KPIs for the period
        $totalRevenue = 0;
        $pendingDebt = 0;
        $bestSeller = null;

        try {
            $periodQuery = DB::table('stock_exits')
                ->whereNull('deleted_at')
                ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);

            $totalRevenue = (clone $periodQuery)
                ->where('payment_status', 'paid')
                ->select(DB::raw('SUM(CAST(quantity AS DECIMAL(15,2)) * CAST(unit_price AS DECIMAL(15,2))) as total'))
                ->first()->total ?? 0;

            $pendingDebt = (clone $periodQuery)
                ->whereIn('payment_status', ['unpaid', 'partial'])
                ->select(DB::raw('SUM(CAST(amount_due AS DECIMAL(15,2))) as total'))
                ->first()->total ?? 0;

            $bestSellerRow = (clone $periodQuery)
                ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('product_id')
                ->orderBy('total_qty', 'desc')
                ->first();

            if ($bestSellerRow) {
                $bestSeller = Product::withTrashed()->find($bestSellerRow->product_id);
                $bestSeller->total_qty = $bestSellerRow->total_qty;
            }
        } catch (\Exception $e) {
            $totalRevenue = 0;
            $pendingDebt = 0;
            $bestSeller = null;
        }

        // 6. Consommation par Chantier (Top 5)
        try {
            $chantierConsumption = collect(DB::table('stock_exits')
                ->join('chantiers', 'stock_exits.chantier_id', '=', 'chantiers.id')
                ->whereNull('stock_exits.deleted_at')
                ->select(
                    'chantiers.name as chantier_name',
                    DB::raw('SUM(CAST(stock_exits.quantity AS DECIMAL(15,2)) * CAST(stock_exits.unit_price AS DECIMAL(15,2))) as total_spent')
                )
                ->groupBy('chantiers.id', 'chantiers.name')
                ->orderBy('total_spent', 'desc')
                ->take(5)
                ->get());
        } catch (\Exception $e) {
            $chantierConsumption = collect([]);
        }

        // 7. CA par Segment
        try {
            $categoryDistribution = collect(DB::table('stock_exits')
                ->join('products', 'stock_exits.product_id', '=', 'products.id')
                ->whereNull('stock_exits.deleted_at')
                ->select(
                    'products.category as category_name',
                    DB::raw('SUM(CAST(stock_exits.quantity AS DECIMAL(15,2)) * CAST(stock_exits.unit_price AS DECIMAL(15,2))) as value')
                )
                ->groupBy('products.category')
                ->get());
        } catch (\Exception $e) {
            $categoryDistribution = collect([]);
        }

        // Chart data (last 7 days)
        $fechasGrafico = [];
        $entradasGrafico = [];
        $salidasGrafico = [];

        try {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $fechasGrafico[] = Carbon::parse($date)->format('d M');

                $entradasGrafico[] = DB::table('stock_entries')->whereDate('created_at', $date)->count();
                $salidasGrafico[] = DB::table('stock_exits')->whereNull('deleted_at')->whereDate('created_at', $date)->count();
            }
        } catch (\Exception $e) {
            $fechasGrafico = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $entradasGrafico = [0,0,0,0,0,0,0];
            $salidasGrafico = [0,0,0,0,0,0,0];
        }

        return view('dashboard', compact(
            'totalInventoryValue', 'globalDebt', 'monthlySales',
            'stockAlerts', 'healthyPercentage',
            'totalRevenue', 'pendingDebt', 'bestSeller',
            'chantierConsumption', 'categoryDistribution',
            'fechasGrafico', 'entradasGrafico', 'salidasGrafico',
            'startDate', 'endDate'
        ));
    }
}