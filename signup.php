<?php 

    // Konstanten (SQL-Queries) aus SQL Skript erkennen und nutzbar machen
    require 'sql.php';

    // Funktionen aus Skript mit Funktionen erkennen und nutzbar machen
    require 'functions.php';

    // Maxchars per Column
    ///// Später nochmal versuchen diese Werte aus Heidi zu fetchen
    
    $clt_submit_length = 10;
    $clt_email_length = 35;
    $clt_password_length = 40;
    $clt_passconf_length = $clt_password_length;

    $clt_submit = _receipt('clt_submit', $clt_submit_length);
    $clt_email = _receipt('clt_email', $clt_email_length);
    $clt_password = _receipt('clt_password', $clt_password_length);
    $clt_passconf = _receipt('clt_passconf', $clt_passconf_length);

    $clt_submit_ = '';
    $clt_email_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_password_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_passconf_ = ['alert_status' => '', 'alert_text' => ''];

    // Wurden Formulardaten gesendet?
    if($clt_submit !== 'clt_submit'){
        $form_status = 'none';

    }elseif($clt_submit == 'clt_submit'){ 

        // Leer Prüfung Email
        if($clt_email == null || $clt_email == ''){
            $clt_email_ = ['alert_status' => 'empty', 'alert_text' => 'Please type email address'];
        // Invalid Prüfung Email
        }elseif(_signup_email($clt_email) == 'invalid'){
            $clt_email_ = ['alert_status' => 'invalid', 'alert_text' => 'Please type valid email address'];
        }elseif(_signup_email($clt_email) == 'exists'){
            $clt_email_ = ['alert_status' => 'exists', 'alert_text' => 'Email address already exists'];
        }else{
            $clt_email_ = ['alert_status' => 'valid', 'alert_text' => '']; 
        }

        // Leer Prüfung Password
        if($clt_password == null || $clt_password == ''){
            $clt_password_ = ['alert_status' => 'empty', 'alert_text' => 'Please type password'];
        // Invalid Prüfung Password
        }elseif(_signup_password($clt_password) == 'invalid'){
            $clt_password_ = ['alert_status' => 'invalid', 'alert_text' => 'Please type valid password'];
        }else{
            $clt_password_ = ['alert_status' => 'valid', 'alert_text' => '']; 
        }

        // Leer Prüfung Password Confirm
        if($clt_passconf == null || $clt_passconf == ''){
            $clt_passconf_ = ['alert_status' => 'empty', 'alert_text' => 'Please confirm password'];
        // Invalid Prüfung Password
        }elseif(_signup_passconf($clt_password, $clt_passconf) == 'invalid'){
            $clt_passconf_ = ['alert_status' => 'invalid', 'alert_text' => 'Passwords does not match'];
        }else{
            $clt_passconf_ = ['alert_status' => 'valid', 'alert_text' => '']; 
        }

        $form_conc_invalid =    (
                                    $clt_email_['alert_status'].'/'. 
                                    $clt_password_['alert_status'].'/'.
                                    $clt_passconf_['alert_status'].'/'.
                                    ''
                                );

        $form_conc_empty =      (
                                    $clt_email_['alert_status'].'/'. 
                                    $clt_password_['alert_status'].'/'.
                                    $clt_passconf_['alert_status'].'/'.
                                    ''
                                );

        $form_conc_exists =      (
                                    $clt_email_['alert_status'].'/'. 
                                    ''
                                );
        
        $form_count_invalid = substr_count($form_conc_invalid, 'invalid', 0, strlen($form_conc_invalid));
        $form_count_empty = substr_count($form_conc_empty, 'empty', 0, strlen($form_conc_empty));    
        $form_count_exists = substr_count($form_conc_exists, 'exists', 0, strlen($form_conc_exists));   
        
        if($form_count_invalid > 0 || $form_count_empty > 0 || $form_count_exists > 0){
            $form_status = 'invalid';

        }elseif($form_count_invalid == 0 && $form_count_empty == 0 && $form_count_exists == 0){
            $form_status = 'valid';

            /////////// INSERT FORMULAR DATEN WENN GANZES FORMULAR VALIDE ///////////
            if($form_status == 'valid'){
                $clt_email_insert = mysqli_escape_string($connect, $clt_email);
                $clt_password_insert = mysqli_escape_string($connect, $clt_password);

                $password_hash = password_hash($clt_password, PASSWORD_DEFAULT);
                
                // Insert ID, Email,  Password
                $clt_insert = 
                "INSERT INTO client
                (clt_email, clt_pw)
                VALUES 
                ('$clt_email', '$password_hash')";

                mysqli_query($connect, $clt_insert);

                // Email über Cookie an Login übergeben
                

                

                $signup_conf_line1 = 'Thanks! You have been registered. <br>';
                $signup_conf_line2 = 'You can now log in.';
                // Click  <a href="'.$login_path.'">here</a>';

                setcookie('signup_conf_line1', $signup_conf_line1, time()+60*60*24);
                setcookie('signup_conf_line2', $signup_conf_line2, time()+60*60*24);

                setcookie('signup_email', $clt_email, time()+60*60*24);

                mysqli_close($connect);  

                // automatisch zu login navigieren
                header('Location: login.php');

            }
        }
    }

    // echo 'form '.$form_status;
  


    // Top HTML, Header und CSS einfügen
    include('top.php'); 
    include('header.php'); 
?>


<!-- LOGIN / REGISTER FORMULAR -->
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">

    <h1 align=center >
        <a href="<?php echo $login_path; ?>" style="text-decoration: none"><font color="#c0c0c0">Login</font> </a>
        |
        <a href="<?php echo $signup_path; ?>" style="color: #000000">Sign Up</a>
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
                            }elseif($form_status == 'none'){
                                echo 'value=""';
                            }
                        ?> 
                        placeholder="Please type email address."
                        <?php
                            if ($clt_email_['alert_status'] == 'empty' || $clt_email_['alert_status'] == 'invalid' || $clt_email_['alert_status'] == 'exists'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Email Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                    <img class="tooltip" src=
                                <?php   if ($clt_email_['alert_status'] == 'invalid' || $clt_email_['alert_status'] == 'exists'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_email_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
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
                    <label for="clt_password">Password*</label>  
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
                            if ($clt_password_['alert_status'] == 'empty' || $clt_password_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Password Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                        <img class="tooltip" src=
                                    <?php   if ($clt_password_['alert_status'] == 'invalid'){
                                                echo '"images/info_icon_red.png"';
                                            }elseif($clt_password_['alert_status'] == 'valid'){
                                                echo '"images/check_icon.png"';
                                            }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                            ?>  
                                width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: valid email adresses</span>
                        </div>
                    </div>
                </td> 
            </tr>

            <!-- Password Confirm Row -->
            <tr>
                <td style = "width:40%" align="right">
                    <label for="clt_passconf">Confirm Password*</label>  
                </td>
                <td>
                    <!-- Password Confirm Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="password" 
                        id="clt_passconf" 
                        name="clt_passconf" 
                        <?php echo 'maxlength ="'.$clt_passconf_length.'"'; ?> 
                        <?php 
                                echo 'value=""';
                        ?> 
                        placeholder="Please confirm password."
                        <?php
                            if ($clt_passconf_['alert_status'] == 'empty' || $clt_passconf_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Password Confirm Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                        <img class="tooltip" src=
                                    <?php   if ($clt_passconf_['alert_status'] == 'invalid'){
                                                echo '"images/info_icon_red.png"';
                                            }elseif($clt_passconf_['alert_status'] == 'valid'){
                                                echo '"images/check_icon.png"';
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
                <input type="hidden" name="clt_submit" value="clt_submit">
                </td>
                <td align=right>
                    <section id="section_submit">
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
                            if($form_status == 'invalid'){
                                echo '<font color="red">'; 
                                echo 'Sign Up failed. <br>';
                                echo 'Some required fields were invalid or empty.<br>';
                                echo '</font>';
                            }elseif($form_status == 'none'){
                                echo '';
                            }
                        ?>
                    </td>
                </td>
            </tr>
</table>

</form>






<?php 
    // Bottom HTML einfügen
    include('bottom.php'); 
?>