<?php
/**
 * Section 4 — Thiết kế · Thi công · Sửa chữa CỬA SẮT.
 *
 * 3 thẻ dịch vụ dùng lại component .pm-card của Session B. Ảnh minh hoạ là ảnh
 * thật bundle trong theme (assets/img/sat-*.jpg); nội dung khớp demo đã duyệt.
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_sat_cards = array(
	array(
		'title' => __( 'CỬA SẮT', 'prometal' ),
		'text'  => __( 'Chuyên thiết kế, thi công Cửa Sắt, Cửa Cuốn, Cửa Cổng, Cửa Sổ. Mẫu mã hiện đại, vật liệu bền bỉ.', 'prometal' ),
		'url'   => pm_service_url( 'lam-cua-sat' ),
		'media' => __( 'Ảnh cửa sắt', 'prometal' ),
		'img'   => 'assets/img/sat-cua.jpg',
	),
	array(
		'title' => __( 'NHÀ XƯỞNG – CÔNG TY', 'prometal' ),
		'text'  => __( 'Thiết kế, thi công & sửa chữa nhà xưởng, văn phòng, công ty. Tối ưu không gian, đúng tiến độ.', 'prometal' ),
		'url'   => pm_service_url( 'nha-xuong' ),
		'media' => __( 'Ảnh nhà xưởng', 'prometal' ),
		'img'   => 'assets/img/sat-nhaxuong.jpg',
	),
	array(
		'title' => __( 'CÔNG TRÌNH SẮT KHÁC', 'prometal' ),
		'text'  => __( 'Lan can, cầu thang, mái hiên, mái che. Sản phẩm bền đẹp, an toàn và thẩm mỹ.', 'prometal' ),
		'url'   => pm_service_url( 'cau-thang' ),
		'media' => __( 'Ảnh lan can', 'prometal' ),
		'img'   => 'assets/img/sat-khac.jpg',
	),
);
?>
<section id="pm-dich-vu-sat" class="pm-section pm-section--alt pm-services-sat">
	<div class="pm-container">

		<div class="pm-h2wrap">
			<h2><?php esc_html_e( 'Thiết kế – Thi công – Sửa chữa cửa sắt', 'prometal' ); ?></h2>
			<p class="pm-h2wrap__lead"><?php esc_html_e( 'Pro-Metal thiết kế – thi công các mẫu cửa sắt, cửa cổng, lan can, cầu thang… đẹp, hiện đại theo yêu cầu.', 'prometal' ); ?></p>
		</div>

		<div class="pm-cards pm-cards--3">
			<?php foreach ( $pm_sat_cards as $pm_c ) : ?>
				<article class="pm-card pm-card--link">
					<a class="pm-card__media" href="<?php echo esc_url( $pm_c['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php
						echo pm_theme_img( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- đã escape trong helper.
							$pm_c['img'],
							array(
								'alt'    => $pm_c['media'],
								'widths' => array( 480, 800 ),
								'sizes'  => '(max-width: 640px) calc(100vw - 32px), (max-width: 980px) calc(50vw - 28px), 362px',
							)
						);
						?>
					</a>
					<div class="pm-card__body">
						<h3 class="pm-card__title"><a href="<?php echo esc_url( $pm_c['url'] ); ?>"><?php echo esc_html( $pm_c['title'] ); ?></a></h3>
						<p class="pm-card__text"><?php echo esc_html( $pm_c['text'] ); ?></p>
						<a class="pm-link-more" href="<?php echo esc_url( $pm_c['url'] ); ?>">
							<?php esc_html_e( 'Xem chi tiết', 'prometal' ); ?>
							<?php echo pm_icon( 'chevron-right', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
