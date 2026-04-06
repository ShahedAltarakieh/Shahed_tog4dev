<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>

    <meta charset="utf-8">
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App css -->
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

    <link href="{{ asset('libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css" />

    <!-- Plugins css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />


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

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
</head>

<body>


    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar Start -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-right mb-0">
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light d-flex" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span style='font-size:28px;'><i class="mdi mdi-earth"></i></span> &nbsp;&nbsp;&nbsp; {{ __('app.language') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown" style='min-width:100%;'>
                            <a href="{{ LaravelLocalization::getLocalizedURL('en') }}" class="dropdown-item notify-item text-left">
                                <span>English</span>
                            </a>
                            <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}" class="dropdown-item notify-item text-right">
                                <span>العربية</span>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light d-flex" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span style='font-size:28px;'><i class="mdi mdi-chevron-down"></i></span> &nbsp;&nbsp;&nbsp; {{ Auth::user()->username }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown" style='min-width:100%;'>
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                {{ __('app.welcome') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                                <form id="logout-form" action="/logout" method="POST" class="d-none">
                                    @csrf
                                </form>
                                <i class="fe-log-out"></i>
                                <span>{{ __('app.logout') }}</span>
                            </a>
                        </div>
                    </li>

                </ul>

                <!-- LOGO -->
                <div class="logo-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('img/logo-bg.png') }}" alt="" height="50">
                            <!-- <span class="logo-lg-text-light">UBold</span> -->
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo.png') }}" alt="" style="height:50px;">
                        </span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('img/logo-bg.png') }}" alt="" height="50">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo.png') }}" alt="" style="height:50px;">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>

                    <li>
                        <!-- Mobile menu toggle (Horizontal Layout)-->
                        <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <div class="h-100" data-simplebar>
                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <ul id="side-menu">

                        @include('includes.admin.side-bar')

                    </ul>
                </div>
                <!-- End Sidebar -->
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">

                <!-- Start Content-->
                <div class="container-fluid">

                    @yield('content')

                </div> <!-- container -->

            </div> <!-- content -->


        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->


    <!-- Vendor js -->
    <script src="{{ asset('js/vendor.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ asset('js/pages/sweet-alerts.init.js') }}"></script>

    <script src="{{ asset('js/pages/dashboard-1.init.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('libs/mohithg-switchery/switchery.min.js') }}"></script>
    <script src="{{ asset('libs/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('libs/devbridge-autocomplete/jquery.autocomplete.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('libs/dropzone/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('libs/dropify/js/dropify.min.js') }}"></script>
    <script src="{{ asset('libs/quill/quill.min.js') }}"></script>
    <!-- Init js-->
    <script src="{{ asset('js/pages/form-advanced.init.js') }}"></script>
    <script src="{{ asset('js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ asset('js/pages/form-fileuploads.init.js') }}"></script>
    <!-- <script src="{{ asset('js/pages/form-quilljs.init.js') }}"></script> -->

    <script src="{{ asset('js/main.js?v=1.2') }}"></script>

    <script type="text/javascript">
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $(document).on('click', '.btn-delete', function (e) {
            e.preventDefault();
            var id = $(this).data("id");
            var table = $(this).data("table");
            var url = '';
            if(table){
                url = '/'+ table +'/' + id;
            }

            Swal.fire({
                title: "",
                text: "{{ __('app.are you sure you want delete this record!') }}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('app.yes delete it') }}",
                cancelButtonText: "{{ __('app.no close') }}",
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ml-2 mt-2",
                buttonsStyling: !1
            })
                .then((result) => {
                    if (result.value) {
                        console.log("test",url)
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            data: {

                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                console.log(data)
                                var data = JSON.parse(response);
                                if (data.status == "success") {
                                    Swal.fire({
                                        title: "",
                                        text: "{{ __('app.deleted successfully') }}",
                                        type: "success",
                                        confirmButtonText: "{{ __('app.ok') }}"
                                    }).then(
                                        (result) => {
                                            location.reload();
                                        });
                                } else {
                                    Swal.fire({
                                        title: "",
                                        text: "{{ __("app.ops there are problem, try again") }}",
                                        type: "error",
                                        confirmButtonText: "{{ __('app.ok') }}"
                                    }).then(
                                        (result) => {
                                            location.reload();
                                        });
                                }

                            },
                            error: function (err) {
                                Swal.fire({
                                    title: "",
                                    text: "حدث خطأ, يرجى المحاولة لاحقاً",
                                    type: "error",
                                    confirmButtonText: "موافق"
                                }).then(
                                    (result) => {
                                        location.reload();
                                    });
                            }
                        });
                    }
                });

        });
    </script>
    <script>
        function refreshToken(){
            $.get('/refresh-csrf').done(function(data){
                $("[name=_token]").val(data); // the new token
            });
        }

        setInterval(refreshToken, 300000);
    </script>
    @yield('jsCode')
</body>
</html>
