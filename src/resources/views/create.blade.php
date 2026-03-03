<!--　商品追加ページ　-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/add.css') }}">
@endsection

@section('content')


<div class="register-container">

    <h1 class="page-header">商品登録</h1>

    <form action="/products/register" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品名 --}}
        <div class="form-group">
            <label>商品名 <span class="required">必須</span></label>
            <input type="text" name="name" placeholder="商品名を入力" value="{{ old('name') }}">
        </div>

        {{-- 価格 --}}
        <div class="form-group">
            <label>価格 <span class="required">必須</span></label>
            <input type="number" name="price" placeholder="価格を入力" value="{{ old('price') }}">
        </div>

        {{-- 商品画像 --}}
        <div class="form-group">
            <label>商品画像 <span class="required">必須</span></label>
            <input type="file" name="image">
        </div>

        {{-- 季節 --}}
        <div class="form-group">
            <label>
                季節 <span class="required">必須</span>
                <span class="note">複数選択可</span>
            </label>
            <div class="radio-group">
                @foreach($seasons as $season)
                    <label>
                        <input type="checkbox"
                            name="seasons[]"
                            value="{{ $season->id }}"
                            {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}>
                        {{ $season->name }}
                    </label>
                @endforeach
            </div>


        </div>

        {{-- 商品説明 --}}
        <div class="form-group">
            <label>商品説明 <span class="required">必須</span></label>
            <textarea name="description" placeholder="商品の説明を入力">{{ old('description') }}</textarea>
        </div>

        <div class="button-group">
            <a href="/products" class="btn-back">戻る</a>
            <button type="submit" class="btn-submit">登録</button>
        </div>

    </form>
</div>




@endsection
