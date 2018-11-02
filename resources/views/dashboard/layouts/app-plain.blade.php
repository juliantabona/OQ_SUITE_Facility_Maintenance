<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ env('APP_NAME', 'OQ - Facility Maintenance') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('css/plugins/mdi/css/materialdesignicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/plugins/simple-line-icons/css/simple-line-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('css/plugins/perfect-scrollbar/dist/css/perfect-scrollbar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/plugins/pace/themes/custom/custom-pace.css') }}">
        <!-- endinject -->

        <!-- App Compilled Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Dashboard Styles -->
        <link href="{{ asset('css/themes/victory/style.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">

        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />        
    </head>
    <body>
        <div id="app">
            <main-dashboard></main-dashboard>
        </div>
    </body>
    <!-- JavaScript Dependencies: jQuery, Popper.js, Bootstrap JS, Shards JS -->
    <script src="{{ asset('js/plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</html>
