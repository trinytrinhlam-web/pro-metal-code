<?php
/**
 * Section 8 — Khách hàng đánh giá Pro-Metal.
 *
 * Nguồn dữ liệu: CPT pm_testimonial (Session A đăng ký).
 *   • Nội dung bài  = câu đánh giá (quote).
 *   • Tiêu đề       = tên khách hàng.
 *   • Trích dẫn     = khu vực (VD "Q. Gò Vấp") — không bắt buộc.
 *   • Ảnh đại diện  = ảnh nền thẻ — không bắt buộc.
 * Chưa có dữ liệu → hiện 3 đánh giá mẫu (khớp demo) với ảnh nền công trình thật
 * bundle trong theme. Slider ở slider.js (hook [data-pm-slider]); tự ẩn nút điều
 * hướng nếu vừa khung.
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_tm_q = new WP_Query(
	array(
		'post_type'           => 'pm_testimonial',
		'posts_per_page'      => 12,
		'post_status'         => 'publish',
		'orderby'             => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

// Gom dữ liệu thẻ (CPT thật → mảng chuẩn; rỗng → dữ liệu mẫu).
$pm_tm_items = array();

if ( $pm_tm_q->have_posts() ) {
	while ( $pm_tm_q->have_posts() ) {
		$pm_tm_q->the_post();
		$pm_quote = wp_strip_all_tags( get_the_content() );
		if ( '' === trim( $pm_quote ) ) {
			$pm_quote = wp_strip_all_tags( get_the_excerpt() );
		}
		$pm_tm_items[] = array(
			'quote'  => wp_trim_words( $pm_quote, 45, '…' ),
			'name'   => get_the_title(),
			'place'  => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '',
			'img'    => '',
			'img_id' => (int) get_post_thumbnail_id(),
		);
	}
	wp_reset_postdata();
} else {
	$pm_tm_items = array(
		array(
			'quote'  => __( 'Pro-Metal tư vấn vật liệu tốt, thi công đúng tiến độ và rất cẩn thận. Tôi rất hài lòng về chất lượng công trình.', 'prometal' ),
			'name'   => __( 'Anh Trường', 'prometal' ),
			'place'  => __( 'Q. Gò Vấp', 'prometal' ),
			'img'    => 'assets/img/sat-nhaxuong.jpg',
			'img_id' => 0,
		),
		array(
			'quote'  => __( 'Anh em thợ rất chuyên nghiệp và tận tâm. Từ thi công đến dọn dẹp vệ sinh sau khi xong đều gọn gàng.', 'prometal' ),
			'name'   => __( 'Chị Hoa', 'prometal' ),
			'place'  => __( 'Q. Bình Thạnh', 'prometal' ),
			'img'    => 'assets/img/inox-cauthang.jpg',
			'img_id' => 0,
		),
		array(
			'quote'  => __( 'Chọn Pro-Metal thi công lan can, cầu thang cho dự án lớn, được giám sát chặt chẽ. Rất yên tâm.', 'prometal' ),
			'name'   => __( 'Cty PNJ', 'prometal' ),
			'place'  => __( 'Bình Thạnh', 'prometal' ),
			'img'    => 'assets/img/nhom-kinh.jpg',
			'img_id' => 0,
		),
	);
}
?>
<section class="pm-section pm-section--alt pm-testi">
	<div class="pm-container">

		<div class="pm-h2wrap">
			<h2><?php esc_html_e( 'Khách hàng đánh giá Pro-Metal', 'prometal' ); ?></h2>
		</div>

		<div class="pm-tm-slider" data-pm-slider>
			<button type="button" class="pm-tm-slider__arrow pm-tm-slider__arrow--prev" data-pm-slider-prev aria-label="<?php esc_attr_e( 'Đánh giá trước', 'prometal' ); ?>">
				<?php echo pm_icon( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>

			<div class="pm-tm-slider__viewport">
				<ul class="pm-tm-slider__track">
					<?php foreach ( $pm_tm_items as $pm_tm ) : ?>
						<li class="pm-tm">
							<?php
							// Ảnh nền thẻ = <img> lazy (thay background-image inline luôn tải ngay).
							$pm_tm_sizes = '(max-width: 720px) calc(100vw - 32px), (max-width: 1024px) calc(50vw - 27px), 378px';
							if ( $pm_tm['img_id'] ) {
								echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									$pm_tm['img_id'],
									'pm-card',
									false,
									array(
										'class'   => 'pm-tm__bg',
										'alt'     => '',
										'loading' => 'lazy',
										'sizes'   => $pm_tm_sizes,
									)
								);
							} elseif ( $pm_tm['img'] ) {
								echo pm_theme_img( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- đã escape trong helper.
									$pm_tm['img'],
									array(
										'class'  => 'pm-tm__bg',
										'widths' => array( 480, 800 ),
										'sizes'  => $pm_tm_sizes,
									)
								);
							} else {
								echo '<span class="pm-tm__bg pm-tm__bg--ph" aria-hidden="true"></span>';
							}
							?>
							<figure class="pm-tm__body">
								<blockquote class="pm-tm__quote"><?php echo esc_html( $pm_tm['quote'] ); ?></blockquote>
								<figcaption class="pm-tm__name">
									<?php echo esc_html( $pm_tm['name'] ); ?>
									<?php if ( $pm_tm['place'] ) : ?>
										<span>— <?php echo esc_html( $pm_tm['place'] ); ?></span>
									<?php endif; ?>
								</figcaption>
							</figure>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<button type="button" class="pm-tm-slider__arrow pm-tm-slider__arrow--next" data-pm-slider-next aria-label="<?php esc_attr_e( 'Đánh giá sau', 'prometal' ); ?>">
				<?php echo pm_icon( 'chevron-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>

			<div class="pm-tm-slider__dots" data-pm-slider-dots role="tablist" aria-label="<?php esc_attr_e( 'Trang đánh giá', 'prometal' ); ?>"></div>
		</div>

	</div>
</section>
