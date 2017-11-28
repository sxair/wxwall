<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>微信墙</title>

    <link rel="stylesheet" href="{{ asset('node_modules/iview/dist/styles/iview.css') }}">

    <link rel="stylesheet" href="{{ asset('node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
    <script src="{{ asset('node_modules/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('node_modules/jquery.cookie/jquery.cookie.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/wall.css') }}">
</head>
<body>
@yield('content')
</body>
</html>
