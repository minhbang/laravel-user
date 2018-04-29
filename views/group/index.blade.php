@extends('kit::backend.layouts.master')
@section('content')
    <div class="panel panel-default panel-nestable panel-sidebar">
        <div class="panel-heading clearfix">
            <div class="loading hidden"></div>
            <a href="{{route('backend.user_group.create')}}"
               class="modal-link btn btn-success btn-xs"
               data-title="{{__('Create new :name', ['name' => __('User group')])}}"
               data-label="{{__('Save')}}"
               data-icon="align-justify"
               data-width="large"
            >
                <span class="glyphicon glyphicon-plus-sign"></span> {{__('Create Group')}}
            </a>
            <a href="#" data-action="collapseAll" class="nestable_action btn btn-default btn-xs">
                <span class="glyphicon glyphicon-circle-arrow-up"></span>
            </a>
            <a href="#" data-action="expandAll" class="nestable_action btn btn-default btn-xs">
                <span class="glyphicon glyphicon-circle-arrow-down"></span>
            </a>
        </div>
        <div class="panel-body">
            <div class="row row-height">
                <div class="row-height-inside">
                    <div class="col-xs-9 col-sm-9 col-md-10 col-height">
                        <div class="panel-body-content left">
                            <div id="nestable-container" class="dd">{!! $nestable !!}</div>
                        </div>
                    </div>
                    <div class="col-xs-3 col-sm-3 col-md-2 col-height panel-body-sidebar right">
                        <ul class="nav nav-tabs tabs-right">
                            @foreach($types as $type => $title)
                                <li{!! $current ==$type ? ' class="active"':'' !!}>
                                    <a href="{{route('backend.user_group.type', ['type' =>$type])}}">{{$title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
        <div class="panel-footer">
            <span class="glyphicon glyphicon-info-sign"></span> {{ __('Drag / Drop Group to move to new location')}}
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.panel-nestable').mbNestable({
            url: {
                data: '{{route('backend.user_group.data', ['type' => $current])}}',
                move: '{{route('backend.user_group.move')}}',
                delete: '{{route('backend.user_group.destroy', ['user_group' => '__ID__'])}}'
            },
            max_depth:{{ $max_depth }},
            trans: {
                name: '{{ __('User group') }}'
            },
            csrf_token: window.Laravel.csrfToken
        });
        $.fn.mbHelpers.reloadPage = function () {
            $('.panel-nestable').mbNestable('reload');
        }
    });
</script>
@endpush