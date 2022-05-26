<?php 

session_start();

if(!isset($_SESSION['clt_id'])){
    header("Location: logout.php");
}else{

    require 'sql.php';
    // Schleife durch Spaltenbezeichnungen für Kunden ID ausser für Adresszeile2 -> hochzählen wenn leer
    $clt_id = $_SESSION['clt_id'];
    $clientdata .= $clt_id;
    $clt_id_login_query = mysqli_query($connect,  $clientdata);
    $clt_id_fetch = mysqli_fetch_assoc($clt_id_login_query);

    $clt_empty_counter = 0;

    $clt_empty_query = mysqli_query($connect, $clt_empty); 
    while (($clt_empty_reply = mysqli_fetch_assoc($clt_empty_query)) !== null) { 
        $clt_currentvar = $clt_empty_reply['clt_alert_varname'];

        if($clt_currentvar !== 'clt_adr_line2'){
                if ($clt_currentvar == 'clt_password'){
                    $clt_currentvar = 'clt_pw';
                }
                if($clt_id_fetch[$clt_currentvar] == null || $clt_id_fetch[$clt_currentvar] == ''){
                    $clt_empty_counter += 1;
                }
        }
    }

    mysqli_free_result($clt_empty_query);
    mysqli_free_result($clt_id_login_query);

    if ($clt_empty_counter >= 1){
        // Autodirect to Account wenn Accountdaten nicht vollständig
        session_start();
        $_SESSION['service_alert'] = 'Please fill account data before using service.';
        header('Location: account.php');

    mysqli_close($connect);
    }
}

    // Top HTML, Header und CSS einfügen
    include('top.php'); 
    include('header.php'); 
?>

<?php
    echo 'Awesome Service. I swear!'
?>


<?php 
    // Bottom HTML einfügen
    include('bottom.php'); 
?>