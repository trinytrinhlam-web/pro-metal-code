<?php
/**
 * Tùy biến nội dung Landing bằng Carbon Fields (miễn phí).
 *
 * - Nạp thư viện Carbon Fields nếu có (composer `vendor/` hoặc bundle
 *   `inc/carbon-fields/vendor/`), boot ở `after_setup_theme`.
 * - Đăng ký:
 *     • Post meta Complex `landing_blocks` (chỉ trên Page dùng template Landing).
 *       Mỗi LAYOUT ↔ 1 file trong template-parts/landing/*.
 *     • Theme Option Complex `pm_hero_slides` (ảnh hero trang chủ).
 * - Bắc cầu filter `prometal_hero_slides` để Session C (trang chủ) đổi ảnh theo
 *   Theme Option — KHÔNG sửa file của Session C.
 * - Helper dùng chung cho template-parts/landing (đọc field + fallback LP11).
 *
 * CHƯA cài Carbon Fields cũng KHÔNG lỗi: template-landing.php tự fallback toàn bộ
 * nội dung mặc định (LP11). Cài Carbon Fields (composer require htmlburger/carbon-fields
 * trong thư mục theme, hoặc thả bundle vào inc/carbon-fields/) để bật tùy biến.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ *
 *  1. Nạp + boot Carbon Fields (nếu có)
 * ------------------------------------------------------------------ */

if ( ! class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
	foreach (
		array(
			PROMETAL_DIR . '/vendor/autoload.php',
			PROMETAL_DIR . '/inc/carbon-fields/vendor/autoload.php',
		) as $prometal_cf_autoload
	) {
		if ( is_readable( $prometal_cf_autoload ) ) {
			require_once $prometal_cf_autoload;
			break;
		}
	}
	unset( $prometal_cf_autoload );
}

add_action( 'after_setup_theme', 'prometal_boot_carbon_fields', 20 );
/**
 * Boot Carbon Fields sau khi theme setup.
 */
function prometal_boot_carbon_fields() {
	if ( class_exists( '\Carbon_Fields\Carbon_Fields' ) ) {
		\Carbon_Fields\Carbon_Fields::boot();
	}
}

/**
 * Carbon Fields đã sẵn sàng chưa (đã cài + boot).
 *
 * @return bool
 */
function prometal_cf_active() {
	return function_exists( 'carbon_get_post_meta' );
}

/* ------------------------------------------------------------------ *
 *  2. Đăng ký field
 * ------------------------------------------------------------------ */

add_action( 'carbon_fields_register_fields', 'prometal_register_landing_fields' );
/**
 * Complex `landing_blocks` + Theme Option `pm_hero_slides`.
 */
function prometal_register_landing_fields() {
	if ( ! class_exists( '\Carbon_Fields\Container\Container' ) ) {
		return;
	}

	/* ---- Landing blocks (post meta) ---- */
	Container::make( 'post_meta', __( 'Nội dung Landing (Pro-Metal)', 'prometal' ) )
		->where( 'post_template', '=', 'template-landing.php' )
		->add_fields(
			array(
				Field::make( 'complex', 'landing_blocks', __( 'Khối nội dung', 'prometal' ) )
					->set_layout( 'tabbed-vertical' )
					->set_collapsed( true )
					->setup_labels(
						array(
							'plural_name'   => __( 'Khối', 'prometal' ),
							'singular_name' => __( 'Khối', 'prometal' ),
						)
					)

					/* Hero dịch vụ */
					->add_fields(
						'hero',
						__( 'Hero dịch vụ', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề H1', 'prometal' ) ),
							Field::make( 'textarea', 'sub', __( 'Mô tả ngắn', 'prometal' ) )->set_rows( 3 ),
							Field::make( 'complex', 'usp', __( 'Gạch đầu dòng (USP)', 'prometal' ) )
								->add_fields( array( Field::make( 'text', 'text', __( 'Nội dung', 'prometal' ) ) ) ),
							Field::make( 'image', 'image', __( 'Ảnh minh hoạ', 'prometal' ) ),
						)
					)

					/* Trust bar */
					->add_fields(
						'trust_bar',
						__( 'Thanh niềm tin', 'prometal' ),
						array(
							Field::make( 'complex', 'items', __( 'Chỉ số', 'prometal' ) )
								->set_max( 4 )
								->add_fields(
									array(
										Field::make( 'text', 'big', __( 'Dòng nổi bật', 'prometal' ) ),
										Field::make( 'text', 'small', __( 'Ghi chú', 'prometal' ) ),
									)
								),
						)
					)

					/* Intro */
					->add_fields(
						'intro',
						__( 'Đoạn mở đầu', 'prometal' ),
						array(
							Field::make( 'rich_text', 'content', __( 'Nội dung', 'prometal' ) ),
						)
					)

					/* Cards ứng dụng */
					->add_fields(
						'cards',
						__( 'Thẻ ứng dụng', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'lead', __( 'Mô tả khối', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'complex', 'cards', __( 'Thẻ', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'image', 'icon', __( 'Icon (ảnh)', 'prometal' ) ),
										Field::make( 'text', 'title', __( 'Tiêu đề', 'prometal' ) ),
										Field::make( 'textarea', 'text', __( 'Mô tả', 'prometal' ) )->set_rows( 3 ),
									)
								),
						)
					)

					/* Gallery */
					->add_fields(
						'gallery',
						__( 'Thư viện ảnh', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'lead', __( 'Mô tả khối', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'complex', 'images', __( 'Ảnh', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'image', 'image', __( 'Ảnh', 'prometal' ) ),
										Field::make( 'text', 'caption', __( 'Chú thích', 'prometal' ) ),
									)
								),
						)
					)

					/* Bảng loại panel */
					->add_fields(
						'price_table',
						__( 'Bảng loại panel', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'lead', __( 'Mô tả khối', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'complex', 'rows', __( 'Dòng bảng', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'text', 'name', __( 'Loại panel', 'prometal' ) ),
										Field::make( 'textarea', 'fit', __( 'Phù hợp', 'prometal' ) )->set_rows( 2 ),
										Field::make( 'textarea', 'note', __( 'Ghi chú tư vấn', 'prometal' ) )->set_rows( 2 ),
										Field::make( 'text', 'level_label', __( 'Nhãn chi phí', 'prometal' ) ),
										Field::make( 'select', 'level', __( 'Mức chi phí', 'prometal' ) )
											->set_options(
												array(
													'l1' => __( 'Hợp lý', 'prometal' ),
													'l2' => __( 'Cao hơn', 'prometal' ),
													'l3' => __( 'Theo yêu cầu', 'prometal' ),
												)
											),
									)
								),
							Field::make( 'text', 'cta_title', __( 'CTA — tiêu đề', 'prometal' ) ),
							Field::make( 'textarea', 'cta_text', __( 'CTA — mô tả', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'text', 'cta_label', __( 'CTA — nhãn nút', 'prometal' ) ),
							Field::make( 'text', 'cta_link', __( 'CTA — link (trống = Zalo)', 'prometal' ) ),
						)
					)

					/* Yếu tố chi phí */
					->add_fields(
						'cost_factors',
						__( 'Yếu tố chi phí', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'lead', __( 'Mô tả khối', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'complex', 'items', __( 'Yếu tố', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'text', 'title', __( 'Tiêu đề', 'prometal' ) ),
										Field::make( 'textarea', 'text', __( 'Mô tả', 'prometal' ) )->set_rows( 3 ),
									)
								),
						)
					)

					/* Quy trình */
					->add_fields(
						'process',
						__( 'Quy trình thi công', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'lead', __( 'Mô tả khối', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'image', 'image', __( 'Ảnh minh hoạ', 'prometal' ) ),
							Field::make( 'complex', 'steps', __( 'Bước', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'text', 'title', __( 'Tên bước', 'prometal' ) ),
										Field::make( 'textarea', 'text', __( 'Mô tả', 'prometal' ) )->set_rows( 2 ),
									)
								),
						)
					)

					/* Cam kết */
					->add_fields(
						'commitment',
						__( 'Cam kết', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'complex', 'items', __( 'Cam kết', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'text', 'title', __( 'Tiêu đề', 'prometal' ) ),
										Field::make( 'textarea', 'text', __( 'Mô tả', 'prometal' ) )->set_rows( 2 ),
									)
								),
							Field::make( 'complex', 'tiles', __( 'Ô lợi ích (icon)', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'image', 'icon', __( 'Icon (ảnh)', 'prometal' ) ),
										Field::make( 'text', 'label', __( 'Nhãn', 'prometal' ) ),
									)
								),
						)
					)

					/* Khu vực + chi nhánh */
					->add_fields(
						'areas',
						__( 'Khu vực & chi nhánh', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'lead', __( 'Mô tả khối', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'complex', 'areas', __( 'Khu vực', 'prometal' ) )
								->add_fields( array( Field::make( 'text', 'name', __( 'Tên khu vực', 'prometal' ) ) ) ),
							Field::make( 'complex', 'branches', __( 'Chi nhánh', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'text', 'title', __( 'Tên chi nhánh', 'prometal' ) ),
										Field::make( 'textarea', 'address', __( 'Địa chỉ', 'prometal' ) )->set_rows( 2 ),
										Field::make( 'text', 'phone', __( 'Hotline & Zalo', 'prometal' ) ),
										Field::make( 'checkbox', 'hq', __( 'Là trụ sở chính', 'prometal' ) ),
									)
								),
						)
					)

					/* FAQ */
					->add_fields(
						'faq',
						__( 'Câu hỏi thường gặp', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'complex', 'items', __( 'Câu hỏi', 'prometal' ) )
								->add_fields(
									array(
										Field::make( 'text', 'question', __( 'Câu hỏi', 'prometal' ) ),
										Field::make( 'rich_text', 'answer', __( 'Trả lời', 'prometal' ) ),
									)
								),
						)
					)

					/* Form báo giá */
					->add_fields(
						'quote_form',
						__( 'Form báo giá', 'prometal' ),
						array(
							Field::make( 'text', 'heading', __( 'Tiêu đề khối', 'prometal' ) ),
							Field::make( 'textarea', 'text', __( 'Mô tả', 'prometal' ) )->set_rows( 3 ),
							Field::make( 'image', 'image', __( 'Ảnh minh hoạ', 'prometal' ) ),
						)
					),
			)
		);

	/* ---- Ảnh hero trang chủ (theme option) ---- */
	Container::make( 'theme_options', __( 'Pro-Metal — Trang chủ', 'prometal' ) )
		->set_page_parent( 'themes.php' )
		->add_fields(
			array(
				Field::make( 'complex', 'pm_hero_slides', __( 'Ảnh hero trang chủ', 'prometal' ) )
					->set_help_text( __( 'Bỏ trống để dùng ảnh mặc định trong theme.', 'prometal' ) )
					->add_fields(
						array(
							Field::make( 'image', 'image', __( 'Ảnh', 'prometal' ) )->set_width( 50 ),
							Field::make( 'text', 'heading', __( 'Tiêu đề / mô tả ảnh (alt)', 'prometal' ) )->set_width( 50 ),
							Field::make( 'textarea', 'sub', __( 'Mô tả phụ', 'prometal' ) )->set_rows( 2 ),
							Field::make( 'text', 'cta_label', __( 'Nhãn nút', 'prometal' ) )->set_width( 50 ),
							Field::make( 'text', 'cta_link', __( 'Link nút', 'prometal' ) )->set_width( 50 ),
						)
					),
			)
		);
}

/* ------------------------------------------------------------------ *
 *  3. Bắc cầu ảnh hero cho Session C (trang chủ) — KHÔNG sửa file C
 * ------------------------------------------------------------------ */

add_filter( 'prometal_hero_slides', 'prometal_bridge_hero_slides' );
/**
 * Ghi đè slide hero trang chủ bằng Theme Option (nếu admin đã cấu hình).
 * Rỗng → trả mặc định để Session C fallback ảnh bundle.
 *
 * @param array $default Slide mặc định của Session C ([file, alt]).
 * @return array
 */
function prometal_bridge_hero_slides( $default ) {
	if ( ! function_exists( 'carbon_get_theme_option' ) ) {
		return $default;
	}
	$rows = carbon_get_theme_option( 'pm_hero_slides' );
	if ( empty( $rows ) ) {
		return $default; // Rỗng → để C fallback ảnh bundle.
	}
	$out = array();
	foreach ( (array) $rows as $r ) {
		$url = ! empty( $r['image'] ) ? wp_get_attachment_image_url( (int) $r['image'], 'full' ) : '';
		if ( $url ) {
			$out[] = array(
				'file' => $url,
				'alt'  => isset( $r['heading'] ) ? (string) $r['heading'] : '',
			);
		}
	}
	return $out ? $out : $default;
}

/* ------------------------------------------------------------------ *
 *  4. Helper dùng chung cho template-parts/landing
 * ------------------------------------------------------------------ */

/**
 * Đọc 1 giá trị từ block; rỗng → trả mặc định.
 *
 * @param mixed  $block   Mảng block (từ carbon_get_post_meta) hoặc rỗng.
 * @param string $key     Khóa field.
 * @param mixed  $default Giá trị mặc định (fallback LP11).
 * @return mixed
 */
function prometal_lp_get( $block, $key, $default = '' ) {
	if ( is_array( $block ) && isset( $block[ $key ] ) ) {
		$val = $block[ $key ];
		if ( '' !== $val && array() !== $val && null !== $val ) {
			return $val;
		}
	}
	return $default;
}

/**
 * Markup <img> từ field ảnh (ID attachment hoặc URL); rỗng → chuỗi rỗng.
 *
 * @param mixed  $img   ID attachment hoặc URL.
 * @param string $alt   Văn bản thay thế.
 * @param string $class Class CSS.
 * @param string $size  Kích thước ảnh (mặc định 'large').
 * @return string
 */
function prometal_lp_img( $img, $alt = '', $class = '', $size = 'large' ) {
	if ( empty( $img ) ) {
		return '';
	}
	if ( is_numeric( $img ) ) {
		return (string) wp_get_attachment_image(
			(int) $img,
			$size,
			false,
			array(
				'class'   => $class,
				'alt'     => $alt,
				'loading' => 'lazy',
			)
		);
	}
	return '<img src="' . esc_url( $img ) . '" alt="' . esc_attr( $alt ) . '" class="' . esc_attr( $class ) . '" loading="lazy">';
}

/**
 * Ô ảnh giữ chỗ (khi chưa có ảnh thật) — tránh ảnh vỡ.
 *
 * @param string $label Nhãn hiển thị.
 * @param string $class Class bổ sung.
 * @return string
 */
function prometal_lp_ph( $label = '', $class = '' ) {
	return '<div class="pm-lp-ph ' . esc_attr( $class ) . '" aria-hidden="true"><span>' . esc_html( $label ) . '</span></div>';
}

/**
 * Bản đồ layout → file template-parts/landing. Nhiều layout có thể dùng chung 1 file
 * (file tự nhánh theo $args['_type']).
 *
 * @return array<string,string>
 */
function prometal_lp_block_map() {
	return array(
		'hero'         => 'hero',
		'trust_bar'    => 'trust-bar',
		'intro'        => 'cards',
		'cards'        => 'cards',
		'gallery'      => 'gallery',
		'price_table'  => 'price-table',
		'cost_factors' => 'price-table',
		'process'      => 'process',
		'commitment'   => 'process',
		'areas'        => 'areas',
		'faq'          => 'faq',
		'quote_form'   => 'quote-form',
	);
}

/**
 * Thứ tự block mặc định (khi chưa cấu hình Carbon Fields) — dựng đủ LP11.
 *
 * @return array<int,array{_type:string}>
 */
function prometal_lp_default_blocks() {
	$order = array(
		'hero',
		'trust_bar',
		'intro',
		'cards',
		'gallery',
		'price_table',
		'cost_factors',
		'process',
		'commitment',
		'areas',
		'faq',
		'quote_form',
	);
	return array_map(
		function ( $type ) {
			return array( '_type' => $type );
		},
		$order
	);
}

/**
 * Meta mục lục (TOC): layout nào có mặt trong TOC + id + nhãn.
 * Cũng là NGUỒN CHÂN LÝ id section (part đọc id của mình từ đây theo _type).
 *
 * @return array<string,array{id:string,label:string}>
 */
function prometal_lp_nav_meta() {
	return array(
		'cards'        => array(
			'id'    => 'ung-dung',
			'label' => __( 'Ứng dụng', 'prometal' ),
		),
		'gallery'      => array(
			'id'    => 'anh-thuc-te',
			'label' => __( 'Ảnh thật', 'prometal' ),
		),
		'price_table'  => array(
			'id'    => 'loai-panel',
			'label' => __( 'Loại panel', 'prometal' ),
		),
		'cost_factors' => array(
			'id'    => 'chi-phi',
			'label' => __( 'Chi phí', 'prometal' ),
		),
		'process'      => array(
			'id'    => 'quy-trinh',
			'label' => __( 'Quy trình', 'prometal' ),
		),
		'commitment'   => array(
			'id'    => 'cam-ket',
			'label' => __( 'Cam kết', 'prometal' ),
		),
		'areas'        => array(
			'id'    => 'khu-vuc',
			'label' => __( 'Khu vực', 'prometal' ),
		),
		'faq'          => array(
			'id'    => 'faq',
			'label' => __( 'Hỏi đáp', 'prometal' ),
		),
		'quote_form'   => array(
			'id'    => 'lien-he',
			'label' => __( 'Báo giá', 'prometal' ),
		),
	);
}

/**
 * Id section của 1 block theo _type (từ nav meta). Không có → ''.
 *
 * @param string $type
 * @return string
 */
function prometal_lp_section_id( $type ) {
	$map = prometal_lp_nav_meta();
	return isset( $map[ $type ] ) ? $map[ $type ]['id'] : '';
}
