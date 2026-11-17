<?php
/**
 * Trang chủ: hero — chữ trái (dòng nhỏ, H1 2 phần, các ý có icon, nút Đặt lịch + nút thứ 2), ảnh lớn phải.
 * Dữ liệu: inc/hero.php (Tùy biến → "Hero (Trang chủ)"). Không JS.
 *
 * Bố cục:
 * - < 1024px: chữ → ảnh khung 4:3 bo góc (DOM chữ trước → H1 vẽ ngay, ảnh ngay dưới).
 * - ≥ 1024px: chữ 55% | ảnh absolute phủ 45% bên phải hero, tràn mép phải màn hình, mép trái cong (border-radius elip); chiều cao hero do cột
 *   chữ + min-h quyết định → ảnh không góp chiều cao, CLS 0.
 * Ảnh = LCP (rhm_home_hero_picture(): fetchpriority=high, không lazy). Không có ảnh → chữ 1 cột.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_hero = rhm_get_hero();
if ( '' === $rhm_hero['title'] ) {
	return;
}
$rhm_has_image = (bool) $rhm_hero['image'];
?>
<section class="relative overflow-hidden bg-linear-to-br from-canvas via-white to-soft" aria-labelledby="home-hero-title">
	<div class="site-container grid items-center gap-8 pt-10 pb-12 lg:min-h-[560px] lg:py-16 xl:min-h-[620px] <?php echo esc_attr( $rhm_has_image ? 'lg:grid-cols-[minmax(0,11fr)_minmax(0,9fr)]' : '' ); ?>">
		<div class="relative z-10 max-w-[46rem]">
			<?php if ( '' !== $rhm_hero['eyebrow'] ) : ?>
				<p class="c-eyebrow"><?php echo esc_html( $rhm_hero['eyebrow'] ); ?></p>
			<?php endif; ?>
			<h1 id="home-hero-title" class="text-[34px] leading-[1.15] font-bold text-balance text-ink sm:text-[42px] xl:text-5xl">
				<?php echo esc_html( $rhm_hero['title'] ); ?>
				<?php if ( '' !== $rhm_hero['highlight'] ) : ?>
					<span class="block text-primary"><?php echo esc_html( $rhm_hero['highlight'] ); ?></span>
				<?php endif; ?>
			</h1>

			<?php if ( $rhm_hero['points'] ) : ?>
				<ul class="mt-7 space-y-3">
					<?php foreach ( $rhm_hero['points'] as $rhm_point ) : ?>
						<li class="flex items-center gap-3 text-[17px] leading-snug font-medium text-ink">
							<span class="c-icon-badge size-10"><?php echo rhm_icon_svg( 'landing', $rhm_point['icon'], 'size-5' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme. ?></span>
							<?php echo esc_html( $rhm_point['text'] ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<div class="mt-9 flex flex-wrap gap-3">
				<?php rhm_booking_button( 'c-btn', 'size-5 shrink-0', false, $rhm_hero['button_text'] ); ?>
				<?php if ( '' !== $rhm_hero['button2_text'] && '' !== $rhm_hero['button2_link'] ) : ?>
					<a class="btn btn-outline c-btn bg-white" href="<?php echo esc_url( $rhm_hero['button2_link'] ); ?>"<?php echo rhm_external_link_attrs( $rhm_hero['button2_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>>
						<span><?php echo esc_html( $rhm_hero['button2_text'] ); ?></span>
						<?php echo rhm_get_icon( 'arrow-right', 'size-5 shrink-0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $rhm_has_image ) : ?>
			<div class="relative aspect-[4/3] overflow-hidden rounded-2xl bg-soft lg:absolute lg:inset-y-0 lg:right-0 lg:aspect-auto lg:w-[45%] lg:rounded-none lg:rounded-tl-[22%_100%]">
				<?php echo rhm_home_hero_picture( $rhm_hero['image'], $rhm_hero['image_mobile'], rhm_home_hero_alt( $rhm_hero['image'], $rhm_hero['title'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- rhm_html_attrs() escape. ?>
			</div>
		<?php endif; ?>
	</div>
</section>
