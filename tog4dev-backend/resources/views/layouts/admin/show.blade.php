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
    <link href="{{ asset('css/bootstrap-dark.css') }}" rel="stylesheet" type="text/css" id="bs-dark-stylesheet"
        disabled />

    @if(App::getLocale() == "ar")
        <link href="{{ asset('css/app-rtl.css') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ asset('css/app-dark-rtl.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet"
            disabled />
        <link href="{{ asset('css/main-rtl.css?v=1.1') }}" rel="stylesheet" type="text/css" />
    @elseif(App::getLocale() == "en")
        <link href="{{ asset('css/app.css?v=1.1') }}" rel="stylesheet" type="text/css" id="app-default-stylesheet" />
        <link href="{{ asset('css/app-dark.css') }}" rel="stylesheet" type="text/css" id="app-dark-stylesheet" disabled />
        <link href="{{ asset('css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />
    @endif

    <!-- Plugins css -->
    <link href="{{ asset('libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('libs/selectize/css/selectize.bootstrap3.css') }}" rel="stylesheet" type="text/css" />
    <!-- icons -->
    <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />


    <!-- third party css -->
    <link href="{{ asset('libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('libs/datatables.net-select-bs4/css//select.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

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
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light d-flex"
                            data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span style='font-size:28px;'><i class="mdi mdi-earth"></i></span> &nbsp;&nbsp;&nbsp;
                            {{ __('app.language') }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown" style='min-width:100%;'>
                            <a href="{{ LaravelLocalization::getLocalizedURL('en') }}"
                                class="dropdown-item notify-item text-left">
                                <span>English</span>
                            </a>
                            <a href="{{ LaravelLocalization::getLocalizedURL('ar') }}"
                                class="dropdown-item notify-item text-right">
                                <span>العربية</span>
                            </a>
                        </div>
                    </li>
                    <li class="dropdown notification-list topbar-dropdown">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light d-flex"
                            data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <span style='font-size:28px;'><i class="mdi mdi-chevron-down"></i></span> &nbsp;&nbsp;&nbsp;
                            {{ Auth::user()->username }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown" style='min-width:100%;'>
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                {{ __('app.welcome') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <a href="/logout"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item notify-item">
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

                    @include('includes.admin.loader')

                </div> <!-- container -->

            </div> <!-- content -->


        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.10/clipboard.min.js"></script>

    <!-- Vendor js -->
    <script src="{{ asset('js/vendor.js') }}"></script>

    <!-- Plugins js-->
    <script src="{{ asset('libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
    <script src="{{ asset('libs/clockpicker/bootstrap-clockpicker.min.js') }}"></script>
    <script src="{{ asset('libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('libs/selectize/js/standalone/selectize.min.js') }}"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <!-- Sweet alert init js-->
    <script src="{{ asset('js/pages/sweet-alerts.init.js') }}"></script>

    <script src="{{ asset('js/pages/dashboard-1.init.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- third party js -->
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
    <!-- third party js ends -->
    @if(App::getLocale() == "ar")
        <!-- Datatables init -->
        <script src="{{ asset('js/pages/datatables.init-rtl.js?v=1.2') }}"></script>
    @elseif(App::getLocale() == "en")
        <!-- Datatables init -->
        <script src="{{ asset('js/pages/datatables.init.js?v=1.2') }}"></script>
    @endif
    <script src="{{ asset('js/main.js?v=1.2') }}"></script>

    <script type="text/javascript">
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>

    <script>
        $(document).ready(function () {
            $(document).on('click', '.change_status', function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var table = $(this).data("table");
                var url = '';
                if (table) {
                    url = '/' + table + '/change-status/' + id;
                }

                Swal.fire({
                    title: "",
                    text: "{{ __('app.are you sure you want save it!') }}",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: {

                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    var data = JSON.parse(response);
                                    if (data.status == "success") {
                                        Swal.fire({
                                            title: "",
                                            text: "{{ __('app.updated successfully') }}",
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
            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var table = $(this).data("table");
                var url = '';
                if (table) {
                    url = '/' + table + '/' + id;
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
                            $.ajax({
                                type: "DELETE",
                                url: url,
                                data: {

                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
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

            // Check if ClipboardJS is loaded
            if (typeof ClipboardJS === 'undefined') {
                console.error('ClipboardJS is not loaded.');
                return;
            }

            // Initialize Clipboard.js
            const clipboard = new ClipboardJS('.btn-copy');

            clipboard.on('success', function (e) {
                showSweetAlert();
                e.clearSelection();
            });

            clipboard.on('error', function (e) {
                console.error('Failed to copy:', e);
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('app.copy_failed') }}',
                    text: '{{ __('app.unable_to_copy') }}',
                    confirmButtonText: '{{ __('app.ok') }}',
                });
            });

            function showSweetAlert() {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('app.copied_successfully') }}',
                    text: '{{ __('app.link_copied') }}',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    timerProgressBar: true,
                });
                console.log('SweetAlert shown.');
            }

            $(document).on('click', '.btn-mark-read', function (e) {
                e.preventDefault();

                // Get the ID of the contact to mark as read
                var id = $(this).data("id");
                var type = $(this).data("type");  // Type (organization/projects)
                var table = $(this).data("table");
                var url = '';
                if (table) {
                    url = '/' + table + '/' + id;
                }
                // Show SweetAlert confirmation before marking as read
                Swal.fire({
                    title: "{{ __('app.are_you_sure') }}",  // "Are you sure?"
                    text: "{{ __('app.mark_as_read_message') }}",  // "Do you want to mark this message as read?"
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                }).then((result) => {
                    if (result.value) {
                        // Proceed with marking as read if confirmed
                        $.ajax({
                            type: "POST",  // Send POST request
                            url: url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                var data = JSON.parse(response);
                                if (data.status == "success") {
                                    Swal.fire({
                                        title: "{{ __('app.success') }}",  // "Success"
                                        text: "{{ __('app.message_marked_as_read') }}",  // "Message marked as read."
                                        type: "success",
                                        confirmButtonText: "{{ __('app.ok') }}"
                                    }).then(() => {
                                        location.reload();  // Reload the page to reflect the update
                                    });
                                } else {
                                    Swal.fire({
                                        title: "{{ __('app.error') }}",  // "Error"
                                        text: "{{ __('app.ops_error_try_again') }}",  // "Oops, there was a problem. Please try again."
                                        type: "error",
                                        confirmButtonText: "{{ __('app.ok') }}"
                                    });
                                }
                            },
                            error: function (err) {
                                Swal.fire({
                                    title: "{{ __('app.error') }}",  // "Error"
                                    text: "{{ __('app.something_went_wrong') }}",  // "Something went wrong, please try again later."
                                    type: "error",
                                    confirmButtonText: "{{ __('app.ok') }}"
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.add_to_home', function (e) {
                e.preventDefault();
                var id = $(this).data("id");
                var table = $(this).data("table");
                var status = $(this).attr("data-add-to-home");
                status = !parseInt(status);
                Swal.fire({
                    title: "",
                    text: "{{ __('app.are you sure you want save it') }}!",
                    type: "warning",
                    showCancelButton: !0,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "{{ __('app.yes') }}",
                    cancelButtonText: "{{ __('app.no close') }}",
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: !1
                })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: "POST",
                                url: "/ajax/add-to-home",
                                data: {
                                    id: id,
                                    status: status,
                                    table: table
                                },
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function (response) {
                                    var data = JSON.parse(response);
                                    if (data.status == "success") {
                                        Swal.fire({
                                            title: "",
                                            text: "{{ __('app.updated successfully') }}",
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
                                        text: "{{ __("app.ops there are problem, try again") }}",
                                        type: "error",
                                        confirmButtonText: "{{ __('app.ok') }}"
                                    }).then(
                                        (result) => {
                                            location.reload();
                                        });
                                }
                            });
                        }
                    });

            });
        });

        $(document).on('click', '.btn-update-status', function () {
            const id = $(this).data('id');
            const status = $(this).data('status');

            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to update the status?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, update it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `/newsletter/${id}`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            status: status
                        },
                        success: function (response) {
                            if (response.status === 'success') {
                                Swal.fire('Updated!', response.message, 'success').then(() => location.reload());
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-unsubscribe', function () {
            const url = $(this).data('url');
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to unsubscribe the payment?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, unsubscribe it!',
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ml-2 mt-2",
                buttonsStyling: !1
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            var data = JSON.parse(response);
                            if (data.status == "success") {
                                Swal.fire({
                                    title: "",
                                    text: "{{ __('app.unsubscribed successfully') }}",
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
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    });
                }
            });
        });
        
        $(document).on('click', '.btn-refund', function () {
            Swal.fire({
                title: "",
                text: "{{ __('Refund process under development') }}",
                type: "info",
                confirmButtonText: "{{ __('app.ok') }}"
            });
            return;
            const url = $(this).data('url');
            const paymentId = $(this).data('payment-id');
            if (!paymentId) {
                Swal.fire('Error', 'Payment ID missing', 'error');
                return;
            }
            Swal.fire({
                title: "Are you sure?",
                text: "Do you want to refund this payment?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, refund it!',
                confirmButtonClass: "btn btn-success mt-2",
                cancelButtonClass: "btn btn-danger ml-2 mt-2",
                buttonsStyling: false
            }).then((result) => {
                console.log(result)
                if (result.value) {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            payment_id: paymentId,
                        },
                        success: function (response) {
                            var data = typeof response === 'object' ? response : JSON.parse(response);
                            if (data.status === "success") {
                                Swal.fire({
                                    title: "",
                                    text: "{{ __('app.refunded successfully') }}",
                                    type: "success",
                                    confirmButtonText: "{{ __('app.ok') }}"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "",
                                    text: "{{ __('app.ops there are problem') }}",
                                    type: "error",
                                    confirmButtonText: "{{ __('app.ok') }}"
                                });
                            }
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
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
    @if (session('success'))
        <script>
            Swal.fire({
                title: "{{ session('success') }}",  // "Success"
                text: "",  // "Message marked as read."
                type: "success",
                confirmButtonText: "{{ __('app.ok') }}"
            });
        </script>
    @endif
    <script>
        document.querySelectorAll('.btn-download-dashboard').forEach(btn => {
            btn.addEventListener('click', function(event) {
                $("#main-loader").removeClass("d-none");
                event.stopPropagation(); // prevents the div click
                var type = $(this).data("type");
                var start = $(this).attr("data-start");
                var end = $(this).attr("data-end");

                $.ajax({
                    type: "POST",  // Send POST request
                    url: "{{ route('dashboard.download_payments') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        service_type: type,
                        start: start,
                        end: end
                    },
                    xhrFields: {
                        responseType: 'blob' // Important for binary data
                    },
                    success: function (data) {
                        var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = type + " " + start + " to " + end + ".xlsx";
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        $("#main-loader").addClass("d-none");
                    },
                    error: function (err) {
                        $("#main-loader").addClass("d-none");
                    }
                });
            });
        });
    </script>
    @yield('jsCode')

</body>

</html>
