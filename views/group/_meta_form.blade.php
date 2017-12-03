<div class="form-group{{ $errors->has('short_name') ? ' has-error':'' }}">
    {!! Form::label('short_name', trans('user::group.short_name'), ['class' => 'col-xs-3 control-label']) !!}
    <div class="col-xs-9">
        {!! Form::text('short_name', null, ['class' => 'form-control']) !!}
        @if($errors->has('short_name'))
            <p class="help-block">{{ $errors->first('short_name') }}</p>
        @endif
    </div>
</div>
<div class="form-group{{ $errors->has('acronym_name') ? ' has-error':'' }}">
    {!! Form::label('acronym_name', trans('user::group.acronym_name'), ['class' => 'col-xs-3 control-label']) !!}
    <div class="col-xs-9">
        {!! Form::text('acronym_name', null, ['class' => 'form-control']) !!}
        @if($errors->has('acronym_name'))
            <p class="help-block">{{ $errors->first('acronym_name') }}</p>
        @endif
    </div>
</div>