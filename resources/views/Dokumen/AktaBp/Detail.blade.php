<!-- Detail Modal -->
<div class="modal fade" id="detailRecordModalAkta" tabindex="-1" aria-labelledby="detailRecordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailRecordModalLabel">Detail Akta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Nomor Akta</th>
                            <td id="akta_nomor"></td>
                        </tr>
                        <tr id="prodi">
                            <th>Jenis Akta</th>
                            <td id="akta_jenis"></td>
                        </tr>
                        <tr>
                            <th>Akta Tanggal</th>
                            <td id="akta_tanggal"></td>
                        </tr>
                        <tr>
                            <th>Nama Notaris</th>
                            <td id="akta_notaris_nama"></td>
                        </tr>
                        <tr>
                            <th>Kota Notaris</th>
                            <td id="akta_notaris_kota"></td>
                        </tr>
                        <tr>
                            <th>Status Akta</th>
                            <td id="akta_status"></td>
                        </tr>
                        <tr>
                            <th>Dokumen Akta</th>
                            <td>
                                <form action="{{ route('akta-badan-penyelenggara.viewPdf') }}" method="post"
                                    enctype="multipart/form-data" target="_blank">
                                    @csrf
                                    <input type="hidden" name="akta_dokumen" id="akta_dokumen" value="">
                                    <button type="submit" class="btn btn-link waves-effect"
                                        id="btn_pdf_akta">Dokumen</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <th>Nomor SK Kumham</th>
                            <td id="kumham_nomor"></td>
                        </tr>
                        <tr>
                            <th>Perihal SK Kumham</th>
                            <td id="kumham_perihal"></td>
                        </tr>
                        <tr>
                            <th>Tanggal SK Kumham</th>
                            <td id="kumham_tanggal"></td>
                        </tr>
                        <tr>
                            <th>Dokumen SK Kumham</th>
                            <td>
                                <form action="{{ route('sk-kumham.viewPdf') }}" method="post"
                                    enctype="multipart/form-data" target="_blank">
                                    @csrf
                                    <input type="hidden" name="kumham_dokumen" id="kumham_dokumen" value="">
                                    <button type="submit" class="btn btn-link waves-effect"
                                        id="btn_pdf_kumham">Dokumen</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
