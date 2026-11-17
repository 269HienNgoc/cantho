<?php
/**
 * Layout dạng ô "Ô thông tin": tiêu đề vạch dọc + ảnh (trái) + chữ + nút (phải). Vd Giấy phép hoạt động, Hệ thống vô trùng.
 * Hiển thị: template-parts/landing/feature.php.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_feature_fields( string $prefix ): array {
	return array_merge(
		array(
			rhm_landing_field( $prefix, 'title', __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text' ),
			rhm_landing_image_field( $prefix, 'image', __( 'Ảnh (giữ nguyên tỉ lệ) — rộng ≥ 600px', 'themeranghammatsgcantho' ), array( 'wrapper' => array( 'width' => 40 ), 'instructions' => __( 'Chiếm 40% bề ngang ô, không bị cắt.', 'themeranghammatsgcantho' ) ) ),
			rhm_landing_rich_field( $prefix, 'text', __( 'Nội dung', 'themeranghammatsgcantho' ), array( 'wrapper' => array( 'width' => 60 ) ) ),
		),
		rhm_landing_button_fields( $prefix )
	);
}
