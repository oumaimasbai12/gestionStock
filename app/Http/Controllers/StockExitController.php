<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\StockExit;
use App\Models\Product;
use App\Models\Chantier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockExitController extends Controller
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
     * Display a listing of stock exits.
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            // Site Managers only see exits related to their assigned Chantier
            $exits = StockExit::withoutTrashed()
                ->where('chantier_id', $user->chantier_id)
                ->paginate(10);
        } else {
            // Admin & Storekeeper see all exits
            $exits = StockExit::withoutTrashed()->paginate(10);
        }
        return view('exits.index', compact('exits'));
    }

    /**
     * Show the form for creating a new stock exit.
     */
    public function create()
    {
        $products = Product::all();
        $customers = Customer::all();
        $chantiers = Chantier::all();
        return view('exits.create', compact('products', 'customers', 'chantiers'));
    }

    /**
     * Store a newly created stock exit in the database and decrease the product stock.
     * Validates that the exit quantity does not exceed the available product stock.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'customer_id'    => 'nullable|exists:customers,id',
            'chantier_id'    => 'nullable|exists:chantiers,id',
            'quantity'       => 'required|integer|min:1',
            'unit_price'     => 'required|numeric|min:0',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_status' => 'required|string|in:paid,partial,unpaid',
            'document'       => 'nullable|string|max:255',
        ]);

        $chantierId = $request->chantier_id;
        if ($user->hasRole('site_manager')) {
            // Force the exit to be assigned to the Site Manager's chantier
            $chantierId = $user->chantier_id;
        }

        $product = Product::findOrFail($request->product_id);

        // Check if there is enough stock for the exit
        if ($request->quantity > $product->stock) {
            return redirect()->back()
                ->withErrors(['quantity' => 'La quantité de sortie excède le stock de produit disponible.'])
                ->withInput();
        }

        // Decrease the product's stock
        $product->stock -= $request->quantity;
        $product->save();

        $totalDue = $request->quantity * $request->unit_price;
        $amountDue = max($totalDue - $request->paid_amount, 0);

        StockExit::create([
            'product_id'     => $request->product_id,
            'customer_id'    => $request->customer_id,
            'chantier_id'    => $chantierId,
            'quantity'       => $request->quantity,
            'unit_price'     => $request->unit_price,
            'paid_amount'    => $request->paid_amount,
            'amount_due'     => $amountDue,
            'payment_status' => $request->payment_status,
            'document'       => $request->document,
        ]);

        return redirect()->route('exits.index')->with('success', 'Bon de sortie de stock créé avec succès.');
    }

    /**
     * Display the specified stock exit.
     */
    public function show(StockExit $exit)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager') && $exit->chantier_id !== $user->chantier_id) {
            abort(403, 'Accès interdit.');
        }

        return view('exits.show', compact('exit'));
    }

    public function edit(StockExit $exit)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager') && $exit->chantier_id !== $user->chantier_id) {
            abort(403, 'Accès interdit.');
        }

        $products = Product::all();
        $customers = Customer::all();
        $chantiers = Chantier::all();
        return view('exits.edit', compact('exit', 'products', 'customers', 'chantiers'));
    }

    public function update(Request $request, StockExit $exit)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        $request->validate([
            'product_id'     => 'required|exists:products,id',
            'customer_id'    => 'nullable|exists:customers,id',
            'chantier_id'    => 'nullable|exists:chantiers,id',
            'quantity'       => 'required|integer|min:1',
            'unit_price'     => 'required|numeric|min:0',
            'paid_amount'    => 'required|numeric|min:0',
            'payment_status' => 'required|string|in:paid,partial,unpaid',
            'document'       => 'nullable|string|max:255',
        ]);

        $product = Product::findOrFail($request->product_id);
        $oldQuantity = $exit->quantity;
        $diff = $request->quantity - $oldQuantity;

        if ($diff > 0 && $diff > $product->stock) {
            return redirect()->back()
                ->withErrors(['quantity' => 'Stock insuffisant pour augmenter la quantité de sortie.'])
                ->withInput();
        }

        $product->stock -= $diff;
        $product->save();

        $totalDue = $request->quantity * $request->unit_price;
        $amountDue = max($totalDue - $request->paid_amount, 0);

        $exit->update([
            'product_id'     => $request->product_id,
            'customer_id'    => $request->customer_id,
            'chantier_id'    => $request->chantier_id,
            'quantity'       => $request->quantity,
            'unit_price'     => $request->unit_price,
            'paid_amount'    => $request->paid_amount,
            'amount_due'     => $amountDue,
            'payment_status' => $request->payment_status,
            'document'       => $request->document,
        ]);

        return redirect()->route('exits.index')->with('success', 'Bon de sortie mis à jour avec succès.');
    }

    /**
     * Soft delete the specified stock exit.
     */
    public function destroy(StockExit $exit)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        $exit->delete();
        return redirect()->route('exits.index')->with('success', 'Bon de sortie archivé avec succès.');
    }

    /**
     * Display a listing of trashed stock exits.
     */
    public function trash()
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        $exits = StockExit::onlyTrashed()->paginate(10);
        return view('exits.trash', compact('exits'));
    }

    /**
     * Restore a soft-deleted stock exit.
     */
    public function restore($id)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        StockExit::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('exits.index')->with('success', 'Bon de sortie restauré avec succès.');
    }

    /**
     * Display pending (unpaid/partial) stock exits.
     */
    public function pending()
    {
        $exits = StockExit::with(['customer', 'product'])
            ->whereNull('deleted_at')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('exits.pending', compact('exits'));
    }

    /**
     * Mark a stock exit as fully paid.
     */
    public function markAsPaid(StockExit $exit)
    {
        $totalDue = $exit->quantity * $exit->unit_price;

        $exit->update([
            'payment_status' => 'paid',
            'paid_amount'    => $totalDue,
            'amount_due'     => 0,
        ]);

        return redirect()->back()->with('success', 'Vente marquée comme payée.');
    }

    /**
     * Permanently delete a stock exit from the database.
     */
    public function forceDelete($id)
    {
        $user = auth()->user();
        if ($user->hasRole('site_manager')) {
            abort(403, 'Action non autorisée pour votre rôle.');
        }

        StockExit::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('exits.index')->with('success', 'Bon de sortie supprimé définitivement.');
    }
}
