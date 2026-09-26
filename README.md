# Pro-Metal — WordPress theme

Theme marketing cho Cơ Khí Pro-Metal (suachuacuasat.com). Classic PHP theme + `theme.json`, tải nhẹ, chuẩn nhận diện Pro-Metal.

## Cài đặt
Copy thư mục `prometal/` vào `wp-content/themes/` rồi kích hoạt trong **Giao diện → Themes**.

## Kiến trúc chống xung đột (đọc kỹ)
- `functions.php` **tự động nạp mọi file** trong `inc/` → thêm logic = tạo file mới trong `inc/`, **không sửa** `functions.php`.
- `inc/enqueue.php` đã **pre-wire** toàn bộ CSS/JS → session sau chỉ **điền nội dung** vào file `assets/css|js/*`, **không sửa** `enqueue.php`.
- `assets/css/tokens.css` là **nguồn chân lý** màu/chữ/spacing — chỉ ĐỌC `var(--pm-*)`, **không sửa**.
- Mỗi file có `@owner Session X` ở đầu → **chỉ Session đó được sửa file đó**.

## Phân chia công việc & quy chuẩn
Xem **`../THEME-DEV-GUIDE.md`** (ở thư mục gốc project) — mô tả 6 Session, nhiệm vụ, ranh giới file, và quy tắc không đụng code nhau.

## Quy ước code
- Prefix hàm: `prometal_` · prefix biến CSS: `--pm-` · text domain: `prometal`.
- Escape mọi output (`esc_html`, `esc_attr`, `esc_url`); i18n bằng `__()/_e()`.
- PHP thụt bằng tab, không BOM. CSS/JS vanilla, hạn chế thư viện.

## Form lead và Telegram

Mọi form `pm-lead-form` và landing cũ dùng `pm-form` đều được nhận tại một
endpoint duy nhất: `POST /wp-json/prometal/v1/lead`. Lead được lưu trước, sau
đó thông báo email, Telegram và tích hợp CRM chạy qua WP-Cron để không làm
người dùng phải chờ.

Đặt thông tin Telegram trong `wp-config.php`, không đặt trong Customizer hay
file theme:

```php
define( 'PROMETAL_TELEGRAM_BOT_TOKEN', 'bot-token-cua-ban' );
define( 'PROMETAL_TELEGRAM_CHAT_ID', 'chat-id-cua-ban' );
```

Nếu website đang có tích hợp CRM/Zalo OA riêng, gắn nó vào hook
`prometal_lead_saved`; hook này được gọi từ tác vụ nền và nhận `$lead_id`,
`$data` (`ten`, `sdt`, `noidung`, `src`).

## Tối ưu mobile (Session M)

- `inc/mobile.php`: inline CSS của theme vào `<head>` (đã rút gọn, bỏ request CSS chặn hiển thị);
  chuyển ký tự như "à" đứng ngay trước thẻ HTML thành thực thể `&#224;` để plugin nén HTML
  (SpeedyCache "Minify HTML") không cắt mất byte → hết lỗi "nh�". Tắt bằng filter
  `prometal_inline_css` / `prometal_utf8_guard` (trả `false`). `?ver=` của CSS/JS theme =
  `PROMETAL_VERSION` + thời điểm sửa file → cập nhật theme không cần tăng số phiên bản. Trang chủ
  chỉ giữ 1 thẻ `<meta name="description">` (SiteSEO in 2 thẻ khi trang chủ là trang tĩnh).
- `inc/media.php`: `pm_theme_img( 'assets/img/x.jpg', array( 'widths' => array( 480, 800 ), 'sizes' => '…' ) )`
  in `<img>` WebP đúng cỡ + `srcset` + `width/height`. Bản thu nhỏ đặt tên `x-480.webp`, `x-800.webp`
  cạnh ảnh gốc; ảnh hero cắt riêng cho điện thoại đặt tên `hero-N-m.webp` (dùng ở ≤480px).
  Ảnh trong nội dung dán tay (thiếu class `wp-image-ID`) được gắn lại ID để WordPress tự thêm
  `srcset/sizes/width/height/lazy`.
- `inc/links.php`: `pm_service_url( 'sua-cua-sat' )` trả link trang dịch vụ CÓ THẬT (thử lần lượt
  các slug, filter `prometal_service_slugs`) — không gõ cứng đường dẫn trong template nữa.
- Mốc menu ngang/thanh CTA dính: **1200px** (header-footer.css, components.css, landing.css, main.js).
- Cuối `base.css`: tương thích nội dung cũ bọc `<div class="pm-page">` (trang Giới thiệu) — đủ để gỡ
  snippet `pm-page-css` ngoài theme.
- Việc còn lại trong wp-admin (snippet cũ, sitemap, trang test, meta trùng): `docs/wp-admin-fixes.md`.
