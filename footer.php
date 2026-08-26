<?php
/**
 * Footer dùng chung — thương hiệu/địa chỉ, dịch vụ, khu vực, form báo giá nhanh, social.
 * Nhúng template-parts/sticky-cta.php (Session B) nếu có.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_services = array(
	__( 'Sửa cửa sắt tại nhà', 'prometal' ) => home_url( '/dich-vu/sua-cua-sat-tai-nha/' ),
	__( 'Sửa cửa kéo, cổng', 'prometal' )   => home_url( '/dich-vu/sua-cua-keo/' ),
	__( 'Thợ hàn sắt tại nhà', 'prometal' ) => home_url( '/dich-vu/tho-han-sat-tai-nha/' ),
	__( 'Làm cửa / cổng sắt', 'prometal' )  => home_url( '/thi-cong/lam-cua-cong-sat/' ),
	__( 'Mái hiên / mái che', 'prometal' )  => home_url( '/thi-cong/mai-hien-mai-che/' ),
	__( 'Cầu thang / lan can', 'prometal' ) => home_url( '/thi-cong/cau-thang-lan-can/' ),
	__( 'Cửa & cầu thang inox', 'prometal' )=> home_url( '/dich-vu/inox/' ),
	__( 'Nhôm kính, vách panel', 'prometal' )=> home_url( '/dich-vu/nhom-kinh/' ),
);
?>
</div><!-- #content -->

<footer class="pm-site-footer" role="contentinfo">
	<div class="pm-container pm-footer__top">

		<?php // ── Cột thương hiệu ── ?>
		<div class="pm-footer__col pm-footer__brand">
			<div class="pm-footer__logo"><?php echo pm_logo_html( true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<p class="pm-footer__slogan pm-js-slogan"><?php echo esc_html( pm_option( 'slogan' ) ); ?></p>
			<ul class="pm-footer__contact">
				<li>
					<?php echo pm_icon( 'location', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span>
						<b><?php esc_html_e( 'Trụ sở chính:', 'prometal' ); ?></b>
						<span class="pm-js-address"><?php echo esc_html( pm_option( 'address' ) ); ?></span><br>
						<em><?php echo esc_html( pm_option( 'branches_note' ) ); ?></em>
					</span>
				</li>
				<li>
					<?php echo pm_icon( 'phone', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<a href="<?php echo esc_url( pm_phone_link() ); ?>"><?php esc_html_e( 'Hotline:', 'prometal' ); ?> <b class="pm-js-phone"><?php echo esc_html( pm_phone() ); ?></b></a>
				</li>
				<?php if ( pm_option( 'website' ) ) : ?>
				<li>
					<?php echo pm_icon( 'globe', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php echo esc_html( pm_option( 'website' ) ); ?></span>
				</li>
				<?php endif; ?>
				<?php if ( pm_option( 'email' ) ) : ?>
				<li>
					<?php echo pm_icon( 'mail', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<a href="mailto:<?php echo esc_attr( pm_option( 'email' ) ); ?>"><?php echo esc_html( pm_option( 'email' ) ); ?></a>
				</li>
				<?php endif; ?>
			</ul>

			<?php $pm_socials = pm_social_links(); ?>
			<?php if ( ! empty( $pm_socials ) ) : ?>
			<ul class="pm-footer__social" aria-label="<?php esc_attr_e( 'Mạng xã hội', 'prometal' ); ?>">
				<?php foreach ( $pm_socials as $s ) : ?>
					<li>
						<a class="pm-social pm-social--<?php echo esc_attr( $s['type'] ); ?>" href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $s['label'] ); ?>">
							<?php echo pm_icon( $s['type'], array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</div>

		<?php // ── Cột dịch vụ ── ?>
		<div class="pm-footer__col pm-footer__services">
			<h2 class="pm-footer__title"><?php esc_html_e( 'Dịch vụ', 'prometal' ); ?></h2>
			<?php
			if ( has_nav_menu( 'footer' ) ) :
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'pm-footer__list',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			else :
				?>
				<ul class="pm-footer__list">
					<?php foreach ( $pm_services as $label => $url ) : ?>
						<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $label ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php // ── Cột khu vực / chi nhánh ── ?>
		<div class="pm-footer__col pm-footer__areas">
			<h2 class="pm-footer__title"><?php esc_html_e( 'Khu vực phục vụ', 'prometal' ); ?></h2>
			<?php $pm_branch_list = pm_branches(); ?>
			<?php if ( ! empty( $pm_branch_list ) ) : ?>
				<ul class="pm-footer__list">
					<?php foreach ( $pm_branch_list as $branch ) : ?>
						<li><?php echo pm_icon( 'location', array( 'width' => 15, 'height' => 15 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $branch ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<p class="pm-footer__areas-note"><?php echo esc_html( pm_option( 'branches_note' ) ); ?></p>
				<ul class="pm-footer__pills">
					<?php
					$pm_districts = array( 'Bình Tân', 'Tân Phú', 'Gò Vấp', 'Bình Thạnh', 'Thủ Đức', 'Quận 12', 'Hóc Môn', 'Bình Chánh' );
					foreach ( $pm_districts as $d ) :
						?>
						<li><?php echo esc_html( $d ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<?php // ── Cột form báo giá nhanh (contract .pm-lead-form → form.js Session B) ── ?>
		<div class="pm-footer__col pm-footer__quote">
			<h2 class="pm-footer__title"><?php esc_html_e( 'Nhận báo giá nhanh', 'prometal' ); ?></h2>
			<p class="pm-footer__quote-sub"><?php esc_html_e( 'Để lại thông tin, Pro-Metal gọi lại tư vấn & báo giá miễn phí.', 'prometal' ); ?></p>
			<form class="pm-lead-form pm-footer__form" method="post" novalidate>
				<div class="pm-field">
					<label class="pm-sr-only" for="pm-foot-ten"><?php esc_html_e( 'Họ tên', 'prometal' ); ?></label>
					<input id="pm-foot-ten" type="text" name="ten" required autocomplete="name" placeholder="<?php esc_attr_e( 'Họ tên của bạn', 'prometal' ); ?>">
				</div>
				<div class="pm-field">
					<label class="pm-sr-only" for="pm-foot-sdt"><?php esc_html_e( 'Số điện thoại (có Zalo)', 'prometal' ); ?></label>
					<input id="pm-foot-sdt" type="tel" name="sdt" required autocomplete="tel" inputmode="tel" placeholder="<?php esc_attr_e( 'Số điện thoại (có Zalo)', 'prometal' ); ?>">
				</div>
				<div class="pm-field">
					<label class="pm-sr-only" for="pm-foot-dv"><?php esc_html_e( 'Dịch vụ cần tư vấn', 'prometal' ); ?></label>
					<select id="pm-foot-dv" name="noidung">
						<option value=""><?php esc_html_e( '— Chọn dịch vụ —', 'prometal' ); ?></option>
						<?php foreach ( array_keys( $pm_services ) as $label ) : ?>
							<option value="<?php echo esc_attr( $label ); ?>"><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
						<option value="<?php esc_attr_e( 'Khác', 'prometal' ); ?>"><?php esc_html_e( 'Khác', 'prometal' ); ?></option>
					</select>
				</div>
				<input type="hidden" name="src" value="footer-baogianhanh">
				<?php // Honeypot chống spam — người thật để trống. ?>
				<div class="pm-hp" aria-hidden="true">
					<label for="pm-foot-website"><?php esc_html_e( 'Để trống ô này', 'prometal' ); ?></label>
					<input id="pm-foot-website" type="text" name="website" tabindex="-1" autocomplete="off">
				</div>
				<button type="submit" class="pm-cta pm-cta--call pm-footer__submit">
					<?php echo pm_icon( 'send', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Gửi yêu cầu', 'prometal' ); ?></span>
				</button>
				<p class="pm-form-msg" role="status" aria-live="polite"></p>
			</form>
		</div>

	</div>

	<div class="pm-footer__bottom">
		<div class="pm-container pm-footer__bottom-inner">
			<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?> — <?php esc_html_e( 'Cơ Khí Pro-Metal. Đã đăng ký bản quyền.', 'prometal' ); ?></p>
			<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="pm-footer__legal" aria-label="<?php esc_attr_e( 'Liên kết cuối trang', 'prometal' ); ?>"></nav>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php
// Sticky CTA mobile (Session B). Nhúng nếu file có nội dung.
if ( locate_template( 'template-parts/sticky-cta.php' ) ) {
	get_template_part( 'template-parts/sticky-cta' );
}

wp_footer();
?>
</body>
</html>
