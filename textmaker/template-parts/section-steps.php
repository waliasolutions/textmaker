<?php
/**
 * Abschnitt: Ablauf der Korrektur.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_steps = textmaker_get_items( 'tm_step', 20 );

if ( array() === $textmaker_steps ) {
	return;
}

$textmaker_intro = textmaker_option( 'steps_intro' );
$textmaker_total = count( $textmaker_steps );
?>

<section class="band" id="ablauf">
	<div class="frame">
		<div class="section-head" data-reveal>
			<h2><?php echo esc_html( textmaker_option( 'steps_heading' ) ); ?></h2>
			<?php if ( '' !== $textmaker_intro ) : ?>
				<p><?php echo esc_html( $textmaker_intro ); ?></p>
			<?php endif; ?>
		</div>

		<div class="carousel carousel--steps" data-carousel data-reveal>
			<div class="carousel-track" data-track tabindex="0" role="group"
				aria-label="<?php echo esc_attr( sprintf( /* translators: %d: Anzahl Schritte. */ __( 'Ablauf der Korrektur, %d Schritte', 'textmaker' ), $textmaker_total ) ); ?>">
				<?php
				foreach ( $textmaker_steps as $textmaker_index => $textmaker_step ) :
					$textmaker_id      = (int) get_post_thumbnail_id( $textmaker_step );
					$textmaker_caption = (string) get_post_meta( $textmaker_step->ID, '_tm_caption', true );

					if ( '' === $textmaker_caption ) {
						$textmaker_caption = get_the_title( $textmaker_step );
					}

					if ( 0 === $textmaker_id ) {
						continue;
					}

					$textmaker_full = (string) wp_get_attachment_image_url( $textmaker_id, 'full' );
					?>
					<button class="step" type="button" data-lightbox
						data-full="<?php echo esc_url( $textmaker_full ); ?>"
						data-caption="<?php echo esc_attr( $textmaker_caption ); ?>">
						<span class="step-frame">
							<?php
							echo wp_get_attachment_image(
								$textmaker_id,
								'textmaker-step',
								false,
								array(
									'alt'     => $textmaker_caption,
									'loading' => $textmaker_index < 2 ? 'eager' : 'lazy',
								)
							);
							?>
						</span>
						<span class="step-caption"><b><?php echo esc_html( $textmaker_caption ); ?></b></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="carousel-nav">
				<button class="cbtn" type="button" data-prev aria-label="<?php esc_attr_e( 'Vorheriger Schritt', 'textmaker' ); ?>">
					<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M10.6 1.4 4 8l6.6 6.6 1.4-1.4L6.8 8l5.2-5.2z"/></svg>
				</button>
				<button class="cbtn" type="button" data-next aria-label="<?php esc_attr_e( 'Nächster Schritt', 'textmaker' ); ?>">
					<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M5.4 1.4 12 8l-6.6 6.6L4 13.2 9.2 8 4 2.8z"/></svg>
				</button>
				<span class="carousel-hint"><?php esc_html_e( 'Zum Vergrössern klicken', 'textmaker' ); ?></span>
			</div>
		</div>
	</div>
</section>
