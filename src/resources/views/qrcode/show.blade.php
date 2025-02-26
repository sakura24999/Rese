@extends('layouts.app')

@section('title', '予約QRコード')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/qrcode.css')}}">

@endpush

@section('content')
    <div class="qrcode-container">
        <div class="qrcode-card">
            <div class="qrcode-header">
                <h2>予約QRコード</h2>
            </div>

            <div class="qrcode-content">
                <div class="reservation-details">
                    <p><strong>店舗:</strong>{{$reservation->shop->name}}</p>
                    <p><strong>日時:</strong>{{$reservation->date}} {{$reservation->time}}</p>
                    <p><strong>人数:</strong>{{$reservation->number_of_people}}名</p>
                </div>

                <div class="qrcode-image">
                    <img src="{{route('reservations.qrcode.generate', $reservation->id)}}" alt="予約QRコード" class="qrcode-img">
                    <p class="qrcode-instructioin">来店時にスタッフにこのQRコードをご提示ください</p>
                </div>

                <div class="qrcode-actions">
                    <a href="{{route('mypage.index')}}" class="back-button">マイページに戻る</a>
                </div>
            </div>
        </div>
    </div>
@endsection
