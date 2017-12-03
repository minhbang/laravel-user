@extends('kit::backend.layouts.modal')
@section('content')
    {!! Form::model($group,['class' => 'form-horizontal','url' => $url, 'method' => $method]) !!}
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-xs-3 control-label">{{ trans('user::group.parent') }}</label>
                <div class="col-xs-9">
                    <p class="form-control-static text-primary">{{ $parent_title }}</p>
                </div>
            </div>
            <div class="form-group{{ $errors->has('full_name') ? ' has-error':'' }}">
                {!! Form::label('label', trans('user::group.full_name'), ['class' => 'col-xs-3 control-label']) !!}
                <div class="col-xs-9">
                    {!! Form::text('full_name', null, ['class' => 'has-slug form-control','data-slug_target' => "#system-name"]) !!}
                    @if($errors->has('full_name'))
                        <p class="help-block">{{ $errors->first('full_name') }}</p>
                    @endif
                </div>
            </div>
            <div class="form-group{{ $errors->has('system_name') ? ' has-error':'' }}">
                {!! Form::label('system_name', trans('user::group.system_name'), ['class' => 'col-xs-3 control-label']) !!}
                <div class="col-xs-9">
                    {!! Form::text('system_name', null, ['class' => 'form-control text-navy', 'id' => 'system-name']) !!}
                    @if($errors->has('system_name'))
                        <p class="help-block">{{ $errors->first('system_name') }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-xs-3 control-label"></label>
                <div class="col-xs-9">
                    <p class="form-control-static text-warning"><strong>{{trans('user::group.meta')}}</strong></p>
                </div>
            </div>
            @include(config('user.group_meta.form'))
        </div>
    </div>
    {!! Form::close() !!}
@stop