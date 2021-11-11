<?php 
    $profileCode = $_GET["profileCode"];
    $getMeta = $_GET["getMeta"];
    if($profileCode != "") {
        ob_start();
        include("https://toyhou.se/".$profileCode);
        $page = ob_get_clean();
        
        if(strpos($page, "Invalid character selected") === false && strpos($page, "Invalid user selected") === false && strpos($page, "We can't find that page") === false) {
            
            if($getMeta == "false") {
                $config = array('indent' => true, 'output-xhtml' => true, 'wrap' => 0);
                       
                $tidy = new tidy;
                $tidy -> parseString($page, $config, 'utf8');
                $tidy -> cleanRepair();
                $tidyString = tidy_get_output($tidy);
                $tidyString = preg_replace('/[^\S\t\n\r]{16}([^\s])/', "$1", $tidyString);
                echo $tidyString;
            } else {
                echo $page;
            }
            
        } else {
            echo "";
        }
    } else {
        echo "";
    }
?>