<?php
/**
 * Item 1 bài trong danh sách (blog/archive/search) — thẻ .pm-post-card tái dùng.
 * Dùng trong Loop bởi home.php / archive.php / search.php (Session E READ).
 *
 * @owner   Session B
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_cats = get_the_category();
$pm_cat  = ! empty( $pm_cats ) ? $pm_cats[0] : null;
?>
<article <?php post_class( 'pm-post-card pm-card pm-card--link' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a class="pm-post-card__media pm-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php
			the_post_thumbnail(
				'large',
				array(
					'loading' => 'lazy',
					'alt'     => the_title_attribute( array( 'echo' => false ) ),
				)
			);
			?>
		</a>
	<?php endif; ?>

	<div class="pm-post-card__body">
		<div class="pm-post-card__meta">
			<?php if ( $pm_cat ) : ?>
				<a class="pm-pill pm-pill--sm" href="<?php echo esc_url( get_category_link( $pm_cat->term_id ) ); ?>">
					<?php echo esc_html( $pm_cat->name ); ?>
				</a>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo pm_icon( 'clock', array( 'width' => 14, 'height' => 14 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo esc_html( get_the_date() ); ?>
			</time>
			<?php if ( function_exists( 'pm_reading_time' ) ) : ?>
				<span class="pm-post-card__rt"><?php echo esc_html( pm_reading_time( get_the_ID() ) ); ?></span>
			<?php endif; ?>
		</div>

		<?php the_title( '<h2 class="pm-post-card__title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

		<p class="pm-post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>

		<a class="pm-link-more pm-post-card__more" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Đọc tiếp', 'prometal' ); ?>
			<?php echo pm_icon( 'chevron-right', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
	</div>

</article>
