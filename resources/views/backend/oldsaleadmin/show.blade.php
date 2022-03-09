@extends('backend.layouts.master')
@section('main-content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col"><h4 class="card-title">Details of Old Book</h4></div>
            <div class="col"><a class="btn btn-info float-right" href="{{route('oldbooksale.index')}}">List</a></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 offset-md-3">
            <table class="table">
                <tr class="table-light">
                    <td>Book Title</td>
                    <td>:</td>
                    <td>{{$oldBook->title}}</td>
                </tr>
                <tr class="table-light">
                    <td>Book Summary</td>
                    <td>:</td>
                    <td>{{$oldBook->summary}}</td>
                </tr>
                <tr class="table-light">
                    <td>Book Description</td>
                    <td>:</td>
                    <td>{{$oldBook->description}}</td>
                </tr>
                <tr class="table-light">
                    <td>Pages</td>
                    <td>:</td>
                    <td>{{$oldBook->pages}}</td>
                </tr>
                <tr class="table-light">
                    <td>Condition</td>
                    <td>:</td>
                    <td>{{$oldBook->condition}}</td>
                </tr>
                @php
                    $user=DB::table('users')->find($oldBook->user_id);
                    $author=DB::table('authors')->find($oldBook->author_id);
                    $publisher=DB::table('publishers')->find($oldBook->publisher_id);
                @endphp
                <tr class="table-light">
                    <td>Author</td>
                    <td>:</td>
                    <td>{{$author->name}}</td>
                </tr>
                <tr class="table-light">
                    <td>Publisher</td>
                    <td>:</td>
                    <td>{{$publisher->title}}</td>
                </tr>
                <tr class="table-light">
                    <td>Uploaded By</td>
                    <td>:</td>
                    <td>{{$user->name}}</td>
                </tr>
                <tr class="table-light">
                    <td>Thumbnail</td>
                    <td>:</td>
                    <td><img class="img" height="50px" width="50px"src="{{asset('storage/'.$oldBook->photo)}}" alt=""></td>
                </tr>
                <tr class="table-light">
                    <td>Quantity</td>
                    <td>:</td>
                    <td>{{$oldBook->stock}}</td>
                </tr>
                @php
                    $category=DB::table('categories')->find($oldBook->cat_id);
                @endphp
                <tr class="table-light">
                    <td>Category</td>
                    <td>:</td>
                    <td>{{$category->title}}</td>
                </tr>
                <tr class="table-light">
                    <td>Current Status</td>
                    <td>:</td>
                    @if($oldBook->admin_status=='approve')
                    <td><span class="badge badge-success">{{$oldBook->admin_status}}</span></td>
                    @elseif ($oldBook->admin_status=='reject')
                    <td><span class="badge badge-danger">{{$oldBook->admin_status}}</span></td>
                    @else
                    <td><span class="badge badge-dark">{{$oldBook->admin_status}}</span></td>
                    @endif
                </tr>
                <tr class="table-light">
                    <td>Change Status</td>
                    <td>:</td>
                    <td>
                        <form action="{{route('oldbooksale.updateStatus',$oldBook->id)}}" method="post">
                            @csrf
                            <select name="admin_status" id="">
                                <option value="approve">Approve</option>
                                <option value="reject">Reject</option>
                            </select>
                            <input type="submit" value="Submit">
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .img:hover{
        -ms-transform: scale(5.5); /* IE 9 */
        -webkit-transform: scale(5.5); /* Safari 3-8 */
        transform: scale(5.5);
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
