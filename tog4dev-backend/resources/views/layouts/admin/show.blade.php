<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>

    <meta charset="utf-8">
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{ asset('css/bootstrap-dark.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet" disabled />

    @if(App::getLocale() == "ar")
        <link href="{{ asset('css/app-rtl.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ asset('css/app-dark-rtl.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
        <link href="{{ asset('css/main-rtl.css?v=1.1') }}" rel="stylesheet" type="text/css" />
    @elseif(App::getLocale() == "en")
        <link href="{{ asset('css/app.css?v=1.1') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ asset('css/app-dark.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
        <link href="{{ asset('css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />
    @endif

    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/datatables.net-select-bs4/css//select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('css/admin-modern.css?v=1.6') }}" rel="stylesheet" type="text/css" />
    @if(App::getLocale() == "ar")
        <link href="{{ asset('css/admin-modern-rtl.css?v=1.6') }}" rel="stylesheet" type="text/css" />
    @endif

    @yield('cssCode')

    <link rel="icon" type="image/ico" href="{{ asset('favicon.ico') }}" />
</head>

<body>

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
                            <div class="topbar-user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
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
                            <a href="{{ route('system.settings') }}" class="dropdown-item">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ __('app.my_profile') }}</span>
                            </a>
                            <a href="{{ route('system.settings') }}" class="dropdown-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>{{ __('app.security_settings') }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item text-danger">
                                <form id="logout-form" action="/logout" method="POST" class="d-none">@csrf</form>
                                <i class="fas fa-sign-out-alt"></i>
                                <span>{{ __('app.logout') }}</span>
                            </a>
                        </div>
                    </li>

                </ul>

                <div class="logo-box">
                    <a href="{{ route('dashboard') }}" class="logo logo-dark text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('img/logo-bg.png') }}" alt="" height="36">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo.png') }}" alt="" style="height:40px;">
                        </span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="logo logo-light text-center">
                        <span class="logo-sm">
                            <img src="{{ asset('img/logo-bg.png') }}" alt="" height="36">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ asset('img/logo.png') }}" alt="" style="height:40px;">
                        </span>
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
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- end Topbar -->

        <!-- Left Sidebar -->
        <div class="left-side-menu">
            <div class="h-100" data-simplebar>
                <div id="sidebar-menu">
                    <ul id="side-menu">
                        @include('includes.admin.side-bar')
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- Left Sidebar End -->

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">

                    @hasSection('breadcrumb')
                    <div class="row mt-2 mb-1">
                        <div class="col-12">
                            @yield('breadcrumb')
                        </div>
                    </div>
                    @endif

                    @yield('content')
                    @include('includes.admin.loader')
                </div>
            </div>
        </div>

    </div>
    <!-- END wrapper -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="{{ asset('js/pages/sweet-alerts.init.js') }}"></script>
    <script src="{{ asset('js/pages/dashboard-1.init.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/pdfmake/build/vfs_fonts.js') }}"></script>
    @if(App::getLocale() == "ar")
        <script src="{{ asset('js/pages/datatables.init-rtl.js?v=1.2') }}"></script>
    @elseif(App::getLocale() == "en")
        <script src="{{ asset('js/pages/datatables.init.js?v=1.2') }}"></script>
    @endif
    <script src="{{ asset('js/main.js?v=1.2') }}"></script>
    <script src="{{ asset('js/admin-components.js?v=1.6') }}"></script>

    <script type="text/javascript">
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

    <script>
        $(document).ready(function () {
            // Sidebar memory
            var openMenus = JSON.parse(localStorage.getItem('sidebarOpenMenus') || '[]');
            openMenus.forEach(function(id) {
                var el = document.getElementById(id);
                if (el) { $(el).addClass('show'); }
            });

            $('#side-menu [data-toggle="collapse"]').on('click', function() {
                var target = $(this).attr('href') || $(this).data('target');
                if (target) {
                    var id = target.replace('#', '');
                    setTimeout(function() {
                        var menus = [];
                        $('#side-menu .collapse.show').each(function() { menus.push(this.id); });
                        localStorage.setItem('sidebarOpenMenus', JSON.stringify(menus));
                    }, 350);
                }
            });

            $('#topbarSearch').on('focus', function() {
                if (window.CommandPalette) {
                    window.CommandPalette.open();
                    $(this).blur();
                }
            });

            $(document).on('click', '.change_status', function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var table = $(this).data("table");
                var url = '';
                if (table) { url = '/' + table + '/change-status/' + id; }
                Swal.fire({
                    title: "",
                    text: "{{ __('app.are you sure you want save it') }}",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#13585D",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST", url: url, data: {},
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data.status == "success") {
                                    Swal.fire({ title: "", text: "{{ __('app.updated successfully') }}", type: "success", confirmButtonText: "{{ __('app.ok') }}" }).then((result) => { location.reload(); });
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

            if (typeof ClipboardJS !== 'undefined') {
                const clipboard = new ClipboardJS('.btn-copy');
                clipboard.on('success', function (e) {
                    Swal.fire({ icon: 'success', title: '{{ __('app.copied_successfully') }}', text: '{{ __('app.link_copied') }}', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end', timerProgressBar: true });
                    e.clearSelection();
                });
                clipboard.on('error', function (e) {
                    Swal.fire({ icon: 'error', title: '{{ __('app.copy_failed') }}', text: '{{ __('app.unable_to_copy') }}', confirmButtonText: '{{ __('app.ok') }}' });
                });
            }

            $(document).on('click', '.btn-mark-read', function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var table = $(this).data("table");
                var url = '';
                if (table) { url = '/' + table + '/' + id; }
                Swal.fire({
                    title: "{{ __('app.are_you_sure') }}",
                    text: "{{ __('app.mark_as_read_message') }}",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#13585D",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: "POST", url: url,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data.status == "success") {
                                    Swal.fire({ title: "{{ __('app.success') }}", text: "{{ __('app.message_marked_as_read') }}", type: "success", confirmButtonText: "{{ __('app.ok') }}" }).then(() => { location.reload(); });
                                } else {
                                    Swal.fire({ title: "", text: "{{ __("app.ops there are problem, try again") }}", type: "error", confirmButtonText: "{{ __('app.ok') }}" }).then(() => { location.reload(); });
                                }
                            },
                            error: function () {
                                Swal.fire({ title: "", text: "{{ __("app.ops there are problem, try again") }}", type: "error", confirmButtonText: "{{ __('app.ok') }}" }).then(() => { location.reload(); });
                            }
                        });
                    }
                });
            });
        });
    </script>

    @yield('jsCode')

</body>

</html>
