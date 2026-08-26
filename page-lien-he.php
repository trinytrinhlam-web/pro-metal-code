<?php
/**
 * Trang Liên hệ.
 *
 * Dùng component dùng chung (Session B) — .pm-card / .pm-field / .pm-lead-form /
 * .pm-btn — để hiển thị đúng trên mọi trang (không phụ thuộc landing.css). Form
 * theo hợp đồng REST /prometal/v1/lead. Địa chỉ/hotline lấy từ theme option.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$pm_title   = get_the_title();
	$pm_address = pm_option( 'address' );
	$pm_map     = pm_option( 'map_url' );
	?>
	<main id="main" class="site-main pm-container pm-page">
		<?php pm_the_breadcrumb(); ?>

		<header class="pm-page__header">
			<h1 class="pm-page__title"><?php echo esc_html( $pm_title ); ?></h1>
		</header>

		<?php if ( trim( get_the_content() ) ) : ?>
			<div class="pm-page__content pm-prose"><?php the_content(); ?></div>
		<?php else : ?>
			<p class="pm-lead"><?php esc_html_e( 'Anh chị cần tư vấn, khảo sát hoặc báo giá? Gọi hotline, nhắn Zalo hoặc để lại thông tin — Pro-Metal sẽ liên hệ lại nhanh nhất.', 'prometal' ); ?></p>
		<?php endif; ?>

		<div class="pm-cards pm-cards--2" style="margin-top:24px">

			<?php // ── Thông tin liên hệ ── ?>
			<section class="pm-card pm-card--pad" aria-label="<?php esc_attr_e( 'Thông tin liên hệ', 'prometal' ); ?>">
				<h2><?php esc_html_e( 'Thông tin liên hệ', 'prometal' ); ?></h2>
				<ul class="pm-contact-list" style="list-style:none;padding:0;margin:16px 0 0;display:flex;flex-direction:column;gap:14px">
					<li style="display:flex;gap:12px;align-items:flex-start">
						<?php echo pm_icon( 'location', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><b><?php esc_html_e( 'Trụ sở chính:', 'prometal' ); ?></b><br><?php echo esc_html( $pm_address ); ?></span>
					</li>
					<li style="display:flex;gap:12px;align-items:flex-start">
						<?php echo pm_icon( 'phone', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><b><?php esc_html_e( 'Hotline & Zalo:', 'prometal' ); ?></b><br><a href="<?php echo esc_url( pm_phone_link() ); ?>"><?php echo esc_html( pm_phone() ); ?></a></span>
					</li>
					<?php if ( pm_option( 'email' ) ) : ?>
						<li style="display:flex;gap:12px;align-items:flex-start">
							<?php echo pm_icon( 'mail', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><b><?php esc_html_e( 'Email:', 'prometal' ); ?></b><br><a href="mailto:<?php echo esc_attr( pm_option( 'email' ) ); ?>"><?php echo esc_html( pm_option( 'email' ) ); ?></a></span>
						</li>
					<?php endif; ?>
					<?php if ( pm_option( 'website' ) ) : ?>
						<li style="display:flex;gap:12px;align-items:flex-start">
							<?php echo pm_icon( 'globe', array( 'width' => 20, 'height' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span><b><?php esc_html_e( 'Website:', 'prometal' ); ?></b><br><?php echo esc_html( pm_option( 'website' ) ); ?></span>
						</li>
					<?php endif; ?>
				</ul>

				<div class="pm-btns" style="display:flex;gap:12px;flex-wrap:wrap;margin-top:20px">
					<a class="pm-btn pm-btn--call" href="<?php echo esc_url( pm_phone_link() ); ?>">
						<?php echo pm_icon( 'phone', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php esc_html_e( 'Gọi ngay', 'prometal' ); ?></span>
					</a>
					<a class="pm-btn pm-btn--zalo" href="<?php echo esc_url( pm_zalo_link() ); ?>" target="_blank" rel="nofollow noopener">
						<?php echo pm_icon( 'zalo', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php esc_html_e( 'Nhắn Zalo', 'prometal' ); ?></span>
					</a>
				</div>
			</section>

			<?php // ── Form gửi yêu cầu ── ?>
			<section class="pm-card pm-card--pad" aria-label="<?php esc_attr_e( 'Gửi yêu cầu tư vấn', 'prometal' ); ?>">
				<h2><?php esc_html_e( 'Gửi yêu cầu tư vấn', 'prometal' ); ?></h2>
				<form class="pm-lead-form" method="post" novalidate style="margin-top:16px">
					<div class="pm-field">
						<label for="pm-lh-ten"><?php esc_html_e( 'Họ tên', 'prometal' ); ?></label>
						<input id="pm-lh-ten" name="ten" type="text" autocomplete="name" placeholder="<?php esc_attr_e( 'Họ tên của bạn', 'prometal' ); ?>" required>
					</div>
					<div class="pm-field">
						<label for="pm-lh-sdt"><?php esc_html_e( 'Số điện thoại (có Zalo)', 'prometal' ); ?></label>
						<input id="pm-lh-sdt" name="sdt" type="tel" inputmode="tel" autocomplete="tel" placeholder="<?php esc_attr_e( 'Số điện thoại để gọi lại', 'prometal' ); ?>" required>
					</div>
					<div class="pm-field">
						<label for="pm-lh-noidung"><?php esc_html_e( 'Nội dung cần tư vấn', 'prometal' ); ?></label>
						<textarea id="pm-lh-noidung" name="noidung" placeholder="<?php esc_attr_e( 'Mô tả ngắn nhu cầu, khu vực và thời gian mong muốn', 'prometal' ); ?>"></textarea>
					</div>

					<input type="hidden" name="src" value="<?php echo esc_attr( 'Trang liên hệ: ' . wp_strip_all_tags( $pm_title ) ); ?>">
					<div class="pm-hp" aria-hidden="true">
						<label for="pm-lh-website"><?php esc_html_e( 'Để trống ô này', 'prometal' ); ?></label>
						<input id="pm-lh-website" name="website" type="text" tabindex="-1" autocomplete="off">
					</div>

					<button type="submit" class="pm-btn pm-btn--call pm-btn--block">
						<?php echo pm_icon( 'send', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span><?php esc_html_e( 'Gửi yêu cầu', 'prometal' ); ?></span>
					</button>
					<p class="pm-form-msg" role="status" aria-live="polite"></p>
				</form>
			</section>

		</div>

		<?php if ( $pm_map ) : ?>
			<div class="pm-map" style="margin-top:24px;border-radius:var(--pm-r-lg);overflow:hidden;border:1px solid var(--pm-line)">
				<iframe src="<?php echo esc_url( $pm_map ); ?>" title="<?php esc_attr_e( 'Bản đồ tới Pro-Metal', 'prometal' ); ?>" width="100%" height="380" style="border:0;display:block" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
			</div>
		<?php endif; ?>

	</main>
	<?php
endwhile;

get_footer();
