<!-- Detail Modal -->
<div class="modal fade" id="detailRecordModalPimpinan" tabindex="-1" aria-labelledby="detailRecordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailRecordModalLabel">Detail Pimpinan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Jabatan Pimpinan</th>
                            <td id="jabatan_nama"></td>
                        </tr>
                        <tr id="prodi">
                            <th>Nama Pimpinan</th>
                            <td id="pimpinan_nama"></td>
                        </tr>
                        <tr>
                            <th>Email Pimpinan</th>
                            <td id="pimpinan_email"></td>
                        </tr>
                        <tr>
                            <th>Nomor SK Pimpinan</th>
                            <td id="pimpinan_sk"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Terbit SK</th>
                            <td id="pimpinan_tanggal"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Berakhir SK</th>
                            <td id="pimpinan_tanggal_berakhir"></td>
                        </tr>
                        <tr>
                            <th>Status Pimpinan</th>
                            <td id="pimpinan_status"></td>
                        </tr>
                        <tr>
                            <th>Dokumen SK Akreditasi</th>
                            <td>
                                <form action="{{ route('pimpinan-perguruan-tinggi.viewPdf') }}" method="post"
                                    enctype="multipart/form-data" target="_blank">
                                    @csrf
                                    <input type="hidden" name="pimpinan_sk_dokumen" id="pimpinan_sk_dokumen"
                                        value="">
                                    <button type="submit" class="btn btn-link waves-effect"
                                        id="btn_pdf">Dokumen</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
