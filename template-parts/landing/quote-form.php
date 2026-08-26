<?php
/**
 * Landing block — Form báo giá (nền tối, thẻ form trắng).
 * Form theo HỢP ĐỒNG Session B: .pm-lead-form + name ten/sdt/noidung, honeypot
 * website, <p class="pm-form-msg"> → form.js gửi REST /prometal/v1/lead.
 * Layout Carbon Fields: `quote_form`. Rỗng → ảnh minh hoạ thật bundle trong
 * theme (assets/img/lp-form.jpg).
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b       = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_id      = prometal_lp_section_id( 'quote_form' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Gửi bản vẽ hoặc kích thước, nhận tư vấn vách panel', 'prometal' ) );
$pm_text    = prometal_lp_get( $pm_b, 'text', __( 'Anh chị để lại số điện thoại kèm diện tích, chiều cao vách, mục đích sử dụng và khu vực thi công. Có bản vẽ mặt bằng hoặc ảnh hiện trạng thì gửi thêm qua Zalo để chúng tôi bóc tách nhanh hơn.', 'prometal' ) );
$pm_img     = prometal_lp_get( $pm_b, 'image', '' );

$pm_src = 'Landing: ' . wp_strip_all_tags( get_the_title() );
?>
<section class="pm-lp-form-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container pm-lp-form-grid">

		<div class="pm-lp-form-intro">
			<div class="pm-h2wrap pm-h2wrap--left pm-h2wrap--light"><h2><?php echo esc_html( $pm_heading ); ?></h2></div>
			<p class="pm-lp-form-intro__text"><?php echo esc_html( $pm_text ); ?></p>

			<div class="pm-lp-form-intro__media">
				<?php
				$pm_fimg = prometal_lp_img( $pm_img, $pm_heading, 'pm-lp-form-intro__img', 'pm-hero' );
				if ( '' !== $pm_fimg ) {
					echo $pm_fimg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				} else {
					echo '<img src="' . esc_url( get_theme_file_uri( 'assets/img/lp-form.jpg' ) ) . '" alt="' . esc_attr( $pm_heading ) . '" class="pm-lp-form-intro__img" loading="lazy">';
				}
				?>
			</div>

			<a class="pm-lp-callbig" href="<?php echo esc_url( pm_phone_link() ); ?>">
				<?php echo pm_icon( 'phone', array( 'width' => 26, 'height' => 26 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span>
					<?php esc_html_e( 'Hotline & Zalo', 'prometal' ); ?>
					<b><?php echo esc_html( pm_phone() ); ?></b>
				</span>
			</a>
		</div>

		<form class="pm-lead-form pm-lp-form" method="post" novalidate>
			<div class="pm-field">
				<label for="pm-lp-ten"><?php esc_html_e( 'Họ tên', 'prometal' ); ?></label>
				<input id="pm-lp-ten" name="ten" type="text" autocomplete="name" placeholder="<?php esc_attr_e( 'Ví dụ: Nguyễn Văn A', 'prometal' ); ?>" required>
			</div>
			<div class="pm-field">
				<label for="pm-lp-sdt"><?php esc_html_e( 'Số điện thoại', 'prometal' ); ?></label>
				<input id="pm-lp-sdt" name="sdt" type="tel" inputmode="tel" autocomplete="tel" placeholder="<?php esc_attr_e( 'Số có Zalo càng tốt', 'prometal' ); ?>" required>
			</div>
			<div class="pm-field">
				<label for="pm-lp-noidung"><?php esc_html_e( 'Nhu cầu thi công panel', 'prometal' ); ?></label>
				<textarea id="pm-lp-noidung" name="noidung" placeholder="<?php esc_attr_e( 'Ví dụ: làm vách panel chia văn phòng trong xưởng, dài khoảng 12m, cao 3m, có 1 cửa đi', 'prometal' ); ?>"></textarea>
			</div>

			<input type="hidden" name="src" value="<?php echo esc_attr( $pm_src ); ?>">
			<?php // Honeypot chống spam — người thật để trống. ?>
			<div class="pm-hp" aria-hidden="true">
				<label for="pm-lp-website"><?php esc_html_e( 'Để trống ô này', 'prometal' ); ?></label>
				<input id="pm-lp-website" name="website" type="text" tabindex="-1" autocomplete="off">
			</div>

			<button class="pm-btn pm-btn--call pm-btn--block pm-btn--lg" type="submit">
				<?php echo pm_icon( 'send', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php esc_html_e( 'Nhận tư vấn vách panel', 'prometal' ); ?></span>
			</button>
			<p class="pm-form-msg" role="status" aria-live="polite"></p>
			<p class="pm-lp-form__fine"><?php esc_html_e( 'Chúng tôi chỉ dùng số điện thoại để gọi lại tư vấn, không gửi tin nhắn quảng cáo.', 'prometal' ); ?></p>
		</form>

	</div>
</section>
