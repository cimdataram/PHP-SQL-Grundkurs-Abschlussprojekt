<?php

    /*
        Abstrahierungen:
            _ am Ende einer Variablen = Statische Variable mit dynamischen Pendant
            _ am Anfang einer Funktion = eigene Funktion

            clt = client
            cnt = country


        HTML Auslagerungen: 
                header.php      = Website-Header
                top.php         = obligatorische HTML Top Befehle (DOCTYPE bis Body) incl CSS
                bottom.php      = Body + HTML Closing Befehle


        SQL Auslagerungen:
                sql.php         = mysqli_connect UND (fast) alle SQL Queries (aus Zeitgründen nicht alle)


        Auslagerung Funktionen:
                functions.php   =   INPUT POST mit substr und trim
                                    Zeichenreglement von Company Data
                                    Validitätsprüfungen von Formulardaten
                                    Vorhandenprüfung von Formulardaten
                                    Doopeltprüfung von Formulardaten
    */


    // hilfreicher link für Formularprüfung: 
    // https://www.phpjabbers.com/php-validation-and-verification-php27.html
    // https://www.php.net/manual/en/function.htmlspecialchars.php

    // hilfreiche links zu variablen Funktionen:
    // https://www.tutorialspoint.com/php-variable-functions
    // https://www.php.net/manual/en/functions.variable-functions.php

    // hilfreicher link zu variablen Abruf von Funktionen (callable / variable containing a function)
    // https://www.php.net/manual/en/function.is-callable.php

    // Send data from page to page without session or cookie:
    // https://viniciusmuniz.com/en/send-post-request-without-form/

    // hilfreiche Links zu ESCAPE / LINK / ENTSCHÄRFEN etc:
    // https://www.databasestar.com/sql-escape-single-quote/
    // https://qastack.com.de/programming/712580/list-of-special-characters-for-sql-like-clause


    // hilfreicher zu EXCEPT
    // https://www.tutorialspoint.com/sql/sql-except-clause.htm

    // hilreiche links zu SQL Injection
    // https://www.geeksforgeeks.org/how-to-prevent-sql-injection-in-php/
    // https://www.php.net/manual/en/security.database.sql-injection.php
    // https://www.acunetix.com/websitesecurity/sql-injection2/
    // https://www.greycampus.com/opencampus/ethical-hacking/types-of-sql-injection

    // Für GET keine encryption erforderlich, da in keinem Script GET verwendet wird

    // hilfreiche links zu htmlspecialchars:
    // https://www.tutorialspoint.com/htmlspecialchars-function-in-php

    // hilreiche links zu PHP security:
    // https://www.cloudways.com/blog/php-security/



    /* Difference between mysqli_espace_string VS mysqli_real_escape_string:
    ////////////////////////////////////////////////////////////////////////////
    The difference is that mysql_escape_string just treats the string as raw bytes, 
    and adds escaping where it believes it's appropriate. 
    mysql_real_escape_string, on the other hand, uses the information 
    about the character set used for the MySQL connection. 
    This means the string is escaped while treating multi-byte characters properly; 
    i.e., it won't insert escaping characters in the middle of a character. 
    This is why you need a connection for mysql_real_escape_string; 
    It's necessary in order to know how the string should be treated.
    */
    
    // TO IMPLEMENT:
    // mysqli_close($conn);
    // <?php echo htmlspecialchars($titel, ENT_QUOTES);
    // mysqli_free_result($result);
    // LIKE!



    // Deprecated Features in PHP:
    // https://www.php.net/manual/en/migration74.deprecated.php


/*







*/





?>

<?php
?>