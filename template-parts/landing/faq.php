<?php
/**
 * Landing block — Câu hỏi thường gặp (accordion .pm-faq của Session B, không cần JS).
 * Layout Carbon Fields: `faq`.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b       = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_id      = prometal_lp_section_id( 'faq' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Câu hỏi thường gặp về vách panel', 'prometal' ) );

$pm_items = prometal_lp_get( $pm_b, 'items', array() );
if ( empty( $pm_items ) ) {
	$pm_items = array(
		array(
			'question' => __( 'Thi công vách panel mất bao lâu?', 'prometal' ),
			'answer'   => '<p>' . esc_html__( 'Tùy diện tích, chiều cao, loại panel và điều kiện mặt bằng. Vách nhỏ, mặt bằng trống thường nhanh hơn; công trình có nhiều cửa, ô kính, trần hoặc đang vận hành cần thêm thời gian tổ chức.', 'prometal' ) . '</p>',
		),
		array(
			'question' => __( 'Panel có cách âm tốt không?', 'prometal' ),
			'answer'   => '<p>' . esc_html__( 'Panel có thể giảm ồn ở mức nhất định, nhưng hiệu quả phụ thuộc loại lõi, độ dày, khe nối, cửa đi và trần/sàn. Nếu anh chị cần cách âm cao, cần nói rõ mục tiêu để tư vấn phương án phù hợp.', 'prometal' ) . '</p>',
		),
		array(
			'question' => __( 'Panel có chống nóng không?', 'prometal' ),
			'answer'   => '<p>' . esc_html__( 'Có, nhất là các loại panel có lõi cách nhiệt phù hợp. Tuy nhiên mức hiệu quả phụ thuộc loại panel, độ dày, hướng nắng, mái/trần và cách xử lý khe hở.', 'prometal' ) . '</p>',
		),
		array(
			'question' => __( 'Có nhận diện tích nhỏ không?', 'prometal' ),
			'answer'   => '<p>' . esc_html__( 'Có thể nhận nếu sắp xếp được thợ và vật tư theo khu vực. Anh chị gửi kích thước và vị trí để chúng tôi phản hồi nhanh.', 'prometal' ) . '</p>',
		),
		array(
			'question' => __( 'Có làm kho lạnh hoặc phòng sạch không?', 'prometal' ),
			'answer'   => '<p>' . esc_html__( 'Với kho lạnh/phòng sạch có tiêu chuẩn kỹ thuật riêng, Pro-Metal cần xem yêu cầu, bản vẽ và thông số vận hành trước. Nếu phù hợp năng lực và phạm vi, chúng tôi sẽ báo phương án; nếu không phù hợp sẽ nói rõ để anh chị tránh mất thời gian.', 'prometal' ) . '</p>',
		),
		array(
			'question' => __( 'Có tháo dời, di chuyển vách panel cũ không?', 'prometal' ),
			'answer'   => '<p>' . esc_html__( 'Có thể khảo sát tháo dỡ hoặc di dời nếu hệ khung và panel còn sử dụng được. Thợ cần xem hiện trạng trước khi báo cách làm.', 'prometal' ) . '</p>',
		),
	);
}
?>
<section class="pm-section pm-section--alt pm-lp-faq-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--left"><h2><?php echo esc_html( $pm_heading ); ?></h2></div>

		<div class="pm-faq pm-lp-faq">
			<?php
			$pm_first = true;
			foreach ( $pm_items as $pm_it ) :
				$pm_q = prometal_lp_get( $pm_it, 'question', '' );
				$pm_a = prometal_lp_get( $pm_it, 'answer', '' );
				if ( '' === $pm_q ) {
					continue;
				}
				?>
				<details class="pm-faq__item"<?php echo $pm_first ? ' open' : ''; ?>>
					<summary class="pm-faq__q"><?php echo esc_html( $pm_q ); ?></summary>
					<div class="pm-faq__a"><?php echo wp_kses_post( wpautop( $pm_a ) ); ?></div>
				</details>
				<?php
				$pm_first = false;
			endforeach;
			?>
		</div>

	</div>
</section>
