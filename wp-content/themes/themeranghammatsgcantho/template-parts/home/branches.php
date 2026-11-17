<?php
/**
 * Trang chủ: hệ thống chi nhánh. Dữ liệu: inc/home-branches.php (Tùy biến → "Chi nhánh (Trang chủ)").
 *
 * Chi nhánh chia 2 nhóm, cùng 1 section (1 tiêu đề H2):
 * - Thẻ lớn ("nổi bật"): chi nhánh được tích "Ghim". Không ghim chi nhánh nào → có 1–2 chi nhánh thì cả hai là thẻ lớn,
 *   từ 3 chi nhánh trở lên thì tất cả vào slider.
 *   Số thẻ lớn: 1 → thẻ ngang từ 1024px (ảnh 7/12 trái, thông tin phải); 2 → 2 cột từ 768px; ≥ 3 → 2 cột từ 768px, 3 cột từ 1024px.
 *   Thẻ: ảnh 16:10 + nhãn số "01 Chi nhánh" → vạch gradient → tên H3 cỡ lớn → Địa chỉ / Hotline (icon tròn + nhãn) →
 *   nút "Gọi ngay" (có hotline) + "Chỉ đường" (có link bản đồ).
 * - Slider: các chi nhánh còn lại. Thẻ phẳng: ảnh 4:3 → tên H3 → địa chỉ → hotline (tel:) → link "Chỉ đường" (URL Google Maps
 *   của chi nhánh, trống thì không in). Hook JS: .js-carousel > .swiper, .carousel-prev / .carousel-next (Swiper, slidesPerView
 *   'auto', độ rộng slide bằng utility → CLS 0). 640 / 768 / 1024px: 2 / 3 / 4 thẻ; mobile hé thẻ kế (80%).
 *   Có thẻ lớn phía trên → nút ‹ › nằm ở hàng "Các chi nhánh khác" ngay trên slider; không có → ở hàng tiêu đề section.
 * Ảnh lazy (section ở cuối trang).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_items = rhm_get_home_branches();
if ( ! $rhm_items ) {
	return;
}

$rhm_id       = 'home-branches';
$rhm_title_id = $rhm_id . '-title';
$rhm_title    = trim( (string) get_theme_mod( 'rhm_home_branches_title', rhm_home_branches_default_title() ) ) ?: rhm_home_branches_default_title();
$rhm_eyebrow  = trim( (string) get_theme_mod( 'rhm_home_branches_eyebrow', rhm_home_branches_default_eyebrow() ) );

// Chia nhóm: ghim → thẻ lớn, còn lại → slider. Không ghim: ≤ 2 chi nhánh thẻ lớn hết, ≥ 3 slider hết.
$rhm_featured = array_values( array_filter( $rhm_items, static fn( array $item ): bool => $item['pinned'] ) );
$rhm_slider   = array_values( array_filter( $rhm_items, static fn( array $item ): bool => ! $item['pinned'] ) );
if ( ! $rhm_featured && count( $rhm_items ) <= 2 ) {
	$rhm_featured = $rhm_items;
	$rhm_slider   = array();
}

$rhm_featured_count = count( $rhm_featured );
$rhm_slider_count   = count( $rhm_slider );

if ( $rhm_slider_count > 1 && file_exists( RHM_THEME_DIR . '/dist/js/carousel.js' ) ) {
	wp_enqueue_script_module( 'rhm-carousel', RHM_THEME_URI . '/dist/js/carousel.js', array(), rhm_asset_version( 'dist/js/carousel.js' ) );
}

// Ảnh thẻ lớn rộng hết thẻ (thẻ viền 1px, ảnh tràn mép trong). Sửa bố cục thì tính lại.
// 1 thẻ: ≥ 1024px ảnh = 7/12 thẻ → ≥ 1280px 728px, 1024–1279px 58.33vw − 19px; < 1024px 100vw − 32px.
// 2 thẻ: ≥ 1280px (1248 − 24) / 2 = 612px; 768–1279px 50vw − 28px (lề 2 × 16 + gap 24); < 768px 100vw − 32px.
// ≥ 3 thẻ: ≥ 1280px (1248 − 48) / 3 = 400px; 1024–1279px 33.33vw − 27px; 768–1023px 50vw − 28px; < 768px 100vw − 32px.
$rhm_featured_single = 1 === $rhm_featured_count;
$rhm_featured_sizes  = match ( true ) {
	$rhm_featured_single     => '(min-width: 1280px) 728px, (min-width: 1024px) calc(58.33vw - 19px), calc(100vw - 32px)',
	2 === $rhm_featured_count => '(min-width: 1280px) 612px, (min-width: 768px) calc(50vw - 28px), calc(100vw - 32px)',
	default                  => '(min-width: 1280px) 400px, (min-width: 1024px) calc(33.33vw - 27px), (min-width: 768px) calc(50vw - 28px), calc(100vw - 32px)',
};
$rhm_featured_grid = match ( true ) {
	$rhm_featured_single     => '',
	2 === $rhm_featured_count => ' md:grid-cols-2',
	default                  => ' md:grid-cols-2 lg:grid-cols-3',
};

// Ảnh slider = slide − đệm phải 24px (khoảng cách giữa thẻ). Khung .swiper = khung nội dung + lề âm phải 24px.
// ≥ 1280px: (1248 + 24) / 4 − 24 = 294px. 1024–1279px: 25vw − 26px. 768–1023px: 33.33vw − 27px. 640–767px: 50vw − 28px. < 640px: 80vw − 30px.
$rhm_sizes = '(min-width: 1280px) 294px, (min-width: 1024px) calc(25vw - 26px), (min-width: 768px) calc(33.33vw - 27px), (min-width: 640px) calc(50vw - 28px), calc(80vw - 30px)';

// Nút ‹ › giữ chỗ (invisible) từ breakpoint mà mọi thẻ đã vừa khung (< 640px luôn hé thẻ kế → luôn cần nút). 1 thẻ → không in nút.
$rhm_nav_hidden = match ( $rhm_slider_count ) {
	2       => 'sm:invisible',
	3       => 'md:invisible',
	4       => 'lg:invisible',
	default => '',
};

/** Nút ‹ › của slider. */
$rhm_nav = static function () use ( $rhm_nav_hidden ): void {
	?>
	<div class="flex gap-2 <?php echo esc_attr( $rhm_nav_hidden ); ?>">
		<button class="carousel-prev c-nav" type="button" aria-label="<?php esc_attr_e( 'Chi nhánh trước', 'themeranghammatsgcantho' ); ?>" disabled>
			<?php echo rhm_get_icon( 'chevron', 'size-5 rotate-90' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
		</button>
		<button class="carousel-next c-nav" type="button" aria-label="<?php esc_attr_e( 'Chi nhánh sau', 'themeranghammatsgcantho' ); ?>">
			<?php echo rhm_get_icon( 'chevron', 'size-5 -rotate-90' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
		</button>
	</div>
	<?php
};

// Nút ‹ › ở hàng tiêu đề section chỉ khi không có thẻ lớn (slider là nội dung duy nhất).
$rhm_nav_in_head = ! $rhm_featured && $rhm_slider_count > 1;
?>
<section id="<?php echo esc_attr( $rhm_id ); ?>" class="home-branches<?php echo $rhm_slider ? ' overflow-hidden' : ''; ?> py-8 lg:py-10" aria-labelledby="<?php echo esc_attr( $rhm_title_id ); ?>">
	<div class="site-container<?php echo $rhm_nav_in_head ? ' js-carousel' : ''; ?>">
		<div class="c-head">
			<div class="max-w-2xl">
				<?php if ( '' !== $rhm_eyebrow ) : ?>
					<p class="c-eyebrow"><?php echo esc_html( $rhm_eyebrow ); ?></p>
				<?php endif; ?>
				<h2 id="<?php echo esc_attr( $rhm_title_id ); ?>" class="c-h2"><?php echo esc_html( $rhm_title ); ?></h2>
			</div>
			<?php
			if ( $rhm_nav_in_head ) {
				$rhm_nav();
			}
			?>
		</div>

		<?php if ( $rhm_featured ) : ?>
			<div class="grid gap-6<?php echo esc_attr( $rhm_featured_grid ); ?>">
				<?php foreach ( $rhm_featured as $rhm_index => $rhm_item ) : ?>
					<?php
					$rhm_hotline = trim( (string) ( $rhm_item['hotline'] ?? '' ) );
					$rhm_map_url = (string) ( $rhm_item['map_url'] ?? '' );
					$rhm_name_id = $rhm_id . '-name-' . ( $rhm_index + 1 );
					?>
					<article class="group c-card flex flex-col<?php echo $rhm_featured_single ? ' lg:grid lg:grid-cols-[minmax(0,7fr)_minmax(0,5fr)]' : ''; ?>" aria-labelledby="<?php echo esc_attr( $rhm_name_id ); ?>">
						<div class="relative aspect-[16/10] overflow-hidden bg-porcelain<?php echo $rhm_featured_single ? ' lg:aspect-auto lg:min-h-[26rem]' : ''; ?>">
							<?php
							echo wp_get_attachment_image(
								$rhm_item['image'],
								'full',
								false,
								array(
									'class'    => 'absolute inset-0 size-full object-cover transition-transform duration-500 group-hover:scale-[1.03]',
									'alt'      => $rhm_item['name'],
									'sizes'    => $rhm_featured_sizes,
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
							<span class="absolute inset-x-0 bottom-0 h-1/3 bg-linear-to-t from-deep/60 to-transparent" aria-hidden="true"></span>
							<span class="absolute top-4 left-4 inline-flex items-center gap-2 rounded-full bg-white/95 py-1.5 pr-4 pl-1.5 text-sm font-bold text-primary shadow-soft">
								<span class="flex size-7 items-center justify-center rounded-full bg-primary text-xs text-white tabular-nums" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $rhm_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
								<?php esc_html_e( 'Chi nhánh', 'themeranghammatsgcantho' ); ?>
							</span>
						</div>

						<div class="relative flex flex-1 flex-col p-6 wrap-anywhere lg:p-8">
							<span class="absolute inset-x-0 top-0 h-1 bg-linear-to-r from-brand to-secondary" aria-hidden="true"></span>
							<h3 id="<?php echo esc_attr( $rhm_name_id ); ?>" class="text-2xl leading-tight font-bold text-balance text-ink lg:text-[28px]"><?php echo esc_html( $rhm_item['name'] ); ?></h3>

							<dl class="mt-5 divide-y divide-thread border-y border-thread">
								<div class="flex gap-4 py-4">
									<dt class="c-icon-badge bg-mint ring-0">
										<?php echo rhm_get_icon( 'map-pin', 'size-5' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
										<span class="sr-only"><?php esc_html_e( 'Địa chỉ', 'themeranghammatsgcantho' ); ?></span>
									</dt>
									<dd class="min-w-0">
										<span class="block text-sm font-bold tracking-[0.06em] text-muted uppercase" aria-hidden="true"><?php esc_html_e( 'Địa chỉ', 'themeranghammatsgcantho' ); ?></span>
										<span class="mt-1 block text-lg leading-relaxed text-ink"><?php echo nl2br( esc_html( $rhm_item['address'] ), false ); ?></span>
									</dd>
								</div>
								<?php if ( '' !== $rhm_hotline ) : ?>
									<div class="flex gap-4 py-4">
										<dt class="c-icon-badge bg-mint ring-0">
											<?php echo rhm_get_icon( 'phone', 'size-5' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
											<span class="sr-only"><?php esc_html_e( 'Hotline', 'themeranghammatsgcantho' ); ?></span>
										</dt>
										<dd class="min-w-0">
											<span class="block text-sm font-bold tracking-[0.06em] text-muted uppercase" aria-hidden="true"><?php esc_html_e( 'Hotline', 'themeranghammatsgcantho' ); ?></span>
											<a class="mt-0.5 block text-2xl leading-tight font-bold tracking-tight text-primary tabular-nums hover:text-primary-dark lg:text-3xl" href="<?php echo esc_url( rhm_get_tel_href( $rhm_hotline ) ); ?>"><?php echo esc_html( $rhm_hotline ); ?></a>
										</dd>
									</div>
								<?php endif; ?>
							</dl>

							<?php if ( '' !== $rhm_hotline || '' !== $rhm_map_url ) : ?>
								<div class="mt-auto flex flex-wrap gap-3 pt-6">
									<?php if ( '' !== $rhm_hotline ) : ?>
										<a class="btn btn-primary c-btn flex-1 whitespace-nowrap" href="<?php echo esc_url( rhm_get_tel_href( $rhm_hotline ) ); ?>">
											<?php echo rhm_get_icon( 'phone', 'size-5 shrink-0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
											<?php esc_html_e( 'Gọi ngay', 'themeranghammatsgcantho' ); ?><span class="sr-only"> <?php /* translators: %s: tên chi nhánh. */ echo esc_html( sprintf( __( 'tới %s', 'themeranghammatsgcantho' ), $rhm_item['name'] ) ); ?></span>
										</a>
									<?php endif; ?>
									<?php if ( '' !== $rhm_map_url ) : ?>
										<a class="btn btn-outline c-btn flex-1 whitespace-nowrap" href="<?php echo esc_url( $rhm_map_url ); ?>"<?php echo rhm_external_link_attrs( $rhm_map_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>>
											<?php echo rhm_get_icon( 'map-pin', 'size-5 shrink-0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
											<?php esc_html_e( 'Chỉ đường', 'themeranghammatsgcantho' ); ?><span class="sr-only"> <?php /* translators: %s: tên chi nhánh. */ echo esc_html( sprintf( __( 'tới %s', 'themeranghammatsgcantho' ), $rhm_item['name'] ) ); ?></span>
										</a>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $rhm_slider ) : ?>
			<?php if ( $rhm_featured ) : ?>
				<div class="js-carousel mt-10 lg:mt-12">
				<div class="mb-6 flex flex-wrap items-center justify-between gap-4">
					<p class="text-xl font-bold text-ink"><?php esc_html_e( 'Các chi nhánh khác', 'themeranghammatsgcantho' ); ?></p>
					<?php
					if ( $rhm_slider_count > 1 ) {
						$rhm_nav();
					}
					?>
				</div>
			<?php endif; ?>

			<div class="swiper -mr-6">
				<div class="swiper-wrapper">
					<?php foreach ( $rhm_slider as $rhm_item ) : ?>
						<?php
						$rhm_hotline = trim( (string) ( $rhm_item['hotline'] ?? '' ) );
						$rhm_map_url = (string) ( $rhm_item['map_url'] ?? '' );
						?>
						<div class="swiper-slide flex h-auto w-[80%] pr-6 sm:w-1/2 md:w-1/3 lg:w-1/4">
							<article class="flex w-full flex-col rounded-xl border border-thread bg-white p-3 pb-5">
								<div class="c-media aspect-[4/3]">
									<?php
									echo wp_get_attachment_image(
										$rhm_item['image'],
										'full',
										false,
										array(
											'class'    => 'absolute inset-0 size-full object-cover',
											'alt'      => $rhm_item['name'],
											'sizes'    => $rhm_sizes,
											'loading'  => 'lazy',
											'decoding' => 'async',
										)
									);
									?>
								</div>
								<div class="flex flex-1 flex-col px-2 pt-4 wrap-anywhere">
									<h3 class="c-h3"><?php echo esc_html( $rhm_item['name'] ); ?></h3>
									<p class="mt-2 text-base leading-relaxed">
										<span class="sr-only"><?php esc_html_e( 'Địa chỉ:', 'themeranghammatsgcantho' ); ?> </span><?php echo nl2br( esc_html( $rhm_item['address'] ), false ); ?>
									</p>
									<?php if ( '' !== $rhm_hotline ) : ?>
										<p class="mt-2">
											<a class="text-lg font-bold text-ink tabular-nums hover:text-primary" href="<?php echo esc_url( rhm_get_tel_href( $rhm_hotline ) ); ?>">
												<span class="sr-only"><?php esc_html_e( 'Gọi', 'themeranghammatsgcantho' ); ?> </span><?php echo esc_html( $rhm_hotline ); ?>
											</a>
										</p>
									<?php endif; ?>
									<?php if ( '' !== $rhm_map_url ) : ?>
										<p class="mt-auto pt-4">
											<a class="c-link" href="<?php echo esc_url( $rhm_map_url ); ?>"<?php echo rhm_external_link_attrs( $rhm_map_url ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>>
												<?php esc_html_e( 'Chỉ đường', 'themeranghammatsgcantho' ); ?><span class="sr-only"> <?php /* translators: %s: tên chi nhánh. */ echo esc_html( sprintf( __( 'tới %s', 'themeranghammatsgcantho' ), $rhm_item['name'] ) ); ?></span>
											</a>
										</p>
									<?php endif; ?>
								</div>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $rhm_featured ) : ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
