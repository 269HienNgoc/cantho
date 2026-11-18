<?php
/**
 * Trang "Liên hệ – Đặt lịch" (page-templates/tpl-lien-he.php): chữ từ Customizer → "Trang Liên hệ".
 * Hotline / email / địa chỉ / giờ làm việc / ID form: "Thông tin phòng khám". Không có bản đồ nhúng (bỏ 2026-11-18).
 * Bố cục đơn giản từ 2026-11-18: banner chữ + thẻ "Thông tin liên hệ" + thẻ form; bỏ khối hotline, chi nhánh, mạng xã hội.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

/**
 * Chữ mặc định (dùng chung cho Customizer và giao diện). Setting: rhm_contact_{khóa}.
 */
function rhm_contact_defaults(): array {
	return array(
		'eyebrow'    => __( 'Luôn đồng hành cùng bạn', 'themeranghammatsgcantho' ),
		'intro'      => __( 'Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn.', 'themeranghammatsgcantho' ),
		'info_title' => __( 'Thông tin liên hệ', 'themeranghammatsgcantho' ),
		'form_title' => __( 'Đăng ký tư vấn', 'themeranghammatsgcantho' ),
		// Chỉ dùng ở khối đặt lịch của hồ sơ bác sĩ (template-parts/doctor/booking.php); trang Liên hệ không in.
		'form_note'  => __( 'Để lại thông tin, đội ngũ tư vấn sẽ gọi lại xác nhận lịch hẹn trong thời gian sớm nhất.', 'themeranghammatsgcantho' ),
	);
}

/**
 * Chữ của 1 khóa trong rhm_contact_defaults() (chưa escape).
 */
function rhm_contact_text( string $key ): string {
	return trim( (string) get_theme_mod( "rhm_contact_{$key}", rhm_contact_defaults()[ $key ] ?? '' ) );
}
