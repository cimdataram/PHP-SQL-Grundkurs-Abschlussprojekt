<?php


    // Empfang der Kunden Formuladaten mit Cut, Trim zur Weiterleitung an Variablen in account
    function _receipt($element, $length) {
        return trim(substr(filter_input(INPUT_POST, $element),0, $length));
    }
  


///////////////   VALIDIERUNG EINGEGEBENER FORMULARDATEN DES KUNDEN ///////////////  

        // Eigentlich müssen Fehlermeldungen schon auftauchen während man etwas eingibt
        // Später über Javascript Eventlistener, via onchange, onclicks auf hidden Submit Button generieren

// Prüfung der Email Adresse (grob)
function _clt_email($email_validate){

    // Reicht noch lange nicht die Email_Validate Funktion
        // TO DO
            // Letzte 2 - 4 zeichen müssen buchstabe sein
            // keine Bullshit Adressen mit zu vielen gleichen zeichen oder zu vielen Sonderzeichen
    
    if(!filter_var($email_validate, FILTER_VALIDATE_EMAIL)){
        return 'invalid';
    }else{
        return 'valid';
    }
}

// Spezielle Prüfung des Firmennamens auf Sonderzeichenregeln
        ///// Reicht noch lange nicht
        ///// Weitere Firmennamenprüfungen TO DO !!!!!!!!!
                    ///// Muss mehr Zahlen oder Buchstaben haben als Sonderzeichen
                    ///// Muss überhaupt mindestens 2 Zeichen (Zahlen oder Buchstaben) haben
                    ///// Nicht zu viele Zahlen (nicht mehr als 5)
                    ///// € und $ darf nicht und andere Zeichen funktionieren bestimmt leider auch noch
                    ///// Hindi, Cyrillic und sowas darf nicht (nur Latin mit EU letters inkl. Skandi, French, Slavic etc)
                    ///// Nicht zu viele gleiche Zeichen hintereinander
                    ///// ggf sinnvolles vorgefertigtes Business Logic Schema suchen
                    ///// ä ö ü werden bis jetzt noch nicht akzeptiert

// Prüfung des Firmennamens
function _clt_company($company_spec){

    // Jedes dieser Zeichen darf nur einmal vorkommen
    $limited_spec1 = ['/', '\²', '\=', '\*', '\@', '\;', '\^', '\°', '\?', '\:', '\!', '\³', '\#', '\+', ];
    $count_spec1 = 0;
    
    for($i = 0; $i < count($limited_spec1); $i++){
        if(preg_match_all('['.$limited_spec1[$i].']', $company_spec) > 1){
        $count_spec1 += preg_match_all('['.$limited_spec1[$i].']', $company_spec);
        }
    }

    // Diese Zeichen dürfen kombiniert nicht öfter als 2 mal auftauchen
    $limited_spec2 = ['\²', '\=', '\*', '\@', '\;', '\^', '\°', '\?', '\:', '\!', '\³', '\#', '\+', ];
    $count_spec2 = 0;
    
    for($i = 0; $i < count($limited_spec2); $i++){
        $count_spec2 += preg_match_all('['.$limited_spec2[$i].']', $company_spec);
    }
    
    // Diese Zeichen dürfen kombiniert nicht öfter als 3 mal auftauchen
    $limited_spec3 = ['\&', '\[', '\]', '\(','\)', '\_', ];
    $count_spec3 = 0;
    
    for($i = 0; $i < count($limited_spec3); $i++){
        $count_spec3 += preg_match_all('['.$limited_spec3[$i].']', $company_spec);
    }
    
    // Diese Zeichen dürfen kombiniert nicht öfter als 8 mal auftauchen
        // ´Gelb markiert???? Scheinbar noch Problem
    $limited_spec8 = ['\,', '\.', '\-', '\'', '\`', '\´'];
    $count_spec8 = 0;
    
    for($i = 0; $i < count($limited_spec8); $i++){
        $count_spec8 += preg_match_all('['.$limited_spec8[$i].']', $company_spec);
    }

    // Summe aller Sonderzeichen
    $count_specs = [$count_spec1, $count_spec2, $count_spec3, $count_spec8];

    // Prüfung auf unerlaubte Sonderzeichen (nur in preg_match erwähnte sind erlaubt)
    // ^ means start of defining allowed letters and $ means end of defining allowed letters
    $allowed_specs = preg_match('/^[a-zA-Z0-9 ²³\=*@;^\'\`\´°?\-:\/!\#\+&\[\](),.]+$/u', $company_spec);

    // Prüfung aller Bedingungen
    if(
        $count_specs[0] > 1 ||
        $count_specs[1] > 2 ||
        $count_specs[2] > 3 ||
        $count_specs[3] > 8 ||
    
        ($count_specs[0] +
         $count_specs[1] +
         $count_specs[2]) > 8 ||   
        
         $allowed_specs !== 1
    ){
        return 'invalid';
    }else{
        return 'valid';
    }
}


// Prüfung der Telefonnummer (grob)
    ///// Ist schon sehr schön
    ///// Aber möglicherweise geht es noch etwas besser
            //// zB müssen die ersten 4 bis Ziffern wirklich eine Ländervorahl sein 
            //// (am besten über 3rd Party API oder Liste aus DB)
function _clt_phone($phone_spec){

    if(
        strlen($phone_spec) >= 15 ||
        strlen($phone_spec) < 8 ||  
        preg_match('/^[Z0-9]+$/u', $phone_spec) !== 1 ||
        substr($phone_spec,0,2) !== '00' 
    ){
        return 'invalid';
    }else{
        return 'valid';
    }   
}


// Prüfung des Passwortes (grob)
    // TO DO
        // at least one special char
        // not longer than 25 chars
        // mindestens 5 verschiedene Chars count_chars($passowrd_spec,5);
function _clt_password($passowrd_spec){

    if(preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $passowrd_spec) === 0){
        return 'invalid';
    }else{
        return 'valid';
    }
}


// Prüfung der Adresszeile1 (grob)
        // TO DO
            // Mindestens genausoviel Buchstaben wie Zahlen
            // Nicht mehr als 8 Zahlen
            // Mindestens 4 Zeichen
            // Eigentlich am besten über Google Maps API 
            ///// so würden direkt alle Lokalisierungsdaten valide geprüft ggf sogar GPS Option)

function _clt_adr_line1($adr_line1_spec){
    if(
        preg_match('/^[a-zA-Z0-9 \-]+$/u', $adr_line1_spec) === 0 ||
        substr_count($adr_line1_spec,'-', 0, strlen($adr_line1_spec)) > 1
    ){
        return 'invalid';
    }else{
        return 'valid';
    } 
}

// Prüfung der Adresszeile2 (grob)
    // wenn leer dann valid
    // wenn nicht leer dann prüfung
    // Validität (s. Adresszeile 1) Eigentlich könnte ich Variable an adr_line1 Funktion übergeben

function _clt_adr_line2($adr_line2_spec){
    if(
        preg_match('/^[a-zA-Z0-9 \-]+$/u', $adr_line2_spec) === 0 ||
        substr_count($adr_line2_spec,'-', 0, strlen($adr_line2_spec)) > 1
    ){
        return 'invalid';
    }else{
        return 'valid';
    }
}

// Prüfung der Postleitzahl (grob)
        // Nur Zahlen und Buchstaben, nicht mehr als 15 Zeichen (wegen zB England)
        // Mindestens 3 Zeichen
function _clt_zip($zip_spec){
        if(
            preg_match('/^[a-zA-Z0-9]+$/u', $zip_spec) === 0 ||
            strlen($zip_spec) > 15 ||
            strlen($zip_spec) < 3
        ){
            return 'invalid';
        }else{
            return 'valid';
        }
}

// Prüfung der Stadt (grob)
    // Nur Buchstaben
    // Maximal 30 Zeichen
    // Mindestens 3 Zeichen
function _clt_city($city_spec){
    if(
        preg_match('/^[a-zA-Z]+$/u', $city_spec) === 0 ||
        strlen($city_spec) >= 30 ||
        strlen($city_spec) < 3
    ){
        return 'invalid';
    }else{
        return 'valid';
    }
}

// Prüfung des Landes (grob)
    // wenn nicht default wert dann per SQL Abfrage prüfen ob Wert in Heidi
    // Eigentlich müsste hier auch SQL-Injection geprüft werden, da HTML Manipulation möglich
function _clt_country($country_spec){
    if($country_spec == 'Please select country'){
        return 'empty';
    }else{
        // Konstanten (SQL-Queries) aus SQL Skript erkennen und nutzbar machen
        require 'sql.php';
        $clt_countries_query = mysqli_query($connect, $countries); 
        $cnt_match_counter = 0;
        // Schleife zum Abgleich der Formularselektion mit Datenbank Tabelle countries
        while (($clt_countries_reply = mysqli_fetch_assoc($clt_countries_query)) !== null){
            if($country_spec !== $clt_countries_reply['cnt_name']){
                $cnt_match_counter += 0;
            }else{
                $cnt_match_counter += 1;
            }
        }
        if($cnt_match_counter >= 1){
            return 'valid';
        }else{
            return 'invalid';
        }
    } 
// Cache leeren    
mysqli_free_result($clt_countries_query);
}

// Prüfung Email in Login
function _login_email($email_spec){
    // Validitätsprüfung und Vorhandenprüfung (gleiche wie SignUp) Übergabe an entsprechende Funktion
   return _signup_email($email_spec);
}

// Prüfung Password in Login
// Bis auf preg_match eig gleiche Prüfung wie SignUp (daher zum Teil Übergabe an entsprechende Funktion möglich)
function _login_password($password_spec, $id_spec){
    require 'sql.php';
    // Avoid SQL Injection
    $password_spec_sec = mysqli_real_escape_string($connect, $password_spec);
        // Validitätsprüfung
    if(
        preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password_spec) == 0 ||
        strlen($password_spec_sec) >= 30 ||
        count(array_unique(str_split($password_spec_sec))) < 5
        ){  
            return 'invalid';
    }else{
        // Match-Prüfung: Passwort eingabe abgleichen wo Emaileingabe = EMail in Datenbank

        // SQL Code um Emailvariable ergänzen
        $clt_password_matches .= $id_spec;
        // Mit DB verbinden und Abbruch falls SQL-Injection
        $clt_password_query = mysqli_query($connect, $clt_password_matches) or die(mysqli_error($connect));
   
        $password_match_counter = 0;
        /////////// KORREKTUR: KEINE SCHLEIFE NOTWENDIG WEIL "WHERE" IN SQL VORHANDEN IST bzw. SEIN SOLL
        while (($clt_password_reply = mysqli_fetch_assoc($clt_password_query)) !== null){
        // Abgleich über Password = Hash: Wenn unmatch 0, match 1  
            if(mysqli_real_escape_string($connect, !password_verify($password_spec_sec, $clt_password_reply['clt_pw']))){
                $password_match_counter += 0;
            }else{
                $password_match_counter += 1;
            }
        }
        if($password_match_counter >= 1){
            return 'exists';
        }else{
            return 'valid';
        }    
    }
// Cache leeren und disconnecten
mysqli_free_result($clt_password_query);
mysqli_free_result($email_spec_sec);
mysqli_close($connect);
}

// Prüfung Email in Sign Up
function _signup_email($email_spec){
    // Validitätsprüfung (grob)
    if(!filter_var($email_spec, FILTER_VALIDATE_EMAIL)){
        return 'invalid';
    }else{
        // Doppeltprüfung
        require 'sql.php';
        // SQL Injection vermeiden
        $email_spec_sec = mysqli_real_escape_string($connect, $email_spec);
        // Mit DB verbinden und Abbruch falls SQL-Injection
        $clt_email_query = mysqli_query($connect, $clt_email_exists) or die(mysqli_error($connect)); 
        $email_match_counter = 0;
        /////////// KORREKTUR: KEINE SCHLEIFE NOTWENDIG WEIL "WHERE" IN SQL VORHANDEN IST bzw. SEIN SOLL
        while (($clt_email_reply = mysqli_fetch_assoc($clt_email_query)) !== null){
            if($email_spec_sec  !== $clt_email_reply['clt_email']){
                $email_match_counter += 0;
            }else{
                $email_match_counter += 1;
            }
        }
        if($email_match_counter >= 1){
            return 'exists';
        }else{
            return 'valid';
        }    
    }
// Cache leeren und disconnecten    
mysqli_free_result($clt_email_query);
mysqli_free_result($email_spec_sec);
mysqli_close($connect);
}

// Prüfung Passwort in Sign Up
function _signup_password($password_spec){
    if(
        // Mindestens 8 Zeichen, 1 Groß- und 1 Kleinbuchstabe, 1 Zahl, weniger als 30 und mehr als 5 Zeichen
        preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password_spec) == 1 &&
        strlen($password_spec) < 30 &&
        count(array_unique(str_split($password_spec))) >=5
    ){
        return 'valid';
    }else{
        return 'invalid';
    }
}

// Prüfung Passwort Bestätigung in Sign Up
function _signup_passconf($password_spec, $passconf_spec){
    if(
        // Mindestens 8 Zeichen, 1 Groß- und 1 Kleinbuchstabe, 1 Zahl, weniger als 30 und mehr als 5 Zeichen
        preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $passconf_spec) == 0 ||
        strlen($passconf_spec) >= 30 ||
        count(array_unique(str_split($password_spec)))  < 5 ||
        $password_spec !== $passconf_spec
    ){
        return 'invalid';
    }else{
        return 'valid';
    }   
}

?>
<?php
?>