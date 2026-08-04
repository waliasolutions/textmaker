<?php
/**
 * Sitemap unter /sitemap.xml.
 *
 * WordPress bringt zwar /wp-sitemap.xml mit, doch die meisten Werkzeuge und
 * Suchmaschinen suchen zuerst unter /sitemap.xml. Statt eine Datei ins
 * Wurzelverzeichnis zu legen — die bei jeder neuen Seite veralten würde —
 * wird die Adresse dynamisch bedient.
 *
 * Bewusst ohne eigene Umschreiberegel: Eine Regel müsste beim Aktivieren
 * eingespielt werden, und ein fehlgeschlagener Durchlauf hinterlässt einen
 * halben Regelsatz — dann liefern plötzlich auch normale Seiten einen 404.
 * Die Anfrage wird deshalb einfach am Pfad erkannt, bevor WordPress zu
 * routen beginnt. Das kommt ohne jeden Eingriff in die Permalinks aus.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Angefragten Pfad relativ zur WordPress-Installation ermitteln.
 *
 * Berücksichtigt Installationen in einem Unterverzeichnis.
 */
function textmaker_request_path(): string {
	$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( (string) $_SERVER['REQUEST_URI'] ) ) : '';
	$path    = (string) wp_parse_url( $request, PHP_URL_PATH );
	$path    = trim( $path, '/' );

	$base = trim( (string) wp_parse_url( home_url(), PHP_URL_PATH ), '/' );

	if ( '' !== $base && str_starts_with( $path, $base ) ) {
		$path = trim( substr( $path, strlen( $base ) ), '/' );
	}

	return $path;
}

/**
 * Sitemap ausliefern, wenn sie angefragt wurde.
 */
function textmaker_maybe_render_sitemap(): void {
	if ( is_admin() || 'sitemap.xml' !== textmaker_request_path() ) {
		return;
	}

	$entries = textmaker_sitemap_entries();

	if ( ! headers_sent() ) {
		header( 'Content-Type: application/xml; charset=UTF-8' );
		header( 'X-Robots-Tag: noindex, follow' );
	}

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
add_action( 'parse_request', 'textmaker_maybe_render_sitemap', 1 );

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
			'lastmod'    => $front_id > 0 ? (string) get_post_modified_time( 'c', true, $front_id ) : '',
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

		if ( '1' === (string) get_post_meta( $post->ID, '_tm_noindex', true ) ) {
			continue;
		}

		if ( post_password_required( $post ) ) {
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
