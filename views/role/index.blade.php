@extends('backend.layouts.main')
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! trans('user::role.list') !!}</h5>
        </div>
        <div class="ibox-content">
            <table class="table table-hover table-striped table-bordered">
                <thead>
                <tr>
                    <th class="min-width">#</th>
                    <th class="text-center">{{trans('user::role.roles')}}</th>
                    <th class="text-center min-width">{{trans('user::role.level')}}</th>
                    <th class="text-center min-width">{{trans('user::role.attached')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $group => $items)
                    <tr>
                        <td colspan="4" class="text-uppercase text-primary bg-warning">
                            <strong>{{trans("user::role.{$group}.title")}}</strong>
                        </td>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach($items as $name => $role)
                        <tr>
                            <td class="min-width">{{$i++}}</td>
                            <td><a href="{{$role->url}}">{{$role->title}}</a></td>
                            <td class="min-width">{{$role->level}}</td>
                            <td class="min-width text-center text-danger">
                                <strong>{{$role->countUsers() ?: ''}}</strong>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            <div class="alert alert-success"><em>{{trans('user::role.note')}}</em></div>
        </div>
    </div>
@endsection