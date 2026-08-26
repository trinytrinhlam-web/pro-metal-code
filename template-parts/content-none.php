<?php
/**
 * Trạng thái rỗng — không tìm thấy bài/nội dung. Kèm ô tìm kiếm.
 * Dùng bởi index.php / home.php / archive.php / search.php (Session A/E READ).
 *
 * @owner   Session B
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="pm-none">

	<?php if ( is_search() ) : ?>

		<h2 class="pm-none__title"><?php esc_html_e( 'Không có kết quả phù hợp', 'prometal' ); ?></h2>
		<p class="pm-none__text">
			<?php esc_html_e( 'Rất tiếc, không tìm thấy nội dung nào khớp với từ khoá của bạn. Hãy thử từ khoá khác.', 'prometal' ); ?>
		</p>
		<?php get_search_form(); ?>

	<?php elseif ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<h2 class="pm-none__title"><?php esc_html_e( 'Chưa có bài viết nào', 'prometal' ); ?></h2>
		<p class="pm-none__text">
			<?php
			printf(
				/* translators: %s: URL trang thêm bài viết mới. */
				wp_kses(
					__( 'Bắt đầu chia sẻ nội dung bằng cách <a href="%s">tạo bài viết đầu tiên</a>.', 'prometal' ),
					array( 'a' => array( 'href' => array() ) )
				),
				esc_url( admin_url( 'post-new.php' ) )
			);
			?>
		</p>

	<?php else : ?>

		<h2 class="pm-none__title"><?php esc_html_e( 'Chưa có nội dung', 'prometal' ); ?></h2>
		<p class="pm-none__text">
			<?php esc_html_e( 'Nội dung đang được cập nhật. Bạn có thể tìm kiếm hoặc quay lại trang chủ.', 'prometal' ); ?>
		</p>
		<?php get_search_form(); ?>
		<p class="pm-mt-0" style="margin-top:18px">
			<a class="pm-btn pm-btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Về trang chủ', 'prometal' ); ?></a>
		</p>

	<?php endif; ?>

</section>
