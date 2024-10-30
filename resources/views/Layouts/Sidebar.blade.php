<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-center">
            <a href="{{ route('dashboard.index') }}" class="text-nowrap logo-img mt-5">
                <img src="{{ asset('assets/images/logo_lldikti.png') }}" alt="LLDIKTI Logo" style="height: 100px;">
            </a>
            <div class="close-btn d-lg-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-muted"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar>
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Menu</span>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('dashboard.index') }}" aria-expanded="false">
                        <span>
                            <i class="ti ti-dashboard"></i>
                        </span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <i class="ti ti-chart-donut-3"></i>
                        </span>
                        <span class="hide-menu">Master</span>
                    </a>
                    <ul aria-expanded="false" class="first-level collapse">
                        <li class="sidebar-item">
                            <a href="{{ route('peringkat-akademik.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Peringkat Akademi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('lembaga-akademik.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Lembaga Akreditasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('organisasi-type.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Jenis Organisasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('jabatan.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Jabatan</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                        <span class="d-flex">
                            <i class="fa fa-sitemap"></i>
                        </span>
                        <span class="hide-menu">Master</span>
                    </a>
                    <ul aria-expanded="false" class="first-level collapse">
                        <li class="sidebar-item">
                            <a href="{{ route('badan-penyelenggara.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Badan Penyelenggara</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('perguruan-tinggi.index') }}" class="sidebar-link">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Perguruan Tinggi</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</aside>
