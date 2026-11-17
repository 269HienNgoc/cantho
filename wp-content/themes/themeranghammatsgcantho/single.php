<?php
/**
 * Chi tiết bài viết. CSS: single-post.css.
 * Cột đọc 48rem (~75 ký tự/dòng): đầu bài (components/post-header) → bác sĩ tham vấn ([bac_si_tham_van]) → sapo (excerpt) → nội dung
 * (mục lục tự chèn đầu nội dung, inc/toc.php) → FAQ → tài liệu tham khảo → từ khóa → bài trước / sau.
 * Từ 1280px: lưới 3 cột 1fr | 48rem | 1fr (cách 20px) → cột đọc nằm CHÍNH GIỮA trang, khối đặt lịch ở cột phải (~220px trong khung
 * 1280px), dính theo khi cuộn (sticky, dưới header); cột trái để trống. Sau bài: dải đặt lịch.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

get_header();

$rhm_hotline = rhm_get_option( 'rhm_hotline' );

while ( have_posts() ) :
	the_post();
	?>
	<div class="site-container py-10 lg:py-14 xl:grid xl:grid-cols-[minmax(0,1fr)_minmax(0,48rem)_minmax(0,1fr)] xl:gap-5">
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'mx-auto max-w-3xl min-w-0 xl:col-start-2 xl:mx-0 xl:max-w-none' ); ?>>
			<?php get_template_part( 'template-parts/components/post-header' ); ?>

			<div class="mt-8">
				<?php echo do_shortcode( '[bac_si_tham_van]' ); ?>

				<?php if ( has_excerpt() ) : // Mô tả ngắn: ngay dưới box bác sĩ (không có box thì ngay dưới đầu bài). ?>
					<p class="c-lead mb-8"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
			</div>

			<?php
			// ACF "Thông tin chuyên môn bài viết" (inc/post-extras.php) — cũng là nguồn schema FAQPage / citation (inc/schema.php).
			get_template_part( 'template-parts/components/post-faq' );
			get_template_part( 'template-parts/components/post-references' );
			?>

			<?php if ( has_tag() ) : ?>
				<p class="mt-10 text-base"><?php the_tags( '<span class="text-silt">' . esc_html__( 'Từ khóa:', 'themeranghammatsgcantho' ) . '</span> ', ', ' ); ?></p>
			<?php endif; ?>

			<?php
			the_post_navigation(
				array(
					'prev_text' => '<span class="post-navigation__label">' . esc_html__( 'Bài trước', 'themeranghammatsgcantho' ) . '</span><span class="post-navigation__title">%title</span>',
					'next_text' => '<span class="post-navigation__label">' . esc_html__( 'Bài sau', 'themeranghammatsgcantho' ) . '</span><span class="post-navigation__title">%title</span>',
				)
			);
			?>
		</article>

		<aside class="hidden xl:col-start-3 xl:block" aria-label="<?php esc_attr_e( 'Đặt lịch khám', 'themeranghammatsgcantho' ); ?>">
			<div class="sticky top-[calc(var(--header-h,0px)+24px)] rounded-lg bg-porcelain p-5">
				<p class="text-lg leading-snug font-bold text-balance text-ink"><?php esc_html_e( 'Cần bác sĩ khám trực tiếp?', 'themeranghammatsgcantho' ); ?></p>
				<p class="mt-2 text-[15px] leading-relaxed"><?php echo esc_html( rhm_booking_cta_default( 'text' ) ); ?></p>
				<?php if ( '' !== $rhm_hotline ) : ?>
					<p class="mt-5 leading-tight">
						<span class="block text-[15px]"><?php esc_html_e( 'Gọi phòng khám', 'themeranghammatsgcantho' ); ?></span>
						<a class="mt-1 block text-[22px] font-bold whitespace-nowrap text-ink tabular-nums hover:text-primary" href="<?php echo esc_url( rhm_get_tel_href( $rhm_hotline ) ); ?>"><?php echo esc_html( $rhm_hotline ); ?></a>
					</p>
				<?php endif; ?>
				<?php rhm_booking_button( 'c-btn mt-5 w-full' ); ?>
			</div>
		</aside>
	</div>
	<?php
endwhile;

get_template_part( 'template-parts/components/booking-band' );
get_footer();
