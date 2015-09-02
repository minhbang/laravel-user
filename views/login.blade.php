@extends('backend.layouts.blank')

@section('content')
    <div class="middle-box text-center loginscreen">
        <div>
            <div class="logo">
                <div class="app-name"> {{setting('app.name_short') }}</div>
            </div>
            <h3 class="text-danger">{{trans('backend.cpanel')}}</h3>
            @if ($message = Session::get('message'))
                <div class="alert alert-{{ $message['type'] }}">
                    {!! $message['content'] !!}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ trans('kit::errors.whoops') }}</strong><br>{{ $errors->has('msg') ? $errors->first('msg') : trans('kit::errors.input') }}
                </div>
            @endif
            {!! Form::open(['class' => 'm-t']) !!}
                <div class="form-group{{ $errors->has('username') ? ' has-error':'' }}">
                    {!! Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => trans('user::account.username'), 'required' => '']) !!}
                    @if($errors->has('username'))
                        <p class="help-block">{{ $errors->first('username') }}</p>
                    @endif
                </div>
                <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('user::account.password'), 'required' => '']) !!}
                    @if($errors->has('password'))
                        <p class="help-block">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                <div class="form-group">
                    <div class="checkbox">
                            <label>
                                {!! Form::checkbox('remember', '1', old('remember'),  ['id' => 'remember', 'class' => 'no-switch']) !!} {{ trans('user::account.remember') }}
                            </label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">{{ trans('user::account.login') }}</button>
                <a href="{{route('password.email')}}"><small>{{trans('user::account.forgot_password')}}</small></a>
            {!! Form::close() !!}
            <p class="m-t">
                <small>{{setting('app.name_long') }} &copy; 2015</small>
            </p>
        </div>
    </div>
@endsection
