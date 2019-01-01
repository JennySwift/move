@extends('auth.master')

@section('content')

    <div>
        <h1>Login to Move</h1>
        <div id="login">
            <div class="flex">
                @include('auth.errors')

                <div>
                    To demo the app, log in with
                    <ul>
                        <li>email: jennyswiftcreations@gmail.com</li>
                        <li>password: abcdefg</li>
                    </ul>
                    <p>Please be aware that for the demo, the data is public, so others may see changes you make, and vice versa.</p>
                </div>

                <form class="form-horizontal" role="form" method="POST" action="/login">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <ul>
                        <div>
                            <label>Email</label>
                            <input type="email" name="email"  value="{{ old('email') }}"/>
                        </div>

                        <div>
                            <label>Password</label>
                            <input type="password" name="password"/>
                        </div>

                        <div>
                            <div>Remember me</div>
                            <input type="checkbox" name="toggle" value="yes"><i class="toggle-icon"></i>
                        </div>

                    </ul>

                    <div>
                        <input type="submit" class="button" value="Login">
                    </div>

                    <div>
                        <a href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>


    {{--Commenting out code here that uses Framework7 because it broke (after upgrading?)--}}
    {{--<f7-page>--}}
        {{--<f7-navbar>--}}
            {{--<f7-nav-left></f7-nav-left>--}}
            {{--<f7-nav-title>Login to Move</f7-nav-title>--}}
            {{--<f7-nav-right></f7-nav-right>--}}
        {{--</f7-navbar>--}}
        {{--<div id="login">--}}
            {{--<div class="flex">--}}
                {{--@include('auth.errors')--}}

                {{--<f7-block>--}}
                    {{--To demo the app, log in with--}}
                    {{--<ul>--}}
                        {{--<li>email:jennyswiftcreations@gmail.com</li>--}}
                        {{--<li>password: abcdefg</li>--}}
                    {{--</ul>--}}
                    {{--<p>Please be aware that for the demo, the data is public, so others may see changes you make, and vice versa.</p>--}}
                {{--</f7-block>--}}

                {{--<form class="form-horizontal" role="form" method="POST" action="/login">--}}
                    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}


                    {{--<f7-list no-hairlines-md inset>--}}
                        {{--<f7-list-item>--}}
                            {{--<f7-label>Email</f7-label>--}}
                            {{--<f7-input type="email" name="email"  value="{{ old('email') }}" clear-button=""></f7-input>--}}
                        {{--</f7-list-item>--}}

                        {{--<f7-list-item>--}}
                            {{--<f7-label>Password</f7-label>--}}
                            {{--<f7-input type="password" name="password" clear-button=""></f7-input>--}}
                        {{--</f7-list-item>--}}

                        {{--<f7-list-item title="Remember Me">--}}
                            {{--<div class="item-after">--}}
                                {{--<label class="toggle toggle-init">--}}
                                    {{--<input type="checkbox" name="toggle" value="yes"><i class="toggle-icon"></i>--}}
                                {{--</label>--}}
                            {{--</div>--}}
                        {{--</f7-list-item>--}}

                    {{--</f7-list>--}}

                    {{--<f7-block>--}}
                        {{--<input type="submit" class="button" value="Login">--}}
                    {{--</f7-block>--}}

                    {{--<f7-block>--}}
                        {{--<f7-link external href="{{ route('password.request') }}">--}}
                            {{--Forgot Your Password?--}}
                        {{--</f7-link>--}}
                    {{--</f7-block>--}}

                {{--</form>--}}
            {{--</div>--}}
        {{--</div>--}}

    {{--</f7-page>--}}

@endsection
