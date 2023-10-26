<?php
    /* This PHP programme handles the basic verification flow in the absence of a full Toyhouse API.
    *   1. Verify that the profile is valid and not blank.
    *   2. Verify that the user / character has the appropriate permissions set (using the allow-thcj-import string).
    *   3. OR verify that the user has global import permissions allowed (using allow-thcj-import-all string).
    *   4. If all checks are passed, fetch the full HTML contents of the profile page, including site meta, and print them to the browser.
    */

    // CSRF token request
    $csrfrequest = curl_init();
    curl_setopt($csrfrequest, CURLOPT_URL, $profile);
    curl_setopt ($csrfrequest, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt ($csrfrequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt ($csrfrequest, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($csrfrequest, CURLOPT_FOLLOWLOCATION, 0);
    
    $csrfresponse = curl_exec($csrfrequest);
    curl_close($csrfrequest);
    preg_match("/<meta\sname=\"csrf-token\"\scontent=\"(.*?)\">/", $csrfresponse, $tokenmatches);
    $token = $tokenmatches[1] ?? null;
    $haswarning = preg_match("/name=\"user\"\stype=\"hidden\"\svalue=\"([0-9]+)\"/", $csrfresponse, $usermatches);
    $hascharwarning = preg_match("/name=\"character\"\stype=\"hidden\"\svalue=\"([0-9]+)\"/", $csrfresponse, $charactermatches);

    if($haswarning) {
        $user = $usermatches[1];

        $postquery = array(
            'user'=>$user,
            '_token'=>$token
        );

        if($hascharwarning) {
            $character = $charactermatches[1];
            $postquery = array_merge($postquery, array("character" => $character));
        }

        $acceptrequest = curl_init();
        curl_setopt($acceptrequest, CURLOPT_URL, "https://toyhou.se/~account/warnings/accept");
        curl_setopt ($acceptrequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($acceptrequest, CURLOPT_POST, 1);
        curl_setopt ($acceptrequest, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($acceptrequest, CURLOPT_POSTFIELDS, http_build_query($postquery));
        $acceptresponse = curl_exec($acceptrequest);
        curl_close($acceptrequest);
    }
    
    $profilerequest = curl_init();
    curl_setopt($profilerequest, CURLOPT_URL, $profile);
    curl_setopt ($profilerequest, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($profilerequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($profilerequest, CURLOPT_POST, 0);
    $profileresponse = curl_exec($profilerequest);
    

    if($ischaracter) {
        preg_match("/\"display-user-username\">(.*?)<\/span>/", $profileresponse, $ownermatches);
        $owner = $ownermatches[1];
        
        $ownerrequest = curl_init();
        curl_setopt($ownerrequest, CURLOPT_URL, "https://toyhou.se/".$owner);
        curl_setopt ($ownerrequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ownerrequest, CURLOPT_COOKIEFILE, $cookie);
        
        $ownerresponse = curl_exec($ownerrequest);
        $ownerhaswarning = preg_match("/name=\"user\"\stype=\"hidden\"\svalue=\"([0-9]+)\"/", $ownerresponse, $usermatches);
        if($ownerhaswarning) {

            $ownerpostquery = array(
                'user'=>$usermatches[1],
                '_token'=>$token
            );

            curl_setopt($ownerrequest, CURLOPT_URL, "https://toyhou.se/~account/warnings/accept");
            curl_setopt ($ownerrequest, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ownerrequest, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ownerrequest, CURLOPT_POST, 1);
            curl_setopt($acceptrequest, CURLOPT_POSTFIELDS, http_build_query($ownerpostquery));
            $ownerresponse = curl_exec($ownerrequest);
        }
    } else $ownerresponse = $profileresponse;
    $userimportpermitted = strpos($ownerresponse, "allow-thcj-import-all") !== false;
    
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
    