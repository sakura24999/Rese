<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title','Rese 店舗代表者画面')</title>
    <link rel="stylesheet" href="{{asset('css/owner.css')}}">
</head>

<body>
    <div class="owner-wrapper">
        <nav class="owner-nav">
            <div class="nav-header">
                <h1 class="nav-title">Rese店舗管理</h1>
            </div>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{route('owner.dashboard')}}" class="nav-link">ダッシュボード</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('owner.reservations.index')}}" class="nav-link">予約管理</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('owner.shops.index')}}" class="nav-link">店舗情報</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('owner.menus.index')}}" class="nav-link">メニュー管理</a>
                </li>
            </ul>
        </nav>

        <main class="owner-main">
            <header class="owner-header">
                <div class="header-content">
                    <h2 class="page-title">@yield('page-title', 'ダッシュボード')</h2>
                    <div class="user-menu">
                        <span class="user-name">{{Auth::user()->name}}</span>
                        <form action="{{route('owner.logout')}}" method="POST" class="logout-form">
                            @csrf
                            <button class="logout-button" type="submit">ログアウト</button>
                        </form>
                    </div>
                </div>
            </header>
            <div class="owner-content">
                @if (session('success'))
                <div class="alert alert-success">{{session('success')}}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-error">{{session('error')}}</div>

                @endif

                @yield('content')
            </div>

            <footer class="owner-footer">
                <p class="footer-text">&copy; {{date('Y')}} Rese All Rights Reserved.</p>
            </footer>
        </main>
    </div>

    @stack('scripts')
</body>

</html>
