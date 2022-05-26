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
      
    > restlos alle sql-codes auslagern


DATEIEN:

info.php


____________________________________________________________________________________________________________________
account.php

    Abstrakt:
    
      > Nach erfolgreichem Login können Kunden hier Ihre Unternehmensdaten eintragen
      > Erst vollständiger und plausibler Eingabe und Speicherung der Daten in die Datenbank, Zugriff auf (fiktive) Service-Sektion

    Konkret:
    
      > Bei leer gelassenem Pflichtfeld Fomrularfeld rot umranden
      > Bei fehlgeschlagener Plausibilität 
          > Formularfeld rot umranden und Infobutton rot färben
          > Über Tooltip individuelle Fehlermeldung anhand Plausibilität je Formularfeld

    To Do:
    
      > bislang aus Zeitgründen auf Passwortupdate verzichtet (diverse Kontroll- und Sicherheitsmechanismen erforderlich)
      > Business-Logik für Unternehmsnamen verfeinern (realistische, d.h. nicht zu strikte aber trotzdem kreative Verwendung von Sonderzeichen ermöglichen)
      
____________________________________________________________________________________________________________________ 
bottom.php

    Abstrakt:
    
     > bottom code für jede subpage ausgelagert

____________________________________________________________________________________________________________________
functions.php

    Abstrakt:
    
     > ausgelagerte Funktionen für:
        > Plausibilitätsprüfung der Formularfelder
        > Empfang und Voraufbereitung der Daten über Get-Methode
____________________________________________________________________________________________________________________
header.php

    Abstrakt:
    
     > HTML header für jede subpage ausgelagert
     > wenn logged out dann login button et vice versa
     > Service button nur wenn Unternehemsdaten erfolgreich eingetragen und in Datenbank gespeichert
____________________________________________________________________________________________________________________
login.php

    Abstrakt:
    
     > Login-Formular mit Plausibilitätskontrolle (analog zu account.php)
     > bei Erfolg Session und Cookie starten

____________________________________________________________________________________________________________________
logout.php

    Abstrakt:
    
     > Bei Klick auf Login Session- und Cookiedaten löschen

____________________________________________________________________________________________________________________
service.php

    Abstrakt:
    
     > leere (fiktive Service-Seite)
     > nur Zugriff, wenn Accountdaten erfolgreich ausgefüllt und abgespeichert

____________________________________________________________________________________________________________________
sessionstart.php

    Abstrakt:
    
     > Bei erfolgreichem Login übergabe von der Kundendaten an Session
  
____________________________________________________________________________________________________________________
signup.php

    Abstrakt:
    
     > Registrierungsformular mit Doppeltprüfung in Datenbank

____________________________________________________________________________________________________________________
sql.php

    Abstrakt:
    
     > ausgelagerte SQL-Anfragen
____________________________________________________________________________________________________________________
start.php

    Abstrakt:
    
     > leere Startseite

____________________________________________________________________________________________________________________
top.php

    Abstrakt:
    
     > top code für jede subpage ausgelagert
     > HTML head + CSS

