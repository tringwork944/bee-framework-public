# Bee Frame

Bee Frame là một dự án PHP theo hướng mô đun, phù hợp để xây dựng các ứng dụng quản trị nội bộ và mở rộng tính năng theo từng khối chức năng. Repository public này tập trung vào cách cài đặt, cấu hình và vận hành cơ bản để người mới có thể tiếp cận nhanh.

## Tính năng chính

- Tổ chức chức năng theo mô đun, dễ bổ sung hoặc mở rộng.
- Có sẵn xác thực đăng nhập, tổng quan, quản lý tài khoản và quản lý mô đun.
- Hỗ trợ bật, tắt, cài đặt và kiểm tra mô đun trong giao diện quản trị.
- Hỗ trợ tải lên gói mô đun `.zip` nếu máy chủ có `ZipArchive`.
- Sử dụng giao diện web có sẵn để đăng nhập và quản lý hệ thống.

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
git clone <repo-url>
cd bee-frame
```

### 2. Cấu hình web server trỏ vào thư mục `cong_khai`

- Document root/Web root cần trỏ đến thư mục `cong_khai/`.
- Không trỏ trực tiếp web server vào root repository hoặc thư mục `ung_dung/`.

Ví dụ:

- Apache: trỏ `DocumentRoot` vào `.../bee-frame/cong_khai`
- Nginx: trỏ `root` vào `.../bee-frame/cong_khai`

### 3. Tạo database

Tạo một database rỗng trên MySQL/MariaDB, ví dụ:

```sql
CREATE DATABASE bee_frame CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Tạo file cấu hình môi trường từ file mẫu

Project đọc cấu hình từ file `.env` ở root repository.

```bash
cp .env.example .env
```

Cập nhật các giá trị trong `.env` cho phù hợp môi trường:

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

### 5. Nạp dữ liệu khởi tạo

Project hiện tại không dùng wizard cài đặt riêng. Để chạy đúng ngay, hãy import file:

`ung_dung/kho_luu/du_lieu_mau.sql`

Đây là bộ schema và dữ liệu mẫu cần thiết để hệ thống khởi động và đăng nhập lần đầu.

### 6. Phân quyền thư mục cần ghi

Đảm bảo PHP/web server có quyền ghi vào:

- `ung_dung/kho_luu/tam/`

Nếu bạn sử dụng tính năng tải lên mô đun `.zip`, hệ thống sẽ tạo thêm thư mục tạm con bên trong khu vực này.

### 7. Truy cập trình duyệt và đăng nhập

Sau khi cấu hình xong:

- Mở trình duyệt đến trang chủ hoặc `/dang-nhap`
- Đăng nhập tài khoản mẫu:
  - Email: `admin@example.com`
  - Mật khẩu: `password`

Nên đổi mật khẩu ngay sau khi đăng nhập thành công nếu bạn dùng dữ liệu mẫu.

### 8. Cài đặt hoặc bật mô đun nếu cần

- Các mô đun hệ thống cần thiết đã được đồng bộ sẵn.
- Với mô đun thông thường, vào menu `Mô đun` hoặc truy cập `/quan-ly-mo-dun`.
- Nếu mô đun mới chỉ vừa được đưa vào source hoặc tải lên bằng file `.zip`, hãy thực hiện lần lượt:
  1. `Cài đặt`
  2. `Kích hoạt`

## Cấu hình cơ bản

Project đang đọc trực tiếp file `.env` tại root repo. Các biến đang dùng gồm:

- `UNG_DUNG_URL`: URL cơ bản của ứng dụng.
- `UNG_DUNG_DEBUG`: bật/tắt debug, nên để `false` khi public.
- `UNG_DUNG_MUOI`: chuỗi bí mật nội bộ, cần đổi giá trị mặc định trước khi triển khai thật.
- `DB_MAY_CHU`, `DB_CONG`, `DB_TEN`, `DB_BANG_MA`, `DB_NGUOI_DUNG`, `DB_MAT_KHAU`: cấu hình kết nối database.

## Chạy dự án

- Nếu đã cấu hình web server dùng `cong_khai/`, chỉ cần truy cập domain/local host đã khai báo.
- Nếu dùng môi trường local tự cài, hãy đảm bảo PHP và database đang hoạt động trước khi truy cập.

Sau khi đăng nhập, hệ thống sẽ đưa bạn vào khu vực tổng quan và các chức năng quản trị có sẵn.

## Quản lý mô đun cơ bản

- Thư mục mô đun nằm trong `ung_dung/mo_dun/`.
- Có sẵn một mô đun mẫu tại `ung_dung/mo_dun/_mau/` để tham khảo cách tạo mô đun mới.
- Mô đun thông thường có thể cài đặt, kích hoạt, tắt và gỡ cài đặt trong `/quan-ly-mo-dun`.
- Khi tải lên mô đun `.zip`, hệ thống chỉ chấp nhận gói hợp lệ và không tự động kích hoạt ngay sau khi tải lên.
- Việc gỡ cài đặt mô đun cần được thực hiện cẩn trọng, vì dữ liệu và mã nguồn mô đun có thể bị xóa theo luồng hệ thống.

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

- Không commit file `.env` thật, thông tin CSDL thật, token hoặc secret key.
- Đặt `UNG_DUNG_DEBUG=false` khi đưa lên môi trường public.
- Đổi `UNG_DUNG_MUOI` khỏi giá trị mặc định.
- Kiểm tra quyền ghi cho `ung_dung/kho_luu/tam/`.
- Thay đổi tài khoản quản trị mặc định nếu bạn import `du_lieu_mau.sql`.
- Chỉ tải lên mô đun `.zip` từ nguồn đáng tin cậy.
- Đảm bảo web server chỉ public thư mục `cong_khai/`.

## Đóng góp

Bạn nên tạo branch riêng cho mỗi thay đổi, mô tả rõ phạm vi sửa và tránh đưa file cấu hình thật vào pull request.

## License

Repository hiện chưa kèm file `LICENSE`. Nếu bạn public hoặc phân phối tiếp, hãy bổ sung license phù hợp trước khi sử dụng theo phạm vi rộng hơn.
