<?php

// Session ID abrufen
session_start();

// Session-Daten löschen
session_destroy();

// Cookie löschen
setcookie('signup_conf_line1', '', 0);
setcookie('signup_conf_line2', '', 0);
setcookie('signup_email', '', 0);

// Session_Cookie löschen
setcookie('PHPSESSID', '', 0, '/');

// Autodirect zu Start
header('Location: login.php');

die();

?>
<?php
?>