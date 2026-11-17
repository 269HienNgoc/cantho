<?php
/**
 * Layout "Danh sách có icon + ảnh": tiêu đề + mô tả + danh sách (icon chung, từng dòng có thể đổi icon riêng)
 * + ảnh tùy chọn ở trái / phải / trên / dưới. Hiển thị: template-parts/landing/icon-list.php.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_icon_list_fields( string $prefix ): array {
	$item = $prefix . '_items';
	return array(
		rhm_landing_field( $prefix, 'title', __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text', array( 'wrapper' => array( 'width' => 60 ) ) ),
		rhm_landing_field( $prefix, 'eyebrow', __( 'Dòng nhỏ trên tiêu đề', 'themeranghammatsgcantho' ), 'text', array( 'wrapper' => array( 'width' => 40 ) ) ),
		rhm_landing_rich_field( $prefix, 'description', __( 'Mô tả (tùy chọn)', 'themeranghammatsgcantho' ), array( 'instructions' => __( 'Hiện dưới tiêu đề, trước danh sách.', 'themeranghammatsgcantho' ) ) ),
		rhm_landing_icon_field(
			$prefix,
			'icon',
			array(
				'label'         => __( 'Icon của danh sách', 'themeranghammatsgcantho' ),
				'default_value' => 'tich',
				'instructions'  => __( 'Icon đứng trước mỗi dòng (từng dòng có thể chọn icon khác ở cột "Icon riêng"). Trống → dấu tích. Thêm icon: chép file .svg vào assets/icons/landing/ của theme.', 'themeranghammatsgcantho' ),
			)
		),
		rhm_landing_field(
			$prefix,
			'items',
			__( 'Danh sách', 'themeranghammatsgcantho' ),
			'repeater',
			array(
				'max'          => 30,
				'layout'       => 'block',
				'button_label' => __( 'Thêm dòng', 'themeranghammatsgcantho' ),
				'instructions' => __( 'Mỗi dòng 1 ý, kéo để sắp xếp. Dòng trống bị bỏ qua.', 'themeranghammatsgcantho' ),
				'sub_fields'   => array(
					rhm_landing_rich_field( $item, 'text', __( 'Nội dung', 'themeranghammatsgcantho' ), array( 'wrapper' => array( 'width' => 70 ) ) ),
					rhm_landing_icon_field( $item, 'icon', array( 'label' => __( 'Icon riêng (tùy chọn)', 'themeranghammatsgcantho' ), 'instructions' => '', 'wrapper' => array( 'width' => 30 ) ) ),
				),
			)
		),
		rhm_landing_image_field( $prefix, 'image', __( 'Ảnh (tùy chọn)', 'themeranghammatsgcantho' ), array( 'wrapper' => array( 'width' => 50 ), 'instructions' => __( 'Giữ nguyên tỉ lệ, không cắt. Trái/phải: khuyến nghị 1200 × 900; trên/dưới (rộng hết khung): rộng 1600px.', 'themeranghammatsgcantho' ) ) ),
		rhm_landing_field(
			$prefix,
			'image_position',
			__( 'Vị trí ảnh', 'themeranghammatsgcantho' ),
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
				'instructions'  => __( 'Trái/phải: 2 cột trên máy tính (mobile: ảnh trái → nằm trên danh sách, ảnh phải → nằm dưới). Trên/dưới: ảnh và danh sách rộng hết khung, danh sách chia 2 cột trên máy tính.', 'themeranghammatsgcantho' ),
			)
		),
	);
}
