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
 * Danh sách slide hero (inc/media.php): url + url_m (bản cắt cho điện thoại) + alt.
 * Ảnh gốc lọc được qua 'prometal_hero_slides'; slide thiếu file ảnh bị bỏ qua.
 */
$pm_slides     = prometal_hero_slides_resolved();
$pm_has_slides = ! empty( $pm_slides );
$pm_hero_media = prometal_hero_mobile_media();
?>
<section class="pm-hero" aria-label="<?php esc_attr_e( 'Giới thiệu Pro-Metal', 'prometal' ); ?>"<?php echo $pm_has_slides ? ' data-pm-hero' : ''; ?>>

	<?php if ( $pm_has_slides ) : ?>
		<div class="pm-hero__slides" aria-hidden="true">
			<?php foreach ( $pm_slides as $pm_i => $pm_slide ) : ?>
				<?php if ( 0 === $pm_i ) : ?>
					<div class="pm-hero__slide is-active" role="img" aria-label="<?php echo esc_attr( $pm_slide['alt'] ); ?>">
						<picture>
							<?php if ( $pm_slide['url_m'] ) : ?>
								<source media="<?php echo esc_attr( $pm_hero_media ); ?>" srcset="<?php echo esc_url( $pm_slide['url_m'] ); ?>">
							<?php endif; ?>
							<img class="pm-hero__image" src="<?php echo esc_url( $pm_slide['url'] ); ?>" alt="" width="1600" height="900" fetchpriority="high" decoding="async">
						</picture>
					</div>
				<?php else : ?>
					<div class="pm-hero__slide" role="img" aria-label="<?php echo esc_attr( $pm_slide['alt'] ); ?>" data-pm-hero-src="<?php echo esc_url( $pm_slide['url'] ); ?>"<?php echo $pm_slide['url_m'] ? ' data-pm-hero-src-m="' . esc_url( $pm_slide['url_m'] ) . '" data-pm-hero-media="' . esc_attr( $pm_hero_media ) . '"' : ''; ?>></div>
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
