styles = {
    "Default": "../src/site_bootstrap.css",
    "Night": "../src/site_night.css",
    "Pink": "../src/site_black-forest.css",
    "Teal": "../src/site_abyssal-plain.css",
    "Bee": "../src/site_apis-mellifera.css",
    "Pink Velvet": "../src/site_pink-velvet-cake.css"
}
 
function switchTo(mode) {
    var currentHTML = $(".ace-code-container").html();
    var currentCSS = $("#custom-css").html();
    $.get("../templates/"+mode+".html", function(data) {
        $("#display-area").html(data);
        updateHTML(currentHTML);
        updateCSS(currentCSS);
    });
}

function toggleTheme(theme){
    $("#theme-css").attr("href", styles[theme]);
}

function updateCSS(newCSS) {
    $("#custom-css").empty();
    $("#custom-css").append(newCSS);
}

function updateHTML(newHTML) {
    var content = $.parseHTML(newHTML);
    $(".ace-code-container").empty();
    $(".ace-code-container").append(content);
}