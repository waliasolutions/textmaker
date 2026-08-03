<?php
/**
 * teXtmaker – Theme-Bootstrap.
 *
 * Kompatibel mit PHP 8.1 bis 8.5. Es werden bewusst keine Funktionen oder
 * Sprachkonstrukte verwendet, die in PHP 8.5 als deprecated markiert sind:
 * keine impliziten Nullable-Parameter, keine "${var}"-Interpolation,
 * keine dynamischen Klassen-Eigenschaften.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

define( 'TEXTMAKER_VERSION', '1.0.0' );
define( 'TEXTMAKER_DIR', get_template_directory() );
define( 'TEXTMAKER_URI', get_template_directory_uri() );

/**
 * Quelldomain, aus der der Medien-Importer die Bilder übernimmt.
 * Über den Filter `textmaker_source_domain` bzw. die Theme-Option änderbar.
 */
define( 'TEXTMAKER_SOURCE_DOMAIN', 'https://www.textmaker.ch' );

require_once TEXTMAKER_DIR . '/inc/setup.php';
require_once TEXTMAKER_DIR . '/inc/post-types.php';
require_once TEXTMAKER_DIR . '/inc/customizer.php';
require_once TEXTMAKER_DIR . '/inc/template-tags.php';
require_once TEXTMAKER_DIR . '/inc/gtm.php';
require_once TEXTMAKER_DIR . '/inc/contact-form.php';

if ( is_admin() ) {
	require_once TEXTMAKER_DIR . '/inc/importer.php';
}
