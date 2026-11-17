<?php
/**
 * Tin tức trang chủ: 2 khối bài viết theo chuyên mục (Customizer → "Tin tức (Trang chủ)").
 * Hiển thị: template-parts/home/news.php. CSS: page-home.css (@source file này).
 *
 * Hiệu năng:
 * - Mỗi khối 1 truy vấn, không đếm tổng (no_found_rows), nạp sẵn ảnh đại diện 1 lần (update_post_thumbnail_cache).
 * - Ảnh: srcset từ các size rhm-*, sizes = độ rộng hiển thị thật, lazy + decoding async, khung tỉ lệ cố định (không CLS).
 *
 * @package themeranghammatsgcantho
 */

defined( 'ABSPATH' ) || exit;

const RHM_NEWS_MAIN_COUNT = 5; // Khối trái: 1 bài lớn + 4 bài nhỏ.
const RHM_NEWS_SIDE_COUNT = 4; // Khối phải.

/** Thông tin hiển thị mặc định (dùng chung cho Customizer và giao diện). */
const RHM_NEWS_SHOW_DEFAULTS = array(
	'date'     => true,
	'author'   => false,
	'category' => false,
	'excerpt'  => true,
);

/**
 * Tiêu đề mặc định của 2 khối.
 */
function rhm_news_default_titles(): array {
	return array(
		'main' => __( 'Hoạt động và sự kiện', 'themeranghammatsgcantho' ),
		'side' => __( 'Tin tức nổi bật', 'themeranghammatsgcantho' ),
	);
}

/**
 * Thông tin bài viết được tích chọn hiển thị.
 *
 * @return array{date:bool, author:bool, category:bool, excerpt:bool}
 */
function rhm_news_show(): array {
	$show = array();
	foreach ( RHM_NEWS_SHOW_DEFAULTS as $key => $default ) {
		$show[ $key ] = (bool) get_theme_mod( "rhm_news_show_{$key}", $default );
	}
	return $show;
}

/**
 * Dữ liệu 1 khối: tiêu đề, link "Xem tất cả", truy vấn bài viết.
 *
 * @param string $key     'main' | 'side'.
 * @param int[]  $exclude ID bài đã hiện ở khối khác.
 * @return array{title:string, link:string, query:WP_Query}
 */
function rhm_news_block( string $key, int $count, array $exclude = array() ): array {
	$category = absint( get_theme_mod( "rhm_news_{$key}_cat" ) );

	$query = new WP_Query(
		array(
			'post_type'              => 'post',
			'post_status'            => 'publish',
			'cat'                    => $category,
			'posts_per_page'         => $count,
			'post__not_in'           => $exclude,
			'ignore_sticky_posts'    => true,
			'no_found_rows'          => true,
			'update_post_term_cache' => rhm_news_show()['category'],
		)
	);
	update_post_thumbnail_cache( $query );

	$posts_page = (int) get_option( 'page_for_posts' );

	return array(
		'title' => (string) get_theme_mod( "rhm_news_{$key}_title", rhm_news_default_titles()[ $key ] ),
		'link'  => (string) ( $category ? get_category_link( $category ) : ( $posts_page ? get_permalink( $posts_page ) : home_url( '/' ) ) ),
		'query' => $query,
	);
}

/**
 * Ảnh đại diện của bài hiện tại trong khung 3:2 (bài chưa có ảnh → khung nền Sứ + icon răng).
 * alt rỗng: ảnh nằm trong link trùng với link tiêu đề ngay bên cạnh. $class: class của <img> (mặc định khung 3:2 tự cao).
 */
function rhm_news_thumbnail( string $sizes, string $class = 'aspect-[3/2] w-full object-cover' ): string {
	$thumbnail_id = (int) get_post_thumbnail_id();

	if ( ! $thumbnail_id ) {
		return '<span class="flex aspect-[3/2] w-full items-center justify-center bg-porcelain text-primary/25">' . rhm_get_icon( 'tooth', 'size-1/3' ) . '</span>';
	}

	return wp_get_attachment_image(
		$thumbnail_id,
		'full',
		false,
		array(
			'class'    => $class,
			'alt'      => '',
			'sizes'    => $sizes,
			'loading'  => 'lazy',
			'decoding' => 'async',
		)
	);
}

/**
 * Dòng thông tin bài viết (ngày / tác giả / chuyên mục) theo tùy chọn hiển thị. Các mục cách nhau bằng khoảng trắng (.c-meta).
 */
function rhm_news_meta( array $show ): void {
	$items = array();

	if ( $show['date'] ) {
		$items[] = sprintf( '<time datetime="%1$s">%2$s</time>', esc_attr( get_the_date( DATE_W3C ) ), esc_html( get_the_date( 'd/m/Y' ) ) );
	}
	$author = $show['author'] ? get_the_author() : '';
	if ( '' !== $author ) {
		$items[] = esc_html( $author );
	}
	if ( $show['category'] ) {
		$categories = get_the_category();
		if ( $categories ) {
			$items[] = '<span class="font-medium text-primary">' . esc_html( $categories[0]->name ) . '</span>';
		}
	}

	if ( ! $items ) {
		return;
	}

	echo '<p class="c-meta mt-2">';
	foreach ( $items as $item ) {
		echo '<span>' . $item . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	}
	echo '</p>';
}

/**
 * 1 bài dạng dòng: ảnh 3:2 nhỏ bên trái + tiêu đề + thông tin. $thumb_class (độ rộng ảnh, vd 'w-24') phải khớp $sizes (vd '96px').
 */
function rhm_news_row( array $show, string $thumb_class, string $sizes, string $li_class = 'flex gap-4' ): void {
	?>
	<li class="<?php echo esc_attr( $li_class ); ?>">
		<a class="c-media block shrink-0 self-start <?php echo esc_attr( $thumb_class ); ?>" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
			<?php echo rhm_news_thumbnail( $sizes ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_get_attachment_image() / static markup. ?>
		</a>
		<div class="min-w-0">
			<h3 class="line-clamp-3 text-base leading-snug font-bold">
				<a class="c-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<?php rhm_news_meta( $show ); ?>
		</div>
	</li>
	<?php
}

/**
 * Tiêu đề khối (H2) + link "Xem tất cả" (kèm tên khối cho trình đọc màn hình).
 */
function rhm_news_heading( array $block ): void {
	?>
	<div class="mb-6 flex flex-wrap items-baseline justify-between gap-x-6 gap-y-2">
		<h2 class="text-2xl leading-tight font-bold text-balance text-ink lg:text-[30px]"><?php echo esc_html( $block['title'] ); ?></h2>
		<a class="c-link text-[17px]" href="<?php echo esc_url( $block['link'] ); ?>">
			<?php esc_html_e( 'Xem tất cả', 'themeranghammatsgcantho' ); ?><span class="sr-only"> <?php echo esc_html( $block['title'] ); ?></span>
		</a>
	</div>
	<?php
}
