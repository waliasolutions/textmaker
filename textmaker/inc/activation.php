<?php
/**
 * Einrichtung beim Aktivieren des Themes.
 *
 * Legt die Startseite an, trägt sie unter „Einstellungen → Lesen“ ein und
 * baut die beiden Menüs auf. Ohne diesen Schritt gäbe es keine Seite „Home“,
 * die sich auswählen oder aufrufen liesse.
 *
 * Alles ist idempotent: Vorhandenes wird erkannt und nicht überschrieben.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Nach dem Wechsel auf dieses Theme einmalig einrichten.
 */
function textmaker_after_switch_theme(): void {
	textmaker_ensure_front_page();
	textmaker_ensure_menus();
}
add_action( 'after_switch_theme', 'textmaker_after_switch_theme' );

/**
 * Startseite anlegen und als statische Startseite eintragen.
 *
 * @return int ID der Startseite, 0 bei Fehlschlag.
 */
function textmaker_ensure_front_page(): int {
	// Ist bereits eine statische Startseite gesetzt, bleibt sie unangetastet.
	$existing = (int) get_option( 'page_on_front' );

	if ( $existing > 0 && 'publish' === get_post_status( $existing ) ) {
		update_option( 'show_on_front', 'page' );

		return $existing;
	}

	$page = get_page_by_path( 'home' );

	if ( ! $page instanceof WP_Post ) {
		// Fällt auf eine bestehende Seite mit dem Titel „Home“ zurück.
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'title'          => 'Home',
				'posts_per_page' => 1,
			)
		);

		$page = $found[0] ?? null;
	}

	if ( ! $page instanceof WP_Post ) {
		$page_id = wp_insert_post(
			array(
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => __( 'Home', 'textmaker' ),
				'post_name'    => 'home',
				'post_content' => '',
			),
			true
		);

		if ( is_wp_error( $page_id ) ) {
			return 0;
		}

		$page_id = (int) $page_id;
	} else {
		$page_id = (int) $page->ID;

		if ( 'publish' !== $page->post_status ) {
			wp_update_post(
				array(
					'ID'          => $page_id,
					'post_status' => 'publish',
				)
			);
		}
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $page_id );

	return $page_id;
}

/**
 * Haupt- und Fussmenü aufbauen, falls noch keines zugewiesen ist.
 */
function textmaker_ensure_menus(): void {
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	$locations = is_array( $locations ) ? $locations : array();

	// Hauptmenü.
	if ( empty( $locations['primary'] ) ) {
		$menu_id = textmaker_create_menu( __( 'Hauptmenü', 'textmaker' ) );

		if ( $menu_id > 0 ) {
			// Kein „Home“-Eintrag: das Logo führt bereits zur Startseite.
			$anchors = array(
				'#lektorat'   => __( 'Lektorat-Service', 'textmaker' ),
				'#preise'     => __( 'Preise', 'textmaker' ),
				'#referenzen' => __( 'Referenzen', 'textmaker' ),
				'#anfragen'   => __( 'Offerte anfragen', 'textmaker' ),
			);

			foreach ( $anchors as $anchor => $label ) {
				textmaker_add_menu_item(
					$menu_id,
					array(
						'menu-item-title' => $label,
						'menu-item-url'   => home_url( '/' ) . $anchor,
						'menu-item-type'  => 'custom',
					)
				);
			}

			$locations['primary'] = $menu_id;
		}
	}

	// Fussmenü „Rechtliches“ — nur mit bereits vorhandenen Seiten.
	if ( empty( $locations['legal'] ) ) {
		$legal = array(
			'agb'         => __( 'AGB', 'textmaker' ),
			'datenschutz' => __( 'Datenschutz', 'textmaker' ),
			'impressum'   => __( 'Impressum', 'textmaker' ),
		);

		$items = array();

		foreach ( $legal as $slug => $label ) {
			$page = get_page_by_path( $slug );

			if ( $page instanceof WP_Post ) {
				$items[ $page->ID ] = $label;
			}
		}

		if ( array() !== $items ) {
			$menu_id = textmaker_create_menu( __( 'Rechtliches', 'textmaker' ) );

			if ( $menu_id > 0 ) {
				foreach ( $items as $page_id => $label ) {
					textmaker_add_menu_item(
						$menu_id,
						array(
							'menu-item-title'     => $label,
							'menu-item-object'    => 'page',
							'menu-item-object-id' => (int) $page_id,
							'menu-item-type'      => 'post_type',
						)
					);
				}

				$locations['legal'] = $menu_id;
			}
		}
	}

	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Menü anlegen oder ein gleichnamiges wiederverwenden.
 *
 * @param string $name Name des Menüs.
 * @return int Menü-ID, 0 bei Fehlschlag.
 */
function textmaker_create_menu( string $name ): int {
	$existing = wp_get_nav_menu_object( $name );

	if ( $existing instanceof WP_Term ) {
		return (int) $existing->term_id;
	}

	$menu_id = wp_create_nav_menu( $name );

	return is_wp_error( $menu_id ) ? 0 : (int) $menu_id;
}

/**
 * Eintrag zu einem Menü hinzufügen.
 *
 * @param int                  $menu_id Menü-ID.
 * @param array<string, mixed> $args    Argumente für wp_update_nav_menu_item().
 */
function textmaker_add_menu_item( int $menu_id, array $args ): void {
	wp_update_nav_menu_item(
		$menu_id,
		0,
		array_merge( array( 'menu-item-status' => 'publish' ), $args )
	);
}

/**
 * Hinweis auf der Startseite im Backend.
 *
 * Der Inhalt der Startseite kommt nicht aus dem Editor, sondern aus dem
 * Customizer und den Inhaltstypen. Ohne Hinweis sucht man dort vergeblich.
 */
function textmaker_front_page_notice(): void {
	$screen = get_current_screen();

	if ( ! $screen instanceof WP_Screen || 'page' !== $screen->id ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	if ( 0 === $post_id || $post_id !== (int) get_option( 'page_on_front' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-info"><p>%1$s</p><p>
			<a class="button" href="%2$s">%3$s</a>
			<a class="button" href="%4$s">%5$s</a>
		</p></div>',
		esc_html__( 'Das ist die Startseite. Ihre Inhalte stammen nicht aus diesem Editor, sondern aus dem Customizer und den teXtmaker-Bereichen — was du hier schreibst, erscheint nicht im Frontend.', 'textmaker' ),
		esc_url( admin_url( 'customize.php' ) ),
		esc_html__( 'Texte im Customizer bearbeiten', 'textmaker' ),
		esc_url( admin_url( 'admin.php?page=' . TEXTMAKER_MENU_SLUG ) ),
		esc_html__( 'Zur teXtmaker-Übersicht', 'textmaker' )
	);
}
add_action( 'admin_notices', 'textmaker_front_page_notice' );

/**
 * Hinweis, solange keine statische Startseite eingetragen ist.
 */
function textmaker_missing_front_page_notice(): void {
	if ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) > 0 ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%1$s <a href="%2$s">%3$s</a></p></div>',
		esc_html__( 'Für teXtmaker ist noch keine statische Startseite eingetragen.', 'textmaker' ),
		esc_url( admin_url( 'options-reading.php' ) ),
		esc_html__( 'Jetzt unter „Einstellungen → Lesen“ festlegen', 'textmaker' )
	);
}
add_action( 'admin_notices', 'textmaker_missing_front_page_notice' );
