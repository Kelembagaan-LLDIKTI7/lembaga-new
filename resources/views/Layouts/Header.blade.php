<!-- Header Start -->
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
            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/dark-logo.svg"
                class="dark-logo" width="180" alt="" />
            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/logos/light-logo.svg"
                class="light-logo" width="180" alt="" />
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
                    {{-- <li class="nav-item dropdown">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-en.svg"
                                alt="" class="rounded-circle object-fit-cover round-20">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body" data-simplebar>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item gap-2 px-4 py-3">
                                    <div class="position-relative">
                                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-en.svg"
                                            alt="" class="rounded-circle object-fit-cover round-20">
                                    </div>
                                    <p class="fs-3 mb-0">English (UK)</p>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item gap-2 px-4 py-3">
                                    <div class="position-relative">
                                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-cn.svg"
                                            alt="" class="rounded-circle object-fit-cover round-20">
                                    </div>
                                    <p class="fs-3 mb-0">中国人 (Chinese)</p>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item gap-2 px-4 py-3">
                                    <div class="position-relative">
                                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-fr.svg"
                                            alt="" class="rounded-circle object-fit-cover round-20">
                                    </div>
                                    <p class="fs-3 mb-0">français (French)</p>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item gap-2 px-4 py-3">
                                    <div class="position-relative">
                                        <img src="https://demos.adminmart.com/premium/bootstrap/modernize-bootstrap/package/dist/images/svgs/icon-flag-sa.svg"
                                            alt="" class="rounded-circle object-fit-cover round-20">
                                    </div>
                                    <p class="fs-3 mb-0">عربي (Arabic)</p>
                                </a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link notify-badge nav-icon-hover" href="javascript:void(0)"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="ti ti-basket"></i>
                            <span class="badge rounded-pill bg-danger fs-2">2</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-bell-ringing"></i>
                            <div class="notification bg-primary rounded-circle"></div>
                        </a>
                        <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up"
                            aria-labelledby="drop2">
                            <div class="d-flex align-items-center justify-content-between px-7 py-3">
                                <h5 class="fs-5 fw-semibold mb-0">Notifications</h5>
                                <span class="badge bg-primary rounded-4 lh-sm px-3 py-1">5 new</span>
                            </div>
                            <div class="message-body" data-simplebar>
                                <a href="javascript:void(0)" class="d-flex align-items-center dropdown-item px-7 py-6">
                                    <span class="me-3">
                                        <img src="../../dist/images/profile/user-1.jpg" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="fw-semibold mb-1">Roman Joined the Team!</h6>
                                        <span class="d-block">Congratulate him</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item px-7 py-6">
                                    <span class="me-3">
                                        <img src="../../dist/images/profile/user-2.jpg" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="fw-semibold mb-1">New message</h6>
                                        <span class="d-block">Salma sent you new message</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item px-7 py-6">
                                    <span class="me-3">
                                        <img src="../../dist/images/profile/user-3.jpg" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="fw-semibold mb-1">Bianca sent payment</h6>
                                        <span class="d-block">Check your earnings</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item px-7 py-6">
                                    <span class="me-3">
                                        <img src="../../dist/images/profile/user-4.jpg" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="fw-semibold mb-1">Jolly completed tasks</h6>
                                        <span class="d-block">Assign her new tasks</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item px-7 py-6">
                                    <span class="me-3">
                                        <img src="../../dist/images/profile/user-5.jpg" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="fw-semibold mb-1">John received payment</h6>
                                        <span class="d-block">$230 deducted from account</span>
                                    </div>
                                </a>
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center dropdown-item px-7 py-6">
                                    <span class="me-3">
                                        <img src="../../dist/images/profile/user-1.jpg" alt="user"
                                            class="rounded-circle" width="48" height="48" />
                                    </span>
                                    <div class="w-75 d-inline-block v-middle">
                                        <h6 class="fw-semibold mb-1">Roman Joined the Team!</h6>
                                        <span class="d-block">Congratulate him</span>
                                    </div>
                                </a>
                            </div>
                            <div class="mb-1 px-7 py-6">
                                <button class="btn btn-outline-primary w-100"> See All Notifications
                                </button>
                            </div>
                        </div>
                    </li> --}}
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
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" action="{{ route('user.user.updatePassword') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation"
                            name="new_password_confirmation" required>
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

<!-- Header End -->
