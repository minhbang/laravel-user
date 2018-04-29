@extends('kit::backend.layouts.basic')

@section('heading', __('Change personal information'))

@section('classes', 'col-md-6 col-md-offset-3')

@section('content')
    {!! Form::model($account, ['class' => 'form-horizontal']) !!}
    <div class="hr-line-solid"></div>
    <div class="form-group">
        <label class="col-md-4 control-label">{{ __('Username') }}</label>
        <div class="col-md-6">
            <div class="text-primary form-control">{{ $account->username }}</div>
        </div>
    </div>
    <div class="form-group{{ $errors->has('name') ? ' has-error':'' }}">
        {!! Form::label('name', __('Fullname'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('name', null, ['class' => 'form-control']) !!}
            @if($errors->has('name'))
                <p class="help-block">{{ $errors->first('name') }}</p>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('email') ? ' has-error':'' }}">
        {!! Form::label('email', __('E-mail'), ['class' => 'col-md-4 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('email', null, ['class' => 'form-control']) !!}
            @if($errors->has('email'))
                <p class="help-block">{{ $errors->first('email') }}</p>
            @endif
        </div>
    </div>
    <div class="hr-line-solid"></div>
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-success"
                    style="margin-right: 15px;">{{ __('Save') }}</button>
            <a href="{{ URL::previous()}}">{{ __('Cancel') }}</a>
        </div>
    </div>
    {!! Form::close() !!}
@endsection
