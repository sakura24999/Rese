@extends('layouts.auth')

@section('title', '予約完了')

@section('style')
<link rel="stylesheet" href="{{asset('css/done.css')}}">
@endsection

@section('content')
<div class="done-container">
    <div class="done-card">
        <h2 class="done-message">ご予約ありがとうございます</h2>
        <button onclick="location.href='{{ route('shops.index') }}'" class="back-btn">戻る</button>
    </div>
</div>


@endsection