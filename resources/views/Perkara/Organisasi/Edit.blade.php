@extends('Layouts.Main')

@section('title', 'Tambah Evaluasi Organisasi')

@section('css')
    <style>
        .form-container {
            display: flex;
        }

        .form-left,
        .form-right {
            flex-basis: 50%;
            padding: 0 10px;
        }

        .image-preview {
            position: relative;
            display: inline-block;
            margin: 5px;
        }

        .image-preview img {
            width: 150px;
            height: auto;
        }

        .image-preview span {
            position: absolute;
            top: 0px;
            right: 5px;
            cursor: pointer;
            color: red;
            font-size: 24px;
            font-weight: bold;
            line-height: 15px;
        }

        .preview-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h3>Edit Evaluasi Organisasi</h3>
                <form id="formPerkaraPT" action="{{ route('evaluasi-organisasi.update', $perkaras->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
                    <input type="hidden" name="id" value="{{ $perkaras->id }}">
                    <div class="form-container">
                        <div class="form-left">
                            <div class="mb-3">
                                <label for="title">Judul Perkara</label>
                                <input type="text" name="title" id="title" class="form-control" required
                                    value="{{ old('title', $perkaras->title) }}">
                                <small class="text-danger error-message" id="error-title"></small>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_kejadian">Tanggal Kejadian</label>
                                <input type="date" name="tanggal_kejadian" id="tanggal_kejadian" class="form-control"
                                    required value="{{ old('tanggal_kejadian', $perkaras->tanggal_kejadian) }}">
                                <small class="text-danger error-message" id="error-tanggal_kejadian"></small>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi_kejadian">Deskripsi Kejadian</label>
                                <textarea name="deskripsi_kejadian" id="deskripsi_kejadian" class="form-control" required>{{ old('deskripsi_kejadian', $perkaras->deskripsi_kejadian) }}</textarea>
                                <small class="text-danger error-message" id="error-deskripsi_kejadian"></small>
                            </div>
                        </div>

                        <div class="form-right">
                            <div class="mb-3">
                                <label for="no_perkara">Nomor Perkara</label>
                                <input type="text" name="no_perkara" id="no_perkara" class="form-control" required
                                    value="{{ old('no_perkara', $perkaras->no_perkara) }}">
                                <small class="text-danger error-message" id="error-no_perkara"></small>
                            </div>
                            <div class="mb-3">
                                <label for="bukti_foto">Bukti Foto</label>
                                <input type="file" name="bukti_foto[]" id="bukti_foto" class="form-control" multiple
                                    accept="image/*">
                                    <small class="form-text text-muted">Format yang diperbolehkan: PNG, JPG, JPEG,
                                    GIF. Maksimal Ukuran File : 2 MB.</small>
                                <small class="text-danger error-message" id="error-bukti_foto"></small>
                            </div>

                            <!-- Display existing images -->
                            @if (isset($existingImages) && count($existingImages) > 0)
                                <div id="existing-previews" class="preview-container">
                                    @foreach ($existingImages as $image)
                                        <div class="image-preview">
                                            <img src="{{ asset('storage/bukti_foto/' . $image) }}" class="img-thumbnail">
                                            <span onclick="removeExistingImage(this)">×</span>
                                            <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- New images preview -->
                            <div id="preview" class="preview-container"></div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div id="buttons">
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-secondary"
                                        onclick="window.history.back();">Keluar</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </div>
                        <div id="loading">
                            <div class="d-flex align-items-center">
                                <strong>Loading...</strong>
                                <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                            </div>
                        </div>
                        <div id="error-messages"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#loading').hide(); // Hide the loading spinner initially

            // Set up CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
            });

            $('#formPerkaraPT').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                $('#buttons').hide(); // Hide the buttons
                $('#loading').show(); // Show the loading spinner

                // Gather form data
                const formData = new FormData(this);

                // $('input[name="existing_images[]"]').each(function () {
                //     formData.append('existing_images[]', $(this).val());
                // });

        // Step 1: Validate the data
        $.ajax({
            url: '{{ route('evaluasi-organisasi.validationUpdatePerkara', ['id' => $perkaras->id]) }}',
            type: 'POST', // Use POST instead of PUT for AJAX requests
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    // Step 2: Submit the form to store the data
                    submitToStore(formData);
                } else {
                    $('#loading').hide(); // Hide the loading spinner
                    $('#buttons').show(); // Show the buttons
                    displayErrors(response.errors);
                }
            },
            error: function (xhr) {
                $('#loading').hide(); // Hide the loading spinner
                $('#buttons').show(); // Show the buttons
                $('#error-messages').html('Terjadi kesalahan pada server. Coba lagi.');
            },
        });
    });

            function displayErrors(errors) {
                // Bersihkan semua pesan error sebelumnya
                $('.error-message').text('');

                // Iterasi semua error
                for (let field in errors) {
                    const errorElement = $(
                    `#error-${field.replace('.*', '')}`); // Tangani array seperti bukti_foto.*
                    if (errorElement.length) {
                        errorElement.text(errors[field].join(', '));
                    } else {
                        console.warn(`Element for field "${field}" not found.`);
                    }
                }
            }


    function submitToStore(formData) {
        $.ajax({
            url: '{{ route('evaluasi-organisasi.update', $perkaras->id) }}', // Update route
            type: 'POST', // Use POST and spoof method as PUT
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    window.location.href = response.redirect_url;
                }
            },
            error: function (xhr) {
                $('#loading').hide(); // Hide the loading spinner
                $('#buttons').show(); // Show the buttons
                $('#error-messages').html(
                    'Terjadi kesalahan pada server saat penyimpanan. Coba lagi.'
                );
            },
        });
    }
});

    </script>
    <script>
        let selectedFiles = [];
        let imagesToDelete = []; // Array to store images that need to be deleted

        document.getElementById('bukti_foto').addEventListener('change', function(event) {
            selectedFiles = Array.from(event.target.files);
            renderPreviews();
        });

        function renderPreviews() {
            const previewContainer = document.getElementById('preview');
            previewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                const fileReader = new FileReader();
                fileReader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('image-preview');
                    imgContainer.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail">
                <span onclick="removeImage(${index})">&times;</span>
            `;
                    previewContainer.appendChild(imgContainer);
                };
                fileReader.readAsDataURL(file);
            });

            updateFileInput();
        }

        function removeImage(index) {
            selectedFiles.splice(index, 1);
            renderPreviews();
        }

        function removeExistingImage(element, imageName) {
            // Remove image preview
            element.parentElement.remove();

            // Add the image name to the deletion list
            imagesToDelete.push(imageName);

            // Remove it from the hidden input to ensure it gets deleted from storage
            const input = element.parentElement.querySelector('input[type="hidden"]');
            if (input) {
                input.disabled = true; // Disable this input so it won't be included in the form submission
            }
        }

        function updateFileInput() {
            const fileInput = document.getElementById('bukti_foto');
            const dataTransfer = new DataTransfer();

            selectedFiles.forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;
        }
    </script>
@endsection
