<?php
/**
 * Chi tiết bài viết — News detail editorial (bố cục theo FRAME 03).
 * 2 cột: bài viết (~780px) + sidebar (320px). Header/footer dùng chung.
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="main" class="site-main pm-container pm-news">

	<?php pm_the_breadcrumb(); ?>

	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="pm-news__grid">

			<article <?php post_class( 'pm-news__article' ); ?>>

				<?php get_template_part( 'template-parts/news/article-header' ); ?>

				<div class="pm-news__body pm-prose">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<nav class="pm-pagelinks" aria-label="' . esc_attr__( 'Trang bài viết', 'prometal' ) . '"><span>' . esc_html__( 'Trang:', 'prometal' ) . '</span> ',
							'after'  => '</nav>',
						)
					);
					?>
				</div>

				<footer class="pm-news__foot">

					<?php
					$pm_tags = get_the_tags();
					if ( ! empty( $pm_tags ) ) :
						?>
						<div class="pm-news__tags">
							<span class="pm-news__tags-label"><?php esc_html_e( 'Tags:', 'prometal' ); ?></span>
							<?php foreach ( $pm_tags as $pm_tag ) : ?>
								<a class="pm-pill pm-pill--sm" href="<?php echo esc_url( get_tag_link( $pm_tag->term_id ) ); ?>">
									#<?php echo esc_html( $pm_tag->name ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php
					$pm_share_url   = rawurlencode( get_permalink() );
					$pm_share_title = rawurlencode( get_the_title() );
					?>
					<div class="pm-news__share">
						<span class="pm-news__share-label"><?php esc_html_e( 'Chia sẻ:', 'prometal' ); ?></span>
						<a class="pm-share-btn" href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . $pm_share_url ); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php esc_attr_e( 'Chia sẻ lên Facebook', 'prometal' ); ?>">
							<?php echo pm_icon( 'facebook', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<a class="pm-share-btn" href="<?php echo esc_url( 'https://zalo.me/share/link?u=' . $pm_share_url . '&t=' . $pm_share_title ); ?>" target="_blank" rel="noopener nofollow" aria-label="<?php esc_attr_e( 'Chia sẻ qua Zalo', 'prometal' ); ?>">
							<?php echo pm_icon( 'zalo', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<a class="pm-share-btn" href="<?php echo esc_url( 'mailto:?subject=' . $pm_share_title . '&body=' . $pm_share_url ); ?>" aria-label="<?php esc_attr_e( 'Chia sẻ qua email', 'prometal' ); ?>">
							<?php echo pm_icon( 'mail', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</footer>

			</article>

			<?php get_sidebar(); ?>

		</div>

		<?php
		if ( comments_open() || get_comments_number() ) :
			?>
			<div class="pm-news__comments">
				<?php comments_template(); ?>
			</div>
			<?php
		endif;
		?>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
