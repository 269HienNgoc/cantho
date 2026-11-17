<?php
/**
 * Thẻ nội dung trong danh sách (bài viết, trang, bác sĩ — kết quả tìm kiếm có cả 3). CSS: utility của bundle trang
 * (archive.css: danh mục, lưu trữ, Tin tức, tìm kiếm; single-doctor.css: "Bài viết mới nhất" ở hồ sơ bác sĩ).
 *
 * $args['layout']:
 *  - 'row' (mặc định): dòng ngang — ảnh trái 2/5 (3:2; bác sĩ 4:5 giữ đầu), chữ phải: nhãn loại / chuyên mục + ngày → tiêu đề H2
 *    → mô tả. Các dòng ngăn bằng đường Chỉ (border-t, dòng đầu không viền — xem .c-rows trong archive.css).
 *  - 'stack': thẻ dọc (ảnh trên, chữ dưới) cho lưới 3 cột.
 * $args['sizes']: độ rộng hiển thị thật của ảnh theo bố cục đang dùng (bắt buộc tính theo trang gọi).
 * $args['loading']: 'lazy' (mặc định) | 'eager' | 'lcp' (eager + fetchpriority=high) — trang danh sách không có bài nổi bật
 *   dùng rhm_list_image_loading() (inc/template-tags.php).
 * $args['heading']: thẻ tiêu đề ('h2' mặc định; 'h3' khi nằm dưới 1 H2 của section).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_layout  = 'stack' === ( $args['layout'] ?? '' ) ? 'stack' : 'row';
$rhm_loading = $args['loading'] ?? 'lazy';
$rhm_heading = 'h3' === ( $args['heading'] ?? '' ) ? 'h3' : 'h2';
$rhm_type    = get_post_type();
$rhm_attrs   = array(
	'class'    => 'aspect-[3/2] w-full object-cover',
	'alt'      => '',
	'sizes'    => $args['sizes'] ?? '(min-width: 640px) 40vw, calc(40vw - 19px)',
	'loading'  => 'lazy' === $rhm_loading ? 'lazy' : 'eager',
	'decoding' => 'lcp' === $rhm_loading ? 'auto' : 'async', // Ảnh LCP: không async.
);
if ( 'lcp' === $rhm_loading ) {
	$rhm_attrs['fetchpriority'] = 'high';
}

// Nhãn trên tiêu đề: chuyên mục (bài viết) | "Bác sĩ" + chức danh | "Trang".
$rhm_label = match ( $rhm_type ) {
	'post'   => ( get_the_category()[0] ?? null )?->name ?? '',
	'bac-si' => __( 'Bác sĩ', 'themeranghammatsgcantho' ),
	'page'   => __( 'Trang', 'themeranghammatsgcantho' ),
	default  => '',
};
$rhm_position = 'bac-si' === $rhm_type ? sanitize_text_field( (string) rhm_get_doctor_field( 'position', get_the_ID() ) ) : '';
$rhm_excerpt  = 'bac-si' === $rhm_type ? '' : wp_strip_all_tags( get_the_excerpt() );

$rhm_wrap = 'row' === $rhm_layout
	? 'grid grid-cols-[minmax(0,2fr)_minmax(0,3fr)] items-start gap-4 sm:gap-6'
	: 'flex flex-col gap-4';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $rhm_wrap ); ?>>
	<a class="c-media block" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?>
			<?php if ( 'bac-si' === $rhm_type ) : ?>
				<?php
				// Chân dung: khung 4:5, giữ phần đầu (object-top), ảnh gốc + srcset (bản cắt 3:2 rhm-card sẽ mất đầu).
				$rhm_thumb_id = (int) get_post_thumbnail_id();
				?>
				<span class="relative block aspect-[4/5]">
					<?php
					the_post_thumbnail(
						'full',
						array_merge(
							$rhm_attrs,
							array(
								'class' => 'absolute inset-0 size-full object-cover object-top',
								'sizes' => rhm_get_doctor_image_sizes( $rhm_thumb_id, $rhm_attrs['sizes'], 4 / 5 ),
							)
						)
					);
					?>
				</span>
			<?php else : ?>
				<?php the_post_thumbnail( 'rhm-card', $rhm_attrs ); ?>
			<?php endif; ?>
		<?php else : ?>
			<span class="flex <?php echo esc_attr( 'bac-si' === $rhm_type ? 'aspect-[4/5]' : 'aspect-[3/2]' ); ?> items-center justify-center text-primary/25"><?php echo rhm_get_icon( 'tooth', 'size-1/3' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
		<?php endif; ?>
	</a>

	<div class="min-w-0">
		<?php if ( '' !== $rhm_label || 'post' === $rhm_type ) : ?>
			<p class="c-meta gap-x-4 text-sm sm:text-[15px]">
				<?php if ( '' !== $rhm_label ) : ?>
					<span class="font-medium text-primary"><?php echo esc_html( $rhm_label ); ?></span>
				<?php endif; ?>
				<?php if ( 'post' === $rhm_type ) : ?>
					<span><?php rhm_posted_on(); ?></span>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<<?php echo esc_html( $rhm_heading ); ?> class="mt-1.5 text-[17px] leading-snug font-bold text-balance max-sm:line-clamp-4 sm:text-xl">
			<a class="c-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</<?php echo esc_html( $rhm_heading ); ?>>

		<?php if ( '' !== $rhm_position ) : ?>
			<p class="mt-1.5 text-[15px] leading-snug sm:text-base"><?php echo esc_html( $rhm_position ); ?></p>
		<?php endif; ?>

		<?php if ( '' !== $rhm_excerpt ) : ?>
			<p class="mt-2 line-clamp-3 text-base leading-relaxed max-sm:hidden"><?php echo esc_html( $rhm_excerpt ); ?></p>
		<?php endif; ?>
	</div>
</article>
