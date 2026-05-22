<div class="row justify-content-center">
    <div class="col-12 col-lg-8 col-xl-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="ti ti-upload me-2"></i>Tai mo dun</h3>
            </div>
            <form method="post" action="/quan-ly-mo-dun/tai-len" enctype="multipart/form-data">
                <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        Chi tai mo dun tu nguon tin cay. He thong se kiem tra cau truc zip, khong tu dong cai dat va khong tu dong kich hoat sau khi tai len.
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="tep_zip">File mo dun (.zip)</label>
                        <input class="form-control" id="tep_zip" type="file" name="tep_zip" accept=".zip,application/zip" required>
                        <div class="form-hint">Toi da 10MB. Zip can chua `cau_hinh.php` va `mo_dun.php`.</div>
                    </div>

                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="xac_nhan_nguon_tin_cay" value="1" required>
                        <span class="form-check-label">Toi hieu chi nen tai mo dun tu nguon tin cay</span>
                    </label>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a class="btn btn-outline-secondary" href="/quan-ly-mo-dun">Quay lai</a>
                    <button class="btn btn-primary ms-auto" type="submit">Tai len</button>
                </div>
            </form>
        </div>
    </div>
</div>
