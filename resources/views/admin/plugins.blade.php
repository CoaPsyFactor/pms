@extends('admin')

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            var $panel = $('div.plugin-panel');

            var translations = {
                active: $panel.data('statusActive'),
                inActive: $panel.data('statusInactive'),
                activate: $panel.data('buttonActivate'),
                deactivate: $panel.data('buttonDeactivate'),
                saved: $panel.data('settingsSaved'),
                notSaved: $panel.data('settingsNotSaved')
            };

            $(document).on('click', 'button.status', function (event) {
                event.preventDefault();

                event.stopImmediatePropagation();

                var pluginId = $(this).data('id');

                $.ajax({
                    url: '{{ route('api.admin.plugin.toggle') }}',
                    data: {id: pluginId},
                    type: 'PUT',
                    dataType: 'json',
                    success: function (response) {
                        $('span.message')
                                .addClass('label-success')
                                .removeClass('label-danger')
                                .text(response.message ? response.message : translations.saved)
                                .fadeIn();

                        setTimeout(function () {
                            $('span.message').fadeOut();
                        }, 3000);

                        $('td.plugin-status-' + pluginId).text(response.active ? translations.active : translations.inActive);

                        $(this)
                            .removeClass(response.active ? 'btn-success' : 'btn-danger')
                            .addClass(response.active ? 'btn-danger' : 'btn-success')
                            .text(response.active ? translations.deactivate : translations.activate);

                    }.bind(this),
                    error: function (xhr) {
                        response = xhr.responseJSON || {};

                        $('span.message')
                                .addClass('label-danger')
                                .removeClass('label-success')
                                .text(response.message ? response.message : translations.notSaved)
                                .fadeIn();

                        setTimeout(function () {
                            $('span.message').fadeOut();
                        }, 3000);
                    }
                });
            });

            $(document).on('click', 'button.options', function (event) {
                event.preventDefault();

                event.stopImmediatePropagation();

                var pluginId = $(this).data('id');

                $.ajax({
                    url: '/api/admin/plugin/options',
                    data: {id: pluginId},
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection

@section('sidebarTitle')
    {{ trans('admin.plugin.options') }}
@endsection

@section('sidebarContent')
    {{ Form::open(['files' => true, 'url' => route('admin.plugins.install', ['lang' => app()->getLocale()]), 'class' => 'form-group']) }}
            <div class="row">
                <div class="col-md-12">
                    {{ Form::file('plugin', ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {{ Form::submit(trans('admin.plugin.install'), ['class' => 'btn btn-large btn-success form-control']) }}
                </div>
            </div>
            @if (request()->session()->get('plugin.install.error'))
                <div class="row text-center">
                    <span class="text-danger">
                        {{ request()->session()->get('plugin.install.error') }}
                    </span>
                </div>
            @elseif (request()->session()->get('plugin.install.success'))
                <div class="row text-center">
                    <span class="text-success">
                        {{ request()->session()->get('plugin.install.success') }}
                    </span>
                </div>
            @endif
        {{ Form::token() }}
    {{ Form::close() }}
@endsection

@section('content')
    <div
            class="panel panel-default plugin-panel"
            data-status-active="{{ trans_choice('admin.pluginStatus', 1) }}"
            data-status-inactive="{{ trans_choice('admin.pluginStatus', 0) }}"
            data-button-activate="{{ trans('admin.button.activate') }}"
            data-button-deactivate="{{ trans('admin.button.deactivate') }}"
            data-settings-saved="{{ trans('admin.settingsSaved') }}"
            data-settings-not-saved="{{ trans('admin.settingsNotSaved') }}"
    >
        <div class="panel-heading">
            {{ trans('admin.panelTitle') }} : {{ trans('admin.plugins') }}
            <span class="message label label-success pull-right"></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="text-left">{{ trans('admin.plugin.labels.status') }}</th>
                            <th class="text-center">{{ trans('admin.plugin.labels.name') }}</th>
                            <th class="text-center">{{ trans('admin.plugin.labels.base_class') }}</th>
                            <th class="text-right">{{ trans('admin.plugin.labels.actions') }}</th>
                        </tr>
                    </thead>
                    @foreach($plugins as $plugin)
                        <tr>
                            <td class="plugin-status-{{ $plugin->id }}">
                                {{ trans_choice('admin.pluginStatus', $plugin->active ? 1 : 0) }}
                            </td>
                            <td class="">
                                {{ $plugin->name }}
                            </td>
                            <td class="">
                                {{ $plugin->base_class }}
                            </td>
                            <td class="text-right">
                                @if ($plugin->active)
                                    <button data-id="{{ $plugin->id }}" class="btn btn-sm btn-danger status">
                                        {{ trans('admin.button.deactivate') }}
                                    </button>
                                @else
                                    <button data-id="{{ $plugin->id }}" class="btn btn-sm btn-success status">
                                        {{ trans('admin.button.activate') }}
                                    </button>
                                @endif
                                <button data-id="{{ $plugin->id }}" class="btn btn-sm btn-info options">
                                    {{ trans('admin.button.options') }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection