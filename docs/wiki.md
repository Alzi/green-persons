Willkommen im _Grüne Personen_ Wiki!
====================================

Das Plugin fügt einen neuen _Inhaltstyp_ (custom post type) **Personen** zur Seite hinzu. Jede Person funktioniert dann also ähnlich einem Beitrag oder einer Seite.
Über sog. Metadaten lassen sich zu jeder Person Kontaktdaten und andere Informationen abspeichern und im Backend verwalten. Jede Person kann auch über eine Detail-Seite individuell beschrieben und dargestellt werden (bspw. mit Lebenslauf, Zielen o.ä.)
Über die Gliederung in **Abteilungen** können Gruppen von Personen zusammengestellt werden, die dann über Shortcodes im Frontend angezeigt werden können.

## Person erstellen

Im Wordpress-Backend wurde ein neuer Menüpunkt **Personen** hinzugefügt. Hierüber kann eine Person ganz ähnlich einem Beitrag oder einer Seite erstellt werden. Dort gibt es allerdings dann zusätzlich Felder für einige personenbezogene Daten.

![screenshot-1][img-1]

## Abteilungen

Abteilungen funktionieren wie Kategorien bei Beiträgen. Sie können entweder über das Menü aufgerufen werden (Personen -> Abteilungen) oder innerhalb der Bearbeitung der Person in der rechten Seitenleiste in der Box **Abteilung** zugewiesen und neu angelegt werden.

![screenshot-2][img-2]

| Parameter | Erklärung |
|---|---|
| Name | So wird die **Abteilung** im Backend angezeigt. Also z.B. in der Auflistung der Personen und beim Bearbeiten der Person in der Sidebar |
| Titelform | Hier kann ganz gut eine Abkürzung benutzt werden. Sie darf nur einmal verwendet werden und wird dann im Shortcode in der Form `abteilung="lgs"` benutzt. |
| Beschreibung | Hier kann eine ausführliche Beschreibung der **Abteilung** stehen (optional)

## Shortcodes

Mit Shortcodes wird unserem Plugin mitgeteilt, welche Daten es auf der Website in welcher Art darstellen soll. Im Wordpress-Editor fügt man einen Block 'Shortcode' hinzu und trägt dort in die Zeile die Anweisung in der folgenden Form ein:

`[shortcodename parameter1="wert1" parameter2="wert2"...]`

### Ein Beispiel

Die Mitarbeiter der Geschäftsstelle (Abteilung **lgs**) sollen auf einer bestimmten Seite als **Team** angezeigt werden.

- Die Abteilung **lgs** muss angelegt werden, falls sie das noch nicht ist
- Alle Personen, die dort angezeigt werden sollen müssen im Backend angelegt werden
- Sie müssen im Feld Sortierreihenfolge1 \[personen-team\] eine Zahl eingetragen haben
- Jede Person muss der Abteilung **lgs** zugeordnet werden.
- Auf der Seite muss an der Stelle, wo die Personen angezeigt werden sollen ein Block 
  **Shortcode** eingefügt werden
- Dort wird folgende Shortcode-Anweisung eingetragen (inkl. Klammern!):

        [personen-team abteilung="lgs"]

### Shortcode-Parameter

Es gibt derzeit 2 Shortcodes, welche die Daten aus dem Backend etwas unterschiedlich anzeigen:

#### personen-team

| Screenshot | Default-Ansicht |
|---|---|
| ![screenshot-4][img-4] | Hier werden angezeigt:<br>- Das **Beitragsbild**<br>- Die **Tätigkeit**<br>- Links zu **Website**, **E-Mail** und **Social-Media-Platformen** als Icons<br>- Die **Adresse** (nur wenn eingetragen)<br>- **Telefon1**<br>- **Telefon2** |

Durch optionale Parameter kann die Darstellung noch angepasst werden:

| Parameter | möglicher Wert | Erklärung
|---|---|---|
| abteilung | \<Titel der Abteilung\> | _obligatorisch_ Welche Abteilung soll angezeigt werden? |
| jobinfo | nein | _optional_ Die Tätigkeitsbeschreibung wird ausgeblendet |
| kurzinfo | ja | _optional_ Die Kurzinfo wird angezeigt |
| telefon | nein | _optional_ Die Telefonnummern werden ausgeblendet |
| button | ja | _optional_ Der **Details**-Button wird angezeigt |


#### personen-detail

| Screenshot | Default-Ansicht |
|---|---|
|  ![screenshot-5][img-5] | Hier werden angezeigt:<br>- Das **Beitragsbild**<br>- Die **Kurzinfo**<br>- Links zu **Website**, **E-Mail** und **Social-Media-Platformen** als Icons<br>- Die **Adresse** (nur wenn eingetragen)<br>- Der Button **Details** mit Link zur Personenseite |

Durch optionale Parameter kann die Darstellung noch angepasst werden:

| Parameter | möglicher Wert | Erklärung
|---|---|---|
| abteilung | \<Titel der Abteilung\> | _obligatorisch_ Welche Abteilung soll angezeigt werden? |
| jobinfo | ja | _optional_ Die Tätigkeitsbeschreibung wird angezeigt |
| kurzinfo | nein | _optional_ Die Kurzinfo wird ausgeblendet |
| telefon | ja | _optional_ Die Telefonnummern werden angezeigt |
| button | nein | _optional_ Der **Details**-Button wird ausgeblendet |

#### Beispiel

| Screenshot | Shortcode mit optionalen Parametern |
|---|---|
|  ![screenshot-6][img-6] | `[personen-team abteilung="marc" kurzinfo="ja" button="ja"]` |


[img-1]:https://github.com/Alzi/green-persons/blob/main/docs/backend_open_person_menu.jpg "Backend, Person bearbeiten"
[img-2]:https://github.com/Alzi/green-persons/blob/main/docs/scr_abteilungen.jpg "Backend, Abteilungen bearbeiten"
[img-3]:https://github.com/Alzi/green-persons/blob/main/docs/scr_sidebar_abteilungen.jpg "Metabox 'Abteilungen' in der Sidebar"
[img-4]:https://github.com/Alzi/green-persons/blob/main/docs/scr_person_team.jpg "Frontend Darstellung einer Person in der Ansicht \"Team\""
[img-5]:https://github.com/Alzi/green-persons/blob/main/docs/scr_person_detail.jpg "Frontend Darstellung einer Person in der Ansicht \"Detail\""
[img-6]:https://github.com/Alzi/green-persons/blob/main/docs/scr_person_team_extra.jpg "Frontend Darstellung einer Person in der Ansicht \"Team\" mit zusätzlichen Infos"