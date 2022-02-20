@extends('user.layouts.master')

@section('main-content')
<h4>Purchase and Sell History</h4>
<hr>
<div class="row" style="padding: 20px">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Purchase Date</th>
                        <th scope="col">Purchase Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userpurchases as $userpurchase)
                        <tr>
                            <td>{{$userpurchase->created_at->format('d M, Y')}}</td>
                            <td>{{$userpurchase->total_amount}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>Total Purchase</td>
                        @php
                           $total_purchase = DB::table('orders')->where('user_id','=',Auth::user()->id)->sum('total_amount');
                        @endphp
                        <td>
                            {{$total_purchase}}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Sell Date</th>
                        <th scope="col">Sell Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $usersell)
                        <tr>
                            <td>
                                @php
                                    echo date($usersell->selldate);
                                @endphp
                            </td>
                            <td>{{$usersell->ct_amt}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>Balance</td>
                        <td>{{Helper::userCreditAmount()}}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
    </div>

@endsection
