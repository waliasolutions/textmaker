# teXtmaker

Nachbau von [textmaker.ch](https://www.textmaker.ch) als eigenständiges WordPress-Theme —
ohne Elementor, ohne WooCommerce, ohne Zahlungsmodul. Kompatibel mit PHP 8.1 bis 8.5.

Das Repository enthält zwei Dinge:

| Ordner       | Inhalt                                                                       |
|--------------|------------------------------------------------------------------------------|
| `artifact/`  | Eigenständige HTML-Design-Referenz der Startseite (eine Datei, keine Abhängigkeiten) |
| `textmaker/` | Das WordPress-Theme                                                          |

---

## Installation

1. Den Ordner `textmaker/` zippen und unter **Design → Themes → Theme hinzufügen → Theme hochladen**
   installieren, oder direkt nach `wp-content/themes/textmaker/` kopieren.
2. Theme aktivieren.
3. Unter **Einstellungen → Lesen** eine statische Seite als Startseite festlegen. Die Startseite
   nutzt automatisch `front-page.php` — welche Seite gewählt wird, spielt für die Darstellung
   keine Rolle.
4. **teXtmaker → Bilder importieren** öffnen und den Import starten (siehe unten).

Elementor, Elementor Pro, GTM4WP und WooCommerce werden nicht mehr benötigt. Solange sie noch
aktiv sind, entfernt das Theme ihre Frontend-Assets von der Startseite.

---

## Bilder aus der Live-Domain importieren

**teXtmaker → Bilder importieren** holt in Etappen von `https://www.textmaker.ch`:

- das Logo (wird direkt als Website-Logo gesetzt),
- das Hintergrundbild des Heros,
- die 7 Ablauf-Screenshots samt Bildunterschriften,
- die 6 Team-Porträts samt Funktion,
- die 22 Referenzen samt Publikation und Autorenzeile,
- die 5 Referenz-Logos.

Zu jedem Bild wird der passende Eintrag angelegt. Bereits geholte Dateien werden übersprungen —
der Import ist beliebig oft wiederholbar. Über *Historie zurücksetzen* lässt sich das Gedächtnis
leeren, falls Dateien neu geladen werden sollen.

Das **Hero-Hintergrundbild** steht nicht im Seiten-HTML, sondern im Stylesheet, das der alte
Page-Builder erzeugt hat. Der Import liest es von dort aus und setzt es als Hintergrund. Klappt
das nicht — etwa weil die Seite inzwischen umgebaut wurde —, lässt es sich im Customizer unter
*Hero* von Hand wählen; dort steuert auch ein Regler die Abdunklung.

### Rechtliche Seiten

**Rechtliche Seiten übernehmen** füllt AGB, Datenschutz und Impressum. Diese Seiten wirken leer,
weil ihr Text nie in `post_content` stand, sondern in den Daten des alten Page-Builders — ist der
abgeschaltet, rendert WordPress nichts.

Der Import sucht darum in dieser Reihenfolge:

1. **Builder-Daten in dieser Datenbank** — der Text wird direkt aus dem gespeicherten JSON-Baum
   der Seite gelesen. Das ist der zuverlässige Weg, wenn das Theme auf derselben Website läuft.
2. **Live-Domain** — nur als Rückfall. Läuft das Theme bereits auf textmaker.ch, liefert dieser
   Weg nichts mehr, weil die Live-Seite dann schon mit diesem Theme ausgeliefert wird.

Übernommene Seiten werden vom Page-Builder gelöst (`_elementor_edit_mode` und das
Builder-Seitentemplate werden entfernt), damit WordPress den Inhalt selbst rendert. Seiten, die
bereits echten Inhalt in `post_content` haben, bleiben unangetastet.

### Datenschutz und Impressum nach revDSG

Der zweite Knopf, **Vorlagen einsetzen**, schreibt neue Fassungen von Datenschutzerklärung und
Impressum auf dem Stand des revidierten Schweizer Datenschutzgesetzes (revDSG) und der
Datenschutzverordnung (DSV) — dem für 2026 massgeblichen Stand. Berücksichtigt sind ausserdem die
EU-DSGVO für Anfragen aus dem EWR und das Swiss-U.S. Data Privacy Framework für Datenflüsse in
die USA. Die AGB bleiben unangetastet; das sind eure Geschäftsbedingungen.

Firmenname, Adresse, E-Mail und Telefon werden aus den Theme-Optionen eingesetzt. Anders als beim
Import aus der Live-Domain wird hier bewusst **überschrieben** — deshalb steht eine Rückfrage davor.

Die Datenschutzerklärung deckt ab: verantwortliche Stelle, Kontaktformular samt Datei-Upload,
gelieferte Texte als Auftragsbearbeitung, Vertrags- und Rechnungsdaten, Server-Protokolle,
Kontaktwege inklusive WhatsApp, Cookies nach Art. 45c FMG, Google Tag Manager, die eingebundenen
Google-Rezensionen, Bekanntgabe ins Ausland nach Art. 16 revDSG, Aufbewahrungsfristen inklusive
der zehnjährigen Pflicht nach Art. 958f OR, Datensicherheit und Meldepflicht nach Art. 24 revDSG,
Betroffenenrechte nach Art. 25/28/32 revDSG, EDÖB als Aufsichtsbehörde sowie den Ausschluss
automatisierter Einzelentscheidungen samt Hinweis auf den unterstützenden Einsatz von
Sprachtechnologie.

Das Impressum enthält verantwortliche Stelle, Unternehmensangaben, Haftungs- und
Urheberrechtshinweise sowie die Zeile **Realisation Website: Walia Solutions**
(<https://walia-solutions.ch>).

Die Vorlagen sind vollständig ausgefüllt — es sind keine Platzhalter mehr zu ersetzen:

- **Hosting:** Metanet AG, Zürich, Server in der Schweiz. Weil die Daten das Land nicht verlassen,
  sagt die Erklärung das ausdrücklich: Anfragen und gelieferte Texte bleiben in der Schweiz, eine
  Bekanntgabe ins Ausland betrifft nur die eingebundenen Dienste von Google und Elfsight.
- **Unternehmensangaben:** kein Handelsregistereintrag, keine Mehrwertsteuerpflicht, keine UID.
  Das steht so im Impressum, zusammen mit dem Hinweis, dass die Preise ohne Mehrwertsteuer gelten.
- **Stand der Datenschutzerklärung:** 3. August 2026.

Die Vorlagen sind eine sorgfältig erstellte Grundlage, **aber keine Rechtsberatung**. Lass sie vor
dem Livegang von einer rechtskundigen Person prüfen. Beim Einsetzen wird die Datenschutzseite
zugleich als WordPress-Datenschutzseite hinterlegt.

Zeigt die Quelldomain woanders hin (Staging, alte Domain), lässt sie sich per Filter umbiegen:

```php
add_filter( 'textmaker_source_domain', fn() => 'https://staging.textmaker.ch' );
```

---

## Was wo bearbeitet wird

Alles liegt unter dem Menüpunkt **teXtmaker**.

| Bereich                  | Wo                                                      |
|--------------------------|---------------------------------------------------------|
| Alle Texte der Startseite | **Design → Customizer → teXtmaker — Inhalte**           |
| Team                     | **teXtmaker → Team** (Bild, Name, Funktion)             |
| Referenzen               | **teXtmaker → Referenzen** (Bild, Publikation, Autor)   |
| Referenz-Logos           | **teXtmaker → Referenz-Logos** (Bild, optionaler Link)  |
| Ablauf der Korrektur     | **teXtmaker → Ablauf der Korrektur** (Bild, Bildunterschrift) |
| Kundenmeinungen          | **teXtmaker → Kundenmeinungen** (Fallback ohne Elfsight) |
| Eingegangene Anfragen    | **teXtmaker → Anfragen**                                |

Die Reihenfolge in den Karussells steuert das Feld **Reihenfolge** unter *Seiten-Attribute* —
kleinere Zahlen erscheinen zuerst.

### Customizer-Bereiche

- **Hero** — Überschrift, unterstrichene Wörter (kommagetrennt), Stempel-Text, die vier
  Buttons im Format `Beschriftung|Ziel`, Hintergrundbild und dessen Abdunklung.
- **Lektorat-Service** — Überschrift und die Punkte (einer pro Zeile).
- **Kundenmeinungen** — Überschrift und Elfsight-App-ID.
- **Ablauf der Korrektur** — Überschrift und Einleitung.
- **Lektorate / Preise** — beide Textspalten und die Richtpreise (`Bezeichnung|Betrag`).
- **Offerte anfragen** — Überschrift, WhatsApp-Link, Empfängeradresse, Bestätigungstext.
- **Referenzen** — Überschrift.
- **Fusszeile** — Dienstleistungen, Adresse, E-Mail, Telefon, Copyright-Zeile.
- **Google Tag Manager** — Container-ID.
- **Abschnitte ein- und ausblenden** — jeder Abschnitt einzeln schaltbar.
- **Farben** — Akzentfarbe und dunkle Flächen.

Für die Fliesstexte der Preisspalten gilt eine schlanke Auszeichnung:

```
### Grosse Überschrift
**Zwischentitel**
**fett** mitten im Satz
- Aufzählungspunkt
```

Leerzeile = neuer Absatz.

---

## Google-Rezensionen

Die Rezensionen kommen weiterhin über das bestehende Elfsight-Widget. Die App-ID
(`6a505a69-02af-4f6a-8f46-ca7603d08c2e`) ist im Customizer unter *Kundenmeinungen* hinterlegt
und dort änderbar. Das Anbieter-Skript wird nur geladen, wenn eine ID gesetzt ist und der
Abschnitt sichtbar ist.

Wird das Feld geleert, zeigt das Theme stattdessen die unter **teXtmaker → Kundenmeinungen**
gepflegten Einträge — ohne externes Skript. Sind auch dort keine Einträge vorhanden, entfällt
der Abschnitt.

---

## Auffindbarkeit: Suchmaschinen und KI-Assistenten

**Seitentitel und Beschreibung** stehen im Customizer unter *Auffindbarkeit*. Einzelne Seiten
bekommen unter dem Editor ein eigenes Feld „Beschreibung für Suchmaschinen“; bleibt es leer,
wird der Textauszug verwendet. Ausgegeben werden `description`, `canonical`, Open Graph und
Twitter-Card — Letztere bestimmen, wie ein Link in WhatsApp, LinkedIn oder Slack aussieht.

Ist ein SEO-Plugin aktiv (Yoast, Rank Math, SEOPress, AIOSEO), hält sich das Theme mit den
Meta-Tags komplett heraus. Doppelte Angaben schaden mehr, als sie nützen.

**Strukturierte Daten** liefert das Theme immer — auch neben einem SEO-Plugin, weil die wenigsten
Plugins Leistungen und Preise sauber abbilden. Ausgegeben wird ein zusammenhängender
`schema.org`-Graph:

- `ProfessionalService` mit Adresse, Telefon, E-Mail, Sprachen und Einzugsgebiet,
- `OfferCatalog` aus den Dienstleistungen der Fusszeile,
- `Service` mit den Richtpreisen als `Offer` in CHF,
- `FAQPage` aus den gepflegten Fragen,
- `BreadcrumbList` auf Unterseiten.

**Warum das für Antwortmaschinen zählt:** Systeme wie Google-Übersichten, ChatGPT oder Perplexity
beantworten Fragen, statt Links zu listen. Sie zitieren am ehesten Text, der eine Frage direkt
beantwortet und maschinenlesbar ausgezeichnet ist. Genau dafür gibt es **teXtmaker → Fragen &
Antworten**: Jede Frage ist ein eigener Eintrag, erscheint auf der Startseite als aufklappbare
Antwort und wird zusätzlich als `FAQPage` ausgeliefert.

Was dort gut funktioniert: eine echte Frage als Titel („Was kostet ein Lektorat?“), die Antwort
im ersten Satz vollständig, konkrete Zahlen und Orte statt Werbesprache. Was nicht funktioniert:
Überschriften wie „Unsere Vorteile“ und Antworten, die erst im dritten Satz zur Sache kommen.

## Sitemap

Erreichbar unter `/sitemap.xml`. Sie wird bei jedem Abruf erzeugt, statt als Datei im
Wurzelverzeichnis zu liegen — so veraltet sie nicht, wenn Seiten dazukommen. Enthalten sind die
Startseite sowie alle veröffentlichten Seiten und Beiträge mit ihrem Änderungsdatum. Die internen
Inhaltstypen des Themes bleiben draussen, sie haben keine eigenen Adressen. Die `robots.txt`
verweist automatisch darauf.

Die Adresse wird über eine Rewrite-Regel bedient, die beim Aktivieren des Themes eingerichtet
wird. Liefert `/sitemap.xml` einen 404, einmal **Einstellungen → Permalinks** speichern.

WordPress' eigenes `/wp-sitemap.xml` bleibt daneben bestehen.

## Website-Symbol (Favicon)

Der Medien-Import holt das Symbol aus der Live-Domain und trägt es als Website-Symbol ein.
Ändern lässt es sich unter **Design → Customizer → Website-Informationen → Website-Symbol**.
WordPress erzeugt daraus alle benötigten Grössen inklusive Apple-Touch-Icon.

## Kontaktformular

Das Formular läuft nativ im Theme, ohne Plugin:

- Felder: Vorname, Nachname, E-Mail (Pflicht), Telefon, Wunschtermin, Bemerkungen, Datei-Upload.
- Erlaubte Uploads: DOC, DOCX, ODT, PDF, RTF, TXT — max. 5 Dateien à 25 MB. Der Dateityp wird
  über `wp_check_filetype_and_ext()` gegen den tatsächlichen Inhalt geprüft, nicht nur über die
  Endung. Anhänge landen als **private** Mediathek-Einträge, die der Anfrage zugeordnet sind.
- Schutz: WordPress-Nonce plus unsichtbares Honeypot-Feld.
- Nach dem Absenden wird per Post/Redirect/Get umgeleitet, damit ein Neuladen die Anfrage nicht
  doppelt verschickt.
- Jede Anfrage wird **zusätzlich** unter *Anfragen* gespeichert — geht der Mailversand schief,
  ist die Anfrage trotzdem da.
### Kommen die E-Mails an?

Der Versand läuft über `wp_mail()`, mit `Reply-To` der anfragenden Person. Empfänger ist die im
Customizer hinterlegte Adresse; ist dort nichts eingetragen, die Kontaktadresse aus der Fusszeile
(`staff@textmaker.ch`), sonst die Administrator-Adresse der Website.

**Ob eine Mail tatsächlich ankommt, entscheidet der Server, nicht das Theme.** Ohne SMTP verschickt
WordPress über PHPs `mail()`. Diese Nachrichten tragen keine SPF- oder DKIM-Signatur der Absender-
domain — Gmail, Outlook und die meisten Firmen-Postfächer stufen sie als Spam ein oder verwerfen
sie kommentarlos. Das ist der häufigste Grund für „das Formular funktioniert nicht“.

Deshalb:

- **Vor dem Livegang prüfen.** Unter **teXtmaker → Übersicht** steht die aktuelle Empfängeradresse,
  darunter der Knopf *Testmail senden*. Kommt sie an (auch im Spam-Ordner nachsehen), stimmt die
  Kette. Kommt sie nicht an, meldet WordPress den Serverfehler direkt zurück.
- **SMTP einrichten.** Ein SMTP-Plugin mit den Zugangsdaten des eigenen Mailanbieters und der
  eigenen Domain als Absender löst das Problem dauerhaft.
- **Nichts geht verloren.** Jede Anfrage wird unabhängig vom Mailversand unter *Anfragen*
  gespeichert. Schlägt der Versand fehl, erscheint im Backend eine Warnung mit der Fehlermeldung
  des Servers, und der Fehler wird an der betroffenen Anfrage vermerkt.

Grenzen und erlaubte Typen sind filterbar:

```php
add_filter( 'textmaker_allowed_upload_types', function ( array $types ): array {
	$types['pptx'] = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
	return $types;
} );
```

Nach jedem erfolgreichen Versand feuert:

```php
do_action( 'textmaker_contact_submitted', array $values, array $attachment_ids, int $submission_id );
```

---

## Google Tag Manager

Ersetzt das Plugin GTM4WP:

- Container-Skript im `<head>`, `noscript`-Fallback direkt nach `<body>` über `wp_body_open()`.
- Ein `dataLayer` mit `pagePostType`, `pagePostType2` und `pagePostAuthor` — dieselben Felder
  wie bisher.
- Im Backend, in der Vorschau und im Customizer wird **nicht** getrackt.
- Ungültige IDs (nicht `GTM-XXXXXXX`) werden ignoriert.

Eigene Felder ergänzen:

```php
add_filter( 'textmaker_data_layer', function ( array $data ): array {
	$data['kundentyp'] = 'b2b';
	return $data;
} );
```

---

## Zahlungsmittel

Bewusst entfernt. Die Bilder „Akzeptierte Zahlungsmittel“ (PostFinance / TWINT) und die
WooCommerce-Anpassungen der alten Seite kommen im Theme nicht vor.

---

## Technische Hinweise

**PHP 8.5.** Keine impliziten Nullable-Parameter, keine `"${var}"`-Interpolation, keine
dynamischen Klassen-Eigenschaften, keine in 8.4/8.5 abgekündigten Funktionen. Getestet mit
`php -l` gegen PHP 8.4; alle verwendeten Sprachmittel (`match`, Union-Types, `str_starts_with`)
sind ab PHP 8.1 verfügbar.

**Keine externen Abhängigkeiten.** Kein jQuery, kein Swiper, keine Webfont-CDN. Karussells,
Lightbox und Scroll-Reveal sind ~200 Zeilen Vanilla-JS. Einziges externes Skript ist das
Elfsight-Widget, und nur wenn eine App-ID gesetzt ist.

**Barrierefreiheit.** Sichtbarer Fokus, Skip-Link, `aria-expanded` am Menü, Lightbox als
`role="dialog"` mit Escape- und Pfeiltasten-Bedienung, Fokus kehrt nach dem Schliessen zurück.
`prefers-reduced-motion` schaltet Stempel-Animation, Reveal und weiches Scrollen ab.

**Sprung-Links.** Menüeinträge wie `#preise` zeigen auf Abschnitte, die es nur auf der Startseite
gibt. Auf Unterseiten stellt das Theme ihnen automatisch die Startseite voran, sonst führen sie
ins Leere. Gescrollt wird weich, mit Abstand zur klebenden Kopfzeile (`scroll-padding-top`).

**Layout.** Der Body ist eine Spalte über die volle Höhe — sonst steht die Fusszeile auf kurzen
Seiten wie dem Impressum mitten im Bild. Die klebende Kopfzeile rastet unter der
WordPress-Werkzeugleiste ein. Für das horizontale Beschneiden wird `overflow-x: clip` statt
`hidden` verwendet, weil `hidden` den Body zum Scroll-Container macht und `position: sticky`
seinen Bezug zum Viewport nimmt.

**Dunkelmodus.** Die Design-Referenz in `artifact/` folgt dem Systemthema. Das Theme selbst
bleibt bewusst beim hellen Erscheinungsbild der Marke.

---

## Design-Referenz

`artifact/textmaker.html` ist eine einzelne, in sich geschlossene Datei ohne externe Ressourcen.
Sie zeigt Layout, Typografie, Abstände und das Verhalten von Karussell, Lightbox und Formular.

Weil die Bilder der Live-Domain dort nicht geladen werden können, stehen an ihrer Stelle
gebaute Platzhalter: die Ablauf-Screenshots sind als Word-Dokument mit nachverfolgten Änderungen
nachgebaut, die Referenzen als Kacheln mit Publikation und Titel. Im Theme stehen dort die
echten, importierten Bilder.
