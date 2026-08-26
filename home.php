<?php
/**
 * Danh sách Tin tức (blog index — trang "Bài viết").
 * Lưới thẻ .pm-post-card (Session B) trong .pm-cards (components.css — tải mọi trang).
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pm_blog_title = get_the_title( (int) get_option( 'page_for_posts' ) );
if ( '' === $pm_blog_title ) {
	$pm_blog_title = __( 'Tin tức', 'prometal' );
}
?>
<main id="main" class="site-main pm-container pm-blog">

	<?php pm_the_breadcrumb(); ?>

	<div class="pm-h2wrap pm-h2wrap--left pm-blog__head">
		<span class="pm-h2wrap__eyebrow"><?php esc_html_e( 'Tin tức & Kiến thức', 'prometal' ); ?></span>
		<h1><?php echo esc_html( $pm_blog_title ); ?></h1>
		<p class="pm-h2wrap__lead"><?php esc_html_e( 'Kinh nghiệm thi công, sửa chữa cửa sắt, kết cấu thép và tin tức mới nhất từ đội ngũ Pro-Metal.', 'prometal' ); ?></p>
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
				'screen_reader_text' => esc_html__( 'Điều hướng danh sách bài viết', 'prometal' ),
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
