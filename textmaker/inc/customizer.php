<?php
/**
 * Customizer: sämtliche Texte, Kontaktangaben, Farben und Tracking-Einstellungen.
 *
 * Jeder Abschnitt der Startseite hat einen eigenen Bereich. Mit der Live-Vorschau
 * ist so jede Zeile ohne Page-Builder änderbar.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Aufbau der Customizer-Bereiche.
 *
 * @return array<string, array{title: string, description: string, fields: array<string, array{label: string, type: string, description?: string}>}>
 */
function textmaker_customizer_sections(): array {
	return array(
		'hero'     => array(
			'title'       => __( 'Hero (Startbild)', 'textmaker' ),
			'description' => __( 'Überschrift und die vier Buttons im Startbild. Buttons im Format „Beschriftung|Ziel“ — das Ziel darf ein Anker (#preise) oder eine vollständige URL sein.', 'textmaker' ),
			'fields'      => array(
				'hero_heading'   => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'textarea',
				),
				'hero_underline' => array(
					'label'       => __( 'Unterstrichene Wörter', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Mehrere Wörter mit Komma trennen. Leer lassen für keine Unterstreichung.', 'textmaker' ),
				),
				'hero_guarantee' => array(
					'label'       => __( 'Stempel-Text', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Der eingerahmte Schriftzug unter der Überschrift. Leer lassen, um ihn auszublenden.', 'textmaker' ),
				),
				'hero_button_1'  => array(
					'label' => __( 'Button 1', 'textmaker' ),
					'type'  => 'text',
				),
				'hero_button_2'  => array(
					'label' => __( 'Button 2', 'textmaker' ),
					'type'  => 'text',
				),
				'hero_button_3'  => array(
					'label' => __( 'Button 3', 'textmaker' ),
					'type'  => 'text',
				),
				'hero_button_4'  => array(
					'label' => __( 'Button 4', 'textmaker' ),
					'type'  => 'text',
				),
			),
		),
		'service'  => array(
			'title'       => __( 'Lektorat-Service', 'textmaker' ),
			'description' => __( 'Der Abschnitt mit den drei Häkchen.', 'textmaker' ),
			'fields'      => array(
				'service_eyebrow' => array(
					'label' => __( 'Kleine Zeile darüber', 'textmaker' ),
					'type'  => 'text',
				),
				'service_heading' => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'textarea',
				),
				'service_items'   => array(
					'label'       => __( 'Punkte', 'textmaker' ),
					'type'        => 'textarea',
					'description' => __( 'Ein Punkt pro Zeile.', 'textmaker' ),
				),
			),
		),
		'reviews'  => array(
			'title'       => __( 'Kundenmeinungen', 'textmaker' ),
			'description' => __( 'Ist eine Elfsight-App-ID hinterlegt, werden die Google-Rezensionen über das bestehende Widget geladen. Ohne App-ID zeigt das Theme die unter „teXtmaker → Kundenmeinungen“ gepflegten Einträge.', 'textmaker' ),
			'fields'      => array(
				'reviews_heading'  => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'text',
				),
				'reviews_elfsight' => array(
					'label'       => __( 'Elfsight-App-ID', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Nur die ID, z. B. 6a505a69-02af-4f6a-8f46-ca7603d08c2e', 'textmaker' ),
				),
			),
		),
		'steps'    => array(
			'title'       => __( 'Ablauf der Korrektur', 'textmaker' ),
			'description' => __( 'Die Screenshots selbst werden unter „teXtmaker → Ablauf der Korrektur“ gepflegt.', 'textmaker' ),
			'fields'      => array(
				'steps_heading' => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'text',
				),
				'steps_intro'   => array(
					'label' => __( 'Einleitung', 'textmaker' ),
					'type'  => 'textarea',
				),
			),
		),
		'prices'   => array(
			'title'       => __( 'Lektorate / Preise', 'textmaker' ),
			'description' => __( 'Formatierung: „### Überschrift“, „**fett**“ für Zwischentitel, „- “ für Aufzählungen, Leerzeile für neuen Absatz.', 'textmaker' ),
			'fields'      => array(
				'prices_heading' => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'text',
				),
				'prices_left'    => array(
					'label' => __( 'Linke Spalte', 'textmaker' ),
					'type'  => 'textarea',
				),
				'prices_right'   => array(
					'label' => __( 'Rechte Spalte', 'textmaker' ),
					'type'  => 'textarea',
				),
				'prices_rates'   => array(
					'label'       => __( 'Richtpreise', 'textmaker' ),
					'type'        => 'textarea',
					'description' => __( 'Eine Zeile pro Ansatz, Format „Bezeichnung|Betrag“.', 'textmaker' ),
				),
			),
		),
		'faq'      => array(
			'title'       => __( 'Fragen & Antworten', 'textmaker' ),
			'description' => __( 'Die Fragen selbst werden unter „teXtmaker → Fragen & Antworten“ gepflegt. Sie erscheinen auf der Startseite und werden zusätzlich maschinenlesbar ausgeliefert, damit Suchmaschinen und KI-Assistenten daraus zitieren können.', 'textmaker' ),
			'fields'      => array(
				'faq_heading' => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'text',
				),
				'faq_intro'   => array(
					'label' => __( 'Einleitung', 'textmaker' ),
					'type'  => 'textarea',
				),
			),
		),
		'seo'      => array(
			'title'       => __( 'Auffindbarkeit', 'textmaker' ),
			'description' => __( 'Titel und Beschreibung für Suchergebnisse, Linkvorschauen und KI-Assistenten. Ist ein SEO-Plugin aktiv, hat dessen Einstellung Vorrang.', 'textmaker' ),
			'fields'      => array(
				'meta_title_suffix' => array(
					'label'       => __( 'Zusatz im Seitentitel', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Erscheint hinter dem Websitenamen, z. B. „Lektorat & Korrektorat aus Zug“.', 'textmaker' ),
				),
				'meta_description'  => array(
					'label'       => __( 'Beschreibung der Startseite', 'textmaker' ),
					'type'        => 'textarea',
					'description' => __( 'Zwei bis drei Sätze, rund 150 Zeichen. Sag konkret, was ihr macht, für wen und wo — danach suchen Menschen und Antwortmaschinen.', 'textmaker' ),
				),
			),
		),
		'contact'  => array(
			'title'       => __( 'Offerte anfragen', 'textmaker' ),
			'description' => __( 'Kontaktformular und Team-Spalte. Die Porträts werden unter „teXtmaker → Team“ gepflegt.', 'textmaker' ),
			'fields'      => array(
				'contact_heading'   => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'text',
				),
				'contact_intro'     => array(
					'label' => __( 'Einleitung', 'textmaker' ),
					'type'  => 'text',
				),
				'contact_whatsapp'  => array(
					'label' => __( 'WhatsApp-Link', 'textmaker' ),
					'type'  => 'url',
				),
				'contact_wa_label'  => array(
					'label' => __( 'Beschriftung des WhatsApp-Links', 'textmaker' ),
					'type'  => 'text',
				),
				'contact_recipient' => array(
					'label'       => __( 'Empfängeradresse für Anfragen', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Leer lassen, um die Administrator-Adresse der Website zu verwenden. Mehrere Adressen mit Komma trennen.', 'textmaker' ),
				),
				'contact_success'   => array(
					'label' => __( 'Bestätigungstext nach dem Absenden', 'textmaker' ),
					'type'  => 'textarea',
				),
				'contact_note'      => array(
					'label' => __( 'Hinweis neben dem Absende-Button', 'textmaker' ),
					'type'  => 'text',
				),
			),
		),
		'refs'     => array(
			'title'       => __( 'Referenzen', 'textmaker' ),
			'description' => __( 'Die Arbeiten und die Logo-Reihe werden unter „teXtmaker → Referenzen“ bzw. „Referenz-Logos“ gepflegt.', 'textmaker' ),
			'fields'      => array(
				'refs_heading' => array(
					'label' => __( 'Überschrift', 'textmaker' ),
					'type'  => 'textarea',
				),
			),
		),
		'footer'   => array(
			'title'       => __( 'Fusszeile', 'textmaker' ),
			'description' => __( 'Die Links unter „Rechtliches“ stammen aus dem Menü „Rechtliches (Fusszeile)“.', 'textmaker' ),
			'fields'      => array(
				'footer_services_heading' => array(
					'label' => __( 'Überschrift Dienstleistungen', 'textmaker' ),
					'type'  => 'text',
				),
				'footer_services'         => array(
					'label'       => __( 'Dienstleistungen', 'textmaker' ),
					'type'        => 'textarea',
					'description' => __( 'Ein Punkt pro Zeile.', 'textmaker' ),
				),
				'footer_contact_heading'  => array(
					'label' => __( 'Überschrift Kontakt', 'textmaker' ),
					'type'  => 'text',
				),
				'footer_company'          => array(
					'label' => __( 'Firmenname', 'textmaker' ),
					'type'  => 'text',
				),
				'footer_address'          => array(
					'label'       => __( 'Adresse', 'textmaker' ),
					'type'        => 'textarea',
					'description' => __( 'Eine Zeile pro Adresszeile.', 'textmaker' ),
				),
				'footer_email'            => array(
					'label' => __( 'E-Mail-Adresse', 'textmaker' ),
					'type'  => 'text',
				),
				'footer_phone'            => array(
					'label' => __( 'Telefonnummer (Anzeige)', 'textmaker' ),
					'type'  => 'text',
				),
				'footer_phone_link'       => array(
					'label'       => __( 'Telefonnummer (Wählformat)', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Ohne Leerzeichen, z. B. +41796601106', 'textmaker' ),
				),
				'footer_legal_heading'    => array(
					'label' => __( 'Überschrift Rechtliches', 'textmaker' ),
					'type'  => 'text',
				),
				'footer_copyright'        => array(
					'label' => __( 'Zeile ganz unten', 'textmaker' ),
					'type'  => 'text',
				),
			),
		),
		'tracking' => array(
			'title'       => __( 'Google Tag Manager', 'textmaker' ),
			'description' => __( 'Der Container wird im <head> geladen und mit dem noscript-Fallback direkt nach <body> ergänzt. Im Backend und in der Vorschau ist das Tracking deaktiviert.', 'textmaker' ),
			'fields'      => array(
				'gtm_id' => array(
					'label'       => __( 'Container-ID', 'textmaker' ),
					'type'        => 'text',
					'description' => __( 'Format GTM-XXXXXXX. Leer lassen, um den Tag Manager nicht zu laden.', 'textmaker' ),
				),
			),
		),
	);
}

/**
 * Customizer-Einstellungen registrieren.
 *
 * @param WP_Customize_Manager $wp_customize Customizer-Instanz.
 */
function textmaker_customize_register( WP_Customize_Manager $wp_customize ): void {
	$defaults = textmaker_defaults();

	$wp_customize->add_panel(
		'textmaker_panel',
		array(
			'title'       => __( 'teXtmaker — Inhalte', 'textmaker' ),
			'description' => __( 'Alle Texte der Startseite und der Fusszeile.', 'textmaker' ),
			'priority'    => 10,
		)
	);

	$priority = 10;

	foreach ( textmaker_customizer_sections() as $key => $section ) {
		$wp_customize->add_section(
			'textmaker_section_' . $key,
			array(
				'title'       => $section['title'],
				'description' => $section['description'],
				'panel'       => 'textmaker_panel',
				'priority'    => $priority,
			)
		);

		$priority += 10;

		foreach ( $section['fields'] as $field_key => $field ) {
			$sanitize = match ( $field['type'] ) {
				'url'      => 'esc_url_raw',
				'textarea' => 'sanitize_textarea_field',
				default    => 'sanitize_text_field',
			};

			$wp_customize->add_setting(
				'textmaker_' . $field_key,
				array(
					'default'           => $defaults[ $field_key ] ?? '',
					'sanitize_callback' => $sanitize,
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				'textmaker_' . $field_key,
				array(
					'label'       => $field['label'],
					'description' => $field['description'] ?? '',
					'section'     => 'textmaker_section_' . $key,
					'type'        => 'textarea' === $field['type'] ? 'textarea' : 'text',
				)
			);
		}
	}

	// Hintergrundbild des Heros.
	$wp_customize->add_setting(
		'textmaker_hero_image',
		array(
			'default'           => 0,
			'sanitize_callback' => 'absint',
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'textmaker_hero_image',
			array(
				'label'       => __( 'Hintergrundbild', 'textmaker' ),
				'description' => __( 'Wird über die volle Breite des Startbilds gelegt. Der Import holt es automatisch aus der Live-Domain.', 'textmaker' ),
				'section'     => 'textmaker_section_hero',
				'mime_type'   => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'textmaker_hero_overlay',
		array(
			'default'           => 55,
			'sanitize_callback' => static fn( $value ): int => max( 0, min( 90, absint( $value ) ) ),
			'transport'         => 'refresh',
		)
	);

	$wp_customize->add_control(
		'textmaker_hero_overlay',
		array(
			'label'       => __( 'Abdunklung des Hintergrundbilds (%)', 'textmaker' ),
			'description' => __( 'Je höher der Wert, desto besser lesbar die Überschrift.', 'textmaker' ),
			'section'     => 'textmaker_section_hero',
			'type'        => 'number',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 90,
				'step' => 5,
			),
		)
	);

	// Sichtbarkeit der Abschnitte.
	$wp_customize->add_section(
		'textmaker_section_visibility',
		array(
			'title'       => __( 'Abschnitte ein- und ausblenden', 'textmaker' ),
			'description' => __( 'Ausgeblendete Abschnitte verschwinden aus der Startseite, ihre Inhalte bleiben erhalten.', 'textmaker' ),
			'panel'       => 'textmaker_panel',
			'priority'    => $priority,
		)
	);

	foreach ( textmaker_sections() as $section_key => $label ) {
		$wp_customize->add_setting(
			'textmaker_show_' . $section_key,
			array(
				'default'           => true,
				'sanitize_callback' => static fn( $value ): bool => (bool) $value,
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			'textmaker_show_' . $section_key,
			array(
				'label'   => $label,
				'section' => 'textmaker_section_visibility',
				'type'    => 'checkbox',
			)
		);
	}

	// Farben.
	foreach ( array(
		'accent_color' => __( 'Akzentfarbe', 'textmaker' ),
		'ink_color'    => __( 'Dunkle Flächen', 'textmaker' ),
	) as $color_key => $label ) {
		$wp_customize->add_setting(
			'textmaker_' . $color_key,
			array(
				'default'           => $defaults[ $color_key ],
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'textmaker_' . $color_key,
				array(
					'label'   => $label,
					'section' => 'colors',
				)
			)
		);
	}

	// Live-Vorschau für die Hero-Überschrift.
	if ( $wp_customize->selective_refresh instanceof WP_Customize_Selective_Refresh ) {
		$textmaker_hero_setting = $wp_customize->get_setting( 'textmaker_hero_heading' );

		if ( $textmaker_hero_setting instanceof WP_Customize_Setting ) {
			$textmaker_hero_setting->transport = 'postMessage';
		}

		$wp_customize->selective_refresh->add_partial(
			'textmaker_hero_heading',
			array(
				'selector'        => '.hero h1',
				'render_callback' => static fn(): string => textmaker_hero_heading_html(),
			)
		);
	}
}
add_action( 'customize_register', 'textmaker_customize_register' );

/**
 * Überschrift des Heros inklusive Unterstreichungen.
 *
 * Jedes im Feld „Unterstrichene Wörter“ genannte Wort wird in der Überschrift
 * einmal unterstrichen.
 */
function textmaker_hero_heading_html(): string {
	$html  = esc_html( textmaker_option( 'hero_heading' ) );
	$words = array_filter( array_map( 'trim', explode( ',', textmaker_option( 'hero_underline' ) ) ) );

	foreach ( $words as $word ) {
		$escaped = esc_html( $word );

		$html = (string) preg_replace(
			'/(?<!<u>)\b' . preg_quote( $escaped, '/' ) . '\b/u',
			'<u>' . $escaped . '</u>',
			$html,
			1
		);
	}

	return $html;
}
