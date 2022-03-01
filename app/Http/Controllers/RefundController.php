<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Refund;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StatusNotification;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;
use App\User;
use Carbon\Carbon;

class RefundController extends Controller
{
    public function userRefundList()
    {
        $refunds=Refund::all();
        return view('user.order.refundList')
        ->with('refunds',$refunds);
    }
    public function index($order_id)
    {
        $order=Order::find($order_id);
        $diff_product=$order->quantity-$order->delivery_qunt;
        $price=$order->cart;
        $refund_amount=$diff_product*$price[0]->price;
        return view('user.order.returnForm')
        ->with('order',$order)
        ->with('refund_amount',$refund_amount);
    }
    public function storeRequest(Request $request,$order_id)
    {

        $this->validate($request,[
            'delivery_status'=>'required|in:delivered,partial,cancel,processing',
            'type'=>'required|in:return,refund',
            'payment_method'=>'required|in:bank,bkash,nagad',
            'reason'=>'required',
            'description'=>'required',
        ]);
        $order=Order::find($order_id);
        $data=$request->all();
        $data['order_id']=$order_id;
        $data['user_id']=Auth::user()->id;
        $data['order_amount']=$order->sub_total;
        $data['admin_status']="processing";
        $diff_product=$order->quantity-$order->delivery_qunt;
        $price=$order->cart;
        $refund_amount=$diff_product*$price[0]->price;
        $data['refund_amount']=$refund_amount;
        $data['user_action_time']=NULL;
        try{
            Refund::create($data);
            $users=User::where('role','admin')->first();
            $details=[
                'title'=>'User'.Auth::user()->name."Submitted a Refund/Return Request",
                'actionURL'=>route('all.notification'),
                'fas'=>'fa-file-alt'
            ];
            Notification::send($users, new StatusNotification($details));
            request()->session()->flash('success','Refund Request Successfully added');
        }catch(\Throwable $e){
            return "Something went wrong";
        }
        return redirect()->route('user.order.index');
    }
    public function userAck(Request $request, $id){
        $data['user_action']=true;
        $data['user_action_time']=Carbon::now();
        $refund=Refund::find($id);
        if($refund->update($data))
        {
            return redirect()->route('refundList');
        }
    }
    //admin part

    public function adminIndex()
    {
        $refunds=Refund::all();
        return view('backend.refund.index')
        ->with('refunds',$refunds);
    }
    public function adminShow($id)
    {
        $refund=Refund::find($id);
        $order=Order::find($refund->order_id);
        $cart_info=$order->cart;
        return view('backend.refund.show',['refund'=>$refund,'order'=>$order,'cart'=>$cart_info]);
    }
    public function adminUpdate(Request $request, $id)
    {
        $refund=Refund::find($id);
        $this->validate($request,[
            'trans_dtls'=>'required'
        ]);
        $data['trans_dtls']=$request->input('trans_dtls');
        $data['admin_status']=$request->input('admin_status');
        if($refund->update($data))
        {
            request()->session()->flash('success',"Successfully updated");
        }else{
            request()->session()->flash('error','Something went wrong');
        }
        return redirect()->route('admin.refund.index');
    }
}
