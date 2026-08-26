<?php
/**
 * News — đầu bài: chuyên mục, H1, lead, meta (tác giả · ngày · thời gian đọc), ảnh đại diện.
 * Nội dung động WP; không hardcode. Dùng trong Loop bởi single.php.
 *
 * @owner   Session E
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_cats      = get_the_category();
$pm_cat       = ! empty( $pm_cats ) ? $pm_cats[0] : null;
$pm_author_id = (int) get_the_author_meta( 'ID' );
?>
<header class="pm-news__head">

	<?php if ( $pm_cat ) : ?>
		<a class="pm-news__cat" href="<?php echo esc_url( get_category_link( $pm_cat->term_id ) ); ?>">
			<?php echo esc_html( $pm_cat->name ); ?>
		</a>
	<?php endif; ?>

	<h1 class="pm-news__title"><?php the_title(); ?></h1>

	<?php if ( has_excerpt() ) : ?>
		<p class="pm-news__lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
	<?php endif; ?>

	<div class="pm-news__meta">
		<div class="pm-news__author">
			<?php
			$pm_avatar = get_avatar(
				$pm_author_id,
				44,
				'',
				get_the_author(),
				array( 'class' => 'pm-news__avatar' )
			);
			if ( $pm_avatar ) {
				echo $pm_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- get_avatar() trả markup an toàn.
			}
			?>
			<span class="pm-news__author-info">
				<span class="pm-news__author-name"><?php the_author(); ?></span>
				<?php
				$pm_role = trim( (string) get_the_author_meta( 'description' ) );
				if ( '' !== $pm_role ) :
					?>
					<span class="pm-news__author-role"><?php echo esc_html( wp_trim_words( $pm_role, 7, '' ) ); ?></span>
				<?php endif; ?>
			</span>
		</div>

		<span class="pm-news__meta-sep" aria-hidden="true"></span>

		<span class="pm-news__meta-item">
			<?php echo pm_icon( 'clock', array( 'width' => 16, 'height' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
		</span>

		<?php if ( function_exists( 'pm_reading_time' ) ) : ?>
			<span class="pm-news__meta-item pm-news__rt"><?php echo esc_html( pm_reading_time( get_the_ID() ) ); ?></span>
		<?php endif; ?>
	</div>
</header>

<?php if ( has_post_thumbnail() ) : ?>
	<figure class="pm-news__figure">
		<?php
		the_post_thumbnail(
			'large',
			array(
				'class'         => 'pm-news__hero',
				'loading'       => 'eager',
				'fetchpriority' => 'high',
				'alt'           => the_title_attribute( array( 'echo' => false ) ),
			)
		);
		$pm_caption = wp_get_attachment_caption( get_post_thumbnail_id() );
		if ( $pm_caption ) :
			?>
			<figcaption class="pm-news__figcap"><?php echo esc_html( $pm_caption ); ?></figcaption>
		<?php endif; ?>
	</figure>
<?php endif; ?>
