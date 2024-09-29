@extends('layout.authLayout')

@section('title')
Reset Password
@endsection

@section('content')

 <!-- Nested Row within Card Body -->
 <div class="row">
  <div class="col-lg-5 d-none d-lg-block bg-register-image">

  </div>
  <div class="col-lg-7">
      <div class="p-5">
          <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">Reset Password Page!</h1>
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
          <form class="user" action="{{ url("auth/submitResetPasswordForm") }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
              <div class="form-group">
                  <input type="email" class="form-control form-control-user" id="email" name="email" alue="{{ $request->email ?? old('email') }}" required  placeholder="Email Address" autofocus>
              </div>
              <div class="form-group row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                </div>
                <div class="col-sm-6">
                    <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" placeholder="Repeat Password">
                </div>
            </div>
              <button name="submit" type="submit" class="btn btn-primary btn-user btn-block">Send Password Reset Link</button>
          </form>
          <hr>
          <div class="text-center">
              <a class="small" href="{{ url("") }}">Back to Login!</a>
          </div>
      </div>
  </div>
</div>
@endsection


