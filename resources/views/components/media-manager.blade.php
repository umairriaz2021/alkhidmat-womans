<div class="modal fade" id="mediaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold"><i class="mdi mdi-folder-multiple-image me-2"></i>Media Library</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0" style="height: 550px;">
                    <div class="col-md-9 d-flex flex-column border-end bg-white">
                        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
                            <button class="btn btn-primary btn-sm" onclick="$('#media-upload-input').click()">
                                <i class="mdi mdi-upload"></i> Upload
                            </button>
                            <input type="file" id="media-upload-input" class="d-none" accept="image/*">
                            <input type="text" id="media-search" class="form-control form-control-sm w-50" placeholder="Search images...">
                        </div>
                        <div class="p-4 overflow-auto flex-grow-1">
                            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-3" id="media-list-container">
                                </div>
                        </div>
                    </div>
                    <div class="col-md-3 bg-light p-3">
                        <h6 class="fw-bold small text-muted text-uppercase mb-3">Selected File</h6>
                        <div id="detail-preview" class="mb-3 border rounded bg-white d-flex align-items-center justify-content-center" style="aspect-ratio: 1/1; overflow: hidden;">
                            <i class="mdi mdi-image-off h1 text-light"></i>
                        </div>
                        <div id="detail-info" class="d-none small">
                            <p class="mb-1 text-truncate"><strong>Name:</strong> <span id="info-name"></span></p>
                            <p class="mb-3"><strong>Size:</strong> <span id="info-size"></span></p>
                            <button class="btn btn-success w-100 fw-bold" id="confirm-selection-btn">Insert Image</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>