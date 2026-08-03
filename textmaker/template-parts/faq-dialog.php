<?php
/**
 * Fragen & Antworten als Dialog in der Fusszeile.
 *
 * Auf der Seite selbst wäre der Block redundant — Preise und Ablauf stehen
 * bereits dort. Im Dialog bleibt der Text aber im Dokument und damit für
 * Suchmaschinen und Antwortmaschinen lesbar; die FAQPage-Auszeichnung in
 * inc/seo.php greift unverändert.
 *
 * Das native <dialog>-Element bringt Fokusfalle, Escape und Hintergrund
 * bereits mit — dafür braucht es kein eigenes Skript.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_faq = textmaker_get_items( 'tm_faq', 30 );

if ( array() === $textmaker_faq ) {
	return;
}

$textmaker_intro = textmaker_option( 'faq_intro' );
?>

<dialog class="faq-dialog" id="faq-dialog" aria-labelledby="faq-dialog-title">
	<div class="faq-dialog-head">
		<h2 id="faq-dialog-title"><?php echo esc_html( textmaker_option( 'faq_heading' ) ); ?></h2>
		<button class="lbtn" type="button" data-faq-close
			aria-label="<?php esc_attr_e( 'Schliessen', 'textmaker' ); ?>">
			<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M14.1 3.3 12.7 1.9 8 6.6 3.3 1.9 1.9 3.3 6.6 8l-4.7 4.7 1.4 1.4L8 9.4l4.7 4.7 1.4-1.4L9.4 8z"/></svg>
		</button>
	</div>

	<div class="faq-dialog-body">
		<?php if ( '' !== $textmaker_intro ) : ?>
			<p class="faq-dialog-intro"><?php echo esc_html( $textmaker_intro ); ?></p>
		<?php endif; ?>

		<div class="faq">
			<?php foreach ( $textmaker_faq as $textmaker_item ) : ?>
				<details class="faq-item">
					<summary>
						<span><?php echo esc_html( get_the_title( $textmaker_item ) ); ?></span>
						<svg class="faq-mark" viewBox="0 0 16 16" aria-hidden="true">
							<path d="M7 2h2v12H7z"/><path d="M2 7h12v2H2z"/>
						</svg>
					</summary>
					<div class="faq-answer">
						<?php echo wp_kses_post( wpautop( (string) $textmaker_item->post_content ) ); ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</dialog>
