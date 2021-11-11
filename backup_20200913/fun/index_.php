<?php 
    $user = $_GET["user"];
    $geturl = "https://toyhou.se/".$user."/characters/folder:all";
    $handle = curl_init($geturl);
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    $lastpage = "";
    $exists = true;
    if($httpCode == 404) {
        $exists = false;
    } else {
        include($geturl);
        $dump = ob_get_clean();
        $lastpage = explode("?page=", $dump);
        $lastpage = $lastpage[sizeof($lastpage)-2];
        $lastpage = explode('"', $lastpage);
        $lastpage = $lastpage[0];
    }
?>
            
<!doctype html>
<html>
    <head>
        <script src="https://circlejourney.net/resources/jquery-3.3.1.min.js" type="text/javascript"></script>
        <script src=""></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        
        <script>
            var charlist = [];
            $(function() {
                var user = location.search.split("=")[1];
                if(!user) return;
                if($(".character-thumb").length == 0) return;
                var chars = $(".character-thumb").each(function() {
                    charlist.push({
                        "name": $(this).find(".character-name-badge").text(),
                        "id": $(this).find(".img-thumbnail").attr("href").split(".")[0].substr(1),
                        "href": $(this).find(".img-thumbnail").attr("href"),
                        "imgurl": $(this).find("img").attr("src"),
                        "faves": $(this).find(".th-favorite-count").text()
                    });
                });
                $(".switch").removeClass("d-none");
                $("#display-user").html(user);
                $("#display-char-count").html(charlist.length);
                $("#user").val(user);
                $("#dump").empty();
                getRandom();
                getPop();
            });
            
            function getRandom() {
                if(!charlist) return;
                var char=charlist[Math.floor(Math.random()*charlist.length)];
                $("#random-char").html(char.name);
                $("#random-char-icon").attr("src", char.imgurl);
                $("#random-char-link").attr("href", char.href);
            }
            
            function getPop() {
                charlist.sort(function(a, b) {
                    return parseInt(b.faves) - parseInt(a.faves);
                });
                $("#popular-char").text(charlist[0].name);
                $("#popular-char-icon").attr("src",charlist[0].imgurl);
                $("#popular-char-link").attr("href", char.href);
            }
        </script>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
            #dump {
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="text-center">
                <h1>Toyhouse tools</h1>
                <p>Pick a random character from a user, find a user's most popular character, etc. A work in progress.</p>
                <form class="form-inline d-inline-block" action="/fun" method="GET">
                    <label class="d-inline" for="user">Username </label>
                    <input class="form-control d-inline" type="text" name="user" id="user"> <button class="btn btn-primary">Go</button>
                </form>
                <div id="display" class="switch d-none">
                    <p>User: <span id="display-user">user</span></p>
                    <p>Found <span id="display-char-count">0</span> characters!</p>
                </div>
            </div>
        <div class="switch d-none text-center">
            <div class="row">
                <div class="col">
                    <h4>Random character:</h4>
                    <a id="random-char-link"><img id="random-char-icon"></a>
                    <h2 id="random-char"></h2>
                    <button class="btn btn-primary" onclick="getRandom()">Generate</button>
                </div>
                <div class="col">
                    <h4>Most popular character:</h4>
                    <a id="popular-char-link"><img id="popular-char-icon"></a>
                    <h2 id="popular-char"></h2>
                </div>
            </div>
            
                <div class="row">
                    <div class="col">
                        
                    </div>
                    <div class="col">
                        
                    </div>
                </div>
            </div>
        </div>
        <div id="dump">
            <?php
                if($exists) {
                    for($i=0; $i<$lastpage; $i++) {
                        include("https://toyhou.se/".$user."/characters/folder:all?page=".strval($i+1));
                    }
                }
            ?>
        </div>
    </body>
</html>