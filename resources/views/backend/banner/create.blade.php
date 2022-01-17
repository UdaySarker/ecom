@extends('backend.layouts.master')
@section('title','BoiBazar|| Create Banner')
@section('main-content')

<div class="card">
    <h5 class="card-header">Add Banner</h5>
    <div class="card-body">
      <form method="post" action="{{route('banner.store')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="form-group">
            <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
            <input id="inputTitle" type="text" name="title" placeholder="Enter title"  value="{{old('title')}}" class="form-control">
        @error('title')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="inputDesc" class="col-form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{old('description')}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <!-- photo section -->
        <div class="form-group">
            <div id="holder" style="margin-top:15px;max-height:100px;"></div>
            @error('banner_img')
            <span class="text-danger">{{$message}}</span>
            @enderror
            </div>
            <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
            <div class="custom-file">
                <label for="" class="custom-file-label">Choose File</label>
                <input id="thumbnail" class="custom-file-input" type="file" name="banner_img" onchange="loadFile(event)">
        </div>
        <div class="form-group">
            <img id="output" class="mt-3">
        </div>
        <div class="form-group">
          <label for="status" class="col-form-label">Status <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Reset</button>
           <button class="btn btn-success" type="submit">Submit</button>
        </div>
      </form>
    </div>
</div>

@endsection
<script>
    var loadFile = function(event) {
      var output = document.getElementById('output');
      output.style.width='200px';
      output.style.height='200px';
      output.src = URL.createObjectURL(event.target.files[0]);
      output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
      }
    };
  </script>
