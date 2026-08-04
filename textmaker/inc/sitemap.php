<?php
/**
 * Sitemap unter /sitemap.xml.
 *
 * WordPress bringt zwar /wp-sitemap.xml mit, doch die meisten Werkzeuge und
 * Suchmaschinen suchen zuerst unter /sitemap.xml. Statt eine Datei ins
 * Wurzelverzeichnis zu legen — die bei jeder neuen Seite veralten würde —
 * wird die Adresse dynamisch bedient. Für die Aussenwelt liegt sie damit
 * genau dort, wo sie erwartet wird, ist aber immer aktuell.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Adresse /sitemap.xml anmelden.
 */
function textmaker_sitemap_rewrite(): void {
	add_rewrite_rule( '^sitemap\.xml$', 'index.php?textmaker_sitemap=1', 'top' );
}
add_action( 'init', 'textmaker_sitemap_rewrite' );

/**
 * Eigene Abfragevariable bekannt machen.
 *
 * @param array<int, string> $vars Bestehende Variablen.
 * @return array<int, string>
 */
function textmaker_sitemap_query_var( array $vars ): array {
	$vars[] = 'textmaker_sitemap';

	return $vars;
}
add_filter( 'query_vars', 'textmaker_sitemap_query_var' );

/**
 * Sitemap ausliefern.
 */
function textmaker_render_sitemap(): void {
	if ( '1' !== (string) get_query_var( 'textmaker_sitemap' ) ) {
		return;
	}

	$entries = textmaker_sitemap_entries();

	header( 'Content-Type: application/xml; charset=UTF-8' );
	header( 'X-Robots-Tag: noindex, follow' );

	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

	foreach ( $entries as $entry ) {
		echo "\t<url>\n";
		printf( "\t\t<loc>%s</loc>\n", esc_url( $entry['loc'] ) );

		if ( '' !== $entry['lastmod'] ) {
			printf( "\t\t<lastmod>%s</lastmod>\n", esc_html( $entry['lastmod'] ) );
		}

		printf( "\t\t<changefreq>%s</changefreq>\n", esc_html( $entry['changefreq'] ) );
		printf( "\t\t<priority>%s</priority>\n", esc_html( $entry['priority'] ) );
		echo "\t</url>\n";
	}

	echo '</urlset>';
	exit;
}
add_action( 'template_redirect', 'textmaker_render_sitemap', 1 );

/**
 * Einträge der Sitemap zusammenstellen.
 *
 * Aufgenommen werden die Startseite sowie alle veröffentlichten, indexierbaren
 * Seiten und Beiträge. Die internen Inhaltstypen des Themes bleiben draussen —
 * sie haben keine eigenen Adressen.
 *
 * @return array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}>
 */
function textmaker_sitemap_entries(): array {
	$front_id = (int) get_option( 'page_on_front' );

	$entries = array(
		array(
			'loc'        => home_url( '/' ),
			'lastmod'    => $front_id > 0 ? get_post_modified_time( 'c', true, $front_id ) : '',
			'changefreq' => 'weekly',
			'priority'   => '1.0',
		),
	);

	$posts = get_posts(
		array(
			'post_type'        => array( 'page', 'post' ),
			'post_status'      => 'publish',
			'posts_per_page'   => 500,
			'orderby'          => 'modified',
			'order'            => 'DESC',
			'suppress_filters' => false,
		)
	);

	foreach ( $posts as $post ) {
		if ( (int) $post->ID === $front_id ) {
			continue;
		}

		// Von der Indexierung ausgenommene Seiten gehören nicht in die Sitemap.
		if ( '1' === (string) get_post_meta( $post->ID, '_tm_noindex', true ) ) {
			continue;
		}

		if ( 'private' === $post->post_status || post_password_required( $post ) ) {
			continue;
		}

		$entries[] = array(
			'loc'        => (string) get_permalink( $post ),
			'lastmod'    => (string) get_post_modified_time( 'c', true, $post ),
			'changefreq' => 'page' === $post->post_type ? 'monthly' : 'weekly',
			'priority'   => 'page' === $post->post_type ? '0.8' : '0.6',
		);
	}

	/**
	 * Einträge der Sitemap anpassen.
	 *
	 * @param array<int, array{loc: string, lastmod: string, changefreq: string, priority: string}> $entries Einträge.
	 */
	return (array) apply_filters( 'textmaker_sitemap_entries', $entries );
}

/**
 * Sitemap in der robots.txt bekannt geben.
 *
 * @param string $output Bisheriger Inhalt.
 * @param string $public Ob die Website öffentlich ist.
 */
function textmaker_robots_txt( string $output, string $public ): string {
	if ( '1' !== $public ) {
		return $output;
	}

	return $output . "\nSitemap: " . home_url( '/sitemap.xml' ) . "\n";
}
add_filter( 'robots_txt', 'textmaker_robots_txt', 10, 2 );

/**
 * Umschreiberegeln neu aufbauen.
 *
 * Ohne das liefert /sitemap.xml einen 404, bis jemand die Permalinks speichert.
 * Aufgerufen wird die Funktion aus textmaker_run_setup() in inc/activation.php —
 * dort, wo auch Startseite und Menüs eingerichtet werden.
 */
function textmaker_flush_rewrites(): void {
	textmaker_sitemap_rewrite();
	flush_rewrite_rules();
}
