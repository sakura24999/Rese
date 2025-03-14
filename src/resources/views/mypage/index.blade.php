@extends('layouts.app')

@section('title', 'マイページ')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/mypage.css')}}">
@endpush

@section('content')
    <div class="mypage-container">
        <div class="header">
            <a href="{{route('shops.index')}}" class="site-title">
                <div class="menu-icon">
                    <span></span>
                </div>
                <div class="site-name">Rese</div>
            </a>
        </div>
        <h1 class="user-name">{{$user->name}}さん</h1>

        <div class="content-grid">
            <div class="reservations-section">
                <h2 class="section-title">予約状況</h2>
                @foreach ($reservations as $reservation)
                    <div class="reservation-card" id="reservation-{{$reservation->id}}">
                        <div class="reservation-actions">
                            <button class="cancel-btn" onclick="cancelReservation({{$reservation->id}})">×</button>
                            <button class="edit-btn" onclick="openEditModal({{$reservation->id}})">予約変更</button>
                            <a href="{{route('reservations.qrcode', $reservation->id)}}" class="qr-code-btn">QRコード</a>

                            @if (!$reservation->is_paid)
                                <button class="payment-btn" onclick="handlePayment({{$reservation->id}})">決済</button>
                            @else
                                <span class="payment-status">決済済み</span>

                            @endif
                        </div>
                        <h3 class="reservation-number">予約{{$loop->iteration}}</h3>
                        <div>Shop: <span class="shop-name">{{$reservation->shop->name}}</span></div>
                        <div data-date="{{$reservation->date}}">Date: {{$reservation->date}}</div>
                        <div data-time="{{$reservation->time}}">Time: {{$reservation->time}}</div>
                        <div data-number="{{$reservation->number_of_people}}">Number: {{$reservation->number_of_people}}人</div>
                    </div>
                @endforeach

                @push('scripts')
                    <meta name="csrf-token" content="{{csrf_token()}}">
                    <script>
                        window.paymentConfig = {
                            stripeKey: "{{config('services.stripe.key')}}",
                            routes: {
                                demo: "{{route('reservations.payment.demo', ['reservation' => '_ID_'])}}",
                                payment: "{{route('reservations.payment', ['reservation' => '_ID_'])}}"
                            }
                        };
                    </script>

                    <script src="{{asset('js/reservation.js')}}"></script>
                    <script src="https://js.stripe.com/v3/"></script>
                    <script src="{{asset('js/payment.js')}}"></script>
                @endpush
            </div>

            <div class="favorites-wrapper">
                <h2 class="section-title">お気に入り店舗</h2>
                <div class="favorites-section">
                    @foreach ($favorites as $favorite)
                        <div class="shop-card">
                            <img src="{{asset($favorite->image_url)}}" alt="{{$favorite->name}}">
                            <div class="shop-info">
                                <h3>{{$favorite->name}}</h3>
                                <div class="shop-tags">
                                    <span>#{{$favorite->area}}</span>
                                    <span>
                                        #{{$favorite->genre}}
                                    </span>
                                </div>
                                <div class="card-actions">
                                    <a href="{{route('shops.show', $favorite->id)}}" class="detail-button">詳しく見る</a>
                                    <button class="favorite-button" data-shop-id="{{$favorite->id}}">
                                        <i class="fa-heart favorite-icon fas active"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="modal" id="editReservationModal" style="display: none;">
            <div class="modal-content">
                <h2>予約変更</h2>
                <form id="editReservationForm">
                    <div class="shop-name"></div>
                    <div class="form-group">
                        <label for="editDate">予約日:</label>
                        <input type="date" id="editDate" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="editTime">予約時間:</label>
                        <select name="time" id="editTime" required></select>
                    </div>
                    <div class="form-group">
                        <label for="editNumber">予約人数:</label>
                        <select name="number_of_people" id="editNumber" required>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{$i}}">{{$i}}人</option>

                            @endfor
                        </select>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="cancel-modal-btn" onclick="closeEditModal()">キャンセル</button>
                        <button type="submit" class="submit-btn">変更を確定</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal" id="paymentModal" style="display: none;">
            <div class="modal-content payment-modal">
                <h2>予約の決済</h2>
                <div class="payment-details">
                    <div class="shop-name"></div>
                    <div class="reservation-date"></div>
                    <div class="reservation-time"></div>
                    <div class="reservation-number"></div>
                    <div class="payment-amount"></div>
                </div>

                <form id="paymentForm">
                    <div id="card-element">
                        <!-- StripeのCardElementがここに挿入されます -->
                    </div>
                    <div id="card-errors" role="alert"></div>

                    <div class="modal-actions">
                        <button type="button" class="cancel-modal-btn" onclick="closePaymentModal()">キャンセル</button>
                        <button type="submit" class="submit-btn" id="submit-payment">決済を完了する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
