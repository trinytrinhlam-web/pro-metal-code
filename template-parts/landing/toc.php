<?php
/**
 * Landing — Mục lục dính (thanh ngang). Dùng .pm-toc (hợp đồng Session B) để
 * toc.js gắn scroll-spy + cuộn mượt; .pm-toc--bar để style thanh ngang (landing.css).
 * Nhận danh sách mục qua $args['items'] = [ '#id' => 'Nhãn' ] do template-landing dựng.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_items = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : array();
if ( empty( $pm_items ) ) {
	return;
}
?>
<nav class="pm-toc pm-toc--bar" aria-label="<?php esc_attr_e( 'Mục lục nội dung', 'prometal' ); ?>">
	<div class="pm-container">
		<ul class="pm-toc__list">
			<?php foreach ( $pm_items as $pm_id => $pm_label ) : ?>
				<li>
					<a class="pm-toc__link" href="#<?php echo esc_attr( $pm_id ); ?>"><?php echo esc_html( $pm_label ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</nav>
