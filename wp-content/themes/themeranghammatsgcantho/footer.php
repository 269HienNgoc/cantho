<?php
/**
 * Footer. Dữ liệu: inc/footer.php (Customizer → "Chân trang (Footer)" + "Thông tin phòng khám"). Style: utility trong file này + src/css/pages/footer.css.
 *
 * Bố cục (2026-11-17):
 * 1. Thương hiệu: logo + tiêu đề + khẩu hiệu + địa chỉ, MST ("Thông tin phòng khám"; icon cột trái, chữ cột phải).
 * 2. Cột linh động (rhm_footer_middle()): menu Điều khoản chính sách HOẶC Hệ thống chi nhánh.
 * 3. Kết nối với chúng tôi: hotline, email, giờ làm việc + mạng xã hội.
 * - < 640px: 1 cột, thứ tự hiển thị 1 → 3 → 2 (cột giữa max-sm:order-last: "Kết nối với chúng tôi" lên trên Điều khoản chính sách). 640px: cột 1 rộng hết hàng, cột 2 | cột 3. 1024px: 3 cột có vạch ngăn;
 *   tỉ lệ cột theo nội dung cột giữa (chi nhánh cần rộng hơn menu).
 * - Vùng riêng dưới lưới 3 cột (viền trên): (tiêu đề khối H2) + tối đa 2 chi nhánh được "Ghim": viên tên (H3), địa chỉ
 *   (+ đ/c cũ, Xem bản đồ), các số điện thoại (mỗi số 1 link tel:), email, giờ làm việc. Từ 640px 2 chi nhánh cạnh nhau.
 * - Hàng cuối: bản quyền + (khi cột giữa là chi nhánh) menu chính sách dạng dòng + huy hiệu.
 * Cột / khối nào không có dữ liệu thì không in.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_logo         = rhm_get_footer_logo_id();
$rhm_title        = rhm_footer_text( 'title' );
$rhm_slogan       = rhm_footer_text( 'slogan' );
$rhm_clinic       = rhm_get_footer_clinic_info();
$rhm_pinned       = rhm_get_footer_pinned_branches();
$rhm_pinned_title = rhm_footer_text( 'pinned_title' );
$rhm_map_text     = rhm_footer_text( 'map_text' );
$rhm_contacts     = rhm_get_footer_contacts();
$rhm_branches     = rhm_get_footer_branches();
$rhm_branch_text  = rhm_footer_text( 'branch_button_text' );
$rhm_branch_link  = rhm_get_option( 'rhm_footer_branch_button_link' ) ?: rhm_get_booking_url();
$rhm_socials      = rhm_get_footer_socials();
$rhm_badges       = rhm_get_footer_badges();
$rhm_policy_menu  = has_nav_menu( 'footer' );
$rhm_middle       = rhm_footer_middle( $rhm_policy_menu, (bool) $rhm_branches );
$rhm_policy_args  = array(
	'theme_location'       => 'footer',
	'container'            => 'nav',
	'container_aria_label' => rhm_footer_text( 'policy_title' ),
	'fallback_cb'          => false,
	'depth'                => 1,
);

// Tỉ lệ cột từ 1024px (viết nguyên văn để Tailwind quét được).
$rhm_grid = 'branches' === $rhm_middle
	? 'lg:grid-cols-[minmax(0,1.25fr)_minmax(0,1.35fr)_minmax(0,1fr)]'
	: 'lg:grid-cols-[minmax(0,1.4fr)_minmax(0,1fr)_minmax(0,1fr)]';
?>
</main>

<footer class="site-footer">
	<div class="site-container grid gap-8 pb-4 sm:grid-cols-2 sm:gap-x-8 lg:gap-x-0 lg:pb-5 <?php echo esc_attr( $rhm_grid ); ?>">
		<div class="sm:col-span-2 lg:col-span-1 lg:pr-8 xl:pr-12">
			<?php if ( $rhm_logo || $rhm_title || $rhm_slogan ) : ?>
				<div class="flex items-center gap-4">
					<?php if ( $rhm_logo ) : ?>
						<a class="block shrink-0" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php
							// Độ rộng phải khớp class w-20 / xl:w-24 của ảnh.
							echo rhm_get_picture( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- attributes escaped in rhm_html_attrs().
								$rhm_logo,
								array(
									'(min-width: 1280px)' => 96,
									''                    => 80,
								),
								array(
									'class'    => 'block h-auto w-20 xl:w-24',
									'alt'      => get_bloginfo( 'name' ),
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
						</a>
					<?php endif; ?>
					<?php if ( $rhm_title || $rhm_slogan ) : ?>
						<div class="min-w-0">
							<?php if ( $rhm_title ) : ?>
								<?php // Tiêu đề dài: mobile (< 640px) 16px, từ 640px 18px. ?>
								<h2 class="text-base leading-snug font-bold text-white uppercase sm:text-lg"><?php echo esc_html( $rhm_title ); ?></h2>
							<?php endif; ?>
							<?php if ( $rhm_slogan ) : ?>
								<p class="mt-1 leading-snug font-light text-footer-accent italic"><?php echo esc_html( $rhm_slogan ); ?></p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $rhm_clinic ) : ?>
				<?php // Cột 1 = icon, cột 2 = chữ (cùng kiểu vùng chi nhánh "Ghim" bên dưới). ?>
				<div class="<?php echo $rhm_logo || $rhm_title || $rhm_slogan ? 'mt-6 ' : ''; ?>grid grid-cols-[1.125rem_minmax(0,1fr)] items-start gap-x-2.5 gap-y-3 text-base leading-relaxed">
					<?php foreach ( $rhm_clinic as $rhm_info ) : ?>
						<?php echo rhm_get_icon( $rhm_info['icon'], 'mt-1 size-[18px] text-footer-accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
						<p>
							<?php if ( $rhm_info['label'] ) : ?>
								<span class="font-medium text-white"><?php echo esc_html( $rhm_info['label'] ); ?></span>
							<?php endif; ?>
							<?php echo nl2br( esc_html( $rhm_info['text'] ), false ); ?>
						</p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( 'policy' === $rhm_middle ) : ?>
			<div class="max-sm:order-last lg:border-l lg:border-white/10 lg:px-8 xl:px-12">
				<h2 class="footer-heading"><?php echo esc_html( rhm_footer_text( 'policy_title' ) ); ?></h2>
				<?php wp_nav_menu( $rhm_policy_args + array( 'menu_class' => 'footer-nav' ) ); ?>
			</div>
		<?php elseif ( 'branches' === $rhm_middle ) : ?>
			<div class="max-sm:order-last lg:border-l lg:border-white/10 lg:px-8 xl:px-12">
				<h2 class="footer-heading"><?php echo esc_html( rhm_footer_text( 'branch_title' ) ); ?></h2>
				<ul class="grid grid-cols-[repeat(auto-fill,minmax(min(18rem,100%),1fr))] gap-x-6 gap-y-3">
					<?php foreach ( $rhm_branches as $rhm_branch ) : ?>
						<li class="flex items-start gap-2.5">
							<?php echo rhm_get_icon( 'map-pin', 'mt-0.5 size-4 shrink-0 text-footer-accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
							<span><?php echo nl2br( esc_html( $rhm_branch['address'] ) ); // Chỉ địa chỉ của chi nhánh được chọn trong Tùy biến (rhm_get_footer_branches). ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<?php if ( $rhm_branch_text ) : ?>
					<a class="mt-6 inline-flex h-11 items-center gap-2.5 rounded-full border border-white/25 px-6 text-base font-medium whitespace-nowrap text-white transition-colors hover:border-primary-light hover:bg-primary hover:text-white" href="<?php echo esc_url( $rhm_branch_link ); ?>">
						<?php echo esc_html( $rhm_branch_text ); ?>
						<?php echo rhm_get_icon( 'arrow-right', 'size-4' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( $rhm_contacts || $rhm_socials ) : ?>
			<div class="lg:border-l lg:border-white/10 lg:pl-8 xl:pl-12">
				<h2 class="footer-heading"><?php echo esc_html( rhm_footer_text( 'social_title' ) ); ?></h2>
				<?php if ( $rhm_contacts ) : ?>
					<ul class="space-y-4">
						<?php foreach ( $rhm_contacts as $rhm_contact ) : ?>
							<li class="flex items-start gap-3">
								<span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-white/10 text-footer-accent">
									<?php echo rhm_get_icon( $rhm_contact['icon'], 'size-[18px]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
								</span>
								<div class="min-w-0">
									<p class="text-sm font-medium tracking-wide text-footer-muted uppercase"><?php echo esc_html( $rhm_contact['label'] ); ?></p>
									<p class="leading-snug"><?php echo $rhm_contact['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in rhm_get_footer_contacts(). ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( $rhm_socials ) : ?>
					<ul class="flex flex-wrap gap-3 <?php echo $rhm_contacts ? 'mt-6 border-t border-white/10 pt-6' : ''; ?>">
						<?php foreach ( $rhm_socials as $rhm_social ) : ?>
							<li>
								<a class="block rounded-lg transition-transform hover:-translate-y-0.5" href="<?php echo esc_url( $rhm_social['link'] ); ?>"<?php echo rhm_external_link_attrs( $rhm_social['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>>
									<img class="size-10" src="<?php echo esc_url( $rhm_social['icon'] ); ?>" alt="<?php echo esc_attr( $rhm_social['name'] ); ?>" width="40" height="40" loading="lazy" decoding="async">
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( $rhm_pinned ) : ?>
		<?php // Vùng riêng: thông tin chi nhánh "Ghim" (rộng hết khung, dưới lưới 3 cột). ?>
		<div class="border-t border-white/10">
			<div class="site-container py-4 lg:py-5">
				<?php if ( $rhm_pinned_title ) : ?>
					<h2 class="mb-5 text-base leading-snug font-bold text-white uppercase lg:text-lg"><?php echo esc_html( $rhm_pinned_title ); ?></h2>
				<?php endif; ?>
				<ul class="grid gap-x-12 gap-y-8 sm:grid-cols-2">
					<?php foreach ( $rhm_pinned as $rhm_branch ) : ?>
						<?php $rhm_phones = rhm_split_phones( $rhm_branch['hotline'] ); ?>
						<li class="min-w-0">
							<?php if ( $rhm_branch['name'] ) : ?>
								<h3 class="mb-3 inline-block rounded-full bg-primary px-3.5 py-1 text-base leading-snug font-bold text-white uppercase"><?php echo esc_html( $rhm_branch['name'] ); ?></h3>
							<?php endif; ?>
							<?php // Cột 1 = icon, cột 2 = chữ; giờ làm việc không icon vẫn thẳng cột chữ (col-start-2). ?>
							<div class="grid grid-cols-[1.125rem_minmax(0,1fr)] items-start gap-x-2.5 gap-y-2 text-base leading-relaxed">
								<?php echo rhm_get_icon( 'map-pin', 'mt-1 size-[18px] text-footer-accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
								<div>
									<p><?php echo nl2br( esc_html( $rhm_branch['address'] ) ); ?></p>
									<?php if ( $rhm_branch['old_address'] ) : ?>
										<p class="text-footer-muted">
											<?php
											/* translators: %s: old address */
											echo esc_html( sprintf( __( '(Đ/c cũ: %s)', 'themeranghammatsgcantho' ), $rhm_branch['old_address'] ) );
											?>
										</p>
									<?php endif; ?>
									<?php if ( $rhm_branch['map_url'] && $rhm_map_text ) : ?>
										<a class="text-footer-accent italic underline underline-offset-4 hover:text-white" href="<?php echo esc_url( $rhm_branch['map_url'] ); ?>"<?php echo rhm_external_link_attrs( $rhm_branch['map_url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>>
											<?php echo esc_html( $rhm_map_text ); ?>
											<?php if ( $rhm_branch['name'] ) : ?>
												<span class="sr-only">
													<?php
													/* translators: %s: branch name */
													echo esc_html( sprintf( __( 'của %s', 'themeranghammatsgcantho' ), $rhm_branch['name'] ) );
													?>
												</span>
											<?php endif; ?>
										</a>
									<?php endif; ?>
								</div>

								<?php if ( $rhm_phones ) : ?>
									<?php echo rhm_get_icon( 'phone', 'mt-1 size-[18px] text-footer-accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
									<p>
										<?php foreach ( $rhm_phones as $rhm_i => $rhm_phone ) : ?>
											<?php echo $rhm_i ? ' - ' : ''; ?><a class="font-medium whitespace-nowrap text-white hover:text-footer-accent" href="<?php echo esc_url( rhm_get_tel_href( $rhm_phone ) ); ?>"><?php echo esc_html( $rhm_phone ); ?></a>
										<?php endforeach; ?>
									</p>
								<?php endif; ?>

								<?php if ( $rhm_branch['email'] ) : ?>
									<?php echo rhm_get_icon( 'mail', 'mt-1 size-[18px] text-footer-accent' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
									<p><a class="wrap-anywhere text-footer-text hover:text-footer-accent" href="<?php echo esc_url( 'mailto:' . $rhm_branch['email'] ); ?>"><?php echo esc_html( $rhm_branch['email'] ); ?></a></p>
								<?php endif; ?>

								<?php if ( $rhm_branch['hours'] ) : ?>
									<p class="col-start-2"><?php echo nl2br( esc_html( $rhm_branch['hours'] ) ); ?></p>
								<?php endif; ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>

	<div class="border-t border-white/10">
		<div class="site-container flex flex-col gap-4 py-5 lg:flex-row lg:items-center lg:gap-8">
			<p class="text-sm text-footer-muted"><?php echo esc_html( rhm_footer_text( 'copyright' ) ); ?></p>
			<?php
			// Cột giữa đang là chi nhánh → menu chính sách hiện dạng dòng ở đây (không mất link pháp lý).
			if ( 'branches' === $rhm_middle && $rhm_policy_menu ) {
				wp_nav_menu( $rhm_policy_args + array( 'menu_class' => 'footer-legal' ) );
			}
			?>
			<?php if ( $rhm_badges ) : ?>
				<ul class="flex shrink-0 flex-wrap items-center gap-4 sm:gap-6 lg:ml-auto">
					<?php foreach ( $rhm_badges as $rhm_badge ) : ?>
						<?php $rhm_img = rhm_footer_image( $rhm_badge['image'], 40, 'h-10 w-auto', $rhm_badge['name'] ); ?>
						<li>
							<?php if ( $rhm_badge['link'] ) : ?>
								<a class="block" href="<?php echo esc_url( $rhm_badge['link'] ); ?>"<?php echo rhm_external_link_attrs( $rhm_badge['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static attributes. ?>><?php echo $rhm_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?></a>
							<?php else : ?>
								<?php echo $rhm_img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image(). ?>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>

<?php
// Nút liên hệ nhanh + popup đặt lịch: style trong footer.css (component, không dùng utility vì nằm ngoài .site-footer).
get_template_part( 'template-parts/components/floating-cta' );
get_template_part( 'template-parts/components/booking-popup' );
?>

<?php wp_footer(); ?>
</body>
</html>
