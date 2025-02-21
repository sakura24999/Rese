@extends('layouts.menu')

@section('menu-items')
    <ul class="menu-list">
        <li><a href="{{route(('shops.index'))}}">Home</a></li>
        <li><a href="{{route('register')}}">Registration</a></li>
        <li><a href="{{route('login')}}">Login</a></li>
    </ul>

@endsection
