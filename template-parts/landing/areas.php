<?php
/**
 * Landing block — Khu vực nhận thi công + chi nhánh.
 * Layout Carbon Fields: `areas`. Pill dùng .pm-pill (Session B).
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b       = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_id      = prometal_lp_section_id( 'areas' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Khu vực nhận thi công vách panel tại TP.HCM', 'prometal' ) );
$pm_lead    = prometal_lp_get( $pm_b, 'lead', __( 'Pro-Metal nhận khảo sát tại nhiều khu vực TP.HCM. Với công trình ngoài TP.HCM, anh chị gửi vị trí để chúng tôi xem khả năng nhận thi công.', 'prometal' ) );

$pm_areas = prometal_lp_get( $pm_b, 'areas', array() );
if ( empty( $pm_areas ) ) {
	$pm_names = array( 'Bình Tân', 'Tân Bình', 'Quận 1', 'Quận 3', 'Quận 10', 'Quận 11', 'Tân Phú', 'Phú Nhuận', 'Bình Thạnh', 'Gò Vấp', 'Hóc Môn', 'Thủ Đức', 'Quận 12' );
	$pm_areas = array_map(
		function ( $n ) {
			return array( 'name' => $n );
		},
		$pm_names
	);
}

$pm_branches = prometal_lp_get( $pm_b, 'branches', array() );
if ( empty( $pm_branches ) ) {
	$pm_branches = array(
		array(
			'title'   => __( 'Trụ sở chính - Bình Tân', 'prometal' ),
			'address' => pm_option( 'address' ),
			'phone'   => pm_phone(),
			'hq'      => true,
		),
		array(
			'title'   => __( 'Chi nhánh Tân Bình', 'prometal' ),
			'address' => __( 'Lầu 4, 249 Cộng Hòa, P.13, Q. Tân Bình', 'prometal' ),
			'phone'   => pm_phone(),
			'hq'      => false,
		),
		array(
			'title'   => __( 'Chi nhánh Quận 3', 'prometal' ),
			'address' => __( '64 Vườn Chuối, Quận 3, TP.HCM', 'prometal' ),
			'phone'   => pm_phone(),
			'hq'      => false,
		),
	);
}
?>
<section class="pm-section pm-lp-areas-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--left">
			<h2><?php echo esc_html( $pm_heading ); ?></h2>
			<?php if ( '' !== $pm_lead ) : ?>
				<p class="pm-h2wrap__lead"><?php echo esc_html( $pm_lead ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $pm_areas ) ) : ?>
			<div class="pm-pill-group pm-lp-areas">
				<?php foreach ( $pm_areas as $pm_a ) : ?>
					<?php $pm_n = prometal_lp_get( $pm_a, 'name', '' ); ?>
					<?php if ( '' !== $pm_n ) : ?>
						<span class="pm-pill"><?php echo esc_html( $pm_n ); ?></span>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $pm_branches ) ) : ?>
			<div class="pm-lp-branches">
				<?php foreach ( $pm_branches as $pm_br ) : ?>
					<?php
					$pm_t    = prometal_lp_get( $pm_br, 'title', '' );
					$pm_addr = prometal_lp_get( $pm_br, 'address', '' );
					$pm_ph   = prometal_lp_get( $pm_br, 'phone', pm_phone() );
					$pm_hq   = ! empty( $pm_br['hq'] );
					if ( '' === $pm_t && '' === $pm_addr ) {
						continue;
					}
					?>
					<div class="pm-card pm-lp-branch<?php echo $pm_hq ? ' is-hq' : ''; ?>">
						<h3><?php echo pm_icon( 'location', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $pm_t ); ?></h3>
						<?php if ( '' !== $pm_addr ) : ?>
							<p class="pm-lp-branch__addr"><?php echo nl2br( esc_html( $pm_addr ) ); ?></p>
						<?php endif; ?>
						<?php if ( '' !== $pm_ph ) : ?>
							<p class="pm-lp-branch__phone">
								<?php esc_html_e( 'Hotline & Zalo:', 'prometal' ); ?>
								<a href="tel:<?php echo esc_attr( pm_digits( $pm_ph ) ); ?>"><b><?php echo esc_html( $pm_ph ); ?></b></a>
							</p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

	</div>
</section>
