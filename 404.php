<?php
/**
 * 404 — không tìm thấy trang. Ô tìm kiếm + CTA Gọi/Về trang chủ (dữ liệu động).
 * Chỉ dùng class base/components (tải mọi trang); vài inline nhỏ cho số 404.
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main pm-container pm-404">

	<?php pm_the_breadcrumb(); ?>

	<section class="pm-none pm-404__inner">

		<p class="pm-404__code" aria-hidden="true" style="font-size:clamp(72px,16vw,132px);font-weight:800;line-height:1;color:var(--pm-brand-soft);margin:0 0 6px;letter-spacing:.02em">404</p>

		<h1 class="pm-none__title"><?php esc_html_e( 'Không tìm thấy trang', 'prometal' ); ?></h1>
		<p class="pm-none__text"><?php esc_html_e( 'Rất tiếc, trang bạn tìm có thể đã được di chuyển hoặc không còn tồn tại. Hãy thử tìm kiếm hoặc quay lại trang chủ.', 'prometal' ); ?></p>

		<?php get_search_form(); ?>

		<p style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;margin-top:20px">
			<a class="pm-btn pm-btn--ghost" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Về trang chủ', 'prometal' ); ?></a>
			<a class="pm-btn pm-btn--call" href="<?php echo esc_url( pm_phone_link() ); ?>">
				<?php echo pm_icon( 'phone', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php echo esc_html( sprintf( /* translators: %s: hotline. */ __( 'Gọi %s', 'prometal' ), pm_phone() ) ); ?></span>
			</a>
		</p>

	</section>

</main>
<?php
get_footer();
