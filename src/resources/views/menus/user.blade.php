@extends('layouts.menu')

@section('menu-items')
<ul class="menu-list">
    <li><a href="{{route('shops.index')}}">Home</a></li>
    <li><a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
    <li><a href="{{route('mypage.index')}}">Mypage</a></li>
</ul>

<form action="{{route('logout')}}" method="POST" style="display: none;" id="logout-form">
    @csrf
</form>

@endsection