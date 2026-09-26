# Việc cần làm trong wp-admin — suachuacuasat.com

Lập 26/09/2026 sau khi đo trực tiếp trên site thật (32 trang, bề rộng 390px và 1366px: gỡ thử từng
đoạn code trong trình duyệt rồi so sánh). Dành cho chủ site tự làm, hoặc cho một phiên Claude có
quyền quản trị (xem mục cuối). Thư mục `docs/` không cần tải lên host.

## Thứ tự nên làm

1. Cập nhật theme bản tối ưu mobile (mục A) — bản mới đã có sẵn phần CSS thay cho snippet `pm-page-css`.
2. Gỡ các đoạn code cũ (mục B) — việc số 2.
3. Sửa robots.txt và sitemap (mục C) — việc số 4.
4. Trang test và thẻ meta trùng (mục D) — việc số 5.
5. Xoá cache SpeedyCache rồi kiểm tra (mục E).

## A. Cập nhật theme

- Thư mục trên host: `wp-content/themes/prometal-0.3.1-landing-fix/`. Giữ nguyên tên thư mục
  (đổi tên sẽ mất menu và cài đặt Customizer).
- Chép đè đúng các file đã đổi trong nhánh `claude/cool-meitner-nwxa9y`:
  `git diff --name-only aeacd0b..HEAD -- . ':!docs' ':!README.md'`
- KHÔNG chép đè `template-landing.php`, `template-parts/landing/*`, `inc/fields.php`,
  `inc/enqueue.php`, `assets/js/lp-hero.js`: bản trên host mới hơn repo.
- `functions.php` chỉ đổi 1 dòng: `define( 'PROMETAL_VERSION', '0.3.1' );` → `'0.4.0'`. Sửa đúng
  dòng đó trên host thay vì chép đè cả file (phòng khi file trên host có thêm code).

## B. Gỡ đoạn code cũ thời Pagelayer (việc số 2)

Các đoạn này KHÔNG nằm trong theme. Site có plugin **Code Snippets**: vào **Snippets**, mở từng
snippet và tìm theo mã trong bảng (Ctrl+F trong ô code).

| Mã để tìm | Đang làm gì (đo trên site thật) | Khi nào gỡ |
|---|---|---|
| `pm-header-fix` | CSS cho header/menu Pagelayer — không còn phần tử nào khớp | Ngay |
| `pm-home-fixes` | CSS trang chủ Pagelayer — không khớp gì | Ngay |
| `pm-hdr-addr` | CSS địa chỉ header Pagelayer — không khớp gì | Ngay |
| `pm-no-sticky` | JS gỡ "sticky" của Pagelayer, chạy 5 lần mỗi trang — không có gì để gỡ | Ngay |
| `pm-single-style` | Desktop: khung bài viết lệch phải (lề trái 112px, lề phải 14px), mất lề trong | Ngay |
| `.pm-container{max-width:1200px` (in cuối trang bài viết/chuyên mục, kèm script `body > .pagelayer-content`) | Xoá khoảng đệm của header, thân bài, chân trang | Ngay |
| `h1.pagelayer-post-title` | Tiêu đề "Bài viết liên quan" ở sidebar thành font Times 23px, dư lề 36px | Ngay |
| `pm-page-css` | Ép font Times cho ~15 trang dùng mẫu trang mặc định, thêm lề 6px quanh mọi nút; định dạng khối số liệu / danh sách ✓ / hộp gọi của trang Giới thiệu | **Sau mục A** |

Cách gỡ an toàn:

- Snippet chỉ chứa các đoạn trong bảng → **Deactivate** (chưa xoá; site ổn 1–2 ngày rồi mới xoá).
- Snippet chứa lẫn phần cần GIỮ → chỉ xoá khối `<style>…</style>` / `<script>…</script>` cũ rồi lưu.
  Cần GIỮ: `prometal-ga-events` (đếm lượt bấm Gọi/Zalo cho Google Analytics) và mọi
  `<script type="application/ld+json">` (dữ liệu có cấu trúc HomeAndConstructionBusiness,
  BreadcrumbList, Article — giúp Google hiểu doanh nghiệp). Trong HTML, `h1.pagelayer-post-title`
  nằm liền trước schema HomeAndConstructionBusiness, `pm-header-fix` liền trước BreadcrumbList
  → rất có thể chung một snippet.
- Chưa làm mục A thì đừng gỡ `pm-page-css`: trang Giới thiệu sẽ mất khung số liệu, dấu ✓, hộp gọi.

## C. Sitemap và robots.txt (việc số 4)

Hiện trạng:

- Sitemap thật của SiteSEO là **`/sitemaps.xml`** (có chữ "s") và đang chạy tốt: 4 bài viết,
  26 trang, 3 chuyên mục, 20 thẻ.
- `/sitemap.xml` là **file tĩnh cũ** (sửa lần cuối 04/12/2025) chỉ chứa `//suachuacuasat.com` → hỏng.
- `robots.txt` cũng là file tĩnh (18/08/2026) và đang trỏ vào file hỏng đó.

Cách sửa (File Manager của hosting, thư mục gốc website — cùng chỗ với `wp-config.php`):

1. Sửa `robots.txt`: dòng `Sitemap: https://suachuacuasat.com/sitemap.xml` →
   `Sitemap: https://suachuacuasat.com/sitemaps.xml` (sửa luôn dòng chú thích ngay phía trên).
2. Đổi tên `sitemap.xml` → `sitemap.xml.bak` (hoặc xoá). Khi đó WordPress tự chuyển `/sitemap.xml`
   (301) sang `/wp-sitemap.xml` — sitemap hợp lệ, hết lỗi.
3. Google Search Console → Sơ đồ trang web: gửi `sitemaps.xml`; xoá mục `sitemap.xml` cũ nếu có.
4. (Nên) SiteSEO: bỏ khỏi sitemap / đặt noindex các thẻ tiếng Anh không liên quan (`carpentry`,
   `contractor`, `plumber`, `plumbing`, `renovation`, `repair`, `welding`) và chuyên mục `uncategorized`.

## D. Trang test và thẻ meta trùng (việc số 5)

- **Trang → "test-bot-chatgpt" (ID 581) → Bỏ vào thùng rác.** Chính trang ghi "có thể xoá page này
  sau khi xác nhận test thành công"; hiện nó vẫn nằm trong sitemap nên Google có thể lập chỉ mục.
- **Thẻ meta description in 2 lần — chỉ ở trang chủ**, nội dung giống hệt nhau. Thẻ thứ 2 (có dấu
  cách trước `>`) là của SiteSEO. Thẻ thứ 1 in ngay sau thẻ `robots`, từ một snippet khác: tìm trong
  Snippets chữ `name="description"` → xoá/tắt riêng phần đó (SiteSEO đã in đúng nội dung này).
- (Nên) SiteSEO → Social → Knowledge Graph: điền tên doanh nghiệp và logo — schema Organization
  của SiteSEO đang để trống `name` và `logo`.

## E. Sau cùng

- SpeedyCache → **Delete Cache** (xoá toàn bộ).
- (Nên) SpeedyCache → Settings: tắt **Minify HTML** — nguyên nhân lỗi chữ "nh�" (theme mới đã tự
  chặn lỗi này, tắt hẳn vẫn an toàn hơn).
- Xem lại trên điện thoại: trang chủ, 1 bài viết, 1 chuyên mục, Giới thiệu, Liên hệ, 1 landing.

## Dành cho phiên Claude có quyền quản trị

- Xác thực REST bằng Application Password đọc từ biến môi trường `WP_USER` và `WP_APP_PASSWORD`
  (chủ site đặt trong cài đặt môi trường — không dán vào chat).
- Snippets: `GET /wp-json/code-snippets/v1/snippets?per_page=100` (lọc theo trường `code`); sao lưu
  bằng `GET …/snippets/{id}/export` trước khi đổi; tắt bằng `POST …/snippets/{id}/deactivate`;
  sửa bằng `POST …/snippets/{id}` với trường `code`.
- Trang test: `DELETE /wp-json/wp/v2/pages/581` (vào thùng rác, khôi phục được).
- REST không sửa được file tĩnh `robots.txt` / `sitemap.xml` → nhờ chủ site làm mục C.
- Site có các route `prometal/v1/deploy-files`, `prometal/v1/purge`, `prometal/v1/lp-set` không có
  trong repo: đọc code của chúng trước khi dùng.
- Kiểm chứng khi xong:

```sh
S=https://suachuacuasat.com
curl -s $S/robots.txt | grep -i '^sitemap'                                    # → …/sitemaps.xml
curl -s -o /dev/null -w '%{http_code} %{redirect_url}\n' $S/sitemap.xml         # → 301 …/wp-sitemap.xml
curl -s "$S/?nc=$RANDOM" | grep -o 'name="description"' | wc -l                # → 1
curl -s "$S/dau-hieu-can-thay-banh-xe-cua-keo/?nc=$RANDOM" \
  | grep -oE 'pm-header-fix|pm-single-style|pm-home-fixes|pm-hdr-addr|pm-no-sticky|pm-page-css|pagelayer-post-title|max-width:1200px;margin:0 auto' | wc -l  # → 0
curl -s -o /dev/null -w '%{http_code}\n' $S/test-bot-chatgpt/                   # → 404
```
