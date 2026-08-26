<?php
/**
 * Template Name: Landing
 *
 * Landing nhân bản theo dịch vụ/khu vực. Nội dung dựng bằng các KHỐI (block)
 * cấu hình qua Carbon Fields (Complex `landing_blocks`): mỗi layout ↔ 1 file
 * trong template-parts/landing/*. Admin kéo–thả để sắp xếp; template lặp theo
 * đúng thứ tự đó.
 *
 * CHƯA cài / chưa cấu hình Carbon Fields → fallback dựng ĐỦ nội dung mặc định
 * (kế thừa LP11-preview.html). Mục lục (TOC) dính tự sinh từ các khối có mặt.
 * Dùng get_header()/get_footer() (Session A), component .pm-* (Session B),
 * toc.js + lightbox.js (Session B) đã enqueue theo template này.
 *
 * @owner   Session D
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	// Lấy danh sách khối: Carbon Fields → nếu rỗng thì fallback mặc định.
	$pm_blocks = array();
	if ( prometal_cf_active() ) {
		$pm_blocks = carbon_get_post_meta( get_the_ID(), 'landing_blocks' );
	}
	if ( empty( $pm_blocks ) ) {
		$pm_blocks = prometal_lp_default_blocks();
	}

	$pm_map      = prometal_lp_block_map();
	$pm_nav_meta = prometal_lp_nav_meta();

	// TOC: gom các khối có mặt (giữ nguyên thứ tự admin, khử trùng theo id).
	$pm_toc_items = array();
	foreach ( $pm_blocks as $pm_blk ) {
		$pm_type = isset( $pm_blk['_type'] ) ? $pm_blk['_type'] : '';
		if ( isset( $pm_nav_meta[ $pm_type ] ) ) {
			$pm_toc_items[ $pm_nav_meta[ $pm_type ]['id'] ] = $pm_nav_meta[ $pm_type ]['label'];
		}
	}
	?>

	<main id="main" class="site-main pm-lp">
		<?php
		$pm_toc_done = false;

		foreach ( $pm_blocks as $pm_blk ) {
			$pm_type = isset( $pm_blk['_type'] ) ? $pm_blk['_type'] : '';

			// Chèn mục lục dính ngay trước khối "có mục lục" đầu tiên.
			if ( ! $pm_toc_done && isset( $pm_nav_meta[ $pm_type ] ) && ! empty( $pm_toc_items ) ) {
				get_template_part( 'template-parts/landing/toc', null, array( 'items' => $pm_toc_items ) );
				$pm_toc_done = true;
			}

			if ( empty( $pm_type ) || ! isset( $pm_map[ $pm_type ] ) ) {
				continue;
			}
			get_template_part( 'template-parts/landing/' . $pm_map[ $pm_type ], null, $pm_blk );
		}
		?>
	</main>

	<?php
endwhile;

get_footer();
