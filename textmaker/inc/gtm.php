<?php
/**
 * Google Tag Manager.
 *
 * Ersetzt das Plugin „GTM4WP“: Container im <head>, noscript-Fallback direkt
 * nach <body> und ein dataLayer mit denselben Feldern wie bisher.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Gültige Container-ID oder leerer String.
 */
function textmaker_gtm_id(): string {
	$id = trim( textmaker_option( 'gtm_id' ) );

	if ( 1 !== preg_match( '/^GTM-[A-Z0-9]{4,10}$/', $id ) ) {
		return '';
	}

	// In Vorschau und Backend nicht tracken.
	if ( is_admin() || is_customize_preview() || is_preview() ) {
		return '';
	}

	return $id;
}

/**
 * Werte des dataLayer für die aktuelle Ansicht.
 *
 * @return array<string, string>
 */
function textmaker_data_layer(): array {
	$data = array();

	if ( is_front_page() ) {
		$data['pagePostType'] = 'frontpage';
	} elseif ( is_singular() ) {
		$data['pagePostType'] = (string) get_post_type();
	} elseif ( is_search() ) {
		$data['pagePostType'] = 'search';
	} else {
		$data['pagePostType'] = 'archive';
	}

	if ( is_singular() ) {
		$post = get_post();

		if ( $post instanceof WP_Post ) {
			$data['pagePostType2'] = 'single-' . $post->post_type;
			$author                = get_the_author_meta( 'display_name', (int) $post->post_author );

			if ( '' !== $author ) {
				$data['pagePostAuthor'] = $author;
			}
		}
	}

	return (array) apply_filters( 'textmaker_data_layer', $data );
}

/**
 * Container-Skript im <head>.
 */
function textmaker_gtm_head(): void {
	$id = textmaker_gtm_id();

	if ( '' === $id ) {
		return;
	}

	printf(
		'<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push(%s);</script>' . "\n",
		wp_json_encode( textmaker_data_layer(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);

	?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js( $id ); ?>');</script>
<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'textmaker_gtm_head', 1 );

/**
 * noscript-Fallback direkt nach dem öffnenden <body>.
 */
function textmaker_gtm_body(): void {
	$id = textmaker_gtm_id();

	if ( '' === $id ) {
		return;
	}

	printf(
		'<!-- Google Tag Manager (noscript) -->' .
		'<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden" aria-hidden="true"></iframe></noscript>' .
		'<!-- End Google Tag Manager (noscript) -->' . "\n",
		esc_attr( $id )
	);
}
add_action( 'wp_body_open', 'textmaker_gtm_body', 1 );

/**
 * Ein Ereignis in den dataLayer schieben — genutzt nach dem Absenden des Formulars.
 *
 * @param string               $event Ereignisname.
 * @param array<string, mixed> $data  Zusätzliche Felder.
 */
function textmaker_push_event( string $event, array $data = array() ): string {
	$payload = array_merge( array( 'event' => $event ), $data );

	return sprintf(
		'<script>window.dataLayer=window.dataLayer||[];window.dataLayer.push(%s);</script>',
		wp_json_encode( $payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
	);
}
