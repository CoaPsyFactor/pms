<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Page Management System')</title>
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
                data-navigation-links="{{ \App\Helpers\NavigationLinks::formatNavigationLinks(request()->get('navigation_links')) }}"
            >
                <div class="collapse navbar-collapse navigation-container">
                    <ul class="nav navbar-nav">
                    </ul>
                </div>
            </nav>

            @yield('content')
        </div>
    </body>
</html>
