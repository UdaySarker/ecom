@extends('backend.layouts.master')
@section('title','Update a Post')
@section('main-content')

<div class="card">
    <h5 class="card-header">Update Post</h5>
    <div class="card-body">
      <form method="post" action="{{route('post.update',$post->id)}}" enctype="multipart/form-data">
        {{csrf_field()}}
        @method('PATCH')
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Title <span class="text-danger">*</span></label>
          <input id="inputTitle" type="text" name="title" value="{{$post->title}}" class="form-control">
          @error('title')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="quote" class="col-form-label">Quote</label>
          <textarea class="form-control" id="quote" name="quote">{{$post->quote}}</textarea>
          @error('quote')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="summary" class="col-form-label">Summary <span class="text-danger">*</span></label>
          <textarea class="form-control" id="summary" name="summary">{{$post->summary}}</textarea>
          @error('summary')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="description" class="col-form-label">Description</label>
          <textarea class="form-control" id="description" name="description">{{$post->description}}</textarea>
          @error('description')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group">
            <label for="post_cat_id">Category <span class="text-danger">*</span></label>
            <select name="post_cat_id" class="form-control">
                <option value="">--Select any category--</option>
                @foreach($categories as $key=>$data)
                    <option value='{{$data->id}}' {{(($data->id==$post->post_cat_id)? 'selected' : '')}}>{{$data->title}}</option>
                @endforeach
            </select>
          </div>

          @php
          $post_tags=explode(',',$post->tags);
          // dd($tags);
        @endphp
        <div class="form-group">
            <label for="tags">Tag</label>
            <select name="tags[]" multiple  data-live-search="true" class="form-control selectpicker">
                <option value="">--Select any tag--</option>
                @foreach($tags as $key=>$data)

                <option value="{{$data->title}}"  {{(( in_array( "$data->title",$post_tags ) ) ? 'selected' : '')}}>{{$data->title}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="added_by">Author</label>
            <select name="added_by" class="form-control">
                <option value="">--Select any one--</option>
                @foreach($users as $key=>$data)
                  <option value='{{$data->id}}' {{(($post->added_by==$data->id)? 'selected' : '')}}>{{$data->name}}</option>
                @endforeach
            </select>
          </div>
        <div class="form-group">
            <label for="">Upload Photo</label>
            <div class="custom-file">
                <label class="custom-file-label" for="customFile">Choose file</label>
                <input type="file" name="photo" class="custom-file-input" id="customFile">

              </div>
        </div>
        {{-- <div id="holder" style="margin-top:15px;max-height:100px;"></div>
          @error('photo')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div> --}}

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

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
@endpush
