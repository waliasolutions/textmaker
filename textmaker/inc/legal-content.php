<?php
/**
 * Vorlagen für Datenschutzerklärung und Impressum.
 *
 * Stand: revidiertes Schweizer Datenschutzgesetz (revDSG) samt Verordnung
 * (DSV), gültig seit dem 1. September 2023 — der für 2026 massgebliche Stand.
 * Berücksichtigt sind ausserdem die EU-DSGVO für Anfragen aus dem EWR und das
 * Swiss-U.S. Data Privacy Framework für Datenflüsse in die USA.
 *
 * WICHTIG: Diese Texte sind eine fachlich sorgfältig erstellte Grundlage,
 * aber keine Rechtsberatung. Vor der Veröffentlichung sind die mit
 * [eckigen Klammern] markierten Stellen zu füllen und der Text durch eine
 * rechtskundige Person zu prüfen.
 *
 * @package teXtmaker
 */

defined( 'ABSPATH' ) || exit;

/**
 * Platzhalter aus den Theme-Optionen füllen.
 *
 * @return array<string, string>
 */
function textmaker_legal_placeholders(): array {
	$address = textmaker_option_lines( 'footer_address' );

	return array(
		'{{firma}}'    => textmaker_option( 'footer_company' ),
		'{{strasse}}'  => $address[0] ?? '',
		'{{ort}}'      => $address[1] ?? '',
		'{{email}}'    => textmaker_option( 'footer_email' ),
		'{{telefon}}'  => textmaker_option( 'footer_phone' ),
		'{{domain}}'   => wp_parse_url( home_url(), PHP_URL_HOST ) ?? '',
		'{{website}}'  => home_url( '/' ),
	);
}

/**
 * Vorlagen der rechtlichen Seiten.
 *
 * @return array<string, array{title: string, content: string}>
 */
function textmaker_legal_templates(): array {
	$templates = array(
		'datenschutz' => array(
			'title'   => __( 'Datenschutz', 'textmaker' ),
			'content' => textmaker_privacy_template(),
		),
		'impressum'   => array(
			'title'   => __( 'Impressum', 'textmaker' ),
			'content' => textmaker_imprint_template(),
		),
	);

	$placeholders = textmaker_legal_placeholders();

	foreach ( $templates as $slug => $template ) {
		$templates[ $slug ]['content'] = strtr( $template['content'], $placeholders );
	}

	return $templates;
}

/**
 * Datenschutzerklärung nach revDSG.
 */
function textmaker_privacy_template(): string {
	return <<<'HTML'
<h2>Datenschutzerklärung</h2>

<p>Diese Erklärung beschreibt, welche Personendaten wir bearbeiten, zu welchem Zweck und welche Rechte Ihnen zustehen. Massgebend ist das revidierte Schweizer Datenschutzgesetz (revDSG) samt Datenschutzverordnung (DSV). Soweit die EU-Datenschutz-Grundverordnung (DSGVO) auf eine Bearbeitung anwendbar ist — etwa bei Anfragen aus dem Europäischen Wirtschaftsraum —, halten wir auch deren Vorgaben ein.</p>

<p>Wir bearbeiten nur so viele Daten, wie für den jeweiligen Zweck nötig sind. Ihre Texte und Dokumente behandeln wir vertraulich; sie sind unser Arbeitsgegenstand und werden weder veröffentlicht noch für andere Zwecke verwendet.</p>

<h3>1. Verantwortliche Stelle</h3>

<p>Verantwortlich für die Bearbeitung Ihrer Personendaten im Sinne von Art. 5 lit. j revDSG ist:</p>

<p><strong>{{firma}}</strong><br>
{{strasse}}<br>
{{ort}}<br>
Schweiz</p>

<p>E-Mail: <a href="mailto:{{email}}">{{email}}</a><br>
Telefon: {{telefon}}</p>

<p>Wir haben keine Datenschutzberaterin und keinen Datenschutzberater im Sinne von Art. 10 revDSG bezeichnet; dazu sind wir nicht verpflichtet. Für alle Fragen zum Datenschutz erreichen Sie uns unter der oben genannten Adresse.</p>

<p>Wir haben keine Vertretung in der EU im Sinne von Art. 27 DSGVO bezeichnet, da wir uns nicht gezielt an Personen im EWR richten.</p>

<h3>2. Welche Daten wir bearbeiten</h3>

<h4>2.1 Anfragen über das Kontaktformular</h4>

<p>Wenn Sie uns eine Offertanfrage senden, bearbeiten wir: Vorname, Nachname, E-Mail-Adresse, Telefonnummer, Wunschtermin für die Rücklieferung, Ihre Bemerkungen sowie allfällig hochgeladene Dokumente. Zusätzlich speichern wir den Zeitpunkt des Eingangs und Ihre IP-Adresse.</p>

<p>Zweck: Bearbeitung Ihrer Anfrage, Erstellung einer Offerte, Durchführung des Auftrags und Beantwortung von Rückfragen. Die IP-Adresse dient ausschliesslich der Abwehr von Missbrauch und Spam.</p>

<p>Die Angabe der E-Mail-Adresse ist erforderlich, weil wir Ihnen sonst nicht antworten können. Alle übrigen Angaben sind freiwillig.</p>

<p>Ihre Anfrage wird auf unserem Webserver gespeichert und zusätzlich per E-Mail an unser Team zugestellt. Hochgeladene Dokumente liegen geschützt und sind nicht öffentlich abrufbar.</p>

<h4>2.2 Texte und Dokumente im Auftragsverhältnis</h4>

<p>Zur Erbringung unserer Leistungen bearbeiten wir die von Ihnen gelieferten Texte. Diese können ihrerseits Personendaten enthalten — etwa in Abschlussarbeiten, Berichten oder Interviews. Wir bearbeiten solche Daten ausschliesslich zur Erfüllung des Auftrags und geben sie nicht weiter. Innerhalb unseres Teams erhalten nur die am Auftrag beteiligten Personen Zugriff; alle sind zur Vertraulichkeit verpflichtet.</p>

<p>Handelt es sich um eine Bearbeitung in unserem Auftrag im Sinne von Art. 9 revDSG, schliessen wir auf Wunsch einen entsprechenden Vertrag zur Auftragsbearbeitung ab.</p>

<h4>2.3 Vertrags- und Rechnungsdaten</h4>

<p>Für die Abwicklung eines Auftrags bearbeiten wir Ihre Bestandes- und Rechnungsdaten: Name, Adresse, Kontaktangaben, Angaben zum Auftrag sowie Zahlungsinformationen. Diese Bearbeitung ist zur Erfüllung des Vertrags erforderlich und unterliegt der gesetzlichen Aufbewahrungspflicht.</p>

<h4>2.4 Server-Protokolle</h4>

<p>Beim Aufruf unserer Website erhebt der Server automatisch technische Angaben, die Ihr Browser übermittelt: aufgerufene Adresse, Zeitpunkt, Browsertyp und -version, Betriebssystem, verweisende Seite sowie die IP-Adresse. Diese Protokolle dienen dem sicheren Betrieb, der Fehlersuche und der Abwehr von Angriffen. Eine Zusammenführung mit anderen Datenquellen findet nicht statt.</p>

<h4>2.5 Kontaktaufnahme per E-Mail, Telefon oder WhatsApp</h4>

<p>Nehmen Sie auf einem dieser Wege Kontakt auf, bearbeiten wir die dabei anfallenden Angaben zur Beantwortung Ihres Anliegens. Beim Weg über WhatsApp gelten zusätzlich die Bedingungen und die Datenschutzerklärung von WhatsApp Ireland Limited; auf deren Bearbeitung haben wir keinen Einfluss. Wenn Sie das vermeiden möchten, verwenden Sie bitte E-Mail oder Telefon.</p>

<h3>3. Cookies und ähnliche Technologien</h3>

<p>Für den Betrieb der Website setzen wir technisch notwendige Cookies ein, etwa zur Absicherung von Formularen. Sie sind für die Funktion erforderlich und werden nach dem Besuch oder nach kurzer Zeit gelöscht.</p>

<p>Werden darüber hinaus Cookies zu Statistik- oder Marketingzwecken gesetzt, holen wir Ihre Einwilligung ein, bevor sie zum Einsatz kommen. Eine erteilte Einwilligung können Sie jederzeit mit Wirkung für die Zukunft widerrufen. Sie können Cookies zudem in Ihrem Browser blockieren oder löschen; einzelne Funktionen der Website stehen dann möglicherweise nicht mehr vollständig zur Verfügung.</p>

<p>Der Einsatz von Cookies richtet sich nach Art. 45c lit. b des Fernmeldegesetzes (FMG).</p>

<h3>4. Dienste von Dritten</h3>

<p>Wo wir Dienste Dritter einsetzen, können diese Daten über Ihren Besuch erhalten. Wir wählen unsere Dienstleister sorgfältig aus und verpflichten sie vertraglich auf den Datenschutz.</p>

<h4>4.1 Hosting</h4>

<p>Unsere Website und die darüber eingehenden Daten werden bei der Metanet AG, Zürich, gehostet. Die Server stehen in der Schweiz und unterstehen damit ausschliesslich Schweizer Recht. Mit dem Anbieter besteht ein Vertrag zur Auftragsbearbeitung im Sinne von Art. 9 revDSG.</p>

<p>Das bedeutet: Ihre Anfragen und die uns übermittelten Dokumente verlassen die Schweiz nicht. Eine Bekanntgabe ins Ausland findet nur bei den unter Ziffer 6 genannten Diensten statt und betrifft nicht Ihre Texte.</p>

<h4>4.2 Google Tag Manager</h4>

<p>Wir setzen den Google Tag Manager der Google Ireland Limited, Gordon House, Barrow Street, Dublin 4, Irland, ein. Der Tag Manager selbst bearbeitet keine Personendaten zu eigenen Zwecken; er dient der Verwaltung weiterer Dienste. Beim Laden wird jedoch Ihre IP-Adresse an Google übermittelt. Welche Dienste über den Tag Manager eingebunden sind, entnehmen Sie den folgenden Abschnitten und dem Einwilligungsdialog.</p>

<h4>4.3 Google-Rezensionen</h4>

<p>Auf der Startseite zeigen wir Kundenbewertungen an, die über einen Dienst der Elfsight LLC eingebunden und von Google Maps bezogen werden. Beim Laden dieses Elements wird eine Verbindung zu den Servern des Anbieters aufgebaut; dabei können Ihre IP-Adresse und technische Angaben zu Ihrem Gerät übermittelt werden. Zweck ist die Darstellung öffentlich zugänglicher Bewertungen über unsere Dienstleistung.</p>

<h4>4.4 Schriften und weitere Ressourcen</h4>

<p>Schriften und Skripte, die zur Darstellung dieser Website nötig sind, werden von unserem eigenen Server ausgeliefert. Eine Verbindung zu Schriftanbietern Dritter wird beim blossen Aufruf der Seite nicht aufgebaut.</p>

<h3>5. Bekanntgabe von Personendaten</h3>

<p>Wir geben Personendaten nur weiter, soweit dies für die Erfüllung des Auftrags erforderlich ist, Sie eingewilligt haben oder wir gesetzlich dazu verpflichtet sind. Empfänger können sein:</p>

<ul>
<li>Dienstleister, die für uns tätig werden (Hosting, IT-Wartung, Buchhaltung, Zahlungsabwicklung) — als Auftragsbearbeiter und vertraglich gebunden;</li>
<li>Partnerinnen und Partner für Übersetzungen und fremdsprachige Lektorate, soweit Ihr Auftrag dies erfordert — ebenfalls zur Vertraulichkeit verpflichtet;</li>
<li>Behörden und Gerichte, soweit wir gesetzlich zur Herausgabe verpflichtet sind.</li>
</ul>

<p>Wir verkaufen keine Personendaten und geben sie nicht zu Werbezwecken an Dritte weiter.</p>

<h3>6. Bekanntgabe ins Ausland</h3>

<p>Ihre Anfragen, Ihre Texte und Ihre Vertragsdaten werden ausschliesslich in der Schweiz bearbeitet und gespeichert.</p>

<p>Eine Bekanntgabe ins Ausland ergibt sich nur aus den unter Ziffer 4 genannten eingebundenen Diensten — namentlich Google (Irland) und Elfsight, wobei dabei Daten auch in die USA gelangen können. Sie erfolgt nur, wenn der betreffende Staat über einen angemessenen Datenschutz verfügt (Anhang 1 DSV) oder wenn geeignete Garantien im Sinne von Art. 16 Abs. 2 revDSG bestehen — insbesondere die vom Eidgenössischen Datenschutz- und Öffentlichkeitsbeauftragten (EDÖB) anerkannten Standardvertragsklauseln oder, für Anbieter in den USA, eine Zertifizierung nach dem Swiss-U.S. Data Privacy Framework.</p>

<p>Wir weisen darauf hin, dass in einzelnen Staaten Behörden auf Daten zugreifen können, ohne dass Sie davon Kenntnis erhalten oder dagegen wirksam vorgehen können.</p>

<h3>7. Aufbewahrungsdauer</h3>

<p>Wir bewahren Personendaten so lange auf, wie es für den jeweiligen Zweck nötig ist:</p>

<ul>
<li><strong>Anfragen ohne Auftrag:</strong> in der Regel zwölf Monate nach dem letzten Kontakt, danach werden sie gelöscht.</li>
<li><strong>Auftragsunterlagen und gelieferte Texte:</strong> bis zum Abschluss des Auftrags und einer angemessenen Nachfrist für Rückfragen, längstens 24 Monate. Auf Wunsch löschen wir Ihre Dokumente unmittelbar nach Abschluss.</li>
<li><strong>Rechnungs- und Geschäftsunterlagen:</strong> zehn Jahre, entsprechend der Aufbewahrungspflicht nach Art. 958f OR.</li>
<li><strong>Server-Protokolle:</strong> in der Regel bis zu sechs Monate.</li>
</ul>

<p>Danach werden die Daten gelöscht oder anonymisiert, soweit keine gesetzliche Pflicht entgegensteht.</p>

<h3>8. Datensicherheit</h3>

<p>Wir treffen angemessene technische und organisatorische Massnahmen nach Art. 8 revDSG, um Ihre Daten vor unbefugtem Zugriff, Verlust und Missbrauch zu schützen. Dazu gehören die verschlüsselte Übertragung dieser Website mittels TLS, Zugriffsbeschränkungen innerhalb des Teams, aktuelle Software sowie regelmässige Sicherungen.</p>

<p>Wir weisen darauf hin, dass die Übermittlung von Daten über das Internet — insbesondere per unverschlüsselter E-Mail — Sicherheitslücken aufweisen kann. Ein lückenloser Schutz vor dem Zugriff Dritter ist nicht möglich. Für besonders vertrauliche Unterlagen vereinbaren wir auf Wunsch einen sicheren Übermittlungsweg.</p>

<p>Kommt es zu einer Verletzung der Datensicherheit, die für Sie ein hohes Risiko mit sich bringt, melden wir dies dem EDÖB und informieren Sie, soweit dies nach Art. 24 revDSG erforderlich ist.</p>

<h3>9. Ihre Rechte</h3>

<p>Im Rahmen des anwendbaren Rechts stehen Ihnen folgende Rechte zu:</p>

<ul>
<li><strong>Auskunft</strong> (Art. 25 revDSG): Sie können erfahren, ob und welche Personendaten wir über Sie bearbeiten.</li>
<li><strong>Berichtigung</strong> (Art. 32 revDSG): Unrichtige Daten lassen wir auf Ihre Meldung hin berichtigen.</li>
<li><strong>Löschung oder Vernichtung</strong>: Sie können die Löschung verlangen, soweit keine gesetzliche Aufbewahrungspflicht entgegensteht.</li>
<li><strong>Datenherausgabe und -übertragung</strong> (Art. 28 revDSG): Sie können die Herausgabe der Daten, die Sie uns bekanntgegeben haben, in einem gängigen elektronischen Format verlangen.</li>
<li><strong>Widerspruch</strong>: Sie können einer Bearbeitung widersprechen und eine erteilte Einwilligung jederzeit mit Wirkung für die Zukunft widerrufen.</li>
</ul>

<p>Zur Ausübung genügt eine formlose Nachricht an <a href="mailto:{{email}}">{{email}}</a>. Zum Schutz Ihrer Daten müssen wir Ihre Identität überprüfen; wir bitten Sie deshalb um einen geeigneten Nachweis. Die Auskunft ist grundsätzlich kostenlos.</p>

<p>Sie haben zudem das Recht, sich beim EDÖB zu beschweren: Eidgenössischer Datenschutz- und Öffentlichkeitsbeauftragter, Feldeggweg 1, 3003 Bern, <a href="https://www.edoeb.admin.ch" target="_blank" rel="noopener">www.edoeb.admin.ch</a>. Unterstehen Sie der DSGVO, können Sie sich an die Aufsichtsbehörde Ihres Wohnsitzstaats wenden.</p>

<h3>10. Keine automatisierte Einzelentscheidung</h3>

<p>Wir treffen keine Entscheidungen, die für Sie mit einer Rechtsfolge verbunden sind oder Sie erheblich beeinträchtigen und ausschliesslich auf einer automatisierten Bearbeitung beruhen (Art. 21 revDSG). Wir betreiben kein Profiling.</p>

<p>Bei der Überarbeitung von Texten setzen wir unterstützend Sprachtechnologie ein. Die inhaltliche Verantwortung und die abschliessende Prüfung liegen stets bei einer Person unseres Teams; jeder Text wird nach dem 4-Augen-Prinzip geprüft. Möchten Sie den Einsatz solcher Werkzeuge für Ihren Auftrag ausschliessen, teilen Sie uns das bitte bei der Beauftragung mit.</p>

<h3>11. Änderungen</h3>

<p>Wir können diese Datenschutzerklärung anpassen, wenn sich unsere Bearbeitungen oder die rechtlichen Vorgaben ändern. Massgebend ist die jeweils auf dieser Seite veröffentlichte Fassung.</p>

<p><em>Stand: 3. August 2026</em></p>
HTML;
}

/**
 * Impressum.
 */
function textmaker_imprint_template(): string {
	return <<<'HTML'
<h2>Impressum</h2>

<h3>Verantwortlich für den Inhalt</h3>

<p><strong>{{firma}}</strong><br>
Daniel Dubouloz<br>
{{strasse}}<br>
{{ort}}<br>
Schweiz</p>

<p>E-Mail: <a href="mailto:{{email}}">{{email}}</a><br>
Telefon: {{telefon}}</p>

<h3>Vertretungsberechtigte Person</h3>

<p>Daniel Dubouloz</p>

<h3>Unternehmensangaben</h3>

<p>{{firma}} ist nicht im Handelsregister eingetragen und nicht mehrwertsteuerpflichtig. Eine Unternehmens-Identifikationsnummer (UID) besteht nicht. Unsere Preise verstehen sich daher ohne Mehrwertsteuer.</p>

<h3>Haftungsausschluss</h3>

<p>Wir erstellen die Inhalte dieser Website mit Sorgfalt, übernehmen jedoch keine Gewähr für deren Richtigkeit, Genauigkeit, Aktualität und Vollständigkeit.</p>

<p>Haftungsansprüche wegen Schäden materieller oder immaterieller Art, die aus dem Zugriff auf diese Website, aus ihrer Nutzung oder Nichtnutzung, aus Missbrauch der Verbindung oder aus technischen Störungen entstehen, sind ausgeschlossen.</p>

<p>Alle Angebote auf dieser Website sind unverbindlich. Wir behalten uns ausdrücklich vor, Teile der Seiten oder das gesamte Angebot ohne Ankündigung zu ändern, zu ergänzen, zu löschen oder die Veröffentlichung zeitweise oder endgültig einzustellen.</p>

<h3>Haftung für Links</h3>

<p>Verweise und Links auf Websites Dritter liegen ausserhalb unseres Verantwortungsbereichs. Für deren Inhalte übernehmen wir keine Verantwortung. Der Zugriff auf solche Websites und ihre Nutzung erfolgen auf eigene Gefahr.</p>

<h3>Urheberrecht</h3>

<p>Die Urheber- und alle weiteren Rechte an Inhalten, Bildern, Fotos und übrigen Dateien dieser Website liegen ausschliesslich bei {{firma}} oder bei den ausdrücklich genannten Rechteinhaberinnen und Rechteinhabern. Für die Reproduktion jeglicher Elemente ist die vorgängige schriftliche Zustimmung erforderlich.</p>

<h3>Datenschutz</h3>

<p>Wie wir Personendaten bearbeiten, beschreibt unsere <a href="/datenschutz/">Datenschutzerklärung</a>.</p>

<h3>Realisation Website</h3>

<p><a href="https://walia-solutions.ch" target="_blank" rel="noopener">Walia Solutions</a></p>
HTML;
}
