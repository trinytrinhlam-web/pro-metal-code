<?php
/**
 * Landing block — Đoạn mở đầu (`intro`) HOẶC Thẻ ứng dụng (`cards`).
 * Cùng file: nhánh theo $args['_type']. Thẻ dùng .pm-card của Session B,
 * nền SÁNG/SẠCH (không nền tối tech, không hiệu ứng bám chuột).
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b    = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_type = isset( $pm_b['_type'] ) ? $pm_b['_type'] : 'cards';

/* ------------------------------------------------------------------ *
 *  A) Đoạn mở đầu
 * ------------------------------------------------------------------ */
if ( 'intro' === $pm_type ) :
	$pm_content = prometal_lp_get(
		$pm_b,
		'content',
		'<p>' . esc_html__( 'Vách panel phù hợp với những công trình cần chia không gian nhanh nhưng vẫn muốn sạch, nhẹ và dễ kiểm soát tiến độ. Thay vì xây tường gạch mất nhiều thời gian, nhiều bụi và khó thay đổi về sau, vách panel có thể lắp theo khung, ghép tấm, xử lý khe nối và đưa vào sử dụng nhanh hơn.', 'prometal' ) . '</p>'
		. '<p>' . esc_html__( 'Tuy vậy, thi công panel không chỉ là dựng vài tấm vách lên. Công trình nhà xưởng cần tính độ cao, khung xương và độ ổn định. Văn phòng trong xưởng cần nhìn gọn, kín, đủ sáng. Phòng trọ cần tối ưu chi phí và dễ vệ sinh. Kho mát hoặc khu phụ trợ lại cần chú ý khả năng cách nhiệt và cách xử lý khe nối.', 'prometal' ) . '</p>'
		. '<p>' . esc_html__( 'Pro-Metal đi theo hướng tư vấn trước khi thi công: xem mục đích sử dụng, đo kích thước, kiểm tra nền/tường/trần hiện trạng, rồi mới đề xuất loại panel, độ dày, khung phụ và cách hoàn thiện.', 'prometal' ) . '</p>'
	);
	?>
	<section class="pm-section pm-lp-intro-sec">
		<div class="pm-container">
			<div class="pm-lp-intro pm-prose">
				<?php echo wp_kses_post( $pm_content ); ?>
			</div>
		</div>
	</section>
	<?php
	return;
endif;

/* ------------------------------------------------------------------ *
 *  B) Thẻ ứng dụng
 * ------------------------------------------------------------------ */
$pm_id      = prometal_lp_section_id( 'cards' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Vách panel phù hợp công trình nào?', 'prometal' ) );
$pm_lead    = prometal_lp_get( $pm_b, 'lead', __( 'Mỗi mặt bằng cần một cách làm khác nhau. Chúng tôi ưu tiên tư vấn theo mục đích sử dụng để không chọn vật tư quá thiếu hoặc quá dư.', 'prometal' ) );

$pm_cards = prometal_lp_get( $pm_b, 'cards', array() );
if ( empty( $pm_cards ) ) {
	$pm_cards = array(
		array(
			'title' => __( 'Vách panel nhà xưởng', 'prometal' ),
			'text'  => __( 'Chia khu làm việc, kho phụ, phòng kỹ thuật hoặc khu cần ngăn bụi/giảm nóng tương đối. Điểm quan trọng là khung xương chắc, chân vách neo ổn và chiều cao phù hợp.', 'prometal' ),
		),
		array(
			'title' => __( 'Văn phòng trong xưởng', 'prometal' ),
			'text'  => __( 'Làm phòng quản lý, phòng họp nhỏ, khu admin hoặc phòng nghỉ. Có thể kết hợp cửa đi, ô kính và trần panel để tách khu rõ hơn.', 'prometal' ),
		),
		array(
			'title' => __( 'Phòng trọ, nhà cho thuê', 'prometal' ),
			'text'  => __( 'Phù hợp chia phòng nhanh, giảm tải trọng lên sàn và hạn chế thi công ướt. Cần tư vấn kỹ khe hở, điện và nhu cầu riêng tư.', 'prometal' ),
		),
		array(
			'title' => __( 'Kho mát, khu phụ trợ', 'prometal' ),
			'text'  => __( 'Panel giúp hạn chế nóng và ổn định không gian hơn vách thường. Nếu là kho lạnh/phòng sạch tiêu chuẩn cao, cần xem yêu cầu kỹ thuật cụ thể.', 'prometal' ),
		),
		array(
			'title' => __( 'Cửa hàng, kho nhỏ', 'prometal' ),
			'text'  => __( 'Ngăn kho hàng, khu nhân viên hoặc khu thao tác phía sau cửa hàng. Thi công gọn giúp mặt bằng kinh doanh sớm vận hành lại.', 'prometal' ),
		),
		array(
			'title' => __( 'Trần panel chống nóng', 'prometal' ),
			'text'  => __( 'Một số công trình cần kết hợp trần panel để giảm nóng và làm mặt bằng gọn hơn. Cần xem cao độ, khung treo và hệ thống đèn/quạt.', 'prometal' ),
		),
	);
}
?>
<section class="pm-section pm-section--alt pm-lp-cards-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--left">
			<h2><?php echo esc_html( $pm_heading ); ?></h2>
			<?php if ( '' !== $pm_lead ) : ?>
				<p class="pm-h2wrap__lead"><?php echo esc_html( $pm_lead ); ?></p>
			<?php endif; ?>
		</div>

		<div class="pm-cards pm-cards--3">
			<?php foreach ( $pm_cards as $pm_c ) : ?>
				<?php
				$pm_title = prometal_lp_get( $pm_c, 'title', '' );
				$pm_text  = prometal_lp_get( $pm_c, 'text', '' );
				$pm_icon  = prometal_lp_get( $pm_c, 'icon', '' );
				if ( '' === $pm_title && '' === $pm_text ) {
					continue;
				}
				$pm_icon_img = prometal_lp_img( $pm_icon, $pm_title, 'pm-lp-card__icon-img', 'pm-thumb' );
				?>
				<article class="pm-card pm-card--pad pm-lp-card">
					<div class="pm-card__icon">
						<?php
						if ( '' !== $pm_icon_img ) {
							echo $pm_icon_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						} else {
							echo pm_icon( 'check', array( 'width' => 30, 'height' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						}
						?>
					</div>
					<h3 class="pm-card__title"><?php echo esc_html( $pm_title ); ?></h3>
					<p class="pm-card__text"><?php echo esc_html( $pm_text ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
