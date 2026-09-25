<?php
/**
 * Section 6 — Giải pháp nhôm kính toàn diện.
 *
 * Bố cục 2 cột: ảnh thật (assets/img/nhom-kinh.jpg) + nội dung có danh sách
 * tick cam. Nội dung khớp demo.
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_alu_checks = array(
	array(
		'label' => __( 'Hệ thống cửa nhôm:', 'prometal' ),
		'text'  => __( 'cửa chính (2/4 cánh), cửa sổ, cửa lùa hiện đại, tối ưu ánh sáng.', 'prometal' ),
	),
	array(
		'label' => __( 'Kết cấu an toàn:', 'prometal' ),
		'text'  => __( 'lan can nhôm, cầu thang và hệ khung bảo vệ chắc chắn, tinh tế.', 'prometal' ),
	),
	array(
		'label' => __( 'Giải pháp văn phòng:', 'prometal' ),
		'text'  => __( 'vách ngăn nhôm kính chia không gian chuyên nghiệp, cách âm.', 'prometal' ),
	),
);

?>
<section class="pm-section pm-aluminum">
	<div class="pm-container pm-aluminum__grid">

		<?php // <img> lazy thay cho background-image inline (ảnh nền tải ngay cả khi ở cuối trang, tranh băng thông với ảnh hero trên mobile). ?>
		<div class="pm-aluminum__media">
			<?php
			echo pm_theme_img( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- đã escape trong helper.
				'assets/img/nhom-kinh.jpg',
				array(
					'alt'    => __( 'Hệ cửa nhôm kính và vách ngăn nhôm kính Pro-Metal', 'prometal' ),
					'widths' => array( 480, 800, 1100 ),
					'sizes'  => '(max-width: 720px) calc(100vw - 32px), 546px',
				)
			);
			?>
		</div>

		<div class="pm-aluminum__text">
			<h2 class="pm-aluminum__title"><?php esc_html_e( 'Giải pháp nhôm kính toàn diện', 'prometal' ); ?></h2>
			<p class="pm-aluminum__desc">
				<?php
				printf(
					/* translators: %s: nhóm dịch vụ được in đậm. */
					esc_html__( 'Pro-Metal tự hào là đơn vị chuyên %s các hạng mục nhôm cao cấp cho nhà ở dân dụng và văn phòng.', 'prometal' ),
					'<strong>' . esc_html__( 'Thiết kế – Thi công – Sửa chữa', 'prometal' ) . '</strong>'
				);
				?>
			</p>
			<ul class="pm-checks">
				<?php foreach ( $pm_alu_checks as $pm_ck ) : ?>
					<li class="pm-checks__item">
						<?php echo pm_icon( 'check', array( 'width' => 22, 'height' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><strong><?php echo esc_html( $pm_ck['label'] ); ?></strong> <?php echo esc_html( $pm_ck['text'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
			<a class="pm-btn pm-btn--call" href="<?php echo esc_url( pm_service_url( 'nhom-kinh' ) ); ?>">
				<?php esc_html_e( 'Xem chi tiết', 'prometal' ); ?>
			</a>
		</div>

	</div>
</section>
