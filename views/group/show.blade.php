@extends('kit::backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$group->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Type') }}</td>
            <td class="text-navy"><strong>{{$group->type_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Fullname') }}</td>
            <td><strong>{{$group->full_name}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('System name') }}</td>
            <td><strong>{{$group->system_name}}</strong></td>
        </tr>
        <tr><td colspan="2"><strong class="text-warning">{{__('Additional information')}}</strong></td></tr>
        @include(config('user.group_meta.show'))
    </table>
@stop