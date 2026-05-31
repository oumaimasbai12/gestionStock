<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Access Control: Non-admins cannot access the dashboard
        if (!auth()->user()->hasRole('admin')) {
            if (auth()->user()->hasRole('storekeeper')) {
                return redirect()->route('products.index');
            } elseif (auth()->user()->hasRole('site_manager')) {
                return redirect()->route('exits.index');
            }
            abort(403, "Accès interdit.");
        }

        $totalProductos = Product::count();
        $totalProveedores = Supplier::count();
        $totalClientes = Customer::count();
        $totalUsuarios = User::count();
        
        try {
            $totalEntradasStock = DB::table('stock_entries')->count();
            $totalSalidasStock = DB::table('stock_exits')->count();
        } catch (\Exception $e) {
            $totalEntradasStock = 0;
            $totalSalidasStock = 0;
        }

        // 1. Valeur du Stock: SUM(stock * purchase_price)
        $totalInventoryValue = 0;
        try {
            $totalInventoryValue = Product::select(
                DB::raw('SUM(CAST(stock AS DECIMAL(15,2)) * CAST(purchase_price AS DECIMAL(15,2))) as total')
            )->first()->total ?? 0;
        } catch (\Exception $e) {
            $totalInventoryValue = 0;
        }

        // 2. Solde Impayé: SUM((quantity * unit_price) - paid_amount)
        $unpaidBalance = 0;
        try {
            $unpaidBalance = DB::table('stock_exits')
                ->whereNull('deleted_at')
                ->select(DB::raw('SUM((CAST(quantity AS DECIMAL(15,2)) * CAST(unit_price AS DECIMAL(15,2))) - CAST(paid_amount AS DECIMAL(15,2))) as total'))
                ->first()->total ?? 0;
        } catch (\Exception $e) {
            $unpaidBalance = 0;
        }

        // 3. Ventes du Mois: SUM(quantity * unit_price)
        $monthlySales = 0;
        try {
            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;
            $monthlySales = DB::table('stock_exits')
                ->whereNull('deleted_at')
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->select(DB::raw('SUM(CAST(quantity AS DECIMAL(15,2)) * CAST(unit_price AS DECIMAL(15,2))) as total'))
                ->first()->total ?? 0;
        } catch (\Exception $e) {
            $monthlySales = 0;
        }

        // 4. Alertes Stock: count of products where stock <= 20
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

        // 5. Consommation par Chantier (Top 5 chantiers - valeur des ventes (MAD))
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

        // 6. CA par Segment (Répartition du chiffre d'affaires par catégorie de produit)
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
            'totalProductos', 'totalProveedores', 'totalClientes', 'totalUsuarios',
            'totalEntradasStock', 'totalSalidasStock', 'totalInventoryValue',
            'unpaidBalance', 'monthlySales', 'stockAlerts', 'healthyPercentage',
            'chantierConsumption', 'categoryDistribution',
            'fechasGrafico', 'entradasGrafico', 'salidasGrafico'
        ));
    }
}