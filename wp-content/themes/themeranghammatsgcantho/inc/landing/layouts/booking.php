<?php
/**
 * Layout "Kêu gọi đặt lịch": dùng lại template-parts/sections/booking-cta.php (nút đặt lịch + hotline).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_booking_fields( string $prefix ): array {
	return array(
		rhm_landing_field( $prefix, 'title', __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text', array( 'placeholder' => rhm_booking_cta_default( 'title' ) ) ),
		rhm_landing_field( $prefix, 'text', __( 'Mô tả', 'themeranghammatsgcantho' ), 'text', array( 'placeholder' => rhm_booking_cta_default( 'text' ), 'instructions' => __( 'Để trống: dùng chữ mặc định.', 'themeranghammatsgcantho' ) ) ),
	);
}
