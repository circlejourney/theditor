<?php
    /* This PHP programme handles the basic verification flow in the absence of a full Toyhouse API.
    *   1. Log into the (to view guest blocked profiles)
    *   2. Verify that the profile is valid and not blank.
    *   3. Verify that the user / character has the appropriate permissions set (using the allow-thcj-import string).
    *   4. OR verify that the user has global import permissions allowed (using allow-thcj-import-all string).
    *   5. If all checks are passed, fetch the full HTML contents of the profile page, including site meta, and print them to the browser.
    */
    require_once("../phpQuery/phpQuery.php");
    $homepage = "https://toyhou.se";
    $loginendpoint = "https://toyhou.se/~account/login";
    $settings = parse_ini_file("../../settings.conf");
    $username = $settings["username"];
    $password = $settings["password"];

    // Login CSRF handshake
    $curlrequest = curl_init();
    curl_setopt($curlrequest, CURLOPT_URL, $loginendpoint);
    curl_setopt($curlrequest, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($curlrequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($curlrequest, CURLOPT_RETURNTRANSFER, 1);
    $csrfresponse = curl_exec($curlrequest);
    phpQuery::newDocumentHTML($csrfresponse);
    $authed = pq("meta[name='csrf-token']")->count() === 0;
    
    if(!$authed) {
        error_log("Application currently not logged in. Logging in...");
        $token = pq("meta[name='csrf-token']")->attr("content");

        // Login post request
        $loginheaders = array(
            "username" => $username,
            "password" => $password,
            "_token" => $token
        );
        curl_setopt($curlrequest, CURLOPT_URL, $loginendpoint);
        curl_setopt($curlrequest, CURLOPT_POST, 1);
        curl_setopt($curlrequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlrequest, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curlrequest, CURLOPT_POSTFIELDS, http_build_query($loginheaders));
        $loginresponse = curl_exec($curlrequest);
        error_log($loginresponse);
    }

    // Find user and accept user warning if it exists
    if($ischaracter) {
        error_log("Finding owner...");
        curl_setopt($curlrequest, CURLOPT_URL, $profile);
        curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($curlrequest, CURLOPT_POST, 0);
        curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
        $profileresponse = curl_exec($curlrequest);
        preg_match("/\"display-user-username\">(.*?)<\/span>/", $profileresponse, $ownermatches);
        $owner = $ownermatches[1];
    } else $owner = $profilePath;
    $ownerprofile = "https://toyhou.se/$owner";
    
    error_log("Getting user profile...");
    curl_setopt($curlrequest, CURLOPT_URL, $ownerprofile);
    curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlrequest, CURLOPT_FOLLOWLOCATION, 0);

    $haswarning = preg_match("/name=\"user\"\stype=\"hidden\"\svalue=\"([0-9]+)\"/", $csrfresponse, $usermatches);
    if($haswarning) {
        error_log("Accepting user warning...");
        $user = $usermatches[1];
        $token = pq("meta[name='csrf-token']")->attr("content");

        $postquery = array(
            'user'=>$user,
            '_token'=>$token
        );
        curl_setopt($curlrequest, CURLOPT_URL, "https://toyhou.se/~account/warnings/accept");
        curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlrequest, CURLOPT_POST, 1);
        curl_setopt($curlrequest, CURLOPT_POSTFIELDS, http_build_query($postquery));
        $warningresponse = curl_exec($curlrequest);
    }
    
    // Check user profile for global user allow string
    curl_setopt($curlrequest, CURLOPT_URL, $ownerprofile);
    curl_setopt($curlrequest, CURLOPT_POST, 0);
    $profileresponse = curl_exec($curlrequest);
    $userglobalpermit = strpos($profileresponse, "allow-thcj-import-all") !== false;

    // Accept character warning if character
    if($ischaracter){
        error_log("Getting character's profile...");
        curl_setopt($curlrequest, CURLOPT_URL, $profile);
        curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($curlrequest, CURLOPT_POST, 0);
        curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
        $charwarningresponse = curl_exec($curlrequest);
        phpQuery::newDocumentHTML($charwarningresponse);
        $hascharwarning = pq("input[name='character']")->count() > 0;

        if($hascharwarning) {
            error_log("Accepting $profilePath's warning...");
            $user = pq("input[name='user']")->attr("value");
            $character = pq("input[name='character']")->attr("value");
            $token = pq("meta[name='csrf-token']")->attr("content");

            $postquery = array(
                '_token'=>$token,
                'user'=>$user,
                'character'=>$character
            );

            curl_setopt($curlrequest, CURLOPT_URL, "https://toyhou.se/~account/warnings/accept");
            curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curlrequest, CURLOPT_POST, 1);
            curl_setopt($curlrequest, CURLOPT_POSTFIELDS, http_build_query($postquery));
            $charwarningresponse = curl_exec($curlrequest);
        }
    
        // Finally, get the profile of interest (if character?)
        curl_setopt($curlrequest, CURLOPT_URL, $profile);
        curl_setopt($curlrequest, CURLOPT_POST, 0);
        $profileresponse = curl_exec($curlrequest); // Override profileresponse with the character's profile
    }
    
    if(strpos($profileresponse, "user-content") === false) {
        echo "<div><div class='user-content thcj-warn'>I couldn't find anything to import. The profile may be private, empty or nonexistent.</div></div>";
        die();

    } else if(strpos($profileresponse, "allow-thcj-import") === false && !$userglobalpermit) {
        echo "<div><div class='user-content thcj-warn'>Security alert: You are attempting to import a profile that has not been set to allow code import. To allow code import, paste the line <code>&lt;span id='allow-thcj-import'>&lt;/span></code> anywhere in your code. Alternatively, paste the line <code>&lt;span id='allow-thcj-import-all'>&lt;/span></code> in your <b>user profile</b> to make <i>all</i> your codes importable.</div></div>";
        die();
    } else {
        error_log("$profilePath: This profile has been set to allow import. Proceeding...");
        echo $profileresponse;
    }
    