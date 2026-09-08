<?php
/**
 * フッター
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$caerleon_logo_gold  = caerleon_img( 'logo_gold', 'logo-gold.png' );
$caerleon_footer_tel = caerleon_opt( 'footer_contact_tel', '03-6434-0048' );

$caerleon_nav = array(
	'#concept'     => 'Concept',
	'#gallery'     => 'Gallery',
	'#information' => 'System',
	'#access'      => 'Access',
	'#recruit'     => 'Recruit',
);
?>

<footer class="footer">
	<div class="footer__inner">
		<?php if ( $caerleon_logo_gold ) : ?>
			<div class="footer__brand"><img src="<?php echo esc_url( $caerleon_logo_gold ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></div>
		<?php endif; ?>

		<div class="footer__grid">
			<div class="footer__col">
				<h5><?php echo esc_html( caerleon_opt( 'footer_nav_title' ) ); ?></h5>
				<ul>
					<?php foreach ( $caerleon_nav as $caerleon_href => $caerleon_text ) : ?>
						<li><a href="<?php echo esc_attr( $caerleon_href ); ?>"><?php echo esc_html( $caerleon_text ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="footer__col">
				<h5><?php echo esc_html( caerleon_opt( 'footer_contact_title' ) ); ?></h5>
				<ul>
					<li><a href="<?php echo esc_attr( caerleon_tel_href( $caerleon_footer_tel ) ); ?>"><?php echo esc_html( $caerleon_footer_tel ); ?></a></li>
					<li><?php echo esc_html( caerleon_opt( 'footer_contact_hours' ) ); ?></li>
				</ul>
			</div>
		</div>

		<div class="footer__bottom">
			<span><?php echo esc_html( caerleon_opt( 'footer_copyright' ) ); ?></span>
			<span><?php echo esc_html( caerleon_opt( 'footer_tagline' ) ); ?></span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
