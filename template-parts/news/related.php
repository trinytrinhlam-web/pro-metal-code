<?php
/**
 * News — bài viết liên quan (cùng chuyên mục, loại trừ bài hiện tại).
 * Không đủ bài cùng chuyên mục → fallback bài mới nhất. Luôn wp_reset_postdata().
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_current  = get_the_ID();
$pm_cat_ids  = wp_get_post_categories( $pm_current );

$pm_args = array(
	'post__not_in'        => array( $pm_current ),
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'orderby'             => 'date',
	'order'               => 'DESC',
);
if ( ! empty( $pm_cat_ids ) ) {
	$pm_args['category__in'] = $pm_cat_ids;
}

$pm_related = new WP_Query( $pm_args );

// Không có bài cùng chuyên mục → lấy bài mới nhất bất kỳ.
if ( ! $pm_related->have_posts() && ! empty( $pm_cat_ids ) ) {
	unset( $pm_args['category__in'] );
	$pm_related = new WP_Query( $pm_args );
}

if ( $pm_related->have_posts() ) :
	?>
	<div class="pm-side-card pm-related">
		<h2 class="pm-side-card__title"><?php esc_html_e( 'Bài viết liên quan', 'prometal' ); ?></h2>
		<div class="pm-related__list">
			<?php
			while ( $pm_related->have_posts() ) :
				$pm_related->the_post();
				?>
				<a class="pm-related__item" href="<?php the_permalink(); ?>">
					<?php if ( has_post_thumbnail() ) : ?>
						<span class="pm-related__thumb">
							<?php
							the_post_thumbnail(
								'thumbnail',
								array(
									'loading' => 'lazy',
									'alt'     => the_title_attribute( array( 'echo' => false ) ),
								)
							);
							?>
						</span>
					<?php endif; ?>
					<span class="pm-related__meta">
						<span class="pm-related__title"><?php the_title(); ?></span>
						<time class="pm-related__date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</span>
				</a>
				<?php
			endwhile;
			?>
		</div>
	</div>
	<?php
endif;

wp_reset_postdata();
