<?php 
    header("Content-type: text/html");

    function sanitise($string) {
        return preg_replace("/\/\\\/", '', $string);
    }


    $profilePath = $_POST["profilePath"] ?? $_GET["profilePath"] ?? false;
    $profilePath = sanitise($profilePath);
    $getMeta = $_POST["getMeta"] ?? $_GET["getMeta"] ?? false;
    $profile = "https://toyhou.se/$profilePath";
    $cookie = getcwd() . DIRECTORY_SEPARATOR . "cookie.txt";
    if($ischaracter = preg_match("/[0-9]+/", $profilePath)) $profile = "$profile.";
    
    ob_start();
    require("auth.php");
    $body = ob_get_clean();
    
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
        
    echo $body;
?>