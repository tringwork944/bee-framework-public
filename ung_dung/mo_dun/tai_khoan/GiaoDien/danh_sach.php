<div class="card">
  <div class="card-header"><h3 class="card-title">Danh sach tai khoan</h3></div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table">
      <thead><tr><th>ID</th><th>Ho ten</th><th>Email</th><th>Trang thai</th><th></th></tr></thead>
      <tbody>
      <?php foreach($ds as $item): ?>
        <tr>
          <td><?= (int)$item['id'] ?></td>
          <td><?= bao_mat_chuoi($item['ho_ten']) ?></td>
          <td><?= bao_mat_chuoi($item['email']) ?></td>
          <td><?= (int)$item['trang_thai'] ? 'Kich hoat' : 'Khoa' ?></td>
          <td><a class="btn btn-sm" href="/tai-khoan/sua/<?= (int)$item['id'] ?>">Sua</a></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
