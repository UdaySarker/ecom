<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Category;
use App\Models\OldBookSaleModel;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OldBookSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products=Product::getOldBookByUser(Auth::user()->id);
        $orders= [];
        return view('oldsale.index')
        ->with('orders',$orders)
        ->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories= Category::getAllCategory();
        $publishers= Publisher::all();
        $authors= Author::all();
        return view('oldsale.create')
        ->with('categories',$categories)
        ->with('publishers',$publishers)
        ->with('authors',$authors);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
            'condition'=>'in:default,new,old,hot,best-seller,trending',
            'price'=>'numeric|required',
        ]);

        $data=$request->all();
        //return $data;
        $slug=Str::slug($request->title);
        $count=Product::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['user_id']=Auth::user()->id;
        $data['slug']=$slug;
        $pages=$request->input('pages');
        $data['pages']=$pages;
        $image_path=$request->file('product_img')->storeAs('oldbooks',$request->file('product_img')->getClientOriginalName());
        $data['photo']=$image_path;
        $data['admin_status']='processing';
        $data['discount']=0;
        $data['is_featured']=0;
        $data['condition']='old';
        unset($data['product_img']);
        $status=Product::create($data);
        if($status){
            request()->session()->flash('success','Product Successfully added');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('oldsale.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $oldBook=Product::find($id);
        $products=Product::getOldBookByUser(Auth::user()->id);
        return view('oldsale.show',['oldBook'=>$oldBook,'products'=>$products]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oldBook=OldBookSaleModel::find($id);
        if($oldBook){
            $status=$oldBook->delete();
            if($status){
                request()->session()->flash('success','Old Book Successfully deleted');
            }
            else{
                request()->session()->flash('error','Old Book can not deleted');
            }
            return redirect()->route('oldsale.index');
        }
        else{
            request()->session()->flash('error','Old Book can not found');
            return redirect()->back();
        }
    }

    public function oldBookSaleAdminIndex()
    {
        $oldBooks=Product::getAllOldBook();
        return view('backend.oldsaleadmin.index')
        ->with('oldBooks',$oldBooks);
    }

    public function oldBookSaleAdminShow($id)
    {
        $oldBook= Product::find($id);
        return view('backend.oldsaleadmin.show')
        ->with('oldBook',$oldBook);
    }
    public function oldBookSaleAdminUpdateStatus(Request $request,$id)
    {
        $oldBook= Product::find($id);
        $oldBook->admin_status=$request->input('admin_status');
        $status=$oldBook->save();
        if($status)
        {
            return redirect(route('oldbooksale.show',$oldBook->id));
            request()->session()->flash('Status Changed');
        }
        else
        {
            request()->session()->flash('Something went wrong');
        }
    }
}
