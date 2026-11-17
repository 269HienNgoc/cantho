<?php
/**
 * Đầu bài viết (CHỈ single.php, trong vòng lặp). CSS: single-post.css.
 * Chuyên mục (link chữ) → H1 (LCP: trang bài viết không có ảnh đầu trang) → thông tin bài
 * (bác sĩ tham vấn hoặc tác giả, ngày đăng, ngày cập nhật khi khác ngày đăng, thời gian đọc — bật/tắt từng mục ở
 * Customizer → "Bài viết", inc/post-meta.php) → sao đánh giá (kk-star-ratings, nếu bật).
 * Không ảnh, không tải gì thêm.
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

$rhm_categories      = get_the_category();
$rhm_rating          = rhm_get_star_rating();
$rhm_meta            = rhm_post_meta_show();
$rhm_meta['updated'] = $rhm_meta['updated'] && get_the_modified_date( 'Y-m-d' ) !== get_the_date( 'Y-m-d' );
$rhm_reviewer        = rhm_get_post_reviewer( get_the_ID() ); // ACF "Bác sĩ tham vấn": thay tên tác giả (khớp schema author + reviewedBy).
?>
<header class="post-header border-b border-thread pb-8">
	<?php if ( $rhm_categories ) : ?>
		<ul class="mb-4 flex flex-wrap gap-x-5 gap-y-1">
			<?php foreach ( $rhm_categories as $rhm_category ) : ?>
				<li><a class="c-link text-base" href="<?php echo esc_url( get_category_link( $rhm_category ) ); ?>"><?php echo esc_html( $rhm_category->name ); ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<h1 class="c-h1"><?php the_title(); ?></h1>

	<?php if ( $rhm_meta['author'] || $rhm_meta['date'] || $rhm_meta['reading'] || $rhm_meta['updated'] || $rhm_rating ) : ?>
		<div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3">
			<dl class="c-meta gap-x-6">
				<?php if ( $rhm_meta['author'] ) : ?>
					<div class="flex gap-1.5">
						<dt><?php echo esc_html( $rhm_reviewer ? __( 'Tham vấn:', 'themeranghammatsgcantho' ) : __( 'Tác giả:', 'themeranghammatsgcantho' ) ); ?></dt>
						<dd class="font-medium text-ink">
							<?php if ( $rhm_reviewer ) : ?>
								<a class="c-link" href="<?php echo esc_url( get_permalink( $rhm_reviewer ) ); ?>"><?php echo esc_html( get_the_title( $rhm_reviewer ) ); ?></a>
							<?php else : ?>
								<?php the_author(); ?>
							<?php endif; ?>
						</dd>
					</div>
				<?php endif; ?>
				<?php if ( $rhm_meta['date'] ) : ?>
					<div class="flex gap-1.5">
						<dt><?php esc_html_e( 'Đăng ngày', 'themeranghammatsgcantho' ); ?></dt>
						<dd><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date( 'd/m/Y' ) ); ?></time></dd>
					</div>
				<?php endif; ?>
				<?php if ( $rhm_meta['updated'] ) : ?>
					<div class="flex gap-1.5">
						<dt><?php esc_html_e( 'Cập nhật', 'themeranghammatsgcantho' ); ?></dt>
						<dd><time datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_modified_date( 'd/m/Y' ) ); ?></time></dd>
					</div>
				<?php endif; ?>
				<?php if ( $rhm_meta['reading'] ) : ?>
					<div>
						<dt class="sr-only"><?php esc_html_e( 'Thời gian đọc', 'themeranghammatsgcantho' ); ?></dt>
						<?php /* translators: %d: số phút đọc. */ ?>
						<dd><?php echo esc_html( sprintf( __( '%d phút đọc', 'themeranghammatsgcantho' ), rhm_get_reading_minutes() ) ); ?></dd>
					</div>
				<?php endif; ?>
			</dl>
			<?php if ( $rhm_rating ) : ?>
				<div><?php echo $rhm_rating; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup của plugin kk-star-ratings (inc/plugin/kk-star-ratings.php). ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</header>
