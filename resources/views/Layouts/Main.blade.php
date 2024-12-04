<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/lldikti.png') }}" />
    <link rel="stylesheet" href="{{ asset('dist/libs/prismjs/themes/prism-okaidia.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.2/toastify.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.2/toastify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .select2-container--default .select2-selection--single {
            height: 40px;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 30px;
        }

        .select2-container .select2-dropdown {
            z-index: 9999;
        }

        .required-label::after {
            content: " *";
            color: red;
        }

        #logo-preview {
            max-width: 150px;
            margin-top: 10px;
        }

        .hidden {
            display: none;
        }

        .custom-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }
    </style>
    @yield('css')
</head>

<body style="background-color: #F4F8FA">
    <div class="preloader">
        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico"
            alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div class="preloader">
        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/favicon.ico"
            alt="loader" class="lds-ripple img-fluid" />
    </div>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('Layouts.Sidebar')
        <div class="body-wrapper">
            @include('Layouts.Header')
            <div class="container-fluid">
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            $(document).ready(function() {
                Toastify({
                    text: '🎉 {{ session('success') }}',
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)",
                        borderRadius: "10px",
                        boxShadow: "0px 4px 15px rgba(0, 0, 0, 0.1)",
                        padding: "10px 15px",
                    },
                    onClick: function() {}
                }).showToast();
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            $(document).ready(function() {
                Toastify({
                    text: '⚠️ {{ $errors->first() }}', // Ambil pesan error pertama
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        borderRadius: "10px",
                        boxShadow: "0px 4px 15px rgba(0, 0, 0, 0.1)",
                        padding: "10px 15px",
                    },
                    onClick: function() {}
                }).showToast();
            });
        </script>
    @endif

    @if (session('errors') && is_string(session('errors')))
        <script>
            $(document).ready(function() {
                Toastify({
                    text: '⚠️ {{ session('errors') }}',
                    duration: 5000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        borderRadius: "10px",
                        boxShadow: "0px 4px 15px rgba(0, 0, 0, 0.1)",
                        padding: "10px 15px",
                    },
                    onClick: function() {}
                }).showToast();
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            var table = $('#dom_jq_event').DataTable();

            $('#dom_jq_event tbody').on('click', 'tr', function() {
                var data = table.row(this).data();
            });

            if (!$.fn.DataTable.isDataTable('#dom_jq_event')) {
                $('#dom_jq_event').DataTable({
                    dom: '<"top"lf>rt<"bottom"ip><"clear">',
                    initComplete: function() {
                        // Add custom classes to the search bar and page length select
                        $('.dataTables_length').addClass('custom-flex');
                        $('.dataTables_filter').addClass('custom-flex');
                    }
                });
            }
        });
    </script>

    <script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('dist/js/custom.js') }}"></script>
    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/js/dashboard2.js') }}"></script>

    <!-- ---------------------------------------------- -->
    <!-- current page js files -->
    <!-- ---------------------------------------------- -->
    <script src="{{ asset('dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dist/js/datatable/datatable-advanced.init.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

    <script src="{{ asset('dist/js/datatable/datatable-basic.init.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select-search').select2({
                placeholder: 'Pilih Opsi',
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#changePasswordForm').on('submit', function(e) {
                const newPassword = $('#new_password').val();
                const confirmPassword = $('#new_password_confirmation').val();

                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    $('#passwordError').show();
                } else {
                    $('#passwordError').hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleNewPassword = document.getElementById("toggleNewPassword");
            const newPasswordField = document.getElementById("new_password");
            const eyeIconNewPassword = document.getElementById("eyeIconNewPassword");

            toggleNewPassword.addEventListener("click", function() {
                const type = newPasswordField.getAttribute("type") === "password" ? "text" : "password";
                newPasswordField.setAttribute("type", type);
                eyeIconNewPassword.classList.toggle("fa-eye");
                eyeIconNewPassword.classList.toggle("fa-eye-slash");
            });

            const toggleNewPasswordConfirmation = document.getElementById("toggleNewPasswordConfirmation");
            const newPasswordConfirmationField = document.getElementById("new_password_confirmation");
            const eyeIconNewPasswordConfirmation = document.getElementById("eyeIconNewPasswordConfirmation");

            toggleNewPasswordConfirmation.addEventListener("click", function() {
                const type = newPasswordConfirmationField.getAttribute("type") === "password" ? "text" :
                    "password";
                newPasswordConfirmationField.setAttribute("type", type);
                eyeIconNewPasswordConfirmation.classList.toggle("fa-eye");
                eyeIconNewPasswordConfirmation.classList.toggle("fa-eye-slash");
            });
        });
    </script>

    @yield('js')

</body>

</html>
