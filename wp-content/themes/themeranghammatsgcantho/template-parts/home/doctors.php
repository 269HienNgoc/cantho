<?php
/**
 * Trang chủ: đội ngũ bác sĩ — cột giới thiệu (dòng nhỏ, H2, đoạn văn, nút "xem tất cả") + RHM_HOME_DOCTORS_SHOWN thẻ bác sĩ
 * (ảnh chân dung 4:5, tên H3, chức danh). Dữ liệu: inc/home-doctors.php (Tùy biến → "Đội ngũ bác sĩ (Trang chủ)").
 * (Landing page dùng slider riêng template-parts/sections/doctors.php — không sửa ở đây.)
 *
 * Bố cục: ≥ 1024px 2 cột 4 : 8, thẻ 3 cột. 640–1023px: giới thiệu trên, thẻ 3 cột. < 640px: hàng thẻ vuốt ngang
 * (scroll-snap, thẻ 70% hé thẻ kế, không JS). Ảnh lazy.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_prefix  = 'rhm_home_doctors';
$rhm_doctors = array_slice( rhm_get_home_doctors( rhm_home_doctor_term( $rhm_prefix ) ), 0, RHM_HOME_DOCTORS_SHOWN );
if ( ! $rhm_doctors ) {
	return;
}

$rhm_eyebrow  = rhm_home_doctors_text( 'eyebrow', $rhm_prefix );
$rhm_title    = rhm_home_doctors_text( 'title', $rhm_prefix );
$rhm_text     = rhm_home_doctors_text( 'text', $rhm_prefix );
$rhm_all_text = rhm_home_doctors_text( 'all_text', $rhm_prefix );
$rhm_all_link = rhm_home_doctors_text( 'all_link', $rhm_prefix );
$rhm_count    = count( $rhm_doctors );

// 1 thẻ = 1/3 cột phải. ≥ 1280px: cột 8/12 của (1248 − gap 64) = 736 → (736 − 2 × 20) / 3 = 249px;
// 1024–1279px: ((100vw − 96) × 8/12 − 40) / 3 = 22.22vw − 35px; 640–1023px: (100vw − 32 − 32) / 3 = 33.33vw − 21px;
// < 640px: 70% của (100vw − 32) = 70vw − 22px.
$rhm_sizes = '(min-width: 1280px) 249px, (min-width: 1024px) calc(22.22vw - 35px), (min-width: 640px) calc(33.33vw - 21px), calc(70vw - 22px)';
?>
<section id="doi-ngu-bac-si" class="py-8 lg:py-10" aria-labelledby="doctors-title">
	<div class="site-container grid gap-8 lg:grid-cols-[minmax(0,4fr)_minmax(0,8fr)] lg:items-center lg:gap-16">
		<div class="max-w-xl">
			<?php if ( $rhm_eyebrow ) : ?>
				<p class="c-eyebrow"><?php echo esc_html( $rhm_eyebrow ); ?></p>
			<?php endif; ?>
			<h2 id="doctors-title" class="c-h2"><?php echo esc_html( $rhm_title ); ?></h2>
			<?php if ( $rhm_text ) : ?>
				<p class="mt-4 text-[17px] leading-relaxed"><?php echo nl2br( esc_html( $rhm_text ), false ); ?></p>
			<?php endif; ?>
			<?php if ( $rhm_all_text && $rhm_all_link ) : ?>
				<a class="btn btn-primary c-btn mt-7" href="<?php echo esc_url( $rhm_all_link ); ?>"<?php echo rhm_external_link_attrs( $rhm_all_link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>>
					<span><?php echo esc_html( $rhm_all_text ); ?></span>
					<?php echo rhm_get_icon( 'arrow-right', 'size-5 shrink-0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</a>
			<?php endif; ?>
		</div>

		<ul class="-mx-4 flex snap-x snap-mandatory scroll-px-4 gap-4 overflow-x-auto px-4 pb-2 [scrollbar-width:none] sm:mx-0 sm:grid sm:grid-cols-3 sm:overflow-visible sm:px-0 sm:pb-0 lg:gap-5"<?php echo $rhm_count > 1 ? ' tabindex="0"' : ''; ?> aria-label="<?php esc_attr_e( 'Bác sĩ', 'themeranghammatsgcantho' ); ?>">
			<?php foreach ( $rhm_doctors as $rhm_doctor ) : ?>
				<li class="<?php echo esc_attr( $rhm_count > 1 ? 'w-[70%] shrink-0 snap-start sm:w-auto' : 'w-full' ); ?>">
					<a class="c-card c-card-link group flex h-full flex-col" href="<?php echo esc_url( $rhm_doctor['url'] ); ?>">
						<div class="relative aspect-[4/5] overflow-hidden bg-soft">
							<?php rhm_doctor_image( $rhm_doctor['image'], $rhm_sizes, 'absolute inset-0 size-full object-cover object-top transition-transform duration-300 group-hover:scale-[1.03]', $rhm_doctor['name'], false, 4 / 5 ); ?>
						</div>
						<div class="flex flex-1 flex-col px-3 py-4 text-center">
							<h3 class="text-[17px] leading-snug font-bold text-ink transition-colors duration-150 group-hover:text-primary lg:text-lg"><?php echo esc_html( $rhm_doctor['name'] ); ?></h3>
							<?php if ( $rhm_doctor['position'] ) : ?>
								<span class="mt-1 text-[15px] leading-snug"><?php echo esc_html( $rhm_doctor['position'] ); ?></span>
							<?php endif; ?>
						</div>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
