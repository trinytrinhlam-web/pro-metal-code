<?php
/**
 * Lưu trữ — chuyên mục / thẻ / tác giả / ngày. Lưới thẻ bài .pm-post-card.
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main pm-container pm-blog pm-archive">

	<?php pm_the_breadcrumb(); ?>

	<div class="pm-h2wrap pm-h2wrap--left pm-blog__head">
		<span class="pm-h2wrap__eyebrow">
			<?php
			if ( is_category() || is_tag() || is_tax() ) {
				esc_html_e( 'Chuyên mục', 'prometal' );
			} elseif ( is_author() ) {
				esc_html_e( 'Tác giả', 'prometal' );
			} else {
				esc_html_e( 'Lưu trữ', 'prometal' );
			}
			?>
		</span>
		<?php the_archive_title( '<h1>', '</h1>' ); ?>
		<?php the_archive_description( '<div class="pm-h2wrap__lead pm-prose">', '</div>' ); ?>
	</div>

	<?php if ( have_posts() ) : ?>

		<div class="pm-cards pm-cards--3 pm-blog__list">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination(
			array(
				'mid_size'           => 1,
				'prev_text'          => esc_html__( '← Trước', 'prometal' ),
				'next_text'          => esc_html__( 'Sau →', 'prometal' ),
				'screen_reader_text' => esc_html__( 'Điều hướng lưu trữ', 'prometal' ),
				'class'              => 'pm-pagination',
			)
		);
		?>

	<?php else : ?>
		<?php get_template_part( 'template-parts/content', 'none' ); ?>
	<?php endif; ?>

</main>
<?php
get_footer();
