<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Tableau de Bord BI') }}
                </h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-semibold">
                    Distribution BTP - {{ now()->format('d/m/Y à H:i') }}
                </p>
            </div>
            
            <!-- Export Button -->
            <button onclick="exportBIReport()" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Exporter Rapport BI</span>
            </button>
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

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- VALEUR DU STOCK -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Valeur du Stock</span>
                    <div class="text-2xl font-black text-gray-900 mt-2">
                        {{ number_format($totalInventoryValue, 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">MAD</span>
                    </div>
                    <span class="text-xs text-gray-400 font-medium mt-1 block">Stock valorisé au prix d'achat</span>
                </div>

                <!-- SOLDE IMPAYÉ -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Solde Impayé</span>
                    <div class="text-2xl font-black text-red-500 mt-2">
                        {{ number_format($unpaidBalance, 0, ',', ' ') }} <span class="text-sm font-bold text-red-400">MAD</span>
                    </div>
                    <span class="text-xs text-gray-400 font-medium mt-1 block">Factures impayées + partielles</span>
                </div>

                <!-- VENTES DU MOIS -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Ventes du Mois</span>
                    <div class="text-2xl font-black text-gray-900 mt-2">
                        {{ number_format($monthlySales, 0, ',', ' ') }} <span class="text-sm font-bold text-gray-500">MAD</span>
                    </div>
                    <span class="text-xs text-emerald-500 font-bold mt-1 inline-flex items-center">
                        <svg class="w-3.5 h-3.5 mr-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        0% vs mois précédent
                    </span>
                </div>

                <!-- ALERTES STOCK -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80">
                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider block">Alertes Stock</span>
                    <div class="text-2xl font-black text-amber-500 mt-2">
                        {{ $stockAlerts }} <span class="text-sm font-bold text-amber-400">produits</span>
                    </div>
                    <span class="text-xs text-emerald-500 font-bold mt-1 block">{{ $healthyPercentage }}% du stock en bonne santé</span>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Consommation par Chantier (Left 2 cols) -->
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
                            <div class="flex items-center">
                                <span class="w-48 text-xs font-semibold text-gray-600 truncate">{{ $chantier->chantier_name }}</span>
                                <div class="flex-1 ml-4">
                                    <div class="w-full bg-gray-50 rounded-full h-8 overflow-hidden relative border border-gray-100/60">
                                        <div class="h-full rounded-full transition-all duration-700 flex items-center justify-end pr-3 text-[11px] font-black text-white shadow-sm
                                            @if($loop->index == 0) bg-blue-500
                                            @elseif($loop->index == 1) bg-emerald-500
                                            @elseif($loop->index == 2) bg-amber-500
                                            @elseif($loop->index == 3) bg-purple-500
                                            @else bg-gray-500
                                            @endif"
                                            style="width: {{ max($percentage, 10) }}%">
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

                <!-- CA par Segment (Right 1 col) -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100/80 flex flex-col justify-between">
                    <div class="border-b border-gray-50 pb-4 mb-4">
                        <h3 class="text-base font-bold text-gray-800">CA par Segment</h3>
                        <p class="text-xs text-gray-400 font-medium">Répartition du chiffre d'affaires</p>
                    </div>
                    
                    <div class="relative flex justify-center items-center py-4">
                        <!-- Canvas for Donut Chart -->
                        <canvas id="categoryChart" class="max-w-[200px] max-h-[200px]"></canvas>
                    </div>
                    
                    <!-- Chart Legend -->
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

        </div>
    </div>

    <!-- ChartJS and Plugin Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoriesData = @json($categoryDistribution->pluck('category_name'));
            const valeursData = @json($categoryDistribution->pluck('value'));

            // Calculate total CA
            const totalCA = valeursData.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
            
            // Format CA to 'k MAD'
            let caText = '';
            if (totalCA >= 1000) {
                caText = (totalCA / 1000).toFixed(1).replace('.', ',') + ' k';
            } else {
                caText = totalCA.toFixed(0);
            }

            // Custom Plugin for Center Text
            const centerTextPlugin = {
                id: 'centerText',
                afterDraw(chart) {
                    const { ctx, chartArea: { top, bottom, left, right, width, height } } = chart;
                    ctx.save();
                    
                    // "CA Total" Label
                    ctx.font = '600 11px Inter, sans-serif';
                    ctx.fillStyle = '#9CA3AF';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('CA Total', left + width / 2, top + height / 2 - 10);
                    
                    // Big Number (e.g. "287,5 k MAD")
                    ctx.font = '800 16px Inter, sans-serif';
                    ctx.fillStyle = '#111827';
                    ctx.fillText(caText + ' MAD', left + width / 2, top + height / 2 + 10);
                    
                    ctx.restore();
                }
            };

            // Donut Chart Init
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
                        legend: {
                            display: false
                        },
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
        });

        // CSV BI Exporter
        function exportBIReport() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Tableau de Bord BI - Stocket Premium\n";
            csvContent += "Date d'exportation: " + new Date().toLocaleString() + "\n\n";
            
            // KPIs Section
            csvContent += "METRIQUE;VALEUR;DETAILS\n";
            csvContent += "Valeur du Stock;" + "{{ $totalInventoryValue }}" + " MAD;Valorise au prix d'achat\n";
            csvContent += "Solde Impaye;" + "{{ $unpaidBalance }}" + " MAD;Factures impayees ou partielles\n";
            csvContent += "Ventes du Mois;" + "{{ $monthlySales }}" + " MAD;Chiffre d'affaires de la periode\n";
            csvContent += "Alertes Stock;" + "{{ $stockAlerts }}" + " produits;Produits sous le seuil d'alerte\n\n";

            // Chantiers Section
            csvContent += "CONSOMMATION PAR CHANTIER\n";
            csvContent += "Chantier;Valeur des ventes (MAD)\n";
            @foreach($chantierConsumption as $chantier)
                csvContent += "{{ $chantier->chantier_name }};{{ $chantier->total_spent }}\n";
            @endforeach
            csvContent += "\n";

            // Segment Section
            csvContent += "CHIFFRE D'AFFAIRES PAR SEGMENT\n";
            csvContent += "Categorie;Ventes (MAD)\n";
            @foreach($categoryDistribution as $dist)
                csvContent += "{{ $dist->category_name }};{{ $dist->value }}\n";
            @endforeach

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Rapport_BI_Stocket_" + new Date().toISOString().slice(0,10) + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</x-app-layout>