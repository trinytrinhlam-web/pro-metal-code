<?php
/**
 * Kết quả tìm kiếm — lưới thẻ bài; rỗng → content-none (có ô tìm lại).
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pm_query = get_search_query();
$pm_found = (int) $GLOBALS['wp_query']->found_posts;
?>
<main id="main" class="site-main pm-container pm-blog pm-search">

	<?php pm_the_breadcrumb(); ?>

	<div class="pm-h2wrap pm-h2wrap--left pm-blog__head">
		<span class="pm-h2wrap__eyebrow"><?php esc_html_e( 'Tìm kiếm', 'prometal' ); ?></span>
		<h1>
			<?php
			/* translators: %s: từ khoá tìm kiếm. */
			printf( esc_html__( 'Kết quả cho: %s', 'prometal' ), '“' . esc_html( $pm_query ) . '”' );
			?>
		</h1>
		<?php if ( have_posts() ) : ?>
			<p class="pm-h2wrap__lead">
				<?php
				/* translators: %s: số lượng kết quả. */
				printf( esc_html( _n( 'Tìm thấy %s bài viết phù hợp.', 'Tìm thấy %s bài viết phù hợp.', $pm_found, 'prometal' ) ), esc_html( number_format_i18n( $pm_found ) ) );
				?>
			</p>
		<?php endif; ?>
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
				'screen_reader_text' => esc_html__( 'Điều hướng kết quả', 'prometal' ),
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
