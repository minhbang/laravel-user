@extends('kit::backend.layouts.master')
@section('content')
    <div class="ibox ibox-table">
        <div class="ibox-title">
            <h5>{!! __('User list') !!}</h5>
            <div class="buttons">
                {!! Html::linkButton('#', __('Filter'), ['class'=>'advanced_filter_collapse','type'=>'info', 'size'=>'xs', 'icon' => 'filter']) !!}
                {!! Html::linkButton('#', __('All'), ['class'=>'advanced_filter_clear', 'type'=>'warning', 'size'=>'xs', 'icon' => 'list']) !!}
                {!! Html::modalButton(
                    route('backend.user.create'),
                    __('Add User'),
                    [
                        'title' => __('Add User').": <span class=\"text-warning\">$typeName</span>",
                        'label' => __('Save'),
                        'icon'  => 'fa-user',
                    ],
                    ['type'=>'success', 'size'=>'xs', 'icon' => 'plus-sign']
                ) !!}
            </div>
        </div>
        <div class="ibox-content">
            <div class="bg-warning dataTables_advanced_filter hidden">
                <form class="form-horizontal" role="form">
                    {!! Form::hidden('filter_form', 1) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('filter_created_at', __('Created at'), ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::daterange('filter_created_at', [], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('filter_updated_at', __('Updated at'), ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-9">
                                    {!! Form::daterange('filter_updated_at', [], ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            {!! $html->table(['id' => 'user-manage']) !!}
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    window.datatableDrawCallback = function (dataTableApi) {
        dataTableApi.$('a.quick-update').quickUpdate({
            'url': '{{ route('backend.user.quick_update', ['user' => '__ID__']) }}',
            'container': '#user-manage',
            'dataTableApi': dataTableApi
        });
    };
    window.settings.mbDatatables = {
        trans: {
            name: '{{__('User')}}'
        }
    }
</script>
{!! $html->scripts() !!}
@endpush
