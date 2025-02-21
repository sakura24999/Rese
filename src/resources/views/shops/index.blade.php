@extends('layouts.app')

@section('title', 'Rese - 飲食店予約サービス')

@section('content')
    <div class="header-content">
        <header class="header">
            <div class="logo-area">
                <div class="menu-icon">
                    <span class="material-icons">menu</span>
                </div>
                <a href="{{auth()->check() ? '/menu/user' : '/menu/guest'}}" class="logo">
                    <h2>Rese</h2>
                </a>
            </div>
            <div class="search-container">
                <div class="search-area">
                    <form action="{{route('shops.index')}}" method="GET" class="search-form">
                        <select name="area" class="search-select">
                            <option value="">All area</option>
                            @foreach ($areas as $area)
                                <option value="{{$area}}" {{request('area') == $area ? 'selected' : ''}}>{{$area}}</option>
                            @endforeach
                        </select>
                        <select name="genre" class="search-select">
                            <option value="">All genre</option>
                            @foreach ($genres as $genre)
                                <option value="{{$genre}}" {{request('genre') == $genre ? 'selected' : ''}}>{{$genre}}</option>
                            @endforeach
                        </select>
                        <div class="search-box">
                            <span class="material-icons search-icon">search</span>
                            <input type="text" name="name" placeholder="Search ..." class="search-input"
                                value="{{request('name')}}">
                        </div>
                    </form>
                </div>
            </div>
    </div>
    </header>

    <main class="main-content">
        <div class="shop-grid">
            @foreach ($shops as $shop)
                <div class="shop-card">
                    <div class="shop-image">
                        <img src="{{asset($shop->image_url)}}" alt="{{$shop->name}}">
                    </div>
                    <div class="shop-info">
                        <h3 class="shop-name">{{$shop->name}}</h3>
                        <div class="shop-tags">
                            <span class="area-tag">#{{$shop->area}}</span>
                            <span class="genre-tag">#{{$shop->genre}}</span>
                        </div>
                        <div class="card-actions">
                            <a href="{{route('shops.show', ['shop' => $shop->id])}}" class="detail-button">詳しく見る</a>
                            @auth
                                <button class="favorite-button" data-shop-id="{{$shop->id}}">
                                    @if($shop->favorites()->where('user_id', auth()->id())->exists())
                                        <i class="fa-heart favorite-icon fas active"></i>
                                    @else
                                        <i class="fa-heart favorite-icon far"></i>
                                    @endif
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.favorite-button').forEach(button => {
            button.addEventListener('click', async function () {
                const shopId = this.dataset.shopId;
                try {
                    console.log('Favorite button clicked:', shopId);

                    const response = await fetch(`/favorites/${shopId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin'
                    });
                    console.log('Response:', response);

                    const data = await response.json();
                    console.log('Data:', data);

                    const icon = this.querySelector('.favorite-icon');

                    if (data.status === 'added' || data.status === 'added') {
                        icon.classList.remove('far');
                        icon.classList.add('fas', 'active');
                    } else if (data.status === 'remove' || data.status === 'removed') {
                        icon.classList.remove('fas', 'active');
                        icon.classList.add('far');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('お気に入りの更新に失敗しました。ページをリロードしてください。');
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.querySelector(' .search-form');
            const areaSelect = searchForm.querySelector('select[name="area"]');
            const genreSelect = searchForm.querySelector('select[name="genre"]');
            const searchInput = searchForm.querySelector('.search-input');

            areaSelect.addEventListener('change', function () {
                console.log('Area changed to:', this.value);
                searchForm.submit();
            });

            genreSelect.addEventListener('change', function () {
                console.log('Genre changed to', this.value);
                searchForm.submit();
            });

            let timeoutId;
            searchInput.addEventListener('input', function () {
                console.log('Search input:', this.value);

                clearTimeout(timeoutId);

                timeoutId = setTimeout(() => {
                    searchForm.submit();
                }, 1500);
            });

            searchForm.addEventListener('submit', function () {
                searchInput.name = 'name';
            });

        });
    </script>
@endpush
