<?php
/**
 * Abschnitt: Referenzen samt Logo-Reihe.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_refs  = textmaker_get_items( 'tm_reference', 60 );
$textmaker_logos = textmaker_get_items( 'tm_logo', 20 );

if ( array() === $textmaker_refs && array() === $textmaker_logos ) {
	return;
}

$textmaker_count = count( $textmaker_refs );
?>

<section class="band band--shell" id="referenzen">
	<div class="frame">
		<div class="section-head" data-reveal>
			<h2><?php echo esc_html( textmaker_option( 'refs_heading' ) ); ?></h2>
		</div>

		<?php if ( array() !== $textmaker_refs ) : ?>
			<div class="carousel carousel--refs" data-carousel data-reveal>
				<div class="carousel-track" data-track tabindex="0" role="group"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %d: Anzahl Arbeiten. */ __( 'Referenzen, %d Arbeiten', 'textmaker' ), $textmaker_count ) ); ?>">
					<?php
					foreach ( $textmaker_refs as $textmaker_ref ) :
						$textmaker_id = (int) get_post_thumbnail_id( $textmaker_ref );

						if ( 0 === $textmaker_id ) {
							continue;
						}

						$textmaker_title       = get_the_title( $textmaker_ref );
						$textmaker_publication = (string) get_post_meta( $textmaker_ref->ID, '_tm_publication', true );
						$textmaker_byline      = (string) get_post_meta( $textmaker_ref->ID, '_tm_byline', true );

						$textmaker_caption = $textmaker_title;

						if ( '' !== $textmaker_publication ) {
							$textmaker_caption = $textmaker_publication . ' — ' . $textmaker_title;
						}

						if ( '' !== $textmaker_byline ) {
							$textmaker_caption .= ' · ' . $textmaker_byline;
						}
						?>
						<button class="ref" type="button" data-lightbox
							data-full="<?php echo esc_url( (string) wp_get_attachment_image_url( $textmaker_id, 'full' ) ); ?>"
							data-caption="<?php echo esc_attr( $textmaker_caption ); ?>">
							<span class="ref-tile">
								<?php
								echo wp_get_attachment_image(
									$textmaker_id,
									'textmaker-reference',
									false,
									array(
										'alt'     => $textmaker_title,
										'loading' => 'lazy',
									)
								);
								?>
							</span>
							<span class="ref-meta">
								<?php if ( '' !== $textmaker_publication ) : ?>
									<span class="pub"><?php echo esc_html( $textmaker_publication ); ?></span>
								<?php endif; ?>
								<span class="headline"><?php echo esc_html( $textmaker_title ); ?></span>
								<?php if ( '' !== $textmaker_byline ) : ?>
									<span class="ref-byline"><?php echo esc_html( $textmaker_byline ); ?></span>
								<?php endif; ?>
							</span>
						</button>
					<?php endforeach; ?>
				</div>

				<div class="carousel-nav">
					<button class="cbtn" type="button" data-prev aria-label="<?php esc_attr_e( 'Vorherige Referenzen', 'textmaker' ); ?>">
						<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M10.6 1.4 4 8l6.6 6.6 1.4-1.4L6.8 8l5.2-5.2z"/></svg>
					</button>
					<button class="cbtn" type="button" data-next aria-label="<?php esc_attr_e( 'Weitere Referenzen', 'textmaker' ); ?>">
						<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M5.4 1.4 12 8l-6.6 6.6L4 13.2 9.2 8 4 2.8z"/></svg>
					</button>
					<span class="carousel-hint">
						<?php
						printf(
							/* translators: %d: Anzahl Arbeiten. */
							esc_html( _n( '%d Arbeit', '%d Arbeiten', $textmaker_count, 'textmaker' ) ),
							(int) $textmaker_count
						);
						?>
					</span>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( array() !== $textmaker_logos ) : ?>
			<div class="logos" data-reveal>
				<?php
				foreach ( $textmaker_logos as $textmaker_logo ) :
					$textmaker_id   = (int) get_post_thumbnail_id( $textmaker_logo );
					$textmaker_name = get_the_title( $textmaker_logo );
					$textmaker_link = (string) get_post_meta( $textmaker_logo->ID, '_tm_link', true );

					$textmaker_markup = 0 !== $textmaker_id
						? wp_get_attachment_image(
							$textmaker_id,
							'medium',
							false,
							array(
								'alt'     => $textmaker_name,
								'class'   => 'logo-img',
								'loading' => 'lazy',
							)
						)
						: '<span class="logo-text">' . esc_html( $textmaker_name ) . '</span>';

					if ( '' !== $textmaker_link ) {
						printf(
							'<a class="logo" href="%1$s" target="_blank" rel="noopener">%2$s</a>',
							esc_url( $textmaker_link ),
							wp_kses_post( $textmaker_markup )
						);
					} else {
						printf( '<span class="logo">%s</span>', wp_kses_post( $textmaker_markup ) );
					}
				endforeach;
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
