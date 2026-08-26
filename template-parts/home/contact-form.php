<?php
/**
 * Section 7 — Liên hệ với Pro-Metal.
 *
 * Nền tối. Cột trái ảnh (ô mờ) + cột phải form lead. Form theo hợp đồng
 * Session B: class .pm-lead-form + input name="ten|sdt|noidung|src" +
 * honeypot "website" + <p class="pm-form-msg"> → form.js gửi REST /prometal/v1/lead.
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="pm-lien-he" class="pm-section pm-home-contact">
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--light">
			<h2><?php esc_html_e( 'Liên hệ với Pro-Metal', 'prometal' ); ?></h2>
		</div>

		<div class="pm-home-contact__grid">

			<div class="pm-home-contact__media" role="img" aria-label="<?php esc_attr_e( 'Bản vẽ kỹ thuật cổng sắt Pro-Metal — Thiết kế · Thi công · Sửa chữa', 'prometal' ); ?>" style="background-color:#0e3a50;background-image:url('<?php echo esc_url( get_theme_file_uri( 'assets/img/contact-illus.svg' ) ); ?>');background-size:contain;background-position:center;background-repeat:no-repeat"></div>

			<div class="pm-home-contact__panel">
				<h3 class="pm-home-contact__title"><?php esc_html_e( 'Gửi ngay yêu cầu của bạn', 'prometal' ); ?></h3>

				<form class="pm-lead-form pm-home-contact__form" method="post" novalidate>
					<div class="pm-field">
						<label class="pm-sr-only" for="pm-contact-ten"><?php esc_html_e( 'Họ tên', 'prometal' ); ?></label>
						<input id="pm-contact-ten" type="text" name="ten" required autocomplete="name" placeholder="<?php esc_attr_e( 'Họ tên', 'prometal' ); ?>">
					</div>
					<div class="pm-field">
						<label class="pm-sr-only" for="pm-contact-sdt"><?php esc_html_e( 'Số điện thoại (có Zalo)', 'prometal' ); ?></label>
						<input id="pm-contact-sdt" type="tel" name="sdt" required autocomplete="tel" inputmode="tel" placeholder="<?php esc_attr_e( 'Số điện thoại (có Zalo)', 'prometal' ); ?>">
					</div>
					<div class="pm-field">
						<label class="pm-sr-only" for="pm-contact-noidung"><?php esc_html_e( 'Lời nhắn / nhu cầu', 'prometal' ); ?></label>
						<textarea id="pm-contact-noidung" name="noidung" rows="2" placeholder="<?php esc_attr_e( 'Lời nhắn / nhu cầu', 'prometal' ); ?>"></textarea>
					</div>

					<input type="hidden" name="src" value="home-lienhe">
					<?php // Honeypot chống spam — người thật để trống. ?>
					<div class="pm-hp" aria-hidden="true">
						<label for="pm-contact-website"><?php esc_html_e( 'Để trống ô này', 'prometal' ); ?></label>
						<input id="pm-contact-website" type="text" name="website" tabindex="-1" autocomplete="off">
					</div>

					<button type="submit" class="pm-btn pm-btn--call pm-btn--block">
						<?php echo pm_icon( 'send', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php esc_html_e( 'Gửi yêu cầu', 'prometal' ); ?></span>
					</button>
					<p class="pm-form-msg" role="status" aria-live="polite"></p>
				</form>

				<div class="pm-home-contact__info">
					<span class="pm-home-contact__info-label"><?php esc_html_e( 'THÔNG TIN LIÊN HỆ', 'prometal' ); ?></span>
					<a class="pm-home-contact__phone" href="<?php echo esc_url( pm_phone_link() ); ?>">
						<?php echo pm_icon( 'phone', array( 'width' => 26, 'height' => 26 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php esc_html_e( 'SĐT/Zalo:', 'prometal' ); ?> <b><?php echo esc_html( pm_phone() ); ?></b></span>
					</a>
				</div>
			</div>

		</div>
	</div>
</section>
