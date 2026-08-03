<?php
/**
 * Fussbereich.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_services = textmaker_option_lines( 'footer_services' );
$textmaker_address  = textmaker_option_lines( 'footer_address' );
$textmaker_email    = textmaker_option( 'footer_email' );
$textmaker_phone    = textmaker_option( 'footer_phone' );
?>

<footer class="site-foot">
	<div class="frame">
		<div class="foot-grid">
			<div>
				<h2><?php echo esc_html( textmaker_option( 'footer_services_heading' ) ); ?></h2>

				<?php if ( array() !== $textmaker_services ) : ?>
					<ul class="services">
						<?php foreach ( $textmaker_services as $textmaker_service ) : ?>
							<li><?php echo esc_html( $textmaker_service ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="contact-block">
				<div>
					<h2><?php echo esc_html( textmaker_option( 'footer_contact_heading' ) ); ?></h2>
					<address>
						<b><?php echo esc_html( textmaker_option( 'footer_company' ) ); ?></b><br>
						<?php foreach ( $textmaker_address as $textmaker_line ) : ?>
							<?php echo esc_html( $textmaker_line ); ?><br>
						<?php endforeach; ?>

						<?php if ( '' !== $textmaker_email || '' !== $textmaker_phone ) : ?>
							<br>
						<?php endif; ?>

						<?php if ( '' !== $textmaker_email ) : ?>
							<a href="mailto:<?php echo esc_attr( $textmaker_email ); ?>"><?php echo esc_html( $textmaker_email ); ?></a><br>
						<?php endif; ?>

						<?php if ( '' !== $textmaker_phone ) : ?>
							<a href="tel:<?php echo esc_attr( textmaker_option( 'footer_phone_link' ) ); ?>"><?php echo esc_html( $textmaker_phone ); ?></a>
						<?php endif; ?>
					</address>
				</div>

				<div>
					<h2><?php echo esc_html( textmaker_option( 'footer_legal_heading' ) ); ?></h2>
					<?php
					if ( has_nav_menu( 'legal' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'legal',
								'menu_class'     => 'legal',
								'container'      => false,
								'depth'          => 1,
								'fallback_cb'    => false,
							)
						);
					} else {
						echo '<ul class="legal">';

						foreach ( array( 'agb' => __( 'AGB', 'textmaker' ), 'datenschutz' => __( 'Datenschutz', 'textmaker' ), 'impressum' => __( 'Impressum', 'textmaker' ) ) as $textmaker_slug => $textmaker_label ) {
							$textmaker_page = get_page_by_path( $textmaker_slug );

							if ( ! $textmaker_page instanceof WP_Post ) {
								continue;
							}

							printf(
								'<li><a href="%1$s">%2$s</a></li>',
								esc_url( (string) get_permalink( $textmaker_page ) ),
								esc_html( $textmaker_label )
							);
						}

						echo '</ul>';
					}
					?>
				</div>
			</div>
		</div>

		<div class="foot-base">
			<span><?php echo esc_html( textmaker_option( 'footer_copyright' ) ); ?></span>
		</div>
	</div>
</footer>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true"
	aria-label="<?php esc_attr_e( 'Grossansicht', 'textmaker' ); ?>" hidden>
	<div class="lightbox-head">
		<span class="lightbox-count" data-lb-count></span>
		<button class="lbtn" type="button" data-lb-close aria-label="<?php esc_attr_e( 'Schliessen', 'textmaker' ); ?>">
			<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M14.1 3.3 12.7 1.9 8 6.6 3.3 1.9 1.9 3.3 6.6 8l-4.7 4.7 1.4 1.4L8 9.4l4.7 4.7 1.4-1.4L9.4 8z"/></svg>
		</button>
	</div>
	<div class="lightbox-stage" data-lb-stage></div>
	<div class="lightbox-foot">
		<button class="lbtn" type="button" data-lb-prev aria-label="<?php esc_attr_e( 'Vorheriges Bild', 'textmaker' ); ?>">
			<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M10.6 1.4 4 8l6.6 6.6 1.4-1.4L6.8 8l5.2-5.2z"/></svg>
		</button>
		<p class="lightbox-caption" data-lb-caption></p>
		<button class="lbtn" type="button" data-lb-next aria-label="<?php esc_attr_e( 'Nächstes Bild', 'textmaker' ); ?>">
			<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M5.4 1.4 12 8l-6.6 6.6L4 13.2 9.2 8 4 2.8z"/></svg>
		</button>
	</div>
</div>

<?php wp_footer(); ?>
</body>
</html>
