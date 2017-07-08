@extends('kit::backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$user->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.type') }}</td>
            <td class="text-navy"><strong>{{ $user->type_name }}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.username') }}</td>
            <td><strong>{{ $user->username }}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.name') }}</td>
            <td><strong>{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::user.email') }}</td>
            <td><strong>{{ $user->email }}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('authority::common.roles') }}</td>
            <td>{!! $user->present()->roles !!}</td>
        </tr>
        <tr>
            <td>{{ trans('common.created_at') }}</td>
            <td>{!! $user->present()->createdAt !!}</td>
        </tr>
        <tr>
            <td>{{ trans('common.updated_at') }}</td>
            <td>{!! $user->present()->updatedAt !!}</td>
        </tr>
    </table>
@stop