<?php
/**
 * Bài nổi bật đầu trang danh mục (trang 1): ảnh trái 7/12 (3:2) | chữ phải: chuyên mục, ngày → H2 → mô tả → nút "Đọc bài".
 * Dưới 1024px ảnh trên, chữ dưới. CSS: archive.css.
 *
 * Ảnh là LCP của trang: eager + fetchpriority=high, decoding mặc định.
 * sizes: ≥ 1280px (1248 − 64) × 7/12 = 691px; 1024–1279px 58.33vw − 56px; dưới 1024px rộng hết khung.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_thumbnail_id = (int) get_post_thumbnail_id();
$rhm_label        = (string) ( $args['label'] ?? single_cat_title( '', false ) );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'grid items-center gap-6 lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)] lg:gap-16' ); ?>>
	<a class="c-media block aspect-[3/2]" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( $rhm_thumbnail_id ) : ?>
			<?php
			echo wp_get_attachment_image(
				$rhm_thumbnail_id,
				'full',
				false,
				array(
					'class'         => 'absolute inset-0 size-full object-cover',
					'alt'           => '',
					'sizes'         => '(min-width: 1280px) 691px, (min-width: 1024px) calc(58.33vw - 56px), calc(100vw - 32px)',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'decoding'      => 'auto',
				)
			);
			?>
		<?php else : ?>
			<span class="absolute inset-0 flex items-center justify-center text-primary/25"><?php echo rhm_get_icon( 'tooth', 'size-1/3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
		<?php endif; ?>
	</a>

	<div>
		<p class="c-meta">
			<?php if ( '' !== $rhm_label ) : ?>
				<span class="font-medium text-primary"><?php echo esc_html( $rhm_label ); ?></span>
			<?php endif; ?>
			<span><?php rhm_posted_on(); ?></span>
		</p>
		<h2 class="mt-3 text-[26px] leading-tight font-bold text-balance lg:text-4xl"><a class="c-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="mt-4 line-clamp-4 text-lg leading-relaxed"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<a class="btn btn-primary c-btn mt-6" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Đọc bài', 'themeranghammatsgcantho' ); ?><span class="sr-only"> <?php the_title(); ?></span>
		</a>
	</div>
</article>
