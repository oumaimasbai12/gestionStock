<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Set up auth checks.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('storekeeper'))) {
                abort(403, 'Accès interdit.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the products (excluding soft-deleted ones).
     */
    public function index()
{
    $products = Product::paginate(10);
    return view('products.index', compact('products'));
}
    /**
     * Fonction Premium: Importation directe du fichier CSV BTP depuis l'interface
     */
    public function import(Request $request)
    {
        // 1. Validation dial l'fichier
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip dial l'ligne l'owl (les entêtes)
        fgetcsv($handle, 1000, ";");

        // Vider la table avant l'importation pour éviter les doublons
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $count = 0;
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            if (empty($data[0])) continue;

            try {
                Product::create([
                    'name'           => trim($data[0]),                     // Nom
                    'category'       => trim($data[2] ?? 'Divers'),         // Catégorie
                    'purchase_price' => floatval($data[4] ?? 0),      // Prix d'achat
                    'stock'          => intval($data[5] ?? 0),         // Stock
                    'description'    => trim($data[7] ?? null),             // Description
                ]);
                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }

        fclose($handle);

        return redirect()->route('products.index')->with('success', "Mabrouk! T-importaw {$count} produits dyal l'BTP b'najah! 🚀🏗️");
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Display the specified product details.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified product in the database.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'description'    => 'nullable|string',
        ]);

        $product->update($request->all());
        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Soft delete the specified product.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produit déplacé vers la poubelle.');
    }

    /**
     * Restore a soft-deleted product.
     */
    public function restore($id)
    {
        Product::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('products.index')->with('success', 'Produit restauré avec succès.');
    }

    /**
     * Permanently delete a product from the database.
     */
    public function forceDelete($id)
    {
        Product::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('products.index')->with('success', 'Produit supprimé définitivement.');
    }

    /**
     * Display a listing of the trashed products.
     */
    public function trash()
    {
        $products = Product::onlyTrashed()->paginate(10);
        return view('products.trash', compact('products'));
    }
}