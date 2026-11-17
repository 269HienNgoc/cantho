<?php
/**
 * Dải đặt lịch — khối bo góc nền gradient xanh đậm → xanh thương hiệu (nút): số hotline cỡ lớn bấm gọi ngay + nút Đặt lịch hẹn.
 * Dùng ở: trang chủ (cuối trang), bài viết (sau nội dung), danh sách bác sĩ. CSS: _content.css + utility của bundle trang
 * (mỗi bundle dùng component này phải @source file này).
 * (Landing page dùng bản cũ template-parts/sections/booking-cta.php — không đổi.)
 *
 * $args (tùy chọn): id, title, text (trống → rhm_booking_cta_default()).
 * Chưa nhập hotline → chỉ còn tiêu đề + nút.
 *
 * Màu: chữ trắng trên river (#006b38) → primary (#007a3b): ≥ 5,4:1; số hotline footer-accent (#b8da84) cỡ ≥ 40px: ≥ 3,4:1 (AA chữ lớn).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_hotline = rhm_get_option( 'rhm_hotline' );
$rhm_note    = rhm_get_option( 'rhm_hotline_note' );
$rhm_id      = (string) ( $args['id'] ?? '' );
$rhm_title   = (string) ( $args['title'] ?? '' ) ?: rhm_booking_cta_default( 'title' );
$rhm_text    = (string) ( $args['text'] ?? '' ) ?: rhm_booking_cta_default( 'text' );
$rhm_head_id = ( '' !== $rhm_id ? $rhm_id : 'booking-band' ) . '-title';
?>
<section<?php echo '' !== $rhm_id ? ' id="' . esc_attr( $rhm_id ) . '"' : ''; ?> class="py-8 lg:py-10" aria-labelledby="<?php echo esc_attr( $rhm_head_id ); ?>">
	<div class="site-container">
	<div class="grid gap-8 rounded-2xl bg-linear-to-br from-river to-primary px-6 py-10 text-white sm:px-10 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end lg:gap-16 lg:px-8 lg:py-8">
		<div class="max-w-2xl">
			<h2 id="<?php echo esc_attr( $rhm_head_id ); ?>" class="text-[27px] leading-tight font-bold text-balance lg:text-4xl"><?php echo esc_html( $rhm_title ); ?></h2>
			<p class="mt-3 text-lg leading-relaxed text-white/85"><?php echo esc_html( $rhm_text ); ?></p>
		</div>

		<div class="flex flex-col gap-5 sm:flex-row sm:flex-wrap sm:items-end lg:flex-col lg:items-start">
			<?php if ( '' !== $rhm_hotline ) : ?>
				<p class="leading-none">
					<span class="block text-base text-white/85"><?php esc_html_e( 'Gọi ngay', 'themeranghammatsgcantho' ); ?></span>
					<a class="mt-2 block text-[40px] font-bold tracking-tight text-footer-accent tabular-nums hover:text-white sm:text-5xl lg:text-6xl" href="<?php echo esc_url( rhm_get_tel_href( $rhm_hotline ) ); ?>"><?php echo esc_html( $rhm_hotline ); ?></a>
					<?php if ( '' !== $rhm_note ) : ?>
						<span class="mt-2 block text-base leading-snug text-white/85"><?php echo esc_html( $rhm_note ); ?></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php rhm_booking_button( 'c-btn self-start border-white bg-white text-river hover:border-footer-accent hover:bg-footer-accent hover:text-ink', 'size-5 shrink-0' ); ?>
		</div>
	</div>
	</div>
</section>
