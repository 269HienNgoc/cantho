<?php
/**
 * Chân trang: dữ liệu từ Customizer → "Chân trang (Footer)". Hiển thị: footer.php. CSS: footer.css (@source file này).
 *
 * Bố cục 3 cột (2026-11-17):
 * 1. Thương hiệu: logo + tiêu đề + khẩu hiệu + tối đa RHM_FOOTER_PINNED_MAX địa chỉ = chi nhánh được tích "Ghim" ở
 *    Tùy biến → "Chi nhánh (Trang chủ)" (rhm_get_footer_pinned_branches()); chưa ghim chi nhánh nào → địa chỉ ở "Thông tin phòng khám".
 * 2. Cột linh động (setting rhm_footer_middle): "policy" = menu vị trí "footer" (Điều khoản chính sách) |
 *    "branches" = Hệ thống chi nhánh (chi nhánh chọn ở "Chi nhánh hiển thị", chỉ in địa chỉ). Lựa chọn không có dữ liệu
 *    → tự dùng lựa chọn còn lại; khi cột giữa là chi nhánh, menu chính sách chuyển xuống hàng bản quyền.
 * 3. Kết nối với chúng tôi: hotline, email, giờ làm việc ("Thông tin phòng khám") + mạng xã hội.
 * Huy hiệu (Bộ Công Thương, DMCA…), mạng xã hội: danh sách động (inc/customize-list.php).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

/** Số địa chỉ chi nhánh ghim tối đa ở cột thương hiệu. */
const RHM_FOOTER_PINNED_MAX = 2;

/**
 * Chữ mặc định (dùng chung cho Customizer và giao diện). Setting: rhm_footer_{khóa}.
 * Bản quyền: {year} được thay bằng năm hiện tại.
 */
function rhm_footer_defaults(): array {
	return array(
		'title'              => get_bloginfo( 'name' ),
		'middle'             => 'policy',
		'slogan'             => __( 'Vì nụ cười Việt Nam', 'themeranghammatsgcantho' ),
		'branch_title'       => __( 'Hệ thống chi nhánh', 'themeranghammatsgcantho' ),
		'branch_button_text' => __( 'Xem chi tiết địa chỉ', 'themeranghammatsgcantho' ),
		'policy_title'       => __( 'Điều khoản chính sách', 'themeranghammatsgcantho' ),
		'social_title'       => __( 'Kết nối với chúng tôi', 'themeranghammatsgcantho' ),
		/* translators: %s: site name */
		'copyright'          => sprintf( __( '© {year} %s. Giữ bản quyền nội dung trên website này.', 'themeranghammatsgcantho' ), get_bloginfo( 'name' ) ),
	);
}

/**
 * Chữ của 1 khóa trong rhm_footer_defaults() (chưa escape).
 */
function rhm_footer_text( string $key ): string {
	$text = trim( (string) get_theme_mod( "rhm_footer_{$key}", rhm_footer_defaults()[ $key ] ?? '' ) );
	return 'copyright' === $key ? str_replace( '{year}', wp_date( 'Y' ), $text ) : $text;
}

/** Field danh sách "Chi nhánh hiển thị" (Tùy biến → Chân trang): mỗi mục chọn 1 chi nhánh. */
function rhm_footer_branch_fields(): array {
	return array(
		'branch' => array( __( 'Chi nhánh', 'themeranghammatsgcantho' ), 'select', rhm_footer_branch_choices() ),
	);
}

/**
 * Lựa chọn cho ô chọn chi nhánh: ID => "Tên — địa chỉ rút gọn" (chi nhánh đã xuất bản, A → Z).
 *
 * @return array<int, string>
 */
function rhm_footer_branch_choices(): array {
	$choices = array();
	foreach ( rhm_get_branches() as $branch ) {
		$address                  = '' !== $branch['address'] ? wp_html_excerpt( str_replace( "\n", ', ', $branch['address'] ), 60, '…' ) : __( '(chưa có địa chỉ)', 'themeranghammatsgcantho' );
		$choices[ $branch['id'] ] = $branch['name'] . ' — ' . $address;
	}
	return $choices;
}

/** Field danh sách mạng xã hội. */
function rhm_footer_social_fields(): array {
	return array(
		'icon' => array( __( 'Icon (.svg / .png vuông)', 'themeranghammatsgcantho' ), 'image' ),
		'name' => array( __( 'Tên mạng xã hội (vd Facebook)', 'themeranghammatsgcantho' ), 'text' ),
		'link' => array( __( 'Link trang', 'themeranghammatsgcantho' ), 'url' ),
	);
}

/** Field danh sách huy hiệu cuối trang. */
function rhm_footer_badge_fields(): array {
	return array(
		'image' => array( __( 'Ảnh huy hiệu (.png / .jpg)', 'themeranghammatsgcantho' ), 'image' ),
		'name'  => array( __( 'Tên (mô tả ảnh)', 'themeranghammatsgcantho' ), 'text' ),
		'link'  => array( __( 'Link (tùy chọn)', 'themeranghammatsgcantho' ), 'url' ),
	);
}

/**
 * ID ảnh logo chân trang (0 = chưa có).
 */
function rhm_get_footer_logo_id(): int {
	foreach ( array( (int) get_option( 'site_icon' ), (int) get_theme_mod( 'custom_logo' ) ) as $id ) {
		if ( wp_attachment_is_image( $id ) ) {
			return $id;
		}
	}
	return 0;
}

/**
 * Dòng liên hệ cột "Kết nối với chúng tôi" (hotline, email, giờ làm việc); dòng trống bị bỏ.
 *
 * @return array<int, array{icon:string, label:string, html:string}> html đã escape.
 */
function rhm_get_footer_contacts(): array {
	$hotline = rhm_get_option( 'rhm_hotline' );
	$email   = rhm_get_option( 'rhm_email' );
	$hours   = rhm_get_option( 'rhm_working_hours' );
	$items   = array();

	if ( $hotline ) {
		$items[] = array(
			'icon'  => 'phone',
			'label' => __( 'Hotline', 'themeranghammatsgcantho' ),
			'html'  => sprintf( '<a class="text-lg font-bold text-white hover:text-footer-accent" href="%s">%s</a>', esc_url( rhm_get_tel_href( $hotline ) ), esc_html( $hotline ) ),
		);
	}
	if ( $email ) {
		$items[] = array(
			'icon'  => 'mail',
			'label' => __( 'Email', 'themeranghammatsgcantho' ),
			'html'  => sprintf( '<a class="wrap-anywhere text-white hover:text-footer-accent" href="%s">%s</a>', esc_url( 'mailto:' . $email ), esc_html( $email ) ),
		);
	}
	if ( $hours ) {
		$items[] = array(
			'icon'  => 'clock',
			'label' => __( 'Giờ làm việc', 'themeranghammatsgcantho' ),
			'html'  => nl2br( esc_html( $hours ) ),
		);
	}
	return $items;
}

/**
 * Địa chỉ ở cột thương hiệu: chi nhánh tích "Ghim" trong Tùy biến → "Chi nhánh (Trang chủ)" (rhm_home_branch_ids),
 * đúng thứ tự đã sắp, tối đa RHM_FOOTER_PINNED_MAX. Khác section trang chủ: KHÔNG cần ảnh đại diện, chỉ cần địa chỉ.
 *
 * @return array<int, array{name:string, address:string, map_url:string}> chưa escape.
 */
function rhm_get_footer_pinned_branches(): array {
	$branches = array_column( rhm_get_branches(), null, 'id' );
	$items    = array();

	foreach ( rhm_get_list( 'rhm_home_branch_ids' ) as $item ) {
		$id = absint( $item['branch'] ?? 0 );
		if ( '1' !== ( $item['pinned'] ?? '' ) || isset( $items[ $id ] ) || '' === trim( (string) ( $branches[ $id ]['address'] ?? '' ) ) ) {
			continue;
		}
		$items[ $id ] = array(
			'name'    => trim( (string) $branches[ $id ]['name'] ),
			'address' => trim( (string) $branches[ $id ]['address'] ),
			'map_url' => (string) ( $branches[ $id ]['map_url'] ?? '' ),
		);
		if ( count( $items ) >= RHM_FOOTER_PINNED_MAX ) {
			break;
		}
	}

	if ( ! $items ) {
		$address = trim( rhm_get_option( 'rhm_address' ) );
		if ( '' !== $address ) {
			$items[] = array(
				'name'    => '',
				'address' => $address,
				'map_url' => '',
			);
		}
	}

	return array_values( $items );
}

/** Lựa chọn nội dung cột giữa. */
function rhm_footer_middle_choices(): array {
	return array(
		'policy'   => __( 'Điều khoản chính sách (menu "Menu chân trang")', 'themeranghammatsgcantho' ),
		'branches' => __( 'Hệ thống chi nhánh (danh sách "Chi nhánh hiển thị")', 'themeranghammatsgcantho' ),
	);
}

/** Sanitize setting rhm_footer_middle. */
function rhm_sanitize_footer_middle( $value ): string {
	$value = (string) $value;
	return isset( rhm_footer_middle_choices()[ $value ] ) ? $value : 'policy';
}

/**
 * Nội dung cột giữa thực sự in: lựa chọn trong Tùy biến; lựa chọn đó không có dữ liệu → lựa chọn còn lại; '' = không có cột.
 */
function rhm_footer_middle( bool $has_policy, bool $has_branches ): string {
	$available = array(
		'policy'   => $has_policy,
		'branches' => $has_branches,
	);
	$mode      = rhm_sanitize_footer_middle( get_theme_mod( 'rhm_footer_middle', 'policy' ) );
	$other     = 'policy' === $mode ? 'branches' : 'policy';

	if ( $available[ $mode ] ) {
		return $mode;
	}
	return $available[ $other ] ? $other : '';
}

/**
 * Chi nhánh hiển thị ở cột giữa (khi chọn "Hệ thống chi nhánh") và trang Liên hệ: chọn + sắp thứ tự trong Tùy biến (setting rhm_footer_branch_ids).
 * Footer chỉ in địa chỉ; trang Liên hệ (tpl-lien-he.php) in tên + link Google Maps.
 * Bỏ: ID trùng, chi nhánh đã xóa / nháp / trong thùng rác, chi nhánh chưa có địa chỉ.
 * Không chọn chi nhánh nào → mảng rỗng → cột giữa dùng menu chính sách (rhm_footer_middle()).
 *
 * @return array<int, array{id:int, name:string, address:string, map_url:string}> đã làm sạch, chưa escape (address có thể nhiều dòng).
 */
function rhm_get_footer_branches(): array {
	$branches = array_column( rhm_get_branches(), null, 'id' ); // Chi nhánh đã xuất bản (có cache) theo ID.
	$items    = array();

	foreach ( rhm_get_list( 'rhm_footer_branch_ids' ) as $item ) {
		$id = absint( $item['branch'] ?? 0 );
		if ( ! $id || isset( $items[ $id ] ) || '' === ( $branches[ $id ]['address'] ?? '' ) ) {
			continue;
		}
		$items[ $id ] = array(
			'id'      => $id,
			'name'    => (string) ( $branches[ $id ]['name'] ?? '' ),
			'address' => $branches[ $id ]['address'],
			'map_url' => (string) ( $branches[ $id ]['map_url'] ?? '' ),
		);
	}

	return array_values( $items );
}

/**
 * Mạng xã hội có đủ icon (ảnh bất kỳ, kể cả SVG) + link.
 *
 * @return array<int, array{icon:string, name:string, link:string}>
 */
function rhm_get_footer_socials(): array {
	$list = rhm_get_list( 'rhm_footer_socials' );
	_prime_post_caches( array_filter( array_map( 'absint', array_column( $list, 'icon' ) ) ) );

	$items = array();
	foreach ( $list as $item ) {
		$icon = absint( $item['icon'] ?? 0 );
		$link = (string) ( $item['link'] ?? '' );
		if ( '' === $link || ! str_starts_with( (string) get_post_mime_type( $icon ), 'image/' ) ) {
			continue;
		}
		$items[] = array(
			'icon' => (string) wp_get_attachment_url( $icon ),
			'name' => trim( (string) ( $item['name'] ?? '' ) ),
			'link' => $link,
		);
	}
	return $items;
}

/**
 * Huy hiệu cuối trang có ảnh (ảnh raster để có srcset + kích thước thật).
 *
 * @return array<int, array{image:int, name:string, link:string}>
 */
function rhm_get_footer_badges(): array {
	$list = rhm_get_list( 'rhm_footer_badges' );
	_prime_post_caches( array_filter( array_map( 'absint', array_column( $list, 'image' ) ) ) );

	$items = array();
	foreach ( $list as $item ) {
		$image = absint( $item['image'] ?? 0 );
		if ( ! wp_attachment_is_image( $image ) ) {
			continue;
		}
		$name    = trim( (string) ( $item['name'] ?? '' ) );
		$items[] = array(
			'image' => $image,
			'name'  => '' !== $name ? $name : get_the_title( $image ),
			'link'  => (string) ( $item['link'] ?? '' ),
		);
	}
	return $items;
}

/**
 * <img> hiển thị theo chiều cao CSS cố định ($height px): sizes = độ rộng thật (chiều cao × tỉ lệ ảnh).
 */
function rhm_footer_image( int $id, int $height, string $class, string $alt ): string {
	$full = wp_get_attachment_image_src( $id, 'full' );
	if ( ! $full || ! $full[2] ) {
		return '';
	}

	return wp_get_attachment_image(
		$id,
		'full',
		false,
		array(
			'class'    => $class,
			'alt'      => $alt,
			'sizes'    => (int) ceil( $height * $full[1] / $full[2] ) . 'px',
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
}
