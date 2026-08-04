<?php
/**
 * Import aus der Live-Domain.
 *
 * Holt Logo, Team-Porträts, Ablauf-Screenshots, Referenzen und Referenz-Logos
 * von www.textmaker.ch in die Mediathek und legt die zugehörigen Einträge an.
 * Zusätzlich lassen sich die Texte der rechtlichen Seiten übernehmen.
 *
 * Bereits importierte Dateien werden übersprungen, der Import ist also
 * beliebig oft wiederholbar.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

const TEXTMAKER_IMPORT_BATCH = 6;
const TEXTMAKER_IMPORT_MAP   = 'textmaker_imported_media';

/**
 * Stylesheet der alten Startseite und Kennung des Hero-Abschnitts.
 *
 * Das Hintergrundbild des Heros steht nicht im Seiten-HTML, sondern im vom
 * Page-Builder erzeugten Stylesheet. Von dort wird es ausgelesen.
 */
const TEXTMAKER_HERO_CSS     = '/wp-content/uploads/elementor/css/post-157.css';
const TEXTMAKER_HERO_ELEMENT = 'elementor-element-7afdeda7';
const TEXTMAKER_HERO_KEY     = 'hero-background';

/**
 * Quelldomain des Imports.
 */
function textmaker_source_domain(): string {
	$domain = (string) apply_filters( 'textmaker_source_domain', TEXTMAKER_SOURCE_DOMAIN );

	return untrailingslashit( $domain );
}

/**
 * Alle bekannten Medien der Live-Seite.
 *
 * @return array<int, array{group: string, path: string, title: string, meta?: array<string, string>, order?: int}>
 */
function textmaker_import_manifest(): array {
	$items = array();

	// Logo der Website.
	$items[] = array(
		'group' => 'logo',
		'path'  => '/wp-content/uploads/2022/08/logo-textmaker-white.png',
		'title' => 'teXtmaker Logo',
	);

	// Website-Symbol (Favicon). WordPress erwartet mindestens 512 px und
	// erzeugt die kleineren Grössen selbst.
	$items[] = array(
		'group' => 'favicon',
		'path'  => '/wp-content/uploads/2022/08/cropped-logo-textmaker-white-270x270.png',
		'title' => 'teXtmaker Website-Symbol',
	);

	// Ablauf der Korrektur.
	$steps = array(
		array( '/wp-content/uploads/2023/03/slider-oben-1.png', 'Dein Text mit Fehlern' ),
		array( '/wp-content/uploads/2023/03/slider-oben-2.png', 'Unsere Korrekturen – für dich klar sichtbar' ),
		array( '/wp-content/uploads/2023/03/slider-oben-3.png', 'Du kannst den Text markieren …' ),
		array( '/wp-content/uploads/2023/03/slider-oben-5.png', '… und unsere Änderungen annehmen …' ),
		array( '/wp-content/uploads/2023/03/slider-oben-6.png', '… dafür kannst du auch «Markup: keine» einstellen' ),
		array( '/wp-content/uploads/2023/03/slider-oben-7.1.png', 'Dein korrigierter Text in der Endversion' ),
		array( '/wp-content/uploads/2023/03/slider-oben-8.png', 'Bearbeite den Text aufgrund unserer Kommentare' ),
	);

	foreach ( $steps as $index => $step ) {
		$items[] = array(
			'group' => 'tm_step',
			'path'  => $step[0],
			'title' => $step[1],
			'order' => ( $index + 1 ) * 10,
			'meta'  => array( '_tm_caption' => $step[1] ),
		);
	}

	// Team.
	$team = array(
		array( '/wp-content/uploads/elementor/thumbs/daniel-dubouloz-bw-q5l69xa22yu62540teazjfco5uqpunhorp4wptbbrg.jpg', 'Daniel Dubouloz', 'Founder: Lektorate, DE / FR' ),
		array( '/wp-content/uploads/elementor/thumbs/nathalie-perret-q3em2r3gb7jkcvbuwq92jmozm2rii3betdwxphond8.jpeg', 'Nathalie Perret', 'Lektorate, Deutschlehrerin' ),
		array( '/wp-content/uploads/elementor/thumbs/laura_eggimann-q3embrdrt1vhmc8tb4fcxuv0k0d59o2t1yzg9ybtqk.jpg', 'Laura Eggimann', 'Lektorate' ),
		array( '/wp-content/uploads/elementor/thumbs/sara-q8oktpb743vc6xfebcmx2ox3m16uw37d594vvnz70s.jpg', 'Sara Schindler', 'Formatierung' ),
		array( '/wp-content/uploads/elementor/thumbs/sarahjossi-textmaker-q8qd57wcwnmylo0ebquevvo4juazlun6hzu6lgql3g.jpg', 'Sarah Jossi', 'Lektorate, inkl. Bücher-Lektorate' ),
		array( '/wp-content/uploads/elementor/thumbs/rahel-textmaker-q8ocqu549pp3ivxcrmezi3a61mtwzwhjtlntp78wgc.jpg', 'Rahel Schneebeli', 'Lektorate, EN-Lektorate' ),
	);

	foreach ( $team as $index => $member ) {
		$items[] = array(
			'group' => 'tm_team',
			'path'  => $member[0],
			'title' => $member[1],
			'order' => ( $index + 1 ) * 10,
			'meta'  => array( '_tm_role' => $member[2] ),
		);
	}

	// Referenzen.
	$references = array(
		array( '/wp-content/uploads/2023/02/cockpit-stratolaunch-daniel-dubouloz-1.jpg', 'Im Cockpit der Stratolaunch', 'Cockpit', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/bewegungplus-online-abenteuer-mit-gott-ausbildung-zum-missionspiloten-daniel-dubouloz-1.jpg', 'Abenteuer mit Gott: Ausbildung zum Missionspiloten', 'BewegungPlus', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/aerorevue-boeing-787-dreamliner-daniel-dubouloz-1.jpg', 'Boeing 787 Dreamliner', 'AeroRevue', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/aerorevue-boeing-787-dreamliner-daniel-dubouloz-2.jpg', 'Boeing 787 Dreamliner (2)', 'AeroRevue', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/aerorevue-dem-hagel-auf-der-spur-daniel-dubouloz-1.jpg', 'Dem Hagel auf der Spur', 'AeroRevue', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/aerorevue-dem-hagel-auf-der-spur-daniel-dubouloz-2.jpg', 'Dem Hagel auf der Spur (2)', 'AeroRevue', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/nzz-konventionelle-technik-als-beste-option-daniel-dubouloz-1.jpg', 'Konventionelle Technik als beste Option', 'NZZ', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/nzz-vernunftsbau-als-musterprojekt-daniel-dubouloz-1.jpg', 'Vernunftsbau als Musterprojekt', 'NZZ', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/nzz-lehren-aus-dem-ungluck-von-air-france-447-daniel-dubouloz-1.jpg', 'Lehren aus dem Unglück von Air France 447', 'NZZ', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/nzz-goldgraberstimmung-am-fernostlichen-himmel-nzz-daniel-dubouloz.jpg', 'Goldgräberstimmung am fernöstlichen Himmel', 'NZZ', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/nzz-neue-sterne-am-japanischen-himmel-daniel-dubouloz-1.jpg', 'Neue Sterne am japanischen Himmel', 'NZZ', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/flying-for-life-2-20_d_web-2.jpg', 'Ausgabe 2/20', 'Flying for Life', '' ),
		array( '/wp-content/uploads/2023/02/flying-for-life-1-20_d-2.jpg', 'Ausgabe 1/20', 'Flying for Life', '' ),
		array( '/wp-content/uploads/2023/02/flying-for-life-3-20_d-2.jpg', 'Ausgabe 3/20', 'Flying for Life', '' ),
		array( '/wp-content/uploads/2023/02/aerorevue-der-luftbruckenbauer-daniel-dubouloz-1.jpg', 'Der Luftbrückenbauer', 'AeroRevue', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/4-teens-fata-morgana-laura-eggimann-1.jpg', 'Fata Morgana', '4teens', 'Laura Eggimann' ),
		array( '/wp-content/uploads/2023/02/4-teens-kolonialismus-laura-eggimann-1.jpg', 'Kolonialismus', '4teens', 'Laura Eggimann' ),
		array( '/wp-content/uploads/2023/02/4-teens-polarlichter-laura-eggimann-1.jpg', 'Polarlichter', '4teens', 'Laura Eggimann' ),
		array( '/wp-content/uploads/2023/02/aerorevue-maf-flugzeug-verkehrshaus-daniel-dubouloz-1.jpg', 'MAF-Flugzeug im Verkehrshaus', 'AeroRevue', 'Daniel Dubouloz' ),
		array( '/wp-content/uploads/2023/02/flying-for-life-1-21_d-doppelseiten-2.jpg', 'Ausgabe 1/21 — Doppelseiten', 'Flying for Life', '' ),
		array( '/wp-content/uploads/2023/02/flying-for-life-2-21_d-2.jpg', 'Ausgabe 2/21', 'Flying for Life', '' ),
		array( '/wp-content/uploads/2023/02/flying-for-life-4-20_d-doppelseiten-2.jpg', 'Ausgabe 4/20 — Doppelseiten', 'Flying for Life', '' ),
	);

	foreach ( $references as $index => $reference ) {
		$items[] = array(
			'group' => 'tm_reference',
			'path'  => $reference[0],
			'title' => $reference[1],
			'order' => ( $index + 1 ) * 10,
			'meta'  => array(
				'_tm_publication' => $reference[2],
				'_tm_byline'      => $reference[3],
			),
		);
	}

	// Referenz-Logos.
	$logos = array(
		array( '/wp-content/uploads/2023/02/logo-nzz.png', 'NZZ' ),
		array( '/wp-content/uploads/2023/03/4teens-logo.webp', '4teens' ),
		array( '/wp-content/uploads/2023/02/logo-maf-1024x403.png', 'MAF' ),
		array( '/wp-content/uploads/2023/02/logo-cockpit.png', 'Cockpit' ),
		array( '/wp-content/uploads/2023/02/logo-aerorevue.png', 'AeroRevue' ),
	);

	foreach ( $logos as $index => $logo ) {
		$items[] = array(
			'group' => 'tm_logo',
			'path'  => $logo[0],
			'title' => $logo[1],
			'order' => ( $index + 1 ) * 10,
		);
	}

	return $items;
}

/**
 * Import-Seite im Backend registrieren.
 */
function textmaker_import_menu(): void {
	add_submenu_page(
		TEXTMAKER_MENU_SLUG,
		__( 'Bilder importieren', 'textmaker' ),
		__( 'Bilder importieren', 'textmaker' ),
		'upload_files',
		'textmaker-import',
		'textmaker_render_import_page'
	);
}
add_action( 'admin_menu', 'textmaker_import_menu', 20 );

/**
 * Zuordnung Quellpfad → Anhang-ID.
 *
 * @return array<string, int>
 */
function textmaker_import_map(): array {
	$map = get_option( TEXTMAKER_IMPORT_MAP, array() );

	return is_array( $map ) ? $map : array();
}

/**
 * Import-Seite ausgeben und Aktionen ausführen.
 */
function textmaker_render_import_page(): void {
	if ( ! current_user_can( 'upload_files' ) ) {
		wp_die( esc_html__( 'Dafür fehlen dir die nötigen Rechte.', 'textmaker' ) );
	}

	$notices = array();

	if ( isset( $_POST['textmaker_import_action'] ) ) {
		check_admin_referer( 'textmaker_import' );

		$action = sanitize_key( wp_unslash( (string) $_POST['textmaker_import_action'] ) );

		if ( 'media' === $action ) {
			$notices = textmaker_run_media_import();
		} elseif ( 'legal' === $action ) {
			$notices = textmaker_import_legal_pages();
		} elseif ( 'legal_template' === $action ) {
			$notices = textmaker_apply_legal_templates();
		} elseif ( 'reset' === $action ) {
			delete_option( TEXTMAKER_IMPORT_MAP );
			$notices[] = array( 'success', __( 'Die Import-Historie wurde zurückgesetzt. Der nächste Durchlauf lädt alle Dateien erneut.', 'textmaker' ) );
		}
	}

	$manifest = textmaker_import_manifest();
	$map      = textmaker_import_map();

	// Der Hero-Hintergrund zählt mit, steht aber nicht im Manifest.
	$total     = count( $manifest ) + 1;
	$done      = count( array_filter( $manifest, static fn( array $item ): bool => isset( $map[ $item['path'] ] ) ) )
		+ ( isset( $map[ TEXTMAKER_HERO_KEY ] ) ? 1 : 0 );
	$remaining = $total - $done;

	echo '<div class="wrap">';
	printf( '<h1>%s</h1>', esc_html__( 'Bilder aus der Live-Domain importieren', 'textmaker' ) );

	foreach ( $notices as $notice ) {
		printf(
			'<div class="notice notice-%1$s"><p>%2$s</p></div>',
			esc_attr( $notice[0] ),
			esc_html( $notice[1] )
		);
	}

	printf(
		'<p style="max-width:60em;">%s</p>',
		sprintf(
			/* translators: %s: Quelldomain. */
			esc_html__( 'Der Import holt Logo, Hero-Hintergrund, Team-Porträts, Ablauf-Screenshots, Referenzen und Referenz-Logos von %s in die Mediathek und legt die passenden Einträge an. Bereits übernommene Dateien werden übersprungen — du kannst den Import also jederzeit erneut starten.', 'textmaker' ),
			esc_html( textmaker_source_domain() )
		)
	);

	printf(
		'<p><strong>%1$s</strong> %2$d / %3$d</p>',
		esc_html__( 'Bereits importiert:', 'textmaker' ),
		(int) $done,
		(int) $total
	);

	echo '<form method="post" id="textmaker-import-form" style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;margin-bottom:2rem;">';
	wp_nonce_field( 'textmaker_import' );
	echo '<input type="hidden" name="textmaker_import_action" value="media">';

	printf(
		'<button type="submit" class="button button-primary">%s</button>',
		$remaining > 0
			? esc_html(
				sprintf(
					/* translators: %d: Anzahl offener Dateien. */
					__( 'Import starten (%d offen)', 'textmaker' ),
					(int) $remaining
				)
			)
			: esc_html__( 'Alles importiert', 'textmaker' )
	);

	if ( 0 === $remaining ) {
		printf(
			'<span class="description">%s</span>',
			esc_html__( 'Es gibt nichts mehr zu holen.', 'textmaker' )
		);
	}

	echo '</form>';

	// Nach einem Teil-Durchlauf automatisch weitermachen.
	if ( isset( $_POST['textmaker_import_action'] ) && 'media' === sanitize_key( wp_unslash( (string) $_POST['textmaker_import_action'] ) ) && $remaining > 0 ) {
		echo '<script>setTimeout(function(){document.getElementById("textmaker-import-form").submit();},600);</script>';
		printf(
			'<div class="notice notice-info"><p>%s</p></div>',
			esc_html__( 'Der Import läuft in Etappen weiter — bitte diese Seite geöffnet lassen.', 'textmaker' )
		);
	}

	echo '<hr>';
	printf( '<h2>%s</h2>', esc_html__( 'Rechtliche Seiten', 'textmaker' ) );
	printf(
		'<p style="max-width:60em;">%s</p>',
		esc_html__( 'Übernimmt die Texte von AGB, Datenschutz und Impressum aus der Live-Domain in gleichnamige WordPress-Seiten. Bestehende Seiten mit Inhalt werden nicht überschrieben.', 'textmaker' )
	);

	echo '<form method="post">';
	wp_nonce_field( 'textmaker_import' );
	echo '<input type="hidden" name="textmaker_import_action" value="legal">';
	printf( '<button type="submit" class="button">%s</button>', esc_html__( 'Rechtliche Seiten übernehmen', 'textmaker' ) );
	echo '</form>';

	textmaker_render_legal_diagnostics();

	echo '<hr>';
	printf( '<h2>%s</h2>', esc_html__( 'Rechtliche Seiten aus dem Theme einsetzen', 'textmaker' ) );
	printf(
		'<p style="max-width:60em;">%s</p>',
		esc_html__( 'Setzt AGB, Datenschutzerklärung und Impressum ein. Datenschutz und Impressum sind auf dem Stand des revidierten Schweizer Datenschutzgesetzes samt Verordnung, mit Hinweisen zur EU-DSGVO und zum Swiss-U.S. Data Privacy Framework. Die AGB sind inhaltlich unverändert übernommen. Firmenname, Adresse, E-Mail und Telefon werden aus den Theme-Optionen eingesetzt — dafür braucht es weder die Live-Domain noch den alten Page-Builder.', 'textmaker' )
	);
	printf(
		'<p class="notice notice-warning" style="padding:.75rem 1rem;max-width:60em;">%s</p>',
		esc_html__( 'Achtung: Die Vorlagen ersetzen den bisherigen Inhalt beider Seiten. Sie sind vollständig ausgefüllt, sind aber eine fachliche Grundlage und ersetzen keine Rechtsberatung. Ändern sich Hosting, Rechtsform oder eingebundene Dienste, gehört der Text angepasst.', 'textmaker' )
	);

	echo '<form method="post" onsubmit="return confirm(' . esc_attr( wp_json_encode( __( 'Datenschutz und Impressum wirklich mit den Vorlagen überschreiben?', 'textmaker' ) ) ) . ');">';
	wp_nonce_field( 'textmaker_import' );
	echo '<input type="hidden" name="textmaker_import_action" value="legal_template">';
	printf( '<button type="submit" class="button button-primary">%s</button>', esc_html__( 'Vorlagen einsetzen', 'textmaker' ) );
	echo '</form>';

	echo '<hr>';
	printf( '<h2>%s</h2>', esc_html__( 'Historie zurücksetzen', 'textmaker' ) );
	printf(
		'<p style="max-width:60em;">%s</p>',
		esc_html__( 'Vergisst, welche Dateien bereits geholt wurden. Vorhandene Bilder und Einträge bleiben erhalten — ein erneuter Import legt sie zusätzlich an.', 'textmaker' )
	);

	echo '<form method="post" onsubmit="return confirm(' . esc_attr( wp_json_encode( __( 'Import-Historie wirklich zurücksetzen?', 'textmaker' ) ) ) . ');">';
	wp_nonce_field( 'textmaker_import' );
	echo '<input type="hidden" name="textmaker_import_action" value="reset">';
	printf( '<button type="submit" class="button button-link-delete">%s</button>', esc_html__( 'Historie zurücksetzen', 'textmaker' ) );
	echo '</form>';

	echo '</div>';
}

/**
 * Eine Etappe des Medien-Imports ausführen.
 *
 * @return array<int, array{0: string, 1: string}> Meldungen für die Ausgabe.
 */
function textmaker_run_media_import(): array {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$map      = textmaker_import_map();
	$notices  = array();
	$imported = 0;
	$failed   = 0;

	// Hero-Hintergrund zuerst — er steckt im Stylesheet, nicht im Manifest.
	if ( ! isset( $map[ TEXTMAKER_HERO_KEY ] ) ) {
		$hero = textmaker_import_hero_background();

		if ( is_wp_error( $hero ) ) {
			++$failed;
			$notices[] = array(
				'warning',
				sprintf(
					/* translators: %s: Fehlermeldung. */
					__( 'Das Hintergrundbild des Heros konnte nicht ermittelt werden: %s Du kannst es im Customizer unter „Hero“ von Hand setzen.', 'textmaker' ),
					$hero->get_error_message()
				),
			);
		} else {
			$map[ TEXTMAKER_HERO_KEY ] = $hero;
			++$imported;
			$notices[] = array( 'success', __( 'Hintergrundbild des Heros übernommen.', 'textmaker' ) );
		}
	}

	foreach ( textmaker_import_manifest() as $item ) {
		if ( $imported >= TEXTMAKER_IMPORT_BATCH ) {
			break;
		}

		if ( isset( $map[ $item['path'] ] ) ) {
			continue;
		}

		$attachment_id = textmaker_sideload_remote( textmaker_source_domain() . $item['path'], $item['title'] );

		if ( is_wp_error( $attachment_id ) ) {
			++$failed;
			$notices[] = array(
				'error',
				sprintf(
					/* translators: 1: Dateipfad, 2: Fehlermeldung. */
					__( '%1$s konnte nicht geholt werden: %2$s', 'textmaker' ),
					$item['path'],
					$attachment_id->get_error_message()
				),
			);
			continue;
		}

		$map[ $item['path'] ] = $attachment_id;
		++$imported;

		if ( 'logo' === $item['group'] ) {
			set_theme_mod( 'custom_logo', $attachment_id );
			continue;
		}

		if ( 'favicon' === $item['group'] ) {
			update_option( 'site_icon', $attachment_id );
			continue;
		}

		textmaker_create_entry( $item, $attachment_id );
	}

	update_option( TEXTMAKER_IMPORT_MAP, $map, false );

	if ( $imported > 0 ) {
		$notices[] = array(
			'success',
			sprintf(
				/* translators: %d: Anzahl importierter Dateien. */
				_n( '%d Datei importiert.', '%d Dateien importiert.', $imported, 'textmaker' ),
				$imported
			),
		);
	}

	if ( 0 === $imported && 0 === $failed ) {
		$notices[] = array( 'info', __( 'Es war nichts mehr zu holen.', 'textmaker' ) );
	}

	return $notices;
}

/**
 * Hintergrundbild des Heros aus dem Stylesheet der Live-Seite holen.
 *
 * @return int|WP_Error Anhang-ID oder Fehler.
 */
function textmaker_import_hero_background(): int|WP_Error {
	$url = textmaker_discover_hero_background();

	if ( is_wp_error( $url ) ) {
		return $url;
	}

	$attachment_id = textmaker_sideload_remote( $url, __( 'Hero-Hintergrund', 'textmaker' ) );

	if ( is_wp_error( $attachment_id ) ) {
		return $attachment_id;
	}

	set_theme_mod( 'textmaker_hero_image', $attachment_id );

	return $attachment_id;
}

/**
 * URL des Hero-Hintergrunds im erzeugten Stylesheet suchen.
 *
 * Zuerst wird gezielt die Regel des Hero-Abschnitts gelesen. Wurde die Seite
 * inzwischen umgebaut, greift als Rückfall das erste Hintergrundbild im
 * Stylesheet, das aus dem Upload-Verzeichnis stammt.
 *
 * @return string|WP_Error Vollständige Bild-URL oder Fehler.
 */
function textmaker_discover_hero_background(): string|WP_Error {
	$response = wp_remote_get(
		textmaker_source_domain() . TEXTMAKER_HERO_CSS,
		array(
			'timeout'    => 30,
			'user-agent' => 'teXtmaker-Theme-Import/1.0',
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );

	if ( 200 !== $code ) {
		/* translators: %d: HTTP-Statuscode. */
		return new WP_Error( 'textmaker_http', sprintf( __( 'Das Stylesheet antwortete mit Status %d.', 'textmaker' ), $code ) );
	}

	$css = wp_remote_retrieve_body( $response );

	if ( '' === $css ) {
		return new WP_Error( 'textmaker_empty', __( 'Das Stylesheet war leer.', 'textmaker' ) );
	}

	// Regelblock des Hero-Abschnitts isolieren.
	$position = strpos( $css, TEXTMAKER_HERO_ELEMENT );

	if ( false !== $position ) {
		$block = substr( $css, $position, 2000 );

		if ( 1 === preg_match( '/background-image\s*:\s*url\(\s*["\']?([^"\')]+)/i', $block, $matches ) ) {
			return textmaker_absolute_url( trim( $matches[1] ) );
		}
	}

	// Rückfall: erstes Hintergrundbild aus dem Upload-Verzeichnis.
	if ( 1 === preg_match( '#background-image\s*:\s*url\(\s*["\']?([^"\')]*wp-content/uploads/[^"\')]+)#i', $css, $matches ) ) {
		return textmaker_absolute_url( trim( $matches[1] ) );
	}

	return new WP_Error( 'textmaker_not_found', __( 'Im Stylesheet wurde kein Hintergrundbild gefunden.', 'textmaker' ) );
}

/**
 * Relative oder protokollrelative URL auf die Quelldomain beziehen.
 *
 * @param string $url Rohe URL aus dem Stylesheet.
 */
function textmaker_absolute_url( string $url ): string {
	if ( str_starts_with( $url, '//' ) ) {
		return 'https:' . $url;
	}

	if ( str_starts_with( $url, 'http' ) ) {
		return $url;
	}

	return textmaker_source_domain() . '/' . ltrim( $url, '/' );
}

/**
 * Eine Datei von der Quelldomain in die Mediathek laden.
 *
 * @param string $url   Vollständige URL.
 * @param string $title Titel des Anhangs.
 * @return int|WP_Error Anhang-ID oder Fehler.
 */
function textmaker_sideload_remote( string $url, string $title ): int|WP_Error {
	$temp = download_url( $url, 60 );

	if ( is_wp_error( $temp ) ) {
		return $temp;
	}

	$file = array(
		'name'     => basename( wp_parse_url( $url, PHP_URL_PATH ) ?: 'datei.jpg' ),
		'tmp_name' => $temp,
	);

	$attachment_id = media_handle_sideload( $file, 0, $title );

	if ( is_wp_error( $attachment_id ) ) {
		wp_delete_file( $temp );

		return $attachment_id;
	}

	update_post_meta( $attachment_id, '_wp_attachment_image_alt', $title );

	return (int) $attachment_id;
}

/**
 * Passenden Eintrag zu einer importierten Datei anlegen.
 *
 * @param array{group: string, path: string, title: string, meta?: array<string, string>, order?: int} $item          Eintrag aus dem Manifest.
 * @param int                                                                                         $attachment_id Anhang-ID.
 */
function textmaker_create_entry( array $item, int $attachment_id ): void {
	$existing = get_posts(
		array(
			'post_type'      => $item['group'],
			'post_status'    => 'any',
			'title'          => $item['title'],
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if ( array() !== $existing ) {
		set_post_thumbnail( (int) $existing[0], $attachment_id );

		return;
	}

	$post_id = wp_insert_post(
		array(
			'post_type'   => $item['group'],
			'post_status' => 'publish',
			'post_title'  => $item['title'],
			'menu_order'  => (int) ( $item['order'] ?? 0 ),
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return;
	}

	set_post_thumbnail( (int) $post_id, $attachment_id );

	foreach ( $item['meta'] ?? array() as $key => $value ) {
		if ( '' === $value ) {
			continue;
		}

		update_post_meta( (int) $post_id, $key, $value );
	}
}

/**
 * Texte der rechtlichen Seiten übernehmen.
 *
 * Diese Seiten wirken leer, weil ihr Text nicht in `post_content` steht,
 * sondern in den Daten des alten Page-Builders. Sobald dieser abgeschaltet
 * ist, rendert WordPress folglich nichts.
 *
 * Die Quelle wird darum in dieser Reihenfolge gesucht:
 *   1. die Builder-Daten der Seite in dieser Datenbank,
 *   2. der Fliesstext der Seite auf der Live-Domain.
 *
 * Schritt 1 ist der zuverlässige Weg, wenn das Theme auf derselben Website
 * läuft — dort liefert Schritt 2 nichts mehr, weil die Live-Seite bereits mit
 * diesem Theme ausgeliefert wird.
 *
 * @return array<int, array{0: string, 1: string}> Meldungen für die Ausgabe.
 */
function textmaker_import_legal_pages(): array {
	$notices = array();

	foreach ( textmaker_legal_pages() as $slug => $title ) {
		$page = textmaker_find_page( $slug, $title );

		// Bereits vorhandener, echter Inhalt bleibt unangetastet.
		if ( $page instanceof WP_Post && '' !== trim( wp_strip_all_tags( $page->post_content ) ) ) {
			$notices[] = array(
				'info',
				sprintf(
					/* translators: %s: Seitentitel. */
					__( '%s hat bereits Inhalt und wurde nicht angetastet.', 'textmaker' ),
					$title
				),
			);
			continue;
		}

		$content = '';
		$source  = '';

		if ( $page instanceof WP_Post ) {
			$content = textmaker_extract_builder_content( (int) $page->ID );

			if ( '' !== $content ) {
				$source = __( 'aus den Daten des alten Page-Builders', 'textmaker' );
			}
		}

		if ( '' === $content ) {
			$remote = textmaker_fetch_page_content( textmaker_source_domain() . '/' . $slug . '/' );

			if ( is_wp_error( $remote ) ) {
				$notices[] = array(
					'error',
					sprintf(
						/* translators: 1: Seitentitel, 2: Fehlermeldung. */
						__( '%1$s konnte nicht übernommen werden. In dieser Datenbank sind keine Builder-Daten hinterlegt, und der Abruf der Live-Seite scheiterte: %2$s', 'textmaker' ),
						$title,
						$remote->get_error_message()
					),
				);
				continue;
			}

			$content = $remote;
			$source  = __( 'von der Live-Domain', 'textmaker' );
		}

		$data = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_content' => $content,
		);

		if ( $page instanceof WP_Post ) {
			$data['ID'] = $page->ID;
		}

		$result = wp_insert_post( $data, true );

		if ( is_wp_error( $result ) ) {
			$notices[] = array(
				'error',
				sprintf( /* translators: %s: Seitentitel. */ __( '%s konnte nicht gespeichert werden.', 'textmaker' ), $title ),
			);
			continue;
		}

		textmaker_detach_builder( (int) $result );

		$notices[] = array(
			'success',
			sprintf(
				/* translators: 1: Seitentitel, 2: Herkunft des Inhalts. */
				__( '%1$s wurde übernommen (%2$s).', 'textmaker' ),
				$title,
				$source
			),
		);
	}

	return $notices;
}

/**
 * Zustand der rechtlichen Seiten anzeigen.
 *
 * Zeigt für jeden Pfad alle passenden Seiten — nicht nur die erste. Existieren
 * zwei Seiten mit demselben Titel, hat WordPress der zweiten einen Pfad wie
 * „agb-2“ gegeben; der Link in der Fusszeile führt dann auf die alte, leere
 * Seite. Ohne diese Übersicht ist das kaum zu erkennen.
 */
function textmaker_render_legal_diagnostics(): void {
	echo '<hr>';
	printf( '<h2>%s</h2>', esc_html__( 'Zustand der rechtlichen Seiten', 'textmaker' ) );

	echo '<table class="widefat striped" style="max-width:70em;"><thead><tr>';

	foreach ( array(
		__( 'Erwarteter Pfad', 'textmaker' ),
		__( 'Seite', 'textmaker' ),
		__( 'Tatsächlicher Pfad', 'textmaker' ),
		__( 'Status', 'textmaker' ),
		__( 'Inhalt', 'textmaker' ),
		__( 'Adresse', 'textmaker' ),
	) as $heading ) {
		printf( '<th>%s</th>', esc_html( $heading ) );
	}

	echo '</tr></thead><tbody>';

	foreach ( textmaker_legal_pages() as $slug => $title ) {
		$pages = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'posts_per_page' => 10,
				's'              => '',
				'title'          => $title,
			)
		);

		$by_path = get_page_by_path( $slug );

		if ( $by_path instanceof WP_Post && ! in_array( $by_path->ID, wp_list_pluck( $pages, 'ID' ), true ) ) {
			array_unshift( $pages, $by_path );
		}

		if ( array() === $pages ) {
			printf(
				'<tr><td><code>/%1$s/</code></td><td colspan="5">%2$s</td></tr>',
				esc_html( $slug ),
				esc_html__( 'Keine Seite vorhanden — „Vorlagen einsetzen“ legt sie an.', 'textmaker' )
			);
			continue;
		}

		foreach ( $pages as $index => $page ) {
			$length   = mb_strlen( trim( wp_strip_all_tags( $page->post_content ) ) );
			$mismatch = $page->post_name !== $slug;

			printf(
				'<tr>
					<td><code>/%1$s/</code></td>
					<td><a href="%2$s">%3$s</a> <span class="description">#%4$d</span></td>
					<td>%5$s</td>
					<td>%6$s</td>
					<td>%7$s</td>
					<td><a href="%8$s" target="_blank" rel="noopener">%8$s</a></td>
				</tr>',
				esc_html( 0 === $index ? $slug : '' ),
				esc_url( (string) get_edit_post_link( $page->ID ) ),
				esc_html( $page->post_title ),
				(int) $page->ID,
				$mismatch
					? '<strong style="color:#b32d2e;">' . esc_html( $page->post_name ) . '</strong>'
					: '<code>' . esc_html( $page->post_name ) . '</code>',
				esc_html( (string) get_post_status( $page ) ),
				0 === $length
					? '<strong style="color:#b32d2e;">' . esc_html__( 'leer', 'textmaker' ) . '</strong>'
					: esc_html( sprintf( /* translators: %d: Anzahl Zeichen. */ __( '%d Zeichen', 'textmaker' ), $length ) ),
				esc_url( (string) get_permalink( $page ) )
			);
		}
	}

	echo '</tbody></table>';

	printf(
		'<p class="description" style="max-width:70em;margin-top:.75rem;">%s</p>',
		esc_html__( 'Rot markiert: Der Pfad weicht vom erwarteten ab. Das passiert, wenn eine zweite Seite mit demselben Titel angelegt wurde — WordPress hängt dann eine Ziffer an. In dem Fall die überzählige Seite löschen und den Pfad der verbleibenden Seite auf den erwarteten Wert setzen.', 'textmaker' )
	);

	// Permalink-Struktur: die häufigste Ursache für 404 auf Unterseiten.
	$structure = (string) get_option( 'permalink_structure', '' );

	if ( '' === $structure ) {
		printf(
			'<div class="notice notice-warning" style="max-width:70em;"><p>%1$s</p><p><a class="button" href="%2$s">%3$s</a></p></div>',
			esc_html__( 'Die Permalinks stehen auf „Einfach“. Die Seiten sind damit nur über eine Adresse mit Fragezeichen erreichbar. Auf „Beitragsname“ umstellen und speichern — das schreibt zugleich die Umschreiberegeln neu.', 'textmaker' ),
			esc_url( admin_url( 'options-permalink.php' ) ),
			esc_html__( 'Zu den Permalink-Einstellungen', 'textmaker' )
		);
	} else {
		printf(
			'<p style="max-width:70em;">%1$s <code>%2$s</code> — %3$s <a href="%4$s">%5$s</a></p>',
			esc_html__( 'Permalink-Struktur:', 'textmaker' ),
			esc_html( $structure ),
			esc_html__( 'Liefern Unterseiten trotzdem einen 404, hilft fast immer: Permalinks einmal ohne Änderung speichern.', 'textmaker' ),
			esc_url( admin_url( 'options-permalink.php' ) ),
			esc_html__( 'Permalinks speichern', 'textmaker' )
		);
	}
}

/**
 * Vorlagen für Datenschutz und Impressum einsetzen.
 *
 * Anders als beim Import aus der Live-Domain wird hier bewusst überschrieben —
 * darum steht davor eine Rückfrage.
 *
 * @return array<int, array{0: string, 1: string}> Meldungen für die Ausgabe.
 */
function textmaker_apply_legal_templates(): array {
	require_once TEXTMAKER_DIR . '/inc/legal-content.php';

	$notices = array();

	foreach ( textmaker_legal_templates() as $slug => $template ) {
		$page = textmaker_find_page( $slug, $template['title'] );

		$data = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $template['title'],
			'post_name'    => $slug,
			'post_content' => $template['content'],
		);

		if ( $page instanceof WP_Post ) {
			$data['ID'] = $page->ID;
		}

		$result = wp_insert_post( $data, true );

		if ( is_wp_error( $result ) ) {
			$notices[] = array(
				'error',
				sprintf( /* translators: %s: Seitentitel. */ __( '%s konnte nicht gespeichert werden.', 'textmaker' ), $template['title'] ),
			);
			continue;
		}

		textmaker_detach_builder( (int) $result );

		$notices[] = array(
			'success',
			sprintf(
				/* translators: 1: Seitentitel, 2: Bearbeitungslink. */
				__( '%1$s wurde eingesetzt. Bitte die Stellen in eckigen Klammern füllen: %2$s', 'textmaker' ),
				$template['title'],
				(string) get_edit_post_link( (int) $result, '' )
			),
		);
	}

	// Datenschutzseite auch für WordPress selbst hinterlegen.
	$privacy = get_page_by_path( 'datenschutz' );

	if ( $privacy instanceof WP_Post ) {
		update_option( 'wp_page_for_privacy_policy', $privacy->ID );
	}

	return $notices;
}

/**
 * Seite über Pfad oder Titel finden.
 *
 * @param string $slug  Pfad der Seite.
 * @param string $title Titel als Rückfall.
 */
function textmaker_find_page( string $slug, string $title ): ?WP_Post {
	$page = get_page_by_path( $slug );

	if ( $page instanceof WP_Post ) {
		return $page;
	}

	$found = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'any',
			'title'          => $title,
			'posts_per_page' => 1,
		)
	);

	return $found[0] ?? null;
}

/**
 * Fliesstext aus den Daten des alten Page-Builders lesen.
 *
 * Der Builder legt seinen Aufbau als JSON-Baum in einem Meta-Feld ab. Gesucht
 * werden die Text- und Überschriften-Widgets, in der Reihenfolge des Baums.
 *
 * @param int $post_id Beitrags-ID.
 * @return string HTML oder leerer String, wenn nichts gefunden wurde.
 */
function textmaker_extract_builder_content( int $post_id ): string {
	$raw = get_post_meta( $post_id, '_elementor_data', true );

	if ( ! is_string( $raw ) || '' === trim( $raw ) ) {
		return '';
	}

	$tree = json_decode( $raw, true );

	// Manche Installationen legen den Wert maskiert ab.
	if ( ! is_array( $tree ) ) {
		$tree = json_decode( wp_unslash( $raw ), true );
	}

	if ( ! is_array( $tree ) ) {
		return '';
	}

	$parts = array();
	textmaker_walk_builder_tree( $tree, $parts );

	if ( array() === $parts ) {
		return '';
	}

	return wp_kses_post( implode( "\n\n", $parts ) );
}

/**
 * Baum des Page-Builders rekursiv nach Textinhalten durchgehen.
 *
 * @param array<int|string, mixed> $nodes Knoten des Baums.
 * @param array<int, string>       $parts Sammelbehälter, wird ergänzt.
 */
function textmaker_walk_builder_tree( array $nodes, array &$parts ): void {
	foreach ( $nodes as $node ) {
		if ( ! is_array( $node ) ) {
			continue;
		}

		$type     = isset( $node['widgetType'] ) ? (string) $node['widgetType'] : '';
		$settings = isset( $node['settings'] ) && is_array( $node['settings'] ) ? $node['settings'] : array();

		if ( 'text-editor' === $type && isset( $settings['editor'] ) ) {
			$editor = trim( (string) $settings['editor'] );

			if ( '' !== $editor ) {
				$parts[] = $editor;
			}
		}

		if ( 'heading' === $type && isset( $settings['title'] ) ) {
			$heading = trim( wp_strip_all_tags( (string) $settings['title'] ) );

			if ( '' !== $heading ) {
				$tag     = isset( $settings['header_size'] ) ? sanitize_key( (string) $settings['header_size'] ) : 'h2';
				$tag     = in_array( $tag, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $tag : 'h2';
				$parts[] = '<' . $tag . '>' . esc_html( $heading ) . '</' . $tag . '>';
			}
		}

		if ( isset( $node['elements'] ) && is_array( $node['elements'] ) ) {
			textmaker_walk_builder_tree( $node['elements'], $parts );
		}
	}
}

/**
 * Seite vom alten Page-Builder lösen, damit WordPress den Inhalt selbst rendert.
 *
 * @param int $post_id Beitrags-ID.
 */
function textmaker_detach_builder( int $post_id ): void {
	delete_post_meta( $post_id, '_elementor_edit_mode' );
	delete_post_meta( $post_id, '_wp_page_template' );
}

/**
 * Fliesstext einer Live-Seite auslesen.
 *
 * Greift auf die Text-Editor-Widgets des bisherigen Page-Builders zu und gibt
 * deren HTML bereinigt zurück.
 *
 * @param string $url Vollständige URL der Seite.
 * @return string|WP_Error Inhalt oder Fehler.
 */
function textmaker_fetch_page_content( string $url ): string|WP_Error {
	$response = wp_remote_get(
		$url,
		array(
			'timeout'    => 30,
			'user-agent' => 'teXtmaker-Theme-Import/1.0',
		)
	);

	if ( is_wp_error( $response ) ) {
		return $response;
	}

	$code = wp_remote_retrieve_response_code( $response );

	if ( 200 !== $code ) {
		/* translators: %d: HTTP-Statuscode. */
		return new WP_Error( 'textmaker_http', sprintf( __( 'Die Seite antwortete mit Status %d.', 'textmaker' ), $code ) );
	}

	$body = wp_remote_retrieve_body( $response );

	if ( '' === $body ) {
		return new WP_Error( 'textmaker_empty', __( 'Die Seite lieferte keinen Inhalt.', 'textmaker' ) );
	}

	$previous = libxml_use_internal_errors( true );
	$document = new DOMDocument();
	$document->loadHTML( '<?xml encoding="UTF-8">' . $body );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	$xpath = new DOMXPath( $document );
	$nodes = $xpath->query( '//div[contains(@class, "elementor-widget-text-editor")]//div[contains(@class, "elementor-widget-container")]' );

	if ( ! $nodes instanceof DOMNodeList || 0 === $nodes->length ) {
		return new WP_Error( 'textmaker_not_found', __( 'Auf der Seite wurde kein Textblock gefunden.', 'textmaker' ) );
	}

	$html = '';

	foreach ( $nodes as $node ) {
		foreach ( $node->childNodes as $child ) {
			$html .= $document->saveHTML( $child );
		}
	}

	$html = trim( $html );

	if ( '' === $html ) {
		return new WP_Error( 'textmaker_empty', __( 'Der gefundene Textblock war leer.', 'textmaker' ) );
	}

	return wp_kses_post( $html );
}

/**
 * Hinweis, solange noch keine Inhalte importiert wurden.
 */
function textmaker_import_hint(): void {
	$screen = get_current_screen();

	if ( ! $screen instanceof WP_Screen || 'dashboard' !== $screen->id ) {
		return;
	}

	if ( array() !== textmaker_import_map() ) {
		return;
	}

	if ( ! current_user_can( 'upload_files' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info is-dismissible"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
		esc_html__( 'Das teXtmaker-Theme ist aktiv, aber die Bilder wurden noch nicht übernommen.', 'textmaker' ),
		esc_url( admin_url( 'admin.php?page=textmaker-import' ) ),
		esc_html__( 'Jetzt importieren', 'textmaker' )
	);
}
add_action( 'admin_notices', 'textmaker_import_hint' );
