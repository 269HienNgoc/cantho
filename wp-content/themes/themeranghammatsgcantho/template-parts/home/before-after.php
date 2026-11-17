<?php
/**
 * Trang chủ: ảnh trước – sau của khách hàng. Dữ liệu: inc/home-before-after.php (Tùy biến → "Trước – sau (Trang chủ)").
 *
 * Mỗi thẻ: chân dung (trái, cao bằng cột phải) | Trước + Sau 3:2 xếp dọc → tên (H3) + nơi ở (desktop cùng hàng: tên trái, nơi ở phải)
 * → dịch vụ, vật liệu.
 * Slider native (scroll-snap) + nút ‹ › do src/js/_tiles.js (nạp từ carousel.js) — hook: [data-rhm-tiles],
 * [data-rhm-tiles-controls], [data-rhm-tiles-prev|next] (aria-controls = id track), [data-rhm-tiles-track].
 * ≥ 1024px mỗi trang 4 thẻ (lưới 2×2, wrapper `contents lg:grid`); 768–1023px 2 thẻ / khung; < 768px 1 thẻ 88% (hé thẻ kế).
 * Chưa có JS vẫn vuốt được, CLS 0, ảnh lazy.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_items = rhm_get_home_before_after();
if ( ! $rhm_items ) {
	return;
}

$rhm_count    = count( $rhm_items );
$rhm_id       = 'home-before-after';
$rhm_title_id = $rhm_id . '-title';
$rhm_track_id = $rhm_id . '-track';
$rhm_title    = trim( (string) get_theme_mod( 'rhm_home_ba_title', rhm_home_ba_default_title() ) ) ?: rhm_home_ba_default_title();
$rhm_eyebrow  = trim( (string) get_theme_mod( 'rhm_home_ba_eyebrow', rhm_home_ba_default_eyebrow() ) );

if ( $rhm_count > 1 && file_exists( RHM_THEME_DIR . '/dist/js/carousel.js' ) ) {
	wp_enqueue_script_module( 'rhm-carousel', RHM_THEME_URI . '/dist/js/carousel.js', array(), rhm_asset_version( 'dist/js/carousel.js' ) );
}

// 1 cột ảnh = (thẻ − đệm 2 × 16 − viền 2 − gap 8) / 2. Khung nội dung W = min(100vw, 1280px) − 32px.
// ≥ 1280px: thẻ (1248 − 24) / 2 = 572 → (572 − 42) / 2 = 285px. 1024–1279px: thẻ 50vw − 28 → 25vw − 35px.
// 768–1023px: thẻ 50vw − 24 → 25vw − 33px. < 768px: thẻ 88vw − 28 → (88vw − 70) / 2 = 44vw − 35px.
$rhm_sizes = '(min-width: 1280px) 285px, (min-width: 1024px) calc(25vw - 35px), (min-width: 768px) calc(25vw - 33px), calc(44vw - 35px)';

$rhm_image = static function ( int $id, string $alt, string $class ) use ( $rhm_sizes ): string {
	return wp_get_attachment_image(
		$id,
		'full',
		false,
		array(
			'class'    => $class,
			'alt'      => $alt,
			'sizes'    => $rhm_sizes,
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
};

$rhm_label = 'absolute top-2 left-2 rounded-md px-2.5 py-1 text-sm font-medium text-white';
?>
<section id="<?php echo esc_attr( $rhm_id ); ?>" class="py-8 lg:py-10" aria-labelledby="<?php echo esc_attr( $rhm_title_id ); ?>">
	<div class="site-container" data-rhm-tiles>
		<div class="c-head">
			<div class="max-w-2xl">
				<?php if ( '' !== $rhm_eyebrow ) : ?>
					<p class="c-eyebrow"><?php echo esc_html( $rhm_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $rhm_title_id ); ?>" class="c-h2"><?php echo esc_html( $rhm_title ); ?></h2>
			</div>
			<?php if ( $rhm_count > 1 ) : ?>
				<div class="invisible flex gap-2" data-rhm-tiles-controls>
					<button class="c-nav" type="button" aria-label="<?php esc_attr_e( 'Khách hàng trước', 'themeranghammatsgcantho' ); ?>" aria-controls="<?php echo esc_attr( $rhm_track_id ); ?>" data-rhm-tiles-prev disabled>
						<?php echo rhm_get_icon( 'chevron', 'size-5 rotate-90' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					</button>
					<button class="c-nav" type="button" aria-label="<?php esc_attr_e( 'Khách hàng tiếp theo', 'themeranghammatsgcantho' ); ?>" aria-controls="<?php echo esc_attr( $rhm_track_id ); ?>" data-rhm-tiles-next disabled>
						<?php echo rhm_get_icon( 'chevron', 'size-5 -rotate-90' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					</button>
				</div>
			<?php endif; ?>
		</div>

		<div id="<?php echo esc_attr( $rhm_track_id ); ?>" class="flex snap-x snap-mandatory gap-4 overflow-x-auto overscroll-x-contain [scrollbar-width:none] lg:gap-6" role="region" aria-labelledby="<?php echo esc_attr( $rhm_title_id ); ?>" data-rhm-tiles-track<?php echo $rhm_count > 1 ? ' tabindex="0"' : ''; ?>>
			<?php foreach ( array_chunk( $rhm_items, 4 ) as $rhm_page ) : ?>
				<div class="contents lg:grid lg:w-full lg:shrink-0 lg:snap-start lg:grid-cols-2 lg:gap-6">
					<?php foreach ( $rhm_page as $rhm_item ) : ?>
						<article class="c-card w-[88%] shrink-0 snap-start p-4 shadow-none md:w-[calc(50%-8px)] lg:w-auto">
							<div class="grid grid-cols-2 gap-2">
								<div class="c-media row-span-2">
									<?php echo $rhm_image( $rhm_item['portrait'], $rhm_item['name'], 'absolute inset-0 size-full object-cover' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?>
								</div>
								<div class="c-media aspect-[3/2]">
									<?php
									/* translators: %s: tên khách hàng. */
									echo $rhm_image( $rhm_item['before'], sprintf( __( 'Trước điều trị – %s', 'themeranghammatsgcantho' ), $rhm_item['name'] ), 'size-full object-cover' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image().
									?>
									<span class="<?php echo esc_attr( $rhm_label ); ?> bg-ink/85" aria-hidden="true"><?php esc_html_e( 'Trước', 'themeranghammatsgcantho' ); ?></span>
								</div>
								<div class="c-media aspect-[3/2]">
									<?php
									/* translators: %s: tên khách hàng. */
									echo $rhm_image( $rhm_item['after'], sprintf( __( 'Sau điều trị – %s', 'themeranghammatsgcantho' ), $rhm_item['name'] ), 'size-full object-cover' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image().
									?>
									<span class="<?php echo esc_attr( $rhm_label ); ?> bg-primary" aria-hidden="true"><?php esc_html_e( 'Sau', 'themeranghammatsgcantho' ); ?></span>
								</div>
							</div>

							<?php // Desktop: tên trái, địa điểm phải (cùng hàng, canh theo chân chữ); dưới 1024px địa điểm xuống dòng dưới tên. ?>
							<div class="mt-4 lg:flex lg:items-baseline lg:justify-between lg:gap-4">
								<h3 class="min-w-0 text-lg leading-snug font-bold text-ink wrap-anywhere lg:text-xl"><?php echo esc_html( $rhm_item['name'] ); ?></h3>
								<?php if ( '' !== $rhm_item['location'] ) : ?>
									<p class="text-[15px] text-silt lg:shrink-0 lg:text-right"><?php echo esc_html( $rhm_item['location'] ); ?></p>
								<?php endif; ?>
							</div>

							<?php if ( '' !== $rhm_item['service'] || '' !== $rhm_item['product'] ) : ?>
								<ul class="mt-3 space-y-1.5 border-t border-thread pt-3 text-base leading-snug">
									<?php if ( '' !== $rhm_item['service'] ) : ?>
										<li class="flex gap-2">
											<?php echo rhm_icon_svg( 'menu', 'tooth', 'mt-px size-5 shrink-0 text-primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme. ?>
											<?php echo esc_html( $rhm_item['service'] ); ?>
										</li>
									<?php endif; ?>
									<?php if ( '' !== $rhm_item['product'] ) : ?>
										<li class="flex gap-2">
											<?php echo rhm_icon_svg( 'menu', 'implant', 'mt-px size-5 shrink-0 text-primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme. ?>
											<?php echo esc_html( $rhm_item['product'] ); ?>
										</li>
									<?php endif; ?>
								</ul>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
