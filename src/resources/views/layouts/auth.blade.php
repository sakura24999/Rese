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
        <a href="/" class="logo">
            <span class="material-icons logo-icon">menu</span>
            Rese</a>
    </header>

    <div class="auth-card">
        <div class="card-header">
            <h2>@yield('card-title')</h2>
        </div>

        @yield('content')


    </div>
</body>

</html>
