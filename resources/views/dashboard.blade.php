<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de Bord - Stocket BI Premium') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div class="p-3 bg-green-100 text-green-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Valeur Financière du Stock</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($totalInventoryValue, 2, ',', ' ') }} DH</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 11m8 4V3m-8 8v10l8 4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Total Produits BTP</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalProductos }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div class="p-3 bg-yellow-100 text-yellow-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-16 0h3m0 0h5m0 0v-7a1 1 0 011-1h2a1 1 0 011 1v7m-7 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Chantiers Actifs</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $chantierConsumption->count() }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
                    <div class="p-3 bg-purple-100 text-purple-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-400 uppercase tracking-wider">Fournisseurs</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalProveedores }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">🏗️ Budget Consommé par Chantier (DH)</h3>
                    <div class="relative h-64">
                        <canvas id="chantierChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📊 Répartition du Stock par Catégorie</h3>
                    <div class="relative h-64flex justify-center">
                        <canvas id="categoryChart" style="max-height: 250px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 Mouvements de Stock (7 Derniers Jours)</h3>
                <div class="relative h-72">
                    <canvas id="mouvementsChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Data passée depuis le DashboardController safely
            const chantiersData = @json($chantierConsumption->pluck('chantier_name'));
            const budgetData = @json($chantierConsumption->pluck('total_spent'));

            const categoriesData = @json($categoryDistribution->pluck('category_name'));
            const valeursData = @json($categoryDistribution->pluck('value'));

            const datesFlux = @json($fechasGrafico);
            const entreesFlux = @json($entradasGrafico);
            const sortiesFlux = @json($salidasGrafico);

            // 1. Chart Chantiers (Bar)
            new Chart(document.getElementById('chantierChart'), {
                type: 'bar',
                data: {
                    labels: chantiersData.length ? chantiersData : ['Aucun chantier'],
                    datasets: [{
                        label: 'Budget Consommé (DH)',
                        data: budgetData.length ? budgetData : [0],
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderRadius: 6
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 2. Chart Catégories (Doughnut)
            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: categoriesData.length ? categoriesData : ['Aucune catégorie'],
                    datasets: [{
                        data: valeursData.length ? valeursData : [1],
                        backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899']
                    }]
                },
                options: { responsive: true }
            });

            // 3. Chart Flux de Stock (Line)
            new Chart(document.getElementById('mouvementsChart'), {
                type: 'line',
                data: {
                    labels: datesFlux,
                    datasets: [
                        {
                            label: 'Entrées',
                            data: entreesFlux,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Sorties',
                            data: sortiesFlux,
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</x-app-layout>