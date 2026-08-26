<?php
/**
 * Section 3 — 4 cam kết (look "tech": nền tối + viền cyan).
 *
 * Giữ tinh thần công nghệ như demo đã duyệt NHƯNG bản tối ưu nhẹ:
 * nền gradient tĩnh + chấm góc tĩnh, KHÔNG orbs động / không bám chuột.
 * Cyan là accent riêng của section (biến local trong home.css, không đụng token).
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Icon vẽ line (stroke=currentColor) → CSS tô màu cyan. Path lấy đúng demo.
$pm_svg_open = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">';

$pm_values = array(
	array(
		'icon'  => $pm_svg_open . '<path d="M4 14v-2a8 8 0 0 1 16 0v2"/><rect x="2.5" y="14" width="4" height="6" rx="1.5"/><rect x="17.5" y="14" width="4" height="6" rx="1.5"/><path d="M20 20v1a3 3 0 0 1-3 3h-3"/></svg>',
		'title' => __( 'MIỄN PHÍ', 'prometal' ),
		'text'  => __( 'Tư vấn thiết kế, vật liệu và các vấn đề liên quan đến công trình hoàn toàn miễn phí.', 'prometal' ),
	),
	array(
		'icon'  => $pm_svg_open . '<path d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18v3h3l6.3-6.3a4 4 0 0 0 5.4-5.4l-2.3 2.3-2-2 2.3-2.3z"/></svg>',
		'title' => __( 'CHUYÊN NGHIỆP', 'prometal' ),
		'text'  => __( 'Đội ngũ thợ lành nghề, nhiều năm kinh nghiệm trong ngành cơ khí.', 'prometal' ),
	),
	array(
		'icon'  => $pm_svg_open . '<path d="M13 2 4 14h6l-1 8 9-12h-6l1-8z"/></svg>',
		'title' => __( 'NHANH – GIÁ RẺ', 'prometal' ),
		'text'  => __( 'Dịch vụ nhanh chóng với mức giá hợp lý và cạnh tranh nhất.', 'prometal' ),
	),
	array(
		'icon'  => $pm_svg_open . '<path d="M12 3 5 6v5c0 4.4 3 8.3 7 9.5 4-1.2 7-5.1 7-9.5V6z"/><path d="M9 12l2 2 4-4"/></svg>',
		'title' => __( 'BẢO HÀNH', 'prometal' ),
		'text'  => __( 'Cam kết chất lượng cao nhất, bảo hành đầy đủ và nhanh chóng trong 2 năm.', 'prometal' ),
	),
);
?>
<section class="pm-section pm-values">
	<div class="pm-container">

		<div class="pm-h2wrap pm-h2wrap--light">
			<h2><?php esc_html_e( 'Cam kết của chúng tôi', 'prometal' ); ?></h2>
			<p class="pm-h2wrap__lead"><?php esc_html_e( 'Điểm khác biệt Pro-Metal — bốn cam kết đi cùng mọi công trình.', 'prometal' ); ?></p>
		</div>

		<ul class="pm-values__grid">
			<?php foreach ( $pm_values as $pm_v ) : ?>
				<li class="pm-vcard">
					<span class="pm-vcard__topline" aria-hidden="true"></span>
					<span class="pm-vcard__node pm-vcard__node--a" aria-hidden="true"></span>
					<span class="pm-vcard__node pm-vcard__node--b" aria-hidden="true"></span>
					<span class="pm-vcard__node pm-vcard__node--c" aria-hidden="true"></span>
					<span class="pm-vcard__node pm-vcard__node--d" aria-hidden="true"></span>
					<span class="pm-vcard__icon">
						<?php echo $pm_v['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</span>
					<h3 class="pm-vcard__title"><?php echo esc_html( $pm_v['title'] ); ?></h3>
					<p class="pm-vcard__text"><?php echo esc_html( $pm_v['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
