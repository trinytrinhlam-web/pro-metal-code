<?php
/**
 * Link tới trang dịch vụ — trỏ vào TRANG CÓ THẬT thay vì đường dẫn gõ cứng.
 *
 * Trước đây theme gõ cứng /dich-vu/..., /thi-cong/... (bố cục URL dự kiến) nhưng
 * site thật dùng slug khác (VD /dich-vu-sua-chua-cua-sat-tai-nha-hcm/) → thẻ dịch
 * vụ trang chủ, cột "Dịch vụ" ở footer, sidebar bài viết đều dẫn tới trang 404.
 *
 * pm_service_url( $key ) thử lần lượt các slug ứng viên, lấy trang đã xuất bản
 * đầu tiên tìm thấy; không có trang nào → giữ đường dẫn cũ (site mới cài).
 * Đổi/ghi đè bản đồ qua filter 'prometal_service_slugs'.
 *
 * @owner   Session M (Mobile)
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bản đồ khoá dịch vụ → slug trang ứng viên (ưu tiên trước → sau).
 * Phần tử CUỐI là đường dẫn dự phòng khi site chưa có trang nào khớp.
 *
 * @return array<string,string[]>
 */
function prometal_service_slugs() {
	return apply_filters(
		'prometal_service_slugs',
		array(
			'sua-cua-sat'      => array( 'dich-vu-sua-chua-cua-sat-tai-nha-hcm', 'dich-vu/sua-cua-sat-tai-nha' ),
			'sua-cua-keo'      => array( 'sua-cua-keo-tphcm', 'dich-vu/sua-cua-keo' ),
			'tho-han-sat'      => array( 'tho-han-sat-tai-nha-tphcm', 'dich-vu/tho-han-sat-tai-nha' ),
			'dich-vu-sua-chua' => array( 'dich-vu-sua-chua-cua-sat-tai-nha-hcm', 'dich-vu-sua-chua' ),
			'thi-cong-moi'     => array( 'thiet-ke-thi-cong-sua-chua-cua-sat-hcm', 'thi-cong-moi' ),
			'lam-cua-sat'      => array( 'thiet-ke-thi-cong-sua-chua-cua-sat-hcm', 'thi-cong/lam-cua-cong-sat' ),
			'lam-cua-keo'      => array( 'lam-cua-keo-tphcm', 'thi-cong/lam-cua-keo' ),
			'mai-hien'         => array( 'lam-mai-hien-tphcm', 'sua-mai-hien-mai-che', 'thi-cong/mai-hien-mai-che' ),
			// Trang "thiet-ke-thi-cong-sua-chua-cau-thang-lan-can" đang chuyển hướng 301 sang trang này.
			'cau-thang'        => array( 'lan-can-cau-thang-sat', 'thiet-ke-thi-cong-sua-chua-cau-thang-lan-can', 'thi-cong/cau-thang-lan-can' ),
			'vach-panel'       => array( 'thi-cong-vach-ngan-panel-tphcm', 'thi-cong/vach-ngan-panel' ),
			'nha-xuong'        => array( 'thi-cong-nha-xuong', 'thi-cong/nha-xuong-cong-ty' ),
			'inox'             => array( 'cua-cong-inox', 'lan-can-cau-thang-inox', 'dich-vu/inox' ),
			'cua-inox'         => array( 'cua-cong-inox', 'dich-vu/inox' ),
			'cau-thang-inox'   => array( 'lan-can-cau-thang-inox', 'dich-vu/inox' ),
			'inox-khac'        => array( 'mai-che-khung-inox', 'tho-han-inox-tai-nha', 'dich-vu/inox' ),
			'nhom-kinh'        => array( 'cua-nhom-kinh-xingfa', 'sua-cua-nhom-kinh', 'dich-vu/nhom-kinh' ),
			'bao-gia'          => array( 'bao-gia-cua-sat' ),
		)
	);
}

/**
 * URL trang dịch vụ theo khoá (xem prometal_service_slugs()).
 *
 * @param string $key Khoá dịch vụ.
 * @return string
 */
function pm_service_url( $key ) {
	static $resolved = array();
	if ( isset( $resolved[ $key ] ) ) {
		return $resolved[ $key ];
	}

	$map        = prometal_service_slugs();
	$candidates = isset( $map[ $key ] ) ? (array) $map[ $key ] : array();
	$url        = '';

	foreach ( $candidates as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			$url = get_permalink( $page );
			break;
		}
	}
	if ( '' === $url ) {
		$fallback = end( $candidates );
		$url      = $fallback ? home_url( '/' . trim( $fallback, '/' ) . '/' ) : home_url( '/' );
	}

	$resolved[ $key ] = $url;
	return $url;
}

/**
 * URL "Xem tất cả tin tức": trang Bài viết (nếu đặt) → chuyên mục "tin-tuc" →
 * chuyên mục của bài mới nhất → '' (ẩn nút) — tránh link /tin-tuc/ 404.
 *
 * @return string
 */
function pm_blog_url() {
	$blog_id = (int) get_option( 'page_for_posts' );
	if ( $blog_id ) {
		return (string) get_permalink( $blog_id );
	}

	$cat = get_category_by_slug( 'tin-tuc' );
	if ( $cat ) {
		return (string) get_category_link( $cat->term_id );
	}

	$latest = get_posts(
		array(
			'numberposts'      => 1,
			'fields'           => 'ids',
			'suppress_filters' => false,
		)
	);
	if ( $latest ) {
		$cats = get_the_category( $latest[0] );
		if ( $cats ) {
			return (string) get_category_link( $cats[0]->term_id );
		}
	}
	return '';
}
