<?php
/**
 * Theme options qua Customizer (đọc lại bằng pm_option()).
 * Mọi mặc định lấy từ prometal_defaults() trong inc/template-tags.php.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'customize_register', 'prometal_customize_register' );
function prometal_customize_register( $wp_customize ) {
	$defaults = function_exists( 'prometal_defaults' ) ? prometal_defaults() : array();

	/* Panel gốc */
	$wp_customize->add_panel(
		'pm_panel',
		array(
			'title'       => __( 'Pro-Metal — Thông tin & Liên hệ', 'prometal' ),
			'description' => __( 'Hotline, Zalo, địa chỉ, chi nhánh, mạng xã hội dùng chung toàn theme.', 'prometal' ),
			'priority'    => 20,
		)
	);

	/* Helper thêm 1 setting + control text/textarea/url. */
	$add = function ( $id, $label, $type, $section, $sanitize, $desc = '' ) use ( $wp_customize, $defaults ) {
		$key = str_replace( 'pm_', '', $id );
		$wp_customize->add_setting(
			$id,
			array(
				'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
				'sanitize_callback' => $sanitize,
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $label,
				'description' => $desc,
				'section'     => $section,
				'type'        => $type,
			)
		);
	};

	/* ---------------- Liên hệ ---------------- */
	$wp_customize->add_section(
		'pm_contact',
		array(
			'title'    => __( 'Liên hệ & Hotline', 'prometal' ),
			'panel'    => 'pm_panel',
			'priority' => 10,
		)
	);
	$add( 'pm_phone', __( 'Hotline (hiển thị)', 'prometal' ), 'text', 'pm_contact', 'sanitize_text_field', __( 'VD: 03.4444.07.17', 'prometal' ) );
	$add( 'pm_zalo', __( 'Số Zalo', 'prometal' ), 'text', 'pm_contact', 'sanitize_text_field', __( 'Chỉ số; để trống sẽ dùng theo hotline.', 'prometal' ) );
	$add( 'pm_email', __( 'Email', 'prometal' ), 'text', 'pm_contact', 'sanitize_email' );
	$add( 'pm_website', __( 'Website (hiển thị)', 'prometal' ), 'text', 'pm_contact', 'sanitize_text_field' );

	/* ---------------- Địa chỉ ---------------- */
	$wp_customize->add_section(
		'pm_address',
		array(
			'title'    => __( 'Địa chỉ & Chi nhánh', 'prometal' ),
			'panel'    => 'pm_panel',
			'priority' => 20,
		)
	);
	$add( 'pm_address', __( 'Trụ sở chính', 'prometal' ), 'text', 'pm_address', 'sanitize_text_field' );
	$add( 'pm_branches_note', __( 'Ghi chú chi nhánh', 'prometal' ), 'text', 'pm_address', 'sanitize_text_field', __( 'VD: Và 8 chi nhánh khác khắp TP.HCM', 'prometal' ) );
	$add( 'pm_branches', __( 'Danh sách chi nhánh', 'prometal' ), 'textarea', 'pm_address', 'sanitize_textarea_field', __( 'Mỗi dòng 1 chi nhánh (tuỳ chọn).', 'prometal' ) );
	$add( 'pm_map_url', __( 'Link Google Maps', 'prometal' ), 'url', 'pm_address', 'esc_url_raw' );

	/* ---------------- Mạng xã hội ---------------- */
	$wp_customize->add_section(
		'pm_social',
		array(
			'title'    => __( 'Mạng xã hội', 'prometal' ),
			'panel'    => 'pm_panel',
			'priority' => 30,
		)
	);
	$add( 'pm_facebook', __( 'Facebook (URL)', 'prometal' ), 'url', 'pm_social', 'esc_url_raw' );
	$add( 'pm_telegram', __( 'Telegram', 'prometal' ), 'text', 'pm_social', 'sanitize_text_field', __( 'Username, @tên hoặc URL t.me/…', 'prometal' ) );

	/* ---------------- Thương hiệu ---------------- */
	$wp_customize->add_section(
		'pm_brand',
		array(
			'title'    => __( 'Thương hiệu & Logo', 'prometal' ),
			'panel'    => 'pm_panel',
			'priority' => 5,
		)
	);
	$add( 'pm_slogan', __( 'Slogan', 'prometal' ), 'text', 'pm_brand', 'sanitize_text_field' );

	// Logo trắng (nền tối) — dùng ở header/footer. Fallback: custom-logo → tên site.
	$wp_customize->add_setting(
		'pm_logo_light',
		array(
			'default'           => '',
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'pm_logo_light',
			array(
				'label'       => __( 'Logo trắng (nền tối)', 'prometal' ),
				'description' => __( 'Dùng cho header/footer nền xanh đậm. Bỏ trống sẽ dùng logo mặc định hoặc tên site.', 'prometal' ),
				'section'     => 'pm_brand',
				'mime_type'   => 'image',
			)
		)
	);
}
