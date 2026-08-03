<?php
/**
 * Abschnitt: Lektorat-Service.
 *
 * Zweispaltig: links die Aussage, rechts die Liste. So bleibt keine leere
 * Fläche stehen, und auf schmalen Geräten stapelt sich beides sauber.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_items   = textmaker_option_lines( 'service_items' );
$textmaker_eyebrow = textmaker_option( 'service_eyebrow' );
?>

<section class="band" id="lektorat">
	<div class="frame">
		<div class="service-grid">
			<div class="service-intro" data-reveal>
				<?php if ( '' !== $textmaker_eyebrow ) : ?>
					<p class="eyebrow"><?php echo esc_html( $textmaker_eyebrow ); ?></p>
				<?php endif; ?>
				<h2><?php echo esc_html( textmaker_option( 'service_heading' ) ); ?></h2>
			</div>

			<?php if ( array() !== $textmaker_items ) : ?>
				<ul class="checks" data-reveal>
					<?php foreach ( $textmaker_items as $textmaker_item ) : ?>
						<li class="check">
							<span class="tick" aria-hidden="true">
								<svg viewBox="0 0 16 16"><path d="M6.2 12.5 1.8 8.1l1.4-1.4 3 3 6.6-6.6 1.4 1.4z"/></svg>
							</span>
							<span class="check-text"><?php echo esc_html( $textmaker_item ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
