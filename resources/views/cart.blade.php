@extends('layouts.frontend')

@section('title', 'Shopping Cart - TechBuy')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @livewire('shopping-cart')
</div>
@endsection