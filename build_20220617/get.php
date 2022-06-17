<?php 
    $profileCode = $_GET["profileCode"];
    $getMeta = $_GET["getMeta"];
    if($profileCode != "") {
        ob_start();
        include("https://toyhou.se/".$profileCode);
        $page = ob_get_clean();
        
        
        if(strpos($page, "We can't find that page") !== false || strpos($page, "Invalid character selected")!== false || strpos($page, "Invalid user selected") !== false) {
        
            echo "<div><div class='user-content thcj-warn'>Private or invalid profile.</div></div>";
            
        } else if(strpos($page, "allow-thcj-import") === false && $getMeta == "false") {
        
            echo "<div><div class='user-content thcj-warn'>Security alert: You are attempting to import a profile that has not been set to allow code import. To allow code import, paste the line <code>&lt;span id='allow-thcj-import'>&lt;/span></code> anywhere in your code.</div></div>";
            
           } else {
            
            if($getMeta == "false") {
                $config = array(
                    'indent' => true,
                    'output-xhtml' => true,
                    'drop-empty-elements' => false,
                    'wrap' => 0
                );
                       
                $tidy = new tidy;
                $tidy -> parseString($page, $config, 'utf8');
                $tidy -> cleanRepair();
                $tidyString = tidy_get_output($tidy);
                $tidyString = preg_replace('/[^\S\t\n\r]{16}([^\s])/', "$1", $tidyString);
                echo $tidyString;
            } else {
                echo $page;
            }
            
        }
    } else {
        echo "<div><div class='user-content thcj-warn'>Private or invalid profile.</div></div>";
        } 
?>