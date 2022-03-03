@extends('backend.layouts.master')

@section('main-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col"><h4>Delivery Schedule</h4></div>
                        <div class="col "><a class="btn btn-sm btn-info float-right text-white" href="{{route('deliveryschedule.create')}}">Add Delivery Schedule</a></div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Order Number</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Phone</th>
                        <th scope="col">Customer Delivery Address</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Delivery Date</th>
                        <th scope="col">Delivery Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{$schedule->id}}</td>
                            @php
                                $order_number=DB::table('orders')->where('id',$schedule->order_id)->value('order_number');
                                $customer_first_name=DB::table('orders')->select('first_name')->where('user_id',$schedule->order->user_id)->where('id',$schedule->order_id)->value('first_name');
                                $customer_last_name=DB::table('orders')->select('last_name')->where('user_id',$schedule->order->user_id)->where('id',$schedule->order_id)->value('last_name');
                                $customer_address=DB::table('orders')->select('address1')->where('user_id',$schedule->order->user_id)->where('id',$schedule->order_id)->value('address1');
                                $customer_phone=DB::table('orders')->select('phone')->where('user_id',$schedule->order->user_id)->where('id',$schedule->order_id)->value('phone');
                            @endphp
                            <td>{{$order_number}}</td>
                            <td>{{$customer_first_name.$customer_last_name}}</td>
                            <td>{{$customer_address}}</td>
                            <td>{{$customer_phone}}</td>
                            <td>{{$schedule->order->created_at}}</td>
                            <td>{{$schedule->delivery_date}}</td>
                            <td>
                                @if($schedule->delivery_status=='pending')
                                    <span class="badge badge-warning">Pending</span>
                                @else
                                    <span class="badge badge-success">Delivered</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-sm btn-primary rounded"href="{{route('deliveryschedule.show',$schedule->id)}}"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
