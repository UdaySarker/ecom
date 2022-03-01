@extends('backend.layouts.master')

@section('main-content')
    <div class="row mt-6 pl-6">
        <div class="col">
            <div class="card-header">
                <h4>Refund/Return Request</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        @php
                            $order_number = DB::table('orders')
                                ->where('id', '=', $refund->order_id)
                                ->value('order_number');
                            $user = DB::table('users')
                                ->where('id', $refund->user_id)
                                ->value('name');
                        @endphp
                        <table class="table table-striped">
                            <tr>
                                <td>Order Number</td>
                                <td>:</td>
                                <td>{{ $order_number }}</td>
                            </tr>
                            <tr>
                                <td>Ordered By</td>
                                <td>:</td>
                                <td>{{ $user }}</td>
                            </tr>
                            <tr>
                                <td>Item Ordered</td>
                                <td>:</td>
                                <td>{{ $cart[0]->product->title }} x {{ $order->quantity }} pc</td>
                            </tr>
                            <tr>
                                <td>Item Delivered</td>
                                <td>:</td>
                                <td>{{ $order->delivery_qunt }}</td>
                            </tr>
                            <tr>
                                <td>Total Cost of Order</td>
                                <td>:</td>
                                <td>{{ $order->sub_total }}</td>
                            </tr>
                            <tr>
                                <td>Return Reason</td>
                                <td>:</td>
                                <td>{{ $refund->reason }}</td>
                            </tr>
                            <tr>
                                <td>Amount To Be Returned</td>
                                <td>:</td>
                                <td>{{ $refund->refund_amount }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:</td>
                                <td>{{$refund->admin_status}}</td>
                            </tr>
                            @if($refund->admin_status=='processing')
                            <tr>
                                <td>Change Status</td>
                                <td>:</td>
                                <td>
                                    <form action="{{route('admin.refund.update',$refund->id)}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">Transaction Details</label>
                                            <input type="text" name="trans_dtls" class="form-control">
                                            @error('trans_dtls')
                                                <span class="text-danger">Please enter details</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <select name="admin_status" id="" class="form-control">
                                                <option value="approved">Approve</option>
                                                <option value="rejected">Reject</option>
                                            </select>
                                        </div>

                                        <input class="btn btn-sm btn-primary form-control" type="submit" value="Change Status">
                                    </form>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
