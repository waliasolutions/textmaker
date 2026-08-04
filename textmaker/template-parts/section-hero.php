<?php
/**
 * Abschnitt: Hero.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_guarantee = trim( textmaker_option( 'hero_guarantee' ) );

$textmaker_hero_id  = (int) get_theme_mod( 'textmaker_hero_image', 0 );
$textmaker_hero_url = $textmaker_hero_id > 0
	? (string) wp_get_attachment_image_url( $textmaker_hero_id, 'full' )
	: '';
$textmaker_overlay  = (int) get_theme_mod( 'textmaker_hero_overlay', 55 );

$textmaker_hero_style = '' !== $textmaker_hero_url
	? sprintf(
		'--hero-bg:url(%1$s);--hero-overlay:%2$s;',
		esc_url( $textmaker_hero_url ),
		esc_attr( (string) ( $textmaker_overlay / 100 ) )
	)
	: '';

?>

<section class="hero<?php echo '' !== $textmaker_hero_url ? ' hero--image' : ''; ?>" id="top"
	<?php echo '' !== $textmaker_hero_style ? 'style="' . esc_attr( $textmaker_hero_style ) . '"' : ''; ?>>
	<div class="frame">
		<h1><?php echo wp_kses( textmaker_hero_heading_html(), array( 'u' => array() ) ); ?></h1>

		<?php if ( '' !== $textmaker_guarantee ) : ?>
			<p class="guarantee"><?php echo esc_html( $textmaker_guarantee ); ?></p>
		<?php endif; ?>

		<div class="hero-actions">
			<?php
			// Zwei Aktionen genügen: eine Hauptaktion, eine Alternative.
			for ( $textmaker_i = 1; $textmaker_i <= 2; $textmaker_i++ ) {
				list( $textmaker_label, $textmaker_target ) = textmaker_split_pair( textmaker_option( 'hero_button_' . $textmaker_i ) );

				if ( '' === $textmaker_label ) {
					continue;
				}

				printf(
					'<a class="btn%1$s" href="%2$s">%3$s</a>',
					2 === $textmaker_i ? ' btn--ghost' : '',
					esc_url( '' !== $textmaker_target ? $textmaker_target : '#' ),
					esc_html( $textmaker_label )
				);
			}
			?>
		</div>
	</div>

	<a class="scroll-cue" href="#lektorat" aria-label="<?php esc_attr_e( 'Weiter zum Lektorat-Service', 'textmaker' ); ?>">
		<svg viewBox="0 0 24.9999 32" role="presentation" aria-hidden="true"><path d="M24.8505,18.7959a.5.5,0,0,0-.707.0059L12.871,30.2773V.5a.5.5,0,0,0-1,0V30.2588L.86,18.8057A.5.5,0,1,0,.14,19.499L12.0087,31.8447l.0078.0078.001.001a.5057.5057,0,0,0,.1274.0928h.001l.001.001.0015.0009a.3267.3267,0,0,0,.0415.0176A.5029.5029,0,0,0,12.3573,32h.0288a.4941.4941,0,0,0,.1783-.0391l.02-.0078v-.001h.002l.0014-.0009a.4835.4835,0,0,0,.1358-.0967h0l.0054-.0059L24.8564,19.5029A.5.5,0,0,0,24.8505,18.7959Z"/></svg>
	</a>
</section>
