<?php 
        
    header("Content-type: text/html");

    $profilePath = $_POST["profilePath"];
    $getMeta = $_POST["getMeta"];
    if($profilePath == "" || $getMeta=="") {
        echo "<div><div class='user-content thcj-warn'>Private or invalid profile.</div></div>";
        die();
    }
        
    $ch = curl_init();

    // Options
    curl_setopt($ch, CURLOPT_URL, "https://toyhou.se/".$profilePath);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Return the response instead of printing it out
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $scraped = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $body = $scraped . PHP_EOL;

    if(strpos($body, "We can't find that page") !== false || strpos($body, "Invalid character selected")!== false || strpos($body, "Invalid user selected") !== false) {
        $body = "<div><div class='user-content thcj-warn'>Private or invalid profile.</div></div>";
    } else if(strpos($body, "allow-thcj-import") === false && $getMeta == "false") {
        $body = "<div><div class='user-content thcj-warn'>Security alert: You are attempting to import a profile that has not been set to allow code import. To allow code import, paste the line <code>&lt;span id='allow-thcj-import'>&lt;/span></code> anywhere in your code.</div></div>";
    } else {
        $config = array(
            'indent' => true,
            'output-xhtml' => true,
            'drop-empty-elements' => false,
            'wrap' => 0
        );
        $tidy = new tidy;
        $tidy -> parseString($body, $config, 'utf8');
        $tidy -> cleanRepair();
        $tidyString = tidy_get_output($tidy);
        $tidyString = preg_replace('/[^\S\t\n\r]{16}([^\s])/', "$1", $tidyString);
        $body = $tidyString;
    }
        
    echo $body;
?>