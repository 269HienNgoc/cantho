<?php
/**
 * Thanh lọc chuyên mục con ở đầu trang danh mục. Dữ liệu: rhm_get_category_filter() (inc/category.php). CSS: archive.css
 *
 * Mỗi nút là link tới trang chuyên mục (không lọc bằng JS) → Google đọc được, không tải thêm gì.
 * - < 768px:   mọi mục trên 1 hàng (nút cao 44px), vuốt ngang trong khối (trang không bị kéo ngang), không có nút "…".
 * - ≥ 768px:   "Tất cả" + 2 mục con (1024px: 3, 1280px: 4) giãn đều theo độ dài chữ; mục còn lại nằm trong nút "…".
 * Nút "…" là <details> (mở/đóng không cần JS); src/js/dropdown.js chỉ thêm đóng khi bấm ra ngoài / Esc.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_filter = rhm_get_category_filter();

if ( ! $rhm_filter ) {
	return;
}

$rhm_count = count( $rhm_filter['items'] );

// Nút "…" chỉ hiện ở breakpoint còn mục con bị ẩn.
$rhm_more = match ( true ) {
	$rhm_count <= 2  => false,
	3 === $rhm_count => 'lg:hidden',
	4 === $rhm_count => 'xl:hidden',
	default          => '',
};

if ( false !== $rhm_more && file_exists( RHM_THEME_DIR . '/dist/js/dropdown.js' ) ) {
	wp_enqueue_script_module( 'rhm-dropdown', RHM_THEME_URI . '/dist/js/dropdown.js', array(), rhm_asset_version( 'dist/js/dropdown.js' ) );
}

$rhm_tabs = array_merge( array( $rhm_filter['all'] ), $rhm_filter['items'] );
?>
<nav aria-label="<?php esc_attr_e( 'Lọc theo chuyên mục', 'themeranghammatsgcantho' ); ?>">
	<ul class="-mx-4 flex gap-2 overflow-x-auto px-4 py-1 [scrollbar-width:none] md:mx-0 md:flex-wrap md:overflow-visible md:px-0 [&::-webkit-scrollbar]:hidden">
		<?php foreach ( $rhm_tabs as $rhm_index => $rhm_tab ) : ?>
			<?php
			// $rhm_index: 0 = "Tất cả", 1… = mục con. Mục con thứ 3, 4, 5+ ẩn dần vào nút "…" theo breakpoint.
			$rhm_hide = match ( true ) {
				$rhm_index <= 2  => '',
				3 === $rhm_index => 'md:max-lg:hidden',
				4 === $rhm_index => 'md:max-xl:hidden',
				default          => 'md:hidden',
			};
			?>
			<li class="shrink-0 md:min-w-0 <?php echo esc_attr( $rhm_hide ); ?>">
				<a class="flex h-11 min-w-0 items-center justify-center rounded-full border px-5 text-base font-medium whitespace-nowrap transition-colors duration-150 md:h-12 <?php echo $rhm_tab['active'] ? 'border-ink bg-ink text-white' : 'border-thread bg-white text-ink hover:border-primary hover:text-primary'; ?>" href="<?php echo esc_url( $rhm_tab['url'] ); ?>"<?php echo $rhm_tab['active'] ? ' aria-current="page"' : ''; ?>>
					<span class="truncate"><?php echo esc_html( $rhm_tab['name'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>

		<?php if ( false !== $rhm_more ) : ?>
			<li class="relative shrink-0 max-md:hidden <?php echo esc_attr( $rhm_more ); ?>">
				<details class="js-dropdown group">
					<summary class="flex size-12 cursor-pointer list-none items-center justify-center rounded-full border border-thread bg-white text-ink transition-colors duration-150 group-open:border-ink group-open:bg-ink group-open:text-white hover:border-primary hover:text-primary [&::-webkit-details-marker]:hidden">
						<?php echo rhm_get_icon( 'more', 'size-6' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
						<span class="sr-only"><?php esc_html_e( 'Chuyên mục khác', 'themeranghammatsgcantho' ); ?></span>
					</summary>
					<ul class="absolute top-full left-0 z-20 mt-2 w-64 rounded-lg border border-thread bg-white py-2 shadow-card">
						<?php foreach ( array_slice( $rhm_filter['items'], 2, null, true ) as $rhm_index => $rhm_item ) : ?>
							<?php
							// Chỉ hiện trong "…" ở breakpoint mà mục đó bị ẩn khỏi hàng nút ($rhm_index: 0 = mục con đầu tiên).
							$rhm_show = match ( $rhm_index ) {
								2       => 'lg:hidden',
								3       => 'xl:hidden',
								default => '',
							};
							?>
							<li class="<?php echo esc_attr( $rhm_show ); ?>">
								<a class="flex min-h-11 items-center px-4 py-2.5 text-ink hover:bg-porcelain hover:text-primary" href="<?php echo esc_url( $rhm_item['url'] ); ?>">
									<?php echo esc_html( $rhm_item['name'] ); ?>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				</details>
			</li>
		<?php endif; ?>
	</ul>
</nav>
