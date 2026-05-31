<x-mail::message>
# Alerte Stock Faible

Bonjour,

Les produits suivants ont atteint un niveau de stock critique et nécessitent votre attention.

<x-mail::table>
| Produit | Catégorie | Stock actuel | Seuil d'alerte |
| :------ | :-------- | :----------: | :------------: |
@foreach($products as $product)
| {{ $product->name }} | {{ $product->category }} | {{ $product->stock }} | {{ $product->alert_quantity }} |
@endforeach
</x-mail::table>

<x-mail::button :url="route('products.index')" color="red">
Voir le Stock
</x-mail::button>

Merci,<br>
{{ config('app.name') }}
</x-mail::message>
