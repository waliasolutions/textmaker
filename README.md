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
- die 7 Ablauf-Screenshots samt Bildunterschriften,
- die 6 Team-Porträts samt Funktion,
- die 22 Referenzen samt Publikation und Autorenzeile,
- die 5 Referenz-Logos.

Zu jedem Bild wird der passende Eintrag angelegt. Bereits geholte Dateien werden übersprungen —
der Import ist beliebig oft wiederholbar. Über *Historie zurücksetzen* lässt sich das Gedächtnis
leeren, falls Dateien neu geladen werden sollen.

Auf derselben Seite übernimmt **Rechtliche Seiten** die Texte von AGB, Datenschutz und Impressum
aus der Live-Domain in gleichnamige WordPress-Seiten. Seiten, die bereits Inhalt haben, werden
nicht überschrieben.

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

- **Hero** — Überschrift, unterstrichene Wörter (kommagetrennt), Stempel-Text und die vier
  Buttons im Format `Beschriftung|Ziel`.
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
- Der Mailversand nutzt `wp_mail()` mit `Reply-To` der anfragenden Person. Empfänger ist die im
  Customizer hinterlegte Adresse, sonst die Administrator-Adresse.

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
