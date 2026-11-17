<?php
/**
 * Hero trang chủ (template-parts/home/hero.php): chữ HTML (dòng nhỏ, H1 2 phần, các ý có icon, 2 nút) + 1 ảnh lớn.
 * Nhập ở Tùy biến → "Hero (Trang chủ)". H1 của trang chủ = tiêu đề hero.
 *
 * Ảnh = phần tử LCP: <picture> có srcset thật trong HTML + fetchpriority=high (rhm_home_hero_picture()).
 * - < 768px: ảnh mobile (tùy chọn) trong khung 4:3; trống → dùng ảnh desktop.
 * - ≥ 1024px: ảnh phủ 45% bên phải hero (object-cover), mép trái cong.
 *
 * rhm_hero_picture() (ảnh banner desktop/mobile, dùng ở template-parts/doctor/hero.php) và rhm_html_attrs() là helper chung.
 * Slider ảnh banner cũ (theme mod rhm_hero_<n>_desktop/_mobile/_link) đã bỏ — dữ liệu cũ còn trong DB nhưng không được đọc.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

/** Số ý (dòng có icon) tối đa dưới tiêu đề. */
const RHM_HERO_POINTS_MAX = 4;

/**
 * Chữ mặc định (site mới chưa nhập vẫn có hero hoàn chỉnh).
 *
 * @return array<string, string>
 */
function rhm_hero_defaults(): array {
	return array(
		'eyebrow'      => __( 'Nha khoa Răng Hàm Mặt SG', 'themeranghammatsgcantho' ),
		'title'        => __( 'Chăm sóc nụ cười khỏe đẹp,', 'themeranghammatsgcantho' ),
		'highlight'    => __( 'tự tin mỗi ngày', 'themeranghammatsgcantho' ),
		'button_text'  => __( 'Đặt lịch khám', 'themeranghammatsgcantho' ),
		'button2_text' => __( 'Xem dịch vụ', 'themeranghammatsgcantho' ),
		'button2_link' => '#dich-vu',
	);
}

/**
 * Các ý mặc định (JSON của control list).
 */
function rhm_hero_default_points(): string {
	return (string) wp_json_encode(
		array(
			array(
				'icon' => 'khien',
				'text' => __( 'Đội ngũ bác sĩ giàu kinh nghiệm', 'themeranghammatsgcantho' ),
			),
			array(
				'icon' => 'rang',
				'text' => __( 'Công nghệ hiện đại, an toàn', 'themeranghammatsgcantho' ),
			),
			array(
				'icon' => 'tim',
				'text' => __( 'Dịch vụ tận tâm, chu đáo', 'themeranghammatsgcantho' ),
			),
		),
		JSON_UNESCAPED_UNICODE
	);
}

/**
 * Field của 1 ý (control list): icon bộ landing/ + chữ.
 *
 * @return array<string, array{0:string, 1:string, 2?:array}>
 */
function rhm_hero_point_fields(): array {
	return array(
		'icon' => array( __( 'Icon', 'themeranghammatsgcantho' ), 'select', array( '' => __( '— Dấu tích —', 'themeranghammatsgcantho' ) ) + rhm_icon_list( 'landing' ) ),
		'text' => array( __( 'Nội dung', 'themeranghammatsgcantho' ), 'text' ),
	);
}

/**
 * Toàn bộ dữ liệu hero. title rỗng → không in hero.
 *
 * @return array{eyebrow:string, title:string, highlight:string, points:array<int, array{icon:string, text:string}>, button_text:string, button2_text:string, button2_link:string, image:int, image_mobile:int}
 */
function rhm_get_hero(): array {
	$defaults = rhm_hero_defaults();
	$text     = static fn( string $key ): string => trim( (string) get_theme_mod( "rhm_hero_{$key}", $defaults[ $key ] ?? '' ) );

	$points = array();
	foreach ( rhm_decode_list( get_theme_mod( 'rhm_hero_points', rhm_hero_default_points() ) ) as $item ) {
		$point = trim( (string) ( $item['text'] ?? '' ) );
		if ( '' === $point ) {
			continue;
		}
		$icon     = (string) ( $item['icon'] ?? '' );
		$points[] = array(
			'icon' => rhm_icon_exists( 'landing', $icon ) ? $icon : 'tich',
			'text' => $point,
		);
		if ( count( $points ) >= RHM_HERO_POINTS_MAX ) {
			break;
		}
	}

	$image  = absint( get_theme_mod( 'rhm_hero_image' ) );
	$mobile = absint( get_theme_mod( 'rhm_hero_image_mobile' ) );

	return array(
		'eyebrow'      => $text( 'eyebrow' ),
		'title'        => $text( 'title' ),
		'highlight'    => $text( 'highlight' ),
		'points'       => $points,
		'button_text'  => $text( 'button_text' ),
		'button2_text' => $text( 'button2_text' ),
		'button2_link' => $text( 'button2_link' ),
		'image'        => wp_attachment_is_image( $image ) ? $image : 0,
		'image_mobile' => wp_attachment_is_image( $mobile ) ? $mobile : 0,
	);
}

/**
 * <picture> ảnh hero trang chủ (ảnh LCP: srcset thật, fetchpriority=high, không lazy, decoding mặc định).
 * Khung do template quyết định (absolute + object-cover) → class ảnh phủ kín khung.
 *
 * sizes: ≥ 1024px khung rộng 45vw nhưng cao hơn tỉ lệ 5:4 của ảnh (min-h 560px ở lg, 620px ở xl) → object-cover vẽ ảnh rộng
 * theo CHIỀU CAO: ≥ 1280px ≈ 620 × 1,25 = 775px (màn rất rộng thì 45vw lớn hơn), 1024–1279px ≈ 560 × 1,25 = 700px.
 * < 1024px: khung 4:3 rộng hết khung nội dung = 100vw − 32px (ảnh mobile 4:3 nên không bị cắt).
 */
function rhm_home_hero_picture( int $image, int $mobile, string $alt ): string {
	$source = '';
	if ( $mobile ) {
		$src    = wp_get_attachment_image_src( $mobile, 'full' );
		$source = '<source' . rhm_html_attrs(
			array(
				'media'  => '(max-width: 767px)',
				'srcset' => wp_get_attachment_image_srcset( $mobile, 'full' ) ?: $src[0],
				'sizes'  => 'calc(100vw - 32px)',
				'width'  => $src[1],
				'height' => $src[2],
			)
		) . '>';
	}

	$src = wp_get_attachment_image_src( $image, 'full' );
	$img = array(
		'class'         => 'absolute inset-0 size-full object-cover',
		'src'           => $src[0],
		'srcset'        => wp_get_attachment_image_srcset( $image, 'full' ) ?: $src[0],
		'sizes'         => '(min-width: 1280px) max(45vw, 780px), (min-width: 1024px) 700px, calc(100vw - 32px)',
		'width'         => $src[1],
		'height'        => $src[2],
		'alt'           => $alt,
		'fetchpriority' => 'high',
	);

	return '<picture>' . $source . '<img' . rhm_html_attrs( $img ) . '></picture>';
}

/**
 * Alt ảnh hero: alt của attachment → tiêu đề attachment → tiêu đề hero.
 */
function rhm_home_hero_alt( int $image, string $fallback ): string {
	$alt = trim( (string) get_post_meta( $image, '_wp_attachment_image_alt', true ) );
	return '' !== $alt ? $alt : ( trim( get_the_title( $image ) ) ?: $fallback );
}

/**
 * <picture> của 1 banner ảnh desktop + mobile (hồ sơ bác sĩ: template-parts/doctor/hero.php).
 * - $is_first (ảnh LCP): srcset thật, tải ngay với fetchpriority=high.
 * - Còn lại: chỉ có data-srcset → trình duyệt KHÔNG tải cho tới khi JS chuyển data-srcset → srcset.
 *
 * @param array{desktop:int, mobile:int} $slide ID ảnh.
 */
function rhm_hero_picture( array $slide, bool $is_first ): string {
	$srcset_attr = $is_first ? 'srcset' : 'data-srcset';

	$source = '';
	if ( $slide['mobile'] ) {
		$mobile = wp_get_attachment_image_src( $slide['mobile'], 'full' );
		$source = '<source' . rhm_html_attrs(
			array(
				'media'      => '(max-width: 767px)',
				$srcset_attr => wp_get_attachment_image_srcset( $slide['mobile'], 'full' ) ?: $mobile[0],
				'sizes'      => '100vw',
				'width'      => $mobile[1],
				'height'     => $mobile[2],
			)
		) . '>';
	}

	$desktop = wp_get_attachment_image_src( $slide['desktop'], 'full' );
	$alt     = (string) get_post_meta( $slide['desktop'], '_wp_attachment_image_alt', true );
	$img     = array(
		'class'      => 'block h-auto w-full',
		'width'      => $desktop[1],
		'height'     => $desktop[2],
		'alt'        => '' !== $alt ? $alt : get_the_title( $slide['desktop'] ),
		$srcset_attr => wp_get_attachment_image_srcset( $slide['desktop'], 'full' ) ?: $desktop[0],
		'sizes'      => '(min-width: 2048px) 2048px, 100vw',
	);

	if ( $is_first ) {
		// Ảnh LCP: tải ngay, ưu tiên cao, decoding mặc định (không async — tránh trễ khung hình vẽ ảnh LCP).
		$img['src']           = $desktop[0];
		$img['fetchpriority'] = 'high';
	} else {
		$img['decoding'] = 'async';
	}

	return '<picture>' . $source . '<img' . rhm_html_attrs( $img ) . '></picture>';
}

/**
 * Chuỗi thuộc tính HTML đã escape: ` name="value" …`.
 */
function rhm_html_attrs( array $attrs ): string {
	$html = '';
	foreach ( $attrs as $name => $value ) {
		$html .= sprintf( ' %s="%s"', esc_attr( $name ), esc_attr( (string) $value ) );
	}
	return $html;
}

/**
 * Customizer: Tùy biến → "Hero (Trang chủ)".
 */
function rhm_customize_hero( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'rhm_hero',
		array(
			'title'       => __( 'Hero (Trang chủ)', 'themeranghammatsgcantho' ),
			'description' => __( 'Phần đầu trang chủ: chữ bên trái, ảnh lớn bên phải (mép trái cong). Tiêu đề là H1 của trang chủ — để trống thì ẩn hero. Ảnh desktop: tỉ lệ 5:4, rộng ≥ 1200px (vd 1500×1200), chủ thể lệch phải. Ảnh mobile (tùy chọn): 4:3, vd 828×621 — dưới 768px; trống thì dùng ảnh desktop.', 'themeranghammatsgcantho' ),
			'priority'    => 31,
		)
	);

	$defaults = rhm_hero_defaults();
	$text     = static fn( string $label, string $key, string $type = 'text', string $sanitize = 'sanitize_text_field' ): array => array( $label, $type, $sanitize, array( 'default' => $defaults[ $key ] ) );

	rhm_customize_fields(
		$wp_customize,
		'rhm_hero',
		array(
			'rhm_hero_eyebrow'      => $text( __( 'Dòng nhỏ trên tiêu đề (in hoa)', 'themeranghammatsgcantho' ), 'eyebrow' ),
			'rhm_hero_title'        => $text( __( 'Tiêu đề (H1) – phần đầu', 'themeranghammatsgcantho' ), 'title' ),
			'rhm_hero_highlight'    => $text( __( 'Tiêu đề – phần nhấn (dòng 2, màu xanh)', 'themeranghammatsgcantho' ), 'highlight' ),
			'rhm_hero_button_text'  => $text( __( 'Chữ nút Đặt lịch', 'themeranghammatsgcantho' ), 'button_text' ),
			'rhm_hero_button2_text' => $text( __( 'Chữ nút thứ 2 (để trống: ẩn nút)', 'themeranghammatsgcantho' ), 'button2_text' ),
			'rhm_hero_button2_link' => $text( __( 'Link nút thứ 2 (vd #dich-vu = cuộn tới Dịch vụ nổi bật)', 'themeranghammatsgcantho' ), 'button2_link', 'text', 'esc_url_raw' ),
			'rhm_hero_image'        => array( __( 'Ảnh desktop', 'themeranghammatsgcantho' ), 'image', 'absint' ),
			'rhm_hero_image_mobile' => array( __( 'Ảnh mobile (tùy chọn)', 'themeranghammatsgcantho' ), 'image', 'absint' ),
		)
	);

	rhm_customize_list(
		$wp_customize,
		'rhm_hero',
		'rhm_hero_points',
		__( 'Các ý dưới tiêu đề', 'themeranghammatsgcantho' ),
		__( 'Ý', 'themeranghammatsgcantho' ),
		rhm_hero_point_fields(),
		/* translators: %d: số ý tối đa */
		sprintf( __( 'Tối đa %d ý; ý trống bị bỏ qua.', 'themeranghammatsgcantho' ), RHM_HERO_POINTS_MAX )
	);
	$wp_customize->get_setting( 'rhm_hero_points' )->default = rhm_hero_default_points();
}
add_action( 'customize_register', 'rhm_customize_hero' );
