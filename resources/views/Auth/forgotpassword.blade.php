@extends('layout.authLayout')

@section('title')
    Forgot Password
@endsection

@section('content')

    <!-- Nested Row within Card Body -->
    <div class="row">
        <div class="col-lg-5 d-none d-lg-block bg-register-image">

        </div>
        <div class="col-lg-7">
            <div class="p-5">
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">Forgot Your Password?</h1>
                    <p class="mb-4">We get it, stuff happens. Just enter your email address below
                        and we'll send you a link to reset your password!</p>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (!empty($success))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ $success }}</li>
                        </ul>
                    </div>
                @endif
                <form class="user" action="{{ url('auth/submitForgetPasswordForm') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <input type="email" class="form-control form-control-user" id="email" name="email"
                            value="{{ old('email') }}" placeholder="Email Address">
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary btn-user btn-block">Send Password Reset
                        Link</button>
                </form>
                <hr>
                <div class="text-center">
                    <a class="small" href="{{ url('') }}">Back to Login!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
