<?php
/**
 * Đầu trang danh sách (giao diện v1.0): breadcrumb → H1 (rhm_get_page_title()) → mô tả → phần thêm.
 * Dùng ở: danh mục / lưu trữ (archive.php, category qua featured-grid), Tin tức (index.php), tìm kiếm, danh sách bác sĩ.
 * (Trang thường + landing vẫn dùng components/page-title.php.) CSS: utility của archive.css.
 *
 * $args (tùy chọn): description (HTML đã an toàn, vd the_archive_description; trống → mô tả của chuyên mục / lưu trữ),
 * after (callable in thêm nội dung dưới mô tả, vd ô tìm kiếm).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_description = (string) ( $args['description'] ?? '' );
if ( '' === $rhm_description && ( is_category() || is_tag() || is_tax() ) ) {
	$rhm_description = (string) get_the_archive_description();
}
$rhm_after = $args['after'] ?? null;
?>
<header class="border-b border-thread bg-porcelain">
	<div class="site-container py-8 lg:py-12">
		<?php rhm_breadcrumb(); ?>
		<h1 class="c-h1 mt-4 max-w-4xl"><?php echo esc_html( rhm_get_page_title() ); ?></h1>
		<?php if ( '' !== trim( wp_strip_all_tags( $rhm_description ) ) ) : ?>
			<div class="c-lead mt-4 max-w-3xl [&_p+p]:mt-3"><?php echo wp_kses_post( $rhm_description ); ?></div>
		<?php endif; ?>
		<?php if ( is_callable( $rhm_after ) ) : ?>
			<div class="mt-6"><?php $rhm_after(); ?></div>
		<?php endif; ?>
	</div>
</header>
