<?php
/**
 * Startseite.
 *
 * Die Reihenfolge der Abschnitte entspricht der bisherigen Website. Welche
 * Abschnitte erscheinen, steuert der Customizer.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="inhalt">
	<?php
	get_template_part( 'template-parts/section', 'hero' );

	if ( textmaker_show_section( 'service' ) ) {
		get_template_part( 'template-parts/section', 'service' );
	}

	if ( textmaker_show_section( 'reviews' ) ) {
		get_template_part( 'template-parts/section', 'reviews' );
	}

	if ( textmaker_show_section( 'steps' ) ) {
		get_template_part( 'template-parts/section', 'steps' );
	}

	if ( textmaker_show_section( 'prices' ) ) {
		get_template_part( 'template-parts/section', 'prices' );
	}

	if ( textmaker_show_section( 'faq' ) ) {
		get_template_part( 'template-parts/section', 'faq' );
	}

	if ( textmaker_show_section( 'contact' ) ) {
		get_template_part( 'template-parts/section', 'contact' );
	}

	if ( textmaker_show_section( 'refs' ) ) {
		get_template_part( 'template-parts/section', 'references' );
	}
	?>
</main>

<?php
get_footer();
