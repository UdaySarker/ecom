@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Edit Post</h5>
    <div class="card-body">
    <form method="post" action="{{route('settings.update')}}" enctype="multipart/form-data">
        @csrf
        {{-- @method('PATCH') --}}
        {{-- {{dd($data)}} --}}
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="short_des" class="col-form-label">Short Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="quote" name="short_des">{{$data->short_des}}</textarea>
                    @error('short_des')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="description" class="col-form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description">{{$data->description}}</textarea>
                    @error('description')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Logo <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <label for="" class="custom-file-label">Choose Logo</label>
                        <input onchange="loadFile(event)" type="file" class="custom-file-input" name="logo">
                    </div>
                <div id="holder1" style="margin-top:15px;max-height:100px;"></div>
                  @error('logo')
                  <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo <span class="text-danger">*</span></label>
                      <div class="custom-file">
                          <label for="" class="custom-file-label">Choose Photo</label>
                          <input onchange="loadFile2(event)"type="file" name="photo" class="custom-file-input">
                      </div>
                  <div id="holder" style="margin-top:15px;max-height:100px;"></div>

                    @error('photo')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                  </div>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center">
                <img src="" id="outputLogo"class="" width="200px" height="200px" alt="">
            </div>
            <div class="col d-flex justify-content-center">
                <img src="" width="200px" id="outputPhoto" height="200px"alt="">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="address" class="col-form-label">Address <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="address" required value="{{$data->address}}">
                    @error('address')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                  </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="email" class="col-form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" required value="{{$data->email}}">
                    @error('email')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="phone" class="col-form-label">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="phone" required value="{{$data->phone}}">
                    @error('phone')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
            </div>
        </div>


        <div class="form-group mb-3">
           <button class="btn btn-success" type="submit">Update</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />

@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
<script>
    var loadFile = function(event) {
      var outputLogo = document.getElementById('outputLogo');
      outputLogo.style.width='200px';
      outputLogo.style.height='200px';
      outputLogo.src = URL.createObjectURL(event.target.files[0]);

      outputText.textContent=event.target.files[0].name;
      outputLogo.onload = function() {
        URL.revokeObjectURL(outputLogo.src) // free memory

      }
    };
    var loadFile2 = function(event) {

      var outputPhoto= document.getElementById('outputPhoto')
      outputPhoto.style.width='200px';
      outputPhoto.style.height='200px';
      outputPhoto.src = URL.createObjectURL(event.target.files[0]);
      outputPhoto.onload = function() {
         // free memory
        URL.revokeObjectURL(outputPhoto.src)
      }
    };
  </script>
@endpush
