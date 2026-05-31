<?php

namespace App\Http\Controllers;

use App\Models\StockEntry;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Chantier;
use Illuminate\Http\Request;

class StockEntryController extends Controller
{
    /**
     * Set up auth checks.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || (!$user->hasRole('admin') && !$user->hasRole('storekeeper') && !$user->hasRole('site_manager'))) {
                abort(403, 'Accès interdit.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of stock entries.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            // Site Managers only see entries related to their assigned Chantier
            $entries = StockEntry::withoutTrashed()
                ->where('chantier_id', $user->chantier_id)
                ->paginate(10);
        } else {
            // Admin & Storekeeper see all entries
            $entries = StockEntry::withoutTrashed()->paginate(10);
        }
        return view('entries.index', compact('entries'));
    }

    /**
     * Show the form for creating a new stock entry.
     */
    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        $chantiers = Chantier::all();
        return view('entries.create', compact('products', 'suppliers', 'chantiers'));
    }

    /**
     * Store a newly created stock entry in the database and increase the product stock.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'chantier_id' => 'nullable|exists:chantiers,id',
            'quantity'    => 'required|integer|min:1',
            'document'    => 'nullable|string|max:255',
        ]);

        $chantierId = $request->chantier_id;
        if ($user->hasRole('site_manager')) {
            // Force the entry to be assigned to the Site Manager's chantier
            $chantierId = $user->chantier_id;
        }

        // Increase the product's stock
        $product = Product::findOrFail($request->product_id);
        $product->stock += $request->quantity;
        $product->save();

        StockEntry::create([
            'product_id'  => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'chantier_id' => $chantierId,
            'quantity'    => $request->quantity,
            'document'    => $request->document,
        ]);

        return redirect()->route('entries.index')->with('success', 'Bon d\'entrée de stock créé avec succès.');
    }

    /**
     * Display the specified stock entry.
     */
    public function show(StockEntry $entry)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager') && $entry->chantier_id !== $user->chantier_id) {
            abort(403, 'Accès interdit.');
        }

        return view('entries.show', compact('entry'));
    }

    public function edit(StockEntry $entry)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager') && $entry->chantier_id !== $user->chantier_id) {
            abort(403, 'Accès interdit.');
        }

        $products = Product::all();
        $suppliers = Supplier::all();
        $chantiers = Chantier::all();
        return view('entries.edit', compact('entry', 'products', 'suppliers', 'chantiers'));
    }

    public function update(Request $request, StockEntry $entry)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'chantier_id' => 'nullable|exists:chantiers,id',
            'quantity'    => 'required|integer|min:1',
            'document'    => 'nullable|string|max:255',
        ]);

        $oldQuantity = $entry->quantity;
        $diff = $request->quantity - $oldQuantity;

        $product = Product::findOrFail($request->product_id);
        $product->stock += $diff;
        $product->save();

        $entry->update([
            'product_id'  => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'chantier_id' => $request->chantier_id,
            'quantity'    => $request->quantity,
            'document'    => $request->document,
        ]);

        return redirect()->route('entries.index')->with('success', 'Bon d\'entrée mis à jour avec succès.');
    }

    /**
     * Soft delete the specified stock entry.
     */
    public function destroy(StockEntry $entry)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        $entry->delete();
        return redirect()->route('entries.index')->with('success', 'Bon d\'entrée archivé avec succès.');
    }

    /**
     * Display a listing of trashed stock entries.
     */
    public function trash()
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        $entries = StockEntry::onlyTrashed()->paginate(10);
        return view('entries.trash', compact('entries'));
    }

    /**
     * Restore a soft-deleted stock entry.
     */
    public function restore($id)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        StockEntry::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('entries.index')->with('success', 'Bon d\'entrée restauré avec succès.');
    }

    /**
     * Permanently delete a stock entry from the database.
     */
    public function forceDelete($id)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        StockEntry::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('entries.index')->with('success', 'Bon d\'entrée supprimé définitivement.');
    }
}
