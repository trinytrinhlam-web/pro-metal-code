<?php
/**
 * Landing block — Thư viện ảnh (masonry) + lightbox.
 * Dùng .pm-gallery (hợp đồng Session B → lightbox.js tự bắt các <a href="ảnh">).
 * Layout Carbon Fields: `gallery`. Rỗng → 6 ảnh công trình thật bundle trong
 * theme (assets/img/lp-gal-*.jpg), có lightbox như ảnh admin upload.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b       = ( isset( $args ) && is_array( $args ) ) ? $args : array();
$pm_id      = prometal_lp_section_id( 'gallery' );
$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Ảnh thực tế vật tư và công trình', 'prometal' ) );
$pm_lead    = prometal_lp_get( $pm_b, 'lead', __( 'Anh chị có thể gửi thêm ảnh mặt bằng hiện trạng qua Zalo. Chúng tôi dựa trên kích thước, vị trí lắp và vật tư thực tế để tư vấn phương án phù hợp.', 'prometal' ) );

$pm_images = prometal_lp_get( $pm_b, 'images', array() );

// Fallback: chưa có ảnh → 6 ảnh công trình thật bundle trong theme.
$pm_ph_items = array(
	array( 'file' => 'assets/img/lp-gal-1.jpg', 'cap' => __( 'Kết cấu thép nhà xưởng', 'prometal' ) ),
	array( 'file' => 'assets/img/lp-gal-2.jpg', 'cap' => __( 'Hệ khung sàn thép mạ kẽm', 'prometal' ) ),
	array( 'file' => 'assets/img/lp-gal-3.jpg', 'cap' => __( 'Tập kết vật tư tại công trình', 'prometal' ) ),
	array( 'file' => 'assets/img/lp-gal-4.jpg', 'cap' => __( 'Thi công khung mái, mái che', 'prometal' ) ),
	array( 'file' => 'assets/img/lp-gal-5.jpg', 'cap' => __( 'Xử lý mái tôn, chống dột', 'prometal' ) ),
	array( 'file' => 'assets/img/lp-gal-6.jpg', 'cap' => __( 'Gia công tại xưởng cơ khí', 'prometal' ) ),
);
?>
<section class="pm-section pm-lp-gallery-sec"<?php echo $pm_id ? ' id="' . esc_attr( $pm_id ) . '"' : ''; ?>>
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--left">
			<h2><?php echo esc_html( $pm_heading ); ?></h2>
			<?php if ( '' !== $pm_lead ) : ?>
				<p class="pm-h2wrap__lead"><?php echo esc_html( $pm_lead ); ?></p>
			<?php endif; ?>
		</div>

		<div class="pm-gallery pm-lp-gallery">
			<?php if ( ! empty( $pm_images ) ) : ?>
				<?php foreach ( $pm_images as $pm_im ) : ?>
					<?php
					$pm_id_img = prometal_lp_get( $pm_im, 'image', '' );
					$pm_cap    = prometal_lp_get( $pm_im, 'caption', '' );
					if ( empty( $pm_id_img ) ) {
						continue;
					}
					$pm_full  = is_numeric( $pm_id_img ) ? wp_get_attachment_image_url( (int) $pm_id_img, 'full' ) : (string) $pm_id_img;
					$pm_thumb = prometal_lp_img( $pm_id_img, $pm_cap, '', 'pm-gallery' );
					if ( ! $pm_full || '' === $pm_thumb ) {
						continue;
					}
					?>
					<figure>
						<a href="<?php echo esc_url( $pm_full ); ?>" data-caption="<?php echo esc_attr( $pm_cap ); ?>">
							<?php echo $pm_thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<?php if ( '' !== $pm_cap ) : ?>
							<figcaption><?php echo esc_html( $pm_cap ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			<?php else : ?>
				<?php foreach ( $pm_ph_items as $pm_it ) : ?>
					<?php $pm_src = get_theme_file_uri( $pm_it['file'] ); ?>
					<figure>
						<a href="<?php echo esc_url( $pm_src ); ?>" data-caption="<?php echo esc_attr( $pm_it['cap'] ); ?>">
							<img src="<?php echo esc_url( $pm_src ); ?>" alt="<?php echo esc_attr( $pm_it['cap'] ); ?>" loading="lazy">
						</a>
						<figcaption><?php echo esc_html( $pm_it['cap'] ); ?></figcaption>
					</figure>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>

	</div>
</section>
