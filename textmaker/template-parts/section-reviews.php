<?php
/**
 * Abschnitt: Kundenmeinungen.
 *
 * Bevorzugt wird das bestehende Elfsight-Widget mit den Google-Rezensionen.
 * Ist keine App-ID hinterlegt, greift die manuell gepflegte Liste.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_app_id = trim( textmaker_option( 'reviews_elfsight' ) );
$textmaker_native = '' === $textmaker_app_id ? textmaker_get_items( 'tm_testimonial', 12 ) : array();

if ( '' === $textmaker_app_id && array() === $textmaker_native ) {
	return;
}
?>

<section class="band band--shell" id="rezensionen">
	<div class="frame">
		<div class="section-head" data-reveal>
			<h2><?php echo esc_html( textmaker_option( 'reviews_heading' ) ); ?></h2>
		</div>

		<?php if ( '' !== $textmaker_app_id ) : ?>
			<div class="reviews-embed" data-reveal>
				<div class="elfsight-app-<?php echo esc_attr( $textmaker_app_id ); ?>" data-elfsight-app-lazy></div>
			</div>
		<?php else : ?>
			<div class="reviews" data-reveal>
				<?php
				foreach ( $textmaker_native as $textmaker_review ) :
					$textmaker_author = (string) get_post_meta( $textmaker_review->ID, '_tm_author', true );
					$textmaker_source = (string) get_post_meta( $textmaker_review->ID, '_tm_source', true );
					$textmaker_rating = (int) get_post_meta( $textmaker_review->ID, '_tm_rating', true );

					if ( '' === $textmaker_author ) {
						$textmaker_author = get_the_title( $textmaker_review );
					}
					?>
					<figure class="review">
						<?php if ( $textmaker_rating > 0 ) : ?>
							<div class="stars" aria-label="
								<?php
								printf(
									/* translators: %d: Anzahl Sterne. */
									esc_attr__( '%d von 5 Sternen', 'textmaker' ),
									$textmaker_rating
								);
								?>
							"><?php echo esc_html( str_repeat( '★', min( 5, $textmaker_rating ) ) ); ?></div>
						<?php endif; ?>

						<blockquote><?php echo esc_html( wp_strip_all_tags( (string) $textmaker_review->post_content ) ); ?></blockquote>

						<figcaption>
							<b><?php echo esc_html( $textmaker_author ); ?></b>
							<?php if ( '' !== $textmaker_source ) : ?>
								· <?php echo esc_html( $textmaker_source ); ?>
							<?php endif; ?>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
