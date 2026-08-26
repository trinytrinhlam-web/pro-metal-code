<?php
/**
 * News — mục lục trong bài. Dùng .pm-toc--sticky của Session B:
 * để RỖNG → toc.js tự sinh mục lục từ các <h2> trong .pm-news__body + scroll-spy.
 * Không có <h2> → toc.js tự ẩn (toc.hidden). Không JS → CSS :empty ẩn.
 * Nav để trên một dòng, KHÔNG khoảng trắng bên trong (giữ :empty hợp lệ).
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="pm-toc pm-toc--sticky pm-news__toc" data-toc-content=".pm-news__body" data-toc-headings="h2" data-toc-label="<?php esc_attr_e( 'Nội dung bài viết', 'prometal' ); ?>" aria-label="<?php esc_attr_e( 'Mục lục bài viết', 'prometal' ); ?>"></nav>
