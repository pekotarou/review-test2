<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;


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



    //検索時の関数
    public function search(Request $request)
    {
   /* テーブルから全てのレコードを取得する （/productに入れ込んだため、後で削除する）*/
        $data = Product::query();
        /* キーワードから検索処理 */
        $keyword = $request->input('keyword');
        $sort = $request->sort;
        if(!empty($keyword)) {//$keyword　が空ではない場合、検索処理を実行
            $data->where('name', 'LIKE', "%{$keyword}%")->get();
            if ( $sort === 'high') {
                $data->orderBy('price', 'desc');
            }elseif ( $sort === 'low') {
                $data->orderBy('price', 'asc');
            }
            $products = $data->paginate(6)->appends($request->all());
            return view('index')->with('products',$products)
            ->with('keyword',$keyword)
            ->with('sort',$sort);
        }else{
            $products = $data->paginate(6)->appends($request->all());
            return view('index')->with('products',$products);
        }
    }





    //商品登録ページ
    public function store(CreateRequest $request)
{
    // バリデーションの内容、後で別で作る、仮置き
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:0',
        'image' => 'required|image|mimes:jpg,jpeg,png',
        'description' => 'required|string',
        'seasons' => 'required|array',
        'seasons.*' => 'exists:seasons,id'
    ]);

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
    $products = Product::query() ->paginate(6);

    return view('index', compact('products'));//商品一覧へ戻る
}
}
