(() => {
  const loading = document.getElementById('dashboard-loading');
  const content = document.getElementById('dashboard-content');
  const empty = document.getElementById('dashboard-empty');
  if (!loading || !content || !empty || typeof ApexCharts === 'undefined' || typeof NhanSuApexChart === 'undefined') {
    return;
  }

  const fmt = (n) => Number(n || 0).toLocaleString('vi-VN');
  const charts = [];
  const toNumberList = (arr, key) => (Array.isArray(arr) ? arr.map((x) => Number((x && x[key]) || 0)) : []);
  const toTextList = (arr, key) => (Array.isArray(arr) ? arr.map((x) => String((x && x[key]) || '')) : []);

  fetch('/nhan-su/api/thong-ke')
    .then((r) => r.json())
    .then((resp) => {
      if (!resp.ok || !resp.du_lieu) {
        throw new Error('empty');
      }

      const d = resp.du_lieu;
      const k = d.thongKe || {};
      const cards = [
        ['Tong nhan vien', k.tong_nhan_vien, 'ti ti-users'],
        ['Dang lam', k.dang_lam, 'ti ti-user-check'],
        ['Tam nghi', k.tam_nghi, 'ti ti-user-pause'],
        ['Nghi viec', k.nghi_viec, 'ti ti-user-x'],
        ['Di muon hom nay', k.di_muon_hom_nay, 'ti ti-clock'],
        ['Vang hom nay', k.vang_hom_nay, 'ti ti-user-off']
      ];

      const kpi = document.getElementById('kpi-cards');
      if (kpi) {
        kpi.innerHTML = cards
          .map((c) => `<div class="col-sm-6 col-lg-4"><div class="card card-sm"><div class="card-body py-2"><div class="d-flex align-items-center"><span class="avatar avatar-sm me-2"><i class="${c[2]}"></i></span><div><div class="text-secondary">${c[0]}</div><div class="h2 m-0">${fmt(c[1])}</div></div></div></div></div></div>`)
          .join('');
      }

      loading.classList.add('d-none');
      content.classList.remove('d-none');

      const mk = (el, cfg) => {
        const target = document.querySelector(el);
        if (!target) {
          return;
        }
        const chart = new NhanSuApexChart(target, cfg);
        if (chart.render()) {
          charts.push(chart);
        }
      };

      mk('#chart-phong-ban', {
        type: 'bar',
        height: 260,
        series: [{ name: 'Nhan vien', data: toNumberList(d.duLieuPhongBan, 'total') }],
        categories: toTextList(d.duLieuPhongBan, 'name'),
        options: {
          chart: { toolbar: { show: false } },
          dataLabels: { enabled: false }
        }
      });

      mk('#chart-trang-thai', {
        type: 'donut',
        height: 260,
        series: [{ name: 'Trang thai', data: toNumberList(d.duLieuTrangThai, 'total') }],
        labels: toTextList(d.duLieuTrangThai, 'status')
      });

      mk('#chart-7ngay', {
        type: 'line',
        height: 260,
        series: [{ name: 'Cham cong', data: toNumberList(d.duLieu7Ngay, 'total') }],
        categories: toTextList(d.duLieu7Ngay, 'attendance_date'),
        options: { chart: { toolbar: { show: false } } }
      });

      mk('#chart-thang', {
        type: 'area',
        height: 260,
        series: [{ name: 'Tong cong', data: toNumberList(d.duLieuTangTruong, 'total') }],
        categories: toTextList(d.duLieuTangTruong, 'ym'),
        options: { chart: { toolbar: { show: false } } }
      });

      const list = (el, arr, mapFn) => {
        const node = document.getElementById(el);
        if (!node) {
          return;
        }
        const a = (arr || []).slice(0, 5);
        node.innerHTML = a.length
          ? a.map(mapFn).join('')
          : '<div class="list-group-item text-secondary">Khong co du lieu</div>';
      };

      list('widget-nhan-vien-moi', d.nhanVienMoi, (x) => `<div class="list-group-item">${x.full_name} <span class="text-secondary">(${x.employee_code})</span></div>`);
      list('widget-bat-thuong', d.chamCongBatThuong, (x) => `<div class="list-group-item d-flex justify-content-between"><span>${x.full_name}</span><span class="badge bg-warning-lt text-warning">${x.status}</span></div>`);
      list('widget-sinh-nhat', d.sinhNhatGanDay, (x) => `<div class="list-group-item d-flex justify-content-between"><span>${x.full_name}</span><span class="text-secondary">${x.birth_date || ''}</span></div>`);
    })
    .catch(() => {
      loading.classList.add('d-none');
      empty.classList.remove('d-none');
    });

  window.addEventListener('beforeunload', () => {
    charts.forEach((chart) => chart.destroy());
  });
})();
