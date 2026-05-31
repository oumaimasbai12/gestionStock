<x-app-layout>
    @section('title', 'Tableau de Bord')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tableau de Bord BI') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Distribution BTP — {{ now()->format('d/m/Y à H:i') }}
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('exits.create') }}" class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Ajout vente</span>
                </a>
                <a href="{{ route('exits.pending') }}" class="inline-flex items-center bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 px-5 py-2.5 rounded-xl text-sm font-semibold transition space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Impayés</span>
                </a>

            </div>
        </div>
    </x-slot>

    @php
    function formatKmAD($val) {
        if ($val >= 1000) {
            return number_format($val / 1000, 1, ',', ' ') . ' k MAD';
        }
        return number_format($val, 0, ',', ' ') . ' MAD';
    }
    @endphp

    <div class="py-8 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Date Filter -->
            <form method="GET" action="{{ route('dashboard') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100/80 flex items-end gap-4 flex-wrap">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Du</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Au</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400 outline-none">
                </div>
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition">
                    Filtrer
                </button>
                @if(request()->hasAny(['start_date', 'end_date']))
                    <a href="{{ route('dashboard') }}"
                       class="bg-gray-50 hover:bg-gray-100 text-gray-600 border border-gray-200 px-4 py-2 rounded-xl text-sm font-semibold transition">
                        Réinitialiser
                    </a>
                @endif
            </form>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Valeur du Stock</span>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-gray-900 mt-3">
                        {{ number_format($totalInventoryValue, 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">MAD</span>
                    </div>
                    <span class="text-xs text-gray-400 font-medium mt-1 block">Stock valorisé au prix d'achat</span>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Dette Globale</span>
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-red-500 mt-3">
                        {{ number_format($globalDebt, 0, ',', ' ') }} <span class="text-sm font-bold text-red-400">MAD</span>
                    </div>
                    <span class="text-xs text-gray-400 font-medium mt-1 block">Somme des montants impayés</span>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Ventes du Mois</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-gray-900 mt-3">
                        {{ number_format($monthlySales, 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">MAD</span>
                    </div>
                    <span class="text-xs text-emerald-500 font-bold mt-1 inline-flex items-center">
                        <svg class="w-3.5 h-3.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        CA mensuel
                    </span>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Alertes Stock</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-500 mt-3">
                        {{ $stockAlerts }} <span class="text-sm font-bold text-amber-400">produits</span>
                    </div>
                    <div class="mt-2">
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="h-2 rounded-full bg-emerald-500 transition-all duration-700" style="width: {{ $healthyPercentage }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 font-medium mt-1 block">{{ $healthyPercentage }}% du stock en bonne santé</span>
                    </div>
                </div>
            </div>

            <!-- Period Stats -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80">
                <div class="border-b border-gray-50 pb-4 mb-4 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Statistiques de la Période</h3>
                        <p class="text-xs text-gray-400 font-medium">Du {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <!-- Total Revenue -->
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/40 p-5 rounded-xl border border-emerald-100/60">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Revenu Total</span>
                            <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xl font-black text-emerald-800">
                            {{ number_format($totalRevenue, 0, ',', ' ') }} <span class="text-sm font-bold text-emerald-500">MAD</span>
                        </div>
                        <span class="text-xs text-emerald-600/70 font-medium mt-1 block">Ventes payées de la période</span>
                    </div>

                    <!-- Pending Debt (Period) -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100/40 p-5 rounded-xl border border-red-100/60">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-red-700 uppercase tracking-wider">Créances Impayées</span>
                            <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-xl font-black text-red-800">
                            {{ number_format($pendingDebt, 0, ',', ' ') }} <span class="text-sm font-bold text-red-500">MAD</span>
                        </div>
                        <span class="text-xs text-red-600/70 font-medium mt-1 block">Montant dû non encore encaissé</span>
                    </div>

                    <!-- Best Seller -->
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100/40 p-5 rounded-xl border border-amber-100/60">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Meilleure Vente</span>
                            <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg class="w-4.5 h-4.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                            </div>
                        </div>
                        @if($bestSeller)
                            <div class="text-lg font-black text-amber-800 truncate" title="{{ $bestSeller->name }}">
                                {{ $bestSeller->name }}
                            </div>
                            <span class="text-xs text-amber-600/70 font-medium mt-1 block">{{ $bestSeller->total_qty }} unités vendues</span>
                        @else
                            <div class="text-lg font-black text-amber-800">—</div>
                            <span class="text-xs text-amber-600/70 font-medium mt-1 block">Aucune vente sur la période</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Consommation par Chantier -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 lg:col-span-2">
                    <div class="border-b border-gray-50 pb-4 mb-4">
                        <h3 class="text-base font-bold text-gray-800">Consommation par Chantier</h3>
                        <p class="text-xs text-gray-400 font-medium">Top 5 chantiers - valeur des ventes (MAD)</p>
                    </div>
                    <div class="space-y-5 mt-6">
                        @forelse($chantierConsumption as $chantier)
                            @php
                                $maxSpent = $chantierConsumption->first()->total_spent ?? 1;
                                $percentage = $maxSpent > 0 ? ($chantier->total_spent / $maxSpent) * 100 : 0;
                            @endphp
                            <div class="flex items-center group">
                                <span class="w-48 text-xs font-semibold text-gray-600 truncate group-hover:text-gray-900 transition-colors">{{ $chantier->chantier_name }}</span>
                                <div class="flex-1 ml-4">
                                    <div class="w-full bg-gray-50 rounded-full h-8 overflow-hidden relative border border-gray-100/60">
                                        <div class="h-full rounded-full transition-all duration-700 flex items-center justify-end pr-3 text-[11px] font-black text-white shadow-sm
                                            @if($loop->index == 0) bg-blue-500
                                            @elseif($loop->index == 1) bg-emerald-500
                                            @elseif($loop->index == 2) bg-amber-500
                                            @elseif($loop->index == 3) bg-purple-500
                                            @else bg-gray-500
                                            @endif"
                                            style="width: {{ max($percentage, 5) }}%">
                                            {{ formatKmAD($chantier->total_spent) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-400 text-sm">
                                Aucun chantier enregistré.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- CA par Segment -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 flex flex-col justify-between">
                    <div class="border-b border-gray-50 pb-4 mb-4">
                        <h3 class="text-base font-bold text-gray-800">CA par Segment</h3>
                        <p class="text-xs text-gray-400 font-medium">Répartition du chiffre d'affaires</p>
                    </div>
                    <div class="relative flex justify-center items-center py-4">
                        <canvas id="categoryChart" class="max-w-[200px] max-h-[200px]"></canvas>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-4 text-[10px] text-gray-500 font-bold border-t border-gray-50 pt-4">
                        @foreach($categoryDistribution as $dist)
                            <div class="text-center truncate" title="{{ $dist->category_name }}">
                                <span class="inline-block w-2.5 h-2.5 rounded-full mr-1 
                                    @if($loop->index == 0) bg-[#3B82F6]
                                    @elseif($loop->index == 1) bg-[#10B981]
                                    @else bg-[#F59E0B]
                                    @endif"></span>
                                <span>{{ $dist->category_name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Mouvements 7 jours -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80">
                <div class="border-b border-gray-50 pb-4 mb-4">
                    <h3 class="text-base font-bold text-gray-800">Mouvements de Stock (7 derniers jours)</h3>
                    <p class="text-xs text-gray-400 font-medium">Entrées et sorties de stock par jour</p>
                </div>
                <div class="relative">
                    <canvas id="movementsChart" class="w-full h-64"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ---- Donut Chart ----
            const categoriesData = @json($categoryDistribution->pluck('category_name'));
            const valeursData = @json($categoryDistribution->pluck('value'));

            const totalCA = valeursData.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);

            let caText = '';
            if (totalCA >= 1000) {
                caText = (totalCA / 1000).toFixed(1).replace('.', ',') + ' k';
            } else {
                caText = totalCA.toFixed(0);
            }

            const centerTextPlugin = {
                id: 'centerText',
                afterDraw(chart) {
                    const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                    ctx.save();
                    ctx.font = '600 11px Inter, sans-serif';
                    ctx.fillStyle = '#9CA3AF';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('CA Total', left + width / 2, top + height / 2 - 10);
                    ctx.font = '800 16px Inter, sans-serif';
                    ctx.fillStyle = '#111827';
                    ctx.fillText(caText + ' MAD', left + width / 2, top + height / 2 + 10);
                    ctx.restore();
                }
            };

            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: categoriesData,
                    datasets: [{
                        data: valeursData,
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = parseFloat(context.raw);
                                    const percentage = totalCA > 0 ? ((value / totalCA) * 100).toFixed(1) : 0;
                                    return ` ${context.label}: ${percentage}% (${value.toLocaleString('fr-FR')} MAD)`;
                                }
                            }
                        }
                    }
                },
                plugins: [centerTextPlugin]
            });

            // ---- Line Chart: 7-day Movements ----
            const fechas = @json($fechasGrafico);
            const entradas = @json($entradasGrafico);
            const salidas = @json($salidasGrafico);

            new Chart(document.getElementById('movementsChart'), {
                type: 'line',
                data: {
                    labels: fechas,
                    datasets: [
                        {
                            label: 'Entrées',
                            data: entradas,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#10B981',
                            borderWidth: 2,
                        },
                        {
                            label: 'Sorties',
                            data: salidas,
                            borderColor: '#3B82F6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#3B82F6',
                            borderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { weight: '600', size: 12 },
                            }
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                font: { size: 11 },
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.05)',
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } },
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>