<?php
/**
 * Section 2 — Giới thiệu thương hiệu + slogan.
 *
 * Cột trái: giới thiệu ngắn + slogan (pm_option). Cột phải: lưới 4 ảnh công
 * trình — ưu tiên ảnh đại diện CPT pm_project (nếu có), thiếu thì dùng ảnh
 * công trình thật bundle sẵn trong theme (assets/img/intro-*.jpg).
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Lấy tối đa 4 công trình mới nhất có ảnh đại diện cho lưới minh hoạ.
$pm_intro_q = new WP_Query(
	array(
		'post_type'           => 'pm_project',
		'posts_per_page'      => 4,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'meta_query'          => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'     => '_thumbnail_id',
				'compare' => 'EXISTS',
			),
		),
	)
);
?>
<section class="pm-section pm-intro">
	<div class="pm-container pm-intro__grid">

		<div class="pm-intro__text">
			<span class="pm-h2wrap__eyebrow"><?php esc_html_e( 'Cơ khí Pro-Metal', 'prometal' ); ?></span>
			<h2 class="pm-intro__title"><?php esc_html_e( 'Cơ khí Pro-Metal', 'prometal' ); ?></h2>
			<span class="pm-intro__bar" aria-hidden="true"></span>
			<p class="pm-intro__desc">
				<?php esc_html_e( 'Cơ khí Pro-Metal chuyên thi công cửa sắt, lan can, cầu thang, mái hiên… tại nhà chuyên nghiệp, chất lượng đạt chuẩn, giá hợp lý, bảo hành đầy đủ. Chúng tôi cam kết mang tới ngôi nhà bạn nét chấm phá hiện đại từ các công trình SẮT, NHÔM, INOX.', 'prometal' ); ?>
			</p>
			<?php $pm_slogan = pm_option( 'slogan' ); ?>
			<?php if ( $pm_slogan ) : ?>
				<span class="pm-intro__slogan"><?php echo esc_html( $pm_slogan ); ?></span>
			<?php endif; ?>
		</div>

		<div class="pm-intro__imgs">
			<?php if ( $pm_intro_q->have_posts() ) : ?>
				<?php
				while ( $pm_intro_q->have_posts() ) :
					$pm_intro_q->the_post();
					?>
					<a class="pm-intro__img" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'pm-thumb', array( 'alt' => the_title_attribute( array( 'echo' => false ) ), 'loading' => 'lazy' ) ); ?>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<?php
				// Chưa có CPT pm_project → dùng 4 ảnh công trình thật bundle trong theme.
				$pm_intro_imgs = array(
					array( 'file' => 'assets/img/intro-1.jpg', 'alt' => __( 'Cửa cổng sắt hoa văn cắt CNC', 'prometal' ) ),
					array( 'file' => 'assets/img/intro-2.jpg', 'alt' => __( 'Cửa sắt khung kính 4 cánh', 'prometal' ) ),
					array( 'file' => 'assets/img/intro-3.jpg', 'alt' => __( 'Cổng xếp inox nhà xưởng', 'prometal' ) ),
					array( 'file' => 'assets/img/intro-4.jpg', 'alt' => __( 'Vách ngăn nhôm kính văn phòng', 'prometal' ) ),
				);
				foreach ( $pm_intro_imgs as $pm_im ) :
					?>
					<div class="pm-intro__img">
						<img src="<?php echo esc_url( get_theme_file_uri( $pm_im['file'] ) ); ?>" alt="<?php echo esc_attr( $pm_im['alt'] ); ?>" loading="lazy">
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</div>
</section>
