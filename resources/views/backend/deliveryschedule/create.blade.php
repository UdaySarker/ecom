@extends('backend.layouts.master')

@section('main-content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h4>Create Delivery Schedule</h4>
                </div>
            </div>
        </div>
    </div>
    @if(count($orders)>0)
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form action="">
                <div class="form-group">
                    <label for="">Pending Delivery Orders</label>
                    <select name="" id="order_number">
                        <option value="">--Please Select One--</option>
                        @foreach ($orders as $order)
                        <option value="{{$order->id}}">{{$order->order_number}}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="" id="order_details">
                <table class="table">
                    <tr>
                        <td>Customer Name</td>
                        <td>:</td>
                        <td id="customer_name"></td>
                    </tr>
                    <tr>
                        <td>Customer Address</td>
                        <td>:</td>
                        <td id="customer_address"></td>
                    </tr>
                    <tr>
                        <td>Payment Method</td>
                        <td>:</td>
                        <td id="payment_method"></td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        <td>:</td>
                        <td id="payment_status"></td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td>:</td>
                        <td id="order_date"></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col" id="updateDelivery">
            <form action="{{route('deliveryschedule.store')}}" method="POST">
                @csrf
                <input type="hidden" name="order_id" value="{{$order->id}}">
                <input type="hidden" name="order_date" value="{{$order->created_at}}">
                <div class="form-group">
                    <label for="">Set Delivery Date</label>
                    <input type="date" name="delivery_date" id="" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">Change Status</label>
                    <select name="delivery_status" id="" class="form-control">
                        <option value="on-progress">Delivery On Progress</option>
                        <option value="picked">Out For Delivery</option>
                        <option value="delivered" selected>Delivery Complete</option>
                    </select>
                </div>
                <input type="submit" class="btn btn-primary">
            </form>
        </div>
    </div>
    @else
    <p>No Pending order found </p>
    @endif
@endsection
@push('scripts')
    <script>
        $(document).ready(function(){
            $('#order_number').change(function(){
                var order_number=$('#order_number').val()
                $.ajax({
                    url:'/admin/getorderdetail/'+order_number,
                    type:'GET',
                    dataType:'json',
                    success:function(res){
                        console.log(res)
                        $('#customer_name').html(res.customer_name);
                        $('#customer_address').html(res.customer_address);
                        $('#payment_method').html(res.payment_method);
                        $('#payment_status').html(res.payment_status);
                        $('#order_date').html(res.order_date);
                        $('#updateDelivery').show();
                    }
                })
            })

        $('#updateDelivery').hide();
        })
    </script>
@endpush
