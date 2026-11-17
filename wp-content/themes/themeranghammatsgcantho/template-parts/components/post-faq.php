<?php
/**
 * Câu hỏi thường gặp cuối bài (ACF rhm_post_faq, inc/post-extras.php). <details> mở/đóng không cần JS.
 * id rhm-faq-N khớp @id Question trong schema (inc/schema.php). CSS: utility của single-post.css. Danh sách phẳng, ngăn bằng đường Chỉ.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_faq = rhm_post_section_visible( 'faq', get_the_ID() ) ? rhm_get_post_faq( get_the_ID() ) : array();
if ( ! $rhm_faq ) {
	return;
}
?>
<section class="mt-12 lg:mt-16" aria-labelledby="rhm-faq-title">
	<h2 id="rhm-faq-title" class="text-2xl leading-tight font-bold text-balance text-ink lg:text-3xl"><?php esc_html_e( 'Câu hỏi thường gặp', 'themeranghammatsgcantho' ); ?></h2>
	<div class="mt-5 divide-y divide-thread border-y border-thread">
		<?php foreach ( $rhm_faq as $rhm_index => $rhm_item ) : ?>
			<details id="rhm-faq-<?php echo esc_attr( $rhm_index + 1 ); ?>" class="group">
				<summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-4 py-4 text-lg leading-snug font-bold text-ink transition-colors duration-150 hover:text-primary [&::-webkit-details-marker]:hidden">
					<span class="min-w-0 [overflow-wrap:anywhere]"><?php echo esc_html( $rhm_item['question'] ); ?></span>
					<?php echo rhm_get_icon( 'chevron', 'size-5 shrink-0 text-primary transition-transform duration-150 group-open:rotate-180' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
				</summary>
				<div class="entry-content pb-6"><?php echo rhm_external_links( wp_kses( $rhm_item['answer'], RHM_FAQ_ALLOWED_HTML ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- kses trước, chỉ thêm target/rel cho link external. ?></div>
			</details>
		<?php endforeach; ?>
	</div>
</section>
