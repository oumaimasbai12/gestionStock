<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\StockExitController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 1. Les routes accessibles par n'importe quel utilisateur connecté (auth)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    // L'dashboard principal de Business Intelligence
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/bi', [DashboardController::class, 'exportBiReport'])->name('dashboard.export.bi');
    Route::get('/dashboard/export/factures', [DashboardController::class, 'exportFactures'])->name('dashboard.export.factures');

});

// 2. Les routes gérées par contrôleurs avec contrôles d'accès précis
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {


    // Users Management
    Route::get('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::get('users/trash', [UserController::class, 'trash'])->name('users.trash');
    Route::resource('users', UserController::class);

    // Customers Management
    Route::get('customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    Route::delete('customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.forceDelete');
    Route::get('customers/trash', [CustomerController::class, 'trash'])->name('customers.trash');
    Route::get('customers/{customer}/sales', [CustomerController::class, 'salesHistory'])->name('customers.sales');
    Route::resource('customers', CustomerController::class);

    // Products Management (M9ada: l'import t-7et houwa l'owl 9bel l'resource!)
    // Products Management
    Route::post('products/import', 'App\Http\Controllers\ProductController@import')->name('products.import');
    Route::get('products/{id}/restore', 'App\Http\Controllers\ProductController@restore')->name('products.restore');
    Route::delete('products/{id}/force-delete', 'App\Http\Controllers\ProductController@forceDelete')->name('products.forceDelete');
    Route::get('products/trash', 'App\Http\Controllers\ProductController@trash')->name('products.trash');
    Route::resource('products', App\Http\Controllers\ProductController::class);

    // Suppliers Management
    Route::get('suppliers/{id}/restore', [SupplierController::class, 'restore'])->name('suppliers.restore');
    Route::delete('suppliers/{id}/force-delete', [SupplierController::class, 'forceDelete'])->name('suppliers.forceDelete');
    Route::get('suppliers/trash', [SupplierController::class, 'trash'])->name('suppliers.trash');
    Route::resource('suppliers', SupplierController::class);

    // Chantiers (construction sites)
    Route::resource('chantiers', App\Http\Controllers\ChantierController::class)->except(['show']);

    // Entries Management
    Route::get('entries/{id}/restore', [StockEntryController::class, 'restore'])->name('entries.restore');
    Route::delete('entries/{id}/force-delete', [StockEntryController::class, 'forceDelete'])->name('entries.forceDelete');
    Route::get('entries/trash', [StockEntryController::class, 'trash'])->name('entries.trash');
    Route::resource('entries', StockEntryController::class);

    // Exits Management
    Route::get('exits/{id}/restore', [StockExitController::class, 'restore'])->name('exits.restore');
    Route::delete('exits/{id}/force-delete', [StockExitController::class, 'forceDelete'])->name('exits.forceDelete');
    Route::get('exits/trash', [StockExitController::class, 'trash'])->name('exits.trash');
    Route::get('exits/pending', [StockExitController::class, 'pending'])->name('exits.pending');
    Route::patch('exits/{exit}/mark-paid', [StockExitController::class, 'markAsPaid'])->name('exits.mark-paid');
    Route::resource('exits', StockExitController::class);

    Route::get('historique-stock', [StockHistoryController::class, 'index'])->name('stock-history.index');

    // Notifications
    Route::patch('notifications/{id}/read', function ($id) {
        $notification = auth()->user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.read');

    Route::post('notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notifications.read-all');

});