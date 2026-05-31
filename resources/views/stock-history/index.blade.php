<x-app-layout>
    @section('title', 'Historique Stock')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="page-title">{{ __('Historique Stock') }}</h2>
                <p class="page-subtitle">Journal chronologique des entrées et sorties de stock.</p>
            </div>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="app-container space-y-6">

            @if(auth()->user()->hasRole('site_manager') && !auth()->user()->chantier_id)
                <div class="p-4 bg-accent/10 border-2 border-accent/30 text-ink text-sm rounded-md">
                    Aucun chantier assigné à votre compte. Contactez l'administrateur pour accéder à l'historique.
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="app-panel py-4">
                    <span class="text-xs font-bold text-ink/50 uppercase tracking-wider">Entrées</span>
                    <div class="text-xl font-black text-sage mt-1">{{ $stats['entries_count'] }}</div>
                    <span class="text-xs text-ink/50">+{{ number_format($stats['entries_qty'], 0, ',', ' ') }} unités</span>
                </div>
                <div class="app-panel py-4">
                    <span class="text-xs font-bold text-ink/50 uppercase tracking-wider">Sorties</span>
                    <div class="text-xl font-black text-accent mt-1">{{ $stats['exits_count'] }}</div>
                    <span class="text-xs text-ink/50">−{{ number_format($stats['exits_qty'], 0, ',', ' ') }} unités</span>
                </div>
                <div class="app-panel py-4 col-span-2">
                    <span class="text-xs font-bold text-ink/50 uppercase tracking-wider">Solde net (période filtrée)</span>
                    <div class="text-xl font-black text-ink mt-1">
                        {{ $stats['net_qty'] >= 0 ? '+' : '' }}{{ number_format($stats['net_qty'], 0, ',', ' ') }}
                        <span class="text-sm font-bold text-ink/60">unités</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="{{ route('stock-history.index') }}" class="app-panel flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-xs font-bold text-ink/70 uppercase tracking-wider mb-1">Du</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="app-input px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink/70 uppercase tracking-wider mb-1">Au</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="app-input px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink/70 uppercase tracking-wider mb-1">Type</label>
                    <select name="type" class="app-select px-3 py-2 text-sm">
                        <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Tous</option>
                        <option value="entry" {{ $type === 'entry' ? 'selected' : '' }}>Entrées</option>
                        <option value="exit" {{ $type === 'exit' ? 'selected' : '' }}>Sorties</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-ink/70 uppercase tracking-wider mb-1">Produit</label>
                    <select name="product_id" class="app-select px-3 py-2 text-sm min-w-[160px]">
                        <option value="">Tous les produits</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ (string)$productId === (string)$product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                @if(!auth()->user()->hasRole('site_manager'))
                    <div>
                        <label class="block text-xs font-bold text-ink/70 uppercase tracking-wider mb-1">Chantier</label>
                        <select name="chantier_id" class="app-select px-3 py-2 text-sm min-w-[160px]">
                            <option value="">Tous les chantiers</option>
                            @foreach($chantiers as $chantier)
                                <option value="{{ $chantier->id }}" {{ (string)$chantierId === (string)$chantier->id ? 'selected' : '' }}>{{ $chantier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <button type="submit" class="btn-primary text-cream px-4 py-2 text-sm">Filtrer</button>
                @if(request()->hasAny(['start_date', 'end_date', 'type', 'product_id', 'chantier_id']))
                    <a href="{{ route('stock-history.index') }}" class="btn-muted text-sm">Réinitialiser</a>
                @endif
            </form>

            <!-- Timeline table -->
            <div class="app-card">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="app-table-head">
                                <th class="py-4 px-6">Date</th>
                                <th class="py-4 px-6">Type</th>
                                <th class="py-4 px-6">Réf.</th>
                                <th class="py-4 px-6">Produit</th>
                                <th class="py-4 px-6">Chantier</th>
                                <th class="py-4 px-6">Tiers</th>
                                <th class="py-4 px-6 text-center">Qté</th>
                                <th class="py-4 px-6 text-right">Détail</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-ink/80 divide-y divide-ink/10">
                            @forelse($movements as $movement)
                                @php
                                    $isEntry = $movement->movement_type === 'entry';
                                    $record = $isEntry
                                        ? $entriesMap->get($movement->id)
                                        : $exitsMap->get($movement->id);
                                @endphp
                                <tr class="app-table-row">
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <div class="font-semibold text-ink">{{ \Carbon\Carbon::parse($movement->created_at)->format('d/m/Y') }}</div>
                                        <div class="text-[11px] text-ink/50">{{ \Carbon\Carbon::parse($movement->created_at)->format('H:i') }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($isEntry)
                                            <span class="app-badge bg-sage/15 border-sage/30 text-sage">Entrée</span>
                                        @else
                                            <span class="app-badge">Sortie</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 font-medium text-ink">{{ $movement->document ?? '—' }}</td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-ink">{{ $record?->product?->name ?? '—' }}</div>
                                        <div class="text-xs text-ink/50">{{ $record?->product?->category ?? '' }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-ink/70">{{ $record?->chantier?->name ?? '—' }}</td>
                                    <td class="py-4 px-6 text-ink/70">
                                        @if($isEntry)
                                            {{ $record?->supplier?->name ?? '—' }}
                                            <span class="block text-[10px] text-ink/40 uppercase">Fournisseur</span>
                                        @else
                                            {{ $record?->customer?->name ?? '—' }}
                                            <span class="block text-[10px] text-ink/40 uppercase">Client</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center font-bold {{ $isEntry ? 'text-sage' : 'text-accent' }}">
                                        {{ $isEntry ? '+' : '−' }}{{ $movement->quantity }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        @if($record)
                                            <a href="{{ $isEntry ? route('entries.show', $record) : route('exits.show', $record) }}"
                                               class="btn-muted text-xs px-3 py-1.5">Voir</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-12 text-center text-ink/50">
                                        Aucun mouvement trouvé pour les filtres sélectionnés.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($movements->hasPages())
                    <div class="px-6 py-4 bg-sage/10 border-t border-ink/15">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
