@extends('pdf.layout')

@section('title', 'Rapport BI')

@section('meta')
    Rapport BI — Période du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
@endsection

@section('content')
    <div class="section-title">Indicateurs clés</div>
    <table class="kpi-grid">
        <tr>
            <td>
                <div class="kpi-label">Valeur du stock</div>
                <div class="kpi-value">{{ number_format($totalInventoryValue, 0, ',', ' ') }} MAD</div>
                <div class="kpi-sub">Valorisation au prix d'achat</div>
            </td>
            <td>
                <div class="kpi-label">Dette globale</div>
                <div class="kpi-value">{{ number_format($globalDebt, 0, ',', ' ') }} MAD</div>
                <div class="kpi-sub">Impayés (toutes périodes)</div>
            </td>
            <td>
                <div class="kpi-label">Ventes période</div>
                <div class="kpi-value">{{ number_format($monthlySales, 0, ',', ' ') }} MAD</div>
                <div class="kpi-sub">Chiffre d'affaires total</div>
            </td>
            <td>
                <div class="kpi-label">Alertes stock</div>
                <div class="kpi-value">{{ $stockAlerts }} produits</div>
                <div class="kpi-sub">{{ $healthyPercentage }}% stock sain</div>
            </td>
        </tr>
    </table>

    <table class="kpi-grid">
        <tr>
            <td>
                <div class="kpi-label">Revenu encaissé</div>
                <div class="kpi-value">{{ number_format($totalRevenue, 0, ',', ' ') }} MAD</div>
                <div class="kpi-sub">Ventes payées sur la période</div>
            </td>
            <td>
                <div class="kpi-label">Créances impayées</div>
                <div class="kpi-value">{{ number_format($pendingDebt, 0, ',', ' ') }} MAD</div>
                <div class="kpi-sub">Sur la période sélectionnée</div>
            </td>
            <td colspan="2">
                <div class="kpi-label">Meilleure vente</div>
                <div class="kpi-value">{{ $bestSeller ? $bestSeller->name : '—' }}</div>
                <div class="kpi-sub">
                    @if($bestSeller)
                        {{ $bestSeller->total_qty }} unités vendues
                    @else
                        Aucune vente sur la période
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">Consommation par chantier (Top 5)</div>
    <table class="data">
        <thead>
            <tr>
                <th>Chantier</th>
                <th class="text-right">Montant (MAD)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chantierConsumption as $row)
                <tr>
                    <td>{{ $row->chantier_name }}</td>
                    <td class="text-right">{{ number_format($row->total_spent, 2, ',', ' ') }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="text-center">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">CA par segment</div>
    <table class="data">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th class="text-right">Montant (MAD)</th>
                <th class="text-right">Part</th>
            </tr>
        </thead>
        <tbody>
            @php $caTotal = $categoryDistribution->sum('value'); @endphp
            @forelse($categoryDistribution as $row)
                <tr>
                    <td>{{ $row->category_name }}</td>
                    <td class="text-right">{{ number_format($row->value, 2, ',', ' ') }}</td>
                    <td class="text-right">
                        {{ $caTotal > 0 ? number_format(($row->value / $caTotal) * 100, 1, ',', ' ') : 0 }}%
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">Aucune donnée</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Mouvements de stock (7 derniers jours)</div>
    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-center">Entrées</th>
                <th class="text-center">Sorties</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fechasGrafico as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="text-center">{{ $entradasGrafico[$i] ?? 0 }}</td>
                    <td class="text-center">{{ $salidasGrafico[$i] ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
