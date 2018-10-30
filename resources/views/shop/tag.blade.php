@extends('shop.parent')

@section('child_content')
<p>Tous les produits {{ $tag->name }}</p>

<ol>
@foreach($tag->products as $product)
    <li>
        <a href="{{route('shop.product', ['target' => $target->id, 'tag' => $tag->id, 'product' => $product->id])}}">
        {{ $product->name }}
        </a>
    </li>
@endforeach
</ol>
@endsection
