@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{asset('css/shops/show.css')}}">
@endpush

@section('content')
    <div class="shop-detail">
        <div class="back-button">
            <a href="{{route('shops.index')}}" class="back-link"><span
                    class="material-icons">chevron_left</span>{{$shop->name}}</a>
        </div>

        <div class="shop-container">
            <div class="shop-info">
                <img src="{{$shop->image_url}}" alt="{{$shop->name}}" class="shop-image">
                <div class="shop-tags">
                    <span class="area-tag">#{{$shop->area}}</span>
                    <span class="genre-tag">#{{$shop->genre}}</span>
                </div>
                <p class="shop-description">{{$shop->description}}</p>
            </div>

            <div class="review-section">
                <div class="average-rating">
                    <h3>評価平均: {{number_format($shop->averageRating(), 1)}}</h3>
                    <div class="star-rating">
                        @php
                            $avgRating = $shop->averageRating();
                            $fullStars = floor($avgRating);
                            $halfStar = $avgRating - $fullStars >= 0.5;
                        @endphp

                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $fullStars)
                                <span class="star full">★</span>
                            @elseif($i == $fullStars + 1 && $halfStar)
                                <span class="star half">★</span>
                            @else
                                <span class="star empty">☆</span>

                            @endif

                        @endfor
                    </div>
                </div>

                @auth
                    <form id="review-form" class="review-form" method="POST">
                        @csrf
                        <input type="hidden" name="shop_id" value="{{$shop->id}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="rating-input">
                            <p class="rating-label">評価を選択:</p>
                            <div class="star-select">
                                @for ($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{$i}}" id="star{{$i}}" required>
                                    <label for="star{{$i}}" title="{{$i}}点">★</label>
                                @endfor
                            </div>
                        </div>
                        <div class="comment-input">
                            <textarea name="comment" maxlength="300" placeholder="レビューを書く(300文字以内)" required></textarea>
                            <span class="char-count">0/300</span>
                        </div>
                        <button type="submit" class="submit-button">投稿する</button>
                    </form>
                @endauth

                <div class="reviews-list">
                    @forelse($shop->reviews()->with('user')->latest()->get() as $review)
                        <div class="review-card">
                            <div class="review-header">
                                <div class="user-info">
                                    <span class="user-name">{{$review->user->name}}</span>
                                    <span class="review-date">{{$review->created_at->format('Y/m/d')}}</span>
                                </div>
                                <div class="rating-display">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{$i <= $review->rating ? 'full' : 'empty'}}">
                                            {{$i <= $review->rating ? '★' : '☆'}}</span>
                                    @endfor
                                </div>
                            </div>
                            <p class="review-comment">{{$review->comment}}</p>
                            @if(auth()->id() === $review->user_id)
                                <div class="review-actions">
                                    <button class="delete-review" data-review-id="{{$review->id}}">削除</button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="no-reviews">まだレビューがありません</p>
                    @endforelse
                </div>
            </div>

            <div class="reservation-container">
                <h2>予約</h2>
                <form action="{{route('reservations.store')}}" method="POST" class="reservation-form">
                    @csrf
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group">
                        <input type="date" name="date" id="date" min="{{date('Y-m-d')}}"
                            max="{{date('Y-m-d', strtotime('+30 days'))}}" value="{{date('Y-m-d')}}" required>
                    </div>

                    <div class="form-group">
                        <select name="time" id="time" required>
                            @foreach ($availableTimes as $time)
                                <option value="{{$time}}">{{$time}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="number_of_people" id="number_of_people" required>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{$i}}">{{$i}}人</option>
                            @endfor
                        </select>
                    </div>

                    <div class="reservation-confirmation">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>

                        @endif
                        <table>
                            <tr>
                                <th>Shop</th>
                                <td class="shop-name">{{$shop->name}}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td id="confirm-date"></td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td id="confirm-time"></td>
                            </tr>
                            <tr>
                                <th>Number</th>
                                <td id="confirm-number"></td>
                            </tr>
                        </table>
                    </div>

                    <button class="reservation-button" type="submit">予約する</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('js/reservation.js')}}"></script>
    <script src="{{asset('js/review.js')}}"></script>
@endpush
