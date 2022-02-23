@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <h5 class="card-header">Order Delivery Update</h5>
  <div class="card-body">
    <form action="{{route('order.update',$order->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="row">
          <div class="col-md-3">
            <div class="form-group">
                <label for="status">Order Status :</label>
                <select name="status" id="" class="form-control">
                    <option value="">--Select Status--</option>
                    <option value="new" {{(($order->status=='new')? 'selected' : '')}}>New</option>
                    <option value="processing" {{(($order->status=='processing')? 'selected' : '')}}>process</option>
                    <option value="delivered" {{(($order->status=='delivered')? 'selected' : '')}}>Delivered</option>
                    <option value="cancel" {{(($order->status=='cancel')? 'selected' : '')}}>Cancel</option>
                    <option value="partial" {{(($order->status=='partial')? 'selected' : '')}}>Partial</option>
                </select>
              </div>
          </div>
      </div>
      <div class="row" id="delivery_quant">
          <div class="col-md-3">
            <div class="form-group">
                <label for="delivery_qunt">Delivery Quantity</label>
                <input type="number" name="delivery_qunt" class="form-control" max="{{$order->quantity}}">
              </div>
          </div>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
   @if($order->payment_method=='cod' && $order->payment_status != 'paid')
  <h5 class="card-header">Order Payment update</h5>
  <div class="card-body">

    <div class="row">
        <div class="col-md-4">
            <form action="{{route('order.update.payment',$order->id)}}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="checkbox" name="payment_status" value="paid" id="" class="">
                    <label for="">Payment Received</label>
                    <br>
                    @error('payment_status')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">

                    <label for="">Received Amount<br><span style="color: red">Total Amount To Be Received: {{$order->total_amount}}</span></label>
                    <input name="amount" value="{{old('amount')}}" type="number" class="form-control" max="{{$order->total_amount}}">
                    @error('amount')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Update Payment</button>
            </form>

        </div>
    </div>
</div>
  @endif

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
    #delivery_quant{
        display: none;
    }

</style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function(){
            $('select').change(function(){
                $(this).find("option:selected").each(function(){
                    if($(this).attr('value')=='partial'){
                        $('#delivery_quant').show()
                    }else{
                        $('#delivery_quant').hide()
                    }
                })
            })
        })
    </script>
@endpush
