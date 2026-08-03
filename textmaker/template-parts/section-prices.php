<?php
/**
 * Abschnitt: Lektorate / Preise.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_allowed = textmaker_allowed_prose_html();
$textmaker_rates   = textmaker_option_lines( 'prices_rates' );
?>

<section class="band band--shell" id="preise">
	<div class="frame">
		<div class="section-head" data-reveal>
			<h2><?php echo esc_html( textmaker_option( 'prices_heading' ) ); ?></h2>
		</div>

		<div class="two-col">
			<div class="prose" data-reveal>
				<?php echo wp_kses( textmaker_format_prose( textmaker_option( 'prices_left' ) ), $textmaker_allowed ); ?>
			</div>

			<div class="prose" data-reveal>
				<?php echo wp_kses( textmaker_format_prose( textmaker_option( 'prices_right' ) ), $textmaker_allowed ); ?>

				<?php if ( array() !== $textmaker_rates ) : ?>
					<h4><?php esc_html_e( 'Richtpreise', 'textmaker' ); ?></h4>
					<div class="rate-card">
						<?php
						foreach ( $textmaker_rates as $textmaker_rate ) :
							list( $textmaker_label, $textmaker_amount ) = textmaker_split_pair( $textmaker_rate );
							?>
							<div class="rate">
								<span><?php echo esc_html( $textmaker_label ); ?></span>
								<b><?php echo esc_html( $textmaker_amount ); ?></b>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
