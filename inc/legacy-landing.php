<?php
/**
 * Tương thích landing cũ được dựng bằng HTML trong nội dung Page.
 *
 * Các landing này có JavaScript form riêng, đã lỗi thời và gửi trùng với
 * assets/js/form.js. Chỉ gỡ script gửi lead cũ; giữ nguyên markup/CSS/layout
 * của từng landing để không làm thay đổi giao diện đang chạy.
 *
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter( 'the_content', 'prometal_normalize_legacy_landing', PHP_INT_MAX );
/**
 * Chuẩn hoá script của landing cũ khi render.
 *
 * Nội dung của một số Page đã được WordPress mã hoá toán tử && thành
 * &#038;&#038; bên trong thẻ script. Đây không phải JavaScript hợp lệ và làm
 * trình duyệt báo SyntaxError. Script TOC đó chỉ thêm trạng thái active,
 * nên bỏ hẳn an toàn hơn giữ một đoạn code lỗi. Các script gửi lead cũ cũng
 * bị loại để mọi form chỉ đi qua assets/js/form.js.
 *
 * @param string $content Rendered post content.
 * @return string
 */
function prometal_normalize_legacy_landing( $content ) {
	if ( is_admin() || false === stripos( $content, 'pm-lp' ) ) {
		return $content;
	}

	// Ba landing cũ ghi body{...} ngay trong content. Body đã được theme reset
	// sẵn; giữ rule này sẽ làm nền/header/footer của theme bị ghi đè.
	$content = preg_replace( '#(?<![\w-])body\s*\{[^}]*\}#i', '', $content );

	return preg_replace_callback(
		'#<script\b[^>]*>.*?</script>#is',
		function ( $match ) {
			$script = $match[0];
			if (
				false !== stripos( $script, 'prometal/v1/lead' ) ||
				false !== stripos( $script, 'pm-footform' ) ||
				false !== stripos( $script, 'document.querySelector(".pm-toc")' ) ||
				false !== stripos( $script, "document.querySelector('.pm-toc')" )
			) {
				return '';
			}
			return $script;
		},
		$content
	);
}
