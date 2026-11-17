<?php
/**
 * Layout "Ảnh slider + chữ (nhiều item)": tiêu đề section (H2) + mô tả, rồi danh sách item; mỗi item = thẻ có slider ảnh
 * (nút ‹ › + vuốt) + tiêu đề (H3) + nội dung. Vị trí ảnh (trái / phải / trên / dưới) và số cột chung cho cả section.
 * Mobile (< 640px) luôn ảnh trên, chữ dưới. Hiển thị: template-parts/landing/media-slider.php.
 * Bản dạng ô trước đó (text/images ở cấp section) không còn được đọc: section cũ phải nhập lại ảnh + nội dung vào Item.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_media_slider_fields( string $prefix ): array {
	$item = $prefix . '_items';
	return array(
		rhm_landing_field( $prefix, 'title', __( 'Tiêu đề section (H2)', 'themeranghammatsgcantho' ), 'text', array( 'wrapper' => array( 'width' => 60 ), 'placeholder' => 'Trang thiết bị hiện đại' ) ),
		rhm_landing_field( $prefix, 'eyebrow', __( 'Dòng nhỏ trên tiêu đề', 'themeranghammatsgcantho' ), 'text', array( 'wrapper' => array( 'width' => 40 ) ) ),
		rhm_landing_rich_field( $prefix, 'description', __( 'Mô tả (tùy chọn)', 'themeranghammatsgcantho' ), array( 'instructions' => __( 'Hiện canh giữa dưới tiêu đề section.', 'themeranghammatsgcantho' ) ) ),
		rhm_landing_field(
			$prefix,
			'columns',
			__( 'Số cột (máy tính)', 'themeranghammatsgcantho' ),
			'button_group',
			array(
				'choices'       => array(
					'1' => __( '1 cột', 'themeranghammatsgcantho' ),
					'2' => __( '2 cột', 'themeranghammatsgcantho' ),
					'3' => __( '3 cột', 'themeranghammatsgcantho' ),
				),
				'default_value' => '1',
				'wrapper'       => array( 'width' => 50 ),
				'instructions'  => __( 'Từ 1024px. Dưới 1024px: 1 item / hàng. 3 cột thẻ hẹp → nên chọn ảnh Trên/Dưới.', 'themeranghammatsgcantho' ),
			)
		),
		rhm_landing_field(
			$prefix,
			'image_position',
			__( 'Vị trí ảnh (mọi item)', 'themeranghammatsgcantho' ),
			'button_group',
			array(
				'choices'       => array(
					'left'   => __( 'Trái', 'themeranghammatsgcantho' ),
					'right'  => __( 'Phải', 'themeranghammatsgcantho' ),
					'top'    => __( 'Trên', 'themeranghammatsgcantho' ),
					'bottom' => __( 'Dưới', 'themeranghammatsgcantho' ),
				),
				'default_value' => 'right',
				'wrapper'       => array( 'width' => 50 ),
				'instructions'  => __( 'Áp dụng từ màn hình ≥ 640px. Mobile luôn: ảnh trên, chữ dưới.', 'themeranghammatsgcantho' ),
			)
		),
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
				'instructions' => __( 'Mỗi item = 1 thẻ: slider ảnh + tiêu đề + nội dung. Kéo để sắp xếp. Item không có ảnh lẫn chữ bị bỏ qua.', 'themeranghammatsgcantho' ),
				'sub_fields'   => array(
					rhm_landing_field(
						$item,
						'images',
						__( 'Ảnh slider — khuyến nghị 1200 × 900 (4:3)', 'themeranghammatsgcantho' ),
						'gallery',
						array(
							'return_format' => 'id',
							'preview_size'  => 'thumbnail',
							'max'           => 12,
							'mime_types'    => 'jpg,jpeg,png,webp,avif',
							'instructions'  => __( 'Chọn nhiều ảnh, kéo thả để sắp thứ tự. Từ 2 ảnh: có nút trái/phải, mobile vuốt ngang. Khung 4:3, ảnh khác tỉ lệ bị cắt quanh tâm → để chủ thể ở giữa ảnh. Nhập "Văn bản thay thế".', 'themeranghammatsgcantho' ),
						)
					),
					rhm_landing_field( $item, 'title', __( 'Tiêu đề item (H3)', 'themeranghammatsgcantho' ), 'text', array( 'placeholder' => 'Máy chụp X-quang Vatech' ) ),
					rhm_landing_rich_field( $item, 'text', __( 'Nội dung', 'themeranghammatsgcantho' ) ),
				),
			)
		),
	);
}
