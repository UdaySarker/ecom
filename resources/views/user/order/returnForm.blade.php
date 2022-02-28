@extends('user.layouts.master')

@section('main-content')
    <div class="row mt-3 pl-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3>Return/Refund Form</h3>
                </div>
                <div class="card-body">
                    <p class="text-primary">Order Number: {{$order->order_number}}</p>
                    <p>Order Amount: BDT {{$order->total_amount}}</p>
                    <p>Return Amount: BDT {{$refund_amount}}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3 pl-3">
        <div class="col-md-6">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            <form id="newModalForm" action="{{route('return',$order->id)}}" method="POST">
                @csrf
                    <input type="hidden" class="form-control" disabled value="{{$order->id}}">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="">Delivery Status</label>
                            <select name="delivery_status" id="" class="form-control">
                                <option value="">--Please Select Any--</option>
                                <option value="delivered" {{$order->status=='delivered' ? 'selected':'' }}>Delivered</option>
                                <option value="partial" {{$order->status=='partial' ? 'selected':'' }}>Partial</option>
                                <option value="cancel" {{$order->status=='cancel' ? 'selected':'' }}>Cancel</option>
                                <option value="processing" {{$order->status=='processing' ? 'selected':'' }}>Processing</option>
                            </select>
                            @error('delivery_status')
                            <span class="text-danger">Please Select Delivery Status</span>
                        @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="">Type</label>
                            <select name="type" id="type" class="form-control">
                                <option value="" selected>--Please Select Any--</option>
                                <option value="refund">Refund</option>
                                <option value="return">Return</option>
                            </select>
                            @error('type')
                                <span class="text-danger">Please Select type</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col" >
                        <div class="form-group"id="payment_method">
                            <label for="">Payment Method</label>
                            <select name="payment_method"  class="form-control">
                                <option value="">--Select Any--</option>
                                <option value="bank">Bank</option>
                                <option value="bkash">bKash</option>
                                <option value="nagad">NAGAD</option>
                            </select>
                            @error('payment')
                            <span class="text-danger">Please Select Payment Method</span>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="">Refund/Return Reason</label>
                        <textarea class="form-control" name="reason" id="" cols="5" rows="2"></textarea>
                        @error('reason')
                        <span class="text-danger">Please Enter Reason for Return/Refund</span>
                    @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="">Tell us detail about it!</label>
                        <textarea class="form-control" name="description" id="" cols="5" rows="2"></textarea>
                        @error('description')
                        <span class="text-danger">Please enter some description</span>
                    @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary">Send</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){
        $('#payment_method').hide()
        $('#type').change(function(){
            $(this).find("option:selected").each(function(){
                if($(this).attr('value')=='refund'){
                    $('#payment_method').show()
                }else{
                    $('#payment_method').hide()
                }
            })
        })
    })
</script>
@endpush
