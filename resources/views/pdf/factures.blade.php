@extends('pdf.layout')

@section('title', 'Factures')

@section('meta')
    Relevé des factures — Période du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
    — {{ $exits->count() }} document(s)
@endsection

@section('content')
    <table class="data">
        <thead>
            <tr>
                <th>Réf.</th>
                <th>Date</th>
                <th>Client</th>
                <th>Produit</th>
                <th class="text-center">Qté</th>
                <th class="text-right">P.U.</th>
                <th class="text-right">Total</th>
                <th class="text-right">Payé</th>
                <th class="text-right">Dû</th>
                <th class="text-center">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($exits as $exit)
                @php
                    $lineTotal = $exit->quantity * $exit->unit_price;
                    $statusLabel = match($exit->payment_status) {
                        'paid' => 'Payé',
                        'partial' => 'Partiel',
                        default => 'Impayé',
                    };
                @endphp
                <tr>
                    <td>{{ $exit->document ?? 'N/A' }}</td>
                    <td>{{ $exit->created_at->format('d/m/Y') }}</td>
                    <td>{{ optional($exit->customer)->name ?? '—' }}</td>
                    <td>{{ optional($exit->product)->name ?? '—' }}</td>
                    <td class="text-center">{{ $exit->quantity }}</td>
                    <td class="text-right">{{ number_format($exit->unit_price, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($lineTotal, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($exit->paid_amount, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($exit->amount_due, 2, ',', ' ') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $exit->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Aucune facture sur cette période.</td>
                </tr>
            @endforelse
            @if($exits->isNotEmpty())
                <tr class="totals-row">
                    <td colspan="6" class="text-right">TOTAUX</td>
                    <td class="text-right">{{ number_format($totalAmount, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($totalPaid, 2, ',', ' ') }}</td>
                    <td class="text-right">{{ number_format($totalDue, 2, ',', ' ') }}</td>
                    <td></td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
