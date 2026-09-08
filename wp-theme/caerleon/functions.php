<?php
/**
 * Caerle'on テーマの基本設定
 *
 * 文章・写真は「外観 → カスタマイズ」から編集します（inc/customizer.php）。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CAERLEON_VERSION', '1.0.0' );

/**
 * テーマがサポートする機能
 */
function caerleon_setup() {
	load_theme_textdomain( 'caerleon', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
}
add_action( 'after_setup_theme', 'caerleon_setup' );

/**
 * スタイルとスクリプトの読み込み
 */
function caerleon_scripts() {
	wp_enqueue_style(
		'caerleon-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Noto+Serif+JP:wght@300;400;500&family=Cormorant:ital,wght@1,300&display=swap',
		array(),
		null
	);

	wp_enqueue_style( 'caerleon-style', get_stylesheet_uri(), array( 'caerleon-fonts' ), CAERLEON_VERSION );

	// ヒーローの背景写真は設定値によって変わるため、CSS 変数として渡す
	wp_add_inline_style(
		'caerleon-style',
		sprintf( '.hero__bg{background-image:url(%s);}', esc_url( caerleon_img( 'hero_bg', 'p0005.jpg' ) ) )
			. sprintf( '.concept__visual{background-image:url(%s);}', esc_url( caerleon_img( 'concept_image', 'c0209824.jpg' ) ) )
			. sprintf( '.recruit__visual{background-image:url(%s);}', esc_url( caerleon_img( 'recruit_image', 'c0209948.jpg' ) ) )
			. sprintf( '.access__visual{background-image:url(%s);}', esc_url( caerleon_img( 'access_image', 'c0209646.jpg' ) ) )
	);

	wp_enqueue_script( 'caerleon-script', get_template_directory_uri() . '/js/site.js', array(), CAERLEON_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'caerleon_scripts' );

/**
 * 各項目の初期値
 *
 * カスタマイザーの表示と、サイト側の表示の両方がこの一覧を参照します。
 * （テーマを有効化した直後、まだ一度も保存していない状態でも
 * 正しい内容が表示されるようにするため）
 *
 * @return array
 */
function caerleon_defaults() {
	return array(
		// トップ画面
		'hero_corner_br'       => 'Roppongi',

		// Concept
		'concept_label'        => 'Concept',
		'concept_catch'        => "至福の時間と上質な空間で\n皆様をお迎えいたします",

		// Gallery
		'gallery_label'        => 'Gallery',

		// System（料金）
		'system_label'         => 'System',
		'system_title_top'     => '料金',
		'system_title_bottom'  => 'システム',
		'system_base_name'     => '基本料金',
		'system_base_unit'     => '1set(90min)　1名様に付き',
		'system_base_price'    => '¥22,000',
		'system_rows'          => "ホステスチャージ|1組様に付き|¥3,000\nテーブルチャージ|1名様に付き|¥2,000\nタイムチャージ(30min)|1名様に付き|¥5,000\n同伴料|1名様に付き|¥6,000\n指名料|1名に付き|¥3,000",
		'system_notes'         => "サービス料|40%\nTAX|10%",

		// Access
		'access_label'         => 'Access',
		'access_addr_label'    => 'Address',
		'access_addr'          => "〒106-0032\n東京都港区六本木 4-11-11\n六本木 Gm ビル 6 階",
		'access_open_label'    => 'Open',
		'access_open'          => "20:00 - 翌1:00\n定休日　土・日・祝",
		'access_tel_label'     => 'Tel',
		'access_tel'           => '03-6434-0048',
		'access_map_src'       => 'https://maps.google.com/maps?cid=2936050850479492176&z=18&hl=ja&output=embed',

		// Recruit
		'recruit_label'        => 'Recruit',
		'recruit_title_top'    => 'ホステス・スタッフ',
		'recruit_title_bottom' => '募集',
		'recruit_body'         => "ホステス・スタッフ募集中。経験不問。\nお問い合わせ・ご応募は下記まで\nご連絡ください。",
		'recruit_tel'          => '03-6434-0048',

		// フッター
		'footer_nav_title'     => 'Navigation',
		'footer_contact_title' => 'Contact',
		'footer_contact_tel'   => '03-6434-0048',
		'footer_contact_hours' => 'Open 20:00 — 1:00',
		'footer_copyright'     => '© ' . gmdate( 'Y' ) . " Caerle'on. All rights reserved.",
		'footer_tagline'       => 'Tokyo — Members Only',

		// SEO
		'seo_title'            => "Caerle'on（カーリアン）｜六本木の会員制ラウンジ",
		'seo_description'      => "六本木の会員制ラウンジ Caerle'on（カーリアン）。2011年開業。完全会員制・ご紹介制のラウンジとして、六本木駅徒歩2分の六本木Gmビル6階で営業しております。営業時間20:00〜翌1:00、土日祝定休。ご予約・お問い合わせは03-6434-0048。",
	);
}

/**
 * カスタマイザーの設定値を取り出す
 *
 * 一度も保存していない場合は caerleon_defaults() の初期値を返します。
 * 管理画面で意図的に空にした場合は、空のまま表示します。
 *
 * @param string      $key      設定名。
 * @param string|null $fallback 初期値一覧にない場合の値。
 * @return string
 */
function caerleon_opt( $key, $fallback = '' ) {
	$value = get_theme_mod( 'caerleon_' . $key, null );

	if ( null === $value ) {
		$defaults = caerleon_defaults();
		return isset( $defaults[ $key ] ) ? $defaults[ $key ] : $fallback;
	}

	return $value;
}

/**
 * 画像設定を取り出す（未設定ならテーマ同梱の写真を使う）
 *
 * @param string $key           設定名。
 * @param string $default_file  assets/img/ 内の既定ファイル名。
 * @return string 画像 URL。設定が空文字なら空文字（＝非表示）。
 */
function caerleon_img( $key, $default_file ) {
	$value = get_theme_mod( 'caerleon_' . $key, null );

	if ( null === $value ) {
		return get_template_directory_uri() . '/assets/img/' . $default_file;
	}
	// 管理画面で画像を削除した場合は空文字。呼び出し側で非表示にする。
	return $value;
}

/**
 * 改行を <br> に変換して安全に出力する
 *
 * @param string $text  対象のテキスト。
 * @param bool   $echo  true なら出力、false なら返り値。
 * @return string|void
 */
function caerleon_nl( $text, $echo = true ) {
	$html = nl2br( esc_html( $text ), false );
	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput -- 上でエスケープ済み
		return;
	}
	return $html;
}

/**
 * 「A|B|C」形式の複数行テキストを配列に変換する
 *
 * 例）ホステスチャージ|1組様に付き|¥3,000
 *
 * @param string $text 複数行テキスト。
 * @param int    $cols 1 行あたりの列数。
 * @return array
 */
function caerleon_rows( $text, $cols = 3 ) {
	$rows = array();

	foreach ( preg_split( '/\r\n|\r|\n/', (string) $text ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$cells = array_map( 'trim', explode( '|', $line ) );
		$cells = array_pad( $cells, $cols, '' );
		$rows[] = array_slice( $cells, 0, $cols );
	}

	return $rows;
}

/**
 * 電話番号を tel: リンク用の文字列に変換する
 *
 * @param string $tel 表示用の電話番号。
 * @return string
 */
function caerleon_tel_href( $tel ) {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) $tel );
}

/**
 * 構造化データ（JSON-LD）を出力する
 *
 * 設定値から生成するため、カスタマイザーで内容を変えると自動で追従します。
 */
function caerleon_structured_data() {
	if ( ! is_front_page() ) {
		return;
	}

	$home  = home_url( '/' );
	$tel   = caerleon_opt( 'access_tel', '03-6434-0048' );
	$addr  = preg_split( '/\r\n|\r|\n/', caerleon_opt( 'access_addr' ) );
	$addr  = array_values( array_filter( array_map( 'trim', $addr ) ) );
	$postal = '';
	$street = '';

	foreach ( $addr as $line ) {
		if ( preg_match( '/^〒?\s*(\d{3}-?\d{4})/u', $line, $m ) ) {
			$postal = str_replace( '〒', '', $m[1] );
		} else {
			$street .= $line;
		}
	}

	$business = array(
		'@type'          => array( 'LocalBusiness', 'BarOrPub' ),
		'@id'            => $home . '#business',
		'name'           => get_bloginfo( 'name' ),
		'description'    => wp_strip_all_tags( caerleon_opt( 'seo_description' ) ),
		'url'            => $home,
		'telephone'      => '+81-' . ltrim( preg_replace( '/[^0-9]/', '', $tel ), '0' ),
		'image'          => array( caerleon_img( 'seo_image', 'p0005.jpg' ) ),
		'logo'           => caerleon_img( 'logo_gold', 'logo-gold.png' ),
		'priceRange'     => caerleon_opt( 'system_base_price', '¥22,000' ) . '〜',
		'currenciesAccepted' => 'JPY',
		'address'        => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $street,
			'addressRegion'   => '東京都',
			'postalCode'      => $postal,
			'addressCountry'  => 'JP',
		),
		'publicAccess'       => false,
		'isAccessibleForFree' => false,
	);

	$map = caerleon_opt( 'access_map_src' );
	if ( $map && preg_match( '/cid=(\d+)/', $map, $m ) ) {
		$place                = 'https://maps.google.com/?cid=' . $m[1];
		$business['hasMap']   = $place;
		$business['sameAs']   = array( $place );
	}

	$graph = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			$business,
			array(
				'@type'     => 'WebSite',
				'@id'       => $home . '#website',
				'url'       => $home,
				'name'      => get_bloginfo( 'name' ),
				'inLanguage' => 'ja',
				'publisher' => array( '@id' => $home . '#business' ),
			),
		),
	);

	echo "\n" . '<script type="application/ld+json">'
		. wp_json_encode( $graph, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
		. '</script>' . "\n";
}
add_action( 'wp_head', 'caerleon_structured_data', 5 );

require_once get_template_directory() . '/inc/customizer.php';
