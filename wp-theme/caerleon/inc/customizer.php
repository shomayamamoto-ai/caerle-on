<?php
/**
 * カスタマイザー（外観 → カスタマイズ）
 *
 * サイト上の文章・写真・料金表をすべてここから編集できます。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 文字列設定を追加するための小さなヘルパー
 *
 * @param WP_Customize_Manager $wp_customize カスタマイザー。
 * @param string               $section      セクション ID。
 * @param string               $key          設定名（接頭辞なし）。
 * @param string               $label        ラベル。
 * @param string               $default      既定値。
 * @param string               $type         text / textarea / url。
 * @param string               $description  補足説明。
 */
function caerleon_add_text( $wp_customize, $section, $key, $label, $default = '', $type = 'text', $description = '' ) {
	$id       = 'caerleon_' . $key;
	$defaults = caerleon_defaults();

	// 初期値は caerleon_defaults() を優先（サイト側の表示と必ず一致させる）
	if ( isset( $defaults[ $key ] ) ) {
		$default = $defaults[ $key ];
	}

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => $default,
			'sanitize_callback' => ( 'url' === $type ) ? 'esc_url_raw' : 'wp_kses_post',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		$id,
		array(
			'label'       => $label,
			'section'     => $section,
			'type'        => ( 'url' === $type ) ? 'url' : $type,
			'description' => $description,
		)
	);
}

/**
 * 画像設定を追加するためのヘルパー
 *
 * @param WP_Customize_Manager $wp_customize カスタマイザー。
 * @param string               $section      セクション ID。
 * @param string               $key          設定名（接頭辞なし）。
 * @param string               $label        ラベル。
 * @param string               $description  補足説明。
 */
function caerleon_add_image( $wp_customize, $section, $key, $label, $description = '' ) {
	$id = 'caerleon_' . $key;

	$wp_customize->add_setting(
		$id,
		array(
			'default'           => null,
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			$id,
			array(
				'label'       => $label,
				'section'     => $section,
				'description' => $description,
			)
		)
	);
}

/**
 * カスタマイザーの項目を登録する
 *
 * @param WP_Customize_Manager $wp_customize カスタマイザー。
 */
function caerleon_customize_register( $wp_customize ) {

	$panel = 'caerleon_panel';

	$wp_customize->add_panel(
		$panel,
		array(
			'title'       => __( 'Caerle\'on サイト設定', 'caerleon' ),
			'description' => __( 'サイトに表示される文章と写真をここから編集できます。変更したら右上の「公開」を押してください。', 'caerleon' ),
			'priority'    => 10,
		)
	);

	$section = function ( $id, $title, $desc = '' ) use ( $wp_customize, $panel ) {
		$wp_customize->add_section(
			$id,
			array(
				'title'       => $title,
				'panel'       => $panel,
				'description' => $desc,
			)
		);
		return $id;
	};

	// ---------------- ヒーロー（トップ） ----------------
	$s = $section( 'caerleon_hero', __( 'トップ画面', 'caerleon' ) );
	caerleon_add_image( $wp_customize, $s, 'hero_bg', __( '背景写真', 'caerleon' ), __( '推奨サイズ：横 1600px 以上', 'caerleon' ) );
	caerleon_add_image( $wp_customize, $s, 'logo_light', __( 'ロゴ（明色・ヘッダーとトップ用）', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'hero_corner_tr', __( '右上の表記', 'caerleon' ), 'Members Only —' );
	caerleon_add_text( $wp_customize, $s, 'hero_corner_br', __( '右下の表記', 'caerleon' ), 'Roppongi' );

	// ---------------- Concept ----------------
	$s = $section( 'caerleon_concept', __( 'Concept（コンセプト）', 'caerleon' ) );
	caerleon_add_image( $wp_customize, $s, 'concept_image', __( '写真', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'concept_label', __( '小見出し', 'caerleon' ), 'Concept' );
	caerleon_add_text(
		$wp_customize,
		$s,
		'concept_catch',
		__( 'キャッチコピー', 'caerleon' ),
		"至福な時間と上質な空間で\n皆様をお迎えいたします",
		'textarea',
		__( '改行するとそのまま反映されます。', 'caerleon' )
	);

	// ---------------- Gallery ----------------
	$s = $section( 'caerleon_gallery', __( 'Gallery（店内写真）', 'caerleon' ), __( '写真は最大 6 枚です。削除した枠はサイトに表示されません。', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'gallery_label', __( '小見出し', 'caerleon' ), 'Gallery' );
	caerleon_add_text( $wp_customize, $s, 'gallery_title_top', __( '見出し（1行目）', 'caerleon' ), 'Interior' );
	caerleon_add_text( $wp_customize, $s, 'gallery_title_bottom', __( '見出し（2行目）', 'caerleon' ), '＆ Atmosphere' );
	for ( $i = 1; $i <= 6; $i++ ) {
		/* translators: %d: 写真の番号 */
		caerleon_add_image( $wp_customize, $s, 'gallery_image_' . $i, sprintf( __( '写真 %d 枚目', 'caerleon' ), $i ) );
	}

	// ---------------- System（料金） ----------------
	$s = $section( 'caerleon_system', __( 'System（料金システム）', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'system_label', __( '小見出し', 'caerleon' ), 'System' );
	caerleon_add_text( $wp_customize, $s, 'system_title_top', __( '見出し（1行目）', 'caerleon' ), '料金' );
	caerleon_add_text( $wp_customize, $s, 'system_title_bottom', __( '見出し（2行目）', 'caerleon' ), 'システム' );
	caerleon_add_text( $wp_customize, $s, 'system_base_name', __( '基本料金の名称', 'caerleon' ), '基本料金' );
	caerleon_add_text( $wp_customize, $s, 'system_base_unit', __( '基本料金の単位', 'caerleon' ), '1set(90min)　1名様に付き' );
	caerleon_add_text( $wp_customize, $s, 'system_base_price', __( '基本料金の金額', 'caerleon' ), '¥22,000' );
	caerleon_add_text(
		$wp_customize,
		$s,
		'system_rows',
		__( '料金の項目', 'caerleon' ),
		"ホステスチャージ|1組様に付き|¥3,000\nテーブルチャージ|1名様に付き|¥2,000\nタイムチャージ(30min)|1名様に付き|¥5,000\n同伴料|1名様に付き|¥6,000\n指名料|1名に付き|¥3,000",
		'textarea',
		__( '1 行に 1 項目。「名称 | 単位 | 金額」を縦棒（|）で区切って入力します。行を増やせば項目が増えます。', 'caerleon' )
	);
	caerleon_add_text(
		$wp_customize,
		$s,
		'system_notes',
		__( 'サービス料・TAX', 'caerleon' ),
		"サービス料|40%\nTAX|10%",
		'textarea',
		__( '1 行に 1 項目。「名称 | 料率」を縦棒（|）で区切って入力します。', 'caerleon' )
	);

	// ---------------- Access ----------------
	$s = $section( 'caerleon_access', __( 'Access（アクセス）', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'access_label', __( '小見出し', 'caerleon' ), 'Access' );
	caerleon_add_text( $wp_customize, $s, 'access_title', __( '見出し', 'caerleon' ), 'Access' );
	caerleon_add_text( $wp_customize, $s, 'access_addr_label', __( '住所の見出し', 'caerleon' ), 'Address' );
	caerleon_add_text( $wp_customize, $s, 'access_addr', __( '住所', 'caerleon' ), "〒106-0032\n東京都港区六本木 4-11-11\n六本木 Gm ビル 6 階", 'textarea' );
	caerleon_add_text( $wp_customize, $s, 'access_open_label', __( '営業時間の見出し', 'caerleon' ), 'Open' );
	caerleon_add_text( $wp_customize, $s, 'access_open', __( '営業時間', 'caerleon' ), "20:00 - 翌1:00\n定休日　土・日・祝", 'textarea' );
	caerleon_add_text( $wp_customize, $s, 'access_tel_label', __( '電話番号の見出し', 'caerleon' ), 'Tel' );
	caerleon_add_text( $wp_customize, $s, 'access_tel', __( '電話番号', 'caerleon' ), '03-6434-0048' );
	caerleon_add_text(
		$wp_customize,
		$s,
		'access_map_src',
		__( '地図の埋め込み URL', 'caerleon' ),
		'https://maps.google.com/maps?cid=2936050850479492176&z=18&hl=ja&output=embed',
		'url',
		__( 'Google マップで店舗を開き「共有 → 地図を埋め込む」でコピーした HTML の中の src="..." の部分（URL）だけを貼り付けてください。ピンの位置を正確にしたいときはこの方法が確実です。', 'caerleon' )
	);

	// ---------------- Recruit ----------------
	$s = $section( 'caerleon_recruit', __( 'Recruit（採用）', 'caerleon' ) );
	caerleon_add_image( $wp_customize, $s, 'recruit_image', __( '写真', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'recruit_label', __( '小見出し', 'caerleon' ), 'Recruit' );
	caerleon_add_text( $wp_customize, $s, 'recruit_title_top', __( '見出し（1行目）', 'caerleon' ), 'ホステス・スタッフ' );
	caerleon_add_text( $wp_customize, $s, 'recruit_title_bottom', __( '見出し（2行目）', 'caerleon' ), '募集' );
	caerleon_add_text(
		$wp_customize,
		$s,
		'recruit_body',
		__( '本文', 'caerleon' ),
		"ホステス・スタッフ募集中。経験不問。\nお問い合わせ・ご応募は下記まで\nご連絡ください。",
		'textarea'
	);
	caerleon_add_text( $wp_customize, $s, 'recruit_tel', __( '応募先の電話番号', 'caerleon' ), '03-6434-0048' );

	// ---------------- フッター ----------------
	$s = $section( 'caerleon_footer', __( 'フッター', 'caerleon' ) );
	caerleon_add_image( $wp_customize, $s, 'logo_gold', __( 'ロゴ（金色・フッター用）', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'footer_nav_title', __( 'ナビゲーションの見出し', 'caerleon' ), 'Navigation' );
	caerleon_add_text( $wp_customize, $s, 'footer_contact_title', __( '連絡先の見出し', 'caerleon' ), 'Contact' );
	caerleon_add_text( $wp_customize, $s, 'footer_contact_tel', __( '電話番号', 'caerleon' ), '03-6434-0048' );
	caerleon_add_text( $wp_customize, $s, 'footer_contact_hours', __( '営業時間', 'caerleon' ), 'Open 20:00 — 1:00' );
	caerleon_add_text( $wp_customize, $s, 'footer_copyright', __( '著作権表記', 'caerleon' ), '© 2026 Caerle\'on. All rights reserved.' );
	caerleon_add_text( $wp_customize, $s, 'footer_tagline', __( '右下の表記', 'caerleon' ), 'Tokyo — Members Only' );

	// ---------------- SEO ----------------
	$s = $section( 'caerleon_seo', __( 'SEO・共有設定', 'caerleon' ), __( '検索結果や SNS で共有されたときの表示内容です。', 'caerleon' ) );
	caerleon_add_text( $wp_customize, $s, 'seo_title', __( 'ページタイトル', 'caerleon' ), 'Caerle\'on（カーリアン）｜六本木の会員制ラウンジ' );
	caerleon_add_text(
		$wp_customize,
		$s,
		'seo_description',
		__( 'ページの説明', 'caerleon' ),
		'六本木の会員制ラウンジ Caerle\'on（カーリアン）。2011年開業。完全会員制・ご紹介制のラウンジとして、六本木駅徒歩2分の六本木Gmビル6階で営業しております。営業時間20:00〜翌1:00、土日祝定休。ご予約・お問い合わせは03-6434-0048。',
		'textarea'
	);
	caerleon_add_image( $wp_customize, $s, 'seo_image', __( '共有時の画像（OGP）', 'caerleon' ) );
}
add_action( 'customize_register', 'caerleon_customize_register' );
