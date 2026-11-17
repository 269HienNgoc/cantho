<?php
/**
 * Dịch vụ nổi bật trang chủ (template-parts/home/quick-services.php): tiêu đề + mô tả canh giữa, lưới thẻ
 * (ảnh 4:3 → icon nét + tên + mũi tên). Nhập ở Tùy biến → "Dịch vụ nổi bật (Trang chủ)".
 *
 * Icon: bộ assets/icons/menu/ (24px currentColor, in inline → tô màu xanh theo CSS). Giá trị cũ của bộ services/
 * (icon 48px màu vẽ sẵn, giao diện trước 2026-11-16) được đổi sang icon menu/ tương ứng (RHM_QS_LEGACY_ICONS).
 * Ô "tải lên icon riêng" cũ đã bỏ (theme mod rhm_qs_<n>_icon còn trong DB nhưng không được đọc).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

const RHM_QUICK_SERVICES_MAX = 8;

/** Icon bộ services/ (cũ) → icon bộ menu/ cùng ý nghĩa. */
const RHM_QS_LEGACY_ICONS = array(
	'nieng-rang'         => 'braces',
	'tay-trang-rang'     => 'whitening',
	'trong-rang-implant' => 'implant',
	'rang-su-tham-my'    => 'crown',
	'nho-rang-khon'      => 'extraction',
	'dieu-tri-tuy'       => 'root-canal',
	'nha-chu'            => 'gum',
	'nha-khoa-tre-em'    => 'kids',
	'phau-thuat-ham-mat' => 'surgery',
	'chup-phim'          => 'xray',
	'cao-voi-rang'       => 'toothbrush',
	'bac-si'             => 'doctor',
	'bang-gia'           => 'price',
	'dat-lich'           => 'calendar',
	'tu-van'             => 'consult',
	'uu-dai'             => 'promo',
);

/**
 * Chữ mặc định của section.
 */
function rhm_quick_services_default( string $key ): string {
	return 'title' === $key
		? __( 'Dịch vụ nổi bật', 'themeranghammatsgcantho' )
		: __( 'Giải pháp nha khoa toàn diện cho sức khỏe răng miệng và nụ cười của bạn.', 'themeranghammatsgcantho' );
}

/**
 * Tiêu đề / mô tả section: 'title' | 'subtitle'.
 */
function rhm_quick_services_text( string $key ): string {
	return trim( (string) get_theme_mod( "rhm_qs_{$key}", rhm_quick_services_default( $key ) ) );
}

/**
 * Mục hợp lệ (có tiêu đề) theo thứ tự trong Customizer. title có thể nhiều dòng; icon = tên icon bộ menu/ ('' = không icon);
 * image = ID ảnh (0 = không ảnh).
 *
 * @return array<int, array{title:string, link:string, icon:string, image:int}>
 */
function rhm_get_quick_services(): array {
	$items = array();

	for ( $i = 1; $i <= RHM_QUICK_SERVICES_MAX; $i++ ) {
		$title = trim( (string) get_theme_mod( "rhm_qs_{$i}_title", '' ) );
		if ( '' === $title ) {
			continue;
		}

		$icon = (string) get_theme_mod( "rhm_qs_{$i}_icon_name", '' );
		$icon = RHM_QS_LEGACY_ICONS[ $icon ] ?? $icon;
		$img  = absint( get_theme_mod( "rhm_qs_{$i}_image" ) );

		$items[] = array(
			'title' => $title,
			'link'  => (string) get_theme_mod( "rhm_qs_{$i}_link", '' ),
			'icon'  => rhm_icon_exists( 'menu', $icon ) ? $icon : '',
			'image' => wp_attachment_is_image( $img ) ? $img : 0,
		);
	}

	return $items;
}
