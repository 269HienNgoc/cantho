<?php
/**
 * Layout "Câu hỏi thường gặp" (<details>, không JS). Hiển thị: template-parts/landing/faq.php.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_landing_layout_faq_fields( string $prefix ): array {
	$item = $prefix . '_items';
	return array(
		rhm_landing_field( $prefix, 'title', __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text', array( 'placeholder' => __( 'Câu hỏi thường gặp', 'themeranghammatsgcantho' ) ) ),
		rhm_landing_field(
			$prefix,
			'items',
			__( 'Câu hỏi', 'themeranghammatsgcantho' ),
			'repeater',
			array(
				'max'          => 20,
				'layout'       => 'block',
				'collapsed'    => 'field_rhm_landing_' . $item . '_question',
				'button_label' => __( 'Thêm câu hỏi', 'themeranghammatsgcantho' ),
				'instructions' => __( 'Câu hỏi thiếu câu trả lời sẽ bị bỏ qua.', 'themeranghammatsgcantho' ),
				'sub_fields'   => array(
					rhm_landing_field( $item, 'question', __( 'Câu hỏi', 'themeranghammatsgcantho' ), 'text' ),
					rhm_landing_wysiwyg_field( $item, 'answer', __( 'Trả lời', 'themeranghammatsgcantho' ) ),
				),
			)
		),
	);
}
