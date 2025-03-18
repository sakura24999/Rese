<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese - @yield('title', 'ホーム')</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shops.css') }}">
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <link rel="stylesheet" href="{{asset('css/review.css')}}">
    @stack('styles')
</head>

<body class="body-container">
    @yield('content')

    <div class="menu-modal" id="guest-menu-modal" style="display: none">
        <div class="menu-container">
            <div class="menu-header">
                <button class="close-button" id="close-guest-modal">
                    <span class="material-icons">close</span></button>
            </div>
            <div class="menu-content">
                <ul class="menu-list">
                    <li><a href="{{route('shops.index')}}">Home</a></li>
                    <li><a href="{{route('register')}}">Registration</a></li>
                    <li><a href="{{route('login')}}">Login</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="menu-modal" id="user-menu-modal" style="display: none">
        <div class="menu-container">
            <div class="menu-header">
                <button class="close-button" id="close-user-modal">
                    <span class="material-icons">close</span></button>
            </div>
            <ul class="menu-list">
                <li><a href="{{route('shops.index')}}">Home</a></li>
                <li><a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </li>
                <li><a href="{{route('mypage.index')}}">Mypage</a></li>
            </ul>
            <form action="{{route('logout')}}" id="logout-form" method="POST" style="display:none;" id="logout-form">
                @csrf</form>
        </div>
    </div>

    @stack('scripts')

    <script>
        window.appConfig = {
            isLoggedIn: {{Auth::check() ? 'true' : 'false'}}
        };
    </script>
    <script src="{{asset('js/menu-handler.js')}}"></script>
</body>

</html>
