@extends('user.layouts.master')

@section('main-content')
 <!-- DataTales Example -->
 <div class="card shadow mb-4">
     <div class="row">
         <div class="col-md-12">
            @include('user.layouts.notification')
         </div>
     </div>
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary float-left">Old Book Lists</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        @if(count($products)>0)
        <table class="table table-bordered" id="order-dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>S.N.</th>
              <th>Title</th>
              <th>Author</th>
              <th>Publisher</th>
              <th>Price</th>
              <th>Status</th>
              <th>Quantity</th>
              <th>Current Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
                <th>S.N.</th>
                <th>Title</th>
                <th>Author</th>
                <th>Publisher</th>
                <th>Price</th>
                <th>Status</th>
                <th>Quantity</th>
                <th>Current Stock</th>
                <th>Action</th>
              </tr>
          </tfoot>
          <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->title}}</td>
                    @php
                        $author=DB::table('authors')->find($product->author_id);
                        $publisher=DB::table('publishers')->find($product->publisher_id);
                        $soldBook=DB::table('carts')->where('product_id',"=",$product->id)->get();
                    @endphp
                    <td>{{$author->name}}</td>
                    <td>{{$publisher->title}}</td>
                    <td>{{$product->price}}</td>
                    @if($product->status=='approve')
                    <td><span class="badge badge-success">{{$product->admin_status}}</span></td>
                    @elseif ($product->status=='reject')
                    <td><span class="badge badge-danger">{{$product->admin_status}}</span></td>
                    @else
                    <td><span class="badge badge-dark">{{$product->admin_status}}</span></td>
                    @endif
                    <td class="text-center">{{$product->stock}}</td>
                    <td class="text-center">{{$product->stock-count($soldBook)}}</td>
                    <td>
                        <a href="{{route('oldsale.show',$product->id)}}" class="btn btn-warning btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>
                        @if($product->admin_status!="approve")
                        <form method="POST" action="{{route('oldsale.destroy',[$product->id])}}">
                          @csrf
                          @method('delete')
                              <button class="btn btn-danger btn-sm dltBtn" data-id={{$product->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                        </form>
                        @endif
                    </td>
                </tr>
            @endforeach
          </tbody>
        </table>
        {{-- <span style="float:right">{{$orders->links()}}</span> --}}
        @else
          <h6 class="text-center">No orders found!!! Please order some products</h6>
        @endif
      </div>
    </div>
</div>
@endsection

@push('styles')
  <link href="{{asset('backend/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
  <style>
      div.dataTables_wrapper div.dataTables_paginate{
          display: none;
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

      $('#order-dataTable').DataTable( {
            "columnDefs":[
                {
                    "orderable":false,
                    "targets":[8]
                }
            ]
        } );

        // Sweet alert

        function deleteData(id){

        }
  </script>
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
