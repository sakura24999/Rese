@extends('layouts.admin')

@section('title', '店舗画像管理')

@section('content')
<div class="admin-content">
    <div class="header-content">
        <h1 class="page-title">店舗画像管理</h1>
    </div>

    <a href="{{route('admin.shop-images.create')}}" class="create-button">
        <i class="fas fa-plus"></i>新規画像アップロード</a>

    <div class="image-gallery">
        @if(!empty($images))
            @foreach($$images as $image)
                <div class="image-item">
                    <div class="image-container">
                        <img src="{{asset('storage/' . $image)}}" alt="Shop Image" class="shop-image">
                    </div>
                    <div class="image-info">
                        <p class="image-name">{{basename($image)}}</p>
                        <div class="image-actions">
                            <form action="{{route('admin.shop-images.destroy', basename($image))}}" method="POST"
                                onsubmit="return confirm('本当にこの画像を削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button class="delete-button" type="submit">削除</button>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        @else
            <p class="no-data">画像がありません</p>
        @endif
    </div>
</div>
