<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>CSV Upload Form</title>
    <style>
        input, textarea{width:100%}
        textarea{height:150px}
    </style>
</head>
<body>
    <!-- Formular zum Hochladen einer CSV-Datei und Eingabe eines Passworts -->
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="campaign" placeholder="Kampagne für alle"/>
        <input type="text" name="url" placeholder="fake-formular-url für alle"/>
        <textarea name="dataProtectionText">Datenschutzerklärung (25712)</textarea>
        <textarea name="dataProtectionHint">Datenschutzhinweis (25711)</textarea>
        <!-- Datei-Upload-Feld für die CSV-Datei -->
        <input type="file" name="csv" value="" />
        <!-- Passwort-Eingabefeld -->
        <input type="password" name="password" />
        <!-- Absende-Button -->
        <input type="submit" name="submit" value="Save" />
    </form>

    <?php
    // Überprüfen, ob eine Datei ohne Fehler hochgeladen wurde und das Passwort korrekt ist
    if ($_FILES['csv']['error'] == 0 && $_POST["password"] == "RitzBitzStart#1!") {

        // Dateiinformationen abrufen
        $name = $_FILES['csv']['name'];
        // Dateiendung extrahieren und in Kleinbuchstaben umwandeln
        $filenameparts = explode('.', $_FILES['csv']['name']);
        $ext = strtolower(end($filenameparts));
        $type = $_FILES['csv']['type'];
        $tmpName = $_FILES['csv']['tmp_name'];

        // URL der API, an die die Daten gesendet werden sollen
        $url = "https://nutzer.zd.aok.de/web/api/v1/ewe/agreement.json";

        // Aufbau der Parameter für den API-Request
        $bodyParams = array();
        $bodyParams['campaign'] = $_POST["campaign"];
        preg_match('@^(https?://)?([^/]+)@i',
            $_POST["url"], $matches);
        $bodyParams['server'] = $matches[2];
        $bodyParams['client'] = "5";
        preg_match('@^(https?://)?(.+)@i',
            $_POST["url"], $matches);
        $bodyParams['url'] = $matches[2];

        // Datenschutzerklärungstexte festlegen
        $bodyParams['terms']['dataProtectionText'] = trim($_POST["dataProtectionText"]);
        $bodyParams['terms']['dataProtectionHint'] = trim($_POST["dataProtectionHint"]);

        // Überprüfen, ob die hochgeladene Datei eine CSV-Datei ist
        if ($ext === 'csv') {
            // Öffnen der CSV-Datei im Lesemodus
            if (($handle = fopen($tmpName, 'r')) !== FALSE) {
                // Maximale Ausführungszeit des Skripts aufheben, um große Dateien verarbeiten zu können
                set_time_limit(0);

                $ic = 0;          // Zeilenzähler
                $ic_max = 2000;   // Maximale Anzahl der zu verarbeitenden Zeilen

                // Schleife durch jede Zeile der CSV-Datei
                while (!feof($handle) && ++$ic <= $ic_max) {
                    // Lesen einer Zeile aus der Datei
                    $buffer = fgets($handle, 4096);
                    // Konvertieren der Zeichenkodierung und Parsen der CSV-Zeile
                    $person = str_getcsv(mb_convert_encoding($buffer, 'UTF-8', 'UTF-16'), ";", "\"", "\\");

                    // Überspringen der ersten Zeile oder leere Einträge
                    if (!isset($person[0]) || $person[0] == "Anrede") continue;

                    $ewe_permission = str_contains(strtolower($person[14]), "ja");

                    // Initialisieren des EWE-Arrays mit Basisinformationen
                    $ewe = array();
                    $ewe['campaign'] = $bodyParams['campaign'];
                    $ewe['server'] = $bodyParams['server'];
                    $ewe['client'] = $bodyParams['client'];
                    $ewe['url'] = $bodyParams['url'];
                    $ewe['isActive'] = $ewe_permission;

                    // Zuweisen der individuellen Daten aus der CSV
                    $ewe['individual']['firstName'] = $person[1];
                    $ewe['individual']['lastName'] = $person[2];

                    // Formatieren des Geburtsdatums von "TT.MM.JJJJ" zu "JJJJ-MM-TT"
                    $ewe['individual']['dob'] = preg_replace("@(\d+)\.(\d+)\.(\d+)@", "$3-$2-$1", $person[7]);

                    // Bestimmen des Geschlechts basierend auf der Anrede
                    $ewe['individual']['gender'] = match ($person[0]) {
                        "Herr", "m", "male" => 'male',
                        "Frau", "f", "female" => 'female',
                        "Divers", "d", "diverse" => 'diverse',
                        "Unbestimmt", "u", "undetermined" => 'undetermined',
                        default => 'unspecified',
                    };

                    $ewe['formEntryCreatedAt'] = preg_replace("@(\d+)\.(\d+)\.(\d+) ([\d:]+) Uhr@", "$3-$2-$1 $4", $person[15]);

                    // Hinzufügen der E-Mail-Adresse, falls vorhanden
                    if (!empty($person[8])) {
                        $ewe['individual']['contacts'][0]['type'] = 'email';
                        $ewe['individual']['contacts'][0]['data'] = $person[8];
                    }

                    // Hinzufügen der Telefonnummer, falls vorhanden
                    if (!empty($person[9])) {
                        $ewe['individual']['contacts'][1]['type'] = 'mobile';
                        $ewe['individual']['contacts'][1]['data'] = $person[9];
                        $ewe['permissions']['viaSms'] = $ewe_permission;

                        if($ewe_permission){
                            $ewe['doiTime']['smsDoiSend'] = $ewe['formEntryCreatedAt'];
                            $ewe['doiTime']['smsDoiConfirmed'] = $ewe['formEntryCreatedAt'];
                        }
                    }

                    // Zuweisen der Datenschutzhinweise
                    $ewe['terms']['dataProtectionText'] = $bodyParams['terms']['dataProtectionText'];
                    $ewe['terms']['dataProtectionHint'] = $bodyParams['terms']['dataProtectionHint'];

                    // Zuweisen der Adressdaten
                    $ewe['individual']['address']['zipId'] = $person[5];
                    $ewe['individual']['address']['number'] = $person[4];
                    $ewe['individual']['address']['street']['name'] = $person[3];
                    $ewe['individual']['address']['city']['name'] = $person[6];

                    // Hinzufügen der Versicherungsdaten, falls vorhanden
                    if (!empty($person[13])) {
                        $bodyParams['individual']['insurance'] = $person[13];
                    }

                    if ($person[10] == 'Ja') {
                        $bodyParams['individual']['isAokMember'] = TRUE;
                        $bodyParams['additionalInformation'] = 'Versichertennummer: '.$person[11].', Kassennummer: '.$person[12];
                    } elseif (!empty($person[11])) {
                        // Wenn die Felder direkt (ohne Checkbox AOK versichert angezeigt werden)
                        $bodyParams['additionalInformation'] = 'Versichertennummer: '.$person[11].', Kassennummer: '.$person[12];
                    }

                    // Umwandeln des EWE-Arrays in JSON
                    $eweJSON = json_encode($ewe);

                    /*
                    // Debugging: Ausgabe des EWE-Arrays und des JSON-Strings
                    echo "<pre>";
                    var_dump($ewe);
                    echo "</pre>";
                    echo "<div><pre>";
                    echo $eweJSON;
                    echo "</pre></div>";
                    */

                    // Protokollieren des JSON-Strings in das Error-Log
                    var_error_log($eweJSON);

                    // Initialisieren einer cURL-Session für den API-Request
                    $handle_curl = curl_init($url);
                    curl_setopt_array($handle_curl, [
                        CURLOPT_HEADER => false,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => [
                            'Api-Authorisation-Key: tyviMi4b8QScnd8B',
                            'Accept: application/json',
                            'Content-type: application/json'
                        ]
                    ]);

                    // Überprüfen und Setzen eines Proxy, falls vorhanden
                    var_error_log(getProxy());
                    if (getProxy() != false) {
                        $proxy = getProxy();
                        curl_setopt($handle_curl, CURLOPT_HTTPPROXYTUNNEL, 1);
                        curl_setopt($handle_curl, CURLOPT_PROXY, $proxy);
                    }

                    // Setzen der Methode auf POST
                    curl_setopt($handle_curl, CURLOPT_POST, true);

                    // Anhängen der JSON-Daten als POST-Feld
                    if (strlen($eweJSON) > 0) {
                        curl_setopt($handle_curl, CURLOPT_POSTFIELDS, $eweJSON);
                    }
                    var_error_log(curl_getinfo($handle_curl));

                    var_error_log(array($url));

                    // Ausführen des cURL-Requests
                    $result = curl_exec($handle_curl);

                    var_error_log(curl_getinfo($handle_curl));

                    // Erstellen eines Rückgabeobjekts mit HTTP-Status und Ergebnis
                    $return = new \stdClass();
                    $return->{'http_status'} = curl_getinfo($handle_curl, CURLINFO_HTTP_CODE);
                    $return->result = $result;

                    // Ausgabe des Status der aktuellen Verarbeitung
                    echo (new DateTime('now'))->format('H:i:s.v') ." --- ". curl_getinfo($handle_curl, CURLINFO_HTTP_CODE)." => Zeile ".$ic.": ".$person[0]." ".$person[2]."<br>";
                    var_error_log($result);

                    // Kurze Pause zwischen den Requests, um die Serverlast zu reduzieren
                    usleep(250000);
                }

                // Schließen der Datei
                fclose($handle);
            }
        }

    }

    /**
     * Funktion zur Ermittlung des Proxy-Servers aus den Umgebungsvariablen
     *
     * @return string|false Proxy-URL oder FALSE, wenn kein Proxy gesetzt ist
     */
    function getProxy()
    {
        $httpsProxy = false;
        $httpProxy = false;

        if (!empty($_SERVER['HTTPS'])) {
            if (empty($httpsProxy)) {
                if (!empty($_SERVER['HTTPS_PROXY'])) {
                    $httpsProxy = $_SERVER['HTTPS_PROXY'];
                    return $httpsProxy;
                }
                return false;
            }
            return $httpsProxy;
        } else {
            if (empty($httpProxy)) {
                if (!empty($_SERVER['HTTP_PROXY'])) {
                    $httpProxy = $_SERVER['HTTP_PROXY'];
                    return $httpProxy;
                }
                return false;
            }
            return $httpProxy;
        }
    }

    /**
     * Funktion zum Protokollieren von Variablen in das Error-Log
     *
     * @param mixed $object Die zu protokollierende Variable
     */
    function var_error_log($object = null)
    {
        if (is_array($object)) {
            // Wenn es sich um ein Array handelt, in JSON umwandeln
            $contents = json_encode($object, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            // Andernfalls var_dump verwenden und in einen String umwandeln
            ob_start();                    // Pufferung starten
            var_dump($object);             // Variable dumpen
            $contents = ob_get_contents(); // Inhalt aus dem Puffer holen
            ob_end_clean();                // Puffer leeren
        }
        // Inhalt ins Error-Log schreiben
        error_log($contents);
    }

    ?>
</body>
</html>
