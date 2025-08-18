<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

    <title> @yield('title')</title>

    <!--Favicon-->
    <link rel="icon" href="{{ url('home/assets/img/favicon.png') }}" type="image/png" >

    @include('layouts.inc-style')
@yield('stylesheet')
</head>

<body>
    <!-- Pre-Loader -->
    <div class="preloader"></div>



    @include('layouts.inc-header')


        @yield('content')


    @include('layouts.inc-footer')

    <!-- JavaScripts -->
    @include('layouts.inc-script')
    @yield('scripts')

</body>

</html>
