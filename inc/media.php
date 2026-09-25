<?php
/**
 * Ảnh bundle trong theme cho mobile: WebP đúng cỡ + srcset/sizes + width/height.
 *
 *  - pm_theme_img()                 : <img> từ ảnh trong assets/img, tự dùng các bản
 *                                     WebP thu nhỏ "{tên}-{rộng}.webp" nếu có (tạo sẵn
 *                                     trong repo) → điện thoại tải ảnh 10–90KB thay vì
 *                                     JPEG gốc 100–230KB.
 *  - prometal_hero_slides_resolved(): danh sách slide hero đã chuẩn hoá (dùng chung cho
 *                                     template hero + preload), kèm bản cắt cho điện
 *                                     thoại "{tên}-m.webp" nếu có.
 *  - Preload ảnh hero đầu tiên (ảnh LCP) ngay đầu <head> trang chủ.
 *
 * @owner   Session M (Mobile)
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Kích thước (rộng, cao) của 1 file ảnh trong theme — nhớ tạm trong request.
 *
 * @param string $rel Đường dẫn tương đối trong theme.
 * @return array{0:int,1:int}|null
 */
function prometal_theme_img_size( $rel ) {
	static $cache = array();
	if ( ! array_key_exists( $rel, $cache ) ) {
		$path          = get_theme_file_path( $rel );
		$size          = file_exists( $path ) ? wp_getimagesize( $path ) : false;
		$cache[ $rel ] = $size ? array( (int) $size[0], (int) $size[1] ) : null;
	}
	return $cache[ $rel ];
}

/**
 * Markup <img> cho ảnh bundle trong theme.
 *
 * @param string $file Đường dẫn tương đối, VD 'assets/img/intro-1.jpg'.
 * @param array  $args {
 *     @type string $alt           Văn bản thay thế ('' = ảnh trang trí).
 *     @type string $class         Class CSS.
 *     @type int[]  $widths        Các bản WebP thu nhỏ cần tìm, VD array( 480, 800 ).
 *     @type string $sizes         Thuộc tính sizes (bề rộng hiển thị theo viewport).
 *     @type string $loading       'lazy' (mặc định) | 'eager'.
 *     @type string $fetchpriority '' | 'high' | 'low'.
 * }
 * @return string
 */
function pm_theme_img( $file, $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'alt'           => '',
			'class'         => '',
			'widths'        => array(),
			'sizes'         => '',
			'loading'       => 'lazy',
			'fetchpriority' => '',
		)
	);

	$base     = preg_replace( '#\.(jpe?g|png|webp)$#i', '', $file );
	$variants = array();
	foreach ( (array) $args['widths'] as $w ) {
		$rel  = $base . '-' . (int) $w . '.webp';
		$size = prometal_theme_img_size( $rel );
		if ( $size ) {
			$variants[] = array(
				'url' => get_theme_file_uri( $rel ),
				'w'   => $size[0],
				'h'   => $size[1],
			);
		}
	}

	if ( $variants ) {
		$largest = end( $variants );
		$src     = $largest['url'];
		$width   = $largest['w'];
		$height  = $largest['h'];
		$srcset  = implode(
			', ',
			array_map(
				function ( $v ) {
					return esc_url( $v['url'] ) . ' ' . $v['w'] . 'w';
				},
				$variants
			)
		);
	} else {
		// Chưa có bản thu nhỏ → dùng file gốc (vẫn kèm width/height để chống giật layout).
		$size   = prometal_theme_img_size( $file );
		$src    = get_theme_file_uri( $file );
		$width  = $size ? $size[0] : 0;
		$height = $size ? $size[1] : 0;
		$srcset = '';
	}

	$attrs = array(
		'src'    => esc_url( $src ),
		'srcset' => $srcset,
		'sizes'  => $srcset ? esc_attr( $args['sizes'] ) : '',
		'width'  => $width ? (int) $width : '',
		'height' => $height ? (int) $height : '',
		'alt'    => esc_attr( $args['alt'] ),
		'class'  => esc_attr( $args['class'] ),
	);
	if ( 'eager' !== $args['loading'] ) {
		$attrs['loading'] = 'lazy';
	}
	if ( in_array( $args['fetchpriority'], array( 'high', 'low' ), true ) ) {
		$attrs['fetchpriority'] = $args['fetchpriority'];
	}
	$attrs['decoding'] = 'async';

	$html = '<img';
	foreach ( $attrs as $name => $value ) {
		if ( '' === $value && 'alt' !== $name ) {
			continue;
		}
		$html .= ' ' . $name . '="' . $value . '"';
	}
	return $html . '>';
}

/* ------------------------------------------------------------------ *
 *  Ảnh trong nội dung thiếu width/height
 * ------------------------------------------------------------------ */

add_filter( 'the_content', 'prometal_content_img_dimensions', 11 );
/**
 * Ảnh trong nội dung viết tay (HTML dán vào, nội dung cũ) trỏ tới thư mục uploads nhưng
 * KHÔNG có class "wp-image-{ID}" → WordPress không biết ảnh nào nên bỏ qua srcset/sizes
 * (điện thoại tải ảnh gốc 1280px cho khung ~330px), thường thiếu cả width/height (khung
 * ảnh "bung" khi tải xong → trang nhảy, CLS) và không được lazy-load.
 *
 *  - File có trong Thư viện → gắn class wp-image-{ID}: wp_filter_content_tags (priority
 *    12, chạy SAU hàm này) tự thêm width/height, srcset, sizes, loading như ảnh chuẩn.
 *  - File .webp do plugin chuyển đổi tạo cạnh bản .jpg/.png của Thư viện → tự dựng
 *    srcset từ các bản .webp cùng cỡ có thật trên đĩa (WordPress không tự khớp được).
 *  - Còn lại → chỉ điền width/height đọc từ file.
 * Tra ID ảnh của cả trang bằng 1 truy vấn.
 *
 * @param string $content Nội dung bài/trang.
 * @return string
 */
function prometal_content_img_dimensions( $content ) {
	if ( false === stripos( (string) $content, '<img' ) || ! preg_match_all( '/<img\b[^>]*>/i', $content, $tags ) ) {
		return $content;
	}
	$uploads = wp_get_upload_dir();
	if ( ! empty( $uploads['error'] ) ) {
		return $content;
	}
	$baseurl = preg_replace( '#^https?:#i', '', untrailingslashit( $uploads['baseurl'] ) );
	$basedir = untrailingslashit( $uploads['basedir'] );

	// Đường dẫn tương đối (trong uploads) của ảnh cần xử lý → tra ID 1 lần cho cả trang.
	$rel_of = function ( $tag ) use ( $baseurl ) {
		if ( preg_match( '/\bwp-image-\d+/', $tag ) || ! preg_match( '/\ssrc\s*=\s*(["\'])([^"\']+)\1/i', $tag, $src ) ) {
			return '';
		}
		$url = strtok( preg_replace( '#^https?:#i', '', html_entity_decode( $src[2] ) ), '?#' );
		if ( 0 !== strpos( $url, $baseurl . '/' ) || false !== strpos( $url, '..' ) ) {
			return ''; // Ảnh ngoài uploads của site → không đụng tới.
		}
		return rawurldecode( substr( $url, strlen( $baseurl ) + 1 ) );
	};
	$candidates = array();
	foreach ( $tags[0] as $tag ) {
		$rel = $rel_of( $tag );
		if ( '' !== $rel ) {
			$candidates = array_merge( $candidates, prometal_attachment_path_candidates( $rel ) );
		}
	}
	if ( ! $candidates ) {
		return $content;
	}
	$ids = prometal_attachment_ids_by_path( $candidates );

	return preg_replace_callback(
		'/<img\b[^>]*>/i',
		function ( $m ) use ( $rel_of, $ids, $basedir, $uploads ) {
			$tag = $m[0];
			$rel = $rel_of( $tag );
			if ( '' === $rel ) {
				return $tag;
			}
			$c = prometal_attachment_path_candidates( $rel );

			// 1) Đúng file của Thư viện (gốc hoặc bản thu nhỏ -WxH) → để WordPress xử lý.
			$id = ! empty( $ids[ $c[0] ] ) ? $ids[ $c[0] ] : ( ! empty( $ids[ $c[1] ] ) ? $ids[ $c[1] ] : 0 );
			if ( $id && wp_attachment_is_image( $id ) ) {
				if ( preg_match( '/\sclass\s*=\s*(["\'])/i', $tag ) ) {
					return preg_replace( '/(\sclass\s*=\s*(["\']))/i', '$1wp-image-' . $id . ' ', $tag, 1 );
				}
				return preg_replace( '/^<img\b/i', '<img class="wp-image-' . $id . '"', $tag, 1 );
			}

			// 2) Bản .webp cạnh file .jpg/.png của Thư viện → srcset từ các bản .webp cùng cỡ.
			foreach ( array_slice( $c, 2 ) as $alt ) {
				if ( empty( $ids[ $alt ] ) || preg_match( '/\ssrcset\s*=/i', $tag ) ) {
					continue;
				}
				$set = prometal_webp_sibling_srcset( $ids[ $alt ], $rel, $basedir, $uploads['baseurl'] );
				if ( $set ) {
					$attrs = ' srcset="' . esc_attr( $set['srcset'] ) . '" sizes="' . esc_attr( '(max-width: ' . $set['width'] . 'px) 100vw, ' . $set['width'] . 'px' ) . '"';
					if ( ! preg_match( '/\s(width|height)\s*=/i', $tag ) ) {
						$attrs .= ' width="' . (int) $set['width'] . '" height="' . (int) $set['height'] . '"';
					}
					return preg_replace( '/^<img\b/i', '<img' . $attrs, $tag, 1 );
				}
			}

			// 3) Chỉ điền width/height từ file (chống nhảy trang).
			if ( preg_match( '/\s(width|height)\s*=/i', $tag ) ) {
				return $tag;
			}
			$file = $basedir . '/' . $rel;
			$size = is_file( $file ) ? wp_getimagesize( $file ) : false;
			if ( empty( $size[0] ) || empty( $size[1] ) ) {
				return $tag;
			}
			return preg_replace( '/^<img\b/i', '<img width="' . (int) $size[0] . '" height="' . (int) $size[1] . '"', $tag, 1 );
		},
		$content
	);
}

/**
 * Các giá trị _wp_attached_file có thể ứng với 1 file trong uploads, theo thứ tự:
 * [0] chính nó, [1] bỏ hậu tố "-WxH" (bản thu nhỏ), [2..] file .webp → .jpg/.jpeg/.png gốc.
 *
 * @param string $rel Đường dẫn tương đối trong uploads, VD '2026/07/a-768x576.webp'.
 * @return string[]
 */
function prometal_attachment_path_candidates( $rel ) {
	$full = preg_replace( '/-\d+x\d+(\.[a-z0-9]+)$/i', '$1', $rel );
	$out  = array( $rel, $full );
	if ( preg_match( '/^(.+)\.webp$/i', $full, $m ) ) {
		foreach ( array( 'jpg', 'jpeg', 'png' ) as $ext ) {
			$out[] = $m[1] . '.' . $ext;
		}
	}
	return $out;
}

/**
 * ID ảnh theo giá trị _wp_attached_file — tra theo lô, nhớ trong request. 0 = không có.
 *
 * @param string[] $paths
 * @return array<string,int>
 */
function prometal_attachment_ids_by_path( array $paths ) {
	global $wpdb;
	static $cache = array();

	$need = array_values( array_diff( array_unique( $paths ), array_keys( $cache ) ) );
	foreach ( array_chunk( $need, 200 ) as $chunk ) {
		foreach ( $chunk as $p ) {
			$cache[ $p ] = 0;
		}
		$in   = implode( ',', array_fill( 0, count( $chunk ), '%s' ) );
		$rows = $wpdb->get_results( $wpdb->prepare( "SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value IN ($in)", $chunk ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery
		foreach ( (array) $rows as $row ) {
			$cache[ $row->meta_value ] = (int) $row->post_id;
		}
	}
	return array_intersect_key( $cache, array_flip( $paths ) );
}

/**
 * srcset .webp cho ảnh .webp mà Thư viện chỉ lưu bản .jpg/.jpeg/.png cùng tên.
 *
 * @param int    $id      ID ảnh .jpg/.png trong Thư viện.
 * @param string $rel     Đường dẫn tương đối của ảnh .webp đang hiển thị (trong src).
 * @param string $basedir Thư mục uploads.
 * @param string $baseurl URL uploads.
 * @return array{srcset:string,width:int,height:int}|null Kích thước của CHÍNH ảnh trong src.
 */
function prometal_webp_sibling_srcset( $id, $rel, $basedir, $baseurl ) {
	$meta = wp_get_attachment_metadata( $id );
	if ( empty( $meta['file'] ) || empty( $meta['width'] ) || empty( $meta['height'] ) ) {
		return null;
	}

	$dir     = trailingslashit( dirname( $meta['file'] ) );
	$ratio   = $meta['height'] / $meta['width'];
	$entries = array( array( wp_basename( $meta['file'] ), (int) $meta['width'], (int) $meta['height'] ) );
	foreach ( (array) ( isset( $meta['sizes'] ) ? $meta['sizes'] : array() ) as $s ) {
		if ( ! empty( $s['file'] ) && ! empty( $s['width'] ) && ! empty( $s['height'] ) ) {
			$entries[] = array( $s['file'], (int) $s['width'], (int) $s['height'] );
		}
	}

	$src_w  = 0;
	$src_h  = 0;
	$srcset = array();
	foreach ( $entries as $e ) {
		$webp = $dir . preg_replace( '/\.(jpe?g|png)$/i', '.webp', $e[0] );
		if ( abs( $e[2] / $e[1] - $ratio ) > 0.02 || isset( $srcset[ $e[1] ] ) || ! is_file( $basedir . '/' . $webp ) ) {
			continue; // Khác tỉ lệ (bản cắt vuông…), trùng cỡ hoặc chưa có bản .webp.
		}
		$srcset[ $e[1] ] = $baseurl . '/' . $webp . ' ' . $e[1] . 'w';
		if ( $webp === $rel ) {
			$src_w = $e[1];
			$src_h = $e[2];
		}
	}
	if ( ! $src_w || count( $srcset ) < 2 ) {
		return null; // Ảnh trong src không thuộc bộ cỡ này, hoặc không có gì để chọn.
	}
	ksort( $srcset );
	return array(
		'srcset' => implode( ', ', $srcset ),
		'width'  => $src_w,
		'height' => $src_h,
	);
}

/* ------------------------------------------------------------------ *
 *  Hero trang chủ
 * ------------------------------------------------------------------ */

/**
 * Slide hero đã chuẩn hoá: chỉ giữ slide có ảnh thật.
 * Mỗi phần tử: url (ảnh desktop), url_m (bản cắt cho điện thoại hoặc ''), alt.
 * Chạy filter 'prometal_hero_slides' đúng 1 lần/request (bridge Carbon Fields +
 * đổi .webp ở inc/performance.php vẫn áp dụng như cũ).
 *
 * @return array<int,array{url:string,url_m:string,alt:string}>
 */
function prometal_hero_slides_resolved() {
	static $slides = null;
	if ( null !== $slides ) {
		return $slides;
	}

	$defaults = array(
		array(
			'file' => 'assets/img/hero-1.png',
			'alt'  => __( 'Thợ Pro-Metal sửa chữa cửa sắt tại nhà', 'prometal' ),
		),
		array(
			'file' => 'assets/img/hero-2.png',
			'alt'  => __( 'Thi công tổng hợp cửa sắt, nhôm, inox', 'prometal' ),
		),
		array(
			'file' => 'assets/img/hero-3.png',
			'alt'  => __( 'Nhà xưởng cơ khí Pro-Metal', 'prometal' ),
		),
	);

	$slides = array();
	foreach ( (array) apply_filters( 'prometal_hero_slides', $defaults ) as $s ) {
		if ( empty( $s['file'] ) ) {
			continue;
		}
		$file  = (string) $s['file'];
		$url_m = '';
		if ( preg_match( '#^https?://#i', $file ) ) {
			$url = $file; // URL tuyệt đối do filter cấp (ảnh admin upload).
		} elseif ( file_exists( get_theme_file_path( $file ) ) ) {
			$url    = get_theme_file_uri( $file );
			$mobile = preg_replace( '#\.(png|jpe?g|webp)$#i', '-m.webp', $file );
			if ( $mobile !== $file && file_exists( get_theme_file_path( $mobile ) ) ) {
				$url_m = get_theme_file_uri( $mobile );
			}
		} else {
			continue; // Thiếu ảnh → bỏ qua.
		}
		$slides[] = array(
			'url'   => $url,
			'url_m' => $url_m,
			'alt'   => isset( $s['alt'] ) ? (string) $s['alt'] : '',
		);
	}
	return $slides;
}

/**
 * Media query dùng bản ảnh hero cắt cho điện thoại (khớp <picture> + preload).
 *
 * @return string
 */
function prometal_hero_mobile_media() {
	return '(max-width: 480px)';
}

add_action( 'wp_head', 'prometal_preload_hero_image', 1 );
/**
 * Preload ảnh hero đầu tiên (ảnh LCP) để trình duyệt tải ngay khi nhận <head>,
 * không phải chờ parse tới <body> (sau ~30KB inline CSS/JSON của plugin).
 */
function prometal_preload_hero_image() {
	if ( ! is_front_page() || is_paged() ) {
		return;
	}
	$slides = prometal_hero_slides_resolved();
	if ( empty( $slides ) ) {
		return;
	}
	$first = $slides[0];
	if ( $first['url_m'] ) {
		printf(
			'<link rel="preload" as="image" href="%1$s" media="%2$s" fetchpriority="high">' . "\n",
			esc_url( $first['url_m'] ),
			esc_attr( prometal_hero_mobile_media() )
		);
		printf(
			'<link rel="preload" as="image" href="%1$s" media="%2$s" fetchpriority="high">' . "\n",
			esc_url( $first['url'] ),
			esc_attr( '(min-width: 481px)' )
		);
	} else {
		printf( '<link rel="preload" as="image" href="%s" fetchpriority="high">' . "\n", esc_url( $first['url'] ) );
	}
}
