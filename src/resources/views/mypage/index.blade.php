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
                        function cancelReservation(reservationId) {
                            if (!confirm('予約をキャンセルしてもよろしいですか？')) {
                                return;
                            }

                            console.log(`キャンセルリクエスト開始 - 予約ID: ${reservationId}`);

                            fetch(`/reservations/${reservationId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Content-Type': 'application/json',
                                },
                            })
                                .then(response => {
                                    console.log('レスポンスステータス:', response.status);
                                    return response.json();
                                })
                                .then(data => {
                                    console.log('サーバーレスポンス:', data);
                                    alert(data.message);

                                    const card = document.getElementById(`reservation-${reservationId}`);
                                    if (card) {
                                        card.style.opacity = '0';
                                        setTimeout(() => {
                                            card.remove();

                                            const reservationCards = document.querySelectorAll('.reservation-card');
                                            reservationCards.forEach((card, index) => {
                                                const reservationNumber = card.querySelector('.reservation-number');
                                                if (reservationNumber) {
                                                    reservationNumber.textContent = `予約${index + 1}`;
                                                }
                                            });
                                        }, 300);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('キャンセルに失敗しました。');
                                });
                        }
                    </script>

                    <script>
                        let currentReservationId = null;

                        function openEditModal(reservationId) {
                            currentReservationId = reservationId;
                            const modal = document.getElementById('editReservationModal');
                            const reservation = document.getElementById(`reservation-${reservationId}`);

                            if (!reservation) {
                                console.error('reservation card not found');
                                return;
                            }

                            const date = reservation.querySelector('[data-date]').getAttribute('data-date');
                            const time = reservation.querySelector('[data-time]').getAttribute('data-time');
                            const number = reservation.querySelector('[data-number]').getAttribute('data-number');
                            const shopName = reservation.querySelector('.shop-name').textContent;

                            modal.querySelector('.shop-name').textContent = shopName;

                            document.getElementById('editDate').value = date;
                            document.getElementById('editTime').value = time;
                            document.getElementById('editNumber').value = number;

                            modal.style.display = 'block';

                            generateTimeOptions();

                            const timeSelect = document.getElementById('editTime');
                            if (timeSelect) {
                                const options = timeSelect.options;
                                for (let i = 0; i < options.length; i++) {
                                    if (options[i].value === time) {
                                        timeSelect.selectedIndex = i;
                                        break;
                                    }
                                }
                            }
                        }

                        function closeEditModal() {
                            const modal = document.getElementById('editReservationModal');
                            modal.style.display = 'none';
                            currentReservationId = null;
                        }

                        function generateTimeOptions() {
                            const timeSelect = document.getElementById('editTime');
                            timeSelect.innerHTML = '';


                            for (let hour = 11; hour <= 22; hour++) {
                                for (let minute of ['00', '30']) {
                                    if (hour === 22 && minute === '30') continue;
                                    const time = `${String(hour).padStart(2, '0')}:${minute}`;
                                    const option = new Option(time, time);
                                    timeSelect.add(option);
                                }
                            }
                        }

                        function updateReservationCard(reservationId, newData) {
                            console.log('更新開始:', {
                                reservationId,
                                newData
                            });

                            const card = document.getElementById(`reservation-${reservationId}`);
                            console.log('予約カード要素:', card);

                            if (card) {
                                const dateElement = card.querySelector('[data-date]');
                                const timeElement = card.querySelector('[data-time]');
                                const numberElement = card.querySelector('[data-number]');

                                console.log('取得した要素:', {
                                    dateElement,
                                    timeElement,
                                    numberElement
                                });

                                if (dateElement) {
                                    dateElement.textContent = `Date: ${newData.date}`;
                                    dateElement.setAttribute('data-date', newData.date);
                                }
                                if (timeElement) {
                                    timeElement.textContent = `Time: ${newData.time}`;
                                    timeElement.setAttribute('data-time', newData.time);
                                }
                                if (numberElement) {
                                    numberElement.textContent = `Number: ${newData.number_of_people}人`;
                                    numberElement.setAttribute('data-number', newData.number_of_people);
                                }
                            } else {
                                console.error('予約カードが見つかりません:', reservationId);
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const editForm = document.getElementById('editReservationForm');
                            if (editForm) {
                                editForm.addEventListener('submit', async (e) => {
                                    e.preventDefault();

                                    const formData = new FormData(e.target);
                                    const data = {
                                        date: formData.get('date'),
                                        time: formData.get('time'),
                                        number_of_people: formData.get('number_of_people')
                                    };

                                    try {
                                        const response = await fetch(`/reservations/${currentReservationId}`, {
                                            method: 'PATCH',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                            },
                                            body: JSON.stringify(data)
                                        });

                                        const result = await response.json();

                                        if (response.ok) {
                                            updateReservationCard(currentReservationId, data);
                                            closeEditModal();
                                            alert('予約を更新しました');
                                            window.location.reload();
                                        } else {
                                            throw new Error(result.message || '予約の更新に失敗しました');
                                        }
                                    } catch (error) {
                                        console.error('Error:', error);
                                        alert(error.message || '予約の更新に失敗しました')
                                    }
                                })
                            }


                        });
                    </script>
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
    </div>

@endsection
