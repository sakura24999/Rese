@extends('layouts.admin')

@section('content')
<div class="shop-owners-index">
    <h1 class="page-title">店舗代表者一覧</h1>
    <div class="action-buttons">
        <form action="{{route('admin.shop-owners.create')}}" method="GET">
            <button class="create-button" type="submit">新規登録</button>
        </form>
    </div>
    <div class="shop-owners-list">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="table-header-id">ID</th>
                    <th class="table-header-name">名前</th>
                    <th class="table-header-email">メールアドレス</th>
                    <th class="table-header-registration_date">登録日</th>
                    <th class="table-header-actions">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shopOwners as $owner)
                <tr>
                    <td class="table-data-id">{{$owner->id}}</td>
                    <td class="table-data-name">{{$owner->name}}</td>
                    <td class="table-data-email">{{$owner->email}}</td>
                    <td class="table-data-actions">
                        <a href="{{route('admin.shop-owners.edit',$owner->id)}}" class="edit-link">編集</a><a href="{{route('admin.shop-owners.show',$owner->id)}}" class="show-link">詳細</a>
                        <form action="{{ route('admin.shop-owners.destroy',$owner ) }} " method="POST" onsubmit="return confirm('本当に削除しますか？');" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="delete-button" type="submit">削除</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="no-data" colspan="6">登録されている店舗代表者はいません</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{$shopOwners->links()}}
    </div>
</div>

@endsection
