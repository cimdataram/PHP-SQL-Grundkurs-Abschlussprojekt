<?php
    session_start();

    $_SESSION['clt_id'] = $clt_id;

    require 'sql.php';
    // Schleife durch Spaltenbezeichnungen für Kunden ID ausser für Adresszeile2 -> hochzählen wenn leer
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
    mysqli_close($connect);     


    if ($clt_empty_counter >= 1){
        // Autodirect to Account wenn Accountdaten nicht vollständig
        header('Location: account.php');
    }else{
        // Autodirect zu Service wenn Accountdaten vollständig
        header('Location: service.php');
    }

    
?>

<?php
?>