<?php
/**
 * Landing block — Hero dịch vụ (nền tối, H1 TRẮNG).
 * Layout Carbon Fields: `hero`. Dữ liệu qua $args; rỗng → fallback LP11 +
 * ảnh nhà xưởng thật bundle trong theme (assets/img/lp-hero.jpg).
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pm_b = ( isset( $args ) && is_array( $args ) ) ? $args : array();

$pm_heading = prometal_lp_get( $pm_b, 'heading', __( 'Thi Công Vách Ngăn Panel Tại TP.HCM', 'prometal' ) );
$pm_sub     = prometal_lp_get( $pm_b, 'sub', __( 'Pro-Metal nhận làm vách ngăn panel cho nhà xưởng, văn phòng trong xưởng, phòng trọ, kho mát/khu phụ trợ và các mặt bằng cần chia phòng nhanh, sạch, ít đập phá. Gửi bản vẽ hoặc kích thước qua Zalo để chúng tôi tư vấn loại panel phù hợp.', 'prometal' ) );

$pm_usp = prometal_lp_get( $pm_b, 'usp', array() );
if ( empty( $pm_usp ) ) {
	$pm_usp = array(
		array( 'text' => __( 'Lắp nhanh, gọn, hạn chế bụi và thi công ướt', 'prometal' ) ),
		array( 'text' => __( 'Tư vấn EPS, PU, Glasswool/Rockwool theo đúng nhu cầu', 'prometal' ) ),
		array( 'text' => __( 'Báo giá theo bản vẽ/kích thước, rõ hạng mục trước khi làm', 'prometal' ) ),
		array( 'text' => __( 'Nhận khảo sát tại TP.HCM, bàn giao gọn và có bảo hành', 'prometal' ) ),
	);
}

$pm_img = prometal_lp_get( $pm_b, 'image', '' );
?>
<section class="pm-lp-hero">
	<div class="pm-container pm-lp-hero__grid">

		<div class="pm-lp-hero__body">
			<h1 class="pm-lp-hero__title"><?php echo esc_html( $pm_heading ); ?></h1>
			<p class="pm-lp-hero__sub"><?php echo esc_html( $pm_sub ); ?></p>

			<?php if ( ! empty( $pm_usp ) ) : ?>
				<ul class="pm-lp-hero__usp">
					<?php foreach ( $pm_usp as $pm_u ) : ?>
						<?php $pm_t = prometal_lp_get( $pm_u, 'text', '' ); ?>
						<?php if ( '' !== $pm_t ) : ?>
							<li>
								<?php echo pm_icon( 'check', array( 'width' => 22, 'height' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span><?php echo esc_html( $pm_t ); ?></span>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<div class="pm-lp-hero__btns">
				<a class="pm-btn pm-btn--call pm-btn--lg" href="<?php echo esc_url( pm_phone_link() ); ?>">
					<?php echo pm_icon( 'phone', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php printf( esc_html__( 'Gọi %s', 'prometal' ), esc_html( pm_phone() ) ); ?></span>
				</a>
				<a class="pm-btn pm-btn--zalo pm-btn--lg" href="<?php echo esc_url( pm_zalo_link() ); ?>" target="_blank" rel="nofollow noopener">
					<?php echo pm_icon( 'zalo', array( 'width' => 18, 'height' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php esc_html_e( 'Gửi bản vẽ qua Zalo', 'prometal' ); ?></span>
				</a>
			</div>
		</div>

		<div class="pm-lp-hero__media">
			<?php
			$pm_hero_img = prometal_lp_img( $pm_img, $pm_heading, 'pm-lp-hero__img', 'pm-hero' );
			if ( '' !== $pm_hero_img ) {
				echo $pm_hero_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				echo '<img src="' . esc_url( get_theme_file_uri( 'assets/img/lp-hero.jpg' ) ) . '" alt="' . esc_attr( $pm_heading ) . '" class="pm-lp-hero__img">';
			}
			?>
		</div>

	</div>
</section>
