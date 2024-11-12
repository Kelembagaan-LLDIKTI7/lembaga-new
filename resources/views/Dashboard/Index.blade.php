@extends('Layouts.Main')

@section('title', 'Dashboard')

@section('css')
    <style>
        .card {
            min-height: 180px;
        }

        .card-body h5 {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-body h3 {
            font-size: 2rem;
            margin: 0;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4">Dashboard</h4>
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-university fa-3x text-primary"></i>
                        </div>
                        <div>
                            <h5>Perguruan Tinggi</h5>
                            <h3>{{ $perguruanTinggi }}</h3>
                            <p class="text-muted">Jumlah perguruan tinggi di wilayah 7</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-graduation-cap fa-3x text-success"></i>
                        </div>
                        <div>
                            <h5>Program Studi</h5>
                            <h3>{{ $programStudi }}</h3>
                            <p class="text-muted">Jumlah program studi di wilayah 7</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-school fa-3x text-warning"></i>
                        </div>
                        <div>
                            <h5>Bentuk Perguruan Tinggi</h5>
                            <h3>{{ $bentukPt }}</h3>
                            <p class="text-muted">Jumlah bentuk perguruan tinggi di wilayah 7</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-map-marker-alt fa-3x text-orange"></i>
                        </div>
                        <div>
                            <h5>Wilayah</h5>
                            <h3>{{ $kota }}</h3>
                            <p class="text-muted">Jumlah kota/kabupaten di wilayah 7</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Grafik Perguruan Tinggi</h5>
                        <p>Jumlah Berdasarkan Bentuk Lembaga</p>
                        <canvas id="chartPerguruanTinggi"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Grafik Program Studi</h5>
                        <p>Jumlah Berdasarkan Bentuk Lembaga</p>
                        <canvas id="chartProgramStudi"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Grafik Program Studi</h5>
                        <p>Jumlah Berdasarkan Program Pendidikan</p>
                        <canvas id="chartProgramPendidikan"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <section class="datatables">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                                <h5 class="mb-0">Perkara Yang Sedang Berjalan</h5>
                            </div>

                            <div class="table-responsive mt-2">
                                <table id="dom_jq_event"
                                    class="table-striped table-bordered display text-nowrap table border"
                                    style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Title</th>
                                            <th>Tanggal Kejadian</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($perkaras as $perkara)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $perkara->title }}</td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($perkara->tanggal_kejadian)->translatedFormat('d F Y') }}
                                                </td>
                                                <td>{{ $perkara->status }}</td>
                                                <td>
                                                    <a href="{{ route('dashboard.show', $perkara->id) }}"
                                                        class="btn btn-sm btn-primary me-2">
                                                        <i class="ti ti-info-circle"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> © LLDIKTI 7.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Develop by Tim Kelembagaan MSIB 7
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const baseOptions = {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ${context.raw}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        };

        const chartPerguruanTinggiCtx = document.getElementById('chartPerguruanTinggi').getContext('2d');
        new Chart(chartPerguruanTinggiCtx, {
            type: 'bar',
            data: {
                labels: ['Unknown', 'Politeknik', 'Institut', 'Akademi Komunitas', 'Universitas', 'Akademi',
                    'Sekolah Tinggi'
                ],
                datasets: [{
                    label: 'Jumlah',
                    data: [290, 10, 5, 2, 1, 1, 1],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderRadius: 5
                }]
            },
            options: baseOptions
        });

        const chartProgramStudiCtx = document.getElementById('chartProgramStudi').getContext('2d');
        new Chart(chartProgramStudiCtx, {
            type: 'bar',
            data: {
                labels: ['Akademi', 'Akademi Komunitas', 'Universitas', 'Sekolah Tinggi', 'Institut', 'Politeknik',
                    'Unknown'
                ],
                datasets: [{
                    label: 'Jumlah',
                    data: [0, 0, 6, 0, 0, 0, 1],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderRadius: 5
                }]
            },
            options: baseOptions
        });

        const chartProgramPendidikanCtx = document.getElementById('chartProgramPendidikan').getContext('2d');
        new Chart(chartProgramPendidikanCtx, {
            type: 'bar',
            data: {
                labels: ['S1', 'S2'],
                datasets: [{
                    label: 'Jumlah',
                    data: [6, 1],
                    backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)'],
                    borderRadius: 5
                }]
            },
            options: baseOptions
        });
    </script>
@endsection
