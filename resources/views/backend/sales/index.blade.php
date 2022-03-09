@extends('backend.layouts.master')

@section('main-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4>Sales Revenue List</h4>
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
                        <th scope="col">Order Date</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->order_number}}</td>
                            <td>{{$order->created_at->format('d/m/y')}}</td>
                            <td>{{$order->sub_total}}</td>
                        </tr>
                   @endforeach
                   <tr>
                       <td colspan="3" class="text-right">Total</td>
                       <td>{{Helper::totalOrderAmount()}}</td>
                   </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
