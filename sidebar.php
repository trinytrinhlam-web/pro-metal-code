<?php
/**
 * Sidebar cho News detail — gom các card bên lề bài viết:
 *  1) Tư vấn nhanh + Dịch vụ nổi bật (sidebar-cards)
 *  2) Mục lục dính (toc — auto-sinh bởi toc.js)
 *  3) Bài viết liên quan (related)
 * Gọi bởi single.php trong Loop (global $post đã sẵn).
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<aside class="pm-news__sidebar" aria-label="<?php esc_attr_e( 'Thông tin bên lề bài viết', 'prometal' ); ?>">
	<?php
	get_template_part( 'template-parts/news/sidebar-cards' );
	get_template_part( 'template-parts/news/toc' );
	get_template_part( 'template-parts/news/related' );
	?>
</aside>
