@extends('auth.master')

@section('content')
    <div id="login">
        <div class="flex">
            @include('auth.errors')

            <form class="form-horizontal" role="form" method="POST" action="/login">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="email" class="text-input" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="control-label">Password</label>
                    <input type="password" class="text-input" name="password">
                </div>

                <div id="remember-me-container" class="form-group">
                    <input type="checkbox" name="remember" id="remember-me">
                    <label for="remember-me">Remember Me</label>
                </div>

                <div class="form-group">
                    <div>
                        <button type="submit" class="btn btn-success" style="margin-right: 15px;">
                            Login
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
