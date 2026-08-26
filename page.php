<?php
/**
 * Template trang tĩnh.
 *
 *  • Trang thường: breadcrumb + tiêu đề + nội dung trong .pm-container/.pm-prose.
 *  • Trang landing nhập khẩu (nội dung tự dựng bọc trong .pm-lp, kèm <style> +
 *    layout riêng): render FULL-BLEED — bỏ container/prose/tiêu đề trùng/wpautop
 *    để giữ đúng thiết kế, và ép nội dung dùng font thương hiệu (thay serif Tinos).
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// Nội dung có phải landing nhập khẩu (wrapper .pm-lp) không?
	$pm_is_canvas = ( false !== strpos( (string) get_the_content(), 'pm-lp' ) );

	if ( $pm_is_canvas ) :
		remove_filter( 'the_content', 'wpautop' ); // HTML đã tự dựng — không để wpautop chèn <p> rác.
		?>
		<style>
		/* Ép nội dung landing về font thương hiệu (thay serif Tinos) — KHÔNG dùng
		   selector "*" để tránh đè font-family của icon-font (Material/FA tự đặt). */
		.pm-canvas .pm-lp,.pm-canvas .pm-lightbox-cap{font-family:var(--pm-font)!important}
		</style>
		<main id="main" class="site-main pm-canvas">
			<article <?php post_class( 'pm-canvas__article' ); ?>>
				<?php the_content(); ?>
			</article>
		</main>
		<?php
	else :
		?>
		<main id="main" class="site-main pm-container pm-page">
			<?php pm_the_breadcrumb(); ?>

			<article <?php post_class( 'pm-page__article' ); ?>>
				<header class="pm-page__header">
					<h1 class="pm-page__title"><?php the_title(); ?></h1>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="pm-page__thumb">
						<?php the_post_thumbnail( 'pm-hero', array( 'loading' => 'lazy' ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="pm-page__content pm-prose">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<nav class="pm-pagelinks" aria-label="' . esc_attr__( 'Trang con', 'prometal' ) . '">',
							'after'  => '</nav>',
						)
					);
					?>
				</div>
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</main>
		<?php
	endif;

endwhile;

get_footer();
