<?php

namespace App\Http\Controllers;

use App\Models\DeliverySchedule;
use App\Models\Order;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon as SupportCarbon;

class DeliveryScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deliver_schedule=DeliverySchedule::all();

        return view('backend.deliveryschedule.index')
        ->with('schedules',$deliver_schedule);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orders=Order::select('id','order_number','created_at')->where(['status'=>'processing'])->get();
        return view('backend.deliveryschedule.create')
                    ->with('orders',$orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request,[
        //     'delivery_date'=>'required',
        // ]);
        // $data['order_date']=$request->input('order_date');
        // $data['order_id']=$request->input('order_id');
        // $data['delivery_date']=new Carbon($request->delivery_date);
        // $data['delivery_status']=$request->input('delivery_status');

        // if(DeliverySchedule::updateOrCreate($data))
        // {
        //     $order=Order::find($data['order_id']);
        //     $order->distribution_deliver=$request->input('delivery_status');
        //     if($order->save())
        //     {
        //         request()->session()->flash('success','Delivery initiated succesfully');
        //     }
        // }
        // return redirect()->route('deliveryschedule.index');
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
        //
    }

    public function getOrderDetails($id)
    {
        $order=Order::find($id);
        $data['customer_name']=$order->first_name.' '.$order->last_name;
        $data['customer_address']=$order->address1.' '.$order->address2;
        if($order->payment_method=='cod')
        {
            $data['payment_method']='Cash On Delivery';
        }elseif($order->payment_method=='ibmb')
        {
            $data['payment_method']='Internet/Mobile Banking';
        }else{
            $data['payment_method']="Credit Balance";
        }
        $data['payment_status']=$order->payment_status;
        $data['order_date']= date('d-m-Y', strtotime($order->created_at));
        json_encode($data);
        return response($data);
    }
}
