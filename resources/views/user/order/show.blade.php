@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
<h5 class="card-header">Order <a href="{{route('order.pdf',$order->id)}}">PDF</a></h5>
  <div class="card-body">
    @if($order)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>S.N.</th>
            <th>Order No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Order Quantity</th>
            <th>Shipping Charge</th>
            <th>Total Amount</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            @php
                $shipping_charge=DB::table('shippings')->where('id',$order->shipping_id)->pluck('price');
            @endphp
            <td>{{$order->id}}</td>
            <td>{{$order->order_number}}</td>
            <td>{{$order->first_name}} {{$order->last_name}}</td>
            <td>{{$order->email}}</td>
            <td>{{$order->quantity}}</td>
            <td>@foreach($shipping_charge as $data) BDT {{number_format($data,2)}} @endforeach</td>
            <td>BDT{{number_format($order->total_amount,2)}}</td>
            <td>
                @if($order->status=='new')
                  <span class="badge badge-primary">{{$order->status}}</span>
                @elseif($order->status=='process')
                  <span class="badge badge-warning">{{$order->status}}</span>
                @elseif($order->status=='delivered')
                  <span class="badge badge-success">{{$order->status}}</span>
                @elseif($order->status == 'partial')
                    <span class="badge badge-secondary">{{$order->status}}</span>
                @else
                  <span class="badge badge-danger">{{$order->status}}</span>
                @endif
            </td>

            <td>
                @if($order->payment_status != "paid")
                <form method="POST" action="{{route('order.destroy',[$order->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$order->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
                @endif
            </td>

        </tr>
      </tbody>
    </table>

    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">ORDER INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Order Number</td>
                        <td class="invoice">{{$order->order_number}}</td>
                    </tr>
                    <tr>
                        <td>Order Date</td>
                        <td> : {{$order->created_at->format('D d M, Y')}} at {{$order->created_at->format('g : i a')}} </td>
                    </tr>
                    <tr>
                        <td>Quantity</td>
                        <td>{{$order->quantity}}</td>
                    </tr>
                    @php
                        $purchased_products=DB::table('carts')->where('order_id','=',$order->id)->get();
                    @endphp
                    <tr>
                        <td>Items: </td>
                        @foreach ($purchased_products as $purchased_product)
                            @php
                            $product= DB::table('products')->find($purchased_product->product_id);
                            $author= DB::table('authors')->where('id','=',$product->author_id)->first();
                            @endphp
                                <td>
                                    <span>{{$product->title}} by {{$author->name}}</span>
                                </td>
                        @endforeach
                        <td>

                        </td>
                    </tr>
                    <tr>
                        <td>Order Delivery Status</td>
                        <td>{{$order->status}}</td>
                    </tr>
                    <tr>
                        <td>Delivery Quantity</td>
                        <td>{{$order->delivery_qunt}}</td>
                    </tr>
                    <tr>
                      @php
                          $shipping_charge=DB::table('shippings')->where('id',$order->shipping_id)->pluck('price');
                      @endphp
                        <td>Shipping Charge</td>
                        <td> : BDT {{number_format($shipping_charge[0],2)}}</td>
                    </tr>
                    <tr>
                        <td>Total Amount</td>
                        <td class="amount">: BDT{{number_format($order->total_amount,2)}}</td>
                    </tr>
                    <tr>
                      <td>Payment Method</td>
                      <td>
                            @if ($order->payment_method == 'ibmb')
                                <span>: Internet/Mobile Banking</span>
                            @elseif ($order->payment_method=='cod')
                                <span>: Cash On Delivery</span>
                            @else
                                <span>: Credit Purchase</span>
                          @endif
                      </td>
                    </tr>
                    <tr>
                        <td>Payment Status</td>
                        @if($order->payment_status=='unpaid' && $order->payment_method !='cod')
                        <td>
                            <span class="badge badge-danger badge-sm">{{$order->payment_status}} </span>
                            <span>
                                <button token="{{csrf_token()}}" order="{{$order->order_number}}" endpoint="{{route('payajax')}}" id="sslczPayBtn" class="btn btn-success btn-sm">Pay Now</button>
                            </span>
                        </td>
                        @else
                        <td class="badge badge-success badge-sm">{{$order->payment_status}}</td>
                        @endif
                    </tr>
              </table>
            </div>
          </div>

          <div class="col-lg-6 col-lx-4">
            <div class="shipping-info">
              <h4 class="text-center pb-4">SHIPPING INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Full Name</td>
                        <td> : {{$order->first_name}} {{$order->last_name}}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td> : {{$order->email}}</td>
                    </tr>
                    <tr>
                        <td>Phone No.</td>
                        <td> : {{$order->phone}}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td> : {{$order->address1}}, {{$order->address2}}</td>
                    </tr>
                    <tr>
                        <td>Country</td>
                        <td> : {{$order->country}}</td>
                    </tr>
                    <tr>
                        <td>Post Code</td>
                        <td> : {{$order->post_code}}</td>
                    </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    @endif

  </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
@push('scripts')
<script>
    var obj={};
    obj.order_number='{{$order->order_number}}';
    obj.cus_fname='{{$order->first_name}}';
    obj.cus_lname='{{$order->last_name}}';
    obj.cus_country='{{$order->country}}';
    obj.cus_phone={{$order->phone}};
    obj.email='{{$order->email}}';
    obj.address='{{$order->address1}}';
    obj.amount={{number_format($order->total_amount,2)}};
    obj.sub_total={{number_format($order->sub_total,2)}};
    obj.quantity={{$order->quantity}};
    $('#sslczPayBtn').prop('postdata',obj);
    // $('#sslczPayBtn').prop('token',document.getElementsByTagName("META")[0].content)
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>
@endpush
