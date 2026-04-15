<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

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

    <link href="{{ asset('css/admin-modern.css?v=1.6') }}" rel="stylesheet" type="text/css" />
    @if(App::getLocale() == "ar")
        <link href="{{ asset('css/admin-modern-rtl.css?v=1.6') }}" rel="stylesheet" type="text/css" />
    @endif

    @yield('cssCode')

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
</head>

<body>


    <!-- Begin page -->
    <div id="wrapper">

        <!-- Topbar -->
        <div class="navbar-custom">
            <div class="container-fluid">
                <ul class="list-unstyled topnav-menu float-right mb-0">

                <!-- <li class="dropdown notification-list topbar-dropdown d-none d-md-block">
                        <div class="topbar-search">
                            <input type="text" class=" topbar-search-input" placeholder="{{ __('app.search_placeholder') }}" id="topbarSearch">
                            <span class="topbar-search-icon"><i class="fas fa-search"></i></span>
                            <kbd class="topbar-search-kbd">{{ app()->getLocale() == 'ar' ? 'Ctrl+K' : '⌘K' }}</kbd>
                        </div>
                    </li> -->
                    
                    <li class="dropdown notification-list topbar-dropdown d-md-none">
                        <a class="nav-link topbar-icon-btn" href="javascript:void(0)" onclick="if(window.CommandPalette) window.CommandPalette.open();" title="{{ __('app.search_placeholder') }}">
                            <i class="fas fa-search"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link topbar-icon-btn" href="{{ route('system.notifications') }}" title="{{ __('app.notifications') }}">
                            <i class="fas fa-bell"></i>
                        </a>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle topbar-icon-btn" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false" title="{{ __('app.language') }}">
                            <i class="fas fa-globe"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right topbar-dropdown-menu">
                            <a href="{{ LaravelLocalization::getLocalizedURL('en') }}" class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                                <span>English</span>
                            </a>
                            <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}" class="dropdown-item {{ app()->getLocale() == 'ar' ? 'active' : '' }}">
                                <span>العربية</span>
                            </a>
                        </div>
                    </li>

                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle topbar-user-btn" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <div class="topbar-user-avatar"><i class="fas fa-user"></i></div>
                            <span class="topbar-user-name d-none d-md-inline-block">{{ Auth::user()->username }}</span>
                            <i class="fas fa-chevron-down topbar-user-arrow"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right topbar-dropdown-menu topbar-user-menu">
                            <div class="topbar-user-header">
                                <div class="topbar-user-avatar-lg"><i class="fas fa-user"></i></div>
                                <div>
                                    <div class="topbar-user-header-name">{{ Auth::user()->username }}</div>
                                    <div class="topbar-user-header-role">{{ __('app.administrator') }}</div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('system.settings') }}" class="dropdown-item"><i class="fas fa-user-circle"></i> <span>{{ __('app.my_profile') }}</span></a>
                            <a href="{{ route('system.settings') }}" class="dropdown-item"><i class="fas fa-shield-alt"></i> <span>{{ __('app.security_settings') }}</span></a>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form-add').submit();" class="dropdown-item text-danger">
                                <form id="logout-form-add" action="/logout" method="POST" class="d-none">@csrf</form>
                                <i class="fas fa-sign-out-alt"></i> <span>{{ __('app.logout') }}</span>
                            </a>
                        </div>
                    </li>

                </ul>

                <div class="logo-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark text-center">
                        <span class="logo-sm"><img src="{{ asset('img/logo-bg.png') }}" alt="" height="36"></span>
                        <span class="logo-lg"><img src="{{ asset('img/logo.png') }}" alt="" style="height:40px;"></span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="logo logo-light text-center">
                        <span class="logo-sm"><img src="{{ asset('img/logo-bg.png') }}" alt="" height="36"></span>
                        <span class="logo-lg"><img src="{{ asset('img/logo.png') }}" alt="" style="height:40px;"></span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light" aria-label="{{ __('app.toggle_menu') }}">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                    <li>
                        <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                            <div class="lines"><span></span><span></span><span></span></div>
                        </a>
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

                    @hasSection('breadcrumb')
                    <div class="row mt-2 mb-2">
                        <div class="col-12">
                            @yield('breadcrumb')
                        </div>
                    </div>
                    @endif

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
    <script src="{{ asset('js/admin-components.js?v=1.6') }}"></script>

    <script type="text/javascript">
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        $(document).ready(function () {
            var openMenus = JSON.parse(localStorage.getItem('sidebarOpenMenus') || '[]');
            openMenus.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) { $(el).addClass('show'); }
            });

            $('#side-menu [data-toggle="collapse"]').on('click', function() {
                var target = $(this).attr('href') || $(this).data('target');
                if (target) {
                    setTimeout(function() {
                        var menus = [];
                        $('#side-menu .collapse.show').each(function() { menus.push(this.id); });
                        localStorage.setItem('sidebarOpenMenus', JSON.stringify(menus));
                    }, 350);
                }
            });

            $('#topbarSearchAdd').on('focus', function() {
                if (window.CommandPalette) {
                    window.CommandPalette.open();
                    $(this).blur();
                }
            });

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var table = $(this).data("table");
                var url = '';
                if (table) { url = '/' + table + '/' + id; }
                Swal.fire({
                    title: "",
                    text: "{{ __('app.are you sure you want delete this record!') }}",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#13585D",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes delete it') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "DELETE", url: url, data: {},
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data.status == "success") {
                                    Swal.fire({ title: "", text: "{{ __('app.deleted successfully') }}", type: "success", confirmButtonText: "{{ __('app.ok') }}" }).then((result) => { location.reload(); });
                                } else {
                                    Swal.fire({ title: "", text: "{{ __("app.ops there are problem, try again") }}", type: "error", confirmButtonText: "{{ __('app.ok') }}" }).then((result) => { location.reload(); });
                                }
                            },
                            error: function (err) {
                                Swal.fire({ title: "", text: "{{ __("app.ops there are problem, try again") }}", type: "error", confirmButtonText: "{{ __('app.ok') }}" }).then((result) => { location.reload(); });
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        function refreshToken(){
            $.get('/refresh-csrf').done(function(data){
                $("[name=_token]").val(data);
            });
        }
        setInterval(refreshToken, 300000);
    </script>
    @yield('jsCode')
</body>
</html>
