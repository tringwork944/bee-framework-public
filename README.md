# Bee Framework

Bee Framework (Modular Admin Framework) la framework PHP thuan theo huong mo dun, tap trung vao he thong quan tri noi bo, de mo rong va de trien khai tren shared hosting.

## Tinh nang chinh
- Kien truc mo dun: moi mo dun tu khai bao route, menu, quyen.
- He thong phan quyen theo vai tro (`vai_tro`, `quyen_vai_tro`).
- Layout admin co san, co the bo sung tai nguyen rieng theo mo dun.
- Quan ly bat/tat mo dun runtime.
- Co script cai dat ban dau va seed du lieu mau.

## Screenshot
- Ban co the dat anh tai `docs/images/` va chen vao day.
- Vi du: `![Dashboard](docs/images/dashboard.png)`

## Yeu cau he thong
- PHP 8.1+
- PDO extension
- MySQL/MariaDB
- Apache + mod_rewrite

## Cai dat nhanh
1. Clone source.
2. Tao file `.env` tu `.env.example`.
3. Tao database rong.
4. Import `co_so_du_lieu/seed.sql`.
5. Dam bao `document root` tro vao `cong_khai/`.
6. Chay installer:
   - `php he_thong_lenh/cai_dat.php`
   - hoac `php cai_dat.php`
7. Dang nhap mac dinh:
   - Email: `admin@example.com`
   - Mat khau: `123456`

## Cau truc thu muc
- `cong_khai/`: diem vao web va static assets public
- `cong_khai/dist/vendor/`: third-party assets (Tabler, Tabler Icons, Bootstrap)
- `ung_dung/he_thong/`: core classes (router, DB, auth, helper)
- `ung_dung/giao_dien/`: layout, partials, error views
- `ung_dung/mo_dun/`: cac mo dun chuc nang
- `ung_dung/kho_luu/`: runtime storage (cache, session, logs)
- `co_so_du_lieu/`: seed SQL public-safe
- `he_thong_lenh/`: script CLI ho tro

## Tao mo dun moi
1. Tao thu muc `ung_dung/mo_dun/ten_mo_dun/`.
2. Tao `cau_hinh.php` va khai bao:
   - `ma`, `ten`, `kich_hoat`
   - `route`, `quyen`
   - `menu` (neu co)
3. Tao controller/model/view theo namespace hien tai.
4. Them CSS/JS rieng (neu can) qua khai bao `tai_nguyen`.

## Bat/Tat mo dun
- Truy cap giao dien quan ly: `/quan-ly-mo-dun`.
- Trang thai bat/tat runtime luu tai `ung_dung/kho_luu/mo_dun/trang_thai.json`.

## Seed database
- Seed mau public: `co_so_du_lieu/seed.sql`.
- Khong commit dump DB that hoac du lieu nguoi dung that.

## Chay localhost
- Apache VirtualHost tro vao `cong_khai/`.
- URL env vi du: `UNG_DUNG_URL=http://localhost`.

## Deploy shared hosting
1. Upload toan bo source.
2. Tro `document root` den `cong_khai/` (hoac dua noi dung `cong_khai` vao `public_html`).
3. Tao `.env` tu `.env.example`.
4. Import `co_so_du_lieu/seed.sql`.
5. Chay `php he_thong_lenh/cai_dat.php` de kiem tra quyen ghi.

## Ban quyen
- Source code framework: MIT (`LICENSE`).
- Tai nguyen ben thu ba: xem `THIRD_PARTY_LICENSES.md`.
- Ghi chu phan phoi: xem `NOTICE.md`.
