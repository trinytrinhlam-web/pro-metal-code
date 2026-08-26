<?php
/**
 * Fallback template (blog index / archive / search).
 * Gọi template-parts/content (Session B) trong vòng lặp.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main pm-container pm-archive">

	<?php pm_the_breadcrumb(); ?>

	<header class="pm-archive__header">
		<?php if ( is_home() && ! is_front_page() ) : ?>
			<h1 class="pm-archive__title"><?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: __( 'Tin tức', 'prometal' ) ); ?></h1>
		<?php elseif ( is_search() ) : ?>
			<h1 class="pm-archive__title">
				<?php
				/* translators: %s: search query. */
				printf( esc_html__( 'Kết quả tìm kiếm: %s', 'prometal' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
				?>
			</h1>
		<?php elseif ( is_archive() ) : ?>
			<?php the_archive_title( '<h1 class="pm-archive__title">', '</h1>' ); ?>
			<?php the_archive_description( '<div class="pm-archive__desc pm-prose">', '</div>' ); ?>
		<?php else : ?>
			<h1 class="pm-archive__title"><?php bloginfo( 'name' ); ?></h1>
		<?php endif; ?>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="pm-archive__list">
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
				'screen_reader_text' => esc_html__( 'Điều hướng trang', 'prometal' ),
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
