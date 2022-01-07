<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
       {{ config('app.name', 'Taskly')}} - @yield('page-title') 
    </title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="{{ asset('assets/libs/@fontawesome/fontawesome-free/css/all.min.css') }}"><!-- Page CSS -->

    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/ac.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-rtl.css') }}">
    @endif
</head>
<body>

<?php
$dir = base_path() . '/resources/lang/';
$glob = glob($dir . "*", GLOB_ONLYDIR);
$arrLang = array_map(function ($value) use ($dir){
    return str_replace($dir, '', $value);
}, $glob);
$arrLang = array_map(function ($value) use ($dir){
    return preg_replace('/[0-9]+/', '', $value);
}, $arrLang);
$arrLang = array_filter($arrLang);
$currantLang = basename(App::getLocale());
$client_keyword = Request::route()->getName() == 'client.login' ? 'client.' : ''
?>
<div class="login-contain">
    <div class="login-inner-contain">
        {{-- <a class="navbar-brand" href="#">
            <img src="{{asset('assets/images/logo.jpg')}}" class="navbar-brand-img" alt="logo">
        </a> --}}
        <div class="row justify-content-center">
            <div class="col-md-4">
                @if(session()->has('info'))
                    <div class="alert alert-primary">
                        {{ session()->get('info') }}
                    </div>
                @endif
                @if(session()->has('status'))
                    <div class="alert alert-info">
                        {{ session()->get('status') }}
                    </div>
                @endif
            </div>
        </div>
        @yield('content')
        <h5 class="copyright-text"> {{env('FOOTER_TEXT')}} </h5>
          @yield('language-bar')
    </div>
</div>
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>

</body>

</html>
