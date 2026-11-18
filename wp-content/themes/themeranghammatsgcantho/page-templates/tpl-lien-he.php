<?php
/**
 * Template Name: Liên hệ – Đặt lịch
 * Template Post Type: page
 *
 * Bố cục đơn giản (thiết kế lại 2026-11-18):
 * 1. Banner: khung trong .site-container, canh giữa: breadcrumb → viên nhãn (rhm_contact_eyebrow) → H1 (tiêu đề trang) → vạch gradient
 *    → 1 dòng mô tả (rhm_contact_intro). Trang trí mềm (2026-11-18): nền gradient + chấm, vòng tròn, răng nét mảnh, mép dưới dạng sóng.
 * 2. 2 thẻ bằng nhau (từ 1024px 2 cột, dưới đó xếp dọc):
 *    - "Thông tin liên hệ": Địa chỉ, Hotline (mỗi số 1 link tel:), Email (mailto:), Giờ làm việc — dòng trống bị bỏ, không còn dòng nào → không in thẻ.
 *    - "Đăng ký tư vấn" (#dat-lich): form Fluent Forms (rhm_booking_form_id) trong khối .rhm-booking.
 * 3. Nội dung editor của trang (nếu có).
 * Không có popup đặt lịch (inc/booking-popup.php), mọi nút đặt lịch trên trang trỏ #dat-lich. Không có bản đồ Google.
 * Dữ liệu: inc/contact.php (Tùy biến → "Trang Liên hệ") + "Thông tin phòng khám". CSS: page-contact.css.
 *
 * Hiệu năng: không ảnh, LCP là H1. Form: CSS/JS của Fluent Forms "chạm mới tải" (inc/plugin/fluentform.php).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_form_id = absint( get_theme_mod( 'rhm_booking_form_id', 0 ) );
$rhm_address = trim( rhm_get_option( 'rhm_address' ) );
$rhm_phones  = rhm_split_phones( rhm_get_option( 'rhm_hotline' ) );
$rhm_email   = sanitize_email( rhm_get_option( 'rhm_email' ) );
$rhm_hours   = trim( rhm_get_option( 'rhm_working_hours' ) );

// Dòng thông tin liên hệ: [ icon, nhãn, HTML giá trị (đã escape) ]. Dòng thiếu dữ liệu thì bỏ.
$rhm_info = array();
if ( '' !== $rhm_address ) {
	$rhm_info[] = array( 'map-pin', __( 'Địa chỉ', 'themeranghammatsgcantho' ), nl2br( esc_html( $rhm_address ), false ) );
}
if ( $rhm_phones ) {
	$rhm_info[] = array(
		'phone',
		__( 'Hotline', 'themeranghammatsgcantho' ),
		implode(
			' - ',
			array_map(
				static fn( string $phone ): string => sprintf( '<a class="whitespace-nowrap hover:text-primary" href="%s">%s</a>', esc_url( rhm_get_tel_href( $phone ) ), esc_html( $phone ) ),
				$rhm_phones
			)
		),
	);
}
if ( '' !== $rhm_email ) {
	$rhm_info[] = array( 'mail', __( 'Email', 'themeranghammatsgcantho' ), sprintf( '<a class="wrap-anywhere hover:text-primary" href="%s">%s</a>', esc_url( 'mailto:' . $rhm_email ), esc_html( $rhm_email ) ) );
}
if ( '' !== $rhm_hours ) {
	$rhm_info[] = array( 'clock', __( 'Giờ làm việc', 'themeranghammatsgcantho' ), nl2br( esc_html( $rhm_hours ), false ) );
}

$rhm_intro = rhm_contact_text( 'intro' );

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<div class="site-container pt-6 lg:pt-10">
		<?php
		// Banner mềm: nền gradient nhiều lớp + chấm (.contact-hero, page-contact.css), vòng tròn / răng nét mảnh trôi ra mép,
		// mép dưới dạng sóng (SVG tô màu nền trang). Mọi phần trang trí aria-hidden, không ảnh, không JS, không hoạt ảnh lúc tải (H1 = LCP).
		?>
		<section class="contact-hero relative isolate overflow-hidden rounded-xl px-5 pt-7 pb-16 text-center sm:px-8 lg:pt-10 lg:pb-24" aria-labelledby="contact-title">
			<span class="pointer-events-none absolute -top-16 -right-14 -z-10 size-48 rounded-full border border-brand/15 lg:-top-24 lg:-right-10 lg:size-80" aria-hidden="true"></span>
			<span class="pointer-events-none absolute -top-6 -right-4 -z-10 size-28 rounded-full bg-white/60 lg:-top-8 lg:right-10 lg:size-44" aria-hidden="true"></span>
			<span class="pointer-events-none absolute -bottom-20 -left-16 -z-10 size-56 rounded-full bg-secondary/15 lg:-bottom-28 lg:-left-10 lg:size-96" aria-hidden="true"></span>
			<span class="contact-hero__dots pointer-events-none absolute top-8 left-6 -z-10 hidden h-20 w-28 sm:block lg:top-12 lg:left-[7%] lg:h-24 lg:w-36" aria-hidden="true"></span>
			<span class="pointer-events-none absolute right-[7%] bottom-16 -z-10 hidden rotate-12 text-brand/15 xl:block [&_svg]:size-36" aria-hidden="true"><?php echo rhm_icon_svg( 'landing', 'rang' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme, đã lọc. ?></span>
			<svg class="pointer-events-none absolute inset-x-0 bottom-0 -z-10 h-8 w-full fill-canvas lg:h-14" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true" focusable="false"><path d="M0 46c160 26 320 34 480 20S800 18 960 22s320 38 480 30v28H0z"/></svg>

			<div class="flex justify-center [&_ol]:flex-wrap [&_ol]:justify-center">
				<?php rhm_breadcrumb(); ?>
			</div>
			<?php if ( '' !== rhm_contact_text( 'eyebrow' ) ) : ?>
				<p class="mt-5 inline-flex items-center gap-2 rounded-full bg-white/80 px-4 py-1.5 text-sm font-semibold text-primary shadow-[0_4px_14px_rgb(0_122_59/0.08)] ring-1 ring-brand/10 [&_svg]:size-4 lg:mt-6">
					<?php echo rhm_icon_svg( 'landing', 'tim' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG trong theme, đã lọc. ?>
					<?php echo esc_html( rhm_contact_text( 'eyebrow' ) ); ?>
				</p>
			<?php endif; ?>
			<h1 id="contact-title" class="mt-3 text-[1.75rem] leading-tight font-bold text-dark sm:text-4xl lg:mt-4 lg:text-[2.75rem]"><?php the_title(); ?></h1>
			<span class="mx-auto mt-4 block h-1 w-16 rounded-full bg-linear-to-r from-brand to-secondary" aria-hidden="true"></span>
			<?php if ( '' !== $rhm_intro ) : ?>
				<p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-muted lg:text-lg"><?php echo esc_html( $rhm_intro ); ?></p>
			<?php endif; ?>
		</section>
	</div>

	<div class="site-container grid gap-5 py-6 lg:gap-6 lg:py-10<?php echo $rhm_info ? ' lg:grid-cols-2' : ''; ?>">
		<?php if ( $rhm_info ) : ?>
			<section class="rounded-xl border border-line bg-canvas-alt p-5 sm:p-6 lg:p-8" aria-labelledby="contact-info-title">
				<h2 id="contact-info-title" class="text-xl leading-snug font-bold text-dark lg:text-2xl"><?php echo esc_html( rhm_contact_text( 'info_title' ) ); ?></h2>
				<ul class="mt-5 space-y-5 lg:mt-6">
					<?php foreach ( $rhm_info as [ $rhm_icon, $rhm_label, $rhm_value ] ) : ?>
						<li class="flex gap-3">
							<?php echo rhm_get_icon( $rhm_icon, 'mt-0.5 size-5 shrink-0 text-primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
							<div class="min-w-0">
								<p class="font-semibold text-dark"><?php echo esc_html( $rhm_label ); ?></p>
								<p class="mt-0.5 leading-relaxed text-muted"><?php echo $rhm_value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped when building $rhm_info. ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<section id="dat-lich" class="contact-form rhm-booking rounded-xl border border-line bg-canvas-alt p-5 sm:p-6 lg:p-8" aria-labelledby="contact-form-title">
			<h2 id="contact-form-title" class="text-xl leading-snug font-bold text-dark lg:text-2xl"><?php echo esc_html( rhm_contact_text( 'form_title' ) ); ?></h2>
			<div class="mt-5 lg:mt-6">
				<?php
				if ( $rhm_form_id && shortcode_exists( 'fluentform' ) ) {
					echo rhm_render_fluent_form( $rhm_form_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fluent Forms output.
				} elseif ( current_user_can( 'edit_theme_options' ) ) {
					echo '<p class="rounded-lg border border-dashed border-primary p-4 text-sm">' . esc_html__( 'Chưa cấu hình form: tạo form trong Fluent Forms rồi nhập ID tại Giao diện → Tùy biến → Thông tin phòng khám.', 'themeranghammatsgcantho' ) . '</p>';
				}
				?>
			</div>
		</section>
	</div>

	<?php if ( '' !== trim( get_the_content() ) ) : ?>
		<div class="site-container pb-12 lg:pb-16">
			<div class="entry-content max-w-4xl"><?php the_content(); ?></div>
		</div>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
