<?php
/**
 * Section 5 — Thiết kế · Thi công · Sửa chữa CỬA INOX.
 *
 * Nền xanh chủ đạo (brand), 3 ô sản phẩm inox. Ảnh minh hoạ là ảnh thật bundle
 * trong theme (assets/img/inox-*.jpg).
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_inox_tiles = array(
	array(
		'title' => __( 'CỬA INOX', 'prometal' ),
		'url'   => home_url( '/dich-vu/inox/' ),
		'media' => __( 'Ảnh cửa inox', 'prometal' ),
		'img'   => 'assets/img/inox-cua.jpg',
	),
	array(
		'title' => __( 'CẦU THANG INOX', 'prometal' ),
		'url'   => home_url( '/dich-vu/inox/' ),
		'media' => __( 'Ảnh cầu thang inox', 'prometal' ),
		'img'   => 'assets/img/inox-cauthang.jpg',
	),
	array(
		'title' => __( 'CÔNG TRÌNH INOX KHÁC', 'prometal' ),
		'url'   => home_url( '/dich-vu/inox/' ),
		'media' => __( 'Ảnh công trình inox', 'prometal' ),
		'img'   => 'assets/img/inox-khac.jpg',
	),
);
?>
<section class="pm-section pm-inox">
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--light">
			<h2><?php esc_html_e( 'Thiết kế – Thi công – Sửa chữa cửa inox', 'prometal' ); ?></h2>
			<p class="pm-h2wrap__lead"><?php esc_html_e( 'Cửa inox, lan can inox, cầu thang inox… giá trị cao với chi phí tối ưu, đa dạng mẫu mã.', 'prometal' ); ?></p>
		</div>

		<div class="pm-cards pm-cards--3 pm-inox__grid">
			<?php foreach ( $pm_inox_tiles as $pm_t ) : ?>
				<a class="pm-itile" href="<?php echo esc_url( $pm_t['url'] ); ?>">
					<span class="pm-itile__media" aria-hidden="true">
						<img src="<?php echo esc_url( get_theme_file_uri( $pm_t['img'] ) ); ?>" alt="" loading="lazy">
					</span>
					<h3 class="pm-itile__title"><?php echo esc_html( $pm_t['title'] ); ?></h3>
				</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>
