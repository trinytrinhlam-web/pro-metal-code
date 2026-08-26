<?php
/**
 * Tối ưu tốc độ + dọn <head>.
 *
 *  - Bỏ toàn bộ emoji script/style của WordPress.
 *  - Gọn <head>: bỏ generator, wlwmanifest, RSD, shortlink (không ảnh hưởng SEO/RSS).
 *  - defer JS (main/form/toc/lightbox/slider) — KHÔNG phá thứ tự phụ thuộc;
 *    inline PROMETAL (rest+nonce) là script thường nên vẫn chạy TRƯỚC form.js.
 *  - Preload font .woff2 quan trọng: Be Vietnam Pro 400 (latin + vietnamese).
 *  - Nonce wp_rest KHÔNG bị full-page cache: cung cấp endpoint /prometal/v1/nonce
 *    trả nonce tươi + inline nhỏ để form.js đọc PROMETAL.nonce mới (né nonce hết hạn
 *    khi trang bị plugin cache/CDN lưu HTML tĩnh).
 *
 * @owner   Session F
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ *
 *  1) Bỏ emoji (script + style + feed + email + TinyMCE + DNS-prefetch)
 * ------------------------------------------------------------------ */

add_action( 'init', 'prometal_disable_emojis' );
function prometal_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'the_content_feed', 'wp_staticize_emoji' );
	remove_action( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_action( 'wp_mail', 'wp_staticize_emoji_for_email' );

	add_filter( 'tiny_mce_plugins', 'prometal_disable_emojis_tinymce' );
	add_filter( 'wp_resource_hints', 'prometal_disable_emojis_dns_prefetch', 10, 2 );
}

/**
 * Gỡ plugin wpemoji khỏi TinyMCE.
 *
 * @param array $plugins
 * @return array
 */
function prometal_disable_emojis_tinymce( $plugins ) {
	if ( ! is_array( $plugins ) ) {
		return array();
	}
	return array_diff( $plugins, array( 'wpemoji' ) );
}

/**
 * Bỏ DNS-prefetch tới CDN emoji của s.w.org.
 *
 * @param array  $urls
 * @param string $relation_type
 * @return array
 */
function prometal_disable_emojis_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' !== $relation_type ) {
		return $urls;
	}
	foreach ( $urls as $key => $url ) {
		$href = is_array( $url ) && isset( $url['href'] ) ? $url['href'] : ( is_string( $url ) ? $url : '' );
		if ( '' !== $href && false !== strpos( $href, 'emoji' ) ) {
			unset( $urls[ $key ] );
		}
	}
	return $urls;
}

/* ------------------------------------------------------------------ *
 *  2) Gọn <head> — bỏ meta không cần cho theme marketing tĩnh
 * ------------------------------------------------------------------ */

add_action( 'init', 'prometal_clean_head' );
function prometal_clean_head() {
	remove_action( 'wp_head', 'wp_generator' );                        // Ẩn phiên bản WP.
	remove_action( 'wp_head', 'wlwmanifest_link' );                    // Windows Live Writer (đã lỗi thời).
	remove_action( 'wp_head', 'rsd_link' );                            // Really Simple Discovery.
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );                // Thẻ shortlink.
	remove_action( 'template_redirect', 'wp_shortlink_header', 11 );   // Header shortlink.
}

/* ------------------------------------------------------------------ *
 *  3) defer JS — không phá thứ tự phụ thuộc
 * ------------------------------------------------------------------ *
 *  Các handle này khai báo ở inc/enqueue.php (Session A), đều nạp ở footer,
 *  không có deps chéo → thêm `defer` an toàn (defer vẫn giữ đúng thứ tự thực thi).
 *  Lưu ý: inline `var PROMETAL = {...}` (localize) và inline refresh nonce là
 *  <script> THƯỜNG (không defer) nên chạy ngay khi parse → luôn có mặt trước khi
 *  form.js (defer) chạy. Dữ liệu form.js đọc lazy lúc submit nên thứ tự đảm bảo. */

add_filter( 'script_loader_tag', 'prometal_defer_scripts', 10, 2 );
function prometal_defer_scripts( $tag, $handle ) {
	if ( is_admin() ) {
		return $tag;
	}

	$defer = array(
		'prometal-main',
		'prometal-form',
		'prometal-toc',
		'prometal-lightbox',
		'prometal-slider',
	);

	if ( ! in_array( $handle, $defer, true ) ) {
		return $tag;
	}
	// Đã có defer/async thì thôi.
	if ( false !== strpos( $tag, ' defer' ) || false !== strpos( $tag, ' async' ) ) {
		return $tag;
	}
	return preg_replace( '/\ssrc=/', ' defer src=', $tag, 1 );
}

/* ------------------------------------------------------------------ *
 *  4) Preload font .woff2 quan trọng (LCP text nhanh hơn)
 * ------------------------------------------------------------------ *
 *  @font-face khai báo trong assets/css/base.css. Chỉ preload weight 400 cho
 *  cả subset latin + vietnamese; các weight còn lại tải theo nhu cầu để không
 *  tranh băng thông với ảnh LCP trên mạng mobile. crossorigin BẮT BUỘC cho font. */

add_action( 'wp_head', 'prometal_preload_fonts', 1 );
function prometal_preload_fonts() {
	if ( is_admin() ) {
		return;
	}

	$base  = PROMETAL_URI . '/assets/fonts/';
	$fonts = array(
		'be-vietnam-pro-400-latin.woff2',
		'be-vietnam-pro-400-vietnamese.woff2',
	);

	foreach ( $fonts as $file ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $base . $file )
		);
	}
}

/* ------------------------------------------------------------------ *
 *  5) Nonce wp_rest né full-page cache
 * ------------------------------------------------------------------ *
 *  Vấn đề: enqueue.php localize PROMETAL.nonce = wp_create_nonce('wp_rest')
 *  trực tiếp vào HTML → khi trang bị cache toàn trang, nonce cứng sẽ HẾT HẠN,
 *  form gửi lên bị 403 "Phiên đã hết hạn".
 *
 *  Giải: endpoint GET /prometal/v1/nonce (không cache) trả nonce tươi;
 *  1 inline nhỏ (gắn TRƯỚC form.js) fetch endpoint và cập nhật window.PROMETAL.nonce.
 *  form.js đọc CFG.nonce LÚC SUBMIT (không phải lúc load) nên luôn dùng nonce mới.
 *  Không cần sửa enqueue.php (Session A) hay form.js (Session B). */

add_action( 'rest_api_init', 'prometal_register_nonce_route' );
function prometal_register_nonce_route() {
	register_rest_route(
		'prometal/v1',
		'/nonce',
		array(
			'methods'             => 'GET',
			'callback'            => 'prometal_rest_nonce',
			'permission_callback' => '__return_true', // Nonce wp_rest không bí mật; buộc theo session người dùng.
		)
	);
}

/**
 * Trả nonce wp_rest tươi (không cho cache client/CDN).
 *
 * @return WP_REST_Response
 */
function prometal_rest_nonce() {
	$response = new WP_REST_Response( array( 'nonce' => wp_create_nonce( 'wp_rest' ) ) );
	$response->header( 'Cache-Control', 'no-store, max-age=0' );
	return $response;
}

add_action( 'wp_enqueue_scripts', 'prometal_refresh_nonce_inline', 20 );
function prometal_refresh_nonce_inline() {
	// Chỉ gắn khi form.js được nạp (trang có form lead).
	if ( ! wp_script_is( 'prometal-form', 'enqueued' ) && ! wp_script_is( 'prometal-form', 'registered' ) ) {
		return;
	}

	$endpoint = wp_json_encode( esc_url_raw( rest_url( 'prometal/v1/nonce' ) ) );

	$js  = '(function(){window.PROMETAL=window.PROMETAL||{};try{';
	$js .= 'var u=' . $endpoint . ';u+=(u.indexOf("?")>-1?"&":"?")+"_="+Date.now();'; // cache-buster: né cả CDN/proxy.
	$js .= "fetch(u,{credentials:'same-origin',headers:{'Accept':'application/json'}})";
	$js .= '.then(function(r){return r.ok?r.json():null;})';
	$js .= '.then(function(d){if(d&&d.nonce){window.PROMETAL.nonce=d.nonce;}})';
	$js .= '.catch(function(){});}catch(e){}})();';

	wp_add_inline_script( 'prometal-form', $js, 'before' );
}

/* ------------------------------------------------------------------ *
 *  6) Wire WebP cho ảnh hero — giảm LCP trang chủ
 * ------------------------------------------------------------------ *
 *  Bối cảnh: template-parts/home/hero-slider.php (owner C) nạp
 *  assets/img/hero-1..3.png (~800KB–1.1MB/ảnh) làm CSS background.
 *  F đã có sẵn hero-1..3.webp cùng tên (~79/128/116KB — nhẹ hơn ~90%)
 *  nhưng trước đó chưa file nào dùng → WebP mồ côi, LCP hero chưa cải thiện.
 *
 *  Giải (KHÔNG đụng file C): hook 'prometal_hero_slides' ở priority 20 —
 *  chạy SAU cầu nối của Session D (priority 10) nên xử lý danh sách CUỐI
 *  CÙNG (ảnh bundle mặc định HOẶC ảnh admin upload đã map). Với mỗi slide:
 *   - 'file' là đường dẫn theme TƯƠNG ĐỐI .png/.jpg/.jpeg VÀ có .webp
 *     cùng tên (kiểm bằng get_theme_file_path) → đổi 'file' sang .webp.
 *   - 'file' là URL TUYỆT ĐỐI (ảnh admin upload) hoặc KHÔNG có .webp
 *     sibling → GIỮ NGUYÊN (fallback an toàn).
 *
 *  Không đổi cấu trúc mảng [file, alt]; hero-slider.php tự file_exists()
 *  + dựng URL qua pipeline sẵn có. WebP được ~97% trình duyệt hỗ trợ;
 *  thiếu .webp thì tự giữ .png nên luôn an toàn. */

add_filter( 'prometal_hero_slides', 'prometal_hero_slides_webp', 20 );
/**
 * Đổi đường dẫn ảnh hero bundle sang .webp khi có file .webp cùng tên.
 *
 * @param array $slides Danh sách slide dạng [ ['file'=>…, 'alt'=>…], … ].
 * @return array
 */
function prometal_hero_slides_webp( $slides ) {
	if ( ! is_array( $slides ) ) {
		return $slides;
	}

	foreach ( $slides as $key => $slide ) {
		if ( ! is_array( $slide ) || empty( $slide['file'] ) ) {
			continue;
		}

		$file = (string) $slide['file'];

		// URL tuyệt đối (ảnh admin upload) → không có .webp bundle, giữ nguyên.
		if ( preg_match( '#^https?://#i', $file ) ) {
			continue;
		}

		// Chỉ xử lý ảnh raster theme tương đối (.png/.jpg/.jpeg).
		if ( ! preg_match( '#\.(png|jpe?g)$#i', $file ) ) {
			continue;
		}

		$webp = preg_replace( '#\.(png|jpe?g)$#i', '.webp', $file );

		// Chỉ đổi khi file .webp thật sự tồn tại trong theme (else giữ .png).
		if ( $webp && file_exists( get_theme_file_path( $webp ) ) ) {
			$slides[ $key ]['file'] = $webp;
		}
	}

	return $slides;
}
