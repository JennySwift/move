@extends('auth.master')

@section('content')
    <f7-page>
        <f7-navbar>
            <f7-nav-left></f7-nav-left>
            <f7-nav-title>Password Reset</f7-nav-title>
            <f7-nav-right></f7-nav-right>
        </f7-navbar>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <f7-list no-hairlines-md inset>
                <f7-list-item>
                    <f7-label>Email</f7-label>
                    <f7-input type="email" name="email" value="{{ old('email') }}" required clear-button=""></f7-input>
                </f7-list-item>
            </f7-list>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                @endif
            </div>

            <f7-block>
                <button type="submit">
                    Send Password Reset Link
                </button>
            </f7-block>
        </form>

    </f7-page>
@endsection
