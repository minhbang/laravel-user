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
            <td>{{ trans('user::group.short_name') }}</td>
            <td><strong>{{$group->short_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::group.acronym_name') }}</td>
            <td><strong>{{$group->acronym_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ trans('user::group.system_name') }}</td>
            <td><strong>{{$group->system_name}}</strong></td>
        </tr>
    </table>
@stop