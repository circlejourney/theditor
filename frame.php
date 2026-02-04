<?php
    extract(parse_ini_file(__DIR__."/.env"));
    include "build_$latestBuild/frame.php";
?>