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

                @canany(['View Peringkat Akreditasi', 'View Jenis Organisasi', 'View Lembaga Akreditasi', 'View
                    Jabatan'])
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-chart-donut-3"></i>
                            </span>
                            <span class="hide-menu">Master</span>
                        </a>
                        <ul aria-expanded="false" class="first-level collapse">
                            @can('View Peringkat Akreditasi')
                                <li class="sidebar-item">
                                    <a href="{{ route('peringkat-akademik.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Peringkat Akreditasi</span>
                                    </a>
                                </li>
                            @endCan
                            @can('View Lembaga Akreditasi')
                                <li class="sidebar-item">
                                    <a href="{{ route('lembaga-akademik.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Lembaga Akreditasi</span>
                                    </a>
                                </li>
                            @endCan
                            @can('View Jenis Organisasi')
                                <li class="sidebar-item">
                                    <a href="{{ route('organisasi-type.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Jenis Organisasi</span>
                                    </a>
                                </li>
                            @endCan
                            @can('View Jabatan')
                                <li class="sidebar-item">
                                    <a href="{{ route('jabatan.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Jabatan</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['View Badan Penyelenggara', 'View Perguruan Tinggi', 'View Program Studi'])
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                            <span class="d-flex">
                                <i class="fa fa-sitemap"></i>
                            </span>
                            <span class="hide-menu">Organisasi</span>
                        </a>
                        <ul aria-expanded="false" class="first-level collapse">
                            @can('View Badan Penyelenggara')
                                <li class="sidebar-item">
                                    <a href="{{ route('badan-penyelenggara.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Badan Penyelenggara</span>
                                    </a>
                                </li>
                            @endCan
                            @can('View Perguruan Tinggi')
                                <li class="sidebar-item">
                                    <a href="{{ route('perguruan-tinggi.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Perguruan Tinggi</span>
                                    </a>
                                </li>
                            @endcan

                            @can('View Program Studi')
                                <li class="sidebar-item">
                                    <a href="{{ route('program-studi.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Program Studi</span>
                                    </a>
                                </li>
                            @endcan
                                <li class="sidebar-item">
                                    <a href="{{ route('perkara.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Evaluasi</span>
                                    </a>
                                </li>
                        </ul>
                    </li>
                @endcanany
                @canany(['View User', 'View Roles', 'View Permission'])
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow" href="#" aria-expanded="false">
                            <span class="d-flex">
                                <i class="ti ti-chart-donut-3"></i>
                            </span>
                            <span class="hide-menu">Manajemen User</span>
                        </a>
                        <ul aria-expanded="false" class="first-level collapse">
                            @can('View User')
                                <li class="sidebar-item">
                                    <a href="{{ route('user.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">User</span>
                                    </a>
                                </li>
                            @endCan
                            @can('View Roles')
                                <li class="sidebar-item">
                                    <a href="{{ route('roles.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Role User</span>
                                    </a>
                                </li>
                            @endCan
                            @can('View Permission')
                                <li class="sidebar-item">
                                    <a href="{{ route('permission.index') }}" class="sidebar-link">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">Permission User</span>
                                    </a>
                                </li>
                            @endCan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </nav>
    </div>
</aside>
