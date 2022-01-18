@extends('backend.layouts.master')
@section('title','BoiBazar || Update User')
@section('main-content')

<div class="card">
    <h5 class="card-header">Update User</h5>
    <div class="card-body">
      <form method="post" action="{{route('users.update',$user->id)}}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="inputTitle" class="col-form-label">Name</label>
                    <input id="inputTitle" type="text" name="name" value="{{$user->name}}"  class="form-control">
                @error('name')
                    <span class="text-danger">{{$message}}</span>
                @enderror
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-form-label">Password</label>
                        <input type="password" name="password" placeholder="Enter password"  value="{{old('password')}}" class="form-control">
                  @error('password')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="form-group">
                    <label for="inputPhoto" class="col-form-label">Photo</label>
                    <div class="custom-file">
                        <label for="" class="custom-file-label">Choose Photo...</label>
                        <input type="file" onchange="loadFile(event)"class="custom-file-input" name="profile_photo">
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="inputEmail" class="col-form-label">Email</label>
                    <input id="inputEmail" type="email" name="email" placeholder="Enter email"  value="{{$user->email}}" class="form-control">
                  @error('email')
                    <span class="text-danger">{{$message}}</span>
                  @enderror
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="role" class="col-form-label">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="">--Select A Role--</option>
                                <option value="admin" {{$user->role=='admin' ? 'selected':''}}>Admin</option>
                                <option value="user" {{$user->role=='user'? 'selected':''}}>User</option>
                            </select>
                          @error('role')
                            <span class="text-danger">{{$message}}</span>
                          @enderror
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="status" class="col-form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{$user->status=='active'?'selected':''}}>Active</option>
                                <option value="inactive" {{$user->status=='inactive'?'selected':''}}>Inactive</option>
                            </select>
                        @error('status')
                          <span class="text-danger">{{$message}}</span>
                        @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <img class="img_thumbnail" src="{{asset('storage/'.$user->photo)}}" id="output" style="width:200px;height:200px;">
                </div>
            </div>
        </div>
        {{--double column row end--}}
        <div>
        <img id="holder" style="margin-top:15px;max-height:100px;">
          @error('photo')
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
@push('scripts')
<script>
    var loadFile = function(event) {
        console.log(event.target.files)
      var output = document.getElementById('output');
      var outputText=document.getElementById('outputText');
      output.style.width='200px';
      output.style.height='200px';
      output.src = URL.createObjectURL(event.target.files[0]);
      outputText.textContent=event.target.files[0].name;
      output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
      }
    };
  </script>
@endpush
