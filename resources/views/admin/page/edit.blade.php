@extends('admin')

@section('scripts')
    @parent

    <script type="text/javascript" src="{{ url('js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            tinymce.init({ selector: 'textarea.page-content' })
        });
    </script>
@endsection

@section('sidebarTitle')
    {{ trans('page.admin.sidebar.title') }}
@endsection

@section('sidebarContent')
    <div class="text-center">
        <a href="{{ route('admin.pages', ['lang' => app()->getLocale()]) }}" class="btn btn-primary">
            {{ trans('page.admin.sidebar.pages.list') }}
        </a>
    </div>
@endsection

@section('content')
    @if (session('page.create.error'))
        <div class="alert alert-danger">
            {{ session('page.create.error') }}
        </div>
    @elseif (session('page.create.success'))
        <div class="alert alert-success">
            {{ session('page.create.success') }}
        </div>
    @endif
    {{ Form::open(['url' => route('admin.page.create', ['lang' => app()->getLocale(), 'pageId' => empty($page) ? null : $page->id]), 'class' => 'form-group']) }}
        <div class="row">
            {{ Form::text('title', empty($page) ? '' : $page->title, ['class' => 'page-title form-control', 'placeholder' => trans('page.title')]) }}
        </div>
        <div class="row">
            {{ Form::textarea('content', empty($page) ? '' : $page->content, ['class' => 'page-content form-control', 'placeholder' => trans('page.content')]) }}
        </div>
        <div class="row">
            {{ Form::submit(empty($page) ? trans('page.create') : trans('page.update'), ['class' => 'page-save pull-right btn btn-success']) }}
        </div>
        {{ Form::token() }}
    {{ Form::close() }}
@endsection