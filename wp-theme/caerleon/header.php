<?php
/**
 * ヘッダー（<head> とサイト上部）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$caerleon_logo_light = caerleon_img( 'logo_light', 'logo-light.png' );
$caerleon_seo_image  = caerleon_img( 'seo_image', 'p0005.jpg' );
$caerleon_desc       = wp_strip_all_tags( caerleon_opt( 'seo_description' ) );
$caerleon_title      = wp_strip_all_tags( caerleon_opt( 'seo_title', get_bloginfo( 'name' ) ) );

$caerleon_nav = array(
	'#concept'     => 'Concept',
	'#gallery'     => 'Gallery',
	'#information' => 'System',
	'#access'      => 'Access',
	'#recruit'     => 'Recruit',
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo esc_attr( $caerleon_desc ); ?>">
<meta name="theme-color" content="#0a0908">
<meta name="format-detection" content="telephone=no">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
<meta property="og:locale" content="ja_JP">
<meta property="og:title" content="<?php echo esc_attr( $caerleon_title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $caerleon_desc ); ?>">
<meta property="og:url" content="<?php echo esc_url( home_url( '/' ) ); ?>">
<meta property="og:image" content="<?php echo esc_url( $caerleon_seo_image ); ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo esc_attr( $caerleon_title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $caerleon_desc ); ?>">
<meta name="twitter:image" content="<?php echo esc_url( $caerleon_seo_image ); ?>">

<meta name="geo.region" content="JP-13">
<meta name="geo.placename" content="東京都港区六本木">

<script>
  // リロード時にブラウザが前回のスクロール位置を復元するのを無効化
  if ('scrollRestoration' in history) history.scrollRestoration = 'manual';
  // リロードの場合は URL のアンカーも取り除き、必ず最上部から表示する
  try {
    var nav = performance.getEntriesByType && performance.getEntriesByType('navigation')[0];
    var isReload = nav ? nav.type === 'reload'
                       : (performance.navigation && performance.navigation.type === 1);
    if (isReload && location.hash) {
      history.replaceState(null, '', location.pathname + location.search);
    }
  } catch (e) {}
</script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#concept" class="skip-link"><?php esc_html_e( 'Skip to content', 'caerleon' ); ?></a>

<div class="progress" aria-hidden="true"><span id="progressFill"></span></div>

<header class="header" id="siteHeader">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
		<span class="header__logo-ornament" aria-hidden="true"></span>
		<?php if ( $caerleon_logo_light ) : ?>
			<img src="<?php echo esc_url( $caerleon_logo_light ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="header__logo-img">
		<?php endif; ?>
	</a>
	<nav class="header__nav">
		<?php foreach ( $caerleon_nav as $caerleon_href => $caerleon_text ) : ?>
			<a href="<?php echo esc_attr( $caerleon_href ); ?>"><?php echo esc_html( $caerleon_text ); ?></a>
		<?php endforeach; ?>
	</nav>
	<button class="nav-toggle" aria-label="<?php esc_attr_e( 'メニューを開く', 'caerleon' ); ?>" aria-expanded="false" aria-controls="mobileMenu">
		<span></span><span></span><span></span>
	</button>
</header>

<nav class="mobile-menu" id="mobileMenu" aria-hidden="true">
	<?php foreach ( $caerleon_nav as $caerleon_href => $caerleon_text ) : ?>
		<a href="<?php echo esc_attr( $caerleon_href ); ?>"><?php echo esc_html( $caerleon_text ); ?></a>
	<?php endforeach; ?>
</nav>
