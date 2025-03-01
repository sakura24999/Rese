@extends('layouts.admin')

@section('content')
    <div class="admin-dashboard">
        <h1 class="dashboard-title">管理者画面</h1>
        <div class="dashboard-content">
            <div class="dashboard-card">
                <h2 class="card-title">店舗代表者管理</h2>
                <form action="{{route('admin.shop-owners.index')}}" method="GET">
                    <button class="button-link" type="submit">一覧を見る</button>
                </form>
            </div>
            <div class="dashboard-card">
                <h2 class="card-title">ユーザー管理</h2>
                <form action="{{route('admin.users.index')}}" method="GET">
                    <button class="button-link" type="submit">一覧を見る</button>
                </form>
            </div>

            <div class="dashboard-card">
                <h2 class="card-title">店舗画像管理</h2>
                <form action="{{route('admin.shop-images.index')}}" method="GET">
                    <button class="button-link" type="submit">一覧を見る</button>
                </form>
            </div>

            <div class="dashboard-card">
                <h2 class="card-title">メール送信</h2>
                <form action="{{route('admin.mail.create')}}" method="GET">
                    <button class="button-link" type="submit">メールを送信する</button>
                </form>
            </div>
            <div class="dashboard-card">
                <h2 class="card-title">リマインダー設定</h2>
                <form action="{{route('admin.reminder.index')}}" method="GET">
                    <button class="button-link" type="submit">設定を変更する</button>
                </form>
            </div>
        </div>
    </div>

@endsection