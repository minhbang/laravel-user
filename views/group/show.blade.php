@extends('kit::backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$group->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::group.type') }}</td>
            <td class="text-navy"><strong>{{$group->type_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::group.full_name') }}</td>
            <td><strong>{{$group->full_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::group.system_name') }}</td>
            <td><strong>{{$group->system_name}}</strong></td>
        </tr>
        <tr><td colspan="2"><strong class="text-warning">{{trans('user::group.meta')}}</strong></td></tr>
        @include(config('user.group_meta.show'))
    </table>
@stop