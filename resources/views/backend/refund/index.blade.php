@extends('backend.layouts.master')

@section('main-content')
    <div class="row mt-3 pl-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4>Refund/Return List</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 pl-3">
        <div class="col">
            <table class="table table-strip">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Order Number</th>
                        <th scope="col">From</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($refunds as $refund)
                        <tr>
                            <td>{{$refund->id}}</td>
                            @php
                                $order_number=DB::table('orders')->where('id','=',$refund->order_id)->pluck('order_number');
                                $user=DB::table('users')->where('id',$refund->user_id)->pluck('name');
                            @endphp
                            <td>{{$order_number[0]}}</td>
                            <td>{{$user[0]}}</td>
                            <td>{{$refund->admin_status}}</td>
                            <td>
                                <span><a href="{{route('admin.refund.show',$refund->id)}}"><i class="fas fa-eye"></i></a></span>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
