<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\StockExit;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->hasRole('admin')) {
                abort(403, 'Accès réservé aux administrateurs.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $customers = Customer::withoutTrashed()->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_id' => 'required|string|max:255|unique:customers',
            'customer_type' => 'required|in:individual,artisan,entreprise',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:customers',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'ice' => 'required_if:customer_type,entreprise|nullable|string|max:255',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Client créé avec succès.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'document_id' => 'required|string|max:255|unique:customers,document_id,'.$customer->id,
            'customer_type' => 'required|in:individual,artisan,entreprise',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:customers,email,'.$customer->id,
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'ice' => 'required_if:customer_type,entreprise|nullable|string|max:255',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Client supprimé avec succès.');
    }

    public function restore($id)
    {
        Customer::withTrashed()->findOrFail($id)->restore();
        return redirect()->route('customers.index')->with('success', 'Client restauré avec succès.');
    }

    public function forceDelete($id)
    {
        Customer::withTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('customers.index')->with('success', 'Client supprimé définitivement.');
    }

    public function trash()
    {
        $customers = Customer::onlyTrashed()->paginate(10);
        return view('customers.trash', compact('customers'));
    }

    public function salesHistory(Customer $customer)
    {
        $exits = StockExit::where('customer_id', $customer->id)
            ->with('product', 'chantier')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customers.sales', compact('customer', 'exits'));
    }
}
