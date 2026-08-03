<?php
/**
 * Natives Kontaktformular mit Datei-Upload.
 *
 * Ersetzt das Elementor-Formular. Die Anfrage wird per E-Mail verschickt und
 * zusätzlich als Beitrag „Anfrage“ gespeichert, damit nichts verloren geht,
 * falls der Mailversand scheitert.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

const TEXTMAKER_MAX_UPLOAD = 25 * 1024 * 1024; // 25 MB je Datei.
const TEXTMAKER_MAX_FILES  = 5;

/**
 * Ergebnis der Verarbeitung, damit das Template den Zustand kennt.
 *
 * @return array{status: string, message: string, errors: array<string, string>, values: array<string, string>}
 */
function &textmaker_form_state(): array {
	static $state = array(
		'status'  => '',
		'message' => '',
		'errors'  => array(),
		'values'  => array(),
	);

	return $state;
}

/**
 * Eingegangenes Formular verarbeiten.
 *
 * Läuft auf `template_redirect`, also bevor Ausgabe erzeugt wird — so ist ein
 * Redirect nach erfolgreichem Versand möglich (Post/Redirect/Get).
 */
function textmaker_handle_contact_form(): void {
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}

	if ( ! isset( $_POST['textmaker_contact'] ) ) {
		return;
	}

	$state = &textmaker_form_state();

	$nonce = isset( $_POST['textmaker_nonce'] )
		? sanitize_text_field( wp_unslash( (string) $_POST['textmaker_nonce'] ) )
		: '';

	if ( '' === $nonce || ! wp_verify_nonce( $nonce, 'textmaker_contact' ) ) {
		$state['status']  = 'error';
		$state['message'] = __( 'Die Sitzung ist abgelaufen. Bitte lade die Seite neu und sende das Formular erneut.', 'textmaker' );

		return;
	}

	// Honeypot: von Menschen nie ausgefüllt.
	$trap = isset( $_POST['textmaker_website'] ) ? trim( (string) wp_unslash( $_POST['textmaker_website'] ) ) : '';

	if ( '' !== $trap ) {
		$state['status']  = 'success';
		$state['message'] = textmaker_option( 'contact_success' );

		return;
	}

	$values = array(
		'first_name' => isset( $_POST['tm_first_name'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['tm_first_name'] ) ) : '',
		'last_name'  => isset( $_POST['tm_last_name'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['tm_last_name'] ) ) : '',
		'email'      => isset( $_POST['tm_email'] ) ? sanitize_email( wp_unslash( (string) $_POST['tm_email'] ) ) : '',
		'phone'      => isset( $_POST['tm_phone'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['tm_phone'] ) ) : '',
		'due_date'   => isset( $_POST['tm_due_date'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['tm_due_date'] ) ) : '',
		'message'    => isset( $_POST['tm_message'] ) ? sanitize_textarea_field( wp_unslash( (string) $_POST['tm_message'] ) ) : '',
	);

	$state['values'] = $values;
	$errors          = array();

	if ( '' === $values['email'] || ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'Bitte gib eine gültige E-Mail-Adresse an, damit wir dir antworten können.', 'textmaker' );
	}

	if ( '' !== $values['due_date'] && 1 !== preg_match( '/^\d{4}-\d{2}-\d{2}$/', $values['due_date'] ) ) {
		$errors['due_date'] = __( 'Bitte wähle ein gültiges Datum.', 'textmaker' );
	}

	$attachments = array();

	if ( array() === $errors ) {
		$upload = textmaker_handle_uploads();

		if ( array() !== $upload['errors'] ) {
			$errors['uploaded_file'] = implode( ' ', $upload['errors'] );
		}

		$attachments = $upload['ids'];
	}

	if ( array() !== $errors ) {
		$state['status']  = 'error';
		$state['errors']  = $errors;
		$state['message'] = __( 'Bitte prüfe die markierten Felder.', 'textmaker' );

		return;
	}

	$submission_id = textmaker_store_submission( $values, $attachments );
	textmaker_send_notification( $values, $attachments, $submission_id );

	/**
	 * Nach dem erfolgreichen Absenden einer Anfrage.
	 *
	 * @param array<string, string> $values        Bereinigte Formularwerte.
	 * @param array<int, int>       $attachments   IDs der hochgeladenen Dateien.
	 * @param int                   $submission_id ID des gespeicherten Eintrags.
	 */
	do_action( 'textmaker_contact_submitted', $values, $attachments, $submission_id );

	$redirect = add_query_arg( 'anfrage', 'gesendet', wp_get_referer() ?: home_url( '/' ) );

	wp_safe_redirect( $redirect . '#anfragen' );
	exit;
}
add_action( 'template_redirect', 'textmaker_handle_contact_form' );

/**
 * Bestätigung nach dem Redirect anzeigen.
 */
function textmaker_pick_up_success(): void {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reine Anzeige, kein Zustandswechsel.
	$flag = isset( $_GET['anfrage'] ) ? sanitize_key( wp_unslash( (string) $_GET['anfrage'] ) ) : '';

	if ( 'gesendet' !== $flag ) {
		return;
	}

	$state            = &textmaker_form_state();
	$state['status']  = 'success';
	$state['message'] = textmaker_option( 'contact_success' );
}
add_action( 'template_redirect', 'textmaker_pick_up_success', 20 );

/**
 * Hochgeladene Dokumente prüfen und in die Mediathek legen.
 *
 * @return array{ids: array<int, int>, errors: array<int, string>}
 */
function textmaker_handle_uploads(): array {
	$result = array(
		'ids'    => array(),
		'errors' => array(),
	);

	if ( ! isset( $_FILES['tm_files'] ) || ! is_array( $_FILES['tm_files']['name'] ?? null ) ) {
		return $result;
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$files   = $_FILES['tm_files']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
	$allowed = textmaker_allowed_upload_types();
	$count   = min( count( $files['name'] ), TEXTMAKER_MAX_FILES );

	for ( $i = 0; $i < $count; $i++ ) {
		$name = sanitize_file_name( (string) $files['name'][ $i ] );

		if ( '' === $name || UPLOAD_ERR_NO_FILE === (int) $files['error'][ $i ] ) {
			continue;
		}

		if ( UPLOAD_ERR_OK !== (int) $files['error'][ $i ] ) {
			/* translators: %s: Dateiname. */
			$result['errors'][] = sprintf( __( '„%s“ konnte nicht hochgeladen werden.', 'textmaker' ), $name );
			continue;
		}

		if ( (int) $files['size'][ $i ] > TEXTMAKER_MAX_UPLOAD ) {
			/* translators: 1: Dateiname, 2: maximale Grösse. */
			$result['errors'][] = sprintf( __( '„%1$s“ ist grösser als %2$s.', 'textmaker' ), $name, size_format( TEXTMAKER_MAX_UPLOAD ) );
			continue;
		}

		$check = wp_check_filetype_and_ext( (string) $files['tmp_name'][ $i ], $name, $allowed );

		if ( empty( $check['ext'] ) || ! in_array( $check['ext'], array_keys( $allowed ), true ) ) {
			/* translators: %s: Dateiname. */
			$result['errors'][] = sprintf( __( '„%s“ hat ein nicht zugelassenes Format.', 'textmaker' ), $name );
			continue;
		}

		$single = array(
			'name'     => $name,
			'type'     => (string) $check['type'],
			'tmp_name' => (string) $files['tmp_name'][ $i ],
			'error'    => UPLOAD_ERR_OK,
			'size'     => (int) $files['size'][ $i ],
		);

		$attachment_id = textmaker_sideload_upload( $single, $allowed );

		if ( is_wp_error( $attachment_id ) ) {
			/* translators: %s: Dateiname. */
			$result['errors'][] = sprintf( __( '„%s“ konnte nicht gespeichert werden.', 'textmaker' ), $name );
			continue;
		}

		$result['ids'][] = $attachment_id;
	}

	return $result;
}

/**
 * Eine geprüfte Datei in die Mediathek übernehmen.
 *
 * @param array{name: string, type: string, tmp_name: string, error: int, size: int} $file    Datei-Angaben.
 * @param array<string, string>                                                      $allowed Erlaubte Typen.
 * @return int|WP_Error Anhang-ID oder Fehler.
 */
function textmaker_sideload_upload( array $file, array $allowed ): int|WP_Error {
	$overrides = array(
		'test_form' => false,
		'mimes'     => $allowed,
	);

	$moved = wp_handle_upload( $file, $overrides );

	if ( ! is_array( $moved ) || isset( $moved['error'] ) ) {
		return new WP_Error( 'textmaker_upload_failed', (string) ( $moved['error'] ?? __( 'Upload fehlgeschlagen.', 'textmaker' ) ) );
	}

	$attachment_id = wp_insert_attachment(
		array(
			'post_mime_type' => (string) $moved['type'],
			'post_title'     => sanitize_text_field( pathinfo( $file['name'], PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'private',
		),
		(string) $moved['file']
	);

	if ( is_wp_error( $attachment_id ) || 0 === $attachment_id ) {
		return new WP_Error( 'textmaker_attachment_failed', __( 'Datei konnte nicht registriert werden.', 'textmaker' ) );
	}

	wp_update_attachment_metadata(
		$attachment_id,
		wp_generate_attachment_metadata( $attachment_id, (string) $moved['file'] )
	);

	return (int) $attachment_id;
}

/**
 * Anfrage im Backend speichern.
 *
 * @param array<string, string> $values      Formularwerte.
 * @param array<int, int>       $attachments Anhang-IDs.
 */
function textmaker_store_submission( array $values, array $attachments ): int {
	$name = trim( $values['first_name'] . ' ' . $values['last_name'] );

	if ( '' === $name ) {
		$name = $values['email'];
	}

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'tm_submission',
			'post_status' => 'publish',
			'post_title'  => $name,
		),
		true
	);

	if ( is_wp_error( $post_id ) ) {
		return 0;
	}

	$post_id = (int) $post_id;

	update_post_meta( $post_id, '_tm_first_name', $values['first_name'] );
	update_post_meta( $post_id, '_tm_last_name', $values['last_name'] );
	update_post_meta( $post_id, '_tm_email', $values['email'] );
	update_post_meta( $post_id, '_tm_phone', $values['phone'] );
	update_post_meta( $post_id, '_tm_due_date', $values['due_date'] );
	update_post_meta( $post_id, '_tm_message', $values['message'] );
	update_post_meta( $post_id, '_tm_ip', textmaker_client_ip() );

	if ( array() !== $attachments ) {
		update_post_meta( $post_id, '_tm_attachments', array_map( 'absint', $attachments ) );

		foreach ( $attachments as $attachment_id ) {
			wp_update_post(
				array(
					'ID'          => (int) $attachment_id,
					'post_parent' => $post_id,
				)
			);
		}
	}

	return $post_id;
}

/**
 * Benachrichtigung an das Team senden.
 *
 * @param array<string, string> $values        Formularwerte.
 * @param array<int, int>       $attachments   Anhang-IDs.
 * @param int                   $submission_id ID des gespeicherten Eintrags.
 */
function textmaker_send_notification( array $values, array $attachments, int $submission_id ): bool {
	$recipient = textmaker_notification_recipient();
	$name      = trim( $values['first_name'] . ' ' . $values['last_name'] );

	/* translators: %s: Name der anfragenden Person. */
	$subject = sprintf( __( 'Neue Offertanfrage von %s', 'textmaker' ), '' !== $name ? $name : $values['email'] );

	$lines = array(
		__( 'Neue Anfrage über das Kontaktformular:', 'textmaker' ),
		'',
		__( 'Vorname:', 'textmaker' ) . ' ' . $values['first_name'],
		__( 'Nachname:', 'textmaker' ) . ' ' . $values['last_name'],
		__( 'E-Mail:', 'textmaker' ) . ' ' . $values['email'],
		__( 'Telefon:', 'textmaker' ) . ' ' . $values['phone'],
		__( 'Wunschtermin:', 'textmaker' ) . ' ' . $values['due_date'],
		'',
		__( 'Bemerkungen:', 'textmaker' ),
		'' !== $values['message'] ? $values['message'] : __( '(keine)', 'textmaker' ),
	);

	if ( $submission_id > 0 ) {
		$lines[] = '';
		$lines[] = __( 'Im Backend ansehen:', 'textmaker' ) . ' ' . get_edit_post_link( $submission_id, '' );
	}

	$paths = array();

	foreach ( $attachments as $attachment_id ) {
		$path = get_attached_file( (int) $attachment_id );

		if ( is_string( $path ) && file_exists( $path ) ) {
			$paths[] = $path;
		}
	}

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );

	if ( is_email( $values['email'] ) ) {
		$headers[] = 'Reply-To: ' . ( '' !== $name ? $name . ' <' . $values['email'] . '>' : $values['email'] );
	}

	$sent = wp_mail( $recipient, $subject, implode( "\n", $lines ), $headers, $paths );

	// Fehlgeschlagene Zustellung festhalten: die Anfrage ist gespeichert, aber
	// niemand wurde benachrichtigt — das muss im Backend sichtbar sein.
	if ( $submission_id > 0 ) {
		if ( $sent ) {
			delete_post_meta( $submission_id, '_tm_mail_error' );
		} else {
			update_post_meta( $submission_id, '_tm_mail_error', textmaker_last_mail_error() );
			update_option( 'textmaker_mail_failed_at', time(), false );
		}
	}

	return $sent;
}

/**
 * Empfängeradresse für Benachrichtigungen.
 *
 * Reihenfolge: eigens hinterlegte Adresse, sonst die Kontaktadresse aus der
 * Fusszeile, sonst die Administrator-Adresse der Website.
 *
 * @return array<int, string>
 */
function textmaker_notification_recipient(): array {
	$candidates = array(
		textmaker_option( 'contact_recipient' ),
		textmaker_option( 'footer_email' ),
		(string) get_option( 'admin_email' ),
	);

	foreach ( $candidates as $candidate ) {
		$addresses = array_filter(
			array_map( 'trim', explode( ',', $candidate ) ),
			static fn( string $address ): bool => is_email( $address ) !== false
		);

		if ( array() !== $addresses ) {
			return array_values( $addresses );
		}
	}

	return array();
}

/**
 * Letzte Fehlermeldung des Mailversands.
 */
function textmaker_last_mail_error(): string {
	static $error = '';

	if ( '' === $error ) {
		$error = __( 'Der Mailversand wurde vom Server abgelehnt.', 'textmaker' );
	}

	return $error;
}

/**
 * Fehlermeldung von WordPress abgreifen.
 *
 * @param WP_Error $error Fehler aus wp_mail().
 */
function textmaker_capture_mail_error( WP_Error $error ): void {
	update_option( 'textmaker_last_mail_error', $error->get_error_message(), false );
}
add_action( 'wp_mail_failed', 'textmaker_capture_mail_error' );

/**
 * Hinweis im Backend, wenn eine Benachrichtigung nicht zugestellt werden konnte.
 */
function textmaker_mail_failure_notice(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$failed_at = (int) get_option( 'textmaker_mail_failed_at', 0 );

	if ( 0 === $failed_at ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p><strong>%1$s</strong> %2$s</p><p>%3$s</p><p>
			<a class="button" href="%4$s">%5$s</a>
			<a class="button" href="%6$s">%7$s</a>
		</p></div>',
		esc_html__( 'teXtmaker:', 'textmaker' ),
		esc_html__( 'Eine Offertanfrage konnte nicht per E-Mail zugestellt werden.', 'textmaker' ),
		esc_html__( 'Die Anfrage selbst ist gespeichert und geht nicht verloren. Für zuverlässigen Versand empfiehlt sich ein SMTP-Plugin — der PHP-Standardversand vieler Hoster landet im Spam oder wird ganz verworfen.', 'textmaker' ),
		esc_url( admin_url( 'edit.php?post_type=tm_submission' ) ),
		esc_html__( 'Anfragen ansehen', 'textmaker' ),
		esc_url( wp_nonce_url( admin_url( 'admin.php?page=' . TEXTMAKER_MENU_SLUG . '&textmaker_dismiss_mail=1' ), 'textmaker_dismiss_mail' ) ),
		esc_html__( 'Hinweis ausblenden', 'textmaker' )
	);
}
add_action( 'admin_notices', 'textmaker_mail_failure_notice' );

/**
 * Testmail an die eingestellte Empfängeradresse senden.
 *
 * Damit lässt sich vor dem Livegang prüfen, ob der Server überhaupt zustellt —
 * ohne dafür eine echte Anfrage abzuschicken.
 */
function textmaker_send_test_mail(): void {
	if ( ! isset( $_POST['textmaker_test_mail'] ) ) {
		return;
	}

	check_admin_referer( 'textmaker_test_mail' );

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$recipient = textmaker_notification_recipient();

	if ( array() === $recipient ) {
		set_transient( 'textmaker_test_mail_result', array( 'error', __( 'Es ist keine gültige Empfängeradresse hinterlegt.', 'textmaker' ) ), 60 );

		return;
	}

	delete_option( 'textmaker_last_mail_error' );

	$sent = wp_mail(
		$recipient,
		__( 'Testmail von teXtmaker', 'textmaker' ),
		__( 'Wenn diese Nachricht ankommt, funktioniert der Versand des Kontaktformulars.', 'textmaker' ),
		array( 'Content-Type: text/plain; charset=UTF-8' )
	);

	$result = $sent
		? array(
			'success',
			sprintf(
				/* translators: %s: Empfängeradressen. */
				__( 'Testmail an %s übergeben. Prüfe das Postfach — auch den Spam-Ordner.', 'textmaker' ),
				implode( ', ', $recipient )
			),
		)
		: array(
			'error',
			sprintf(
				/* translators: %s: Fehlermeldung des Servers. */
				__( 'Der Versand schlug fehl: %s', 'textmaker' ),
				(string) get_option( 'textmaker_last_mail_error', __( 'keine nähere Angabe', 'textmaker' ) )
			),
		);

	set_transient( 'textmaker_test_mail_result', $result, 60 );
}
add_action( 'admin_init', 'textmaker_send_test_mail' );

/**
 * Hinweis zum Mailversand ausblenden.
 */
function textmaker_dismiss_mail_notice(): void {
	if ( ! isset( $_GET['textmaker_dismiss_mail'] ) ) {
		return;
	}

	check_admin_referer( 'textmaker_dismiss_mail' );

	if ( current_user_can( 'manage_options' ) ) {
		delete_option( 'textmaker_mail_failed_at' );
	}
}
add_action( 'admin_init', 'textmaker_dismiss_mail_notice' );

/**
 * IP-Adresse der anfragenden Person, soweit verfügbar.
 */
function textmaker_client_ip(): string {
	$raw = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( (string) $_SERVER['REMOTE_ADDR'] ) ) : '';
	$ip  = filter_var( $raw, FILTER_VALIDATE_IP );

	return is_string( $ip ) ? $ip : '';
}

/**
 * Fehlermeldung zu einem Feld, falls vorhanden.
 *
 * @param string $field Feldname.
 */
function textmaker_field_error( string $field ): string {
	$state = textmaker_form_state();

	return (string) ( $state['errors'][ $field ] ?? '' );
}

/**
 * Zuvor eingegebener Wert eines Feldes, damit nach einem Fehler nichts verloren geht.
 *
 * @param string $field Feldname.
 */
function textmaker_field_value( string $field ): string {
	$state = textmaker_form_state();

	return (string) ( $state['values'][ $field ] ?? '' );
}
