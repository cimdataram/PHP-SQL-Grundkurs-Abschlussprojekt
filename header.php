<?php

    // Pfadvariablendefinition für Seiten
    $start_path = '../00_PROJEKT/start.php';
    $service_path = '../00_PROJEKT/service.php';
    $account_path = '../00_PROJEKT/account.php';
    $login_path = '../00_PROJEKT/login.php';
    $signup_path = '../00_PROJEKT/signup.php';
    $logout_path = '../00_PROJEKT/logout.php';


// Nur wenn session geöffnet und clt id übergeben und clt_id in db vorhanden 
// dann soll login button zu logout button werden, sowie account zur Verfügung stehen

?>
    <header>
        <table>
            <!-- Logo -->
            <tr class="border-bottom">
                <th style="height:30px;width:60px">
                    <div id="logo">
                        <img src="images/logo.png" width ="100%" height="auto" alt="clients.io">
                    </div>
                </th>
                <!-- Startlink immer vorhanden-->   
                <th style="padding-bottom:25px" valign="bottom" align="left">
                    <a href="<?php echo $start_path ?>" style="text-decoration: none; height: 20px"><h1 style="width:180px; height: 10px">CLIENTS.IO</h1></a>
                </th>
                <th style="width:600px"></th>
                <th>

<?php
///////////////   LOGIN BEDINGTE HEADER BUTTONS   ///////////////

// Konstanten (SQL-Queries) aus SQL Skript erkennen und nutzbar machen
require 'sql.php';
// Wenn Kunden ID über Login übergeben, in Datenbank vorhanden und nicht leer (3-Fache Prüfung)
if(isset($_SESSION['clt_id'])){
    if($_SESSION['clt_id'] !== '' || $_SESSION['clt_id'] !== null){
        $clt_id_exists .= $_SESSION['clt_id'];
        $clt_id_exists_query = mysqli_query($connect,  $clt_id_exists);
        $clt_id_exists_reply = mysqli_fetch_assoc($clt_id_exists_query);
        if($clt_id_exists_reply['clt_id'] == $_SESSION['clt_id']){

            // WENN LOGGED IN DANN ALLE BUTTONS VORHANDEN (auch Prüfung über Email)
            $clt_id = $_SESSION['clt_id'];
            $clientdata .= $clt_id;
            $clt_id_login_query = mysqli_query($connect,  $clientdata);
            $clt_id_fetch = mysqli_fetch_assoc($clt_id_login_query);      
            $clt_empty_counter = 0;
                // WENN LOGGED IN  VORHANDEN DANN SERVICE BUTTON VORHANDEN
                echo '<a href="'.$service_path.'" style="text-decoration: none"><h3 style="width:110px">SERVICE</h3></a>';
                echo '</th><th>';
                // WENN LOGGED IN  VORHANDEN DANN ACCOUNT BUTTON VORHANDEN
                echo '<a href="'.$account_path.'" style="text-decoration: none"><h3 style="width:200px">ACCOUNT</h3></a>';
                echo '</th><th>';
                // WENN LOGGED IN VORHANDEN DANN LOGIN WIRD ZU LOGOUT
                echo '<a href="'.$logout_path.'" style="text-decoration: none"><h3>LOGOUT</h3></a>';
                    
                // Cache leeren für 
                mysqli_free_result($clt_id_login_query); 
            }
    // Cache leeren        
    mysqli_free_result($clt_id_exists_query);        
   
    }
}else{
    // WENN NICHT LOGGED IN DANN NUR LOGIN VORHANDEN
    // Einen Haufen HTML Leerzeichen als Platzhalter für konforme Formatierung, da sonst nichts funktioniert hat ^^
    echo '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
    </th><th style="height:50px;width:110px>';
    echo '</th><th style="height:50px;width:200px>&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';
    echo '</th><th>';
    echo '<a href="'.$login_path.' " style="text-decoration: none"><h3>LOGIN</h3></a>';
}
?>

<?php

echo '
            </th>
        </tr>
    </table>  
</header>
';

?>

