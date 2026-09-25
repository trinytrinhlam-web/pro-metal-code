<?php
/**
 * News — card sidebar: "Tư vấn nhanh" (Gọi/Zalo động) + Dịch vụ nổi bật.
 * Dữ liệu liên hệ lấy từ helper pm_* (KHÔNG hardcode SĐT/địa chỉ).
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="pm-consult" role="complementary" aria-label="<?php esc_attr_e( 'Tư vấn kỹ thuật nhanh', 'prometal' ); ?>">
	<span class="pm-consult__glow" aria-hidden="true"></span>

	<span class="pm-consult__icon">
		<?php echo pm_icon( 'phone', array( 'width' => 26, 'height' => 26 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</span>

	<h2 class="pm-consult__title"><?php esc_html_e( 'Tư vấn kỹ thuật nhanh', 'prometal' ); ?></h2>
	<p class="pm-consult__text"><?php esc_html_e( 'Kết nối trực tiếp với kỹ sư Pro-Metal để được khảo sát, tư vấn và báo giá miễn phí.', 'prometal' ); ?></p>

	<a class="pm-btn pm-btn--call pm-btn--block" href="<?php echo esc_url( pm_phone_link() ); ?>">
		<?php echo pm_icon( 'phone', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php echo esc_html( sprintf( /* translators: %s: hotline. */ __( 'Hotline: %s', 'prometal' ), pm_phone() ) ); ?></span>
	</a>

	<a class="pm-btn pm-btn--zalo pm-btn--block" href="<?php echo esc_url( pm_zalo_link() ); ?>" target="_blank" rel="noopener">
		<?php echo pm_icon( 'zalo', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span><?php esc_html_e( 'Chat qua Zalo', 'prometal' ); ?></span>
	</a>
</div>

<?php
$pm_hot_services = array(
	__( 'Sửa cửa sắt tại nhà', 'prometal' ) => pm_service_url( 'sua-cua-sat' ),
	__( 'Làm cửa / cổng sắt', 'prometal' )  => pm_service_url( 'lam-cua-sat' ),
	__( 'Báo giá cửa sắt', 'prometal' )     => pm_service_url( 'bao-gia' ),
);
?>
<div class="pm-side-card">
	<h2 class="pm-side-card__title"><?php esc_html_e( 'Dịch vụ nổi bật', 'prometal' ); ?></h2>
	<ul class="pm-side-services">
		<?php foreach ( $pm_hot_services as $pm_label => $pm_url ) : ?>
			<li>
				<a href="<?php echo esc_url( $pm_url ); ?>">
					<span><?php echo esc_html( $pm_label ); ?></span>
					<?php echo pm_icon( 'chevron-right', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
