@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Add Product</h5>
    <div class="card-body">
      <form method="post" action="{{route('product.update',$product->id)}}" enctype="multipart/form-data">
        {{csrf_field()}}
        @method('PATCH')
        <div class="row">
            {{-- start of first column --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label pt-0">Title <span class="text-danger">*</span></label>
                    <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{$product->title}}" class="form-control">
                    @error('title')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="description" class="col-form-label pt-0">Description</label>
                    <textarea class="form-control" id="description" name="description">{{$product->description}}</textarea>
                    @error('description')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="summary" name="summary">{{$product->summary}}</textarea>
                    @error('summary')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <label for="" class="custom-file-label">Choose File</label>
                        <input type="file" name="product_img" class="custom-file-input" id="inputPhoto">
                    </div>

                    <div id="holder" style="margin-top:15px;max-height:100px;"></div>
                        @error('photo')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>
                </div>
            {{-- end of first column --}}



            {{-- start of second column --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cat_id">Category <span class="text-danger">*</span></label>
                    <select name="cat_id" id="cat_id" class="form-control">
                        <option value="">--Select any category--</option>
                        @foreach($categories as $key=>$cat_data)
                            <option value='{{$cat_data->id}}' {{$product->cat_id==$cat_data->id?'selected':''}}>{{$cat_data->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group d-none" id="child_cat_div">
                    <label for="child_cat_id">Sub Category</label>
                    <select name="child_cat_id" id="child_cat_id" class="form-control">
                        <option value="">--Select any category--</option>
                        {{-- @foreach($parent_cats as $key=>$parent_cat)
                            <option value='{{$parent_cat->id}}'>{{$parent_cat->title}}</option>
                        @endforeach --}}
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="size">Pages<span class="text-danger">*</span></label>
                              <input type="number" class="form-control" name="pages" value="{{$product->pages}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="condition">Condition</label>
                            <select name="condition" class="form-control">
                                <option value="">--Select Condition--</option>
                                <option value="default" {{$product->condition=='default'?'selected':''}}>Default</option>
                                <option value="new" {{$product->condition=='new'?'selected':''}}>New</option>
                                <option value="best-seller" {{$product->condition=='best-seller'?'selected':''}}>Best Seller</option>
                                <option value="trending" {{$product->condition=='trending'?'selected':''}}>Trending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status" class="col-form-label pt-0">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control">
                                <option value="active" {{$product->status=='active'?'selected':''}}>Active</option>
                                <option value="inactive" {{$product->status=='inactive'?'selected':''}}>Inactive</option>
                            </select>
                              @error('status')
                              <span class="text-danger">{{$message}}</span>
                              @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group" style="" >
                    <label for="publisher">Publisher</label>
                    {{-- {{$brands}} --}}

                    <select name="publisher_id" class="form-control">
                        <option value="">--Select Publisher--</option>
                       @foreach($publishers as $publisher)
                        <option value="{{$publisher->id}}" {{$product->publisher_id==$publisher->id ?'selected':''}}>{{$publisher->title}}</option>
                       @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <select name="author_id" id="author" class="form-control">
                        <option value="">--Select Author--</option>
                        @foreach ($authors as $author)
                            <option value="{{$author->id}}" {{$product->author_id==$author->id?'selected':''}}>{{$author->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="padding-top: 31px">
                    <label for="is_featured">Is Featured</label><br>
                    <input type="checkbox" name='is_featured' id='is_featured' value='1' checked> Yes
                </div>
            </div>

            {{-- end of second column --}}
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="price" class="col-form-label">Price<span class="text-danger">*</span></label>
                    <input id="price" type="number" name="price" placeholder="Enter price"  value="{{$product->price}}" class="form-control">
                    @error('price')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="discount" class="col-form-label">Discount(%)</label>
                    <input id="discount" type="number" name="discount" min="0" max="100" placeholder="Enter discount"  value="{{$product->discount}}" class="form-control">
                    @error('discount')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col" style="padding-top:6px">
                <div class="form-group">
                    <label for="stock">Quantity <span class="text-danger">*</span></label>
                    <input id="quantity" type="number" name="stock" min="0" placeholder="Enter quantity"  value="{{$product->stock}}" class="form-control">
                    @error('stock')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>
              {{-- {{$categories}} --}}

        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
  $('#cat_id').change(function(){
    var cat_id=$(this).val();
    // alert(cat_id);
    if(cat_id !=null){
      // Ajax call
      $.ajax({
        url:"/admin/category/"+cat_id+"/child",
        data:{
          _token:"{{csrf_token()}}",
          id:cat_id
        },
        type:"POST",
        success:function(response){
          if(typeof(response) !='object'){
            response=$.parseJSON(response)
          }
          // console.log(response);
          var html_option="<option value=''>----Select sub category----</option>"
          if(response.status){
            var data=response.data;
            // alert(data);
            if(response.data){
              $('#child_cat_div').removeClass('d-none');
              $.each(data,function(id,title){
                html_option +="<option value='"+id+"'>"+title+"</option>"
              });
            }
            else{
            }
          }
          else{
            $('#child_cat_div').addClass('d-none');
          }
          $('#child_cat_id').html(html_option);
        }
      });
    }
    else{
    }
  })
</script>
@endpush
