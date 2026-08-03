<?php
/**
 * Theme-Optionen und Ausgabe-Helfer.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Standardwerte aller Theme-Optionen.
 *
 * Sie entsprechen den Texten der bestehenden Website und dienen zugleich als
 * Fallback, solange im Customizer nichts anderes hinterlegt ist.
 *
 * @return array<string, string>
 */
function textmaker_defaults(): array {
	return array(
		// Hero.
		'hero_heading'      => 'Wir bringen deine Texte auf ein höheres Level.',
		'hero_underline'    => 'Wir, höheres',
		'hero_guarantee'    => 'Garantiert',
		'hero_button_1'     => 'Preise|#preise',
		'hero_button_2'     => 'Rezensionen|#rezensionen',
		'hero_button_3'     => 'Ablauf Korrektur|#ablauf',
		'hero_button_4'     => 'Kontaktformular|#anfragen',

		// Lektorat-Service.
		'service_heading'   => 'Das korrigieren wir in deinen Magazinen, Berichten, Broschüren, Abschlussarbeiten und anderen Texten',
		'service_eyebrow'   => 'Lektorat-Service',
		'service_items'     => "Grammatik, Stilistik\nRechtschreibung, Formatierung\nRoter Faden, Logik & Zusammenhänge",

		// Kundenmeinungen.
		'reviews_heading'   => 'Kundenmeinungen',
		'reviews_elfsight'  => '6a505a69-02af-4f6a-8f46-ca7603d08c2e',

		// Ablauf.
		'steps_heading'     => 'So läuft die Korrektur ab',
		'steps_intro'       => 'Du erhältst dein Dokument mit nachvollziehbaren Änderungen zurück — du entscheidest, was du übernimmst.',

		// Preise.
		'prices_heading'    => 'Lektorate',
		'prices_left'       => "### B2B: Eckpunkte einer Korrektur\n\n**Sprachliche Qualität mit intelligenter Unterstützung**\n\nWir verbinden moderne AI-Technologie mit menschlicher Erfahrung und sprachlichem Feingefühl. Auf diese Weise überarbeiten wir Texte effizient, präzise und mit dem hohen Qualitätsanspruch, den professionelle Kommunikation erfordert.\n\n**Wir denken weiter**\n\nWir korrigieren nicht nur, sondern kommunizieren auch Vorschläge unaufgefordert; sowohl sprachlicher als auch inhaltlicher Natur.\n\n**4-Augen-Prinzip für überzeugende Resultate**\n\nWir prüfen jeden Text nach dem 4-Augen-Prinzip. Das schafft zusätzliche Sicherheit, erhöht die Genauigkeit und bringt oft auch sprachliche Feinheiten ans Licht, die einer einzelnen Prüfung entgehen könnten. So gewinnen Texte an Klarheit, Stilsicherheit und Wirkung.",
		'prices_right'      => "**Korrekturfrist, abhängig von der Textmenge**\n\n- 1 bis 5 Tage oder nach Absprache.\n\n**Was unsere Kunden schätzen**\n\n- Gute Erreichbarkeit auch bei kurzfristigen Aufträgen\n- Persönliche, individuelle Betreuung\n- Faire Preise\n- Ein Team mit unterschiedlichen und sich ergänzenden Fähigkeiten\n- Mehrsprachigkeit: Wir übernehmen auf Anfrage Korrekturen und Übersetzungen von französischen und englischen Texten dank unseren Partnern.\n- Möglichkeit, Formatierungsarbeiten zu übernehmen (Word-Dokumente)\n- Wir bringen Erfahrung aus dem Journalismus mit und sind in der Lage, bei Bedarf Texte zu ergänzen oder von Grund auf zu schreiben\n- Erfahrung mit Texten im Bereich Medizin",
		'prices_rates'      => "Stundenansatz für Firmen / B2B|CHF 135.00\nStundenansatz für Studierende (Abschlussarbeiten)|CHF 95.00",

		// Anfrage.
		'contact_heading'   => 'Wir freuen uns auf deine Anfrage!',
		'contact_intro'     => 'Wir sind Mo. – So. erreichbar.',
		'contact_whatsapp'  => 'https://wa.me/message/MTVORVZRIBJDD1',
		'contact_wa_label'  => 'Schreibe uns eine WhatsApp …',
		'contact_recipient' => '',
		'contact_success'   => 'Vielen Dank für deine Anfrage! Wir melden uns so rasch als möglich bei dir.',
		'contact_note'      => 'Deine Angaben gehen direkt an unser Team. Keine Weitergabe an Dritte.',

		// Referenzen.
		'refs_heading'      => 'Referenzen unseres Teams im Bereich Text / Journalismus',

		// Fusszeile.
		'footer_services_heading' => 'Unsere Dienstleistungen',
		'footer_services'   => "Lektorate für B2B sowie Privatkunden (Diplomarbeiten, Bachelorarbeiten, Masterarbeiten)\nÜbersetzungen auf Deutsch, Französisch und Englisch\nTexterstellung auf Anfrage\nFormatierungsaufgaben auf Anfrage",
		'footer_contact_heading' => 'Kontakt',
		'footer_company'    => 'teXtmaker.ch',
		'footer_address'    => "Franz-Rittmeyer-Weg 3\nCH-6300 Zug",
		'footer_email'      => 'staff@textmaker.ch',
		'footer_phone'      => '079 660 11 06',
		'footer_phone_link' => '+41796601106',
		'footer_legal_heading' => 'Rechtliches',
		'footer_copyright'  => '© teXtmaker.ch — we write what you think',

		// Tracking.
		'gtm_id'            => 'GTM-PZKN5CC',

		// Farben.
		'accent_color'      => '#7ED321',
		'ink_color'         => '#14181A',
	);
}

/**
 * Schaltbare Abschnitte der Startseite.
 *
 * @return array<string, string>
 */
function textmaker_sections(): array {
	return array(
		'service' => __( 'Lektorat-Service', 'textmaker' ),
		'reviews' => __( 'Kundenmeinungen', 'textmaker' ),
		'steps'   => __( 'Ablauf der Korrektur', 'textmaker' ),
		'prices'  => __( 'Lektorate / Preise', 'textmaker' ),
		'contact' => __( 'Offerte anfragen', 'textmaker' ),
		'refs'    => __( 'Referenzen', 'textmaker' ),
	);
}

/**
 * Wert einer Theme-Option lesen.
 *
 * @param string $key      Schlüssel ohne Präfix.
 * @param string $fallback Rückfallwert, falls kein Standard hinterlegt ist.
 */
function textmaker_option( string $key, string $fallback = '' ): string {
	$defaults = textmaker_defaults();
	$default  = $defaults[ $key ] ?? $fallback;

	return (string) get_theme_mod( 'textmaker_' . $key, $default );
}

/**
 * Prüft, ob ein Abschnitt angezeigt werden soll.
 *
 * @param string $section Schlüssel aus textmaker_sections().
 */
function textmaker_show_section( string $section ): bool {
	return (bool) get_theme_mod( 'textmaker_show_' . $section, true );
}

/**
 * Zeilen eines mehrzeiligen Feldes als Liste.
 *
 * @param string $key Options-Schlüssel.
 * @return array<int, string>
 */
function textmaker_option_lines( string $key ): array {
	$lines = preg_split( '/\r\n|\r|\n/', textmaker_option( $key ) );

	if ( false === $lines ) {
		return array();
	}

	return array_values( array_filter( array_map( 'trim', $lines ), static fn( string $line ): bool => '' !== $line ) );
}

/**
 * Ein mit „|“ getrenntes Wertepaar aufteilen.
 *
 * @param string $value Rohwert, z. B. „Preise|#preise“.
 * @return array{0: string, 1: string}
 */
function textmaker_split_pair( string $value ): array {
	$parts = explode( '|', $value, 2 );

	return array( trim( $parts[0] ), trim( $parts[1] ?? '' ) );
}

/**
 * Einfache Auszeichnungen in HTML übersetzen.
 *
 * Unterstützt „### Überschrift“, „**fett**“, Aufzählungen mit „- “ und Absätze.
 * Das reicht für die Fliesstexte der Startseite und hält die Eingabe im
 * Customizer lesbar, ohne einen vollen Editor einzubinden.
 *
 * @param string $text Rohtext.
 */
function textmaker_format_prose( string $text ): string {
	$blocks = preg_split( '/(\r\n|\r|\n){2,}/', trim( $text ) );

	if ( false === $blocks ) {
		return '';
	}

	$html = '';

	foreach ( $blocks as $block ) {
		$block = trim( $block );

		if ( '' === $block ) {
			continue;
		}

		$lines = preg_split( '/\r\n|\r|\n/', $block ) ?: array();
		$lines = array_map( 'trim', $lines );

		// Aufzählung.
		if ( str_starts_with( $lines[0], '- ' ) ) {
			$html .= '<ul>';

			foreach ( $lines as $line ) {
				if ( ! str_starts_with( $line, '- ' ) ) {
					continue;
				}

				$html .= '<li>' . textmaker_format_inline( substr( $line, 2 ) ) . '</li>';
			}

			$html .= '</ul>';
			continue;
		}

		// Überschriften.
		if ( str_starts_with( $block, '### ' ) ) {
			$html .= '<h3>' . textmaker_format_inline( substr( $block, 4 ) ) . '</h3>';
			continue;
		}

		// Ein einzelner, komplett fett gesetzter Absatz wird zur Zwischenüberschrift.
		if ( 1 === count( $lines ) && preg_match( '/^\*\*(.+)\*\*$/', $block, $matches ) ) {
			$html .= '<h4>' . esc_html( $matches[1] ) . '</h4>';
			continue;
		}

		$html .= '<p>' . textmaker_format_inline( implode( ' ', $lines ) ) . '</p>';
	}

	return $html;
}

/**
 * Inline-Auszeichnungen („**fett**“) übersetzen und alles Übrige escapen.
 *
 * @param string $text Rohtext.
 */
function textmaker_format_inline( string $text ): string {
	$escaped = esc_html( $text );

	return (string) preg_replace( '/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $escaped );
}

/**
 * Initialen eines Namens, als Rückfall für fehlende Porträts.
 *
 * @param string $name Vollständiger Name.
 */
function textmaker_initials( string $name ): string {
	$parts    = preg_split( '/\s+/', trim( $name ) ) ?: array();
	$initials = '';

	foreach ( array_slice( $parts, 0, 2 ) as $part ) {
		$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
	}

	return '' === $initials ? '·' : $initials;
}

/**
 * Erlaubtes HTML in den Fliesstexten der Startseite.
 *
 * @return array<string, array<string, bool>>
 */
function textmaker_allowed_prose_html(): array {
	return array(
		'p'      => array(),
		'h3'     => array(),
		'h4'     => array(),
		'ul'     => array(),
		'li'     => array(),
		'strong' => array(),
		'em'     => array(),
		'br'     => array(),
	);
}
