<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockHistoryController extends Controller
{
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

    public function index(Request $request)
    {
        $user = auth()->user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type', 'all');
        $productId = $request->input('product_id');
        $chantierId = $request->input('chantier_id');

        if ($user->hasRole('site_manager')) {
            $chantierId = $user->chantier_id;
            if (!$chantierId) {
                $chantierId = -1;
            }
        }

        $entriesQuery = DB::table('stock_entries')
            ->select(
                'id',
                DB::raw("'entry' as movement_type"),
                'document',
                'product_id',
                'quantity',
                'chantier_id',
                'created_at'
            )
            ->whereNull('deleted_at');

        $exitsQuery = DB::table('stock_exits')
            ->select(
                'id',
                DB::raw("'exit' as movement_type"),
                'document',
                'product_id',
                'quantity',
                'chantier_id',
                'created_at'
            )
            ->whereNull('deleted_at');

        if ($startDate) {
            $entriesQuery->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
            $exitsQuery->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $entriesQuery->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
            $exitsQuery->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        if ($productId) {
            $entriesQuery->where('product_id', $productId);
            $exitsQuery->where('product_id', $productId);
        }

        if ($chantierId) {
            $entriesQuery->where('chantier_id', $chantierId);
            $exitsQuery->where('chantier_id', $chantierId);
        }

        if ($type === 'entry') {
            $union = $entriesQuery;
        } elseif ($type === 'exit') {
            $union = $exitsQuery;
        } else {
            $union = $entriesQuery->unionAll($exitsQuery);
        }

        $movements = DB::query()
            ->fromSub($union, 'movements')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $entryIds = collect($movements->items())->where('movement_type', 'entry')->pluck('id');
        $exitIds = collect($movements->items())->where('movement_type', 'exit')->pluck('id');

        $entriesMap = StockEntry::with(['product', 'supplier', 'chantier'])
            ->whereIn('id', $entryIds)
            ->get()
            ->keyBy('id');

        $exitsMap = StockExit::with(['product', 'customer', 'chantier'])
            ->whereIn('id', $exitIds)
            ->get()
            ->keyBy('id');

        $stats = $this->buildStats($user, $startDate, $endDate, $productId, $chantierId, $type);

        $products = Product::orderBy('name')->get(['id', 'name']);
        $chantiers = Chantier::orderBy('name')->get(['id', 'name']);

        return view('stock-history.index', compact(
            'movements',
            'entriesMap',
            'exitsMap',
            'products',
            'chantiers',
            'startDate',
            'endDate',
            'type',
            'productId',
            'chantierId',
            'stats'
        ));
    }

    private function buildStats($user, ?string $startDate, ?string $endDate, ?int $productId, ?int $chantierId, string $type): array
    {
        $entriesCount = 0;
        $exitsCount = 0;
        $entriesQty = 0;
        $exitsQty = 0;

        if ($type !== 'exit') {
            $eq = StockEntry::query()->whereNull('deleted_at');
            $this->applyFilters($eq, $startDate, $endDate, $productId, $chantierId, $user);
            $entriesCount = (clone $eq)->count();
            $entriesQty = (clone $eq)->sum('quantity');
        }

        if ($type !== 'entry') {
            $xq = StockExit::query()->whereNull('deleted_at');
            $this->applyFilters($xq, $startDate, $endDate, $productId, $chantierId, $user);
            $exitsCount = (clone $xq)->count();
            $exitsQty = (clone $xq)->sum('quantity');
        }

        return [
            'entries_count' => $entriesCount,
            'exits_count' => $exitsCount,
            'entries_qty' => $entriesQty,
            'exits_qty' => $exitsQty,
            'net_qty' => $entriesQty - $exitsQty,
        ];
    }

    private function applyFilters($query, ?string $startDate, ?string $endDate, ?int $productId, ?int $chantierId, $user): void
    {
        if ($user->hasRole('site_manager')) {
            $query->where('chantier_id', $user->chantier_id);
        } elseif ($chantierId) {
            $query->where('chantier_id', $chantierId);
        }

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }

        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        if ($productId) {
            $query->where('product_id', $productId);
        }
    }
}
