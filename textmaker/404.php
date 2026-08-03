<?php
/**
 * Fehlerseite.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="inhalt">
	<section class="band page-body">
		<div class="frame">
			<div class="section-head">
				<h1><?php esc_html_e( 'Diese Seite gibt es nicht', 'textmaker' ); ?></h1>
				<p><?php esc_html_e( 'Der Link führt ins Leere. Über das Menü findest du zurück — oder schreib uns direkt.', 'textmaker' ); ?></p>
			</div>

			<p style="margin-top:2rem;">
				<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Zur Startseite', 'textmaker' ); ?>
				</a>
			</p>
		</div>
	</section>
</main>

<?php
get_footer();
