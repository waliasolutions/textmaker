<?php
/**
 * Abschnitt: Fragen & Antworten.
 *
 * Zusätzlich zur Darstellung werden diese Inhalte in inc/seo.php als
 * FAQPage ausgeliefert — daraus zitieren Antwortmaschinen direkt.
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

<section class="band" id="fragen">
	<div class="frame">
		<div class="section-head" data-reveal>
			<h2><?php echo esc_html( textmaker_option( 'faq_heading' ) ); ?></h2>
			<?php if ( '' !== $textmaker_intro ) : ?>
				<p><?php echo esc_html( $textmaker_intro ); ?></p>
			<?php endif; ?>
		</div>

		<div class="faq" data-reveal>
			<?php foreach ( $textmaker_faq as $textmaker_index => $textmaker_item ) : ?>
				<details class="faq-item"<?php echo 0 === $textmaker_index ? ' open' : ''; ?>>
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
</section>
