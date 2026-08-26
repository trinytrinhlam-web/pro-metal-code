<?php
/**
 * Shortcode tiện ích — trả nút .pm-btn* đúng hợp đồng Session B.
 *
 *  [pm_phone label="" size=""]                → nút gọi (.pm-btn--call)
 *  [pm_zalo  label="" size=""]                → nút Zalo (.pm-btn--zalo)
 *  [pm_cta   label="" link="" style="" size="" icon="" target=""]
 *                                             → nút tuỳ biến (.pm-btn--{style})
 *
 *  Thuộc tính:
 *   - size : một hoặc nhiều trong {sm, lg, block} (cách nhau bằng dấu cách) → .pm-btn--{token}
 *   - style: call | zalo | ghost | ghost-light            (mặc định: call)
 *   - icon : tên icon trong thư viện pm_icon()             (mặc định theo style)
 *   - target: _blank để mở tab mới                         (tự thêm rel an toàn)
 *
 * Escape đầy đủ; class chỉ nhận token trong allowlist (chống chèn class lạ).
 *
 * @owner   Session F
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'prometal_register_shortcodes' );
function prometal_register_shortcodes() {
	add_shortcode( 'pm_phone', 'prometal_shortcode_phone' );
	add_shortcode( 'pm_zalo', 'prometal_shortcode_zalo' );
	add_shortcode( 'pm_cta', 'prometal_shortcode_cta' );
}

/**
 * Chuẩn hoá danh sách modifier kích thước → chuỗi class an toàn.
 *
 * @param string $size VD: "lg block".
 * @return string      VD: " pm-btn--lg pm-btn--block".
 */
function prometal_shortcode_size_classes( $size ) {
	$allow = array( 'sm', 'lg', 'block' );
	$out   = '';
	foreach ( preg_split( '/[\s,]+/', (string) $size, -1, PREG_SPLIT_NO_EMPTY ) as $token ) {
		$token = strtolower( $token );
		if ( in_array( $token, $allow, true ) ) {
			$out .= ' pm-btn--' . $token;
		}
	}
	return $out;
}

/**
 * Map style → class biến thể + icon mặc định.
 *
 * @param string $style
 * @return array{class:string,icon:string}
 */
function prometal_shortcode_style( $style ) {
	$map = array(
		'call'        => array( 'class' => 'pm-btn--call', 'icon' => 'phone' ),
		'zalo'        => array( 'class' => 'pm-btn--zalo', 'icon' => 'zalo' ),
		'ghost'       => array( 'class' => 'pm-btn--ghost', 'icon' => '' ),
		'ghost-light' => array( 'class' => 'pm-btn--ghost-light', 'icon' => '' ),
	);
	$style = strtolower( (string) $style );
	return isset( $map[ $style ] ) ? $map[ $style ] : $map['call'];
}

/**
 * Dựng markup nút .pm-btn (icon + span). Dùng chung cho 3 shortcode.
 *
 * @param array{href:string,label:string,style:string,size:string,icon:string,target:string} $args
 * @return string
 */
function prometal_shortcode_button( $args ) {
	$style = prometal_shortcode_style( $args['style'] );

	$class = 'pm-btn ' . $style['class'] . prometal_shortcode_size_classes( $args['size'] );

	// Icon: attr icon="" ghi đè; "none" để tắt; rỗng → icon mặc định của style.
	$icon_name = isset( $args['icon'] ) ? trim( (string) $args['icon'] ) : '';
	if ( '' === $icon_name ) {
		$icon_name = $style['icon'];
	}
	$icon_html = ( '' !== $icon_name && 'none' !== $icon_name )
		? pm_icon( $icon_name, array( 'width' => 18, 'height' => 18 ) )
		: '';

	// target=_blank → rel an toàn.
	$attr = '';
	if ( '_blank' === $args['target'] ) {
		$attr = ' target="_blank" rel="nofollow noopener"';
	}

	return sprintf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s<span>%5$s</span></a>',
		esc_attr( $class ),
		esc_url( $args['href'] ),
		$attr,
		$icon_html, // pm_icon() trả SVG an toàn (Session A).
		esc_html( $args['label'] )
	);
}

/**
 * [pm_phone] — nút gọi hotline.
 */
function prometal_shortcode_phone( $atts ) {
	$atts = shortcode_atts(
		array(
			'label' => '',
			'size'  => '',
			'icon'  => '',
		),
		$atts,
		'pm_phone'
	);

	$label = '' !== trim( $atts['label'] ) ? $atts['label'] : pm_phone();

	return prometal_shortcode_button(
		array(
			'href'   => pm_phone_link(),
			'label'  => $label,
			'style'  => 'call',
			'size'   => $atts['size'],
			'icon'   => $atts['icon'],
			'target' => '',
		)
	);
}

/**
 * [pm_zalo] — nút nhắn Zalo.
 */
function prometal_shortcode_zalo( $atts ) {
	$atts = shortcode_atts(
		array(
			'label' => '',
			'size'  => '',
			'icon'  => '',
		),
		$atts,
		'pm_zalo'
	);

	$label = '' !== trim( $atts['label'] ) ? $atts['label'] : __( 'Nhắn Zalo', 'prometal' );

	return prometal_shortcode_button(
		array(
			'href'   => pm_zalo_link(),
			'label'  => $label,
			'style'  => 'zalo',
			'size'   => $atts['size'],
			'icon'   => $atts['icon'],
			'target' => '_blank',
		)
	);
}

/**
 * [pm_cta label="" link="" style="" size="" icon="" target=""] — nút tuỳ biến.
 */
function prometal_shortcode_cta( $atts ) {
	$atts = shortcode_atts(
		array(
			'label'  => '',
			'link'   => '',
			'style'  => 'call',
			'size'   => '',
			'icon'   => '',
			'target' => '',
		),
		$atts,
		'pm_cta'
	);

	$label = '' !== trim( $atts['label'] ) ? $atts['label'] : __( 'Liên hệ ngay', 'prometal' );

	// Link rỗng → mặc định theo style (call → tel, zalo → zalo, khác → hotline).
	$link = trim( (string) $atts['link'] );
	if ( '' === $link ) {
		$style = strtolower( (string) $atts['style'] );
		if ( 'zalo' === $style ) {
			$link = pm_zalo_link();
		} else {
			$link = pm_phone_link();
		}
	}

	return prometal_shortcode_button(
		array(
			'href'   => $link,
			'label'  => $label,
			'style'  => $atts['style'],
			'size'   => $atts['size'],
			'icon'   => $atts['icon'],
			'target' => '_blank' === $atts['target'] ? '_blank' : '',
		)
	);
}
