<?php
/**
 * Trang chủ: dịch vụ nổi bật — H2 + mô tả canh giữa, lưới thẻ: ảnh 4:3 → icon nét xanh + tên + mũi tên.
 * Dữ liệu: inc/quick-services.php (Tùy biến → "Dịch vụ nổi bật (Trang chủ)"). Không có mục nào → không in. Không JS.
 * id="dich-vu": nút thứ 2 của hero mặc định cuộn tới đây.
 *
 * Lưới: < 768px 2 cột (gap 12px), 768px 3 cột (gap 16px), từ 1024px (gap 20px) số cột theo số mục để hàng cuối không lẻ loi:
 * ≤ 3 → 3, 4 → 4, 5 → 5, 6 → 3 (≥ 1280px = khung đầy: 6), 7–8 → 4. Class viết nguyên văn trong $rhm_layouts để Tailwind quét được.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_items = rhm_get_quick_services();
if ( ! $rhm_items ) {
	return;
}
$rhm_title    = rhm_quick_services_text( 'title' );
$rhm_subtitle = rhm_quick_services_text( 'subtitle' );

// Số cột từ 1024px theo số mục: [class lưới, [min-width px => số cột]].
$rhm_layouts = array(
	3 => array( 'lg:grid-cols-3', array( 1024 => 3 ) ),
	4 => array( 'lg:grid-cols-4', array( 1024 => 4 ) ),
	5 => array( 'lg:grid-cols-5', array( 1024 => 5 ) ),
	6 => array( 'lg:grid-cols-3 xl:grid-cols-6', array( 1280 => 6, 1024 => 3 ) ),
	8 => array( 'lg:grid-cols-4', array( 1024 => 4 ) ),
);
$rhm_count = count( $rhm_items );
$rhm_key   = $rhm_count <= 3 ? 3 : ( $rhm_count >= 7 ? 8 : $rhm_count );
[ $rhm_grid, $rhm_lg_cols ] = $rhm_layouts[ $rhm_key ];

// sizes ảnh = 1 cột lưới: (khung − (cột − 1) × gap) / cột, khung = min(100vw, 1280px) − 32px.
// ≥ 1280px: số px cố định; 1024–1279px: 100/cột vw − (32 + (cột − 1) × 20) / cột px; 768px: 3 cột gap 16; < 768px: 2 cột gap 12.
$rhm_col_size = static function ( int $cols, int $gap, ?int $fixed_width = null ): string {
	$minus = 32 + ( $cols - 1 ) * $gap;
	if ( $fixed_width ) {
		return round( ( $fixed_width - $minus ) / $cols ) . 'px';
	}
	return sprintf( 'calc(%svw - %spx)', round( 100 / $cols, 2 ), round( $minus / $cols ) );
};
$rhm_sizes_parts = array( '(min-width: 1280px) ' . $rhm_col_size( reset( $rhm_lg_cols ), 20, 1280 ) );
foreach ( $rhm_lg_cols as $rhm_min => $rhm_cols ) {
	$rhm_sizes_parts[] = "(min-width: {$rhm_min}px) " . $rhm_col_size( $rhm_cols, 20 );
}
$rhm_sizes_parts[] = '(min-width: 768px) ' . $rhm_col_size( 3, 16 );
$rhm_sizes_parts[] = $rhm_col_size( 2, 12 );
$rhm_sizes         = implode( ', ', $rhm_sizes_parts );
?>
<section id="dich-vu" class="py-8 lg:py-10"<?php echo '' !== $rhm_title ? ' aria-labelledby="home-services-title"' : ' aria-label="' . esc_attr__( 'Dịch vụ nổi bật', 'themeranghammatsgcantho' ) . '"'; ?>>
	<div class="site-container">
		<?php if ( '' !== $rhm_title ) : ?>
			<div class="mx-auto mb-8 max-w-3xl text-center lg:mb-10">
				<h2 id="home-services-title" class="c-h2"><?php echo esc_html( $rhm_title ); ?></h2>
				<?php if ( '' !== $rhm_subtitle ) : ?>
					<p class="mt-3 text-lg leading-relaxed"><?php echo esc_html( $rhm_subtitle ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<ul class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-4 lg:gap-5 <?php echo esc_attr( $rhm_grid ); ?>">
			<?php foreach ( $rhm_items as $rhm_item ) : ?>
				<?php
				$rhm_tag  = $rhm_item['link'] ? 'a' : 'div';
				$rhm_href = $rhm_item['link'] ? ' href="' . esc_url( $rhm_item['link'] ) . '"' . rhm_external_link_attrs( $rhm_item['link'] ) : '';
				?>
				<li>
					<<?php echo esc_html( $rhm_tag ); ?> class="c-card group flex h-full flex-col<?php echo $rhm_item['link'] ? ' c-card-link' : ''; ?>"<?php echo $rhm_href; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
						<?php if ( $rhm_item['image'] ) : ?>
							<span class="relative block aspect-[4/3] overflow-hidden bg-soft">
								<?php
								echo wp_get_attachment_image(
									$rhm_item['image'],
									'full',
									false,
									array(
										'class'    => 'absolute inset-0 size-full object-cover transition-transform duration-300 group-hover:scale-[1.03]',
										'sizes'    => $rhm_sizes,
										'alt'      => '',
										'loading'  => 'lazy',
										'decoding' => 'async',
									)
								);
								?>
							</span>
						<?php endif; ?>
						<span class="flex flex-1 items-center gap-2.5 px-3 py-3.5 lg:px-4 lg:py-4">
							<?php if ( $rhm_item['icon'] ) : ?>
								<?php echo rhm_icon_svg( 'menu', $rhm_item['icon'], 'size-6 shrink-0 text-primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme. ?>
							<?php endif; ?>
							<span class="min-w-0 flex-1 text-[15px] leading-snug font-semibold text-ink lg:text-base"><?php echo nl2br( esc_html( $rhm_item['title'] ), false ); ?></span>
							<?php if ( $rhm_item['link'] ) : ?>
								<?php echo rhm_get_icon( 'arrow-right', 'size-4 shrink-0 text-ink transition-colors duration-150 group-hover:text-primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
							<?php endif; ?>
						</span>
					</<?php echo esc_html( $rhm_tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
