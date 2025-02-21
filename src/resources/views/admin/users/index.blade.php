@extends('layouts.admin')

@section('content')
<div class="users-index">
    <h1 class="page-title">ユーザー一覧</h1>
    <div class="action-buttons">
        <a href="{{route('admin.users.create')}}" class="create-button">新規登録</a>
    </div>
    <div class="users-list">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td class="action-cell">
                        <a href="{{route('admin.users.edit',$user->id)}}" class="edit-link">編集</a><a href="{{route('admin.users.show',$user->id)}}" class="show-link">詳細</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{$users->links()}}
    </div>
</div>

@endsection
