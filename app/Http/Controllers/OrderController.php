<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Mail\OrderShipped;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\DeliverySchedule;
use App\Models\Message;
use App\Models\Order;
use App\Models\Shipping;
use App\User;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Helper;
// Use Barryvdh\DomPDF\PDF as PDF;
use Illuminate\Support\Str;
use App\Notifications\StatusNotification;
use App\Notifications\UserNotification;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders=Order::orderBy('id','DESC')->paginate(10);
        return view('backend.order.index')->with('orders',$orders);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'first_name'=>'string|required',
            'last_name'=>'string|required',
            'address1'=>'string|required',
            'address2'=>'string|nullable',
            'coupon'=>'nullable|numeric',
            'phone'=>'numeric|required',
            'post_code'=>'string|nullable',
            'email'=>'string|required',
            'payment_method'=>'required',
            'shipping'=>'required',
        ]);
        // return $request->all();

        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            request()->session()->flash('error','Cart is Empty !');
            return back();
        }
        // $cart=Cart::get();
        // // return $cart;
        // $cart_index='ORD-'.strtoupper(uniqid());
        // $sub_total=0;
        // foreach($cart as $cart_item){
        //     $sub_total+=$cart_item['amount'];
        //     $data=array(
        //         'cart_id'=>$cart_index,
        //         'user_id'=>$request->user()->id,
        //         'product_id'=>$cart_item['id'],
        //         'quantity'=>$cart_item['quantity'],
        //         'amount'=>$cart_item['amount'],
        //         'status'=>'new',
        //         'price'=>$cart_item['price'],
        //     );

        //     $cart=new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }

        // $total_prod=0;
        // if(session('cart')){
        //         foreach(session('cart') as $cart_items){
        //             $total_prod+=$cart_items['quantity'];
        //         }
        // }

        $order=new Order();
        $order_data=$request->all();
        $order_data['order_number']='ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']=$request->user()->id;
        $order_data['shipping_id']=$request->shipping;
        $order_data['distribution_deliver']='on-progress';
        $shipping=Shipping::where('id',$order_data['shipping_id'])->pluck('price');
        // return session('coupon')['value'];
        $order_data['sub_total']=Helper::totalCartPrice();
        $order_data['quantity']=Helper::cartCount();
        if(session('coupon')){
            $order_data['coupon']=session('coupon')['value'];
        }
        if($request->shipping){
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0]-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0];
            }
        }
        else{
            if(session('coupon')){
                $order_data['total_amount']=Helper::totalCartPrice()-session('coupon')['value'];
            }
            else{
                $order_data['total_amount']=Helper::totalCartPrice();
            }
        }
        if($request->payment_method=="credit"){
            if(Helper::userCreditAmount()<$order_data['total_amount']){
                request()->session()->flash('error','Available balance is not sufficient!');
                return back()->withInput($request['input']);
            }
        }
        if($request->payment_method=='cod' || $request->payment_method=='credit')
        {
            $order_data['status']="processing";
        }else{
            $order_data['status']="new";
        }

        if($request->payment_method=='credit'){
            $order_data['payment_method']='paid';
        }else{
            $order_data['payment_method']=$request->input('payment_method');
        }
        $order->fill($order_data);
        $status=$order->save();
        if($status)
        $users=User::where('role','admin')->first();
        $details=[
            'title'=>'New order created',
            'actionURL'=>route('order.show',$order->id),
            'fas'=>'fa-file-alt'
        ];
        $data_delivery['order_id']=$order->id;
        $data_delivery['order_date']=$order->created_at;
        $data_delivery['delivery_status']=$order->distribution_deliver;
        DeliverySchedule::create($data_delivery);
        Notification::send($users, new StatusNotification($details));
        session()->forget('cart');
        session()->forget('coupon');
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);
        request()->session()->flash('success','Your product successfully placed in order');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::find($id);
        // return $order;
        return view('backend.order.show')->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order=Order::find($id);
        return view('backend.order.edit')->with('order',$order);
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
        $data=$request->all();
        $order=Order::find($id);
        if($order->payment_status=="unpaid")
        {
            request()->session()->flash('error','Order is still unpaid');
            return redirect()->route('order.index');
        }else{
            $this->validate($request,[
                'status'=>'required|in:new,processing,delivered,cancel,partial'
            ]);

            if($request->status=='delivered'){
                foreach($order->cart as $cart){
                    $product=$cart->product;
                    $product->stock -=$cart->quantity;
                    $product->save();
                    if($product->condition="old" && $product->slug==$cart->product->slug){
                        $data_wallet['order_id']=$order->order_number;
                        $data_wallet['book_owner_id']=$product->user_id;
                        $data_wallet['dt_amt']=0;
                        $data_wallet['ct_amt']=$product->price;
                        $data_wallet['selldate']=$order->created_at;

                       // $data_wallet['credit_balance']=$credit_balance+$product->price;
                       if(DB::table('user_wallet')->insert($data_wallet)){
                        $credit_balance['user_id']=$product->user_id;
                        $credit_balance['credit_amt']= DB::table('user_wallet')->where('book_owner_id','=',$product->user_id)->sum('ct_amt');
                        DB::table('credit_balance')->insert($credit_balance);
                       }
                    }
                }
                if($order->payment_method=='credit')
                {
                    $data_wallet['order_id']=$order->order_number;
                    $data_wallet['book_owner_id']=$order->user_id;
                    $data_wallet['dt_amt']=$order->sub_total;
                    $data_wallet['ct_amt']=0;
                    $data_wallet['selldate']=$order->created_at;

                    if(DB::table('user_wallet')->insert($data_wallet))
                    {
                        $credit_balance['user_id']=$order->user_id;
                        $credit_balance['credit_amt']= -$order->sub_total;
                        DB::table('credit_balance')->insert($credit_balance);
                    }
                }
                $users=User::where('id',$order->user_id)->first();
                $details=[
                    'title'=>'Order Notification',
                    'actionURL'=>route('user.order.show',$order->id),
                    'fas'=>'fa-file-alt'
                ];
                Notification::send($users, new UserNotification($details));
            }elseif($request->status=='partial'){
                // return $request->delivery_qunt;
                // exit();
                $users=User::where('id',$order->user_id)->first();
                $details=[
                    'title'=>'Order Notification',
                    'actionURL'=>route('user.order.show',$order->id),
                    'fas'=>'fa-file-alt'
                ];
                Notification::send($users, new UserNotification($details));
                foreach($order->cart as $cart){
                    $product=$cart->product;
                    $product->stock -=$request->delivery_qunt;
                    $order->delivery_qunt=$request->delivery_qunt;
                    $product->save();
                    if($product->condition="old" && $product->slug==$cart->product->slug){
                        $data_wallet['order_id']=$order->order_number;
                        $data_wallet['book_owner_id']=$product->user_id;
                        $data_wallet['dt_amt']=0;
                        $data_wallet['ct_amt']=$product->price;
                        $data_wallet['selldate']=$order->created_at;

                       // $data_wallet['credit_balance']=$credit_balance+$product->price;
                       if(DB::table('user_wallet')->insert($data_wallet)){
                        $credit_balance['user_id']=$product->user_id;
                        $credit_balance['credit_amt']= DB::table('user_wallet')->where('book_owner_id','=',$product->user_id)->sum('ct_amt');
                        DB::table('credit_balance')->insert($credit_balance);
                       }
                    }
                }
                if($order->payment_method=='credit')
                {
                    $data_wallet['order_id']=$order->order_number;
                    $data_wallet['book_owner_id']=$order->user_id;
                    $data_wallet['dt_amt']=$order->sub_total;
                    $data_wallet['ct_amt']=0;
                    $data_wallet['selldate']=$order->created_at;

                    if(DB::table('user_wallet')->insert($data_wallet))
                    {
                        $credit_balance['user_id']=$order->user_id;
                        $credit_balance['credit_amt']= -$order->sub_total;
                        DB::table('credit_balance')->insert($credit_balance);
                    }
                }
            }

            $status=$order->fill($data)->save();
            if($status){
                request()->session()->flash('success','Successfully updated order');

            }
            else{
                request()->session()->flash('error','Error while updating order');
            }
            return redirect()->route('order.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            return redirect()->route('order.index');
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }

    public function orderTrack(){
        return view('frontend.pages.order-track');
    }

    public function productTrackOrder(Request $request){
        // return $request->all();

        $order=Order::where('user_id',auth()->user()->id)->where('order_number',$request->order_number)->first();
        $delivery_date=DeliverySchedule::select('delivery_date')->where('order_id',$order->id)->value('delivery_date');
        $order_date=DeliverySchedule::select('order_date')->where('order_id',$order->id)->value('order_date');
        $order_date=new DateTime($order_date);
        $delivery_date=new DateTime($delivery_date);
        $date_diff=date_diff($order_date, $delivery_date);
        $days=$date_diff->d;
        $hours=$date_diff->h;
        if($order){
            if($order->status=="new"){
            request()->session()->flash('success','Your order has been placed. please wait.');
            return redirect()->route('home');

            }
            elseif($order->status=="processing"){
                request()->session()->flash('success','Your order is under processing please wait Please expect delivery at'.$days.'days'.$hours.'hours');
                return redirect()->route('home');

            }
            elseif($order->distribution_deliver=="true"){
                request()->session()->flash('success','Your order is out for delivery. Expect your products at'.$days.' days and '.$hours.' hours');
                return redirect()->route('home');

            }
            else{
                request()->session()->flash('error','Your order canceled. please try again');
                return redirect()->route('home');

            }
        }
        else{
            request()->session()->flash('error','Invalid order number please try again');
            return back();
        }
    }

    // PDF generate
    public function pdf(Request $request){
        $order=Order::getAllOrder($request->id);
        // return $order;
        $file_name=$order->order_number.'-'.$order->first_name.'.pdf';
        // return $file_name;
        $pdf = app('dompdf.wrapper');
        // $pdf=new Dompdf();
        $pdf=$pdf->loadview('backend.order.pdf',compact('order'));
        return $pdf->download($file_name);
    }
    // Income chart
    public function incomeChart(Request $request){
        $year=\Carbon\Carbon::now()->year;
        // dd($year);
        $items=Order::with(['cart_info'])->whereYear('created_at',$year)->where('status','delivered')->get()
            ->groupBy(function($d){
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
            // dd($items);
        $result=[];
        foreach($items as $month=>$item_collections){
            foreach($item_collections as $item){
                $amount=$item->cart_info->sum('amount');
                // dd($amount);
                $m=intval($month);
                // return $m;
                isset($result[$m]) ? $result[$m] += $amount :$result[$m]=$amount;
            }
        }
        $data=[];
        for($i=1; $i <=12; $i++){
            $monthName=date('F', mktime(0,0,0,$i,1));
            $data[$monthName] = (!empty($result[$i]))? number_format((float)($result[$i]), 2, '.', '') : 0.0;
        }
        return $data;
    }

    //order payment update
    public function updatePayment(Request $request,$id){
        $order=Order::find($id);
        $this->validate($request,[
            'payment_status'=>'required',
            'amount'=>'required|numeric'
        ]);
        $data['payment_status']=$request->input('payment_status');
        $data['total_amount']=$request->input('amount');
        $status=$order->fill($data)->save();
        $user=User::find($order->user_id);
        if($status){
            Mail::to($user->email)->send(new OrderShipped($order));
            request()->session()->flash('success','Successfully updated order');

        }
        else{
            request()->session()->flash('error','Error while updating order');
        }
        return redirect()->route('order.index');
    }
}
