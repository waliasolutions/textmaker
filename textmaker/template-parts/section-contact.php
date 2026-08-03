<?php
/**
 * Abschnitt: Offerte anfragen — Team und natives Kontaktformular.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

$textmaker_team     = textmaker_get_items( 'tm_team', 20 );
$textmaker_state    = textmaker_form_state();
$textmaker_whatsapp = textmaker_option( 'contact_whatsapp' );
$textmaker_intro    = textmaker_option( 'contact_intro' );
$textmaker_note     = textmaker_option( 'contact_note' );
?>

<section class="band" id="anfragen">
	<div class="frame">
		<div class="section-head" data-reveal>
			<h2><?php echo esc_html( textmaker_option( 'contact_heading' ) ); ?></h2>
			<?php if ( '' !== $textmaker_intro ) : ?>
				<p><?php echo esc_html( $textmaker_intro ); ?></p>
			<?php endif; ?>
		</div>

		<div class="request">
			<div data-reveal>
				<?php if ( array() !== $textmaker_team ) : ?>
					<div class="team">
						<?php
						foreach ( $textmaker_team as $textmaker_member ) :
							$textmaker_name = get_the_title( $textmaker_member );
							$textmaker_role = (string) get_post_meta( $textmaker_member->ID, '_tm_role', true );
							?>
							<div class="member">
								<?php if ( has_post_thumbnail( $textmaker_member ) ) : ?>
									<?php
									echo get_the_post_thumbnail(
										$textmaker_member,
										'textmaker-portrait',
										array(
											'class'   => 'avatar',
											'alt'     => $textmaker_name,
											'loading' => 'lazy',
										)
									);
									?>
								<?php else : ?>
									<span class="avatar" aria-hidden="true"><?php echo esc_html( textmaker_initials( $textmaker_name ) ); ?></span>
								<?php endif; ?>

								<span>
									<b><?php echo esc_html( $textmaker_name ); ?></b>
									<?php if ( '' !== $textmaker_role ) : ?>
										<span class="role"><?php echo esc_html( $textmaker_role ); ?></span>
									<?php endif; ?>
								</span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( '' !== $textmaker_whatsapp ) : ?>
					<a class="whatsapp" href="<?php echo esc_url( $textmaker_whatsapp ); ?>">
						<svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.4-.7-1.7-.8-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3 0-.5l-.7-1.7c-.2-.4-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3 2.9 2.9 0 0 0-.9 2.2 5 5 0 0 0 1 2.6 11.4 11.4 0 0 0 4.4 3.9c1.7.7 2.1.6 2.5.5a2.5 2.5 0 0 0 1.6-1.1 2 2 0 0 0 .1-1.1c0-.1-.2-.2-.4-.3Z"/></svg>
						<?php echo esc_html( textmaker_option( 'contact_wa_label' ) ); ?>
					</a>
				<?php endif; ?>
			</div>

			<div class="form-shell" data-reveal>
				<?php
				$textmaker_action = is_front_page() && ! is_singular()
					? home_url( '/' )
					: (string) ( get_permalink() ?: home_url( '/' ) );
				?>
				<form class="form-grid" id="anfrage-formular" method="post" enctype="multipart/form-data"
					action="<?php echo esc_url( $textmaker_action ); ?>#anfragen">

					<?php wp_nonce_field( 'textmaker_contact', 'textmaker_nonce' ); ?>
					<input type="hidden" name="textmaker_contact" value="1">

					<?php if ( '' !== $textmaker_state['message'] ) : ?>
						<p class="form-msg form-msg--<?php echo esc_attr( $textmaker_state['status'] ); ?>" role="status">
							<?php echo esc_html( $textmaker_state['message'] ); ?>
						</p>
					<?php endif; ?>

					<div class="field">
						<label for="f-vorname"><?php esc_html_e( 'Vorname', 'textmaker' ); ?></label>
						<input type="text" id="f-vorname" name="tm_first_name" autocomplete="given-name"
							value="<?php echo esc_attr( textmaker_field_value( 'first_name' ) ); ?>">
					</div>

					<div class="field">
						<label for="f-nachname"><?php esc_html_e( 'Nachname', 'textmaker' ); ?></label>
						<input type="text" id="f-nachname" name="tm_last_name" autocomplete="family-name"
							value="<?php echo esc_attr( textmaker_field_value( 'last_name' ) ); ?>">
					</div>

					<div class="field">
						<label for="f-email">
							<?php esc_html_e( 'E-Mail', 'textmaker' ); ?>
							<span class="req" aria-hidden="true">*</span>
						</label>
						<input type="email" id="f-email" name="tm_email" required autocomplete="email"
							<?php echo '' !== textmaker_field_error( 'email' ) ? 'aria-invalid="true" aria-describedby="f-email-fehler"' : ''; ?>
							value="<?php echo esc_attr( textmaker_field_value( 'email' ) ); ?>">
						<?php if ( '' !== textmaker_field_error( 'email' ) ) : ?>
							<span class="field-error" id="f-email-fehler"><?php echo esc_html( textmaker_field_error( 'email' ) ); ?></span>
						<?php endif; ?>
					</div>

					<div class="field">
						<label for="f-telefon"><?php esc_html_e( 'Telefon', 'textmaker' ); ?></label>
						<input type="tel" id="f-telefon" name="tm_phone" autocomplete="tel"
							value="<?php echo esc_attr( textmaker_field_value( 'phone' ) ); ?>">
					</div>

					<div class="field field--wide">
						<label for="f-datum"><?php esc_html_e( 'Wann möchtest du die Korrektur zurück erhalten?', 'textmaker' ); ?></label>
						<input type="date" id="f-datum" name="tm_due_date"
							value="<?php echo esc_attr( textmaker_field_value( 'due_date' ) ); ?>">
						<?php if ( '' !== textmaker_field_error( 'due_date' ) ) : ?>
							<span class="field-error"><?php echo esc_html( textmaker_field_error( 'due_date' ) ); ?></span>
						<?php endif; ?>
					</div>

					<div class="field field--wide">
						<label for="f-bemerkungen"><?php esc_html_e( 'Bemerkungen', 'textmaker' ); ?></label>
						<textarea id="f-bemerkungen" name="tm_message" rows="4"><?php echo esc_textarea( textmaker_field_value( 'message' ) ); ?></textarea>
					</div>

					<div class="field field--wide">
						<label for="f-datei"><?php esc_html_e( 'Dokument anhängen', 'textmaker' ); ?></label>
						<label class="file-drop" for="f-datei">
							<input type="file" id="f-datei" name="tm_files[]" multiple
								accept=".doc,.docx,.odt,.pdf,.rtf,.txt">
							<span data-file-label>
								<?php
								printf(
									/* translators: %s: maximale Dateigrösse. */
									esc_html__( 'Datei wählen — DOC, DOCX, PDF, ODT, RTF, TXT (max. %s)', 'textmaker' ),
									esc_html( size_format( TEXTMAKER_MAX_UPLOAD ) )
								);
								?>
							</span>
						</label>
						<?php if ( '' !== textmaker_field_error( 'uploaded_file' ) ) : ?>
							<span class="field-error"><?php echo esc_html( textmaker_field_error( 'uploaded_file' ) ); ?></span>
						<?php endif; ?>
					</div>

					<p class="honeypot" aria-hidden="true">
						<label for="f-website"><?php esc_html_e( 'Dieses Feld bitte leer lassen', 'textmaker' ); ?></label>
						<input type="text" id="f-website" name="textmaker_website" tabindex="-1" autocomplete="off">
					</p>

					<div class="form-foot">
						<button class="btn" type="submit"><?php esc_html_e( 'Offerte anfragen', 'textmaker' ); ?></button>
						<?php if ( '' !== $textmaker_note ) : ?>
							<p class="form-note"><?php echo esc_html( $textmaker_note ); ?></p>
						<?php endif; ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
