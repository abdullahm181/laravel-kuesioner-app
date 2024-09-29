@extends('layout.authLayout')

@section('title')
Register
@endsection

@section('content')

 <!-- Nested Row within Card Body -->
 <div class="row">
  <div class="col-lg-5 d-none d-lg-block bg-register-image">

  </div>
  <div class="col-lg-7">
      <div class="p-5">
          <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
          </div>
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $item)
                        <li>{{$item}}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if(!empty($success))
            <div class="alert alert-success">
                <ul>
                    <li>{{$success}}</li>
                </ul>
            </div>
            @endif
          <form class="user" action="{{ url("auth/register") }}" method="POST">
            @csrf
              <div class="form-group">
                <input type="text" class="form-control form-control-user" id="name" name="name" value="{{old('name')}}"placeholder="name">
              </div>
              <div class="form-group">
                  <input type="email" class="form-control form-control-user" id="email" name="email" value="{{old('email')}}" placeholder="Email Address">
              </div>
              <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                      <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                  </div>
                  <div class="col-sm-6">
                      <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" placeholder="Repeat Password">
                  </div>
              </div>
              <button name="submit" type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
          </form>
          <hr>
          <div class="text-center">
              <a class="small" href="{{ url("auth/forgotPasswordPage") }}">Forgot Password?</a>
          </div>
          <div class="text-center">
              <a class="small" href="{{ url("") }}">Already have an account? Login!</a>
          </div>
      </div>
  </div>
</div>
@endsection


