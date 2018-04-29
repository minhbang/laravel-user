@extends('kit::backend.layouts.modal')
@section('content')
    <table class="table table-hover table-striped table-bordered table-detail">
        <tr>
            <td>ID</td>
            <td><strong>{{$user->id}}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Typp') }}</td>
            <td class="text-navy"><strong>{{ $user->type_name }}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Username') }}</td>
            <td><strong>{{ $user->username }}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Fullname') }}</td>
            <td><strong>{{ $user->name }}</strong></td>
        </tr>
        <tr>
            <td>{{ __('E-mail') }}</td>
            <td><strong>{{ $user->email }}</strong></td>
        </tr>
        <tr>
            <td>{{ __('Roles') }}</td>
            <td>{!! $user->present()->roles !!}</td>
        </tr>
        <tr>
            <td>{{ __('Created at') }}</td>
            <td>{!! $user->present()->createdAt !!}</td>
        </tr>
        <tr>
            <td>{{ __('Updated at') }}</td>
            <td>{!! $user->present()->updatedAt !!}</td>
        </tr>
    </table>
@stop