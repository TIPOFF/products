@extends('support::base')

@section('content')
    @include('products::partials._identity_tag')

    @foreach($products as $product)
        <form method="POST" action="{{ route('products.add-to-cart') }}">
            @csrf
            <input type="hidden" name="id" value="{{$product->id}}">

            {{ $product->title }}

            @if($hasCart)
                <button type="submit" value="{{ __('Add to cart') }}"/>
            @endif
        </form>
    @endforeach

@endsection
