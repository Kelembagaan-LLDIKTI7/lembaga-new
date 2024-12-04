@extends('Layouts.Main')

@section('title', 'Penyatuan Perguruan Tinggi')

@section('css')
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }

        .required-label span {
            color: red;
        }

        #filePreview {
            margin-top: 10px;
        }

        .select2-container .select2-selection--single {
            height: 40px;
            display: flex;
            align-items: center;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <a href="{{ route('perguruan-tinggi.show', ['id' => $perguruanTinggi->id]) }}">
            <i class="fas fa-arrow-left mb-4 me-2"></i> Kembali
        </a>
        <div class="row">
            <div class="col-12">
                <form id="formPTedit" action="{{ route('perguruan-tinggi.updatePenyatuan', $perguruanTinggi->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Penyatuan Perguruan Tinggi</h5>
                            <input type="hidden" name="organisasi_pt" value="{{ $perguruanTinggi->id }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="organisasi_berubah_id" class="required-label">
                                            PT Tujuan
                                        </label>
                                        <select
                                            class="form-control select-search @error('organisasi_berubah_id') is-invalid @enderror"
                                            name="organisasi_berubah_id[]" id="organisasi_berubah_id" required>
                                            <option value="">-- Pilih Perguruan Tinggi --</option>
                                            @foreach ($perguruanTinggis as $pt)
                                                @if ($pt->id !== $perguruanTinggi->id)
                                                    <option value="{{ $pt->id }}"
                                                        {{ in_array($pt->id, old('organisasi_berubah_id', [])) ? 'selected' : '' }}>
                                                        {{ $pt->organisasi_nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('organisasi_berubah_id')
                                            <small class="error-message">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Data Surat Keputusan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="sk_nomor" class="required-label">Nomor Surat Keputusan</label>
                                        <input type="text" name="sk_nomor" id="sk_nomor"
                                            class="form-control @error('sk_nomor') is-invalid @enderror"
                                            value="{{ old('sk_nomor') }}" required>
                                        @error('sk_nomor')
                                            <small class="error-message">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="sk_tanggal" class="required-label">Tanggal SK</label>
                                        <input type="date" name="sk_tanggal" id="sk_tanggal"
                                            class="form-control @error('sk_tanggal') is-invalid @enderror"
                                            value="{{ old('sk_tanggal') }}" required>
                                        @error('sk_tanggal')
                                            <small class="error-message">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="id_jenis_surat_keputusan" class="required-label">Jenis Surat
                                            Keputusan
                                        </label>
                                        <select name="id_jenis_surat_keputusan" id="id_jenis_surat_keputusan"
                                            class="form-control select-search" required>
                                            <option value="">-- Pilih Jenis Surat Keputusan --</option>
                                            @foreach ($jenis as $jsk)
                                                <option value="{{ $jsk->id }}">{{ $jsk->jsk_nama }}</option>
                                            @endforeach
                                        </select>
                                        <small class="error-message" id="error-id_jenis_surat_keputusan"></small>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="sk_dokumen" class="required-label">Dokumen SK</label>
                                        <input type="file" name="sk_dokumen" id="sk_dokumen"
                                            class="form-control @error('sk_dokumen') is-invalid @enderror"
                                            accept=".pdf,.doc,.docx">
                                        <small class="form-text text-muted">Format yang diperbolehkan: PDF, DOC,
                                            DOCX. Maksimal Ukuran File : 2 MB.</small>
                                        @error('sk_dokumen')
                                            <small class="error-message">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                                <div id="loading" class="d-none">
                                    <button class="btn btn-primary" type="button" disabled>
                                        <span class="spinner-border spinner-border-sm"></span> Loading...
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#formPTedit').on('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            const url = $(this).attr('action');

            $('#loading').removeClass('d-none');
            $('#submitBtn').prop('disabled', true);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    window.location.href = response.redirect_url;
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        displayErrors(errors);
                    }
                },
                complete: function() {
                    $('#loading').addClass('d-none');
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });
        function displayErrors(errors) {
            let firstErrorField;
            for (const field in errors) {
                $(`#error-${field}`).text(errors[field].join(', '));
                if (!firstErrorField) {
                    firstErrorField = $(`#${field}`);
                }
            }

            if (firstErrorField) {
                $('html, body').animate({
                    scrollTop: firstErrorField.offset().top - 100
                }, 500);
            }
        }

        $('#sk_dokumen').on('change', function(event) {
        const file = event.target.files[0];
        if (file && file.type === 'application/pdf') {
            const fileURL = URL.createObjectURL(file);
            $('#filePreview').html(
                `<iframe src="${fileURL}" width="100%" height="300px"></iframe>`);
        } else {
            $('#filePreview').html(
                '<small class="text-danger">Hanya file PDF yang dapat dipreview.</small>');
        }
        });
        });
    </script>
@endsection
