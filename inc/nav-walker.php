<?php
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
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
