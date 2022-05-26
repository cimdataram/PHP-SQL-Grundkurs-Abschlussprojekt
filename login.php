<?php 
// Konstanten (SQL-Queries) aus SQL Skript erkennen und nutzbar machen
require 'sql.php';

// Funktionen aus Skript mit Funktionen erkennen und nutzbar machen
require 'functions.php';

if(isset($_COOKIE["signup_email"])){
    $signup_email = $_COOKIE["signup_email"];
    $signup_conf_line1 = $_COOKIE["signup_conf_line1"];
    $signup_conf_line2 = $_COOKIE["signup_conf_line2"];
}

$clt_submit_length = 10;
$clt_email_length = 35;
$clt_password_length = 40;

$clt_submit = _receipt('clt_submit', $clt_submit_length);
$clt_email = _receipt('clt_email', $clt_email_length);
$clt_password = _receipt('clt_password', $clt_password_length);

$clt_submit_ = '';
$clt_email_ = ['alert_status' => '', 'alert_text' => ''];
$clt_password_ = ['alert_status' => '', 'alert_text' => ''];




// Wurden Formulardaten gesendet?
if($clt_submit !== 'clt_submit'){
    $form_status = 'none';

}elseif($clt_submit == 'clt_submit'){ 

    // Leer Prüfung Email
    if($clt_email == null || $clt_email == ''){
        $clt_email_ = ['alert_status' => 'empty', 'alert_text' => 'Please type email address'];
    // Invalid Prüfung Email
    }elseif(_login_email($clt_email) == 'invalid'){
        $clt_email_ = ['alert_status' => 'invalid', 'alert_text' => 'Please type valid email address'];
    // Match Prüfung Email
    }elseif(_login_email($clt_email) == 'exists'){
        $clt_email_ = ['alert_status' => 'exists', 'alert_text' => ''];
        // Kunden ID über Email fetchen:
        $clt_id_login .= '"'.$clt_email.'"';
        $clt_id_login_query = mysqli_query($connect, $clt_id_login);
        $clt_id_fetch = mysqli_fetch_assoc($clt_id_login_query);

        if($clt_id_fetch['clt_id'] == null){
            $clt_id =  0;   
        }else{
            $clt_id =  $clt_id_fetch['clt_id'];
        }
    }else{
        $clt_email_ = ['alert_status' => 'valid', 'alert_text' => 'This email address does not exist']; 
    }
    //mysqli_free_result($clt_id_login_query);

    if($clt_email_['alert_status'] !== 'exists'){
        $clt_password_ = ['alert_status' => 'email', 'alert_text' => 'Please type email address'];
    }else{    
    // Leer Prüfung Password
        if($clt_password == null || $clt_password == ''){
            $clt_password_ = ['alert_status' => 'empty', 'alert_text' => 'Please type password'];
        // Invalid Prüfung Password
        }elseif(_login_password($clt_password, $clt_id) == 'invalid'){
            $clt_password_ = ['alert_status' => 'invalid', 'alert_text' => 'Please type valid password'];
        // Match Prüfung EMail
        }elseif(_login_password($clt_password, $clt_id) == 'exists'){
            $clt_password_ = ['alert_status' => 'exists', 'alert_text' => ''];
        }else{
            $clt_password_ = ['alert_status' => 'valid', 'alert_text' => 'Wrong email address']; 
        }

    }

        // In Login bedeutet Alert Status exists = match und valid = valid aber no match
        // 2 mal exists = form status ist valid

        $form_conc_invalid =    (
            $clt_email_['alert_status'].'/'. 
            $clt_password_['alert_status'].'/'.
            ''
        );

        $form_conc_empty =      (
            $clt_email_['alert_status'].'/'. 
            $clt_password_['alert_status'].'/'.
            ''
        );

        $form_conc_exists =      (
            $clt_email_['alert_status'].'/'. 
            $clt_password_['alert_status'].'/'.
            ''
        );

        $form_conc_valid =      (
            $clt_email_['alert_status'].'/'. 
            $clt_password_['alert_status'].'/'.
            ''
        );

        $form_conc_email =      (
            $clt_password_['alert_status'].'/'.
            ''
        );

        $form_count_invalid = substr_count($form_conc_invalid, 'invalid', 0, strlen($form_conc_invalid));
        $form_count_empty = substr_count($form_conc_empty, 'empty', 0, strlen($form_conc_empty));    
        $form_count_exists = substr_count($form_conc_exists, 'exists', 0, strlen($form_conc_exists)); 
        $form_count_valid = substr_count($form_conc_valid, 'valid', 0, strlen($form_conc_valid)); 
        $form_count_email = substr_count($form_conc_email, 'valid', 0, strlen($form_conc_email)); 

        if($form_count_invalid > 0 || $form_count_empty > 0 || $form_count_valid > 0 || $form_count_email > 0){
            $form_status = 'invalid';

        }elseif($form_count_invalid == 0 && $form_count_empty == 0 && $form_count_valid == 0  && $form_count_email == 0 && $form_count_exists >= 1){
            $form_status = 'valid';

            //header('Location: sessionstart.php'); 
            // Session eröffnen und Daten (id, login datum und zeit) an Service weiterleiten
            session_start();
            $_SESSION['clt_id'] = $clt_id;

            // Schleife durch Spaltenbezeichnungen für Kunden ID ausser für Adresszeile2 -> hochzählen wenn leer
            $clientdata .= $clt_id;
            $clt_id_login_query = mysqli_query($connect,  $clientdata);
            $clt_id_fetch = mysqli_fetch_assoc($clt_id_login_query);

            $clt_empty_counter = 0;

            $clt_empty_query = mysqli_query($connect, $clt_empty); 
            while (($clt_empty_reply = mysqli_fetch_assoc($clt_empty_query)) !== null) { 
                $clt_currentvar = $clt_empty_reply['clt_alert_varname'];
                $$clt_currentvar = '';
                if($$clt_currentvar !== 'adr_line_2'){
                    if($clt_id_fetch[$$clt_currentvar] == null || $clt_id_fetch[$$clt_currentvar] == ''){
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
        }    

mysqli_close($connect);      
}




    // Top HTML, Header und CSS einfügen
    include('top.php'); 
    include('header.php'); 

?>


<!-- LOGIN / REGISTER FORMULAR -->
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">

    <h1 align=center >
        <a href="<?php echo $login_path ?>" style="color: #000000">Login</a>
        |
        <a href="<?php echo $signup_path ?>" style="text-decoration: none"><font color="#c0c0c0">Sign Up</font> </a>
      


    </h1>
    
    <table align=center>
        <section id="section_input">
            <!-- Email Row -->
            <tr>
                <td style = "width:40%" align="right">
                    <label for="clt_email">Email Address*</label>  
                </td>
                <td>
                    <!-- Email Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="text" 
                        id="clt_email" 
                        name="clt_email" 
                        <?php echo 'maxlength ="'.$clt_email_length.'"'; ?> 
                        <?php 
                            if($form_status == 'invalid' || $form_status == 'valid'){
                                    echo 'value="'.htmlspecialchars($clt_email, ENT_QUOTES).'"';  
                            }elseif(isset($_COOKIE["signup_email"])){
                                        echo 'value="'.htmlspecialchars($_COOKIE["signup_email"], ENT_QUOTES).'"';  
                            }elseif($form_status == 'none'){
                                echo 'value=""';
                            }
                        ?> 
                        placeholder="Please type email address."
                            <?php
                                if ($clt_email_['alert_status'] == 'empty' || $clt_email_['alert_status'] == 'invalid' || $clt_email_['alert_status'] == 'valid'){
                                    echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                            ?>>
                </td>
                <!-- Email Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                    <img class="tooltip" src=
                                <?php   if ($clt_email_['alert_status'] == 'invalid' || $clt_email_['alert_status'] == 'valid'){
                                            echo '"images/info_icon_red.png"';
                                        // Hier keine grüne Checkmark, sonst könnten Nicht-Kunden durch testen von Emails schauen wer Kunde ist
                                        // Können die zwar bei SIgnUp auch, aber dort lässt es sich leider nicht vermeiden und man muss erst über info button mouseovern um zu sehen dass email adresse existiert
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>    
                                        width ="40%" height="auto" alt="info">  
                            <div class="tooltiptext">
                            <span>Allowed: valid email adresses</span>
                        </div>
                    </div>
                </td> 
            </tr>

            <!-- Password Row -->
            <tr>
                <td style = "width:40%" align="right">
                    <label for="clt_email">Password*</label>  
                </td>
                <td>
                    <!-- Password Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="password" 
                        id="clt_password" 
                        name="clt_password" 
                        <?php echo 'maxlength ="'.$clt_password_length.'"'; ?> 
                        <?php 
                            echo 'value=""';
                        ?> 
                        placeholder="Please type password."
                        <?php
                            if ($clt_password_['alert_status'] == 'empty' || $clt_password_['alert_status'] == 'invalid' || $clt_password_['alert_status'] == 'email' || $clt_password_['alert_status'] == 'valid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Password Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                    <img class="tooltip" src=
                    <?php   if ($clt_password_['alert_status'] == 'invalid' || $clt_password_['alert_status'] == 'valid' || $clt_password_['alert_status'] == 'email'){
                                            echo '"images/info_icon_red.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>    
                                        width ="40%" height="auto" alt="info"> 
                            <div class="tooltiptext">
                            <span>Allowed: valid email adresses</span>
                        </div>
                    </div>
                </td> 
            </tr>

        

        </section>

            <tr>
                <td>
                <!-- Save Button -->    
                <input type="hidden" name="clt_submit" value="">
                </td>
                <td align=right>
                    <section id="section_save">
                        <button 
                            class="form_input form_button" 
                            type="submit" 
                            name="clt_submit" 
                            value="clt_submit"
                            >SUBMIT
                        </button>
                    </section>
                </td>
            </tr>
            <tr>
                <td>          
                    <td style ="
                        text-align: left;
                        font-family: Arial;
                        font-size: 12.5px;">
                    </td>
                </td>
            </tr>
            <tr>
                <td>          
                    <td style ="
                        text-align: left;
                        font-family: Arial;
                        font-size: 12.5px;">
                        <?php 
                            if($clt_submit !== 'clt_submit'){
                                if(isset($_COOKIE["signup_email"])){
                                    echo '<font color="green">';
                                    echo $signup_conf_line1;
                                    echo $signup_conf_line2;
                                    echo '</font>';
                                }
                            }
                        ?>
                    </td>
                </td>
            </tr>
</table>

</form>



<?php

    mysqli_close($connect);

?>


<?php 
    // Bottom HTML einfügen
    include('bottom.php'); 
?>

