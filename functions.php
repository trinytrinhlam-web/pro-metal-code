<?php
/**
 * Pro-Metal theme bootstrap.
 *
 * @owner   Session A (Core Foundations)
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PROMETAL_VERSION', '0.3.1' );
define( 'PROMETAL_DIR', get_template_directory() );
define( 'PROMETAL_URI', get_template_directory_uri() );

/**
 * Tự động nạp mọi file trong /inc theo thứ tự alphabet.
 *
 * → Muốn thêm logic mới: TẠO FILE MỚI trong /inc, nó tự chạy.
 *   KHÔNG cần sửa functions.php  ==> tránh xung đột giữa các session.
 */
foreach ( glob( PROMETAL_DIR . '/inc/*.php' ) as $prometal_inc ) {
	require_once $prometal_inc;
}
unset( $prometal_inc );
