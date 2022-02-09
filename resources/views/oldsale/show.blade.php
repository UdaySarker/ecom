@extends('user.layouts.master')
@section('main-content')
<div class="card">
<h5 class="card-header">Order</h5>
{{-- <a href="{{route('order.pdf',$order->id)}}" class=" btn btn-sm btn-primary shadow-sm float-right"><i class="fas fa-download fa-sm text-white-50"></i> Generate PDF</a> --}}
  <div class="card-body">
    @if(count($products)>0)
    <table class="table table-striped table-hover">
      <thead>
        <tr>
            <th>S.N.</th>
            <th>Title</th>
            <th>Author</th>
            <th>Publisher</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
      </thead>
      <tbody>@foreach($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>{{$product->title}}</td>
            @php
                $author=DB::table('authors')->find($product->author_id);
                $publisher=DB::table('publishers')->find($product->publisher_id);
            @endphp
            <td>{{$author->name}}</td>
            <td>{{$publisher->title}}</td>
            <td>{{$product->price}}</td>
            <td>
                <a href="{{route('oldsale.show',$product->id)}}" class="btn btn-warning btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>
                <form method="POST" action="{{route('oldsale.destroy',[$product->id])}}">
                  @csrf
                  @method('delete')
                      <button class="btn btn-danger btn-sm dltBtn" data-id={{$product->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                </form>
            </td>
        </tr>
    @endforeach
      </tbody>
    </table>
    @endif
    <section class="confirmation_part section_padding">
      <div class="order_boxes">
        <div class="row">
          <div class="col-lg-6 col-lx-4">
            <div class="order-info">
              <h4 class="text-center pb-4">OLD BOOK INFORMATION</h4>
              <table class="table">
                    <tr class="">
                        <td>Book Title</td>
                        <td class="invoice">{{$oldBook->title}}</td>
                    </tr>
                    <tr>
                        <td>Book Summary</td>
                        <td>{{$oldBook->summary}}</td>
                    </tr>
                    <tr>
                        <td>Book Description</td>
                        <td>{{$oldBook->description}}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>{{$oldBook->status}}</td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td class="amount">{{number_format($oldBook->price,2)}}</td>
                    </tr>
                    {{-- <tr>
                        <td>Payment Status</td>
                        @if($order->payment_status=='unpaid')
                        <td>
                            <span class="badge badge-danger badge-sm">{{$order->payment_status}} </span>
                            <span>
                                <button token="{{csrf_token()}}" order="{{$order->order_number}}" endpoint="{{route('payajax')}}" id="sslczPayBtn" class="btn btn-success btn-sm">Pay Now</button>
                            </span>
                        </td>
                        @else
                        <td class="badge badge-success badge-sm">{{$order->payment_status}}</td>
                        @endif
                    </tr> --}}
              </table>
            </div>
          </div>

          {{-- <div class="col-lg-6 col-lx-4">
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
          </div> --}}
        </div>
      </div>
    </section>
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
  <!-- Page level plugins -->
  <script src="{{asset('backend/vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{asset('backend/js/demo/datatables-demo.js')}}"></script>
<script>
    $(document).ready(function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
        $('.dltBtn').click(function(e){
          var form=$(this).closest('form');
            var dataID=$(this).data('id');
            // alert(dataID);
            e.preventDefault();
            swal({
                  title: "Are you sure?",
                  text: "Once deleted, you will not be able to recover this data!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
              })
              .then((willDelete) => {
                  if (willDelete) {
                     form.submit();
                  } else {
                      swal("Your data is safe!");
                  }
              });
        })
    })
</script>
@endpush
