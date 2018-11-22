<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Page Management System - Admin Panel')</title>
    <link rel="stylesheet" type="text/css" href="{{ url('css/app.css') }}" />
    <script type="text/javascript" src="{{ url('js/jquery-3.1.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('js/app.js') }}"></script>
    @yield('scripts')
</head>
<body>
<div class="container">
    <nav
            class="navigation-bar navbar navbar-default"
    >
        <div class="collapse navbar-collapse navigation-container">
            <ul class="nav navbar-nav">
                <li class="{{ \App\Helpers\NavigationLinks::active(['admin.pages', 'admin.page', 'admin.page.new']) }}">
                    <a href="{{ route('admin.pages', ['lang' => app()->getLocale()]) }}">
                        {{ trans('admin.navigation.pages') }}
                    </a>
                </li>
                <li class="{{ \App\Helpers\NavigationLinks::active('admin.plugins') }}">
                    <a href="{{ route('admin.plugins', ['lang' => app()->getLocale()]) }}">
                        {{ trans('admin.navigation.plugins') }}
                    </a>
                </li>
                <li class="{{ \App\Helpers\NavigationLinks::active('admin.navigation') }}">
                    <a href="{{ route('admin.navigation', ['lang' => app()->getLocale()]) }}">
                        {{ trans('admin.navigation.navigation') }}
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav pull-right">
                <li>
                    <a href="{{ route('homepage', ['lang' => app()->getLocale()]) }}">
                        {{ trans('admin.navigation.home') }}
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="col-md-9 col-md-offset-0">
        @yield('content')
    </div>
    <div class="col-md-3 sidebar">
        <div class="panel panel-info">
            <div class="panel-heading">
                @yield('sidebarTitle', trans('admin.sidebar.default.title'))
            </div>
            <div class="panel-body">
                @yield('sidebarContent', trans('admin.sidebar.default.content'))
            </div>
        </div>
    </div>
</div>
</body>
</html>
