<?php
/**
 * Trang chủ: "Vì sao chọn chúng tôi". Dữ liệu: inc/home-why.php (Tùy biến → "Vì sao chọn (Trang chủ)"). Không JS.
 *
 * Khối bo góc nền Sứ (xanh nhạt): trái = H2 + lưới lý do (1 cột < 567px, 2 cột từ 567px), phải = ảnh liền khối.
 * ≥ 1024px: 2 cột 6 : 5, ảnh object-cover phủ đúng chiều cao khối (không góp chiều cao → khối cao theo chữ, tối thiểu 380px).
 * < 1024px: ảnh 16:10 dưới chữ. Ảnh lazy.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_why = rhm_get_home_why();
if ( '' === $rhm_why['title'] || ! $rhm_why['items'] ) {
	return;
}

// Ảnh = cột 5/11 của khung (≥ 1280px: 1248 × 5/11 = 567px; 1024–1279px: (100vw − 32) × 5/11 ≈ 45.45vw − 15px);
// < 1024px rộng hết khung = 100vw − 32px.
$rhm_sizes = '(min-width: 1280px) 567px, (min-width: 1024px) calc(45.45vw - 15px), calc(100vw - 32px)';
?>
<section class="py-8 lg:py-10" aria-labelledby="home-why-title">
	<div class="site-container">
		<div class="grid overflow-hidden rounded-2xl bg-soft <?php echo esc_attr( $rhm_why['image'] ? 'lg:grid-cols-[minmax(0,6fr)_minmax(0,5fr)]' : '' ); ?>">
			<div class="px-5 py-10 sm:px-8 lg:px-12 lg:py-14">
				<h2 id="home-why-title" class="c-h2"><?php echo esc_html( $rhm_why['title'] ); ?></h2>
				<ul class="mt-8 grid gap-x-8 gap-y-7 sm:grid-cols-2">
					<?php foreach ( $rhm_why['items'] as $rhm_item ) : ?>
						<li class="flex gap-4">
							<span class="c-icon-badge size-12"><?php echo rhm_icon_svg( 'landing', $rhm_item['icon'], 'size-6' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme. ?></span>
							<div class="min-w-0">
								<h3 class="text-lg leading-snug font-bold text-ink"><?php echo esc_html( $rhm_item['title'] ); ?></h3>
								<?php if ( '' !== $rhm_item['text'] ) : ?>
									<p class="mt-1.5 text-[15px] leading-relaxed"><?php echo nl2br( esc_html( $rhm_item['text'] ), false ); ?></p>
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<?php if ( $rhm_why['image'] ) : ?>
				<div class="relative aspect-[16/10] bg-mint-dark lg:aspect-auto lg:min-h-[380px]">
					<?php
					echo wp_get_attachment_image(
						$rhm_why['image'],
						'full',
						false,
						array(
							'class'    => 'absolute inset-0 size-full object-cover',
							'sizes'    => $rhm_sizes,
							'loading'  => 'lazy',
							'decoding' => 'async',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
