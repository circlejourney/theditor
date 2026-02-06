<?php
    $settings = parse_ini_file(__DIR__."/.env");
    $lastUpdate = (int)$settings["lastUpdate"]; // Change latest update date to make the popup appear.
    $latestBuild = $settings["latestBuild"]; // Set this to the latest build directory to select the directory for source files
?>