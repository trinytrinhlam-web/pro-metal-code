<?php
/**
 * Endpoint REST /wp-json/prometal/v1/lead — nhận lead từ mọi form .pm-lead-form.
 *
 * - Đăng ký CPT nội bộ `pm_lead` (không public, admin xem/quản lý trong Dashboard).
 * - Route POST /prometal/v1/lead: verify nonce wp_rest, bẫy honeypot 'website',
 *   sanitize {ten, sdt, noidung, src}, LƯU thành 1 pm_lead + gửi mail admin (best-effort),
 *   trả JSON { ok:true }.
 *
 * @owner   Session B
 * @package Pro-Metal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ------------------------------------------------------------------ *
 *  CPT pm_lead — lưu trữ lead (riêng tư, chỉ admin thấy)
 * ------------------------------------------------------------------ */

add_action( 'init', 'prometal_register_lead_cpt' );
function prometal_register_lead_cpt() {
	register_post_type(
		'pm_lead',
		array(
			'labels'          => array(
				'name'          => __( 'Yêu cầu / Lead', 'prometal' ),
				'singular_name' => __( 'Lead', 'prometal' ),
				'menu_name'     => __( 'Yêu cầu liên hệ', 'prometal' ),
				'all_items'     => __( 'Tất cả yêu cầu', 'prometal' ),
				'search_items'  => __( 'Tìm yêu cầu', 'prometal' ),
				'not_found'     => __( 'Chưa có yêu cầu nào.', 'prometal' ),
			),
			'public'          => false,        // Không có mặt tiền công khai.
			'show_ui'         => true,         // Nhưng hiện trong admin để xem/quản lý.
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-phone',
			'menu_position'   => 26,
			'capability_type' => 'post',
			'capabilities'    => array(
				'create_posts' => 'do_not_allow', // Chỉ tạo qua form, không tạo tay.
			),
			'map_meta_cap'    => true,
			'supports'        => array( 'title' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'query_var'       => false,
			'exclude_from_search' => true,
		)
	);
}

/* ------------------------------------------------------------------ *
 *  REST route
 * ------------------------------------------------------------------ */

add_action( 'rest_api_init', 'prometal_register_lead_route' );
function prometal_register_lead_route() {
	register_rest_route(
		'prometal/v1',
		'/lead',
		array(
			'methods'             => 'POST',
			'callback'            => 'prometal_handle_lead',
			'permission_callback' => '__return_true', // Public: kiểm nonce + honeypot bên trong.
		)
	);
}

/**
 * Xử lý 1 lead gửi lên.
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response  { ok:bool, message:string }
 */
function prometal_handle_lead( WP_REST_Request $request ) {

	// 1) Verify nonce 'wp_rest' (form.js gửi qua header X-WP-Nonce hoặc field _wpnonce).
	$nonce = $request->get_header( 'x_wp_nonce' );
	if ( ! $nonce ) {
		$nonce = (string) $request->get_param( '_wpnonce' );
	}
	if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
		return prometal_lead_response(
			false,
			__( 'Phiên đã hết hạn, vui lòng tải lại trang và thử lại.', 'prometal' ),
			403
		);
	}

	// 2) Honeypot: người thật để trống 'website'. Có giá trị => bot → giả vờ thành công.
	if ( '' !== trim( (string) $request->get_param( 'website' ) ) ) {
		return prometal_lead_response( true, prometal_lead_success_msg() );
	}

	// 3) Sanitize dữ liệu.
	$ten     = sanitize_text_field( (string) $request->get_param( 'ten' ) );
	$sdt_raw = sanitize_text_field( (string) $request->get_param( 'sdt' ) );
	$noidung = sanitize_textarea_field( (string) $request->get_param( 'noidung' ) );
	$src     = sanitize_text_field( (string) $request->get_param( 'src' ) );

	$ten   = trim( wp_unslash( $ten ) );
	$sdt   = trim( wp_unslash( $sdt_raw ) );
	$digits = preg_replace( '/\D+/', '', $sdt );

	// 4) Validate tối thiểu: tên + SĐT hợp lệ.
	if ( '' === $ten ) {
		return prometal_lead_response( false, __( 'Vui lòng nhập họ tên.', 'prometal' ), 400 );
	}
	if ( strlen( $digits ) < 8 || strlen( $digits ) > 15 ) {
		return prometal_lead_response( false, __( 'Số điện thoại chưa hợp lệ, vui lòng kiểm tra lại.', 'prometal' ), 400 );
	}

	// 5) Lưu thành pm_lead. Đây là transaction bắt buộc; thông báo được đẩy nền sau đó.
	$title   = sprintf( '%1$s — %2$s', $ten, $sdt );
	$lead_id = wp_insert_post(
		array(
			'post_type'   => 'pm_lead',
			'post_status' => 'private',
			'post_title'  => $title,
			'meta_input'  => array(
				'_pm_ten'     => $ten,
				'_pm_sdt'     => $sdt,
				'_pm_noidung' => $noidung,
				'_pm_src'     => $src,
				'_pm_ip'      => prometal_lead_client_ip(),
				'_pm_ua'      => sanitize_text_field( (string) $request->get_header( 'user_agent' ) ),
			),
		),
		true
	);

	if ( is_wp_error( $lead_id ) || ! $lead_id ) {
		return prometal_lead_response(
			false,
			__( 'Không lưu được yêu cầu, vui lòng gọi hotline giúp chúng tôi.', 'prometal' ),
			500
		);
	}

	// 6) Đẩy thông báo nền: phản hồi cho khách không phải chờ SMTP/Telegram/CRM.
	prometal_queue_lead_notification( $lead_id );

	return prometal_lead_response( true, prometal_lead_success_msg() );
}

/**
 * Hàng đợi thông báo dùng WP-Cron. Lead đã được lưu trước khi tác vụ chạy nên
 * mọi đích gửi lỗi vẫn có bản ghi trong Dashboard để xử lý lại.
 *
 * @param int $lead_id Lead ID.
 * @return void
 */
function prometal_queue_lead_notification( $lead_id ) {
	$lead_id = absint( $lead_id );
	if ( ! $lead_id || ! wp_next_scheduled( 'prometal_dispatch_lead_notification', array( $lead_id ) ) ) {
		wp_schedule_single_event( time(), 'prometal_dispatch_lead_notification', array( $lead_id ) );
	}

	// WordPress tự gọi cron dạng non-blocking; lỗi thông báo không làm chậm form.
	if ( function_exists( 'spawn_cron' ) ) {
		spawn_cron();
	}
}

add_action( 'prometal_dispatch_lead_notification', 'prometal_dispatch_lead_notification' );
/**
 * Gửi tất cả thông báo từ đúng một nơi.
 *
 * @param int $lead_id Lead ID.
 * @return void
 */
function prometal_dispatch_lead_notification( $lead_id ) {
	$lead_id = absint( $lead_id );
	if ( ! $lead_id || 'pm_lead' !== get_post_type( $lead_id ) ) {
		return;
	}

	$data = array(
		'ten'     => (string) get_post_meta( $lead_id, '_pm_ten', true ),
		'sdt'     => (string) get_post_meta( $lead_id, '_pm_sdt', true ),
		'noidung' => (string) get_post_meta( $lead_id, '_pm_noidung', true ),
		'src'     => (string) get_post_meta( $lead_id, '_pm_src', true ),
	);

	prometal_lead_notify_admin( $data );
	prometal_lead_notify_telegram( $data );

	/**
	 * Điểm tích hợp duy nhất cho CRM/Zalo OA/plugin hiện có. Hook chạy nền để
	 * integration chậm không ảnh hưởng thời gian submit.
	 */
	do_action( 'prometal_lead_saved', $lead_id, $data );
}

/* ------------------------------------------------------------------ *
 *  Helpers
 * ------------------------------------------------------------------ */

/**
 * Chuẩn hoá phản hồi JSON.
 *
 * @param bool   $ok
 * @param string $message
 * @param int    $status
 * @return WP_REST_Response
 */
function prometal_lead_response( $ok, $message, $status = 200 ) {
	return new WP_REST_Response(
		array(
			'ok'      => (bool) $ok,
			'message' => (string) $message,
		),
		$status
	);
}

/**
 * Lời cảm ơn mặc định khi gửi thành công.
 *
 * @return string
 */
function prometal_lead_success_msg() {
	return __( 'Đã nhận yêu cầu! Pro-Metal sẽ gọi lại tư vấn trong ít phút.', 'prometal' );
}

/**
 * Gửi email thông báo cho admin. Có thể tắt qua filter prometal_lead_send_mail.
 *
 * @param array{ten:string,sdt:string,noidung:string,src:string} $data
 * @return void
 */
function prometal_lead_notify_admin( $data ) {
	if ( ! apply_filters( 'prometal_lead_send_mail', true, $data ) ) {
		return;
	}

	$to = apply_filters( 'prometal_lead_mail_to', get_option( 'admin_email' ), $data );
	if ( ! is_email( $to ) ) {
		return;
	}

	$site    = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$subject = sprintf(
		/* translators: %s: khách hàng. */
		__( '[%1$s] Yêu cầu mới từ %2$s', 'prometal' ),
		$site,
		$data['ten']
	);

	$lines = array(
		__( 'Có yêu cầu liên hệ mới:', 'prometal' ),
		'',
		sprintf( __( 'Họ tên: %s', 'prometal' ), $data['ten'] ),
		sprintf( __( 'Điện thoại: %s', 'prometal' ), $data['sdt'] ),
		sprintf( __( 'Nội dung: %s', 'prometal' ), '' !== $data['noidung'] ? $data['noidung'] : '—' ),
		sprintf( __( 'Nguồn: %s', 'prometal' ), '' !== $data['src'] ? $data['src'] : '—' ),
		sprintf( __( 'Thời gian: %s', 'prometal' ), wp_date( 'H:i d/m/Y' ) ),
	);

	wp_mail( $to, $subject, implode( "\n", $lines ) );
}

/**
 * Gửi Telegram qua Bot API nếu cấu hình bảo mật đã có trong wp-config.php.
 * Không lưu bot token trong database/theme. Có thể thay bằng filters khi dùng
 * một dịch vụ trung gian.
 *
 * @param array{ten:string,sdt:string,noidung:string,src:string} $data Lead data.
 * @return void
 */
function prometal_lead_notify_telegram( $data ) {
	$token = defined( 'PROMETAL_TELEGRAM_BOT_TOKEN' ) ? PROMETAL_TELEGRAM_BOT_TOKEN : '';
	$chat  = defined( 'PROMETAL_TELEGRAM_CHAT_ID' ) ? PROMETAL_TELEGRAM_CHAT_ID : '';
	$token = apply_filters( 'prometal_lead_telegram_bot_token', $token, $data );
	$chat  = apply_filters( 'prometal_lead_telegram_chat_id', $chat, $data );

	if ( ! is_string( $token ) || '' === trim( $token ) || ! is_scalar( $chat ) || '' === trim( (string) $chat ) ) {
		return;
	}

	$lines = array(
		'YEU CAU MOI - ' . wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		'Ho ten: ' . $data['ten'],
		'Dien thoai: ' . $data['sdt'],
		'Noi dung: ' . ( '' !== $data['noidung'] ? $data['noidung'] : '-' ),
		'Nguon: ' . ( '' !== $data['src'] ? $data['src'] : '-' ),
		'Thoi gian: ' . wp_date( 'H:i d/m/Y' ),
	);

	wp_remote_post(
		'https://api.telegram.org/bot' . rawurlencode( trim( $token ) ) . '/sendMessage',
		array(
			'timeout' => 10,
			'body'    => array(
				'chat_id' => (string) $chat,
				'text'    => implode( "\n", $lines ),
			),
		)
	);
}

/**
 * Lấy IP client (thô, chỉ để tham khảo trong admin).
 *
 * @return string
 */
function prometal_lead_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? wp_unslash( $_SERVER['REMOTE_ADDR'] ) : '';
	$ip = preg_replace( '/[^0-9a-fA-F:.]/', '', (string) $ip );
	return substr( $ip, 0, 45 );
}

/* ------------------------------------------------------------------ *
 *  Admin: hiển thị cột thông tin lead trong danh sách
 * ------------------------------------------------------------------ */

add_filter( 'manage_pm_lead_posts_columns', 'prometal_lead_columns' );
function prometal_lead_columns( $columns ) {
	$new = array(
		'cb'         => isset( $columns['cb'] ) ? $columns['cb'] : '',
		'title'      => __( 'Khách hàng', 'prometal' ),
		'pm_sdt'     => __( 'Điện thoại', 'prometal' ),
		'pm_noidung' => __( 'Nội dung', 'prometal' ),
		'pm_src'     => __( 'Nguồn', 'prometal' ),
		'date'       => __( 'Ngày gửi', 'prometal' ),
	);
	return $new;
}

add_action( 'manage_pm_lead_posts_custom_column', 'prometal_lead_column_content', 10, 2 );
function prometal_lead_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'pm_sdt':
			$sdt = (string) get_post_meta( $post_id, '_pm_sdt', true );
			if ( '' !== $sdt ) {
				echo '<a href="tel:' . esc_attr( preg_replace( '/\D+/', '', $sdt ) ) . '">' . esc_html( $sdt ) . '</a>';
			}
			break;
		case 'pm_noidung':
			echo esc_html( wp_trim_words( (string) get_post_meta( $post_id, '_pm_noidung', true ), 12 ) );
			break;
		case 'pm_src':
			echo esc_html( (string) get_post_meta( $post_id, '_pm_src', true ) );
			break;
	}
}
