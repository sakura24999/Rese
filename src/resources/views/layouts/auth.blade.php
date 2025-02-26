<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Rese</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('css/shops.css')}}">
    @yield('style')

</head>

<body class="body-screen">
    <header class="header">
        <div class="logo-area">
            <div class="menu-icon" id="menu-trigger">
                <span class="material-icons">menu</span>
            </div>
            <a href="/" class="logo">
                <h2>Rese</h2>
            </a>
        </div>
    </header>

    <div class="modal-overlay" id="modal-overlay"></div>

    <div class="modal-menu" id="modal-menu">
        <div class="modal-header">
            <button class="modal-close" id="modal-close">
                <span class="material-icons">close</span>
            </button>
        </div>
        <nav class="menu-content">
            <ul class="menu-list">
                <li><a href="/shops">Home</a></li>
                <li><a href="{{route('register')}}">Registration</a></li>
                <li><a href="{{route('login')}}">Login</a></li>
            </ul>
        </nav>
    </div>

    <div class="auth-card">
        <div class="card-header">
            <h2>@yield('card-title')</h2>
        </div>

        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuTrigger = document.getElementById('menu-trigger');
            const modalMenu = document.getElementById('modal-menu');
            const modalClose = document.getElementById('modal-close');
            const modalOverlay = document.getElementById('modal-overlay');

            const toggleMenu = () => {
                modalMenu.classList.toggle('is-active');
                modalOverlay.classList.toggle('is-active');
                document.body.classList.toggle('no-scroll');
            };

            menuTrigger.addEventListener('click', toggleMenu);
            modalClose.addEventListener('click', toggleMenu);
            modalOverlay.addEventListener('click', toggleMenu);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modalMenu.classList.contains('is-active')) {
                    toggleMenu();
                }
            });
        });
    </script>
</body>

</html>
