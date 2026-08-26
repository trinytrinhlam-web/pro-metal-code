<?php
/**
 * Trang Báo giá.
 *
 * Dùng component dùng chung (Session B) để chạy tốt trên mọi trang (không phụ
 * thuộc landing.css). Điểm nhấn: cam kết báo giá minh bạch + form gửi yêu cầu
 * theo hợp đồng REST /prometal/v1/lead.
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

	$pm_title = get_the_title();

	$pm_points = array(
		array(
			'title' => __( 'Báo giá theo bản vẽ/kích thước', 'prometal' ),
			'text'  => __( 'Bóc tách rõ hạng mục vật tư và nhân công trước khi làm, không phát sinh mập mờ.', 'prometal' ),
		),
		array(
			'title' => __( 'Tư vấn đúng nhu cầu', 'prometal' ),
			'text'  => __( 'Chọn vật tư và phương án phù hợp mục đích sử dụng, tránh dư thừa gây đội chi phí.', 'prometal' ),
		),
		array(
			'title' => __( 'Khảo sát tận nơi tại TP.HCM', 'prometal' ),
			'text'  => __( 'Nhận khảo sát hiện trạng, đo đạc và thống nhất khối lượng trước khi chốt giá.', 'prometal' ),
		),
	);
	?>
	<main id="main" class="site-main pm-container pm-page">
		<?php pm_the_breadcrumb(); ?>

		<header class="pm-page__header">
			<h1 class="pm-page__title"><?php echo esc_html( $pm_title ); ?></h1>
		</header>

		<?php if ( trim( get_the_content() ) ) : ?>
			<div class="pm-page__content pm-prose"><?php the_content(); ?></div>
		<?php else : ?>
			<p class="pm-lead"><?php esc_html_e( 'Gửi bản vẽ, kích thước hoặc ảnh hiện trạng — Pro-Metal bóc tách khối lượng và báo giá rõ ràng theo từng hạng mục. Cần gấp, anh chị gọi hotline để được tư vấn ngay.', 'prometal' ); ?></p>
		<?php endif; ?>

		<?php // ── Cam kết báo giá ── ?>
		<section aria-label="<?php esc_attr_e( 'Cam kết báo giá', 'prometal' ); ?>" style="margin-top:24px">
			<div class="pm-cards pm-cards--3">
				<?php foreach ( $pm_points as $pm_p ) : ?>
					<article class="pm-card pm-card--pad">
						<div class="pm-card__icon">
							<?php echo pm_icon( 'check', array( 'width' => 30, 'height' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<h2 class="pm-card__title"><?php echo esc_html( $pm_p['title'] ); ?></h2>
						<p class="pm-card__text"><?php echo esc_html( $pm_p['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</section>

		<?php // ── Form nhận báo giá ── ?>
		<section class="pm-card pm-card--pad" aria-label="<?php esc_attr_e( 'Nhận báo giá', 'prometal' ); ?>" style="margin:28px auto 0;max-width:680px">
			<h2><?php esc_html_e( 'Nhận báo giá miễn phí', 'prometal' ); ?></h2>
			<p class="pm-card__text" style="margin:6px 0 16px"><?php esc_html_e( 'Để lại thông tin, Pro-Metal gọi lại tư vấn và báo giá trong thời gian sớm nhất.', 'prometal' ); ?></p>

			<form class="pm-lead-form" method="post" novalidate>
				<div class="pm-field">
					<label for="pm-bg-ten"><?php esc_html_e( 'Họ tên', 'prometal' ); ?></label>
					<input id="pm-bg-ten" name="ten" type="text" autocomplete="name" placeholder="<?php esc_attr_e( 'Họ tên của bạn', 'prometal' ); ?>" required>
				</div>
				<div class="pm-field">
					<label for="pm-bg-sdt"><?php esc_html_e( 'Số điện thoại (có Zalo)', 'prometal' ); ?></label>
					<input id="pm-bg-sdt" name="sdt" type="tel" inputmode="tel" autocomplete="tel" placeholder="<?php esc_attr_e( 'Số điện thoại để gọi lại', 'prometal' ); ?>" required>
				</div>
				<div class="pm-field">
					<label for="pm-bg-noidung"><?php esc_html_e( 'Hạng mục cần báo giá', 'prometal' ); ?></label>
					<textarea id="pm-bg-noidung" name="noidung" placeholder="<?php esc_attr_e( 'Ví dụ: làm cửa cổng sắt 3,2m x 2,4m; hoặc vách panel 12m dài, cao 3m…', 'prometal' ); ?>"></textarea>
				</div>

				<input type="hidden" name="src" value="<?php echo esc_attr( 'Trang báo giá: ' . wp_strip_all_tags( $pm_title ) ); ?>">
				<div class="pm-hp" aria-hidden="true">
					<label for="pm-bg-website"><?php esc_html_e( 'Để trống ô này', 'prometal' ); ?></label>
					<input id="pm-bg-website" name="website" type="text" tabindex="-1" autocomplete="off">
				</div>

				<button type="submit" class="pm-btn pm-btn--call pm-btn--block pm-btn--lg">
					<?php echo pm_icon( 'send', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Gửi yêu cầu báo giá', 'prometal' ); ?></span>
				</button>
				<p class="pm-form-msg" role="status" aria-live="polite"></p>
				<p style="font-size:13px;color:var(--pm-muted);text-align:center;margin:12px 0 0">
					<?php esc_html_e( 'Chúng tôi chỉ dùng số điện thoại để gọi lại tư vấn, không gửi tin nhắn quảng cáo.', 'prometal' ); ?>
				</p>
			</form>
		</section>

	</main>
	<?php
endwhile;

get_footer();
