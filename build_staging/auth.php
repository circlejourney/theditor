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
$settings = parse_ini_file(__DIR__."/../../settings.conf");
$username = $settings["username"];
$password = $settings["password"];
$cookie = getcwd() . DIRECTORY_SEPARATOR . "cookie.txt";

// Login CSRF handshake
$curlrequest = curl_init();
curl_setopt($curlrequest, CURLOPT_URL, $loginendpoint);
curl_setopt($curlrequest, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($curlrequest, CURLOPT_COOKIEFILE, $cookie);
curl_setopt($curlrequest, CURLOPT_RETURNTRANSFER, 1);
$csrfresponse = curl_exec($curlrequest);
phpQuery::newDocumentHTML($csrfresponse);
$authed = $csrfresponse && pq("meta[name='csrf-token']")->count() === 0;

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
    curl_setopt($curlrequest, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curlrequest, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curlrequest, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlrequest, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($curlrequest, CURLOPT_POSTFIELDS, http_build_query($loginheaders));
    curl_exec($curlrequest);
}

curl_setopt($curlrequest, CURLOPT_URL, $profile);
curl_setopt($curlrequest, CURLOPT_POST, 0);
$homepageresponse = curl_exec($curlrequest);
phpQuery::newDocumentHTML($homepageresponse);
$loggedin = pq("meta[name='user-id']")->attr("content") !== "0";

// Find user and accept user warning if it exists
if($ischaracter) {
    error_log("Finding owner...");
    curl_setopt($curlrequest, CURLOPT_URL, $profile);
    curl_setopt($curlrequest, CURLOPT_POST, 0);
    $profileresponse = curl_exec($curlrequest);
    phpQuery::newDocumentHTML($profileresponse);
    $owner = pq(".side-nav .display-user-username")->text();
} else $owner = $profilePath;
$ownerprofile = "https://toyhou.se/$owner";

error_log("Getting user profile...");
curl_setopt($curlrequest, CURLOPT_URL, $ownerprofile);
curl_setopt ($curlrequest, CURLOPT_COOKIEJAR, $cookie);
curl_setopt ($curlrequest, CURLOPT_COOKIEFILE, $cookie);
curl_setopt ($curlrequest, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curlrequest, CURLOPT_FOLLOWLOCATION, 0);
$ownerresponse = curl_exec($curlrequest);

$haswarning = preg_match("/name=\"user\"\stype=\"hidden\"\svalue=\"([0-9]+)\"/", $ownerresponse, $usermatches);
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
phpQuery::newDocumentHTML($profileresponse);
$userglobalpermit = pq("#allow-thcj-import-all")->count() > 0;

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
    echo "<div><div class='user-content thcj-warn'>Security alert: You are attempting to import a profile that has not been set to allow code import. To allow code import, paste the line <code>&lt;u id='allow-thcj-import'>&lt;/u></code> to the front of your code. Alternatively, paste the line <code>&lt;u id='allow-thcj-import-all'>&lt;/u></code> in your <b>user profile</b> to make <i>all</i> your codes importable.</div></div>";
    die();
} else {
    error_log("This profile has been set to allow import. Proceeding...");
    echo $profileresponse;
}