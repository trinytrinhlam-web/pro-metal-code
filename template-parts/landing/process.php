<?php
/**
 * Landing block — Quy trình thi công (`process`) HOẶC Cam kết (`commitment`).
 * Cùng file: nhánh theo $args['_type']. Quy trình rỗng → ảnh minh hoạ 5 bước
 * thật bundle trong theme (assets/img/lp-process.jpg).
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b    = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_type = isset( $pm_b['_type'] ) ? $pm_b['_type'] : 'process';

/* ------------------------------------------------------------------ *
 *  B) Cam kết
 * ------------------------------------------------------------------ */
if ( 'commitment' === $pm_type ) :
	$pm_id      = prometal_lp_section_id( 'commitment' );
	$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Cam kết khi nhận công trình panel', 'prometal' ) );

	$pm_items = prometal_lp_get( $pm_b, 'items', array() );
	if ( empty( $pm_items ) ) {
		$pm_items = array(
			array(
				'title' => __( 'Tư vấn đúng nhu cầu', 'prometal' ),
				'text'  => __( 'Không đẩy loại panel vượt yêu cầu sử dụng chỉ để tăng chi phí.', 'prometal' ),
			),
			array(
				'title' => __( 'Báo rõ vật tư', 'prometal' ),
				'text'  => __( 'Loại panel, khung, nẹp, phụ kiện và phần phát sinh được nói rõ trước.', 'prometal' ),
			),
			array(
				'title' => __( 'Thi công gọn', 'prometal' ),
				'text'  => __( 'Hạn chế ảnh hưởng khu vực đang vận hành, dọn gọn khi bàn giao.', 'prometal' ),
			),
			array(
				'title' => __( 'Phát sinh phải hỏi trước', 'prometal' ),
				'text'  => __( 'Không tự làm thêm rồi tính thêm khi chưa thống nhất với anh chị.', 'prometal' ),
			),
		);
	}

	$pm_tiles = prometal_lp_get( $pm_b, 'tiles', array() );
	if ( empty( $pm_tiles ) ) {
		$pm_tiles = array(
			array( 'label' => __( 'Lắp nhanh', 'prometal' ) ),
			array( 'label' => __( 'Cách nhiệt', 'prometal' ) ),
			array( 'label' => __( 'Gọn sạch', 'prometal' ) ),
			array( 'label' => __( 'Dễ điều chỉnh', 'prometal' ) ),
		);
	}
	?>
	<section class="pm-section pm-section--alt pm-lp-commit-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
		<div class="pm-container pm-lp-commit">

			<div class="pm-lp-commit__main">
				<div class="pm-h2wrap pm-h2wrap--left"><h2><?php echo esc_html( $pm_heading ); ?></h2></div>
				<div class="pm-lp-why">
					<?php foreach ( $pm_items as $pm_it ) : ?>
						<?php
						$pm_t  = prometal_lp_get( $pm_it, 'title', '' );
						$pm_tx = prometal_lp_get( $pm_it, 'text', '' );
						if ( '' === $pm_t && '' === $pm_tx ) {
							continue;
						}
						?>
						<div class="pm-lp-why__item">
							<span class="pm-lp-why__ic"><?php echo pm_icon( 'check', array( 'width' => 24, 'height' => 24 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<div>
								<h3><?php echo esc_html( $pm_t ); ?></h3>
								<p><?php echo esc_html( $pm_tx ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( ! empty( $pm_tiles ) ) : ?>
				<div class="pm-lp-tiles" aria-label="<?php esc_attr_e( 'Lợi ích vách ngăn panel', 'prometal' ); ?>">
					<?php foreach ( $pm_tiles as $pm_ti ) : ?>
						<?php
						$pm_lab  = prometal_lp_get( $pm_ti, 'label', '' );
						$pm_ic   = prometal_lp_get( $pm_ti, 'icon', '' );
						if ( '' === $pm_lab ) {
							continue;
						}
						$pm_icimg = prometal_lp_img( $pm_ic, $pm_lab, 'pm-lp-tile__img', 'pm-thumb' );
						?>
						<div class="pm-lp-tile">
							<span class="pm-lp-tile__ic">
								<?php
								if ( '' !== $pm_icimg ) {
									echo $pm_icimg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								} else {
									echo pm_icon( 'check', array( 'width' => 34, 'height' => 34 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								}
								?>
							</span>
							<b><?php echo esc_html( $pm_lab ); ?></b>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>
	</section>
	<?php
	return;
endif;

/* ------------------------------------------------------------------ *
 *  A) Quy trình thi công
 * ------------------------------------------------------------------ */
$pm_id      = prometal_lp_section_id( 'process' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Quy trình thi công vách panel', 'prometal' ) );
$pm_lead    = prometal_lp_get( $pm_b, 'lead', __( 'Quy trình được giữ rõ từ đầu để công trình B2B không bị mập mờ khối lượng, vật tư và thời gian bàn giao.', 'prometal' ) );
$pm_img     = prometal_lp_get( $pm_b, 'image', '' );

$pm_steps = prometal_lp_get( $pm_b, 'steps', array() );
if ( empty( $pm_steps ) ) {
	$pm_steps = array(
		array(
			'title' => __( 'Nhận bản vẽ', 'prometal' ),
			'text'  => __( 'Gửi mặt bằng, ảnh hiện trạng, chiều dài/cao vách và nhu cầu sử dụng.', 'prometal' ),
		),
		array(
			'title' => __( 'Khảo sát', 'prometal' ),
			'text'  => __( 'Xem nền, tường, trần, hướng lắp, vị trí cửa/điện và đường vận chuyển.', 'prometal' ),
		),
		array(
			'title' => __( 'Báo giá', 'prometal' ),
			'text'  => __( 'Tách phần panel, khung, phụ kiện, cửa/ô kính, thời gian thi công.', 'prometal' ),
		),
		array(
			'title' => __( 'Chuẩn bị vật tư', 'prometal' ),
			'text'  => __( 'Chuẩn bị panel, khung, nẹp, vít, keo và phương án đưa vật tư vào công trình.', 'prometal' ),
		),
		array(
			'title' => __( 'Lắp đặt', 'prometal' ),
			'text'  => __( 'Cố định khung, dựng tấm, xử lý khe nối, vệ sinh và nghiệm thu.', 'prometal' ),
		),
	);
}
?>
<section class="pm-section pm-lp-process-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--left">
			<h2><?php echo esc_html( $pm_heading ); ?></h2>
			<?php if ( '' !== $pm_lead ) : ?>
				<p class="pm-h2wrap__lead"><?php echo esc_html( $pm_lead ); ?></p>
			<?php endif; ?>
		</div>

		<div class="pm-lp-process__media">
			<?php
			$pm_pimg = prometal_lp_img( $pm_img, $pm_heading, 'pm-lp-process__img', 'pm-hero' );
			if ( '' !== $pm_pimg ) {
				echo $pm_pimg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<img src="' . esc_url( get_theme_file_uri( 'assets/img/lp-process.jpg' ) ) . '" alt="' . esc_attr( $pm_heading ) . '" class="pm-lp-process__img" loading="lazy">';
			}
			?>
		</div>

		<ol class="pm-lp-steps">
			<?php foreach ( $pm_steps as $pm_st ) : ?>
				<?php
				$pm_t  = prometal_lp_get( $pm_st, 'title', '' );
				$pm_tx = prometal_lp_get( $pm_st, 'text', '' );
				if ( '' === $pm_t && '' === $pm_tx ) {
					continue;
				}
				?>
				<li class="pm-lp-step">
					<h3><?php echo esc_html( $pm_t ); ?></h3>
					<p><?php echo esc_html( $pm_tx ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>

	</div>
</section>
