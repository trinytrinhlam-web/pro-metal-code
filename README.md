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
