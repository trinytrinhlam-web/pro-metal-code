<?php
/**
 * Trang chủ — 9 section (front-page).
 *
 * Chuyển thể từ nguồn thiết kế đã duyệt (demo-trangchu.html) sang PHP.
 * Chỉ điều phối: nạp 9 template-parts/home theo đúng thứ tự. Mỗi section tự
 * dựng markup + dữ liệu. Dùng get_header()/get_footer() của Session A.
 *
 * @owner   Session C
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main pm-home">

	<?php
	/*
	 * Thứ tự 9 section (khớp demo-trangchu.html):
	 * 1 Hero · 2 Giới thiệu · 3 Cam kết · 4 Cửa sắt · 5 Inox
	 * 6 Nhôm kính · 7 Liên hệ · 8 Đánh giá · 9 Tin tức.
	 */
	$pm_home_sections = array(
		'hero-slider',
		'brand-intro',
		'values',
		'services-sat',
		'services-inox',
		'aluminum',
		'contact-form',
		'testimonials',
		'news-latest',
	);

	foreach ( $pm_home_sections as $pm_section ) {
		get_template_part( 'template-parts/home/' . $pm_section );
	}
	?>

</main>

<?php
get_footer();
