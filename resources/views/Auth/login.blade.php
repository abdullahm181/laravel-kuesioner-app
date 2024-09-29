@extends('layout.authLayout')

@section('title')
Login
@endsection

@section('content')

<!-- Nested Row within Card Body -->
<div class="row">
    <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
    <div class="col-lg-6">
        <div class="p-5">
            <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
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
            @if(!empty($message))
            <div class="alert alert-success">
                <ul>
                    <li>{{$message}}</li>
                </ul>
            </div>
            @endif
            <form class="user"  action="" method="POST" >
                @csrf
                <div class="form-group">
                    <input type="email" class="form-control form-control-user"
                        id="email" value="{{old('email')}}" name="email" aria-describedby="emailHelp"
                        placeholder="Enter Email Address...">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control form-control-user"
                        id="password" name="password" placeholder="Password">
                </div>
                <button name="submit" type="submit" class="btn btn-primary btn-user btn-block">Login</button>
            </form>
            <hr>
            <div class="text-center">
                <a class="small" href="{{ url("auth/forgotPasswordPage") }}">Forgot Password?</a>
            </div>
            <div class="text-center">
                <a class="small" href="{{ url("auth/registerPage") }}">Create an Account!</a>
            </div>
        </div>
    </div>
</div>
@endsection
