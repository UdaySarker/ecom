<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::getAllProduct();
        // return $products;
        return view('backend.product.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $publisher=Publisher::get();
        $author=Author::get();
        $category=Category::where('is_parent',1)->get();
        // return $category;
        return view('backend.product.create')
            ->with('categories',$category)
            ->with('publishers',$publisher)
            ->with('authors',$author);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request,[
            'title'=>'required|string|min:4|unique:products,title',
            'summary'=>'required|string',
            'description'=>'required|string|nullable',
            'product_img'=>'image|mimes:png,jpg,jpeg',
            'pages'=>'nullable|numeric',
            'stock'=>"required|numeric",
            'cat_id'=>'exists:categories,id',
            'publisher_id'=>'nullable|exists:publishers,id',
            'author_id'=>'nullable|exists:authors,id',
            'child_cat_id'=>'nullable|exists:categories,id',
            'is_featured'=>'sometimes|in:1',
            'status'=>'in:active,inactive',
            'condition'=>'in:default,new,hot,best-seller,trending',
            'price'=>'numeric|required',
            'discount'=>'nullable|numeric'
        ]);

        $data=$request->all();
        //return $data;
        $slug=Str::slug($request->title);
        $count=Product::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $data['is_featured']=$request->input('is_featured',0);
        $pages=$request->input('pages');
        $data['pages']=$pages;
        $image_path=$request->file('product_img')->storeAs('products_image',$request->file('product_img')->getClientOriginalName());
        $data['photo']=$image_path;
        unset($data['product_img']);
        $status=Product::create($data);
        if($status){
            request()->session()->flash('success','Product Successfully added');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $author=Author::get();
        $publishers=Publisher::get();
        $product=Product::findOrFail($id);
        $category=Category::where('is_parent',1)->get();
        $items=Product::where('id',$id)->get();
        // return $items;
        return view('backend.product.edit')->with('product',$product)
                    ->with('authors',$author)
                    ->with('categories',$category)
                    ->with('items',$items)
                    ->with('publishers',$publishers);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product=Product::findOrFail($id);
        $this->validate($request,[
            'title'=>'required|string|min:4',
            'summary'=>'required|string',
            'description'=>'required|string|nullable',
            'product_img'=>'image|mimes:png,jpg,jpeg',
            'pages'=>'nullable|numeric',
            'stock'=>"required|numeric",
            'cat_id'=>'exists:categories,id',
            'publisher_id'=>'nullable|exists:publishers,id',
            'author_id'=>'nullable|exists:authors,id',
            'child_cat_id'=>'nullable|exists:categories,id',
            'is_featured'=>'sometimes|in:1',
            'status'=>'in:active,inactive',
            'condition'=>'in:default,new,hot,best-seller,trending',
            'price'=>'numeric|required',
            'discount'=>'nullable|numeric',
        ]);
        $data=$request->all();
        $data['is_featured']=$request->input('is_featured',0);
        $pages=$request->input('pages');
        $data['pages']=$pages;
        $data['user_id']=Auth::user()->id;
        if(empty($request->file('product_img'))){
            $data['photo']=$product->photo;
        }else{
            $image_path=$request->file('product_img')->storeAs('products_image',$request->file('product_img')->getClientOriginalName());
            $data['photo']=$image_path;
        }

        unset($data['product_img']);
        //return $data;
        $status=$product->fill($data)->save();
        if($status){
            request()->session()->flash('success','Product Successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $status=$product->delete();

        if($status){
            request()->session()->flash('success','Product successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}
