# Mo dun mau (_mau)

Thu muc `_mau` la bo khung theo chuan lifecycle mo dun moi.
`_mau` khong phai mo dun loi (`la_mo_dun_loi = false`, `kich_hoat_mac_dinh = false`).

## Quy trinh tao mo dun moi
1. Copy `ung_dung/mo_dun/_mau` thanh `ung_dung/mo_dun/ten_mo_dun`.
2. Doi `ma` trong `cau_hinh.php` dung dinh dang `^[a-z0-9_]+$`.
3. Doi namespace/class trong `dieu_khien`, `mo_hinh`.
4. Khai bao `route`, `menu`, `quyen`, `tai_nguyen`, `phu_thuoc` trong `cau_hinh.php`.
5. Viet logic cho:
- `cai_dat.php`
- `kich_hoat.php`
- `tat.php`
- `go_cai_dat.php`
6. Them cau truc/du lieu SQL trong:
- `co_so_du_lieu/migration.sql`
- `co_so_du_lieu/seed.sql`
- `co_so_du_lieu/uninstall.sql`
7. Cai dat va kich hoat trong admin `/quan-ly-mo-dun`.

## Cau truc lifecycle
- `mo_dun.php`: bootstrap dang ky SuKien/BoLoc.
- `cau_hinh.php`: metadata + route/menu/quyen/tai_nguyen.
- `cai_dat.php`: chay lan dau khi cai dat.
- `kich_hoat.php`: chay moi lan kich hoat.
- `tat.php`: chay khi tat.
- `go_cai_dat.php`: chay khi go cai dat.
- `co_so_du_lieu/uninstall.sql`: xoa du lieu rieng cua mo dun trong CSDL khi go cai dat.

## Luu y
- Thu muc bat dau bang `_` bi bo qua khi quet mo dun.
- `_mau` la template, khong duoc nap nhu mo dun that.
- Khi go cai dat, he thong se chay `uninstall.sql` neu tep ton tai.
- Chi khai bao bang rieng cua mo dun trong `uninstall.sql`; khong drop bang loi cua he thong.
