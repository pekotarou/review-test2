<!--　商品一覧ページ　-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')


<div class="page-header">
    @if(request()->filled('keyword'))
     <h1>“{{ $keyword }}”の商品一覧</h1>
    @else
    <h1>商品一覧</h1>
    @endif
    <a href="/products/register" class="add-button">+ 商品を追加</a>
</div>

<div class="content-wrapper">

    <!-- サイドバー -->
    <aside class="sidebar">
        <form class="search-form" action="/products/search" method="get" >
            @csrf
            <div class="search-form__item">
                @if(request()->filled('keyword'))
                <input class="search-input" type="text" placeholder="{{ $keyword }}" name="keyword" value="{{ old('keyword') }}" />
                @else
                <input class="search-input" type="text" placeholder="商品名で検索" name="keyword" value="{{ old('keyword') }}" />
                @endif
            </div>
            <div class="search-form__button">
                <button class="search-button" type="submit">検索</button>
            </div>
        </form>
        <div class="sort-area">
            <label>価格順で表示</label>
            <form method="GET" action="/products" >
                @if(request()->filled('keyword'))
                    <input type="hidden" name="keyword" value="{{ $keyword }}">
                @endif
                <select name="sort" onchange="this.form.submit()" class="sort-select">
                    <option value="">価格で並べ替え</option>
                    <option value="high" {{ request('sort')=='high'?'selected':'' }}>高い順に表示</option>
                    <option value="low" {{ request('sort')=='low'?'selected':'' }}>低い順に表示</option>
                </select>
            </form>
        @if(request('sort') == 'high')
        <!-- バツを押したら並べ替えだけリセットされる（キーワード選択している場合は保持される） -->
            <div class="sort-tag">
                <span class="tag-text">高い順に表示</span>
                <a href="{{ route('/products', request()->except('sort')) }}" class="tag-close">
                    ×
                </a>
            </div>
        @endif
        @if(request('sort') == 'low')
            <div class="sort-tag">
                <span class="tag-text">低い順に表示</span>
                <a href="{{ route('/products', request()->except('sort')) }}" class="tag-close">
                    ×
                </a>
            </div>
        @endif
        </div>
        <div class="line"></div>

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
