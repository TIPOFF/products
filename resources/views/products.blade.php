@extends('support::base')

@section('content')
    @include('products::partials._identity_tag')

    @foreach($products as $product)
        <form method="GET" action="{{ route('products.add-to-cart', ['product' => $product, '_token' => csrf_token()]) }}">
            {{ $product->title }}

            @if($hasCart)
                <button type="submit">{{ __('Add to cart') }}</button>
            @endif
        </form>
    @endforeach

@endsection
