<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;
use App\Http\Requests\UpdateRequest;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{

    //商品一覧ページ表示
    public function index(Request $request)
{
 $query = Product::query();

    // 検索
    if ($request->filled('keyword')) {
        $query->where('name', 'like', '%' . $request->keyword . '%');
    }

    // 並び替え
    if ($request->sort === 'high') {
        $query->orderBy('price', 'desc');
    } elseif ($request->sort === 'low') {
        $query->orderBy('price', 'asc');
    }

    $products = $query->paginate(6);
    return view('index', compact('products'));
}

    //商品登録ページ表示
    public function add()
    {
        $seasons = Season::all();
        return view('create', compact('seasons'));

    }



   





    //商品登録ページ
    public function store(CreateRequest $request)
    {

        //画像保存先
        $path = $request->file('image')->store('products', 'public');

        //productsへ保存
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $path, 
            'description' => $request->description,
        ]);


        //中間テーブルへ保存
        $product->seasons()->attach($request->seasons);

        //とりあえず登録後は商品一覧へ
    return redirect()->route('products.index');//商品一覧へ戻る
    }

    public function detail($id)
    {
        $product = Product::with('seasons')->findOrFail($id);
        $seasons = Season::all();

        return view('detail', compact('product', 'seasons'));
    }

    public function update(UpdateRequest $request, $id)
{
    $product = Product::findOrFail($id);

    // 画像更新（アップされた場合のみ）
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('products', 'public');
        $product->image = $path;
    }

    // 商品更新
    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
    ]);

    // 季節更新（中間テーブル）
    $product->seasons()->sync($request->seasons);

    // 商品一覧へ戻る
    return redirect()->route('products.index');
}


    public function edit($id)
{
    $product = Product::with('seasons')->findOrFail($id);
    $seasons = Season::all();

    return view('detail', compact('product', 'seasons')); 
    // もし edit.blade.php に分けるなら view('products.edit', ...) に変更
}


public function destroy($id)
{
    $product = Product::with('seasons')->findOrFail($id);

    // 中間テーブル解除
    $product->seasons()->detach();

    // 画像ファイル削除（publicディスク）
    if ($product->image && Storage::disk('public')->exists($product->image)) {
        Storage::disk('public')->delete($product->image);
    }

    // products削除
    $product->delete();

    // 一覧へ戻る（メッセージ付き）
    return redirect()->route('products.index')->with('success', '商品を削除しました');
}


}
