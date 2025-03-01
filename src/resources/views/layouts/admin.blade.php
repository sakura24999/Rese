<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title', 'Rese 管理画面')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="{{asset('css/shops.css')}}">
    <link rel="stylesheet" href="{{asset('css/notification.css')}}">
    <link rel="stylesheet" href="{{asset('css/reminder.css')}}">
</head>

<body>
    <header class="admin-header">
        <a href="/" class="logo">
            <span class="material-icons logo-icon">menu</span>
            Rese</a>
    </header>
    <div class="admin-wrapper">
        <nav class="admin-nav">
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{route('admin.shop-owners.index')}}" class="nav-link">店舗代表者管理</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.users.index')}}" class="nav-link">ユーザー管理</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.shop-images.index')}}" class="nav-link">店舗画像管理</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.mail.create')}}" class="nav-link">メール送信</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('admin.reminder.index')}}" class="nav-link">リマインダー設定</a>
                </li>
                <li class="nav-item">
                    <form action="{{route('logout')}}" class="nav-logout" method="POST">
                        @csrf
                        <button class="nav-link nav-logout-button" type="submit">ログアウト</button>
                    </form>
                </li>
            </ul>
        </nav>

        <main class="admin-main">
            <div class="admin-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{session('success')}}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{session('error')}}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @yield('scripts')
</body>

</html>