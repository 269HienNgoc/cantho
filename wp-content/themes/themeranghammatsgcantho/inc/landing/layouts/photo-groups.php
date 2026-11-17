<?php
/**
 * Layout "Nhóm ảnh 3 cột": tiêu đề + mô tả section, rồi nhiều item; mỗi item = tiêu đề + mô tả + hàng ảnh (3 ảnh / hàng).
 * Hiển thị: template-parts/landing/photo-groups.php.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_photo_groups_fields( string $prefix ): array {
	$item = $prefix . '_items';
	return array(
		rhm_landing_field( $prefix, 'title', __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text' ),
		rhm_landing_rich_field( $prefix, 'description', __( 'Mô tả (tùy chọn)', 'themeranghammatsgcantho' ), array( 'instructions' => __( 'Hiện canh giữa dưới tiêu đề.', 'themeranghammatsgcantho' ) ) ),
		rhm_landing_field(
			$prefix,
			'items',
			__( 'Item', 'themeranghammatsgcantho' ),
			'repeater',
			array(
				'max'          => 12,
				'layout'       => 'block',
				'collapsed'    => 'field_rhm_landing_' . $item . '_title',
				'button_label' => __( 'Thêm item', 'themeranghammatsgcantho' ),
				'instructions' => __( 'Mỗi item = tiêu đề + mô tả + hàng ảnh bên dưới, các item xếp lần lượt từ trên xuống. Item thiếu tiêu đề hoặc không có ảnh sẽ bị bỏ qua.', 'themeranghammatsgcantho' ),
				'sub_fields'   => array(
					rhm_landing_field( $item, 'title', __( 'Tiêu đề item (H3)', 'themeranghammatsgcantho' ), 'text' ),
					rhm_landing_rich_field( $item, 'text', __( 'Mô tả item (tùy chọn)', 'themeranghammatsgcantho' ) ),
					rhm_landing_field(
						$item,
						'images',
						__( 'Ảnh — 3 ảnh / hàng, khuyến nghị 1200 × 900 (4:3)', 'themeranghammatsgcantho' ),
						'gallery',
						array(
							'return_format' => 'id',
							'preview_size'  => 'thumbnail',
							'max'           => 9,
							'mime_types'    => 'jpg,jpeg,png,webp,avif',
							'instructions'  => __( 'Nên chọn 3 ảnh (hoặc 6, 9) cho đủ hàng. Khung 4:3, ảnh khác tỉ lệ sẽ được cắt giữa. Mobile: vuốt ngang. Nhập "Văn bản thay thế" cho từng ảnh.', 'themeranghammatsgcantho' ),
						)
					),
				),
			)
		),
	);
}
