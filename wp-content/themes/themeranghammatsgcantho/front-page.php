<?php
/**
 * Trang chủ. CSS: page-home.css. Mỗi section là 1 file trong template-parts/home/ (dữ liệu: các module trong inc/),
 * cuối trang là dải đặt lịch dùng chung (template-parts/components/booking-band.php).
 * Section thiếu dữ liệu tự không in.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

get_header();

$rhm_sections = array(
	'hero',
	'quick-services',
	'why',
	'doctors',
	'before-after',
	'news',
	'branches',
);

foreach ( $rhm_sections as $rhm_section ) {
	get_template_part( 'template-parts/home/' . $rhm_section );
}

get_template_part( 'template-parts/components/booking-band' );

// Nội dung soạn trong trình soạn thảo của trang "Trang chủ" — chỉ khi trang chủ là trang tĩnh
// (Cài đặt → Đọc → "Trang tĩnh"); khi trang chủ hiện bài viết mới nhất thì vòng lặp là danh sách bài, không in.
if ( 'page' === get_option( 'show_on_front' ) ) :
	while ( have_posts() ) :
		the_post();
		if ( '' !== trim( get_the_content() ) ) :
			?>
			<div class="site-container entry-content max-w-[52rem] py-14 lg:py-20"><?php the_content(); ?></div>
			<?php
		endif;
	endwhile;
endif;

get_footer();
