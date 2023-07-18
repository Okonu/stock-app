<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <meta name="robots" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fursa : Joboard" />
    <meta property="og:title" content="Fursa Mombasa County Joboard" />
    <meta property="og:description" content="Fursa Mombasa County Joboard" />
    <meta property="og:image" content="social-image.png" />
    <meta name="format-detection" content="telephone=no">
    
    <!-- PAGE TITLE HERE -->
    <title>Fursa - Joboard System</title>
    <!-- Favicon icon -->
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="front/images/favicon.png">
    <link href="{{ asset('front/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/vendor/chartist/css/chartist.min.css') }}">
    <!-- Vectormap -->
    <link href="{{ asset('front/vendor/jqvmap/css/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('front/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('fron/tvendor/owl-carousel/owl.carousel.css') }}" rel="stylesheet">
    <!-- Datatable -->
    <link href="{{ asset('front/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Summernote -->
    <link href="{{ asset('front/vendor/summernote/summernote.css') }}" rel="stylesheet">
    <link href="{{ asset('front/vendor/chartist/css/chartist.min.cs') }}" rel="stylesheet">
    
    
</head>
<body>

@include('admin.layouts.header')

@include('admin.layouts.sidebar')

<div id="content-wrapper">
    <!-- Content from child views will be rendered here -->
    @yield('content')
</div>

<script src="{{ asset('front/vendor/global/global.min.js') }}"></script>
<script src="{{ asset('front/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('front/vendor/chart.js/Chart.bundle.min.js') }}"></script>
<script src="{{ asset('front/vendor/owl-carousel/owl.carousel.js') }}"></script>
<!-- Chart piety plugin files -->
<!-- Chart Chartist plugin files -->

<script src="{{ asset('front/vendor/chartist/js/chartist.min.js') }}"></script>
<script src="{{ asset('front/vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js') }}"></script>
<script src="{{ asset('front/js/plugins-init/chartist-init.js') }}"></script>
<script src="{{ asset('front/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('front/js/plugins-init/datatables.init.js') }}"></script>
<script src="{{ asset('front/vendor/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('front/vendor/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
<!-- Apex Chart -->
<script src="{{ asset('front/vendor/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('front/vendor/apexchart/apexchart.js') }}"></script>
<!-- Dashboard 1 -->
<script src="{{ asset('front/js/dashboard/dashboard-1.js') }}"></script>
<script src="{{ asset('front/js/custom.min.js') }}"></script>
<script src="{{ asset('front/js/dlabnav-init.js') }}"></script>
<script src="{{ asset('front/js/demo.js') }}"></script>

<!-- <script src="{{ asset('front/js/styleSwitcher.js') }}"></script> -->
</body>
</html>
