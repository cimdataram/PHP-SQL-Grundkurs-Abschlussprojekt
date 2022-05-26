<?php 
session_start();
if(!isset($_SESSION['clt_id'])){
    header("Location: logout.php");
    die();
} 
// Oben Empfang der Kunden ID von Login, ansonsten auto-logout

// Empfang einer Alert Meldung falls auf Service-Page geklickt wurde ohne Accountdaten vervollständigt zu haben
if(isset($_SESSION['service_alert'])){      
    $service_alert = @$_SESSION['service_alert'];
}

// Kunden ID von Login in Variable setzen
$logged = @$_SESSION['clt_id'];

    // Konstanten (SQL-Queries) aus SQL Skript erkennen und nutzbar machen
    require 'sql.php';

    // Funktionen aus Skript mit Funktionen erkennen und nutzbar machen
    require 'functions.php';

    // Maxchars per Column
    ///// Später nochmal versuchen diese Werte aus Heidi zu fetchen
    $clt_save_length = 10;
    $clt_email_length = 35;
    $clt_password = '';
    $clt_phone_length = 50;
    $clt_company_length = 100;
    $clt_adr_line1_length = 80;
    $clt_adr_line2_length = 80;
    $clt_zip_length = 25;
    $clt_city_length = 50;
    $clt_country_length = 50;

    // Empfang der Formulardaten mit Kürzen und Trimmen
    // Später Variablennamen und Empfangsnamen auch aus Datenbank über Schleife fetchen (überall)
    $clt_save = _receipt('clt_save', $clt_save_length);
    $clt_email = _receipt('clt_email', $clt_email_length);
    $clt_password = '';
    $clt_phone = _receipt('clt_phone', $clt_phone_length);
    $clt_company = _receipt('clt_company', $clt_company_length);
    $clt_adr_line1 = _receipt('clt_adr_line1', $clt_adr_line1_length);
    $clt_adr_line2 = _receipt('clt_adr_line2', $clt_adr_line2_length);
    $clt_zip = _receipt('clt_zip', $clt_zip_length);
    $clt_city = _receipt('clt_city', $clt_city_length);
    $clt_country = _receipt('clt_country', $clt_country_length);

    // Statisches Pendant für dynamische Variablen zur bedingten Formatierung und Prüfung des Formulars
    $clt_save_ = '';
    $clt_email_ = ['alert_status' => '', 'alert_text' => ''];
    // $clt_password_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_phone_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_company_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_adr_line1_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_adr_line2_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_zip_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_city_ = ['alert_status' => '', 'alert_text' => ''];
    $clt_country_ = ['alert_status' => '', 'alert_text' => ''];

    // Wurden Formulardaten gesendet?
    if($clt_save !== 'clt_save'){
        $form_status = 'none';

    }elseif($clt_save == 'clt_save'){    

        // Verbindung zur Tabelle mit Fehlermeldungen zu Variablenmamen
        $clt_empty_query = mysqli_query($connect, $clt_empty); 

        // Schleife zur Prüfung was leer ist
        while (($clt_empty_reply = mysqli_fetch_assoc($clt_empty_query)) !== null) { 

            // Definition des Primärschlüssels(Variablenname) für dynamische Variable
            $clt_currentvar = $clt_empty_reply['clt_alert_varname'];

            // Bezeichnung für dynamische Variable für statischen Abruf des Alert-Status      
            $clt_current_element = $clt_currentvar.'_';

            // Prüfung des Empfangenen Inhaltes ob Leer (über dynmische Variable)
            if($$clt_currentvar == null || $$clt_currentvar == '' || $$clt_currentvar == 'Please select country'){
                            
                // Dynamische Elementsteuerungsvariable als assoziatives Array für Alert-Status und Alert Message
                $$clt_current_element = ['alert_status' => 'empty', 'alert_text' => $clt_empty_reply['clt_empty']];

            // Wenn nicht empty dann Prüfung ob invalid
            }else{
                        // Variable Funktionsnamen für individuellen Prüfung des jeweiligen Formularfeldes erstellen
                        $clt_form_control = '_'.$clt_currentvar;

                        // Prüfung der jeweiligen Eingabe in der jeweiligen Funktion
                        if($clt_form_control($$clt_currentvar) == 'invalid'){    

                            // Dynamische Elementsteuerungsvariable als assoziatives Array
                            $$clt_current_element = ['alert_status' => 'invalid', 'alert_text' => $clt_empty_reply['clt_invalid']];                                
                        
                        // Wenn nicht invalid dann valid
                        }else{$$clt_current_element = ['alert_status' => 'valid', 'alert_text' => '']; 
                        }
            }            
        } 
        // Cache leeren
        mysqli_free_result($clt_empty_query);

        // Alerts zu Formularfeldern konkatenieren, für Gesamt prüfung des Formulars
        $form_conc_invalid =    (
                                    $clt_email_['alert_status'].'/'. 
                                    $clt_phone_['alert_status'].'/'.
                                    $clt_company_['alert_status'].'/'.
                                    $clt_adr_line1_['alert_status'].'/'.
                                    $clt_adr_line2_['alert_status'].'/'.
                                    $clt_zip_['alert_status'].'/'.
                                    $clt_city_['alert_status'].'/'.
                                    $clt_country_['alert_status'].'/'.
                                    ''
                                );

        $form_conc_empty =      (
                                    $clt_email_['alert_status'].'/'. 
                                    $clt_phone_['alert_status'].'/'.
                                    $clt_company_['alert_status'].'/'.
                                    $clt_adr_line1_['alert_status'].'/'.
                                    // adr_line2 darf empty sein (optional)
                                    $clt_zip_['alert_status'].'/'.
                                    $clt_city_['alert_status'].'/'.
                                    $clt_country_['alert_status'].'/'.
                                    ''
                                );
        
        // Alerts zählen                        
        $form_count_invalid = substr_count($form_conc_invalid, 'invalid', 0, strlen($form_conc_invalid));
        $form_count_empty = substr_count($form_conc_empty, 'empty', 0, strlen($form_conc_empty));
        
        // Alerts auswerten und interpretieren
        if($form_count_invalid > 0 || $form_count_empty > 0){
            $form_status = 'invalid';
        }elseif($form_count_invalid == 0 && $form_count_empty == 0){
            $form_status = 'valid';

            /////////// UPDATE FORMULAR DATEN WENN GANZES FORMULAR VALIDE ///////////
            if($form_status == 'valid'){
        
                # Bestimmte Sonderzeichen für SQL entschärfen
                // eigentlich müssen möglichst alle erlaubten Sonderzeichen entschärft werden
                // Später als Schleife umsetzen und Passwort aus Schleife ausgliedern
                $clt_email_update = mysqli_real_escape_string($connect, $clt_email);
                $clt_phone_update = mysqli_real_escape_string($connect, $clt_phone);
                $clt_company_update = mysqli_real_escape_string($connect, $clt_company);
                $clt_adr_line1_update = mysqli_real_escape_string($connect, $clt_adr_line1);
                $clt_adr_line2_update = mysqli_real_escape_string($connect, $clt_adr_line2);
                $clt_zip_update = mysqli_real_escape_string($connect, $clt_zip);
                $clt_city_update = mysqli_real_escape_string($connect, $clt_city);
                $clt_country_update = mysqli_real_escape_string($connect, $clt_country);

                // Datenbank Update Code
                $clt_update1 = 
                    "UPDATE client 
                    SET clt_email = '$clt_email_update',
                    clt_phone = '$clt_phone_update',
                    clt_company = '$clt_company_update',
                    clt_adr_line1 = '$clt_adr_line1_update',
                    clt_adr_line2 = '$clt_adr_line2_update',
                    clt_zip = '$clt_zip_update',
                    clt_city = '$clt_city_update'
                    WHERE clt_id = '$logged'";

                // Datenbank updaten oder abbrechen wenn SQL Injection
                $clt_update1_query =  mysqli_query($connect, $clt_update1) or die(mysqli_error($connect));

                // Country Update Code (separat, da zwei Tabellen und JOIN erforderlich)
                $clt_update2 = 
                    "UPDATE client
                    JOIN country
                    ON country.cnt_name = '$clt_country_update'
                    SET client.clt_country = country.cnt_id
                    WHERE clt_id = '$logged'";

                // Country updaten oder abbrechen wenn SQL Injection
                $clt_update2_query = mysqli_query($connect, $clt_update2) or die(mysqli_error($connect));

                // DB Verbindung kappen
                mysqli_close($connect);
            }
        }    
    }



    // Top HTML, Header und CSS einfügen
    include('top.php'); 
    include('header.php');

?>

<script>
    // ein wenig JQuery  für komfortable Tooltiptext Darstellung :-)
    $(document).ready(function(){
        $('[data-toggle="popover"]').popover();
    });
</script>


<!-- ///////////////  KUNDEDATEN FORMULAR  /////////////// -->

<!-- Formularöffnung und Versenden über Post und Redirect auf selbe Page -->
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
    <h1 align=center >Account</h1>

    <?php
        // SQL Abfrage konkateniert mit ID des eingeloggten Kundem    
        $clientdata_ = 'SELECT * FROM client WHERE clt_id = '.$logged;
        $client_query = mysqli_query($connect, $clientdata_);
        $client_reply = mysqli_fetch_assoc($client_query);

    ?>

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
                        <?php echo 'maxlength ="'.$clt_email_length.'"' ?> 
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.htmlspecialchars($clt_email, ENT_QUOTES).'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_email'], ENT_QUOTES).'"';
                            }
                        ?> 
                        placeholder="Please type your email address."
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung
                            if ($clt_email_['alert_status'] == 'empty' || $clt_email_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Email Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Info Button Formatierung anhand der Alertmeldung
                                        if ($clt_email_['alert_status'] == 'invalid'){
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
            <tr>
            <!-- Phone Row -->
            <td style = "width:40%" align="right">
                    <label for="clt_phone">Phone*</label>
                </td>
                <td>
                    <!-- Phone Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="text" 
                        id="clt_phone" 
                        name="clt_phone" 
                        <?php echo 'maxlength ="'.$clt_phone_length.'"' ?> 
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.htmlspecialchars($clt_phone, ENT_QUOTES).'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_phone'], ENT_QUOTES).'"';
                            }
                        ?> 
                        placeholder="Please type your phone number."
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung
                            if ($clt_phone_['alert_status'] == 'empty' || $clt_phone_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Phone Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Info Button Formatierung anhand der Alertmeldung
                                        if ($clt_phone_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_phone_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>  
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: numbers with country prefix starting with 00</span>
                        </div>
                    </div>
                </td>      
            </tr>
            <!-- Company Row -->
            <tr>
            <td style = "width:40%" align="right">
                    <label for="clt_company">Company Name*</label>  
                </td>
                <td style>
                    <!-- Company Eingabefeld -->
                    <input
                        size = "40%" 
                        type="text" 
                        id="clt_company" 
                        name="clt_company" 
                        <?php echo 'maxlength ="'.$clt_company_length.'"' ?>                         
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.htmlspecialchars($clt_company, ENT_QUOTES).'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_company'], ENT_QUOTES).'"';
                            }
                        ?> 
                        placeholder="Please type the name of your company."
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung
                            if ($clt_company_['alert_status'] == 'empty' || $clt_company_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Company Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Info Button Formatierung anhand der Alertmeldung
                                        if ($clt_company_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_company_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>  
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: latin letters, numbers and not too many special characters</span>
                        </div>
                    </div>
                </td> 
            </tr>
            <!-- Address1 Row -->
            <tr>
            <td style = "width:40%" align="right">
                    <label for="clt_adr_line1">Address Line1*</label>
                </td>
                <td>
                    <!-- Address1 Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="text" 
                        id="clt_adr_line1" 
                        name="clt_adr_line1" 
                        <?php echo 'maxlength ="'.$clt_adr_line1_length.'"' ?> 
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.htmlspecialchars($clt_adr_line1, ENT_QUOTES).'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_adr_line1'], ENT_QUOTES).'"';
                            }
                        ?> 
                        placeholder="Please type your address."
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung
                            if ($clt_adr_line1_['alert_status'] == 'empty' || $clt_adr_line1_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Address1 Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Info Button Formatierung anhand der Alertmeldung
                                        if ($clt_adr_line1_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_adr_line1_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>   
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: latin letters and numbers and once - (no abbreviations)</span>
                        </div>
                    </div>
                </td> 
            </tr>
            <!-- Address2 Row -->
            <tr>
            <td style = "width:40%" align="right">
                    <label for="clt_adr_line2">Address Line2&nbsp;</label>
                </td>
                <td>
                    <!-- Address2 Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="text" 
                        id="clt_adr_line2" 
                        name="clt_adr_line2" 
                        <?php echo 'maxlength ="'.$clt_adr_line2_length.'"' ?> 
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.htmlspecialchars($clt_adr_line2, ENT_QUOTES).'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_adr_line2'], ENT_QUOTES).'"';
                            }
                        ?> 
                        placeholder="Please type your address."
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung (hier Leer kein Fehler)
                            if ($clt_adr_line2_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- Address2 Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Button Formatierung anhand der Alertmeldung
                                        if ($clt_adr_line2_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_adr_line2_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>   
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: latin letters (and numbers (and .-))/</span>
                        </div>
                    </div>
                </td> 
            </tr>
            <!-- ZIP Code Row -->
            <tr>
            <td style = "width:40%" align="right"> 
                    <label for="clt_zip">Zip Code*</label>
                </td>
                <td>
                    <!-- ZIP Code Eingabefeld -->
                    <input 
                        size = "40%" 
                        type="text" 
                        id="clt_zip" 
                        name="clt_zip" 
                        <?php echo 'maxlength ="'.$clt_zip_length.'"' ?> 
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.htmlspecialchars($clt_zip, ENT_QUOTES).'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_zip'], ENT_QUOTES).'"';
                            }
                        ?> 
                        placeholder="Please type your zip code"
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung
                            if ($clt_zip_['alert_status'] == 'empty' || $clt_zip_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- ZIP Code Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Button Formatierung anhand der Alertmeldung
                                        if ($clt_zip_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_zip_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>   
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: latin letters and/or numbers and no special characters</span>
                        </div>
                    </div>
                </td> 
            </tr>
            <!-- City Row -->
            <tr>
            <td style = "width:40%" align="right">
                    <label for="clt_city">City*</label>
                </td>
                <td>
                    <!-- City Eingabefeld -->
                        <input 
                        size = "40%" 
                        type="text" 
                        id="clt_city" 
                        name="clt_city" 
                        <?php echo 'maxlength ="'.$clt_city_length.'"' ?> 
                        <?php 
                        // Value anhand der Alertmeldung definieren Leer/None = Leer, Invalid = Übergabe
                            if($form_status == 'invalid'){
                                    echo 'value="'.$clt_city.'"';  
                            }elseif($form_status == 'valid' || $form_status == 'none'){
                                echo 'value="'.htmlspecialchars($client_reply['clt_city'], ENT_QUOTES).'"';
                            }
                        ?>  
                        placeholder="Please type your city."
                        <?php
                        // Bedingte Border Formatierung anhand der Alertmeldung
                            if ($clt_city_['alert_status'] == 'empty' || $clt_city_['alert_status'] == 'invalid'){
                                echo 'style ="border: solid 2px #ff0000; color=#ff0000"';}
                        ?>>
                </td>
                <!-- City Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Button Formatierung anhand der Alertmeldung
                                        if ($clt_city_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_city_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>   
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Allowed: only latin letters</span>
                        </div>
                    </div>
                </td> 
            </tr>
            <!-- Country Row -->
            <tr>
                <td style = "width:20%" align="right">
                    <label for="clt_country">Country*</label>
                </td>
                <td>
                   <!-- Dropdownmenü für Countries  -->
                    <select style="width:100%                         
                        <?php 
                        // Bedingte Border Formatierung anhand der Alertmeldung
                        if ($clt_country_['alert_status'] == 'empty' || $clt_country_['alert_status'] == 'invalid'){
                                echo '; border: solid 2px #ff0000; color=#ff0000';}?>"
                        id="clt_country" name="clt_country">
                        <?php
                            // Wenn Formular nicht vollständig korrekt, dann eingegeben Country Wert übergeben, sofern korrekt, sonst default value.
                            if($form_status == 'invalid'){
                                if($clt_country_['alert_status'] == 'invalid' || $clt_country_['alert_status'] == 'empty'){
                                    echo '<option selected>Please select country</option>';
                                    $country_query = mysqli_query($connect, $countries);
                                    while (($country_reply = mysqli_fetch_assoc($country_query)) !== null) {
                                        echo '<option value ="'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'">'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'</option>';
                                    }
                                }elseif($clt_country_['alert_status'] == 'valid'){
                                    $country_query = mysqli_query($connect, $countries);
                                    while (($country_reply = mysqli_fetch_assoc($country_query)) !== null) {   
                                        if ($country_reply['cnt_id'] == $clt_country){
                                            $selected = 'selected'; 
                                        }else{
                                            $selected = '';
                                        }
                                        echo '<option '.$selected.' value ="'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'">'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'</option>';
                                    }
                                }
                            // Wenn Formular vollständig korrekt, dann oben geupdateten Country Wert aus Heidi fetchen
                            }elseif($form_status == 'valid'){
                                $country_query = mysqli_query($connect, $countries);
                                while (($country_reply = mysqli_fetch_assoc($country_query)) !== null) {   
                                    if ($country_reply['cnt_id'] == $client_reply['clt_country']){
                                        $selected = 'selected'; 
                                    }else{
                                        $selected = '';
                                    }
                                    echo '<option '.$selected.' value ="'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'">'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'</option>';
                                }
                            // Beim ersten Aufrufen des Formulars, Country Wert aus Heidi fetchen, sofern eingetragen, sonst default value
                            }elseif($form_status == 'none'){    
                                if($client_reply['clt_country'] == ''){
                                echo '<option selected>Please select country</option>';
                                }
                                $country_query = mysqli_query($connect, $countries);                       
                                while (($country_reply = mysqli_fetch_assoc($country_query)) !== null) {   
                                    if ($country_reply['cnt_id'] == $client_reply['clt_country']){
                                        $selected = 'selected'; 
                                    }else{
                                        $selected = '';
                                    }
                                    echo '<option '.$selected.' value ="'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'">'.htmlspecialchars($country_reply['cnt_name'], ENT_QUOTES).'</option>';
                                }
                            }
                        ?>
                    </select>
                </td>
                <!-- Country Info Button -->
                <td width="40px" height="auto">
                    <div class="tooltip">
                            <img class="tooltip" src=
                                <?php   
                                // Bedingte Button Formatierung anhand der Alertmeldung
                                if ($clt_country_['alert_status'] == 'invalid'){
                                            echo '"images/info_icon_red.png"';
                                        }elseif($clt_country_['alert_status'] == 'valid'){
                                            echo '"images/check_icon.png"';
                                        }else{echo '"images/info_icon.png" style="opacity:0.4"';}  
                                        ?>  
                            width ="40%" height="auto" alt="info">    
                            <div class="tooltiptext">
                            <span>Select from list</span>
                        </div>
                    </div>
                </td> 
            </tr>
        </section>

            <tr>
                <td>
                <!-- Save Button -->    
                <input type="hidden" name="clt_id" value="<?php echo $logged; ?>">
                </td>
                <td align=right>
                    <section id="section_save">
                        <button 
                            class="form_input form_button" 
                            type="submit" 
                            name="clt_save" 
                            value="clt_save"
                            >SAVE
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
                        <?php 
                        // Bedingte Alerts für Gesamtformular
                            if($form_status == 'invalid'){
                                echo '<font color="red">'; 
                                echo 'Your data has not been saved. <br>';
                                echo 'Some required fields were invalid or empty.<br>';
                                echo '</font>';
                            }elseif($form_status == 'valid'){
                                echo '<font color="green">';
                                echo 'Your data has been updated. Thank You!';
                                echo '</font>';
                            }elseif($form_status == 'none'){
                                if(isset($_SESSION['service_alert'])){
                                    echo '<font color="red">'; 
                                    echo $service_alert;
                                    echo '</font>';
                                }else{
                                    echo '';
                                }
                            }
                        ?>
                    </td>
                </td>
            </tr>
</table>

</form>


<?php
// Cache leeren und Disconnecten
mysqli_free_result($client_query);
mysqli_free_result($country_query);
mysqli_close($connect);

?>

<?php 
    // Bottom HTML einfügen
    include('bottom.php'); 
?>
