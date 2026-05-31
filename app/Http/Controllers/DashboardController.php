<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockExit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        [$startDate, $endDate] = $this->resolveDateRange($request);
        $metrics = $this->buildDashboardMetrics($startDate, $endDate);

        return view('dashboard', array_merge($metrics, compact('startDate', 'endDate')));
    }

    public function exportBiReport(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        [$startDate, $endDate] = $this->resolveDateRange($request);
        $metrics = $this->buildDashboardMetrics($startDate, $endDate);

        $filename = 'rapport-bi-' . $startDate . '_' . $endDate . '.pdf';

        return Pdf::loadView('pdf.bi-report', array_merge($metrics, [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now(),
        ]))->download($filename);
    }

    public function exportFactures(Request $request)
    {
        if ($redirect = $this->adminGuard()) {
            return $redirect;
        }

        [$startDate, $endDate] = $this->resolveDateRange($request);

        $exits = StockExit::with(['customer', 'product', 'chantier'])
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAmount = $exits->sum(fn ($exit) => $exit->quantity * $exit->unit_price);
        $totalPaid = $exits->sum('paid_amount');
        $totalDue = $exits->sum('amount_due');

        $filename = 'factures-' . $startDate . '_' . $endDate . '.pdf';

        return Pdf::loadView('pdf.factures', [
            'exits' => $exits,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'generatedAt' => now(),
            'totalAmount' => $totalAmount,
            'totalPaid' => $totalPaid,
            'totalDue' => $totalDue,
        ])->download($filename);
    }

    private function adminGuard(): ?RedirectResponse
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Accès interdit.');
        }

        if ($user->hasRole('admin')) {
            return null;
        }

        if ($user->hasRole('storekeeper')) {
            return redirect()->route('entries.index');
        }

        if ($user->hasRole('site_manager')) {
            return redirect()->route('exits.index');
        }

        abort(403, 'Accès interdit.');
    }

    private function resolveDateRange(Request $request): array
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        return [$startDate, $endDate];
    }

    private function buildDashboardMetrics(string $startDate, string $endDate): array
    {
        $totalInventoryValue = 0;
        try {
            $totalInventoryValue = Product::select(
                DB::raw('SUM(CAST(stock AS DECIMAL(15,2)) * CAST(purchase_price AS DECIMAL(15,2))) as total')
            )->first()->total ?? 0;
        } catch (\Exception $e) {
            $totalInventoryValue = 0;
        }

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

        $stockAlerts = 0;
        $healthyPercentage = 100;
        try {
            $stockAlerts = Product::whereColumn('stock', '<=', 'alert_quantity')->count();
            $totalProductsCount = Product::count();
            if ($totalProductsCount > 0) {
                $healthyPercentage = round((Product::whereColumn('stock', '>', 'alert_quantity')->count() / $totalProductsCount) * 100);
            }
        } catch (\Exception $e) {
            $stockAlerts = 0;
            $healthyPercentage = 100;
        }

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
                if ($bestSeller) {
                    $bestSeller->total_qty = $bestSellerRow->total_qty;
                }
            }
        } catch (\Exception $e) {
            $totalRevenue = 0;
            $pendingDebt = 0;
            $bestSeller = null;
        }

        try {
            $chantierConsumption = collect(DB::table('stock_exits')
                ->leftJoin('chantiers', 'stock_exits.chantier_id', '=', 'chantiers.id')
                ->whereNull('stock_exits.deleted_at')
                ->whereBetween('stock_exits.created_at', [$startDate, $endDate . ' 23:59:59'])
                ->select(
                    DB::raw("COALESCE(chantiers.name, 'Non affecté') as chantier_name"),
                    DB::raw('SUM(CAST(stock_exits.quantity AS DECIMAL(15,2)) * CAST(stock_exits.unit_price AS DECIMAL(15,2))) as total_spent')
                )
                ->groupBy('chantiers.id', 'chantiers.name')
                ->orderBy('total_spent', 'desc')
                ->take(5)
                ->get());
        } catch (\Exception $e) {
            $chantierConsumption = collect([]);
        }

        try {
            $categoryDistribution = collect(DB::table('stock_exits')
                ->join('products', 'stock_exits.product_id', '=', 'products.id')
                ->whereNull('stock_exits.deleted_at')
                ->whereBetween('stock_exits.created_at', [$startDate, $endDate . ' 23:59:59'])
                ->select(
                    'products.category as category_name',
                    DB::raw('SUM(CAST(stock_exits.quantity AS DECIMAL(15,2)) * CAST(stock_exits.unit_price AS DECIMAL(15,2))) as value')
                )
                ->groupBy('products.category')
                ->orderBy('value', 'desc')
                ->get());
        } catch (\Exception $e) {
            $categoryDistribution = collect([]);
        }

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
            $fechasGrafico = ['—', '—', '—', '—', '—', '—', '—'];
            $entradasGrafico = [0, 0, 0, 0, 0, 0, 0];
            $salidasGrafico = [0, 0, 0, 0, 0, 0, 0];
        }

        return compact(
            'totalInventoryValue', 'globalDebt', 'monthlySales',
            'stockAlerts', 'healthyPercentage',
            'totalRevenue', 'pendingDebt', 'bestSeller',
            'chantierConsumption', 'categoryDistribution',
            'fechasGrafico', 'entradasGrafico', 'salidasGrafico'
        );
    }
}
