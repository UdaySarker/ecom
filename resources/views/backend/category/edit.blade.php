@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Category</h5>
    <div class="card-body">
      <form method="post" action="{{route('category.update',$category->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{$category->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary</label>
          <textarea class="form-control" id="summary" name="summary">{{$category->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="">Is Parent: </label>
          @if($category->is_parent==1)
            <h5 id="parent_enabled" class="badge badge-success">Yes</h5>
          @else
            <h5 class="badge badge-primary">Not A Parent</h5>
          @endif

        </div>
        <div class="form-group enable_parent">
            <label for="is_parent">Make as Parent</label>
            <input type="checkbox" name="is_parent" id="enable_parent">
        </div>
        {{-- {{$parent_cats}} --}}
        {{-- {{$category}} --}}
        <div class="form-group" id='parent_cat_div'>
          <label for="parent_id">Parent Category</label>
          <select name="parent_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($parent_cats as $key=>$parent_cat)
                  <option value='{{$parent_cat->id}}' {{(($parent_cat->id==$category->parent_id) ? 'selected' : '')}}>{{$parent_cat->title}}</option>
              @endforeach
          </select>
          @error('parent_id')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>`
        <div class="form-group">
          <label>Photo<span class="text-danger">*</span></label>
          <div class="custom-file">
              <label for="" class="custom-file-label">Choose File</label>
            <input type="file" class="custom-file-input" name="category_img">
          </div>
        </div>
        <div id="holder" style="margin-top:15px;max-height:100px;">
          @error('category_img')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active" {{(($category->status=='active')? 'selected' : '')}}>Active</option>
              <option value="inactive" {{(($category->status=='inactive')? 'selected' : '')}}>Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('scripts')
<script>

    $(document).ready(function(){
      var html=`<div class="form-group" id='parent_cat_div'>
          <label for="parent_id">Parent Category</label>
          <select name="parent_id" class="form-control">
              <option value="">--Select any category--</option>
              @foreach($parent_cats as $key=>$parent_cat)

                  <option value='{{$parent_cat->id}}' {{(($parent_cat->id==$category->parent_id) ? 'selected' : '')}}>{{$parent_cat->title}}</option>
              @endforeach
          </select>
        </div>`;
        if($('#parent_enabled').text()==='Yes'){
            $('#enable_parent').parent().remove();
        }
        $('input[type="checkbox"]').click(function(){
            this.value=this.checked?1:0;
            if($(this).prop("checked") == false){
                $(html).insertAfter('.enable_parent');
            }else{
                $('#parent_cat_div').remove();
            }
        });
    });
    //   $('#is_parent').change(function(){
    //     var is_checked=$('#is_parent').prop('checked');
    //     // alert(is_checked);
    //     if(is_checked){
    //       $('#parent_cat_div').addClass('d-none');
    //       $('#parent_cat_div').val('');
    //     }
    //     else{
    //       $('#parent_cat_div').removeClass('d-none');
    //     }
    //   })
</script>
@endpush
