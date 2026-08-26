<?php
/**
 * Thanh CTA Gọi / Zalo cố định đáy màn hình (chỉ hiện mobile/tablet).
 * Nhúng bởi footer.php. Ẩn từ ≥1080px qua components.css (.pm-sticky-cta).
 *
 * @owner   Session B
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="pm-sticky-cta" role="region" aria-label="<?php esc_attr_e( 'Liên hệ nhanh', 'prometal' ); ?>">
	<a class="pm-sticky-cta__btn pm-sticky-cta__btn--call" href="<?php echo esc_url( pm_phone_link() ); ?>">
		<?php echo pm_icon( 'phone', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php esc_html_e( 'Gọi ngay', 'prometal' ); ?></span>
	</a>
	<a class="pm-sticky-cta__btn pm-sticky-cta__btn--zalo" href="<?php echo esc_url( pm_zalo_link() ); ?>" target="_blank" rel="noopener">
		<?php echo pm_icon( 'zalo', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php esc_html_e( 'Nhắn Zalo', 'prometal' ); ?></span>
	</a>
</div>
