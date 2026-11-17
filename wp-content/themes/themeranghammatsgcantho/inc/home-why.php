<?php
/**
 * Section "Vì sao chọn chúng tôi" trang chủ (template-parts/home/why.php): khối nền xanh nhạt, trái = H2 + lưới lý do
 * (icon tròn + tiêu đề + mô tả), phải = ảnh phòng khám. Thay cho 2 section "Giới thiệu" cũ (đã bỏ 2026-11-16).
 * Nhập ở Tùy biến → "Vì sao chọn (Trang chủ)". Tiêu đề trống hoặc không còn lý do hợp lệ → không in.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

/** Số lý do tối đa (lưới 2 cột). */
const RHM_HOME_WHY_MAX = 6;

function rhm_home_why_default_title(): string {
	return __( 'Vì sao nên chọn Răng Hàm Mặt SG?', 'themeranghammatsgcantho' );
}

/**
 * Lý do mặc định (JSON của control list).
 */
function rhm_home_why_default_items(): string {
	return (string) wp_json_encode(
		array(
			array(
				'icon'  => 'kim-cuong',
				'title' => __( 'Chuyên môn vững vàng', 'themeranghammatsgcantho' ),
				'text'  => __( 'Bác sĩ giàu kinh nghiệm, liên tục cập nhật kỹ thuật mới.', 'themeranghammatsgcantho' ),
			),
			array(
				'icon'  => 'thiet-bi',
				'title' => __( 'Trang thiết bị hiện đại', 'themeranghammatsgcantho' ),
				'text'  => __( 'Ứng dụng công nghệ tiên tiến, đảm bảo an toàn và hiệu quả.', 'themeranghammatsgcantho' ),
			),
			array(
				'icon'  => 'doi-ngu',
				'title' => __( 'Phác đồ cá nhân hóa', 'themeranghammatsgcantho' ),
				'text'  => __( 'Giải pháp điều trị phù hợp với tình trạng và nhu cầu riêng.', 'themeranghammatsgcantho' ),
			),
			array(
				'icon'  => 'tim',
				'title' => __( 'Trải nghiệm thoải mái', 'themeranghammatsgcantho' ),
				'text'  => __( 'Không gian sạch sẽ, thân thiện, quy trình nhẹ nhàng, ít đau.', 'themeranghammatsgcantho' ),
			),
		),
		JSON_UNESCAPED_UNICODE
	);
}

/**
 * @return array<string, array{0:string, 1:string, 2?:array}>
 */
function rhm_home_why_fields(): array {
	return array(
		'icon'  => array( __( 'Icon', 'themeranghammatsgcantho' ), 'select', array( '' => __( '— Dấu tích —', 'themeranghammatsgcantho' ) ) + rhm_icon_list( 'landing' ) ),
		'title' => array( __( 'Tiêu đề', 'themeranghammatsgcantho' ), 'text' ),
		'text'  => array( __( 'Mô tả', 'themeranghammatsgcantho' ), 'textarea' ),
	);
}

/**
 * Dữ liệu section.
 *
 * @return array{title:string, image:int, items:array<int, array{icon:string, title:string, text:string}>}
 */
function rhm_get_home_why(): array {
	$items = array();
	foreach ( rhm_decode_list( get_theme_mod( 'rhm_home_why_items', rhm_home_why_default_items() ) ) as $item ) {
		$title = trim( (string) ( $item['title'] ?? '' ) );
		if ( '' === $title ) {
			continue;
		}
		$icon    = (string) ( $item['icon'] ?? '' );
		$items[] = array(
			'icon'  => rhm_icon_exists( 'landing', $icon ) ? $icon : 'tich',
			'title' => $title,
			'text'  => trim( (string) ( $item['text'] ?? '' ) ),
		);
		if ( count( $items ) >= RHM_HOME_WHY_MAX ) {
			break;
		}
	}

	$image = absint( get_theme_mod( 'rhm_home_why_image' ) );

	return array(
		'title' => trim( (string) get_theme_mod( 'rhm_home_why_title', rhm_home_why_default_title() ) ),
		'image' => wp_attachment_is_image( $image ) ? $image : 0,
		'items' => $items,
	);
}

function rhm_customize_home_why( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'rhm_home_why',
		array(
			'title'       => __( 'Vì sao chọn (Trang chủ)', 'themeranghammatsgcantho' ),
			'description' => __( 'Khối nền xanh nhạt ngay dưới Dịch vụ nổi bật: tiêu đề + các lý do (2 cột) bên trái, ảnh phòng khám bên phải. Ảnh: ngang, vd 1200×900. Để trống tiêu đề thì ẩn section.', 'themeranghammatsgcantho' ),
			'priority'    => 32,
		)
	);

	rhm_customize_fields(
		$wp_customize,
		'rhm_home_why',
		array(
			'rhm_home_why_title' => array( __( 'Tiêu đề (H2)', 'themeranghammatsgcantho' ), 'text', 'sanitize_text_field', array( 'default' => rhm_home_why_default_title() ) ),
			'rhm_home_why_image' => array( __( 'Ảnh phòng khám', 'themeranghammatsgcantho' ), 'image', 'absint' ),
		)
	);

	rhm_customize_list(
		$wp_customize,
		'rhm_home_why',
		'rhm_home_why_items',
		__( 'Lý do', 'themeranghammatsgcantho' ),
		__( 'Lý do', 'themeranghammatsgcantho' ),
		rhm_home_why_fields(),
		/* translators: %d: số lý do tối đa */
		sprintf( __( 'Tối đa %d lý do; lý do thiếu tiêu đề bị bỏ qua.', 'themeranghammatsgcantho' ), RHM_HOME_WHY_MAX )
	);
	$wp_customize->get_setting( 'rhm_home_why_items' )->default = rhm_home_why_default_items();
}
add_action( 'customize_register', 'rhm_customize_home_why' );
