<?php
/**
 * Auffindbarkeit: Seitentitel, Meta-Beschreibung, Open Graph und strukturierte Daten.
 *
 * Die strukturierten Daten sind der Teil, der für Antwortmaschinen zählt
 * (Google-Übersichten, ChatGPT, Perplexity & Co.): Sie beschreiben in
 * maschinenlesbarer Form, wer teXtmaker ist, welche Leistungen es gibt und
 * welche Fragen wie beantwortet werden. Ohne das raten die Systeme.
 *
 * Ist bereits ein SEO-Plugin aktiv, hält sich das Theme heraus und liefert
 * nur die strukturierten Daten — doppelte Meta-Tags schaden mehr als sie nützen.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Läuft bereits ein SEO-Plugin?
 */
function textmaker_seo_plugin_active(): bool {
	return defined( 'WPSEO_VERSION' )        // Yoast SEO.
		|| defined( 'RANK_MATH_VERSION' )    // Rank Math.
		|| defined( 'SEOPRESS_VERSION' )     // SEOPress.
		|| class_exists( 'All_in_One_SEO_Pack' );
}

/**
 * Beschreibung der aktuellen Ansicht.
 *
 * Reihenfolge: eigene Beschreibung der Seite, sonst der Textauszug, sonst die
 * im Customizer hinterlegte Standardbeschreibung.
 */
function textmaker_meta_description(): string {
	$description = '';

	if ( is_singular() && ! is_front_page() ) {
		$post = get_post();

		if ( $post instanceof WP_Post ) {
			$custom = (string) get_post_meta( $post->ID, '_tm_description', true );

			$description = '' !== $custom
				? $custom
				: wp_strip_all_tags( (string) get_the_excerpt( $post ) );
		}
	}

	if ( '' === trim( $description ) ) {
		$description = textmaker_option( 'meta_description' );
	}

	$description = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $description ) ) ?? '' );

	// Suchergebnisse schneiden nach rund 160 Zeichen ab.
	if ( mb_strlen( $description ) > 160 ) {
		$description = rtrim( mb_substr( $description, 0, 157 ), " \t\n\r\0\x0B,.;:" ) . '…';
	}

	return $description;
}

/**
 * Bild für die Vorschau in sozialen Netzwerken und Chat-Vorschauen.
 */
function textmaker_share_image(): string {
	if ( is_singular() && has_post_thumbnail() ) {
		$url = get_the_post_thumbnail_url( null, 'large' );

		if ( is_string( $url ) && '' !== $url ) {
			return $url;
		}
	}

	foreach ( array( 'textmaker_hero_image', 'custom_logo' ) as $mod ) {
		$attachment_id = (int) get_theme_mod( $mod, 0 );

		if ( $attachment_id > 0 ) {
			$url = wp_get_attachment_image_url( $attachment_id, 'large' );

			if ( is_string( $url ) && '' !== $url ) {
				return $url;
			}
		}
	}

	return '';
}

/**
 * Meta-Tags im Kopfbereich ausgeben.
 */
function textmaker_meta_tags(): void {
	if ( textmaker_seo_plugin_active() ) {
		return;
	}

	$description = textmaker_meta_description();

	if ( '' !== $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	}

	// Kanonische Adresse — sonst konkurrieren Varianten derselben Seite.
	$canonical = is_singular() ? (string) get_permalink() : home_url( add_query_arg( array(), '/' ) );

	if ( is_front_page() ) {
		$canonical = home_url( '/' );
	}

	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $canonical ) );

	$title = wp_get_document_title();
	$image = textmaker_share_image();

	$tags = array(
		'og:type'        => is_singular() && ! is_front_page() ? 'article' : 'website',
		'og:site_name'   => get_bloginfo( 'name' ),
		'og:locale'      => get_locale(),
		'og:title'       => $title,
		'og:description' => $description,
		'og:url'         => $canonical,
	);

	if ( '' !== $image ) {
		$tags['og:image'] = $image;
	}

	foreach ( $tags as $property => $content ) {
		if ( '' === $content ) {
			continue;
		}

		printf(
			'<meta property="%1$s" content="%2$s">' . "\n",
			esc_attr( $property ),
			esc_attr( $content )
		);
	}

	printf(
		'<meta name="twitter:card" content="%s">' . "\n",
		esc_attr( '' !== $image ? 'summary_large_image' : 'summary' )
	);
}
add_action( 'wp_head', 'textmaker_meta_tags', 3 );

/**
 * Titel der Startseite: Name plus Schlagzeile statt nur „Startseite“.
 *
 * @param array<string, string> $parts Bestandteile des Titels.
 * @return array<string, string>
 */
function textmaker_document_title( array $parts ): array {
	if ( textmaker_seo_plugin_active() ) {
		return $parts;
	}

	if ( is_front_page() ) {
		$tagline = trim( textmaker_option( 'meta_title_suffix' ) );

		$parts['title'] = get_bloginfo( 'name' );

		if ( '' !== $tagline ) {
			$parts['tagline'] = $tagline;
			unset( $parts['site'] );
		}
	}

	return $parts;
}
add_filter( 'document_title_parts', 'textmaker_document_title' );

/**
 * Trennzeichen im Seitentitel.
 */
function textmaker_title_separator(): string {
	return '–';
}
add_filter( 'document_title_separator', 'textmaker_title_separator' );

/**
 * Strukturierte Daten ausgeben.
 *
 * Ein einziger Graph mit allen Knoten — so können Suchmaschinen und
 * Antwortmaschinen die Beziehungen zwischen Anbieter, Website, Leistungen und
 * Fragen auflösen, statt lose Fragmente zu sehen.
 */
function textmaker_structured_data(): void {
	$home     = home_url( '/' );
	$name     = get_bloginfo( 'name' );
	$org_id   = $home . '#anbieter';
	$site_id  = $home . '#website';
	$email    = textmaker_option( 'footer_email' );
	$phone    = textmaker_option( 'footer_phone_link' );
	$address  = textmaker_option_lines( 'footer_address' );
	$logo_id  = (int) get_theme_mod( 'custom_logo', 0 );

	$organisation = array(
		'@type'       => 'ProfessionalService',
		'@id'         => $org_id,
		'name'        => textmaker_option( 'footer_company' ),
		'url'         => $home,
		'description' => textmaker_meta_description(),
		'areaServed'  => array( 'CH', 'DE', 'AT' ),
		'knowsLanguage' => array( 'de', 'fr', 'en' ),
	);

	if ( '' !== $email ) {
		$organisation['email'] = $email;
	}

	if ( '' !== $phone ) {
		$organisation['telephone'] = $phone;
	}

	if ( array() !== $address ) {
		$organisation['address'] = array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $address[0] ?? '',
			'addressLocality' => textmaker_locality( $address[1] ?? '' ),
			'postalCode'      => textmaker_postal_code( $address[1] ?? '' ),
			'addressCountry'  => 'CH',
		);
	}

	if ( $logo_id > 0 ) {
		$logo_url = wp_get_attachment_image_url( $logo_id, 'full' );

		if ( is_string( $logo_url ) ) {
			$organisation['logo']  = $logo_url;
			$organisation['image'] = $logo_url;
		}
	}

	$services = textmaker_option_lines( 'footer_services' );

	if ( array() !== $services ) {
		$organisation['hasOfferCatalog'] = array(
			'@type'           => 'OfferCatalog',
			'name'            => textmaker_option( 'footer_services_heading' ),
			'itemListElement' => array_map(
				static fn( string $service ): array => array(
					'@type' => 'Offer',
					'itemOffered' => array(
						'@type' => 'Service',
						'name'  => $service,
					),
				),
				$services
			),
		);
	}

	$graph = array(
		$organisation,
		array(
			'@type'     => 'WebSite',
			'@id'       => $site_id,
			'url'       => $home,
			'name'      => $name,
			'publisher' => array( '@id' => $org_id ),
			'inLanguage' => get_bloginfo( 'language' ),
		),
	);

	// Fragen und Antworten — der Teil, aus dem Antwortmaschinen direkt zitieren.
	if ( is_front_page() ) {
		$faq = textmaker_faq_schema();

		if ( array() !== $faq ) {
			$graph[] = $faq;
		}

		$rates = textmaker_option_lines( 'prices_rates' );

		if ( array() !== $rates ) {
			$graph[] = array(
				'@type'       => 'Service',
				'@id'         => $home . '#lektorat',
				'name'        => __( 'Lektorat und Korrektorat', 'textmaker' ),
				'serviceType' => __( 'Lektorat, Korrektorat, Übersetzung, Texterstellung', 'textmaker' ),
				'provider'    => array( '@id' => $org_id ),
				'offers'      => array_map(
					static function ( string $rate ): array {
						list( $label, $amount ) = textmaker_split_pair( $rate );

						return array(
							'@type'         => 'Offer',
							'name'          => $label,
							'priceCurrency' => 'CHF',
							'price'         => preg_replace( '/[^0-9.]/', '', $amount ),
							'description'   => $label . ': ' . $amount,
						);
					},
					$rates
				),
			);
		}
	}

	// Brotkrumen auf Unterseiten.
	if ( is_singular() && ! is_front_page() ) {
		$post = get_post();

		if ( $post instanceof WP_Post ) {
			$graph[] = array(
				'@type'           => 'BreadcrumbList',
				'itemListElement' => array(
					array(
						'@type'    => 'ListItem',
						'position' => 1,
						'name'     => $name,
						'item'     => $home,
					),
					array(
						'@type'    => 'ListItem',
						'position' => 2,
						'name'     => get_the_title( $post ),
					),
				),
			);
		}
	}

	$payload = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
	);
}
add_action( 'wp_head', 'textmaker_structured_data', 5 );

/**
 * FAQ-Einträge als strukturierte Daten.
 *
 * @return array<string, mixed> Leeres Array, wenn keine Fragen gepflegt sind.
 */
function textmaker_faq_schema(): array {
	$items = textmaker_get_items( 'tm_faq', 30 );

	if ( array() === $items ) {
		return array();
	}

	$entries = array();

	foreach ( $items as $item ) {
		$answer = trim( wp_strip_all_tags( (string) $item->post_content ) );

		if ( '' === $answer ) {
			continue;
		}

		$entries[] = array(
			'@type'          => 'Question',
			'name'           => get_the_title( $item ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $answer,
			),
		);
	}

	if ( array() === $entries ) {
		return array();
	}

	return array(
		'@type'      => 'FAQPage',
		'@id'        => home_url( '/' ) . '#faq',
		'mainEntity' => $entries,
	);
}

/**
 * Ort aus einer Adresszeile wie „CH-6300 Zug“ lesen.
 *
 * @param string $line Adresszeile.
 */
function textmaker_locality( string $line ): string {
	$line = trim( $line );

	if ( 1 === preg_match( '/^(?:[A-Z]{2}-)?\d{4,5}\s+(.+)$/u', $line, $matches ) ) {
		return trim( $matches[1] );
	}

	return $line;
}

/**
 * Postleitzahl aus einer Adresszeile lesen.
 *
 * @param string $line Adresszeile.
 */
function textmaker_postal_code( string $line ): string {
	if ( 1 === preg_match( '/(\d{4,5})/', $line, $matches ) ) {
		return $matches[1];
	}

	return '';
}

/**
 * Feld für eine eigene Beschreibung an Seiten und Beiträgen.
 */
function textmaker_description_meta_box(): void {
	foreach ( array( 'page', 'post' ) as $type ) {
		add_meta_box(
			'textmaker_description',
			__( 'Beschreibung für Suchmaschinen', 'textmaker' ),
			'textmaker_render_description_box',
			$type,
			'normal',
			'default'
		);
	}
}
add_action( 'add_meta_boxes', 'textmaker_description_meta_box' );

/**
 * Feld für die Beschreibung ausgeben.
 *
 * @param WP_Post $post Aktueller Beitrag.
 */
function textmaker_render_description_box( WP_Post $post ): void {
	if ( textmaker_seo_plugin_active() ) {
		printf(
			'<p class="description">%s</p>',
			esc_html__( 'Es ist ein SEO-Plugin aktiv — die Beschreibung wird dort gepflegt.', 'textmaker' )
		);

		return;
	}

	$value = (string) get_post_meta( $post->ID, '_tm_description', true );

	wp_nonce_field( 'textmaker_save_description', 'textmaker_description_nonce' );

	printf(
		'<textarea name="_tm_description" rows="3" class="large-text" maxlength="200">%1$s</textarea>
		<p class="description">%2$s</p>',
		esc_textarea( $value ),
		esc_html__( 'Ein bis zwei Sätze, rund 150 Zeichen. Bleibt das Feld leer, wird der Textauszug verwendet.', 'textmaker' )
	);
}

/**
 * Beschreibung speichern.
 *
 * @param int $post_id Beitrags-ID.
 */
function textmaker_save_description( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$nonce = isset( $_POST['textmaker_description_nonce'] )
		? sanitize_text_field( wp_unslash( (string) $_POST['textmaker_description_nonce'] ) )
		: '';

	if ( '' === $nonce || ! wp_verify_nonce( $nonce, 'textmaker_save_description' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$value = isset( $_POST['_tm_description'] )
		? sanitize_textarea_field( wp_unslash( (string) $_POST['_tm_description'] ) )
		: '';

	if ( '' === $value ) {
		delete_post_meta( $post_id, '_tm_description' );

		return;
	}

	update_post_meta( $post_id, '_tm_description', $value );
}
add_action( 'save_post', 'textmaker_save_description' );
