<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="handheldfriendly" content="true" />
    <meta name="MobileOptimized" content="width" />
    <meta name="description" content="Mordenize" />
    <meta name="author" content="" />
    <meta name="keywords" content="Mordenize" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/lldikti.png') }}" />
    <link id="themeColors" rel="stylesheet" href="../../dist/css/style.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.2/toastify.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.11.2/toastify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
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
        <div
            class="position-relative radial-gradient min-vh-100 d-flex align-items-center justify-content-center overflow-hidden">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="{{ route('dashboard.index') }}"
                                    class="text-nowrap logo-img d-block w-100 mb-2 text-center">
                                    <img src="{{ asset('assets/images/logo_lldikti.png') }}" alt="LLDIKTI Logo"
                                        style="height: 100px;">
                                </a>

                                <form method="post" action="{{ route('login.store') }}">
                                    @csrf
                                    <div class="mb-4 text-center">
                                        <h3 class="fw-bold">Selamat Datang di LLDIKTI Wilayah 7</h3>
                                        <p class="text-muted">Silakan masuk dengan akun Anda untuk melanjutkan.</p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Username</label>
                                        <input type="text" class="form-control" name="email"
                                            id="exampleInputEmail1" aria-describedby="emailHelp">
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control"
                                            id="exampleInputPassword1">
                                    </div>

                                    <button class="btn btn-primary w-100 rounded-2 mb-4 py-8" type="submit">
                                        Sign In
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="../../dist/libs/simplebar/dist/simplebar.min.js"></script>
    <script src="../../dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/app.min.js"></script>
    <script src="../../dist/js/app.init.js"></script>
    <script src="../../dist/js/app-style-switcher.js"></script>
    <script src="../../dist/js/sidebarmenu.js"></script>
    <script src="../../dist/js/custom.js"></script>

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

    @if (session('error'))
        <script>
            $(document).ready(function() {
                Toastify({
                    text: '⚠️ {{ session('error') }}',
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
</body>

</html>
