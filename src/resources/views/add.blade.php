<!--　商品追加ページ　-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h1>商品追加ページ</h1>
    <a href="register" class="add-button">+ 商品を追加</a>
</div>

<div class="content-wrapper">

    <!-- サイドバー -->
    <aside class="sidebar">
        <form>
            <input type="text" placeholder="商品名で検索" class="search-input">
            <button type="submit" class="search-button">検索</button>

            <div class="sort-area">
                <label>価格順で表示</label>
                <select class="sort-select">
                    <option>価格で並べ替え</option>
                    <option value="asc">安い順</option>
                    <option value="desc">高い順</option>
                </select>
            </div>
        </form>
    </aside>

    <!-- 商品一覧 -->
    <div class="product-grid">

        @foreach($products as $product)
        <div class="product-card">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            <div class="product-info">
                <span>{{ $product->name }}</span>
                <span>¥{{ number_format($product->price) }}</span>
            </div>
        </div>
        @endforeach

    </div>

</div>

<!-- ページネーション -->
<div class="pagination">

{{ $products->links('pagination::simple-bootstrap-4') }}    
</div>



@endsection
