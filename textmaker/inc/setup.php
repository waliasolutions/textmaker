<?php
/**
 * Theme-Support, Menüs und Assets.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Grundlegender Theme-Support.
 */
function textmaker_setup(): void {
	load_theme_textdomain( 'textmaker', TEXTMAKER_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/main.css' );

	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 266,
			'width'       => 938,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Hauptmenü', 'textmaker' ),
			'legal'   => __( 'Rechtliches (Fusszeile)', 'textmaker' ),
		)
	);

	// Bildgrössen für Galerien und Team-Porträts.
	add_image_size( 'textmaker-portrait', 400, 400, true );
	add_image_size( 'textmaker-reference', 600, 800, false );
	add_image_size( 'textmaker-step', 1200, 900, false );
}
add_action( 'after_setup_theme', 'textmaker_setup' );

/**
 * Inhaltsbreite für eingebettete Medien.
 */
function textmaker_content_width(): void {
	$GLOBALS['content_width'] = 1200;
}
add_action( 'after_setup_theme', 'textmaker_content_width', 0 );

/**
 * Frontend-Assets einbinden.
 */
function textmaker_assets(): void {
	$css = TEXTMAKER_DIR . '/assets/css/main.css';
	$js  = TEXTMAKER_DIR . '/assets/js/main.js';

	wp_enqueue_style(
		'textmaker',
		TEXTMAKER_URI . '/assets/css/main.css',
		array(),
		file_exists( $css ) ? (string) filemtime( $css ) : TEXTMAKER_VERSION
	);

	wp_add_inline_style( 'textmaker', textmaker_palette_css() );

	wp_enqueue_script(
		'textmaker',
		TEXTMAKER_URI . '/assets/js/main.js',
		array(),
		file_exists( $js ) ? (string) filemtime( $js ) : TEXTMAKER_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	wp_localize_script(
		'textmaker',
		'textmakerData',
		array(
			'ajaxUrl' => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
			'nonce'   => wp_create_nonce( 'textmaker_contact' ),
			'i18n'    => array(
				'chooseFile' => __( 'Datei wählen — DOC, DOCX, PDF, ODT, RTF, TXT', 'textmaker' ),
				'filesPick'  => __( '%d Dateien ausgewählt', 'textmaker' ),
				'sending'    => __( 'Wird gesendet …', 'textmaker' ),
				'submit'     => __( 'Offerte anfragen', 'textmaker' ),
				'network'    => __( 'Die Anfrage konnte nicht gesendet werden. Bitte versuche es erneut oder schreib uns an staff@textmaker.ch.', 'textmaker' ),
			),
		)
	);

	// Google-Rezensionen über das bestehende Elfsight-Widget.
	if ( is_front_page() && '' !== trim( textmaker_option( 'reviews_elfsight' ) ) && textmaker_show_section( 'reviews' ) ) {
		wp_enqueue_script(
			'elfsight-platform',
			'https://static.elfsight.com/platform/platform.js',
			array(),
			null, // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Vom Anbieter versioniert.
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'textmaker_assets' );

/**
 * Farbtokens aus dem Customizer als Inline-CSS.
 */
function textmaker_palette_css(): string {
	$accent = textmaker_option( 'accent_color', '#7ED321' );
	$ink    = textmaker_option( 'ink_color', '#0F1512' );

	return sprintf(
		':root{--accent:%1$s;--accent-deep:%2$s;--ink:%3$s;}' .
		':root[data-theme="light"]{--accent:%1$s;--accent-deep:%2$s;}',
		esc_attr( $accent ),
		esc_attr( textmaker_shade( $accent, -0.32 ) ),
		esc_attr( $ink )
	);
}

/**
 * Hex-Farbe aufhellen (positiver Faktor) oder abdunkeln (negativer Faktor).
 *
 * @param string $hex    Farbe im Format #RGB oder #RRGGBB.
 * @param float  $amount Faktor zwischen -1 und 1.
 */
function textmaker_shade( string $hex, float $amount ): string {
	$hex = ltrim( $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
		return '#' . $hex;
	}

	$out = '#';

	for ( $i = 0; $i < 3; $i++ ) {
		$channel = (int) hexdec( substr( $hex, $i * 2, 2 ) );
		$target  = $amount < 0 ? 0 : 255;
		$channel = (int) round( $channel + ( $target - $channel ) * abs( $amount ) );
		$out    .= str_pad( dechex( max( 0, min( 255, $channel ) ) ), 2, '0', STR_PAD_LEFT );
	}

	return $out;
}

/**
 * Body-Klassen ergänzen.
 *
 * @param array<int, string> $classes Bestehende Klassen.
 * @return array<int, string>
 */
function textmaker_body_class( array $classes ): array {
	$classes[] = 'textmaker';

	if ( is_front_page() ) {
		$classes[] = 'textmaker-front';
	}

	return $classes;
}
add_filter( 'body_class', 'textmaker_body_class' );

/**
 * Elementor- und WooCommerce-Altlasten aus dem Frontend halten.
 *
 * Das Theme kommt ohne Page-Builder aus; falls die Plugins noch aktiv sind,
 * werden ihre Frontend-Assets auf der Startseite nicht mehr benötigt.
 */
function textmaker_dequeue_legacy(): void {
	if ( ! is_front_page() ) {
		return;
	}

	foreach ( array( 'elementor-frontend', 'elementor-post-157', 'elementor-pro-frontend', 'e-swiper', 'swiper' ) as $handle ) {
		wp_dequeue_style( $handle );
		wp_dequeue_script( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'textmaker_dequeue_legacy', 100 );

/**
 * Emoji-Skript entfernen — spart einen Request, ohne Funktionsverlust.
 */
function textmaker_disable_emojis(): void {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'textmaker_disable_emojis' );

/**
 * Erlaubte Upload-Typen für das Kontaktformular.
 *
 * @return array<string, string> Erweiterung => MIME-Type.
 */
function textmaker_allowed_upload_types(): array {
	return (array) apply_filters(
		'textmaker_allowed_upload_types',
		array(
			'doc'  => 'application/msword',
			'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'odt'  => 'application/vnd.oasis.opendocument.text',
			'pdf'  => 'application/pdf',
			'rtf'  => 'application/rtf',
			'txt'  => 'text/plain',
		)
	);
}
