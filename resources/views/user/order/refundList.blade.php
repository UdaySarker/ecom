@extends('user.layouts.master')

@section('main-content')
    <div class="row mt-3 pl-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Return/Refund List</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Order ID</th>
                        <th>Type</th>
                        <th>Payment Method</th>
                        <th>Refund Amount</th>
                        <th>Admin Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($refunds as $refund)
                        <tr>
                            <td>{{$refund->id}}</td>
                            @php
                                $order_number=DB::table('orders')->where('id','=',$refund->order_id)->value('order_number');
                            @endphp
                            <td>{{$order_number}}</td>
                            <td>{{$refund->type}}</td>
                            <td>{{$refund->payment_method}}</td>
                            <td>{{$refund->refund_amount}}</td>
                            <td>
                                @if($refund->admin_status=='approved')
                                    <span class="badge badge-success">Approved {{$refund->trans_dtls}}</span>
                                @elseif($refund->admin_status=='rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-warning">Processing</span>
                                @endif
                            </td>
                            <td>
                                @if($refund->admin_status=='approved')
                                    @if($refund->user_action=='false')
                                    <form action="{{route('user.refund.ack',$refund->id)}}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button data-toggle="tooltip" data-placement="top" title="Send Acceptance"type="submit"class="btn btn -sm text-danger"><i class="fas fa-hand-holding-usd"></i></button>
                                    </form>
                                    @else
                                    <span class="text-success font-weight-bold"><i class="fas fa-check-circle"></i></span>
                                    @endif
                                @else
                                    <span class="text-info">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
