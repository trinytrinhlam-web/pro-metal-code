<?php
/**
 * Ô tìm kiếm dùng chung (a11y, dùng token).
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_sf_id = 'pm-search-' . wp_unique_id();
?>
<form role="search" method="get" class="pm-searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="pm-sr-only" for="<?php echo esc_attr( $pm_sf_id ); ?>"><?php esc_html_e( 'Tìm kiếm', 'prometal' ); ?></label>
	<input
		id="<?php echo esc_attr( $pm_sf_id ); ?>"
		type="search"
		class="pm-searchform__input"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Tìm dịch vụ, bài viết…', 'prometal' ); ?>"
		required
	>
	<button type="submit" class="pm-searchform__submit" aria-label="<?php esc_attr_e( 'Tìm', 'prometal' ); ?>">
		<?php echo pm_icon( 'search', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="pm-sr-only"><?php esc_html_e( 'Tìm', 'prometal' ); ?></span>
	</button>
</form>
