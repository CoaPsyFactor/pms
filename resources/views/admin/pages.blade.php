@extends('admin')

@section('scripts')
    @parent
    <script type="text/javascript">
        (function () {
            var showMessage = function (message, type) {
                type = type || 'success';

                var $el = $('span.message');

                $el.removeClass('label-*');

                $el.addClass('label-' + type);

                $el.text(message || 'Unknown').fadeIn();

                setTimeout(function () {
                    $el.fadeOut();
                }, 3000);
            };

            $(document).on('click', 'button.page-remove', function (event) {
                event.preventDefault();

                event.stopImmediatePropagation();

                if (false === confirm('Are You sure?')) {
                    return;
                }

                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('api.admin.page.remove') }}',
                    type: 'DELETE',
                    data: {id: id},
                    dataType: 'json',
                    success: function (response) {
                        showMessage(response.message, 'success');

                        $(this).parents('.page-info-holder').remove();
                    }.bind(this),
                    error: function (xhr) {
                        response = xhr.responseJSON || {};

                        showMessage(response.message, 'danger');
                    }
                });
            });

            $(document).on('click', 'button.status', function (event) {
                event.preventDefault();

                event.stopImmediatePropagation();

                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route('api.admin.page.toggle') }}',
                    type: 'PUT',
                    data: { id: id },
                    dataType: 'json',
                    success: function (response) {

                        showMessage(response.message, 'success');

                        $('td.page-status-' + id).text(response.active ? '{{ trans_choice('admin.pluginStatus', 1) }}' : '{{ trans_choice('admin.pluginStatus', 0) }}');

                        $('button.status[data-id="' + id + '"]').toggleClass('hide');
                    }.bind(this),
                    error: function (xhr) {
                        response = xhr.responseJSON || {};

                        showMessage(response.message, 'danger');
                    }
                })
            });
        })();
    </script>
@endsection

@section('sidebarTitle')
    {{ trans('admin.sidebar.pages.title') }}
@endsection

@section('sidebarContent')
    <div class="text-center">
        <a href="{{ route('admin.page.new', ['lang' => app()->getLocale()]) }}" class="btn btn-primary text-center">
            {{ trans('admin.new.page') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-heading">
            {{ trans('admin.panelTitle') }} : {{ trans('admin.pages') }}
            <span class="message label label-success pull-right"></span>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th class="text-left">{{ trans('admin.plugin.labels.status') }}</th>
                        <th class="text-center">{{ trans('admin.page.label.title') }}</th>
                        <th class="text-center">{{ trans('admin.page.label.slug') }}</th>
                        <th class="text-right">{{ trans('admin.plugin.labels.actions') }}</th>
                    </tr>
                    </thead>
                    @foreach($pages as $page)
                        <tr class="page-info-holder">
                            <td class="page-status-{{ $page->id }}">
                                {{ trans_choice('admin.pluginStatus', $page->active ? 1 : 0) }}
                            </td>
                            <td class="">
                                {{ $page->title }}
                            </td>
                            <td class="">
                                @foreach($page->navigationLinks as $navigationLink)
                                    <span class="badge">
                                        <a href="/{{ app()->getLocale() }}/{{ $navigationLink->slug }}" target="_blank">
                                            {{ $navigationLink->slug }}
                                        </a>
                                    </span>
                                @endforeach
                            </td>
                            <td class="text-right">
                                <div class="pull-left">
                                    <a
                                        href="{{ route('admin.page.edit', ['lang' => app()->getLocale(), 'pageId' => $page->id]) }}"
                                        class="btn btn-sm btn-info"
                                        title="{{ trans('admin.button.edit') }}"
                                    >
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <button data-id="{{ $page->id }}" class="btn btn-sm btn-danger status deactivate {{ $page->active ? '' : 'hide' }}" title="{{ trans('admin.button.deactivate') }}">
                                        <i class="glyphicon glyphicon-pause"></i>
                                    </button>
                                    <button data-id="{{ $page->id }}" class="btn btn-sm btn-success status activate {{ $page->active ? 'hide' : '' }}" title="{{ trans('admin.button.activate') }}">
                                        <i class="glyphicon glyphicon-play"></i>
                                    </button>
                                    <a
                                            href="{{ route('page', ['lang' => app()->getLocale(), 'pageId' => $page->id]) }}"
                                            target="_blank"
                                            title="{{ trans('admin.page.label.title') }}"
                                            class="btn btn-sm btn-default"
                                    >
                                        <i class="glyphicon glyphicon-link"></i>
                                    </a>
                                </div>
                                <button data-id="{{ $page->id }}" class="btn btn-sm btn-danger page-remove" title="{{ trans('admin.button.remove') }}">
                                    <i class="glyphicon glyphicon-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection