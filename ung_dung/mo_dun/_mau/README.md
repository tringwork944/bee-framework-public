# Mo dun mau (_mau)

Thu muc `_mau` la template de ban copy khi tao mo dun custom moi.

## Cach su dung nhanh
1. Copy thu muc: `ung_dung/mo_dun/_mau` -> `ung_dung/mo_dun/san_pham` (hoac ten khac).
2. Doi ten file/class:
   - `MauDieuKhien` -> class dieu khien moi
   - `Mau` -> class mo hinh moi
3. Cap nhat `cau_hinh.php`:
   - `ma`, `ten`, `route`, `quyen`, `menu`, `tai_nguyen`
   - Dat `kich_hoat` thanh `true` khi san sang su dung.
4. Cap nhat view trong `giao_dien/` theo nhu cau.
5. Tao bang CSDL cho module moi va cap nhat truy van trong `mo_hinh/`.
6. Dong bo menu:
   - He thong se tu dong dong bo menu seed tu `cau_hinh.php` vao `menu_he_thong` khi module duoc nap.

## Luu y quan trong
- Thu muc bat dau bang `_` se bi `BoNapMoDun` bo qua, khong dang ky route/menu/quyen.
- Vi vay `_mau` khong bao gio hoat dong nhu module that.
- Sau khi copy, hay doi ten thu muc khong bat dau bang `_`.

## Checklist doi ten
- `ma` trong `cau_hinh.php`
- namespace trong `dieu_khien/` va `mo_hinh/`
- class controller/model
- route URL (`/mau` -> URL moi)
- quyen (`_mau.*` -> `san_pham.*`...)
- menu seed (`ma`, `tieu_de`, `duong_dan`, `nhom`)
- duong dan tai nguyen CSS/JS
