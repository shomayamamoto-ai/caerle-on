<?php
/**
 * 汎用テンプレート
 *
 * この一枚もののサイトでは、トップページ以外にアクセスされた場合も
 * トップページの内容を表示します。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_template_part( 'front-page' );
