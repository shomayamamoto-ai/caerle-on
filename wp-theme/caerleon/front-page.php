<?php
/**
 * トップページ
 *
 * 表示される文章・写真は「外観 → カスタマイズ → Caerle'on サイト設定」から変更できます。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$logo_light  = caerleon_img( 'logo_light', 'logo-light.png' );
$access_tel  = caerleon_opt( 'access_tel', '03-6434-0048' );
$recruit_tel = caerleon_opt( 'recruit_tel', '03-6434-0048' );
$map_src     = caerleon_opt( 'access_map_src' );

// ギャラリー写真（未設定の枠は既定の写真、削除した枠は非表示）
$gallery_defaults = array( 'p0005.jpg', 'p0004.jpg', 'c0209615.jpg', 'c0209748.jpg', 'c0209962.jpg', 'p0002.jpg' );
$gallery_alts     = array(
	'Caerle\'on のメインフロア。木組みの間仕切りと季節の花で設えたラウンジ空間',
	'Caerle\'on 店内のヤマハ製グランドピアノ',
	'間接照明に照らされたベルベットのソファ席',
	'大理石のテーブルを備えたバンケット席',
	'大理石の壁面を背にした季節のフラワーアレンジメント',
	'ダリアと百合を用いた店内のフラワーアレンジメント',
);
$gallery = array();
foreach ( $gallery_defaults as $i => $file ) {
	$url = caerleon_img( 'gallery_image_' . ( $i + 1 ), $file );
	if ( $url ) {
		$gallery[] = array( $url, $gallery_alts[ $i ] );
	}
}
?>

<section class="hero">
	<div class="hero__bg"></div>
	<div class="hero__corner hero__corner--tr"><?php echo esc_html( caerleon_opt( 'hero_corner_tr' ) ); ?></div>
	<div class="hero__corner hero__corner--br"><?php echo esc_html( caerleon_opt( 'hero_corner_br' ) ); ?></div>

	<div class="hero__content">
		<h1 class="hero__title">
			<?php if ( $logo_light ) : ?>
				<img src="<?php echo esc_url( $logo_light ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="hero__title-img">
			<?php endif; ?>
			<span class="sr-only"><?php echo esc_html( caerleon_opt( 'seo_title', get_bloginfo( 'name' ) ) ); ?></span>
		</h1>
	</div>

	<span class="hero__scroll" aria-hidden="true"></span>
</section>

<section id="concept">
	<div class="concept">
		<div class="concept__visual fade-in">
			<div class="concept__visual-frame"></div>
		</div>
		<div class="concept__text fade-in">
			<div class="section__label"><?php echo esc_html( caerleon_opt( 'concept_label' ) ); ?></div>
			<h2 class="concept__catch"><?php caerleon_nl( caerleon_opt( 'concept_catch' ) ); ?></h2>
		</div>
	</div>
</section>

<section id="gallery" class="gallery">
	<div class="gallery__head fade-in">
		<div>
			<div class="section__label"><?php echo esc_html( caerleon_opt( 'gallery_label' ) ); ?></div>
			<h2 class="section__title">
				<span class="italic"><?php echo esc_html( caerleon_opt( 'gallery_title_top' ) ); ?></span><br>
				<span><?php echo esc_html( caerleon_opt( 'gallery_title_bottom' ) ); ?></span>
			</h2>
		</div>
	</div>

	<div class="gallery__slider">
		<div class="gallery__grid" id="galleryGrid" role="group" aria-roledescription="<?php esc_attr_e( 'カルーセル', 'caerleon' ); ?>" aria-label="<?php esc_attr_e( '店内写真', 'caerleon' ); ?>">
			<?php foreach ( $gallery as $item ) : ?>
				<figure class="gallery__item">
					<img src="<?php echo esc_url( $item[0] ); ?>" alt="<?php echo esc_attr( $item[1] ); ?>" loading="lazy">
				</figure>
			<?php endforeach; ?>
		</div>
		<button class="gallery__nav gallery__nav--prev" id="galleryPrev" type="button" aria-label="<?php esc_attr_e( '前の写真', 'caerleon' ); ?>">‹</button>
		<button class="gallery__nav gallery__nav--next" id="galleryNext" type="button" aria-label="<?php esc_attr_e( '次の写真', 'caerleon' ); ?>">›</button>
	</div>
	<div class="gallery__dots" id="galleryDots"></div>
</section>

<section id="information" class="information">
	<div class="information__inner">
		<div class="section__label"><?php echo esc_html( caerleon_opt( 'system_label' ) ); ?></div>
		<h2 class="section__title">
			<span class="italic"><?php echo esc_html( caerleon_opt( 'system_title_top' ) ); ?></span><br>
			<span><?php echo esc_html( caerleon_opt( 'system_title_bottom' ) ); ?></span>
		</h2>

		<div class="price">
			<div class="price__base">
				<span class="price__base-name"><?php echo esc_html( caerleon_opt( 'system_base_name' ) ); ?></span>
				<span class="price__base-unit"><?php echo esc_html( caerleon_opt( 'system_base_unit' ) ); ?></span>
				<span class="price__base-price"><?php echo esc_html( caerleon_opt( 'system_base_price' ) ); ?></span>
			</div>

			<dl class="price__list" id="priceRows">
				<?php foreach ( caerleon_rows( caerleon_opt( 'system_rows' ), 3 ) as $row ) : ?>
					<div class="price__row">
						<dt>
							<?php echo esc_html( $row[0] ); ?>
							<?php if ( '' !== $row[1] ) : ?>
								<span class="price__unit"><?php echo esc_html( $row[1] ); ?></span>
							<?php endif; ?>
						</dt>
						<dd><?php echo esc_html( $row[2] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>

			<dl class="price__notes" id="priceNotes">
				<?php foreach ( caerleon_rows( caerleon_opt( 'system_notes' ), 2 ) as $row ) : ?>
					<div class="price__row">
						<dt><?php echo esc_html( $row[0] ); ?></dt>
						<dd><?php echo esc_html( $row[1] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>
	</div>
</section>

<section id="access" class="access">
	<div class="access__grid">
		<div class="access__info">
			<div class="section__label"><?php echo esc_html( caerleon_opt( 'access_label' ) ); ?></div>
			<h2 class="section__title"><span class="italic"><?php echo esc_html( caerleon_opt( 'access_title' ) ); ?></span></h2>
			<dl class="access__details" id="accessRows">
				<dt><?php echo esc_html( caerleon_opt( 'access_addr_label' ) ); ?></dt>
				<dd><?php caerleon_nl( caerleon_opt( 'access_addr' ) ); ?></dd>
				<dt><?php echo esc_html( caerleon_opt( 'access_open_label' ) ); ?></dt>
				<dd><?php caerleon_nl( caerleon_opt( 'access_open' ) ); ?></dd>
				<dt><?php echo esc_html( caerleon_opt( 'access_tel_label' ) ); ?></dt>
				<dd><a href="<?php echo esc_attr( caerleon_tel_href( $access_tel ) ); ?>"><?php echo esc_html( $access_tel ); ?></a></dd>
			</dl>
		</div>
		<?php if ( $map_src ) : ?>
			<div class="access__map">
				<iframe
					class="access__map-frame"
					src="<?php echo esc_url( $map_src ); ?>"
					title="<?php esc_attr_e( '所在地の地図', 'caerleon' ); ?>"
					loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"
					allowfullscreen></iframe>
			</div>
		<?php endif; ?>
	</div>
</section>

<section id="recruit" class="recruit">
	<div class="recruit__inner">
		<div class="recruit__visual fade-in"></div>
		<div class="recruit__text fade-in">
			<div class="section__label"><?php echo esc_html( caerleon_opt( 'recruit_label' ) ); ?></div>
			<h3>
				<span><?php echo esc_html( caerleon_opt( 'recruit_title_top' ) ); ?></span><br>
				<span class="italic"><?php echo esc_html( caerleon_opt( 'recruit_title_bottom' ) ); ?></span>
			</h3>
			<p>
				<?php
				// 2 つ目以降の改行はスマートフォンのみ有効にするため、通し番号を付ける
				$body  = esc_html( caerleon_opt( 'recruit_body' ) );
				$index = 0;
				echo preg_replace_callback(
					'/\r\n|\r|\n/',
					function () use ( &$index ) {
						$index++;
						return '<br class="nl nl-' . $index . '">';
					},
					$body
				); // phpcs:ignore WordPress.Security.EscapeOutput -- 上でエスケープ済み
				?>
			</p>
			<?php if ( '' !== $recruit_tel ) : ?>
				<a href="<?php echo esc_attr( caerleon_tel_href( $recruit_tel ) ); ?>" class="tel-link" id="recruitTel" aria-label="<?php esc_attr_e( '採用について電話で問い合わせる', 'caerleon' ); ?>"><span><?php echo esc_html( $recruit_tel ); ?></span></a>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
get_footer();
