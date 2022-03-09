@extends('backend.layouts.master')

@section('main-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4>Delivery Update</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <td>Order Number</td>
                            <td>:</td>
                            <td>{{$order->order_number}}</td>
                        </tr>
                        <tr>
                            <td>Ordered By</td>
                            <td>:</td>
                            <td>{{$order->user->name}}<br>{{$order->address1}}<br>{{$order->phone}}</td>
                        </tr>
                        <tr>
                            <td>Item Ordered</td>
                            <td>:</td>
                            <td>
                                @foreach ($order->cart as $cart_item)
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">{{$cart_item->product->title}}x{{$cart_item->quantity}}</li>
                                </ul>
                                @endforeach
                            </td>

                        </tr>
                        <tr>
                            <td>Delivery Date</td>
                            <td>:</td>
                            @php
                                $delivery_date=DB::table('delivery_schedules')->where('order_id',$order->id)->first();
                            @endphp
                            @if (empty($delivery_date->delivery_date))
                                <td>
                                    <form action="{{route('updateDeliveryDate',$delivery_date->id)}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="delivery_id" value="{{$delivery_date->id}}">
                                        <input type="date" name="delivery_date">
                                        <input type="submit" value="Set Date">
                                    </form>
                                </td>
                            @else
                                <td>{{$delivery_date->delivery_date}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>Delivery Status</td>
                            <td>:</td>
                                @if($delivery_date->delivery_status=='delivered')
                                    <td><span class="badge badge-success"></span></td>
                                @else
                                    <td>
                                        <form action="{{route('updateDeliveryStatus',$delivery_date->id)}}" method="POST">
                                            @csrf
                                            <select name="delivery_status" id="">
                                                <option value="picked">Picked</option>
                                                <option value="delivered">Delivered</option>
                                            </select>
                                            <input type="submit" class="btn btn-primary" value="Set Delivery Status">
                                        </form>
                                    </td>
                                @endif
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // $(document).ready(function(){
        //     $('#delivery_date').submit(function(e){
        //         e.preventDefault();
        //         let date=$("input[name=delivery_date]").val();
        //         let _token=$('meta[name="csrf-token"]').attr('content');
        //         $.ajax({
        //             url: 'updateDeliveryDate/'+{{$delivery_date->id}},
        //             type: 'post',
        //             data: {
        //                 delivery_date:date,
        //                 _token: _token,
        //             },
        //             success: function(res){
        //                 console.log(res)
        //             }

        //         })
        //     })
        // })
    </script>
@endpush
