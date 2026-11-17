<?php
/**
 * Danh sách bài của chuyên mục (category.php): trang 1 = bài nổi bật (LCP) + thanh lọc chuyên mục con + danh sách dòng;
 * trang 2+ = thanh lọc + danh sách (ảnh dòng đầu là LCP, rhm_list_image_loading()). Query: inc/category.php (13 / 12 bài).
 * Danh sách: 1 cột (< 1024px), 2 cột từ 1024px; mỗi dòng ảnh trái 2/5 (card-post layout 'row').
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

global $wp_query;
update_post_thumbnail_cache( $wp_query );

// Ảnh dòng = 2/5 của (cột − gap 24): ≥ 1280px cột (1248 − 64) / 2 = 552 → 227px; 1024–1279px cột 50vw − 48 → 20vw − 29px;
// 640–1023px cột rộng hết khung (100vw − 32) → 40vw − 22px; < 640px gap 16 → 40vw − 19px.
$rhm_sizes = '(min-width: 1280px) 227px, (min-width: 1024px) calc(20vw - 29px), (min-width: 640px) calc(40vw - 22px), calc(40vw - 19px)';
?>
<div class="site-container py-10 lg:py-14">
	<?php if ( have_posts() ) : ?>
		<?php
		if ( ! is_paged() ) {
			the_post();
			get_template_part( 'template-parts/content/featured-post', null, array( 'label' => single_cat_title( '', false ) ) );
		}
		?>

		<div class="<?php echo esc_attr( is_paged() ? '' : 'mt-12 border-t border-thread pt-10 lg:mt-16 lg:pt-12' ); ?>">
			<h2 class="sr-only"><?php esc_html_e( 'Bài viết mới nhất', 'themeranghammatsgcantho' ); ?></h2>
			<?php get_template_part( 'template-parts/components/category-filter' ); ?>

			<?php if ( have_posts() ) : ?>
				<div class="c-rows mt-8 lg:grid lg:grid-cols-2 lg:gap-x-16">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part(
							'template-parts/content/card',
							'post',
							array(
								'loading' => is_paged() ? rhm_list_image_loading() : 'lazy', // Trang 1 đã có bài nổi bật là LCP.
								'sizes'   => $rhm_sizes,
								'heading' => 'h3',
							)
						);
					endwhile;
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php rhm_pagination(); ?>
	<?php else : ?>
		<?php get_template_part( 'template-parts/content/content-none' ); ?>
	<?php endif; ?>
</div>
