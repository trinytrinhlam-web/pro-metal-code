<?php
/**
 * Data model — Custom Post Types.
 *
 *  - pm_testimonial : đánh giá khách hàng (slider trang chủ).
 *  - pm_project     : công trình / gallery (landing, trang chủ).
 *  - pm_project_cat : phân loại công trình (cửa sắt, inox, nhôm kính…).
 *
 * @owner   Session A
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'prometal_register_cpt' );
function prometal_register_cpt() {

	/* ---- Đánh giá khách hàng ---- */
	register_post_type(
		'pm_testimonial',
		array(
			'labels'              => array(
				'name'               => __( 'Đánh giá', 'prometal' ),
				'singular_name'      => __( 'Đánh giá', 'prometal' ),
				'add_new'            => __( 'Thêm đánh giá', 'prometal' ),
				'add_new_item'       => __( 'Thêm đánh giá mới', 'prometal' ),
				'edit_item'          => __( 'Sửa đánh giá', 'prometal' ),
				'new_item'           => __( 'Đánh giá mới', 'prometal' ),
				'view_item'          => __( 'Xem đánh giá', 'prometal' ),
				'search_items'       => __( 'Tìm đánh giá', 'prometal' ),
				'not_found'          => __( 'Chưa có đánh giá', 'prometal' ),
				'not_found_in_trash' => __( 'Thùng rác trống', 'prometal' ),
				'all_items'          => __( 'Tất cả đánh giá', 'prometal' ),
				'menu_name'          => __( 'Đánh giá KH', 'prometal' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'has_archive'         => false,
			'hierarchical'        => false,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-format-quote',
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		)
	);

	/* ---- Công trình / gallery ---- */
	register_post_type(
		'pm_project',
		array(
			'labels'              => array(
				'name'               => __( 'Công trình', 'prometal' ),
				'singular_name'      => __( 'Công trình', 'prometal' ),
				'add_new'            => __( 'Thêm công trình', 'prometal' ),
				'add_new_item'       => __( 'Thêm công trình mới', 'prometal' ),
				'edit_item'          => __( 'Sửa công trình', 'prometal' ),
				'new_item'           => __( 'Công trình mới', 'prometal' ),
				'view_item'          => __( 'Xem công trình', 'prometal' ),
				'search_items'       => __( 'Tìm công trình', 'prometal' ),
				'not_found'          => __( 'Chưa có công trình', 'prometal' ),
				'not_found_in_trash' => __( 'Thùng rác trống', 'prometal' ),
				'all_items'          => __( 'Tất cả công trình', 'prometal' ),
				'menu_name'          => __( 'Công trình', 'prometal' ),
			),
			'public'              => true,
			'show_in_rest'        => true,
			'has_archive'         => true,
			'hierarchical'        => false,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-images-alt2',
			'rewrite'             => array( 'slug' => 'cong-trinh', 'with_front' => false ),
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
		)
	);

	/* ---- Phân loại công trình ---- */
	register_taxonomy(
		'pm_project_cat',
		'pm_project',
		array(
			'labels'            => array(
				'name'          => __( 'Loại công trình', 'prometal' ),
				'singular_name' => __( 'Loại công trình', 'prometal' ),
				'search_items'  => __( 'Tìm loại', 'prometal' ),
				'all_items'     => __( 'Tất cả loại', 'prometal' ),
				'edit_item'     => __( 'Sửa loại', 'prometal' ),
				'update_item'   => __( 'Cập nhật loại', 'prometal' ),
				'add_new_item'  => __( 'Thêm loại mới', 'prometal' ),
				'new_item_name' => __( 'Tên loại mới', 'prometal' ),
				'menu_name'     => __( 'Loại công trình', 'prometal' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'loai-cong-trinh', 'with_front' => false ),
		)
	);
}

/**
 * Flush rewrite rules 1 lần khi kích hoạt theme (để slug CPT/taxonomy hoạt động).
 */
add_action( 'after_switch_theme', 'prometal_cpt_flush' );
function prometal_cpt_flush() {
	prometal_register_cpt();
	flush_rewrite_rules();
}
