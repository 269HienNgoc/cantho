<?php
/**
 * Layout "Danh sách dịch vụ": thẻ ảnh + tên (có link). Hiển thị: template-parts/landing/services.php.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_services_fields( string $prefix ): array {
	$item = $prefix . '_items';
	return array(
		rhm_landing_field( $prefix, 'title', __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text', array( 'placeholder' => __( 'Đa dạng dịch vụ nha khoa', 'themeranghammatsgcantho' ) ) ),
		rhm_landing_rich_field( $prefix, 'description', __( 'Mô tả ngắn', 'themeranghammatsgcantho' ) ),
		rhm_landing_field(
			$prefix,
			'items',
			__( 'Dịch vụ', 'themeranghammatsgcantho' ),
			'repeater',
			array(
				'max'          => 10,
				'layout'       => 'table',
				'button_label' => __( 'Thêm dịch vụ', 'themeranghammatsgcantho' ),
				'instructions' => __( 'Nên 5 dịch vụ (1 hàng desktop; mobile vuốt ngang). Ảnh khung 16:10 (khuyến nghị 640 × 400), cắt giữa. Dòng thiếu ảnh hoặc tên sẽ bị bỏ qua.', 'themeranghammatsgcantho' ),
				'sub_fields'   => array(
					rhm_landing_image_field( $item, 'image', __( 'Ảnh', 'themeranghammatsgcantho' ), array( 'preview_size' => 'thumbnail', 'wrapper' => array( 'width' => 25 ) ) ),
					rhm_landing_field( $item, 'title', __( 'Tên dịch vụ', 'themeranghammatsgcantho' ), 'text', array( 'wrapper' => array( 'width' => 35 ) ) ),
					rhm_landing_field( $item, 'link', __( 'Link', 'themeranghammatsgcantho' ), 'text', array( 'wrapper' => array( 'width' => 40 ), 'placeholder' => 'https://…/trong-rang-implant/' ) ),
				),
			)
		),
	);
}
