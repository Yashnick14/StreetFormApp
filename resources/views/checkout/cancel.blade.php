@extends('layouts.app')

@section('content')
<div class="text-center py-20">
    <h1 class="text-2xl font-bold text-red-600">❌ Payment Cancelled!</h1>
    <p>Your payment was not completed.</p>
    <a href="{{ route('checkout') }}" class="mt-4 inline-block px-4 py-2 bg-black text-white rounded">Try Again</a>
</div>
@endsection
