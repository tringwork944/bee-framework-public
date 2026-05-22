# Bee Frame

Bee Frame là một dự án PHP theo hướng mô đun, phù hợp để xây dựng các ứng dụng quản trị nội bộ và mở rộng chức năng theo từng khối nghiệp vụ. Phiên bản repository public này được trình bày theo hướng dễ tiếp cận cho người dùng và nhà phát triển mới, tập trung vào cài đặt, cấu hình cơ bản và vận hành an toàn.

## Giới thiệu ngắn gọn

Dự án cung cấp một nền tảng quản trị viết bằng PHP với cách tổ chức chức năng theo mô đun. Một số mô đun hệ thống cơ bản đã có sẵn để phục vụ đăng nhập, tổng quan, quản lý tài khoản và quản lý mô đun.

README này chỉ trình bày ở mức sử dụng và triển khai cơ bản, không đi sâu vào các chi tiết lõi hoặc logic nội bộ của core.

## Tính năng chính

- Tổ chức chức năng theo mô đun để dễ mở rộng.
- Có sẵn đăng nhập, trang tổng quan và một số chức năng quản trị cơ bản.
- Hỗ trợ quản lý vòng đời mô đun từ giao diện quản trị.
- Hỗ trợ tải lên gói mô đun `.zip` nếu máy chủ có `ZipArchive`.
- Tách thư mục public web và phần mã nguồn ứng dụng để thuận tiện khi triển khai.

## Yêu cầu hệ thống

| Thành phần | Yêu cầu tối thiểu |
| --- | --- |
| PHP | 8.1+ |
| CSDL | MySQL / MariaDB |
| Web server | Apache hoặc Nginx |
| PHP extension | PDO MySQL, session, fileinfo |
| Tùy chọn | ZipArchive để tải lên mô đun `.zip` |

## Cài đặt

### 1. Clone repository

```bash
git clone https://github.com/tringwork944/bee-framework-public
cd bee-frame
```

### 2. Cấu hình web server trỏ vào thư mục `cong_khai`

- Web server cần trỏ document root vào `cong_khai/`.
- Không nên public trực tiếp root repository hoặc thư mục `ung_dung/`.

Ví dụ:

- Apache: trỏ `DocumentRoot` vào `.../bee-frame/cong_khai`
- Nginx: trỏ `root` vào `.../bee-frame/cong_khai`

### 3. Tạo database

Tạo một database rỗng trên MySQL/MariaDB, ví dụ:

```sql
CREATE DATABASE bee_frame CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Tạo file cấu hình môi trường

Project đọc cấu hình từ file `.env` tại root repository. Hãy tạo file cấu hình từ mẫu:

```bash
cp .env.example .env
```

Sau đó cập nhật các giá trị trong `.env` theo môi trường của bạn:

```env
UNG_DUNG_URL=http://localhost
UNG_DUNG_DEBUG=false
UNG_DUNG_MUOI=thay-doi-chuoi-bi-mat

DB_MAY_CHU=127.0.0.1
DB_CONG=3306
DB_TEN=bee_frame
DB_BANG_MA=utf8mb4
DB_NGUOI_DUNG=root
DB_MAT_KHAU=
```

### 5. Khởi tạo dữ liệu

Project hiện sử dụng file dữ liệu mẫu để khởi tạo schema và dữ liệu ban đầu:

`ung_dung/kho_luu/du_lieu_mau.sql`

Hãy import file này vào database vừa tạo.

### 6. Phân quyền thư mục cần ghi

Đảm bảo PHP/web server có quyền ghi vào:

- `ung_dung/kho_luu/tam/`

Thư mục này được dùng cho dữ liệu tạm, đặc biệt khi tải lên mô đun.

### 7. Truy cập trình duyệt

Sau khi cấu hình xong:

- Truy cập domain hoặc local host đã cấu hình
- Có thể mở trực tiếp `/dang-nhap` để vào màn hình đăng nhập

Nếu bạn import dữ liệu mẫu, hệ thống sẽ có sẵn tài khoản quản trị mẫu phục vụ mục đích cài đặt thử nghiệm. Nên thay đổi hoặc thay thế dữ liệu này trước khi đưa vào môi trường thật.

### 8. Cài đặt hoặc bật mô đun nếu cần

- Truy cập khu vực quản lý mô đun tại `/quan-ly-mo-dun`
- Với mô đun mới được thêm vào source hoặc tải lên bằng `.zip`, hãy thực hiện:
  1. `Cài đặt`
  2. `Kích hoạt`

## Cấu hình cơ bản

Project hiện sử dụng file `.env` tại root repo. Các biến chính gồm:

- `UNG_DUNG_URL`: URL cơ bản của ứng dụng.
- `UNG_DUNG_DEBUG`: bật/tắt debug, nên để `false` khi public.
- `UNG_DUNG_MUOI`: chuỗi bí mật nội bộ, nên thay đổi trước khi triển khai.
- `DB_MAY_CHU`, `DB_CONG`, `DB_TEN`, `DB_BANG_MA`, `DB_NGUOI_DUNG`, `DB_MAT_KHAU`: cấu hình kết nối database.

## Chạy dự án

Sau khi hoàn tất cấu hình web server, database và file `.env`, chỉ cần truy cập địa chỉ đã cấu hình để sử dụng hệ thống.

Nếu môi trường cài đặt đúng, ứng dụng sẽ hiển thị màn hình đăng nhập và cho phép truy cập các chức năng quản trị tương ứng theo quyền tài khoản.

## Quản lý mô đun cơ bản

- Mã nguồn mô đun nằm trong `ung_dung/mo_dun/`
- Có sẵn thư mục mẫu `ung_dung/mo_dun/_mau/` để tham khảo khi tạo mô đun mới
- Mô đun thông thường có thể được cài đặt, kích hoạt, tắt hoặc gỡ cài đặt trong giao diện quản lý mô đun
- Mô đun tải lên bằng `.zip` không được tự động kích hoạt ngay sau khi tải lên
- Nên kiểm tra kỹ mô đun từ bên thứ ba trước khi sử dụng trên môi trường thật

## Cấu trúc thư mục tổng quan

```text
bee-frame/
|-- cong_khai/          # Web root
|-- ung_dung/
|   |-- cau_hinh/       # Cấu hình ứng dụng
|   |-- he_thong/       # Thành phần hệ thống dùng chung
|   |-- kho_luu/        # Dữ liệu mẫu, thư mục tạm
|   `-- mo_dun/         # Các mô đun chức năng
|-- .env.example
`-- README.md
```

## Lưu ý khi triển khai public

- Không commit file `.env` thật, thông tin CSDL thật, token hoặc secret key
- Đặt `UNG_DUNG_DEBUG=false` trên môi trường public
- Thay `UNG_DUNG_MUOI` khỏi giá trị mặc định
- Kiểm tra quyền ghi cho `ung_dung/kho_luu/tam/`
- Không giữ nguyên dữ liệu hoặc tài khoản mẫu trên môi trường production
- Chỉ public thư mục `cong_khai/` qua web server
- Chỉ cài mô đun từ nguồn đáng tin cậy

## Đóng góp

Nếu muốn đóng góp:

- Tạo branch riêng cho thay đổi của bạn
- Mô tả rõ mục tiêu chỉnh sửa
- Không đưa file cấu hình thật hoặc dữ liệu nhạy cảm vào commit/pull request

## License

Repository hiện chưa kèm file `LICENSE`. Nếu bạn dự định chia sẻ rộng rãi hoặc cho phép bên khác tái sử dụng, nên bổ sung license phù hợp.
