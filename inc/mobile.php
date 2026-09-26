<?php
/**
 * Tối ưu hiển thị trên điện thoại (bổ sung cho inc/performance.php).
 *
 *  1) Inline CSS của theme vào <head> (đã rút gọn) → bỏ 4–6 request CSS chặn hiển
 *     thị; trên 4G mỗi request thêm 1 vòng mạng trước khi vẽ được chữ đầu tiên.
 *  2) Chống lỗi "nh�": plugin nén HTML (SpeedyCache "Minify HTML") xoá byte \xA0 đứng
 *     ngay trước "<" — mà "à" trong UTF-8 là C3 A0 → "Sửa cửa sắt tại nhà</a>" thành
 *     "tại nh�" ở menu, footer, tiêu đề… Chuyển các ký tự đó thành thực thể HTML
 *     (&#224;) trước khi plugin nén chạy. Nên TẮT hẳn "Minify HTML" trong SpeedyCache.
 *  3) <meta name="theme-color">: thanh địa chỉ Chrome Android cùng màu header.
 *  4) ?ver= của CSS/JS theme kèm thời điểm sửa file: host cho trình duyệt giữ JS tới 1 năm,
 *     nên cập nhật theme mà giữ nguyên PROMETAL_VERSION thì khách cũ vẫn chạy JS cũ.
 *
 * Tắt từng phần bằng filter: 'prometal_inline_css', 'prometal_utf8_guard'.
 *
 * @owner   Session M (Mobile)
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ *
 *  1) Inline CSS theme
 * ------------------------------------------------------------------ */

/**
 * Rút gọn CSS an toàn cho file của theme: bỏ chú thích, gộp khoảng trắng, bỏ khoảng
 * trắng quanh { } ; , (KHÔNG đụng dấu : > + - vì có thể đổi nghĩa selector/calc()).
 *
 * @param string $css
 * @return string
 */
function prometal_minify_css( $css ) {
	$css = preg_replace( '#/\*.*?\*/#s', '', (string) $css );
	$css = preg_replace( '/\s+/', ' ', $css );
	$css = preg_replace( '/\s*([{};,])\s*/', '$1', $css );
	return trim( str_replace( ';}', '}', $css ) );
}

/**
 * Đổi url() tương đối trong CSS (VD '../fonts/x.woff2') thành URL tuyệt đối theo vị
 * trí file gốc — cần thiết khi CSS được chép vào <style> trong HTML.
 *
 * @param string $css
 * @param string $src URL file CSS gốc.
 * @return string
 */
function prometal_css_absolute_urls( $css, $src ) {
	$dir = trailingslashit( dirname( strtok( $src, '?' ) ) );
	return preg_replace_callback(
		'#url\(\s*([\'"]?)(?!data:|[a-z][a-z0-9+.-]*:|/|\#)([^\'")]+)\1\s*\)#i',
		function ( $m ) use ( $dir ) {
			$url = $dir . $m[2];
			// Gỡ "thu-muc/../" để URL trùng khớp link preload font (dùng lại được bản đã tải).
			do {
				$url = preg_replace( '#/(?!\.\./)[^/]+/\.\./#', '/', $url, 1, $count );
			} while ( $count );
			return 'url(' . $m[1] . $url . $m[1] . ')';
		},
		$css
	);
}

add_action( 'wp_enqueue_scripts', 'prometal_inline_theme_css', 9999 );
/**
 * Chuyển các stylesheet "prometal-*" đã enqueue thành <style> inline (cùng vị trí,
 * cùng thứ tự phụ thuộc — đúng cơ chế WordPress dùng cho wp_maybe_inline_styles()).
 */
function prometal_inline_theme_css() {
	if ( is_admin() || is_customize_preview() || ! apply_filters( 'prometal_inline_css', true ) ) {
		return;
	}

	$styles = wp_styles();
	$budget = (int) apply_filters( 'prometal_inline_css_budget', 80000 ); // byte sau rút gọn.
	$used   = 0;

	foreach ( $styles->queue as $handle ) {
		if ( 0 !== strpos( $handle, 'prometal-' ) || ! isset( $styles->registered[ $handle ] ) ) {
			continue;
		}
		$style = $styles->registered[ $handle ];
		$src   = (string) $style->src;
		if ( '' === $src || 0 !== strpos( $src, PROMETAL_URI . '/' ) ) {
			continue; // Chỉ file nằm trong theme này.
		}

		$path = PROMETAL_DIR . substr( strtok( $src, '?' ), strlen( PROMETAL_URI ) );
		if ( ! is_readable( $path ) ) {
			continue;
		}

		$css = prometal_minify_css( (string) file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
		$css = prometal_css_absolute_urls( $css, $src );
		if ( '' === $css || $used + strlen( $css ) > $budget ) {
			continue; // Quá ngân sách → giữ <link> như cũ.
		}
		$used += strlen( $css );

		$style->src = false;
		if ( empty( $style->extra['after'] ) ) {
			$style->extra['after'] = array();
		}
		array_unshift( $style->extra['after'], $css );
	}
}

/* ------------------------------------------------------------------ *
 *  2) Chống lỗi ký tự "à" bị plugin nén HTML cắt mất byte
 * ------------------------------------------------------------------ */

add_action( 'template_redirect', 'prometal_utf8_guard_start', PHP_INT_MAX );
/**
 * Mở output buffer muộn nhất có thể (để nằm TRONG buffer của plugin cache/nén) → phần
 * xử lý của theme chạy trước khi plugin nén HTML.
 */
function prometal_utf8_guard_start() {
	if ( is_admin() || is_feed() || is_robots() || is_trackback() || wp_doing_ajax() || ! apply_filters( 'prometal_utf8_guard', true ) ) {
		return;
	}
	ob_start( 'prometal_utf8_guard_filter' );
	$GLOBALS['prometal_utf8_guard_level'] = ob_get_level();
}

add_action( 'wp_footer', 'prometal_utf8_guard_end', PHP_INT_MAX );
/**
 * Đóng buffer của theme ngay cuối wp_footer (chỉ khi buffer trên cùng đúng là của theme).
 */
function prometal_utf8_guard_end() {
	if ( isset( $GLOBALS['prometal_utf8_guard_level'] ) && ob_get_level() === $GLOBALS['prometal_utf8_guard_level'] ) {
		unset( $GLOBALS['prometal_utf8_guard_level'] );
		ob_end_flush();
	}
}

/**
 * Ký tự UTF-8 có byte cuối \xA0 (à, Ạ, Ơ, Ỡ, nbsp…) đứng trước "<" → &#NNN;. Tính cả khi
 * giữa chúng chỉ có khoảng trắng ("nhà\n</a>", "Nhà </title>"): bộ nén xoá khoảng trắng
 * trước thẻ và coi luôn \xA0 là khoảng trắng.
 * Bỏ qua nội dung <script>/<style> (thực thể không được giải mã trong đó).
 *
 * @param string $html
 * @return string
 */
function prometal_utf8_guard_filter( $html ) {
	if ( ! is_string( $html ) || ! preg_match( '/\xA0[ \t\r\n\f]*</', $html ) ) {
		return $html;
	}
	$parts = preg_split( '#(<script\b[^>]*>.*?</script>|<style\b[^>]*>.*?</style>)#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	if ( false === $parts ) {
		return $html;
	}
	foreach ( $parts as $i => $part ) {
		if ( $i % 2 ) {
			continue; // Khối script/style → giữ nguyên.
		}
		$parts[ $i ] = preg_replace_callback(
			'/(?:[\xC2-\xDF]|[\xE0-\xEF][\x80-\xBF]|[\xF0-\xF4][\x80-\xBF]{2})\xA0(?=[ \t\r\n\f]*<)/',
			'prometal_utf8_char_to_entity',
			$part
		);
	}
	return implode( '', $parts );
}

/**
 * 1 ký tự UTF-8 (2–4 byte) → thực thể HTML dạng số.
 *
 * @param array $m Kết quả preg_replace_callback.
 * @return string
 */
function prometal_utf8_char_to_entity( $m ) {
	$b   = array_values( unpack( 'C*', $m[0] ) );
	$len = count( $b );
	if ( 2 === $len ) {
		$cp = ( ( $b[0] & 0x1F ) << 6 ) | ( $b[1] & 0x3F );
	} elseif ( 3 === $len ) {
		$cp = ( ( $b[0] & 0x0F ) << 12 ) | ( ( $b[1] & 0x3F ) << 6 ) | ( $b[2] & 0x3F );
	} else {
		$cp = ( ( $b[0] & 0x07 ) << 18 ) | ( ( $b[1] & 0x3F ) << 12 ) | ( ( $b[2] & 0x3F ) << 6 ) | ( $b[3] & 0x3F );
	}
	return '&#' . $cp . ';';
}

/* ------------------------------------------------------------------ *
 *  3) Màu thanh trình duyệt trên điện thoại
 * ------------------------------------------------------------------ */

add_action( 'wp_head', 'prometal_theme_color_meta', 2 );
/**
 * Thanh địa chỉ Chrome/Samsung Internet cùng màu header (nền xanh đậm).
 */
function prometal_theme_color_meta() {
	echo '<meta name="theme-color" content="#0b3a4e">' . "\n";
}

/* ------------------------------------------------------------------ *
 *  4) Phiên bản CSS/JS theo thời điểm sửa file
 * ------------------------------------------------------------------ */

add_action( 'wp_enqueue_scripts', 'prometal_asset_file_versions', 10000 );
/**
 * ?ver= = PROMETAL_VERSION + filemtime → file đổi là URL đổi, không cần nhớ tăng
 * PROMETAL_VERSION mỗi lần cập nhật theme. Chạy sau phần inline CSS (style đã inline
 * có src = false nên được bỏ qua).
 */
function prometal_asset_file_versions() {
	foreach ( array( wp_scripts(), wp_styles() ) as $deps ) {
		foreach ( $deps->registered as $dep ) {
			$src = is_string( $dep->src ) ? $dep->src : '';
			if ( '' === $src || 0 !== strpos( $src, PROMETAL_URI . '/' ) ) {
				continue;
			}
			$path = PROMETAL_DIR . substr( strtok( $src, '?' ), strlen( PROMETAL_URI ) );
			if ( is_readable( $path ) ) {
				$dep->ver = PROMETAL_VERSION . '.' . filemtime( $path );
			}
		}
	}
}
