<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function storeRequest(Request $request,$order_id)
    {
        $this->validate($request,[
            'delivery_status'=>'required|in:delivered,partial,cancel,processing',
            'type'=>'required|in:return,refund',
            'payment_method'=>'required|in:bank,bkash,nagad',
            'order_amount'=>'required',
            'reason'=>'required',
            'description'=>'required',
        ]);
        $data=$request->all();
        $data['order_id']=$order_id;
        return $data;
    }
}
