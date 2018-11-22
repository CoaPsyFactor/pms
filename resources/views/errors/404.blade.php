<!DOCTYPE html>
<html>
<head>
    <title>Page Management System Error - 404.</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        a {
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato', sans-serif;
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">
            {{ trans('errors.404', ['type' => empty($type) ? '' : $type]) }}
        </div>
        <div class="title">
            <a href="{{ route('homepage', ['lang' => app()->getLocale()]) }}">
                {{ trans('admin.navigation.home') }}
            </a>
        </div>
    </div>
</div>
</body>
</html>
