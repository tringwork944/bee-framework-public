<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Danh sach tai khoan</h3>
    <a class="btn btn-primary btn-sm" href="/tai-khoan/them">Them tai khoan</a>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead><tr><th>ID</th><th>Ho ten</th><th>Email</th><th>Trang thai</th><th class="text-end"></th></tr></thead>
      <tbody>
      <?php foreach($ds as $item): ?>
        <tr>
          <td><?= (int)$item['id'] ?></td>
          <td><?= bao_mat_chuoi($item['ho_ten']) ?></td>
          <td><?= bao_mat_chuoi($item['email']) ?></td>
          <td><?= (int)$item['trang_thai'] ? 'Kich hoat' : 'Khoa' ?></td>
          <td class="text-end">
            <a class="btn btn-sm" href="/tai-khoan/sua/<?= (int)$item['id'] ?>">Sua</a>
            <form method="post" action="/tai-khoan/xoa/<?= (int)$item['id'] ?>" class="d-inline">
              <input type="hidden" name="_csrf" value="<?= bao_mat_chuoi(csrf_tao()) ?>">
              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xoa tai khoan nay?')">Xoa</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
