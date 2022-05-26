# PHP-SQL-Grundkurs-Abschlussprojekt
Registrierungs- und Anmeldeformular für Kunden

Verwendete Auzeichnungs- bzw. Programmiersprachen:
HTML, CSS, Javascript, PHP, SQL


ALLGEMEIN:

  To Do:
    > stärker dynamisieren:
      > Spaltennamen aus Datenbank fetchen und über Schleifen als dynamische Variablen abrufen
      > Statt Variablennamen hardcoden
      > um durch direkte Arbeit in der Datenbank redundante und mehrfache Skriptänderungen in PHP zu vermeiden
    > Live-Kontrolle von User-Inputs ermöglichen damit User im Momment der Eingabe Feedback über Plausibiliät seiner Eingabedaten erhält
      > Eventlistener der bei Veränderung der Formulardaten im Hintergrund absendet und sofort wieder updatet ohne Daten an die Datenbank zu senden


DATEIEN:

info.php



account.php
    Abstrakt:
    > Nach erfolgreichem Login können Kunden hier Ihre Unternehmensdaten eintragen
    > Erst vollständiger und plausibler Eingabe und Speicherung der Daten in die Datenbank, Zugriff auf (fiktive) Service-Sektion


    Konkret:
      > zu jedem Formularfeld individuelle Fehlermeldungen anhand der Plausibilitätskontolle
      > 


    To Do:
      > bislang aus Zeitgründen auf Passwortupdate verzichtet (diverse Kontroll- und Sicherheitsmechanismen erforderlich)
      > Business-Logik für Unternehmsnamen verfeinern (realistische, d.h. nicht zu strikte aber trotzdem kreative Verwendung von Sonderzeichen ermöglichen)

bottom.php


functions.php



header.php


login.php
