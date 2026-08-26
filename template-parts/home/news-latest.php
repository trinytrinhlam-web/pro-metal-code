<?php
/**
 * Section 9 — Tin tức · Thông tin (3 bài mới nhất).
 *
 * Lấy 3 post mới nhất; dùng lại component .pm-card / .pm-post-card của Session B.
 * Không có bài nào → ẩn cả section.
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_news_q = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $pm_news_q->have_posts() ) {
	wp_reset_postdata();
	return; // Chưa có bài → bỏ qua section (tránh khối rỗng).
}

$pm_blog_id  = (int) get_option( 'page_for_posts' );
$pm_blog_url = $pm_blog_id ? get_permalink( $pm_blog_id ) : home_url( '/tin-tuc/' );
?>
<section class="pm-section pm-news">
	<div class="pm-container">

		<div class="pm-h2wrap">
			<h2><?php esc_html_e( 'Tin tức – Thông tin', 'prometal' ); ?></h2>
		</div>

		<div class="pm-cards pm-cards--3">
			<?php
			while ( $pm_news_q->have_posts() ) :
				$pm_news_q->the_post();
				?>
				<article <?php post_class( 'pm-card pm-card--link pm-post-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a class="pm-card__media pm-post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php the_post_thumbnail( 'pm-card', array( 'loading' => 'lazy' ) ); ?>
						</a>
					<?php else : ?>
						<a class="pm-card__media pm-post-card__media pm-ph" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php esc_html_e( 'Ảnh bài viết', 'prometal' ); ?>
						</a>
					<?php endif; ?>

					<div class="pm-card__body pm-post-card__body">
						<div class="pm-post-card__meta">
							<span><?php echo esc_html( get_the_author() ); ?></span>
							<span aria-hidden="true">•</span>
							<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
						</div>
						<h3 class="pm-post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="pm-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></p>
						<a class="pm-link-more pm-post-card__more" href="<?php the_permalink(); ?>">
							<?php esc_html_e( 'Đọc tiếp', 'prometal' ); ?>
							<?php echo pm_icon( 'chevron-right', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</article>
			<?php endwhile; ?>
		</div>

		<div class="pm-news__all">
			<a class="pm-btn pm-btn--ghost" href="<?php echo esc_url( $pm_blog_url ); ?>">
				<?php esc_html_e( 'Xem tất cả tin tức', 'prometal' ); ?>
			</a>
		</div>

	</div>
</section>
<?php
wp_reset_postdata();
