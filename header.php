<?php
/**
 * Header dùng chung — topbar, logo, menu đa cấp (mega-menu), CTA Gọi/Zalo.
 * Hamburger mobile + dropdown/mega-menu hoạt động bằng CSS thuần (không phụ
 * thuộc JS) — xem Prometal_Mega_Menu_Walker trong inc/nav-walker.php.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="pm-skip-link" href="#main"><?php esc_html_e( 'Bỏ qua tới nội dung', 'prometal' ); ?></a>

<header class="pm-site-header" role="banner">

	<?php // ── Top bar: địa chỉ trụ sở + hotline ── ?>
	<div class="pm-topbar">
		<div class="pm-container pm-topbar__inner">
			<p class="pm-topbar__addr">
				<?php echo pm_icon( 'location', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="pm-js-address"><?php echo esc_html( pm_option( 'address' ) ); ?></span>
			</p>
			<p class="pm-topbar__hot">
				<a href="<?php echo esc_url( pm_phone_link() ); ?>">
					<?php echo pm_icon( 'phone', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Hotline:', 'prometal' ); ?></span>
					<b class="pm-js-phone"><?php echo esc_html( pm_phone() ); ?></b>
				</a>
			</p>
		</div>
	</div>

	<?php // ── Thanh chính: logo + menu + CTA ── ?>
	<div class="pm-header-main">
		<div class="pm-container pm-header-main__inner">

			<div class="pm-header-brand">
				<?php echo pm_logo_html( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<?php // Checkbox hack: bật/tắt menu trên mobile, không cần JS. ?>
			<input type="checkbox" id="pm-nav-toggle" class="pm-nav-toggle" aria-label="<?php esc_attr_e( 'Mở / đóng menu', 'prometal' ); ?>">
			<label for="pm-nav-toggle" class="pm-burger">
				<span class="pm-sr-only"><?php esc_html_e( 'Menu', 'prometal' ); ?></span>
				<span class="pm-burger__open" aria-hidden="true"><?php echo pm_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="pm-burger__close" aria-hidden="true"><?php echo pm_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</label>

			<nav class="pm-nav" id="pm-primary-nav" aria-label="<?php esc_attr_e( 'Menu chính', 'prometal' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_id'        => 'pm-primary-menu',
						'menu_class'     => 'pm-menu',
						'depth'          => 3,
						'walker'         => new Prometal_Mega_Menu_Walker(),
						'fallback_cb'    => 'prometal_primary_menu_fallback',
					)
				);
				?>
			</nav>

			<div class="pm-header-cta">
				<a class="pm-cta pm-cta--call" href="<?php echo esc_url( pm_phone_link() ); ?>">
					<?php echo pm_icon( 'phone', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Gọi ngay', 'prometal' ); ?></span>
				</a>
				<a class="pm-cta pm-cta--zalo" href="<?php echo esc_url( pm_zalo_link() ); ?>" target="_blank" rel="noopener">
					<?php echo pm_icon( 'zalo', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Nhắn Zalo', 'prometal' ); ?></span>
				</a>
			</div>

		</div>
	</div>
</header>

<div id="content" class="site-content">
