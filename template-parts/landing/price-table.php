<?php
/**
 * Landing block — Bảng loại panel (`price_table`) HOẶC Yếu tố chi phí (`cost_factors`).
 * Cùng file: nhánh theo $args['_type']. Bảng dùng .pm-table (Session B).
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b    = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_type = isset( $pm_b['_type'] ) ? $pm_b['_type'] : 'price_table';

/* ------------------------------------------------------------------ *
 *  B) Yếu tố chi phí
 * ------------------------------------------------------------------ */
if ( 'cost_factors' === $pm_type ) :
	$pm_id      = prometal_lp_section_id( 'cost_factors' );
	$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Chi phí thi công vách panel phụ thuộc điều gì?', 'prometal' ) );
	$pm_lead    = prometal_lp_get( $pm_b, 'lead', __( 'Pro-Metal không ghi số tiền cố định vì chi phí thay đổi theo vật tư, mặt bằng và yêu cầu hoàn thiện. Cách báo đúng là bóc tách từ bản vẽ hoặc kích thước thực tế.', 'prometal' ) );

	$pm_items = prometal_lp_get( $pm_b, 'items', array() );
	if ( empty( $pm_items ) ) {
		$pm_items = array(
			array(
				'title' => __( 'Loại panel và độ dày', 'prometal' ),
				'text'  => __( 'EPS, PU, Glasswool/Rockwool có tính năng và mức chi phí khác nhau. Độ dày panel ảnh hưởng đến độ cứng, khả năng cách nhiệt và giá vật tư.', 'prometal' ),
			),
			array(
				'title' => __( 'Diện tích và chiều cao', 'prometal' ),
				'text'  => __( 'Diện tích càng lớn thì khối lượng tấm và công lắp càng nhiều. Vách cao cần tính thêm khung, điểm neo, độ rung và vận chuyển tấm vào công trình.', 'prometal' ),
			),
			array(
				'title' => __( 'Khung xương hiện trạng', 'prometal' ),
				'text'  => __( 'Nền/tường phẳng, chắc sẽ thi công nhanh hơn. Mặt bằng yếu, lệch, nhiều vật cản hoặc cần gia cố sẽ tăng công xử lý.', 'prometal' ),
			),
			array(
				'title' => __( 'Cửa, ô kính, điện', 'prometal' ),
				'text'  => __( 'Vách đi kèm cửa đi, ô kính, dây điện, đèn/quạt hoặc lỗ kỹ thuật cần bóc tách riêng để báo đúng.', 'prometal' ),
			),
			array(
				'title' => __( 'Vị trí thi công', 'prometal' ),
				'text'  => __( 'Tầng cao, hẻm nhỏ, khu đang vận hành hoặc cần làm ngoài giờ sẽ có cách tổ chức thi công khác công trình trống mặt bằng.', 'prometal' ),
			),
			array(
				'title' => __( 'Mức hoàn thiện', 'prometal' ),
				'text'  => __( 'Nẹp góc, keo xử lý khe, vệ sinh bàn giao và yêu cầu thẩm mỹ ảnh hưởng đến thời gian thi công và chi phí sau cùng.', 'prometal' ),
			),
		);
	}
	?>
	<section class="pm-section pm-section--alt pm-lp-cost-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
		<div class="pm-container">
			<div class="pm-h2wrap pm-h2wrap--left">
				<h2><?php echo esc_html( $pm_heading ); ?></h2>
				<?php if ( '' !== $pm_lead ) : ?>
					<p class="pm-h2wrap__lead"><?php echo esc_html( $pm_lead ); ?></p>
				<?php endif; ?>
			</div>
			<div class="pm-cards pm-cards--3">
				<?php foreach ( $pm_items as $pm_it ) : ?>
					<?php
					$pm_t  = prometal_lp_get( $pm_it, 'title', '' );
					$pm_tx = prometal_lp_get( $pm_it, 'text', '' );
					if ( '' === $pm_t && '' === $pm_tx ) {
						continue;
					}
					?>
					<article class="pm-card pm-card--pad pm-lp-cost">
						<h3 class="pm-card__title"><?php echo esc_html( $pm_t ); ?></h3>
						<p class="pm-card__text"><?php echo esc_html( $pm_tx ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return;
endif;

/* ------------------------------------------------------------------ *
 *  A) Bảng loại panel
 * ------------------------------------------------------------------ */
$pm_id      = prometal_lp_section_id( 'price_table' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Các loại panel thường dùng', 'prometal' ) );
$pm_lead    = prometal_lp_get( $pm_b, 'lead', __( 'Không có một loại panel tốt nhất cho mọi công trình. Loại phù hợp là loại đáp ứng đúng nhu cầu sử dụng, tiến độ và ngân sách.', 'prometal' ) );

$pm_lv_default = array(
	'l1' => __( 'Hợp lý', 'prometal' ),
	'l2' => __( 'Cao hơn', 'prometal' ),
	'l3' => __( 'Theo yêu cầu', 'prometal' ),
);

$pm_rows = prometal_lp_get( $pm_b, 'rows', array() );
if ( empty( $pm_rows ) ) {
	$pm_rows = array(
		array(
			'name'  => __( 'Panel EPS', 'prometal' ),
			'fit'   => __( 'Vách ngăn phòng, xưởng nhẹ, phòng trọ, khu phụ trợ', 'prometal' ),
			'note'  => __( 'Dễ dùng, thi công nhanh, phù hợp nhu cầu phổ thông không đòi hỏi cách nhiệt quá cao.', 'prometal' ),
			'level' => 'l1',
		),
		array(
			'name'  => __( 'Panel PU', 'prometal' ),
			'fit'   => __( 'Khu cần cách nhiệt tốt hơn, kho mát nhẹ, không gian cần ổn định nhiệt', 'prometal' ),
			'note'  => __( 'Hiệu quả cách nhiệt tốt hơn EPS, nên dùng khi nhu cầu thật sự cần để tránh đội chi phí.', 'prometal' ),
			'level' => 'l2',
		),
		array(
			'name'  => __( 'Panel Glasswool/Rockwool', 'prometal' ),
			'fit'   => __( 'Công trình quan tâm chống cháy, cách âm/cách nhiệt và yêu cầu kỹ thuật', 'prometal' ),
			'note'  => __( 'Cần xem tiêu chuẩn cụ thể, độ dày, hệ khung và phụ kiện đi kèm trước khi chốt.', 'prometal' ),
			'level' => 'l3',
		),
	);
}

$pm_cta_title = prometal_lp_get( $pm_b, 'cta_title', __( 'Không chắc nên dùng panel nào?', 'prometal' ) );
$pm_cta_text  = prometal_lp_get( $pm_b, 'cta_text', __( 'Gửi ảnh mặt bằng hoặc bản vẽ, chúng tôi xem nhu cầu rồi tư vấn loại phù hợp trước khi báo giá.', 'prometal' ) );
$pm_cta_label = prometal_lp_get( $pm_b, 'cta_label', __( 'Gửi qua Zalo', 'prometal' ) );
$pm_cta_link  = prometal_lp_get( $pm_b, 'cta_link', pm_zalo_link() );
?>
<section class="pm-section pm-lp-panel-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--left">
			<h2><?php echo esc_html( $pm_heading ); ?></h2>
			<?php if ( '' !== $pm_lead ) : ?>
				<p class="pm-h2wrap__lead"><?php echo esc_html( $pm_lead ); ?></p>
			<?php endif; ?>
		</div>

		<div class="pm-table-wrap">
			<table class="pm-table pm-lp-table">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Loại panel', 'prometal' ); ?></th>
						<th><?php esc_html_e( 'Phù hợp', 'prometal' ); ?></th>
						<th><?php esc_html_e( 'Ghi chú tư vấn', 'prometal' ); ?></th>
						<th><?php esc_html_e( 'Mức chi phí tương đối', 'prometal' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $pm_rows as $pm_r ) : ?>
						<?php
						$pm_name  = prometal_lp_get( $pm_r, 'name', '' );
						$pm_fit   = prometal_lp_get( $pm_r, 'fit', '' );
						$pm_note  = prometal_lp_get( $pm_r, 'note', '' );
						$pm_level = prometal_lp_get( $pm_r, 'level', 'l3' );
						$pm_level = in_array( $pm_level, array( 'l1', 'l2', 'l3' ), true ) ? $pm_level : 'l3';
						$pm_lvlab = prometal_lp_get( $pm_r, 'level_label', $pm_lv_default[ $pm_level ] );
						if ( '' === $pm_name ) {
							continue;
						}
						?>
						<tr>
							<td><b><?php echo esc_html( $pm_name ); ?></b></td>
							<td><?php echo esc_html( $pm_fit ); ?></td>
							<td><?php echo esc_html( $pm_note ); ?></td>
							<td><span class="pm-lp-lv pm-lp-lv--<?php echo esc_attr( $pm_level ); ?>"><?php echo esc_html( $pm_lvlab ); ?></span></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>

		<?php if ( '' !== $pm_cta_title || '' !== $pm_cta_label ) : ?>
			<div class="pm-lp-ctabox">
				<div class="pm-lp-ctabox__text">
					<?php if ( '' !== $pm_cta_title ) : ?>
						<h3><?php echo esc_html( $pm_cta_title ); ?></h3>
					<?php endif; ?>
					<?php if ( '' !== $pm_cta_text ) : ?>
						<p><?php echo esc_html( $pm_cta_text ); ?></p>
					<?php endif; ?>
				</div>
				<?php if ( '' !== $pm_cta_label ) : ?>
					<a class="pm-btn pm-btn--call pm-btn--lg" href="<?php echo esc_url( $pm_cta_link ); ?>" target="_blank" rel="nofollow noopener">
						<?php echo pm_icon( 'zalo', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php echo esc_html( $pm_cta_label ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
