<?php
/* This PHP programme is the non-auth version of auth.php which handles finding and retrieving HTML contents.
*   1. Verify that the profile is valid and not blank.
*   2. Verify that the user / character has the appropriate permissions set (using the allow-thcj-import string).
*   3. OR verify that the user has global import permissions allowed (using allow-thcj-import-all string).
*   4. If all checks are passed, fetch the full HTML contents of the profile page, including site meta, and serve in response.
*/
header("Content-Type: text/plain");
$captchawarning = "<div><div class='user-content thcj-warn'>Toyhouse's new recaptcha system prevents the import tool from bypassing warning pages. Only profiles without warnings can be imported.</div></div>";
$cookie = getcwd() . DIRECTORY_SEPARATOR . "cookie.txt";

$curlrequest = curl_init();
curl_setopt($curlrequest, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($curlrequest, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($curlrequest, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curlrequest, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($curlrequest, CURLOPT_RETURNTRANSFER, 1);

// Find user and accept user warning if it exists
if($ischaracter) {
    error_log("Finding owner...");
    curl_setopt($curlrequest, CURLOPT_URL, $profile);
    curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($curlrequest, CURLOPT_POST, 0);
    $profileresponse = curl_exec($curlrequest);
    if(!trim($profileresponse)) { echo $captchawarning; die(); }
    phpQuery::newDocumentHTML($profileresponse);
    $owner = pq(".side-nav .display-user-username")->text();
} else $owner = $profilePath;
$ownerprofile = "https://toyhou.se/$owner";

error_log("Getting $ownerprofile...");
curl_setopt($curlrequest, CURLOPT_URL, $ownerprofile);
curl_setopt($curlrequest, CURLOPT_FOLLOWLOCATION, 0);
$ownerresponse = curl_exec($curlrequest);
phpQuery::newDocumentHTML($ownerresponse);
$haswarning = pq("input[id='user']")->count();
// $haswarning = preg_match("/name=\"user\"\stype=\"hidden\"\svalue=\"([0-9]+)\"/", $ownerresponse, $usermatches);

if($haswarning) {
    error_log("Accepting user warning...");
    $user = $usermatches[1];
    $token = pq("meta[name='csrf-token']")->attr("content");
    $postquery = array(
        'user'=>$user,
        '_token'=>$token
    );
    curl_setopt($curlrequest, CURLOPT_URL, "https://toyhou.se/~account/warnings/accept");
    curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlrequest, CURLOPT_POST, 1);
    curl_setopt($curlrequest, CURLOPT_POSTFIELDS, http_build_query($postquery));
    $warningresponse = curl_exec($curlrequest);
}

// Check user profile for global user allow string
curl_setopt($curlrequest, CURLOPT_URL, $ownerprofile);
curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($curlrequest, CURLOPT_POST, 0);
$profileresponse = curl_exec($curlrequest);
$userglobalpermit = pq("#allow-thcj-import-all")->count() > 0;

// Accept character warning if character
if($ischaracter){
    error_log("Getting character profile...");
    curl_setopt($curlrequest, CURLOPT_URL, $profile);
    curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($curlrequest, CURLOPT_POST, 0);
    $charwarningresponse = curl_exec($curlrequest);
    phpQuery::newDocumentHTML($charwarningresponse);
    $hascharwarning = pq("input[name='character']")->count() > 0;

    if($hascharwarning) {
        error_log("Accepting $profilePath's warning...");
        $postquery = array(
            '_token' => pq("meta[name='csrf-token']")->attr("content"),
            'user'=> pq("input[name='user']")->attr("value"),
            'character' => pq("input[name='character']")->attr("value")
        );
        curl_setopt($curlrequest, CURLOPT_URL, "https://toyhou.se/~account/warnings/accept");
        curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
        curl_setopt($curlrequest, CURLOPT_POST, 1);
        curl_setopt($curlrequest, CURLOPT_POSTFIELDS, http_build_query($postquery));
        $charwarningresponse = curl_exec($curlrequest);
    }

    // Finally, get the profile of interest (if character?)
    curl_setopt($curlrequest, CURLOPT_URL, $profile);
    curl_setopt($curlrequest, CURLOPT_POST, 0);
    $profileresponse = curl_exec($curlrequest); // Override profileresponse with the character's profile
}

phpQuery::newDocumentHTML($profileresponse);
$stillhaswarning = pq("input[id='user']")->count();
if($stillhaswarning) { echo $captchawarning; die(); }

phpQuery::unloadDocuments();

if(strpos($profileresponse, "user-content") === false) {
    echo "<div><div class='user-content thcj-warn'>I couldn't find anything to import. The profile may be private, empty or nonexistent.</div></div>";
    die();

} else if(strpos($profileresponse, "allow-thcj-import") === false && !$userglobalpermit) {
    echo "<div><div class='user-content thcj-warn'>Security alert: You are attempting to import a profile that has not been set to allow code import. To allow code import, paste the line <code>&lt;u id='allow-thcj-import'>&lt;/u></code> to teh front of your code. Alternatively, paste the line <code>&lt;u id='allow-thcj-import-all'>&lt;/u></code> in your <b>user profile</b> to make <i>all</i> your codes importable.</div></div>";
    die();
} else {
    error_log("This profile has been set to allow import. Proceeding...");
    echo $profileresponse;
}
