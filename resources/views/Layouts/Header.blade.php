<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link sidebartoggler nav-icon-hover ms-n3" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
        </ul>

        <div class="d-block d-lg-none">
            <img src="{{ asset('assets/images/logo_lldikti.png') }}" alt="LLDIKTI Logo" style="height: 100px;">
        </div>
        <button class="navbar-toggler border-0 p-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="p-2">
                <i class="ti ti-dots fs-7"></i>
            </span>
        </button>
        <div class="navbar-collapse justify-content-end collapse" id="navbarNav">
            <div class="d-flex align-items-center justify-content-between">
                <a href="javascript:void(0)" class="nav-link d-flex d-lg-none align-items-center justify-content-center"
                    type="button" data-bs-toggle="offcanvas" data-bs-target="#mobilenavbar"
                    aria-controls="offcanvasWithBothOptions">
                    <i class="ti ti-align-justified fs-7"></i>
                </a>
                <ul class="navbar-nav align-items-center justify-content-center ms-auto flex-row">
                    <li class="nav-item dropdown">
                        <a class="nav-link pe-0" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <div class="user-profile-img">
                                    <img src="../../dist/images/profile/user-1.jpg" class="rounded-circle"
                                        width="35" height="35" alt="" />
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop1">
                            <div class="profile-dropdown position-relative" data-simplebar>
                                <div class="px-7 py-3 pb-0">
                                    <h5 class="fs-5 fw-semibold mb-0">User Profile</h5>
                                </div>
                                <div class="d-flex align-items-center border-bottom mx-7 py-9">
                                    <img src="../../dist/images/profile/user-1.jpg" class="rounded-circle"
                                        width="80" height="80" alt="" />
                                    <div class="ms-3">
                                        <h5 class="fs-3 mb-1">{{ Auth::user()->name }}</h5>

                                        <p class="d-flex text-dark align-items-center mb-0 gap-2">
                                            <i class="ti ti-mail fs-4"></i> {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                </div>
                                <div class="d-grid px-7 py-4 pt-8">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#changePasswordModal">
                                        Ubah Password
                                    </button>
                                </div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <div class="d-grid px-7 py-4 pt-8">
                                        <button class="btn btn-outline-primary" type="submit">Logout</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" action="{{ route('user.user.updatePassword') }}" method="POST">
                    @csrf
                    <div class="position-relative mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                        <span id="toggleNewPassword" class="position-absolute"
                            style="top: 50%; right: 10px; cursor: pointer; transform: translateY(10%);">
                            <i class="fas fa-eye" id="eyeIconNewPassword"></i>
                        </span>
                        @if ($errors->has('new_password'))
                            <div class="text-danger mt-1">
                                {{ $errors->first('new_password') }}
                            </div>
                        @endif
                    </div>

                    <div class="position-relative mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation"
                            name="new_password_confirmation" required>
                        <span id="toggleNewPasswordConfirmation" class="position-absolute"
                            style="top: 50%; right: 10px; cursor: pointer; transform: translateY(10%);">
                            <i class="fas fa-eye" id="eyeIconNewPasswordConfirmation"></i>
                        </span>
                        @if ($errors->has('new_password_confirmation'))
                            <div class="text-danger mt-1">
                                {{ $errors->first('new_password_confirmation') }}
                            </div>
                        @endif
                    </div>

                    <div id="passwordError" class="alert alert-danger hidden" role="alert" style="display: none;">
                        Konfirmasi password baru tidak cocok.
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
