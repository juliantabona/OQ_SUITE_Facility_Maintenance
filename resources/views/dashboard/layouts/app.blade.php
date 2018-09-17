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

        <!-- plugin css for this page -->
        <link rel="stylesheet" href="{{ asset('css/plugins/font-awesome/css/font-awesome.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/plugins/jquery-bar-rating/dist/themes/fontawesome-stars.css') }}">
        <!-- End plugin css for this page -->

        <!-- App Compilled Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Dashboard Styles -->
        <link href="{{ asset('css/themes/victory/style.css') }}" rel="stylesheet">

        <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />

        @yield('style')
        
    </head>

    <body>
        <div id="app">
            <main class="py-4">
                <!-- container-scroller -->
                <div class="container-scroller">
                    <!-- Top menu with logo, profile and message icons -->
                    @include('dashboard/layouts/topmenu/default-topmenu')
                    <div class="container-fluid page-body-wrapper">
                        <div class="row row-offcanvas row-offcanvas-right">
                            <!-- Right sidebar with instant settings -->
                            @include('dashboard/layouts/sidebar/default-right-settings') 
                            @include('dashboard/layouts/sidebar/default-right-todolist')
                            <!-- Left sidebar with navigation menus -->
                            @include('dashboard/layouts/sidebar/default-left-menu')
                            <!-- Dashboard content -->
                            <div class="content-wrapper" style="min-height: 2956.38px;">
                                @include('dashboard/layouts/alerts/default-top-alerts') 
                                @yield('content')
                            </div>
                            <!-- partial:partials/_footer.html -->
                            @include('dashboard/layouts/footer/default-footer')
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </body>

    <!-- Scripts -->

    <!-- plugins:js -->
    <script src="{{ asset('js/plugins/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
    <!-- endinject -->

    <!-- Plugin js for this page-->
    <script src="{{ asset('js/plugins/pace/pace.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-bar-rating/dist/jquery.barrating.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('js/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('js/plugins/morris.js/morris.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/plugins/cycle/cycle.js') }}"></script>
    <!-- End plugin js for this page-->

    <!-- inject:js -->
    <script src="{{ asset('js/custom/off-canvas.js') }}"></script>
    <script src="{{ asset('js/custom/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('js/custom/misc.js') }}"></script>
    <script src="{{ asset('js/custom/settings.js') }}"></script>
    <script src="{{ asset('js/custom/todolist.js') }}"></script>
    <!-- endinject -->

    @yield('js')

</html>