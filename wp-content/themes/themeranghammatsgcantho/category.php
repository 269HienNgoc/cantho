<?php
/**
 * Trang chuyên mục bài viết. CSS: archive.css. Đầu trang: components/content-header (H1 + mô tả chuyên mục);
 * nội dung: components/featured-grid (bài nổi bật + thanh lọc + danh sách).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/components/content-header' );
get_template_part( 'template-parts/components/featured-grid' );
get_footer();
