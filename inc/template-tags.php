<?php
/**
 * Helper dùng chung ("hợp đồng" cho mọi Session gọi lại).
 *
 * Cung cấp: pm_option, pm_phone, pm_phone_link, pm_zalo_link, pm_telegram_link,
 *           pm_facebook_link, pm_social_links, pm_the_breadcrumb, pm_reading_time,
 *           pm_icon, pm_logo_html + fallback menu.
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Giá trị mặc định cho theme options.
 * Dùng chung bởi pm_option() và inc/customizer.php → 1 nguồn chân lý.
 *
 * @return array<string,string>
 */
function prometal_defaults() {
	return array(
		'phone'         => '03.4444.07.17',
		'zalo'          => '0344440717',
		'email'         => '',
		'website'       => 'suachuacuasat.com',
		'address'       => '728/1 Lê Trọng Tấn, Sơn Kỳ, Bình Tân, TP.HCM',
		'branches_note' => 'Và 8 chi nhánh khác khắp TP.HCM',
		'branches'      => '',
		'facebook'      => '',
		'telegram'      => '',
		'map_url'       => '',
		'slogan'        => 'TẬN TÂM – TRÁCH NHIỆM – CHUYÊN NGHIỆP',
		'logo_light'    => '', // ID ảnh logo trắng (nền tối) — dùng ở header/footer.
	);
}

/**
 * Đọc 1 theme option (lưu bằng theme_mod, prefix pm_).
 *
 * @param string $key     Khóa (không kèm prefix pm_).
 * @param string $default Mặc định; để trống → lấy từ prometal_defaults().
 * @return mixed
 */
function pm_option( $key, $default = '' ) {
	if ( '' === $default ) {
		$defaults = prometal_defaults();
		if ( isset( $defaults[ $key ] ) ) {
			$default = $defaults[ $key ];
		}
	}
	return get_theme_mod( 'pm_' . $key, $default );
}

/**
 * Số hotline hiển thị.  VD: 03.4444.07.17
 *
 * @return string
 */
function pm_phone() {
	return (string) pm_option( 'phone' );
}

/**
 * Chỉ các chữ số của 1 chuỗi điện thoại (để tạo link tel/zalo).
 *
 * @param string $number
 * @return string
 */
function pm_digits( $number ) {
	return preg_replace( '/\D+/', '', (string) $number );
}

/**
 * Link gọi điện.  VD: tel:0344440717
 *
 * @return string
 */
function pm_phone_link() {
	return 'tel:' . pm_digits( pm_phone() );
}

/**
 * Link Zalo.  VD: https://zalo.me/0344440717
 *
 * @return string
 */
function pm_zalo_link() {
	$zalo = pm_digits( pm_option( 'zalo' ) );
	if ( '' === $zalo ) {
		$zalo = pm_digits( pm_phone() );
	}
	return 'https://zalo.me/' . $zalo;
}

/**
 * Link Telegram (nếu đã cấu hình).
 *
 * @return string
 */
function pm_telegram_link() {
	$tg = trim( (string) pm_option( 'telegram' ) );
	if ( '' === $tg ) {
		return '';
	}
	if ( preg_match( '#^https?://#i', $tg ) ) {
		return $tg;
	}
	return 'https://t.me/' . ltrim( $tg, '@/' );
}

/**
 * Link Facebook (nếu đã cấu hình).
 *
 * @return string
 */
function pm_facebook_link() {
	return trim( (string) pm_option( 'facebook' ) );
}

/**
 * Danh sách mạng xã hội đang bật (Zalo luôn có; FB/Telegram tuỳ cấu hình).
 * Session B/C/E dùng để render icon.
 *
 * @return array<int,array{type:string,url:string,label:string}>
 */
function pm_social_links() {
	$out = array();

	$fb = pm_facebook_link();
	if ( '' !== $fb ) {
		$out[] = array(
			'type'  => 'facebook',
			'url'   => $fb,
			'label' => __( 'Facebook', 'prometal' ),
		);
	}

	$out[] = array(
		'type'  => 'zalo',
		'url'   => pm_zalo_link(),
		'label' => __( 'Zalo', 'prometal' ),
	);

	$tg = pm_telegram_link();
	if ( '' !== $tg ) {
		$out[] = array(
			'type'  => 'telegram',
			'url'   => $tg,
			'label' => __( 'Telegram', 'prometal' ),
		);
	}

	return $out;
}

/**
 * Danh sách chi nhánh (mỗi dòng 1 chi nhánh trong option).
 *
 * @return string[]
 */
function pm_branches() {
	$raw = (string) pm_option( 'branches' );
	if ( '' === trim( $raw ) ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$lines = array_filter( array_map( 'trim', $lines ) );
	return array_values( $lines );
}

/* ------------------------------------------------------------------ *
 *  Breadcrumb
 * ------------------------------------------------------------------ */

/**
 * In breadcrumb có ngữ nghĩa + a11y. Bỏ qua khi ở trang chủ.
 */
function pm_the_breadcrumb() {
	if ( is_front_page() ) {
		return;
	}

	$items = array();
	$items[] = array(
		'label' => __( 'Trang chủ', 'prometal' ),
		'url'   => home_url( '/' ),
	);

	if ( is_home() ) {
		$items[] = array( 'label' => __( 'Tin tức', 'prometal' ), 'url' => '' );
	} elseif ( is_singular( 'post' ) ) {
		$blog_id = (int) get_option( 'page_for_posts' );
		if ( $blog_id ) {
			$items[] = array( 'label' => get_the_title( $blog_id ), 'url' => get_permalink( $blog_id ) );
		}
		$cats = get_the_category();
		if ( ! empty( $cats ) ) {
			$cat     = $cats[0];
			$items[] = array( 'label' => $cat->name, 'url' => get_category_link( $cat->term_id ) );
		}
		$items[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_page() ) {
		foreach ( array_reverse( get_post_ancestors( get_queried_object_id() ) ) as $ancestor ) {
			$items[] = array( 'label' => get_the_title( $ancestor ), 'url' => get_permalink( $ancestor ) );
		}
		$items[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_singular() ) {
		$pt     = get_post_type_object( get_post_type() );
		$archive = $pt ? get_post_type_archive_link( $pt->name ) : '';
		if ( $pt && $archive ) {
			$items[] = array( 'label' => $pt->labels->name, 'url' => $archive );
		}
		$items[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$items[] = array( 'label' => single_term_title( '', false ), 'url' => '' );
	} elseif ( is_post_type_archive() ) {
		$items[] = array( 'label' => post_type_archive_title( '', false ), 'url' => '' );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$items[] = array( 'label' => sprintf( __( 'Kết quả tìm: %s', 'prometal' ), get_search_query() ), 'url' => '' );
	} elseif ( is_404() ) {
		$items[] = array( 'label' => __( 'Không tìm thấy', 'prometal' ), 'url' => '' );
	} elseif ( is_archive() ) {
		$items[] = array( 'label' => get_the_archive_title(), 'url' => '' );
	} else {
		$items[] = array( 'label' => get_the_title(), 'url' => '' );
	}

	echo '<nav class="pm-breadcrumb" aria-label="' . esc_attr__( 'Đường dẫn', 'prometal' ) . '">';
	echo '<ol class="pm-breadcrumb__list">';
	$last = count( $items ) - 1;
	foreach ( $items as $i => $item ) {
		$label = wp_strip_all_tags( (string) $item['label'] );
		if ( '' === $label ) {
			continue;
		}
		echo '<li class="pm-breadcrumb__item">';
		if ( ! empty( $item['url'] ) && $i !== $last ) {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $label ) . '</a>';
		} else {
			echo '<span aria-current="page">' . esc_html( $label ) . '</span>';
		}
		echo '</li>';
	}
	echo '</ol>';
	echo '</nav>';
}

/* ------------------------------------------------------------------ *
 *  Reading time
 * ------------------------------------------------------------------ */

/**
 * Thời gian đọc ước tính (200 từ/phút).
 *
 * @param int|WP_Post|null $post_id
 * @return string  VD: "5 phút đọc"
 */
function pm_reading_time( $post_id = null ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	$text  = wp_strip_all_tags( strip_shortcodes( $post->post_content ) );
	$words = preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY );
	$count = is_array( $words ) ? count( $words ) : 0;
	$mins  = max( 1, (int) ceil( $count / 200 ) );
	/* translators: %d: number of minutes. */
	return sprintf( _n( '%d phút đọc', '%d phút đọc', $mins, 'prometal' ), $mins );
}

/* ------------------------------------------------------------------ *
 *  Icon system
 * ------------------------------------------------------------------ */

/**
 * Thư viện icon nội tuyến (line 2 màu Pro-Metal, dùng currentColor để CSS đổi màu).
 * Có thể ghi đè bằng file assets/icons/{name}.svg (Session sau thêm sau).
 *
 * @return array<string,string>
 */
function prometal_icon_library() {
	$s = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
	return array(
		'phone'        => $s . '<path d="M6.5 3.5 9 8l-2 2a13 13 0 0 0 5 5l2-2 4.5 2.5v3.5A1.5 1.5 0 0 1 16.9 20 16 16 0 0 1 4 7.1 1.5 1.5 0 0 1 5.5 3.5z"/></svg>',
		'location'     => $s . '<path d="M12 21c5-5 7-8.2 7-11a7 7 0 1 0-14 0c0 2.8 2 6 7 11z"/><circle cx="12" cy="10" r="2.6"/></svg>',
		'clock'        => $s . '<circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/></svg>',
		'mail'         => $s . '<rect x="3.5" y="5.5" width="17" height="13" rx="2"/><path d="m4 7 8 6 8-6"/></svg>',
		'globe'        => $s . '<circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.5 2.4 2.5 14.6 0 17M12 3.5c-2.5 2.4-2.5 14.6 0 17"/></svg>',
		'chevron-down' => $s . '<path d="m6 9 6 6 6-6"/></svg>',
		'chevron-right'=> $s . '<path d="m9 6 6 6-6 6"/></svg>',
		'menu'         => $s . '<path d="M4 7h16M4 12h16M4 17h16"/></svg>',
		'close'        => $s . '<path d="m6 6 12 12M18 6 6 18"/></svg>',
		'arrow-up'     => $s . '<path d="M12 19V5M6 11l6-6 6 6"/></svg>',
		'search'       => $s . '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.2-3.2"/></svg>',
		'check'        => $s . '<path d="m5 12 4.5 4.5L19 7"/></svg>',
		'send'         => $s . '<path d="M21 4 3 11l6 2.5L12 20l3-7z"/><path d="M9 13.5 21 4"/></svg>',
		// Icon cột mega-menu (suy ra theo từ khoá tên mục — xem prometal_mega_col_icon()).
		'tool'         => $s . '<path d="M14.7 6.3a4 4 0 0 1-5.1 5.1L4 17l3 3 5.6-5.6a4 4 0 0 1 5.1-5.1l-2.6 2.6-2-2z"/></svg>',
		'home'         => $s . '<path d="m4 11 8-7 8 7"/><path d="M6 10.5V20h12v-9.5"/><path d="M10 20v-5.5h4V20"/></svg>',
		'roof'         => $s . '<path d="M3.5 12 12 5l8.5 7"/><path d="M6 20c1-1.2 2-1.2 3 0s2 1.2 3 0 2-1.2 3 0 2 1.2 3 0"/></svg>',
		'grid'         => $s . '<rect x="4" y="4" width="7" height="7" rx="1.5"/><rect x="13" y="4" width="7" height="7" rx="1.5"/><rect x="4" y="13" width="7" height="7" rx="1.5"/><rect x="13" y="13" width="7" height="7" rx="1.5"/></svg>',
		// Icon thương hiệu (glyph đặc, dùng currentColor).
		'facebook'     => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/></svg>',
		'zalo'         => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C6.9 3 2.75 6.53 2.75 10.88c0 2.5 1.36 4.72 3.48 6.16-.13.9-.53 2.06-1.2 2.86-.2.24-.03.6.28.55 1.7-.28 3.02-.86 3.95-1.4A11 11 0 0 0 12 19.6c5.1 0 9.25-3.53 9.25-8.72C21.25 6.53 17.1 3 12 3z"/></svg>',
		'telegram'     => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.5 4.3 2.9 11.5c-.9.36-.9.86-.16 1.08l4.72 1.47 1.82 5.6c.22.6.4.83.83.83.42 0 .6-.19.83-.6l2.28-3.5 4.9 3.63c.9.5 1.55.24 1.77-.83l3.2-15.1c.33-1.32-.5-1.92-1.4-1.5z"/></svg>',
	);
}

/**
 * Trả về markup SVG icon (echo bởi caller).
 *
 * @param string               $name  Tên icon.
 * @param array<string,scalar> $attrs class / width / height / aria-label...
 * @return string
 */
function pm_icon( $name, $attrs = array() ) {
	$name = sanitize_key( $name );

	$svg  = '';
	$file = PROMETAL_DIR . '/assets/icons/' . $name . '.svg';
	if ( is_readable( $file ) ) {
		$svg = (string) file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions
	} else {
		$lib = prometal_icon_library();
		if ( isset( $lib[ $name ] ) ) {
			$svg = $lib[ $name ];
		}
	}

	if ( '' === $svg ) {
		return '';
	}

	$attrs = wp_parse_args(
		$attrs,
		array(
			'class'  => '',
			'width'  => 24,
			'height' => 24,
		)
	);

	// A11y: có aria-label → role img; ngược lại ẩn với trợ lý đọc màn hình.
	if ( ! empty( $attrs['aria-label'] ) ) {
		$attrs['role'] = 'img';
	} else {
		$attrs['aria-hidden'] = 'true';
		$attrs['focusable']   = 'false';
	}

	// Ghép class: pm-icon + pm-icon--{name} + class caller (nếu có).
	$attrs['class'] = trim( 'pm-icon pm-icon--' . $name . ' ' . trim( (string) $attrs['class'] ) );

	return prometal_svg_set_attrs( $svg, $attrs );
}

/**
 * Gán/ghi đè thuộc tính lên thẻ <svg> gốc, escape an toàn.
 *
 * @param string               $svg
 * @param array<string,scalar> $attrs
 * @return string
 */
function prometal_svg_set_attrs( $svg, $attrs ) {
	return preg_replace_callback(
		'/<svg\b([^>]*)>/',
		function ( $m ) use ( $attrs ) {
			$open = $m[1];
			foreach ( $attrs as $key => $val ) {
				$key = preg_replace( '/[^a-zA-Z0-9:_-]/', '', (string) $key );
				if ( '' === $key ) {
					continue;
				}
				$val_esc = esc_attr( (string) $val );
				// Có sẵn attr → thay; chưa có → thêm.
				$pattern = '/\s' . preg_quote( $key, '/' ) . '="[^"]*"/';
				if ( preg_match( $pattern, $open ) ) {
					$open = preg_replace( $pattern, ' ' . $key . '="' . $val_esc . '"', $open );
				} else {
					$open .= ' ' . $key . '="' . $val_esc . '"';
				}
			}
			return '<svg' . $open . '>';
		},
		$svg,
		1
	);
}

/* ------------------------------------------------------------------ *
 *  Logo
 * ------------------------------------------------------------------ */

/**
 * Markup logo (link về trang chủ). Ưu tiên logo trắng (nền tối) ở header/footer.
 *
 * @param bool $light Dùng logo trắng cho nền tối.
 * @return string
 */
function pm_logo_html( $light = true ) {
	$home  = esc_url( home_url( '/' ) );
	$name  = get_bloginfo( 'name' );
	$inner = '';

	$logo_light_id = (int) pm_option( 'logo_light' );
	if ( $light && $logo_light_id ) {
		$img = wp_get_attachment_image(
			$logo_light_id,
			'full',
			false,
			array(
				'class' => 'pm-logo__img',
				'alt'   => $name,
			)
		);
		if ( $img ) {
			$inner = $img;
		}
	}

	if ( '' === $inner && ! $light && has_custom_logo() ) {
		$custom = get_custom_logo();
		if ( $custom ) {
			// get_custom_logo đã kèm link → trả nguyên.
			return '<div class="pm-logo pm-logo--custom">' . $custom . '</div>';
		}
	}

	if ( '' === $inner && has_custom_logo() ) {
		$logo_id = get_theme_mod( 'custom_logo' );
		$img     = wp_get_attachment_image(
			$logo_id,
			'full',
			false,
			array(
				'class' => 'pm-logo__img',
				'alt'   => $name,
			)
		);
		if ( $img ) {
			$inner = $img;
		}
	}

	if ( '' === $inner ) {
		$inner = '<span class="pm-logo__text">' . esc_html( $name ) . '</span>';
	}

	return '<a class="pm-logo' . ( $light ? ' pm-logo--light' : '' ) . '" href="' . $home . '" rel="home">' . $inner . '</a>';
}

/* ------------------------------------------------------------------ *
 *  Fallback menu (khi chưa gán Menu chính trong Giao diện → Menu)
 * ------------------------------------------------------------------ */

/**
 * Menu mặc định theo nhận diện Pro-Metal.
 * Hiển thị đúng cấu trúc khi site mới cài, admin sẽ thay bằng menu thật sau.
 *
 * @param array $args Đối số từ wp_nav_menu.
 */
function prometal_primary_menu_fallback( $args = array() ) {
	$menu_class = isset( $args['menu_class'] ) ? $args['menu_class'] : 'menu';
	$menu_id    = isset( $args['menu_id'] ) ? $args['menu_id'] : 'pm-primary-menu';

	$sua_chua = array(
		__( 'Sửa cửa sắt tại nhà', 'prometal' )  => '/dich-vu/sua-cua-sat-tai-nha/',
		__( 'Sửa cửa kéo', 'prometal' )          => '/dich-vu/sua-cua-keo/',
		__( 'Thợ hàn sắt tại nhà', 'prometal' )  => '/dich-vu/tho-han-sat-tai-nha/',
	);
	$thi_cong = array(
		__( 'Làm cửa/cổng sắt', 'prometal' )     => '/thi-cong/lam-cua-cong-sat/',
		__( 'Làm cửa kéo', 'prometal' )          => '/thi-cong/lam-cua-keo/',
		__( 'Mái hiên/mái che', 'prometal' )     => '/thi-cong/mai-hien-mai-che/',
		__( 'Cầu thang/lan can', 'prometal' )    => '/thi-cong/cau-thang-lan-can/',
		__( 'Vách ngăn panel', 'prometal' )      => '/thi-cong/vach-ngan-panel/',
	);

	echo '<ul id="' . esc_attr( $menu_id ) . '" class="' . esc_attr( $menu_class ) . '">';

	echo '<li class="menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Trang chủ', 'prometal' ) . '</a></li>';

	// Dịch vụ sửa chữa (submenu).
	echo '<li class="menu-item menu-item-has-children"><a href="' . esc_url( home_url( '/dich-vu-sua-chua/' ) ) . '">' . esc_html__( 'Dịch vụ sửa chữa', 'prometal' ) . '</a><ul class="sub-menu">';
	foreach ( $sua_chua as $label => $path ) {
		echo '<li class="menu-item"><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul></li>';

	// Thi công mới (submenu).
	echo '<li class="menu-item menu-item-has-children"><a href="' . esc_url( home_url( '/thi-cong-moi/' ) ) . '">' . esc_html__( 'Thi công mới', 'prometal' ) . '</a><ul class="sub-menu">';
	foreach ( $thi_cong as $label => $path ) {
		echo '<li class="menu-item"><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul></li>';

	echo '<li class="menu-item"><a href="' . esc_url( home_url( '/bao-gia-cua-sat/' ) ) . '">' . esc_html__( 'Báo giá cửa sắt', 'prometal' ) . '</a></li>';
	echo '<li class="menu-item"><a href="' . esc_url( home_url( '/gioi-thieu/' ) ) . '">' . esc_html__( 'Giới thiệu', 'prometal' ) . '</a></li>';
	echo '<li class="menu-item"><a href="' . esc_url( home_url( '/lien-he/' ) ) . '">' . esc_html__( 'Liên hệ', 'prometal' ) . '</a></li>';

	echo '</ul>';
}

/* ------------------------------------------------------------------ *
 *  Walker menu chính — mega-menu đa cấp (xem header.php)
 * ------------------------------------------------------------------ */

/**
 * Walker cho menu chính: dropdown đơn giản (dữ liệu hiện có, 2 cấp) tự nâng
 * cấp thành mega-menu nhiều cột khi admin thêm cấp con thứ 3 trong
 * Giao diện → Menu. Không đổi dữ liệu/menu hiện có, chỉ đổi cách render.
 *
 * Quy ước (tuỳ chọn, không bắt buộc):
 *  - Mục cấp 2 CÓ mục con   → hiển thị như 1 cột (icon tự suy ra từ tên mục
 *                              qua prometal_mega_col_icon(), danh sách link bên dưới).
 *  - Mục cấp 2 KHÔNG có con → hiển thị như 1 dòng link phẳng, y hệt dropdown
 *                              đơn giản trước đây (menu 2 cấp hiện tại không đổi giao diện).
 */
class Prometal_Mega_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * @param string $output Passed by reference.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '<div class="pm-mega">';
			return;
		}
		if ( 1 === $depth ) {
			$output .= '<ul class="pm-mega__list">';
			return;
		}
		parent::start_lvl( $output, $depth, $args );
	}

	/**
	 * @param string $output Passed by reference.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 === $depth ) {
			$output .= '</div>';
			return;
		}
		if ( 1 === $depth ) {
			$output .= '</ul>';
			return;
		}
		parent::end_lvl( $output, $depth, $args );
	}

	/**
	 * @param string $output Passed by reference.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		// Cấp 0 (mục chính trên thanh menu) — giữ nguyên hành vi mặc định của WP.
		if ( 0 === $depth ) {
			parent::start_el( $output, $item, $depth, $args, $id );
			return;
		}

		$url = ! empty( $item->url ) ? $item->url : '#';

		// Cấp 1 — cột trong mega-panel (nếu có con) hoặc 1 dòng link phẳng (nếu không).
		if ( 1 === $depth ) {
			$has_children = ! empty( $item->classes ) && in_array( 'menu-item-has-children', $item->classes, true );

			if ( $has_children ) {
				$output .= '<div class="pm-mega__col"><a class="pm-mega__col-heading" href="' . esc_url( $url ) . '">'
					. pm_icon( prometal_mega_col_icon( $item->title ), array( 'width' => 16, 'height' => 16 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					. '<span>' . esc_html( $item->title ) . '</span></a>';
			} else {
				$output .= '<div class="pm-mega__col pm-mega__col--flat"><a class="pm-mega__link" href="' . esc_url( $url ) . '">'
					. pm_icon( 'chevron-right', array( 'class' => 'pm-mega__link-icon', 'width' => 14, 'height' => 14 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					. '<span>' . esc_html( $item->title ) . '</span></a>';
			}
			return;
		}

		// Cấp 2 — link lá bên trong 1 cột.
		$output .= '<li class="pm-mega__item"><a class="pm-mega__link" href="' . esc_url( $url ) . '">'
			. pm_icon( 'chevron-right', array( 'class' => 'pm-mega__link-icon', 'width' => 14, 'height' => 14 ) ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			. '<span>' . esc_html( $item->title ) . '</span></a>';
	}

	/**
	 * @param string $output Passed by reference.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		if ( 1 === $depth ) {
			$output .= '</div>';
			return;
		}
		parent::end_el( $output, $item, $depth, $args );
	}
}

/**
 * Suy ra icon cho tiêu đề cột mega-menu từ từ khoá trong tên mục — admin chỉ
 * cần đặt tên mục như bình thường trong Giao diện → Menu, không cần cấu hình
 * thêm gì. Không khớp từ khoá nào → dùng icon mặc định ('grid').
 *
 * @param string $title Tên mục menu (cấp 2).
 * @return string Tên icon trong prometal_icon_library().
 */
function prometal_mega_col_icon( $title ) {
	$t = remove_accents( mb_strtolower( (string) $title ) );

	if ( false !== strpos( $t, 'mai' ) || false !== strpos( $t, 'chong tham' ) || false !== strpos( $t, 'dot' ) ) {
		return 'roof';
	}
	if ( false !== strpos( $t, 'thi cong' ) || false !== strpos( $t, 'lap dat' ) || false !== strpos( $t, 'xay' ) ) {
		return 'tool';
	}
	if ( false !== strpos( $t, 'sua' ) || false !== strpos( $t, 'bao tri' ) || false !== strpos( $t, 'tai nha' ) || false !== strpos( $t, 'han' ) ) {
		return 'home';
	}

	return 'grid';
}
