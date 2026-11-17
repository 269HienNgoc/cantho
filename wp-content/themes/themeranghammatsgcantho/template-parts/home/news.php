<?php
/**
 * Trang chủ: kiến thức / tin tức — 2 khối theo chuyên mục (Customizer "Tin tức (Trang chủ)"). Dữ liệu + hàm in: inc/news.php.
 *
 * ≥ 1024px: 2 cột 8 : 4, cùng chiều cao (grid stretch):
 * - Trái: bài dẫn = thẻ ngang (ảnh 3:2 trái | tiêu đề, mô tả, ngày phải) → 4 bài nhỏ lưới 2 × 2 (ảnh 128px + tiêu đề + ngày).
 * - Phải: khung thẻ trắng kéo dài bằng cột trái, mỗi bài = ảnh 96px + tiêu đề + ngày, ngăn bằng đường Chỉ.
 * 640–1023px: 2 khối xếp dọc, bài dẫn vẫn ngang, bài nhỏ 2 cột. < 640px: bài dẫn ảnh trên chữ dưới, mọi danh sách 1 cột.
 * Ảnh lazy (section nằm dưới màn hình đầu).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_show = rhm_news_show();
$rhm_main = rhm_news_block( 'main', RHM_NEWS_MAIN_COUNT );
$rhm_side = rhm_news_block( 'side', RHM_NEWS_SIDE_COUNT, wp_list_pluck( $rhm_main['query']->posts, 'ID' ) );

$rhm_has_main = $rhm_main['query']->have_posts();
$rhm_has_side = $rhm_side['query']->have_posts();
if ( ! $rhm_has_main && ! $rhm_has_side ) {
	return;
}
$rhm_both = $rhm_has_main && $rhm_has_side;

// Ảnh bài dẫn = nửa thẻ ở cột trái. ≥ 1280px: cột (1248 − 40) × 8/12 = 752 → 403px;
// 1024–1279px: ((100vw − 72) × 2/3) / 2 = 33.33vw − 24px; 640–1023px: (100vw − 32) / 2 = 50vw − 16px; < 640px: 100vw − 34px.
$rhm_lead_sizes = '(min-width: 1280px) 403px, (min-width: 1024px) calc(33.33vw - 24px), (min-width: 640px) calc(50vw - 16px), calc(100vw - 34px)';

?>
<section class="border-y border-thread bg-white py-8 lg:py-10" aria-label="<?php esc_attr_e( 'Kiến thức nha khoa', 'themeranghammatsgcantho' ); ?>">
	<div class="site-container grid gap-10 <?php echo esc_attr( $rhm_both ? 'lg:grid-cols-[minmax(0,8fr)_minmax(0,4fr)]' : '' ); ?>">
		<?php if ( $rhm_has_main ) : ?>
			<div class="min-w-0">
				<?php rhm_news_heading( $rhm_main ); ?>
				<?php $rhm_main['query']->the_post(); ?>
				<article class="c-card grid sm:grid-cols-2">
					<a class="relative block aspect-[3/2] overflow-hidden bg-soft sm:aspect-auto sm:min-h-56" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
						<?php echo rhm_news_thumbnail( $rhm_lead_sizes, 'absolute inset-0 size-full object-cover' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() / static markup. ?>
					</a>
					<div class="flex flex-col justify-center p-5 lg:p-7">
						<h3 class="text-xl leading-snug font-bold text-balance lg:text-2xl">
							<a class="c-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</h3>
						<?php if ( $rhm_show['excerpt'] ) : ?>
							<p class="mt-3 line-clamp-3 text-base leading-relaxed"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
						<?php endif; ?>
						<?php rhm_news_meta( $rhm_show ); ?>
					</div>
				</article>

				<?php if ( $rhm_main['query']->have_posts() ) : ?>
					<ul class="mt-6 grid gap-x-8 gap-y-5 sm:grid-cols-2">
						<?php
						while ( $rhm_main['query']->have_posts() ) :
							$rhm_main['query']->the_post();
							rhm_news_row( $rhm_show, 'w-28 sm:w-32', '(min-width: 640px) 128px, 112px', 'flex gap-4' );
						endwhile;
						?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $rhm_has_side ) : ?>
			<div class="flex min-w-0 flex-col">
				<?php rhm_news_heading( $rhm_side ); ?>
				<ul class="c-card flex-1 px-5 py-1 shadow-none">
					<?php
					while ( $rhm_side['query']->have_posts() ) :
						$rhm_side['query']->the_post();
						rhm_news_row( $rhm_show, 'w-24', '96px', 'flex gap-4 border-t border-thread py-4 first:border-t-0' );
					endwhile;
					?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php
wp_reset_postdata();
