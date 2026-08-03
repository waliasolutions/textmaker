<?php
/**
 * Abschnitt: Lektorat-Service.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_items   = textmaker_option_lines( 'service_items' );
$textmaker_eyebrow = textmaker_option( 'service_eyebrow' );
?>

<section class="band" id="lektorat">
	<div class="frame">
		<div class="section-head" data-reveal>
			<?php if ( '' !== $textmaker_eyebrow ) : ?>
				<p class="eyebrow"><?php echo esc_html( $textmaker_eyebrow ); ?></p>
			<?php endif; ?>
			<h2><?php echo esc_html( textmaker_option( 'service_heading' ) ); ?></h2>
		</div>

		<?php if ( array() !== $textmaker_items ) : ?>
			<div class="checks" data-reveal>
				<?php foreach ( $textmaker_items as $textmaker_item ) : ?>
					<div class="check">
						<span class="tick">
							<svg viewBox="0 0 16 16" aria-hidden="true"><path d="M6.2 12.5 1.8 8.1l1.4-1.4 3 3 6.6-6.6 1.4 1.4z"/></svg>
						</span>
						<p><?php echo esc_html( $textmaker_item ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
