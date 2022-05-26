<?php

// Mit Datenbank verbinden
$connect = mysqli_connect('127.0.0.1', 'root', 'cimdata', 'clients', '3306');

// SQL Anfrage zur Auflistung aller möglichen Countries
$countries = 
'SELECT * FROM country
ORDER BY cnt_name ASC';


$clientdata = 
'SELECT * FROM client
WHERE clt_id = ';


// Versuch Maxchars per Column anzusteuern (Kein Error aber leider auch kein Ergebnis) Erneut versuchen
/*
function _colchars($col){ 
    return 
        "SELECT CHARACTER_MAXIMUM_LENGTH
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = 'client'
        AND COLUMN_NAME = '.$col.'";
}
*/


$clt_empty = 
"SELECT * FROM clt_form_alert";


// Hier besser in PHP die Schleife oder in SQL die Autoschleife über WHERE cnt_email = form_email???
$clt_email_exists =
"SELECT clt_email FROM client";


$clt_id_login =
'SELECT clt_id FROM client
WHERE clt_email = ';


$clt_password_matches =
'SELECT * FROM client
WHERE clt_id = ';


$clt_id_exists =
'SELECT clt_id FROM client
WHERE clt_id = ';



?>
<?php
?>

