<?php
/**
 * Chi nhánh trang chủ: danh sách chọn riêng, không tự thêm và không dùng danh sách footer.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

function rhm_home_branches_default_title(): string {
	return __( 'Hệ thống chi nhánh', 'themeranghammatsgcantho' );
}

/** Dòng nhỏ phía trên tiêu đề (như section Đội ngũ bác sĩ); để trống trong Tùy biến → không in. */
function rhm_home_branches_default_eyebrow(): string {
	return __( 'Phòng khám gần bạn', 'themeranghammatsgcantho' );
}

/**
 * Field mỗi mục của danh sách: ô chọn chi nhánh (dùng chung với footer) + ô tích "Ghim".
 * Chi nhánh được ghim hiện thành thẻ lớn ở đầu section (template-parts/home/branches.php).
 */
function rhm_home_branch_fields(): array {
	return rhm_footer_branch_fields() + array(
		'pinned' => array( __( 'Ghim (hiển thị nổi bật)', 'themeranghammatsgcantho' ), 'checkbox' ),
	);
}

/** Chi nhánh đủ tên, địa chỉ và ảnh, theo thứ tự đã chọn; bỏ ID trùng. Mỗi mục có thêm 'pinned' (bool). */
function rhm_get_home_branches(): array {
	$list = rhm_get_list( 'rhm_home_branch_ids' );
	if ( ! $list ) {
		return array();
	}
	$branches = array_column( rhm_get_branches(), null, 'id' );
	_prime_post_caches( array_filter( array_column( $branches, 'image' ) ) );
	$items = array();
	foreach ( $list as $item ) {
		$id     = absint( $item['branch'] ?? 0 );
		$branch = $branches[ $id ] ?? null;
		if ( ! $branch || isset( $items[ $id ] ) || '' === trim( $branch['name'] ) || '' === trim( $branch['address'] ) || ! wp_attachment_is_image( $branch['image'] ) || ! wp_get_attachment_image_src( $branch['image'], 'full' ) ) {
			continue;
		}
		$items[ $id ] = $branch + array( 'pinned' => '1' === ( $item['pinned'] ?? '' ) );
	}
	return array_values( $items );
}

function rhm_customize_home_branches( WP_Customize_Manager $wp_customize ): void {
	$wp_customize->add_section(
		'rhm_home_branches',
		array(
			'title'       => __( 'Chi nhánh (Trang chủ)', 'themeranghammatsgcantho' ),
			'description' => __( 'Chọn chi nhánh đã xuất bản, có tên, địa chỉ và ảnh đại diện. Danh sách độc lập với chân trang; để trống sẽ ẩn section.', 'themeranghammatsgcantho' ),
			'priority'    => 34,
		)
	);
	rhm_customize_fields(
		$wp_customize,
		'rhm_home_branches',
		array(
			'rhm_home_branches_eyebrow' => array( __( 'Dòng nhỏ trên tiêu đề (để trống = ẩn)', 'themeranghammatsgcantho' ), 'text', 'sanitize_text_field', array( 'default' => rhm_home_branches_default_eyebrow() ) ),
			'rhm_home_branches_title'   => array( __( 'Tiêu đề', 'themeranghammatsgcantho' ), 'text', 'sanitize_text_field', array( 'default' => rhm_home_branches_default_title() ) ),
		)
	);
	rhm_customize_list(
		$wp_customize,
		'rhm_home_branches',
		'rhm_home_branch_ids',
		__( 'Chi nhánh hiển thị', 'themeranghammatsgcantho' ),
		__( 'Chi nhánh', 'themeranghammatsgcantho' ),
		rhm_home_branch_fields(),
		__( 'Thêm mới để chọn chi nhánh, dùng ↑ ↓ để sắp xếp hoặc xóa mục để bỏ chọn. Chi nhánh mới không tự xuất hiện trên trang chủ. Tích "Ghim" để chi nhánh hiện thành thẻ lớn ở đầu section, các chi nhánh còn lại hiện dạng slider bên dưới. Không ghim chi nhánh nào: có 1–2 chi nhánh thì cả hai hiện thẻ lớn, từ 3 chi nhánh trở lên hiện slider.', 'themeranghammatsgcantho' )
	);
}
add_action( 'customize_register', 'rhm_customize_home_branches' );
