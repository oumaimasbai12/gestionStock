<x-app-layout>
    @section('title', 'Modifier Sortie')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="page-title">
                {{ __('Modifier le Bon de Sortie') }}
            </h2>
            <a href="{{ route('exits.index') }}" class="inline-flex items-center btn-muted px-4 py-2 rounded-md text-sm font-medium transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retour aux sorties</span>
            </a>
        </div>
    </x-slot>

    <div class="app-page">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="app-card p-8">
                <form action="{{ route('exits.update', $exit) }}" method="POST" id="exitForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="md:col-span-2">
                            <label for="product_id" class="block text-sm font-semibold text-ink mb-1">Produit BTP</label>
                            <select name="product_id" id="product_id" required onchange="updateProductPrice()" class="mt-1 app-input">
                                <option value="" disabled>Sélectionner un produit dans le stock</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->purchase_price }}" data-stock="{{ $product->stock }}" {{ old('product_id', $exit->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} (Stock actuel: {{ $product->stock }} | Catégorie: {{ $product->category }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="chantier_id" class="block text-sm font-semibold text-ink mb-1">Chantier d'Affectation</label>
                            <select name="chantier_id" id="chantier_id" class="mt-1 app-input">
                                <option value="">Aucun chantier</option>
                                @foreach($chantiers as $chantier)
                                    <option value="{{ $chantier->id }}" {{ old('chantier_id', $exit->chantier_id) == $chantier->id ? 'selected' : '' }}>
 {{ $chantier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('chantier_id')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="customer_id" class="block text-sm font-semibold text-ink mb-1">Client Destinataire (Optionnel)</label>
                            <select name="customer_id" id="customer_id" class="mt-1 app-input">
                                <option value="">Sélectionner un client</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id', $exit->customer_id) == $customer->id ? 'selected' : '' }}>
 {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="quantity" class="block text-sm font-semibold text-ink mb-1">Quantité à Sortir</label>
                            <input type="number" name="quantity" id="quantity" min="1" required value="{{ old('quantity', $exit->quantity) }}" oninput="calculateTotal()" class="mt-1 app-input">
                            @error('quantity')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="unit_price" class="block text-sm font-semibold text-ink mb-1">Prix Unitaire de Vente (DH)</label>
                            <input type="number" name="unit_price" id="unit_price" step="0.01" min="0" required value="{{ old('unit_price', $exit->unit_price) }}" oninput="calculateTotal()" class="mt-1 app-input">
                            @error('unit_price')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="paid_amount" class="block text-sm font-semibold text-ink mb-1">Montant Payé (DH)</label>
                            <input type="number" name="paid_amount" id="paid_amount" step="0.01" min="0" required value="{{ old('paid_amount', $exit->paid_amount) }}" oninput="adjustPaymentStatus()" class="mt-1 app-input">
                            @error('paid_amount')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-semibold text-ink mb-1">Statut du Paiement</label>
                            <select name="payment_status" id="payment_status" required class="mt-1 app-select bg-sage/10">
                                <option value="unpaid" {{ old('payment_status', $exit->payment_status) == 'unpaid' ? 'selected' : '' }}>Non Payé</option>
                                <option value="partial" {{ old('payment_status', $exit->payment_status) == 'partial' ? 'selected' : '' }}>Paiement Partiel</option>
                                <option value="paid" {{ old('payment_status', $exit->payment_status) == 'paid' ? 'selected' : '' }}>Entièrement Payé</option>
                            </select>
                            @error('payment_status')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="document" class="block text-sm font-semibold text-ink mb-1">Référence Document</label>
                            <input type="text" name="document" id="document" required value="{{ old('document', $exit->document) }}" class="mt-1 app-input">
                            @error('document')
                                <span class="text-accent text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-sage/10 rounded-lg p-5 border border-ink/15 mb-6">
                        <h4 class="text-xs font-bold text-ink/50 uppercase tracking-wider mb-3">Récapitulatif Financier</h4>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <span class="text-[11px] font-semibold text-ink/50 block">Total Vente</span>
                                <span id="totalLabel" class="text-base font-black text-ink">{{ number_format($exit->quantity * $exit->unit_price, 2, ',', ' ') }} DH</span>
                            </div>
                            <div>
                                <span class="text-[11px] font-semibold text-ink/50 block">Montant Payé</span>
                                <span id="paidLabel" class="text-base font-black text-sage">{{ number_format($exit->paid_amount, 2, ',', ' ') }} DH</span>
                            </div>
                            <div>
                                <span class="text-[11px] font-semibold text-ink/50 block">Solde Restant</span>
                                <span id="remainingLabel" class="text-base font-black text-accent">{{ number_format(($exit->quantity * $exit->unit_price) - $exit->paid_amount, 2, ',', ' ') }} DH</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 border-t border-ink/15 pt-6">
                        <a href="{{ route('exits.index') }}" class="inline-flex items-center px-5 py-2.5 btn-muted font-semibold rounded-md text-sm transition">
                            Annuler
                        </a>
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 btn-primary text-cream font-semibold rounded-md text-sm transition">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateProductPrice() {
            const select = document.getElementById('product_id');
            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
                const purchasePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                document.getElementById('unit_price').value = purchasePrice.toFixed(2);
                calculateTotal();
            }
        }

        function calculateTotal() {
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const total = quantity * unitPrice;
            document.getElementById('totalLabel').textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' DH';
            let paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
            if (paidAmount > total) {
                document.getElementById('paid_amount').value = total.toFixed(2);
                paidAmount = total;
            }
            document.getElementById('paidLabel').textContent = paidAmount.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' DH';
            const remaining = total - paidAmount;
            document.getElementById('remainingLabel').textContent = remaining.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' DH';
            adjustPaymentStatus();
        }

        function adjustPaymentStatus() {
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
            const total = quantity * unitPrice;
            const paidAmount = parseFloat(document.getElementById('paid_amount').value) || 0;
            const statusSelect = document.getElementById('payment_status');
            if (total === 0 || paidAmount >= total) {
                statusSelect.value = 'paid';
            } else if (paidAmount > 0) {
                statusSelect.value = 'partial';
            } else {
                statusSelect.value = 'unpaid';
            }
            document.getElementById('paidLabel').textContent = paidAmount.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' DH';
            const remaining = total - paidAmount;
            document.getElementById('remainingLabel').textContent = remaining.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' DH';
        }
    </script>
</x-app-layout>