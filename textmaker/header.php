<?php
/**
 * Kopfbereich.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip" href="#inhalt"><?php esc_html_e( 'Zum Inhalt wechseln', 'textmaker' ); ?></a>

<header class="site-head">
	<div class="frame">
		<?php if ( has_custom_logo() ) : ?>
			<div class="site-logo"><?php the_custom_logo(); ?></div>
		<?php else : ?>
			<a class="wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php
				$textmaker_name = get_bloginfo( 'name' );

				// Byte-basiert genügt: getrennt wird an einem ASCII-„x“, das in
				// UTF-8 nie Teil einer Mehrbyte-Sequenz ist.
				$textmaker_pos = stripos( $textmaker_name, 'x' );

				if ( false === $textmaker_pos ) {
					echo esc_html( $textmaker_name );
				} else {
					printf(
						'%1$s<span class="x">%2$s</span>%3$s',
						esc_html( substr( $textmaker_name, 0, $textmaker_pos ) ),
						esc_html( substr( $textmaker_name, $textmaker_pos, 1 ) ),
						esc_html( substr( $textmaker_name, $textmaker_pos + 1 ) )
					);
				}
				?>
			</a>
		<?php endif; ?>

		<button class="burger" type="button" aria-expanded="false" aria-controls="hauptmenue"
			aria-label="<?php esc_attr_e( 'Menü', 'textmaker' ); ?>">
			<span></span><span></span><span></span>
		</button>

		<nav aria-label="<?php esc_attr_e( 'Hauptmenü', 'textmaker' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'hauptmenue',
						'menu_class'     => 'nav',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				echo '<ul class="nav" id="hauptmenue">';

				// Kein „Home“ — dafür ist das Logo da.
				$textmaker_fallback = array(
					'#lektorat'   => __( 'Lektorat-Service', 'textmaker' ),
					'#preise'     => __( 'Preise', 'textmaker' ),
					'#referenzen' => __( 'Referenzen', 'textmaker' ),
					'#anfragen'   => __( 'Offerte anfragen', 'textmaker' ),
				);

				foreach ( $textmaker_fallback as $textmaker_url => $textmaker_label ) {
					printf(
						'<li><a href="%1$s">%2$s</a></li>',
						esc_url( textmaker_absolute_anchor( (string) $textmaker_url ) ),
						esc_html( $textmaker_label )
					);
				}

				echo '</ul>';
			}
			?>
		</nav>
	</div>
</header>
