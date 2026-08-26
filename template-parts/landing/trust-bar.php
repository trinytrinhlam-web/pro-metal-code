<?php
/**
 * Landing block — Thanh niềm tin (4 chỉ số, nền xanh brand).
 * Layout Carbon Fields: `trust_bar`.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b = ( isset( $args ) && is_array( $args ) ) ? $args : array();

$pm_items = prometal_lp_get( $pm_b, 'items', array() );
if ( empty( $pm_items ) ) {
	$pm_items = array(
		array(
			'big'   => __( 'Gửi bản vẽ', 'prometal' ),
			'small' => __( 'bóc tách khối lượng rõ hơn', 'prometal' ),
		),
		array(
			'big'   => __( 'Lắp nhanh', 'prometal' ),
			'small' => __( 'ít đập phá, ít bụi', 'prometal' ),
		),
		array(
			'big'   => __( 'Nhiều ứng dụng', 'prometal' ),
			'small' => __( 'xưởng, văn phòng, phòng trọ', 'prometal' ),
		),
		array(
			'big'   => pm_phone(),
			'small' => __( 'hotline & Zalo', 'prometal' ),
		),
	);
}
?>
<section class="pm-lp-trust" aria-label="<?php esc_attr_e( 'Điểm nổi bật', 'prometal' ); ?>">
	<div class="pm-container pm-lp-trust__grid">
		<?php foreach ( $pm_items as $pm_it ) : ?>
			<?php
			$pm_big   = prometal_lp_get( $pm_it, 'big', '' );
			$pm_small = prometal_lp_get( $pm_it, 'small', '' );
			if ( '' === $pm_big && '' === $pm_small ) {
				continue;
			}
			?>
			<div class="pm-lp-trust__item">
				<b><?php echo esc_html( $pm_big ); ?></b>
				<span><?php echo esc_html( $pm_small ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>
