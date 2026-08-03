<?php
/**
 * Suchformular.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;
?>
<form class="searchform" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="suche"><?php esc_html_e( 'Suchen', 'textmaker' ); ?></label>
	<input type="search" id="suche" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Suchbegriff …', 'textmaker' ); ?>">
	<button class="btn" type="submit"><?php esc_html_e( 'Suchen', 'textmaker' ); ?></button>
</form>
