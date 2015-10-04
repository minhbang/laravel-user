@extends('backend.layouts.blank')

@section('content')
	<div class="middle-box text-center loginscreen">
		<div>
			<div class="logo">
				<div class="app-name"> {{setting('app.name_short') }}</div>
			</div>
			<h3>{{trans('user::account.reset_password')}}</h3>
            @if (count($errors) > 0)
				<div class="alert alert-danger">
					<strong>{{ trans('errors.whoops') }}</strong><br>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
				</div>
			@endif
			{!! Form::open(['class' => 'm-t']) !!}
            {!! Form::hidden('token', $token) !!}
			<div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
				{!! Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => trans('user::account.email_reset_password'), 'required' => '']) !!}
			</div>
            <div class="form-group{{ $errors->has('password') ? ' has-error':'' }}">
                {!! Form::password('password', ['class' => 'form-control', 'placeholder' => trans('user::account.password_new'), 'required' => '']) !!}
            </div>
            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error':'' }}">
                {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('user::account.password_confirmation'), 'required' => '']) !!}
            </div>
			<button type="submit" class="btn btn-primary block full-width m-b">{{ trans('common.save') }}</button>
			{!! Form::close() !!}
			<p class="m-t">
				<small>{{setting('app.name_long') }} &copy; 2015</small>
			</p>

		</div>
	</div>
@endsection
