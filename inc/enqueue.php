<?php
/**
 * Nạp CSS/JS/font. ĐÃ pre-wire sẵn cho mọi asset dự kiến.
 *
 * → Các session sau chỉ cần ĐIỀN nội dung vào file .css/.js tương ứng.
 *   KHÔNG sửa file này (trừ khi thêm asset hoàn toàn mới)  ==> tránh xung đột enqueue.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'prometal_assets' );
function prometal_assets() {
	$css = PROMETAL_URI . '/assets/css/';
	$js  = PROMETAL_URI . '/assets/js/';
	$v   = PROMETAL_VERSION;

	// Font — self-host: @font-face khai báo trong assets/css/base.css (bỏ Google Fonts để tải nhanh, không phụ thuộc bên thứ 3).

	// Core — mọi trang. Thứ tự phụ thuộc: tokens -> base -> components / header-footer.
	wp_enqueue_style( 'prometal-tokens', $css . 'tokens.css', array(), $v );
	wp_enqueue_style( 'prometal-base', $css . 'base.css', array( 'prometal-tokens' ), $v );
	wp_enqueue_style( 'prometal-components', $css . 'components.css', array( 'prometal-base' ), $v );
	wp_enqueue_style( 'prometal-header-footer', $css . 'header-footer.css', array( 'prometal-base' ), $v );

	// Core JS.
	wp_enqueue_script( 'prometal-main', $js . 'main.js', array(), $v, true );
	wp_enqueue_script( 'prometal-form', $js . 'form.js', array(), $v, true );

	// Theo trang (điều kiện) — file do session tương ứng ĐIỀN.
	if ( is_front_page() ) {
		wp_enqueue_style( 'prometal-home', $css . 'home.css', array( 'prometal-components' ), $v );
		wp_enqueue_script( 'prometal-slider', $js . 'slider.js', array(), $v, true );
	}
	if ( is_page_template( 'template-landing.php' ) ) {
		wp_enqueue_style( 'prometal-landing', $css . 'landing.css', array( 'prometal-components' ), $v );
		wp_enqueue_script( 'prometal-lightbox', $js . 'lightbox.js', array(), $v, true );
		wp_enqueue_script( 'prometal-toc', $js . 'toc.js', array(), $v, true );
	}
	if ( is_singular( 'post' ) ) {
		wp_enqueue_style( 'prometal-news', $css . 'news.css', array( 'prometal-components' ), $v );
		wp_enqueue_script( 'prometal-toc', $js . 'toc.js', array(), $v, true );
	}

	// Dữ liệu cho form.js (Session B): endpoint REST + nonce.
	wp_localize_script(
		'prometal-form',
		'PROMETAL',
		array(
			'rest'  => esc_url_raw( rest_url( 'prometal/v1/lead' ) ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		)
	);
}
