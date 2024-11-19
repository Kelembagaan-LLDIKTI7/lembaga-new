<!-- Detail Modal -->
<div class="modal fade" id="detailRecordModalSK" tabindex="-1" aria-labelledby="detailRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailRecordModalLabel">Detail SK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table-striped table-bordered table">
                    <tbody>
                        <tr>
                            <th>Nomor SK</th>
                            <td id="sk_nomor"></td>
                        </tr>
                        <tr id="prodi">
                            <th>Tanggal Terbit</th>
                            <td id="sk_tanggal"></td>
                            </td>
                        </tr>
                        <tr>
                            <th>Jenis SK</th>
                            <td id="jsk_nama"></td>
                        </tr>
                        <tr>
                            <th>Dokumen SK Akreditasi</th>
                            <td>
                                <form action="{{ route('sk-program-studi.viewPdf') }}" method="post"
                                    enctype="multipart/form-data" target="_blank">
                                    @csrf
                                    <input type="hidden" name="sk_dokumen" id="sk_dokumen" value="">
                                    <button type="submit" class="btn btn-link waves-effect"
                                        id="btn_pdf_sk">Dokumen</button>
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
