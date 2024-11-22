@extends('Layouts.Main')

@section('title', 'Detail Perkara')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Detail Perkara
                        {{ $perkaras->organisasi->organisasi_type_id == 2 ? 'Badan Penyelenggara' : 'Perguruan Tinggi' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Nama Perguruan Tinggi:</strong></h6>
                            <p>{{ $perkaras->organisasi->organisasi_nama }}</p>
                        </div>

                        <div class="col-md-6">
                            <h6><strong>Status Perguruan Tinggi</strong></h6>
                            <p>{{ $perkaras->organisasi->organisasi_status }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                            <div class="col-md-12">
                                <h6><strong>Nomor Perkara:</strong></h6>
                                <p>{{ $perkaras->no_perkara }}</p>
                            </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Judul Perkara:</strong></h6>
                            <p>{{ $perkaras->title }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Tanggal Kejadian:</strong></h6>
                            <p>{{ \Carbon\Carbon::parse($perkaras->tanggal_kejadian)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6><strong>Status Perkara:</strong></h6>
                            <p>{{ $perkaras->status }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Deskripsi Kejadian:</strong></h6>
                            <p>{{ $perkaras->deskripsi_kejadian }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <h6><strong>Bukti Foto:</strong></h6>
                            <div class="d-flex flex-wrap">
                                @if ($perkaras->bukti_foto && count(json_decode($perkaras->bukti_foto)) > 0)
                                @foreach (json_decode($perkaras->bukti_foto) as $foto)
                                <div class="image-preview m-2"
                                    style="position: relative; width: 150px; height: 150px;">
                                    <img src="{{ asset('storage/bukti_foto/' . $foto) }}" alt="Bukti Foto"
                                        class="img-thumbnail"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                    <div class="overlay">
                                        <a href="{{ asset('storage/bukti_foto/' . $foto) }}" target="_blank"
                                            class="btn btn-sm btn-primary mt-2" style="width: 100%;">Lihat</a>
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
            <a href="{{ route('perguruan-tinggi.show', $perkaras->id_organization) }}" class="btn btn-secondary">
                Kembali
            </a>
            <a href="{{ route('perkara-organisasipt.edit', $perkaras->id) }}"
                    class="btn btn-secondary">Edit
                </a>
        </div>
    </div>
</div>
@endsection