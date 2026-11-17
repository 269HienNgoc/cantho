<?php
/**
 * Chuyển dữ liệu Tùy biến (Customizer) từ thư mục theme cũ sang thư mục mới.
 *
 * WordPress lưu toàn bộ theme mod theo TÊN THƯ MỤC theme (option "theme_mods_<thư mục>"). Theme đổi thư mục
 * themeranghammatsg → themeranghammatsgcantho (2026-11-17) nên khi kích hoạt bản mới, Customizer sẽ trống nếu không chuyển.
 * Khi kích hoạt theme (after_switch_theme): option của thư mục hiện tại chưa có setting nào của theme (khóa "rhm_…")
 * mà option thư mục cũ có → chép sang (giá trị WordPress vừa tự ghi cho theme mới, vd vị trí menu, được giữ nếu khác rỗng).
 * Option cũ để nguyên trong DB (dự phòng, trả lại được bằng cách kích hoạt lại theme cũ).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

/** Thư mục theme cũ. */
const RHM_LEGACY_THEME_SLUG = 'themeranghammatsg';

function rhm_migrate_legacy_theme_mods(): void {
	$stylesheet = (string) get_option( 'stylesheet' );
	if ( RHM_LEGACY_THEME_SLUG === $stylesheet ) {
		return;
	}

	$old = get_option( 'theme_mods_' . RHM_LEGACY_THEME_SLUG );
	if ( ! is_array( $old ) || ! $old ) {
		return;
	}

	$current = get_option( 'theme_mods_' . $stylesheet );
	$current = is_array( $current ) ? $current : array();
	foreach ( array_keys( $current ) as $key ) {
		if ( str_starts_with( (string) $key, 'rhm_' ) ) {
			return; // Theme mới đã có cấu hình riêng → không ghi đè.
		}
	}

	update_option( 'theme_mods_' . $stylesheet, array_merge( $old, array_filter( $current ) ) );
}
add_action( 'after_switch_theme', 'rhm_migrate_legacy_theme_mods' );
