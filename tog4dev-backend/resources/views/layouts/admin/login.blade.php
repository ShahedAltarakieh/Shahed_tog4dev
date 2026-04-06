<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{ asset('css/bootstrap-dark.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />

    @if(App::getLocale() == "ar")
    <link href="{{ asset('css/app-rtl.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('css/app-dark-rtl.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
    <link href="{{ asset('css/main-rtl.css') }}" rel="stylesheet" type="text/css" />
    @elseif(App::getLocale() == "en")
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('css/app-dark.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
    <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css" />
    @endif

    <!-- Plugins css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&family=Cairo&display=swap" rel="stylesheet">

    <!-- Plugins css -->
    <link href="{{ asset('libs/mohithg-switchery/switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropzone/min/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/dropify/css/dropify.min.css') }}" rel="stylesheet" type="text/css" />

    @yield('cssCode')
    <script src="{{ asset('/js/head.js') }}"></script>

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
</head>

<body class="authentication-bg authentication-bg-pattern">

    <div class="account-pages mt-5 pt-5 mb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 col-sm-8 col-12">

                    @yield('content')

                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <!-- Vendor js -->
    <script src="{{ asset('/js/vendor.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('/js/app.js') }}"></script>

<script>
    function refreshToken(){
        $.get('/refresh-csrf').done(function(data){
            $("[name=_token]").val(data); // the new token
        });
    }

    setInterval(refreshToken, 300000);
</script>

</body>

</html>
