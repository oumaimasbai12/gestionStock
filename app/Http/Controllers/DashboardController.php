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

        $totalInventoryValue = 0;
        try {
            $totalInventoryValue = Product::select(
                DB::raw('SUM(CAST(quantity AS DECIMAL(10,2)) * CAST(price AS DECIMAL(10,2))) as total')
            )->first()->total ?? 0;
        } catch (\Exception $e) {
            try {
                $totalInventoryValue = Product::select(
                    DB::raw('SUM(CAST(stock AS DECIMAL(10,2)) * CAST(purchase_price AS DECIMAL(10,2))) as total')
                )->first()->total ?? 0;
            } catch (\Exception $ex) {
                $totalInventoryValue = 0;
            }
        }

        try {
            $chantierConsumption = collect(DB::table('chantier_product')
                ->join('chantiers', 'chantier_product.chantier_id', '=', 'chantiers.id')
                ->join('products', 'chantier_product.product_id', '=', 'products.id')
                ->select(
                    'chantiers.name as chantier_name', 
                    DB::raw('SUM(CAST(chantier_product.quantity_consumed AS DECIMAL(10,2)) * CAST(products.price AS DECIMAL(10,2))) as total_spent')
                )
                ->groupBy('chantiers.id', 'chantiers.name')
                ->orderBy('total_spent', 'desc')
                ->get());
        } catch (\Exception $e) {
            $chantierConsumption = collect([]);
        }

        try {
            $categoryDistribution = collect(Product::select(
                    'category as category_name', 
                    DB::raw('SUM(CAST(quantity AS DECIMAL(10,2)) * CAST(price AS DECIMAL(10,2))) as value')
                )
                ->groupBy('category')
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
                $salidasGrafico[] = DB::table('stock_exits')->whereDate('created_at', $date)->count();
            }
        } catch (\Exception $e) {
            $fechasGrafico = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $entradasGrafico = [0,0,0,0,0,0,0];
            $salidasGrafico = [0,0,0,0,0,0,0];
        }

        return view('dashboard', compact(
            'totalProductos', 'totalProveedores', 'totalClientes', 'totalUsuarios',
            'totalEntradasStock', 'totalSalidasStock', 'totalInventoryValue',
            'chantierConsumption', 'categoryDistribution',
            'fechasGrafico', 'entradasGrafico', 'salidasGrafico'
        ));
    }
}