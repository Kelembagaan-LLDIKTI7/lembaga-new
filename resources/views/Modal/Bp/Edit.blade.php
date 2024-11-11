<div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editStatusForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="editStatusModalLabel">Edit Status Perkara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="perkaraId" name="perkara_id">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Pending"
                                {{ optional($perkaras->first())->status == 'Pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="In Progress"
                                {{ optional($perkaras->first())->status == 'In Progress' ? 'selected' : '' }}>In
                                Progress</option>
                            <option value="Completed"
                                {{ optional($perkaras->first())->status == 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="Diunggah"
                                {{ optional($perkaras->first())->status == 'Diunggah' ? 'selected' : '' }}>Diunggah
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
