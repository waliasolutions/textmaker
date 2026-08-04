<?php
/**
 * Inhaltstypen: Team, Ablaufschritte, Referenzen, Logos, Kundenmeinungen, Anfragen.
 *
 * Alle Bereiche der Startseite lassen sich damit im Backend pflegen, ohne dass
 * ein Page-Builder nötig ist. Die Reihenfolge steuert das Feld „Reihenfolge“
 * (menu_order) unter „Seiten-Attribute“.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

const TEXTMAKER_MENU_SLUG = 'textmaker-home';

/**
 * Definition aller pflegbaren Inhaltstypen.
 *
 * @return array<string, array{singular: string, plural: string, supports: array<int, string>}>
 */
function textmaker_post_type_config(): array {
	return array(
		'tm_team'        => array(
			'singular' => __( 'Teammitglied', 'textmaker' ),
			'plural'   => __( 'Team', 'textmaker' ),
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		),
		'tm_step'        => array(
			'singular' => __( 'Ablaufschritt', 'textmaker' ),
			'plural'   => __( 'Ablauf der Korrektur', 'textmaker' ),
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		),
		'tm_reference'   => array(
			'singular' => __( 'Referenz', 'textmaker' ),
			'plural'   => __( 'Referenzen', 'textmaker' ),
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		),
		'tm_logo'        => array(
			'singular' => __( 'Referenz-Logo', 'textmaker' ),
			'plural'   => __( 'Referenz-Logos', 'textmaker' ),
			'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
		),
		'tm_testimonial' => array(
			'singular' => __( 'Kundenmeinung', 'textmaker' ),
			'plural'   => __( 'Kundenmeinungen', 'textmaker' ),
			'supports' => array( 'title', 'editor', 'page-attributes' ),
		),
		'tm_faq'         => array(
			'singular' => __( 'Frage & Antwort', 'textmaker' ),
			'plural'   => __( 'Fragen & Antworten', 'textmaker' ),
			'supports' => array( 'title', 'editor', 'page-attributes' ),
		),
	);
}

/**
 * Sammelmenü „teXtmaker“ im Backend.
 */
function textmaker_admin_menu(): void {
	add_menu_page(
		__( 'teXtmaker', 'textmaker' ),
		__( 'teXtmaker', 'textmaker' ),
		'edit_posts',
		TEXTMAKER_MENU_SLUG,
		'textmaker_render_dashboard',
		'dashicons-edit-large',
		3
	);

	add_submenu_page(
		TEXTMAKER_MENU_SLUG,
		__( 'Übersicht', 'textmaker' ),
		__( 'Übersicht', 'textmaker' ),
		'edit_posts',
		TEXTMAKER_MENU_SLUG,
		'textmaker_render_dashboard'
	);
}
add_action( 'admin_menu', 'textmaker_admin_menu', 5 );

/**
 * Startseite des Sammelmenüs: kurze Wegweiser zu allen Bereichen.
 */
function textmaker_render_dashboard(): void {
	$areas = array(
		array(
			'title' => __( 'Texte der Startseite', 'textmaker' ),
			'desc'  => __( 'Überschriften, Fliesstexte, Preise, Kontaktangaben, Google Tag Manager und die Sichtbarkeit einzelner Abschnitte.', 'textmaker' ),
			'url'   => admin_url( 'customize.php' ),
			'label' => __( 'Im Customizer öffnen', 'textmaker' ),
		),
		array(
			'title' => __( 'Team', 'textmaker' ),
			'desc'  => __( 'Porträts und Funktionen der Mitarbeitenden im Abschnitt „Offerte anfragen“.', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_team' ),
			'label' => __( 'Team bearbeiten', 'textmaker' ),
		),
		array(
			'title' => __( 'Referenzen', 'textmaker' ),
			'desc'  => __( 'Die Arbeiten im Referenz-Karussell — Bild, Publikation und Autorin bzw. Autor.', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_reference' ),
			'label' => __( 'Referenzen bearbeiten', 'textmaker' ),
		),
		array(
			'title' => __( 'Referenz-Logos', 'textmaker' ),
			'desc'  => __( 'Die Logo-Reihe unter dem Referenz-Karussell (NZZ, MAF, Cockpit …).', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_logo' ),
			'label' => __( 'Logos bearbeiten', 'textmaker' ),
		),
		array(
			'title' => __( 'Ablauf der Korrektur', 'textmaker' ),
			'desc'  => __( 'Die Screenshots samt Bildunterschriften im Ablauf-Karussell.', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_step' ),
			'label' => __( 'Ablauf bearbeiten', 'textmaker' ),
		),
		array(
			'title' => __( 'Kundenmeinungen', 'textmaker' ),
			'desc'  => __( 'Wird nur angezeigt, wenn im Customizer keine Elfsight-App-ID hinterlegt ist.', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_testimonial' ),
			'label' => __( 'Kundenmeinungen bearbeiten', 'textmaker' ),
		),
		array(
			'title' => __( 'Fragen & Antworten', 'textmaker' ),
			'desc'  => __( 'Kurze, klare Antworten auf häufige Fragen. Sie erscheinen auf der Startseite und werden zusätzlich maschinenlesbar ausgeliefert, damit Suchmaschinen und KI-Assistenten daraus zitieren können.', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_faq' ),
			'label' => __( 'Fragen bearbeiten', 'textmaker' ),
		),
		array(
			'title' => __( 'Anfragen', 'textmaker' ),
			'desc'  => __( 'Alle über das Kontaktformular eingegangenen Offertanfragen samt Dokumenten.', 'textmaker' ),
			'url'   => admin_url( 'edit.php?post_type=tm_submission' ),
			'label' => __( 'Anfragen ansehen', 'textmaker' ),
		),
		array(
			'title' => __( 'Bilder importieren', 'textmaker' ),
			'desc'  => __( 'Übernimmt Logo, Team-Porträts, Ablauf-Screenshots und Referenzen aus der Live-Domain in die Mediathek.', 'textmaker' ),
			'url'   => admin_url( 'admin.php?page=textmaker-import' ),
			'label' => __( 'Import starten', 'textmaker' ),
		),
	);

	echo '<div class="wrap">';
	printf( '<h1>%s</h1>', esc_html__( 'teXtmaker', 'textmaker' ) );
	printf(
		'<p class="description" style="max-width:60em;">%s</p>',
		esc_html__( 'Jeder Abschnitt der Startseite hat hier seinen eigenen Bereich. Änderungen sind sofort im Frontend sichtbar — ein Page-Builder wird nicht benötigt.', 'textmaker' )
	);

	echo '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;margin-top:1.5rem;">';

	foreach ( $areas as $area ) {
		printf(
			'<div class="card" style="margin:0;max-width:none;padding:1.25rem;">
				<h2 style="margin-top:0;font-size:1.05rem;">%1$s</h2>
				<p style="color:#50575e;">%2$s</p>
				<a class="button button-secondary" href="%3$s">%4$s</a>
			</div>',
			esc_html( $area['title'] ),
			esc_html( $area['desc'] ),
			esc_url( $area['url'] ),
			esc_html( $area['label'] )
		);
	}

	echo '</div>';

	// Startseite und Menüs.
	$setup_result = get_transient( 'textmaker_setup_result' );

	if ( is_string( $setup_result ) && '' !== $setup_result ) {
		delete_transient( 'textmaker_setup_result' );
		printf( '<div class="notice notice-success" style="margin-top:1.5rem;"><p>%s</p></div>', esc_html( $setup_result ) );
	}

	$front_id = (int) get_option( 'page_on_front' );

	echo '<hr style="margin:2rem 0 1.5rem;">';
	printf( '<h2>%s</h2>', esc_html__( 'Startseite und Menüs', 'textmaker' ) );

	if ( $front_id > 0 && 'page' === get_option( 'show_on_front' ) ) {
		printf(
			'<p style="max-width:60em;">%1$s <a href="%2$s"><strong>%3$s</strong></a></p>',
			esc_html__( 'Als Startseite ist eingetragen:', 'textmaker' ),
			esc_url( (string) get_edit_post_link( $front_id ) ),
			esc_html( (string) get_the_title( $front_id ) )
		);
	} else {
		printf(
			'<p class="notice notice-warning" style="padding:.75rem 1rem;max-width:60em;">%s</p>',
			esc_html__( 'Es ist noch keine statische Startseite eingetragen. Der Knopf unten legt die Seite „Home“ an, trägt sie ein und baut die Menüs auf.', 'textmaker' )
		);
	}

	printf(
		'<p class="description" style="max-width:60em;">%s</p>',
		esc_html__( 'Legt die Seite „Home“ an, trägt sie unter „Einstellungen → Lesen“ ein, baut Haupt- und Fussmenü auf und erneuert die Umschreiberegeln für /sitemap.xml. Eine bereits gewählte Startseite bleibt unangetastet.', 'textmaker' )
	);

	echo '<form method="post">';
	wp_nonce_field( 'textmaker_run_setup' );
	echo '<input type="hidden" name="textmaker_run_setup" value="1">';
	printf( '<button type="submit" class="button">%s</button>', esc_html__( 'Startseite und Menüs einrichten', 'textmaker' ) );
	echo '</form>';

	// Zustellung des Kontaktformulars prüfen.
	$result = get_transient( 'textmaker_test_mail_result' );

	if ( is_array( $result ) ) {
		delete_transient( 'textmaker_test_mail_result' );
		printf(
			'<div class="notice notice-%1$s" style="margin-top:1.5rem;"><p>%2$s</p></div>',
			esc_attr( (string) $result[0] ),
			esc_html( (string) $result[1] )
		);
	}

	$recipient = textmaker_notification_recipient();

	echo '<hr style="margin:2rem 0 1.5rem;">';
	printf( '<h2>%s</h2>', esc_html__( 'Zustellung des Kontaktformulars', 'textmaker' ) );

	printf(
		'<p style="max-width:60em;">%1$s <code>%2$s</code></p>',
		esc_html__( 'Anfragen gehen an:', 'textmaker' ),
		esc_html( array() !== $recipient ? implode( ', ', $recipient ) : __( 'keine gültige Adresse hinterlegt', 'textmaker' ) )
	);

	printf(
		'<p class="description" style="max-width:60em;">%s</p>',
		esc_html__( 'Der Versand läuft über wp_mail(). Ob eine Nachricht tatsächlich ankommt, entscheidet der Server: Viele Hoster verschicken ohne SPF- und DKIM-Signatur, solche Mails landen im Spam oder werden verworfen. Für verlässliche Zustellung ein SMTP-Plugin einrichten und dort die eigene Domain als Absender hinterlegen.', 'textmaker' )
	);

	echo '<form method="post">';
	wp_nonce_field( 'textmaker_test_mail' );
	echo '<input type="hidden" name="textmaker_test_mail" value="1">';
	printf( '<button type="submit" class="button">%s</button>', esc_html__( 'Testmail senden', 'textmaker' ) );
	echo '</form>';

	echo '</div>';
}

/**
 * Inhaltstypen registrieren.
 */
function textmaker_register_post_types(): void {
	foreach ( textmaker_post_type_config() as $slug => $config ) {
		register_post_type(
			$slug,
			array(
				'labels'          => array(
					'name'               => $config['plural'],
					'singular_name'      => $config['singular'],
					'menu_name'          => $config['plural'],
					'add_new'            => __( 'Neu hinzufügen', 'textmaker' ),
					/* translators: %s: Bezeichnung des Inhaltstyps. */
					'add_new_item'       => sprintf( __( '%s hinzufügen', 'textmaker' ), $config['singular'] ),
					/* translators: %s: Bezeichnung des Inhaltstyps. */
					'edit_item'          => sprintf( __( '%s bearbeiten', 'textmaker' ), $config['singular'] ),
					/* translators: %s: Bezeichnung des Inhaltstyps. */
					'search_items'       => sprintf( __( '%s durchsuchen', 'textmaker' ), $config['plural'] ),
					'not_found'          => __( 'Noch keine Einträge vorhanden.', 'textmaker' ),
					'featured_image'     => __( 'Bild', 'textmaker' ),
					'set_featured_image' => __( 'Bild festlegen', 'textmaker' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => TEXTMAKER_MENU_SLUG,
				'show_in_rest'    => true,
				'supports'        => $config['supports'],
				'has_archive'     => false,
				'rewrite'         => false,
				'capability_type' => 'post',
			)
		);
	}

	// Eingegangene Anfragen — nur lesend, es wird nichts von Hand angelegt.
	register_post_type(
		'tm_submission',
		array(
			'labels'          => array(
				'name'          => __( 'Anfragen', 'textmaker' ),
				'singular_name' => __( 'Anfrage', 'textmaker' ),
				'menu_name'     => __( 'Anfragen', 'textmaker' ),
				'not_found'     => __( 'Noch keine Anfragen eingegangen.', 'textmaker' ),
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => TEXTMAKER_MENU_SLUG,
			'show_in_rest'    => false,
			'supports'        => array( 'title' ),
			'has_archive'     => false,
			'rewrite'         => false,
			'capability_type' => 'post',
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'    => true,
		)
	);
}
add_action( 'init', 'textmaker_register_post_types' );

/**
 * Zusatzfelder je Inhaltstyp.
 *
 * @return array<string, array<string, array{label: string, type: string, description?: string}>>
 */
function textmaker_meta_fields(): array {
	return array(
		'tm_team'        => array(
			'_tm_role' => array(
				'label'       => __( 'Funktion', 'textmaker' ),
				'type'        => 'text',
				'description' => __( 'Erscheint unter dem Namen, z. B. „Lektorate, DE / FR“.', 'textmaker' ),
			),
		),
		'tm_step'        => array(
			'_tm_caption' => array(
				'label'       => __( 'Bildunterschrift', 'textmaker' ),
				'type'        => 'textarea',
				'description' => __( 'Erscheint unter dem Screenshot im Ablauf-Karussell.', 'textmaker' ),
			),
		),
		'tm_reference'   => array(
			'_tm_publication' => array(
				'label'       => __( 'Publikation', 'textmaker' ),
				'type'        => 'text',
				'description' => __( 'z. B. NZZ, AeroRevue, Cockpit, 4teens', 'textmaker' ),
			),
			'_tm_byline'      => array(
				'label' => __( 'Autorin / Autor', 'textmaker' ),
				'type'  => 'text',
			),
		),
		'tm_logo'        => array(
			'_tm_link' => array(
				'label'       => __( 'Link (optional)', 'textmaker' ),
				'type'        => 'url',
				'description' => __( 'Wird das Logo verlinkt, öffnet der Link in einem neuen Tab.', 'textmaker' ),
			),
		),
		'tm_testimonial' => array(
			'_tm_author' => array(
				'label' => __( 'Name der Kundin / des Kunden', 'textmaker' ),
				'type'  => 'text',
			),
			'_tm_source' => array(
				'label'       => __( 'Quelle / Firma', 'textmaker' ),
				'type'        => 'text',
				'description' => __( 'z. B. Google-Rezension oder Firmenname.', 'textmaker' ),
			),
			'_tm_rating' => array(
				'label'       => __( 'Sterne (1–5)', 'textmaker' ),
				'type'        => 'number',
				'description' => __( 'Leer lassen, um keine Sterne anzuzeigen.', 'textmaker' ),
			),
		),
	);
}

/**
 * Metafelder für REST-API und Block-Editor registrieren.
 */
function textmaker_register_meta(): void {
	foreach ( textmaker_meta_fields() as $post_type => $fields ) {
		foreach ( $fields as $key => $field ) {
			$is_number = 'number' === $field['type'];

			register_post_meta(
				$post_type,
				$key,
				array(
					'single'            => true,
					'type'              => $is_number ? 'integer' : 'string',
					'show_in_rest'      => true,
					'sanitize_callback' => $is_number ? 'absint' : ( 'url' === $field['type'] ? 'esc_url_raw' : 'sanitize_text_field' ),
					'auth_callback'     => static fn(): bool => current_user_can( 'edit_posts' ),
				)
			);
		}
	}
}
add_action( 'init', 'textmaker_register_meta' );

/**
 * Meta-Boxen registrieren.
 */
function textmaker_add_meta_boxes(): void {
	foreach ( array_keys( textmaker_meta_fields() ) as $post_type ) {
		add_meta_box(
			'textmaker_details',
			__( 'Details', 'textmaker' ),
			'textmaker_render_meta_box',
			$post_type,
			'normal',
			'high'
		);
	}

	add_meta_box(
		'textmaker_submission',
		__( 'Inhalt der Anfrage', 'textmaker' ),
		'textmaker_render_submission_box',
		'tm_submission',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'textmaker_add_meta_boxes' );

/**
 * Felder der Meta-Box ausgeben.
 *
 * @param WP_Post $post Aktueller Beitrag.
 */
function textmaker_render_meta_box( WP_Post $post ): void {
	$fields = textmaker_meta_fields()[ $post->post_type ] ?? array();

	wp_nonce_field( 'textmaker_save_meta', 'textmaker_meta_nonce' );

	echo '<div style="display:grid;gap:1rem;padding:.5rem 0;max-width:44em;">';

	foreach ( $fields as $key => $field ) {
		$value = (string) get_post_meta( $post->ID, $key, true );
		$id    = 'tm-field-' . sanitize_key( $key );

		echo '<p style="margin:0;">';
		printf(
			'<label for="%1$s" style="display:block;font-weight:600;margin-bottom:.25rem;">%2$s</label>',
			esc_attr( $id ),
			esc_html( $field['label'] )
		);

		switch ( $field['type'] ) {
			case 'textarea':
				printf(
					'<textarea id="%1$s" name="%2$s" rows="3" class="large-text">%3$s</textarea>',
					esc_attr( $id ),
					esc_attr( $key ),
					esc_textarea( $value )
				);
				break;

			case 'number':
				printf(
					'<input type="number" min="1" max="5" step="1" id="%1$s" name="%2$s" value="%3$s" class="small-text">',
					esc_attr( $id ),
					esc_attr( $key ),
					esc_attr( $value )
				);
				break;

			case 'url':
				printf(
					'<input type="url" id="%1$s" name="%2$s" value="%3$s" class="large-text" placeholder="https://">',
					esc_attr( $id ),
					esc_attr( $key ),
					esc_attr( $value )
				);
				break;

			default:
				printf(
					'<input type="text" id="%1$s" name="%2$s" value="%3$s" class="large-text">',
					esc_attr( $id ),
					esc_attr( $key ),
					esc_attr( $value )
				);
		}

		if ( isset( $field['description'] ) ) {
			printf( '<span class="description" style="display:block;margin-top:.25rem;">%s</span>', esc_html( $field['description'] ) );
		}

		echo '</p>';
	}

	if ( in_array( $post->post_type, array( 'tm_team', 'tm_step', 'tm_reference', 'tm_logo' ), true ) ) {
		printf(
			'<p class="description" style="margin:0;">%s</p>',
			esc_html__( 'Das Bild wird rechts unter „Bild“ gesetzt. Die Position im Karussell steuert das Feld „Reihenfolge“ unter „Seiten-Attribute“ — kleinere Zahlen kommen zuerst.', 'textmaker' )
		);
	}

	echo '</div>';
}

/**
 * Meta-Werte speichern.
 *
 * @param int $post_id Beitrags-ID.
 */
function textmaker_save_meta( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	$nonce = isset( $_POST['textmaker_meta_nonce'] )
		? sanitize_text_field( wp_unslash( (string) $_POST['textmaker_meta_nonce'] ) )
		: '';

	if ( '' === $nonce || ! wp_verify_nonce( $nonce, 'textmaker_save_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = (string) get_post_type( $post_id );
	$fields    = textmaker_meta_fields()[ $post_type ] ?? array();

	foreach ( $fields as $key => $field ) {
		if ( 'number' === $field['type'] ) {
			$raw   = isset( $_POST[ $key ] ) ? absint( wp_unslash( $_POST[ $key ] ) ) : 0;
			$value = $raw > 0 ? (string) min( 5, $raw ) : '';
		} elseif ( 'textarea' === $field['type'] ) {
			$value = isset( $_POST[ $key ] ) ? sanitize_textarea_field( wp_unslash( (string) $_POST[ $key ] ) ) : '';
		} elseif ( 'url' === $field['type'] ) {
			$value = isset( $_POST[ $key ] ) ? esc_url_raw( wp_unslash( (string) $_POST[ $key ] ) ) : '';
		} else {
			$value = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( (string) $_POST[ $key ] ) ) : '';
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, $key );
			continue;
		}

		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post', 'textmaker_save_meta' );

/**
 * Eingegangene Anfrage im Backend darstellen.
 *
 * @param WP_Post $post Aktuelle Anfrage.
 */
function textmaker_render_submission_box( WP_Post $post ): void {
	$rows = array(
		__( 'Vorname', 'textmaker' )      => get_post_meta( $post->ID, '_tm_first_name', true ),
		__( 'Nachname', 'textmaker' )     => get_post_meta( $post->ID, '_tm_last_name', true ),
		__( 'E-Mail', 'textmaker' )       => get_post_meta( $post->ID, '_tm_email', true ),
		__( 'Telefon', 'textmaker' )      => get_post_meta( $post->ID, '_tm_phone', true ),
		__( 'Wunschtermin', 'textmaker' ) => get_post_meta( $post->ID, '_tm_due_date', true ),
		__( 'IP-Adresse', 'textmaker' )   => get_post_meta( $post->ID, '_tm_ip', true ),
	);

	echo '<table class="widefat striped"><tbody>';

	foreach ( $rows as $label => $value ) {
		$value = (string) $value;

		if ( '' === $value ) {
			continue;
		}

		printf(
			'<tr><th scope="row" style="width:180px;">%1$s</th><td>%2$s</td></tr>',
			esc_html( (string) $label ),
			esc_html( $value )
		);
	}

	$message = (string) get_post_meta( $post->ID, '_tm_message', true );

	if ( '' !== $message ) {
		printf(
			'<tr><th scope="row">%1$s</th><td><div style="white-space:pre-wrap;">%2$s</div></td></tr>',
			esc_html__( 'Bemerkungen', 'textmaker' ),
			esc_html( $message )
		);
	}

	$attachments = get_post_meta( $post->ID, '_tm_attachments', true );

	if ( is_array( $attachments ) && array() !== $attachments ) {
		$links = array();

		foreach ( $attachments as $attachment_id ) {
			$url = wp_get_attachment_url( (int) $attachment_id );

			if ( false === $url ) {
				continue;
			}

			$links[] = sprintf(
				'<a href="%1$s" download>%2$s</a>',
				esc_url( $url ),
				esc_html( (string) get_the_title( (int) $attachment_id ) )
			);
		}

		if ( array() !== $links ) {
			printf(
				'<tr><th scope="row">%1$s</th><td>%2$s</td></tr>',
				esc_html__( 'Dokumente', 'textmaker' ),
				wp_kses_post( implode( '<br>', $links ) )
			);
		}
	}

	echo '</tbody></table>';
}

/**
 * Spalten der Anfragen-Übersicht.
 *
 * @param array<string, string> $columns Bestehende Spalten.
 * @return array<string, string>
 */
function textmaker_submission_columns( array $columns ): array {
	return array(
		'cb'       => $columns['cb'] ?? '',
		'title'    => __( 'Absender', 'textmaker' ),
		'tm_email' => __( 'E-Mail', 'textmaker' ),
		'tm_due'   => __( 'Wunschtermin', 'textmaker' ),
		'tm_files' => __( 'Dokumente', 'textmaker' ),
		'date'     => __( 'Eingegangen', 'textmaker' ),
	);
}
add_filter( 'manage_tm_submission_posts_columns', 'textmaker_submission_columns' );

/**
 * Inhalt der Spalten in der Anfragen-Übersicht.
 *
 * @param string $column  Spaltenschlüssel.
 * @param int    $post_id Beitrags-ID.
 */
function textmaker_submission_column( string $column, int $post_id ): void {
	switch ( $column ) {
		case 'tm_email':
			$email = (string) get_post_meta( $post_id, '_tm_email', true );

			if ( '' !== $email ) {
				printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $email ) );
			}
			break;

		case 'tm_due':
			echo esc_html( (string) get_post_meta( $post_id, '_tm_due_date', true ) );
			break;

		case 'tm_files':
			$attachments = get_post_meta( $post_id, '_tm_attachments', true );
			echo esc_html( (string) ( is_array( $attachments ) ? count( $attachments ) : 0 ) );
			break;
	}
}
add_action( 'manage_tm_submission_posts_custom_column', 'textmaker_submission_column', 10, 2 );

/**
 * Vorschaubild-Spalte für die bildbasierten Inhaltstypen.
 *
 * @param array<string, string> $columns Bestehende Spalten.
 * @return array<string, string>
 */
function textmaker_thumb_column( array $columns ): array {
	$out = array();

	foreach ( $columns as $key => $label ) {
		if ( 'title' === $key ) {
			$out['tm_thumb'] = __( 'Bild', 'textmaker' );
		}

		$out[ $key ] = $label;
	}

	return $out;
}

foreach ( array( 'tm_team', 'tm_step', 'tm_reference', 'tm_logo' ) as $textmaker_thumb_type ) {
	add_filter( 'manage_' . $textmaker_thumb_type . '_posts_columns', 'textmaker_thumb_column' );
	add_action(
		'manage_' . $textmaker_thumb_type . '_posts_custom_column',
		static function ( string $column, int $post_id ): void {
			if ( 'tm_thumb' !== $column ) {
				return;
			}

			$thumb = get_the_post_thumbnail( $post_id, array( 60, 60 ), array( 'style' => 'object-fit:cover;border-radius:3px;' ) );

			echo '' === $thumb ? '—' : wp_kses_post( $thumb );
		},
		10,
		2
	);
}
unset( $textmaker_thumb_type );

/**
 * Einträge eines Inhaltstyps in Menü-Reihenfolge holen.
 *
 * @param string $post_type Inhaltstyp.
 * @param int    $limit     Maximale Anzahl.
 * @return array<int, WP_Post>
 */
function textmaker_get_items( string $post_type, int $limit = 60 ): array {
	$query = new WP_Query(
		array(
			'post_type'              => $post_type,
			'post_status'            => 'publish',
			'posts_per_page'         => $limit,
			'orderby'                => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
			'no_found_rows'          => true,
			'update_post_term_cache' => false,
		)
	);

	return $query->posts;
}
