<!-- Detail Modal -->
<div class="modal fade" id="detailRecordModalAkreditasi" tabindex="-1" aria-labelledby="detailRecordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailRecordModalLabel">Detail Akreditasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Nama Perguruan Tinggi</th>
                            <td id="org_nama"></td>
                        </tr>
                        <tr id="prodi">
                            <th>Nama Prodi</th>
                            <td id="prodi_nama"></td>
                        </tr>
                        <tr>
                            <th>Lembaga Akreditasi</th>
                            <td id="lembaga_nama"></td>
                        </tr>
                        <tr>
                            <th>Peringkat Akreditasi</th>
                            <td id="peringkat_nama"></td>
                        </tr>
                        <tr>
                            <th>Nomor SK Akreditasi</th>
                            <td id="akreditasi_sk"></td>
                        </tr>
                        <tr>
                            <th>Status Akreditasi</th>
                            <td id="akreditasi_status"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Berlaku</th>
                            <td id="akreditasi_tgl_awal"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Berakhir</th>
                            <td id="akreditasi_tgl_akhir"></td>
                        </tr>
                        <tr>
                            <th>Dokumen SK Akreditasi</th>
                            <td>
                                <form action="{{ route('akreditasi-perguruan-tinggi.viewPdf') }}" method="post"
                                    enctype="multipart/form-data" target="_blank">
                                    @csrf
                                    <input type="hidden" name="akreditasi_dokumen" id="akreditasi_dokumen"
                                        value="">
                                    <button type="submit" class="btn btn-link waves-effect">Dokumen</button>
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
