<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;


class ProductController extends Controller
{

    //商品一覧ページ表示
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $sort = $request->sort;
        $products;
        $data;

        
        if(empty($keyword)&&empty($sort)) {
            //キーワードもソートも無しの場合
            $products = Product::paginate(6);
        }elseif($keyword&&empty($sort)){
            //キーワード有りソート無しの場合
            $data = Product::query();
            $data->where('name', 'LIKE', "%{$keyword}%")->get();
            $products = $data->paginate(6)->appends($request->all());
        }elseif($keyword&&$sort){
            //キーワード有りソート有りの場合
            $data = Product::query();
            $data->where('name', 'LIKE', "%{$keyword}%")->get();
            if ($request->sort === 'high') {
                $data->orderBy('price', 'desc');
            }elseif ($request->sort === 'low') {
                $data->orderBy('price', 'asc');
            }
            $products = $data->paginate(6)->appends($request->all());
        }elseif(empty($keyword)&&$sort){
            //キーワード無しソート有りの場合
            $data = Product::query();
            if ($request->sort === 'high') {
                $data->orderBy('price', 'desc');
            }elseif ($request->sort === 'low') {
                $data->orderBy('price', 'asc');
            }
            $products = $data->paginate(6)->appends($request->all());
        }

        //return view('index', compact('products'));
        return view('index')->with('products',$products)
        ->with('keyword',$keyword)
        ->with('sort',$sort);
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
   /* テーブルから全てのレコードを取得する */
        $data = Product::query();
        /* キーワードから検索処理 */
        $keyword = $request->input('keyword');
        $sort = $request->sort;
        if(!empty($keyword)) {//$keyword　が空ではない場合、検索処理を実行します
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
    public function store(Request $request)
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
    return redirect()->route('/products');//商品一覧へ戻る
}






}
