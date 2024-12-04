@extends('Layouts.Main')

@section('title', 'Detail Evaluasi Prodi')

@section('content')
    <div class="container-fluid my-4">
        <button onclick="window.history.back();" class="btn btn-link p-0 text-decoration-none"><i class="fas fa-arrow-left mb-4 me-2"></i> Kembali
        </button>
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <h5 class="card-title">Detail Perkara Program Studi
                            </h5>
                            @can('Edit Evaluasi Program Studi')
                                <a href="{{ route('evaluasi-prodi.edit', $perkaras->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-2"></i> Edit</a>
                                </a>
                            @endcan
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6><strong>Nama Badan Penyelenggara:</strong></h6>
                                <p>{{ $perkaras->organisasi_nama }}</p>
                            </div>

                            <div class="col-md-6">
                                <h6><strong>Status Badan Penyelenggara</strong></h6>
                                <p>{{ $perkaras->organisasi_status }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Nama Program Studi</strong></h6>
                                <p>{{ $perkaras->prodi_nama ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Nomor Perkara:</strong></h6>
                                <p>{{ $perkaras->no_perkara }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Judul Perkara:</strong></h6>
                                <p>{{ $perkaras->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Tanggal Kejadian:</strong></h6>
                                <p>{{ \Carbon\Carbon::parse($perkaras->tanggal_kejadian)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Status Perkara:</strong></h6>
                                <p>{{ $perkaras->status }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6><strong>Deskripsi Kejadian:</strong></h6>
                                <p>{{ $perkaras->deskripsi_kejadian }}</p>
                            </div>
                            <div class="col-12 mb-3">
                                <h6><strong>Bukti Foto:</strong></h6>
                                <div class="d-flex flex-wrap">
                                    @if ($perkaras->bukti_foto && count(json_decode($perkaras->bukti_foto)) > 0)
                                        @foreach (json_decode($perkaras->bukti_foto) as $foto)
                                            <div class="image-preview d-flex gap-1"
                                                style="position: relative; width: 150px; height: 150px;">
                                                <img src="{{ asset('storage/bukti_foto/' . $foto) }}" alt="Bukti Foto"
                                                    class="img-thumbnail"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                                <div class="overlay">
                                                    <a href="{{ asset('storage/bukti_foto/' . $foto) }}" target="_blank"
                                                        class="btn btn-sm btn-primary"><i class="fas fa-eye"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-muted">Tidak ada bukti foto yang tersedia.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
