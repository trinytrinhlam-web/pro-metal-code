<?php
/**
 * Section 1 — Hero slider.
 *
 * Băng chuyền ảnh nền (cross-fade) + nội dung cố định phủ lên trên.
 * Ảnh thật: mặc định 3 ảnh bundle trong assets/img (lọc được qua
 * 'prometal_hero_slides' để admin/khác thay bằng ảnh của họ).
 * H1 giữ chữ "PRO-METAL" tô cam (quyết định đã duyệt — ngoại lệ có chủ đích).
 * Điều khiển slider ở assets/js/slider.js (hook: [data-pm-hero]).
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Danh sách slide hero. Mỗi phần tử: img (URL) + alt (mô tả a11y).
 * Bỏ qua slide thiếu file ảnh để không hiện khung trống.
 */
$pm_hero_slides = array(
	array(
		'file' => 'assets/img/hero-1.png',
		'alt'  => __( 'Thợ Pro-Metal sửa chữa cửa sắt tại nhà', 'prometal' ),
	),
	array(
		'file' => 'assets/img/hero-2.png',
		'alt'  => __( 'Thi công tổng hợp cửa sắt, nhôm, inox', 'prometal' ),
	),
	array(
		'file' => 'assets/img/hero-3.png',
		'alt'  => __( 'Nhà xưởng cơ khí Pro-Metal', 'prometal' ),
	),
);

/** Cho phép ghi đè nguồn ảnh hero. */
$pm_hero_slides = apply_filters( 'prometal_hero_slides', $pm_hero_slides );

// Chuẩn hoá: chỉ giữ slide có ảnh thật (file bundle tồn tại, hoặc URL tuyệt đối).
$pm_slides = array();
foreach ( (array) $pm_hero_slides as $pm_s ) {
	if ( empty( $pm_s['file'] ) ) {
		continue;
	}
	$pm_file = (string) $pm_s['file'];
	if ( preg_match( '#^https?://#i', $pm_file ) ) {
		$pm_url = $pm_file; // URL tuyệt đối do filter cấp.
	} elseif ( file_exists( get_theme_file_path( $pm_file ) ) ) {
		$pm_url = get_theme_file_uri( $pm_file );
	} else {
		continue; // Thiếu ảnh → bỏ qua.
	}
	$pm_slides[] = array(
		'url' => $pm_url,
		'alt' => isset( $pm_s['alt'] ) ? (string) $pm_s['alt'] : '',
	);
}

$pm_has_slides = ! empty( $pm_slides );
?>
<section class="pm-hero" aria-label="<?php esc_attr_e( 'Giới thiệu Pro-Metal', 'prometal' ); ?>"<?php echo $pm_has_slides ? ' data-pm-hero' : ''; ?>>

	<?php if ( $pm_has_slides ) : ?>
		<div class="pm-hero__slides" aria-hidden="true">
			<?php foreach ( $pm_slides as $pm_i => $pm_slide ) : ?>
				<?php if ( 0 === $pm_i ) : ?>
					<div class="pm-hero__slide is-active" role="img" aria-label="<?php echo esc_attr( $pm_slide['alt'] ); ?>">
						<img class="pm-hero__image" src="<?php echo esc_url( $pm_slide['url'] ); ?>" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
					</div>
				<?php else : ?>
					<div class="pm-hero__slide" role="img" aria-label="<?php echo esc_attr( $pm_slide['alt'] ); ?>" data-pm-hero-src="<?php echo esc_url( $pm_slide['url'] ); ?>"></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<?php if ( count( $pm_slides ) > 1 ) : ?>
			<button type="button" class="pm-hero__arrow pm-hero__arrow--prev" data-pm-hero-prev aria-label="<?php esc_attr_e( 'Ảnh trước', 'prometal' ); ?>">
				<?php echo pm_icon( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<button type="button" class="pm-hero__arrow pm-hero__arrow--next" data-pm-hero-next aria-label="<?php esc_attr_e( 'Ảnh sau', 'prometal' ); ?>">
				<?php echo pm_icon( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		<?php endif; ?>
	<?php endif; ?>

	<div class="pm-hero__inner">
		<div class="pm-container">
			<div class="pm-hero__content">
				<h1 class="pm-hero__title">
					<?php esc_html_e( 'Sửa Chữa Cửa Sắt', 'prometal' ); ?><br>
					<span class="pm-hero__brand">PRO-METAL</span>
				</h1>
				<p class="pm-hero__lead">
					<?php esc_html_e( 'Thiết kế – thi công – sửa chữa cửa sắt, cửa nhôm, cửa inox… tại nhà. Chuyên nghiệp – Uy tín – Nhanh chóng – Bảo hành đầy đủ.', 'prometal' ); ?>
				</p>
				<div class="pm-hero__btns">
					<a class="pm-btn pm-btn--call pm-btn--lg" href="#pm-lien-he">
						<?php esc_html_e( 'Nhận báo giá ngay', 'prometal' ); ?>
					</a>
					<a class="pm-btn pm-btn--ghost-light pm-btn--lg" href="#pm-dich-vu-sat">
						<?php esc_html_e( 'Xem dịch vụ', 'prometal' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>

	<?php if ( $pm_has_slides && count( $pm_slides ) > 1 ) : ?>
		<div class="pm-hero__nav">
			<div class="pm-container pm-hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'Chọn ảnh hero', 'prometal' ); ?>">
				<?php foreach ( $pm_slides as $pm_i => $pm_slide ) : ?>
					<button type="button" class="pm-hero__dot<?php echo 0 === $pm_i ? ' is-active' : ''; ?>"
						data-pm-hero-dot="<?php echo esc_attr( $pm_i ); ?>"
						role="tab" aria-selected="<?php echo 0 === $pm_i ? 'true' : 'false'; ?>"
						aria-label="<?php echo esc_attr( sprintf( __( 'Ảnh %d', 'prometal' ), $pm_i + 1 ) ); ?>"></button>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

</section>
