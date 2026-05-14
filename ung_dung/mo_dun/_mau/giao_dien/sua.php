<div class="page-header d-print-none mb-3">
    <h2 class="page-title">Sua du lieu mau #<?= (int)($banGhi['id'] ?? 0) ?></h2>
</div>

<div class="card">
    <div class="card-body">
        <form method="post" action="/mau/cap-nhat/<?= (int)($banGhi['id'] ?? 0) ?>" class="row g-3">
            <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
            <div class="col-12 col-md-6">
                <label class="form-label">Ten</label>
                <input class="form-control" name="ten" value="<?= bao_mat_chuoi((string)($banGhi['ten'] ?? '')) ?>" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Luu thay doi</button>
                <a class="btn btn-outline-secondary" href="/mau">Quay lai</a>
            </div>
        </form>
    </div>
</div>
