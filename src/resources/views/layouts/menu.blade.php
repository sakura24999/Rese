<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese - メニュー</title>
    <link rel="stylesheet" href="{{asset('css/menu.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>

<body class="menu-body">
    <div class="menu-overlay">
        <div class="menu-header">
            <button class="close-button" onclick="window.history.back()">
                <span class="material-icons">close</span>
            </button>
        </div>
        <nav class="menu-content">
            @yield('menu-items')
        </nav>
    </div>
@yield('content')

    @stack('scripts')

    <script>
        window.appConfig = {
            isLoggedIn: {{Auth::check() ? 'true' : 'false'}},
            routes: {
                userMenu: "{{route('menu.user')}}",
                guestMenu: "{{route('menu.guest')}}"
            }
        };
    </script>
    <script src="{{asset('js/menu-handler.js')}}"></script>
</body>


</html>
