styles = {
    "Default": "/src/site_bootstrap.css?cachebust=46",
    "Night": "/src/site_night.css?cachebust=46",
    "Pink": "/src/site_black-forest.css?cachebust=46",
    "Teal": "/src/site_abyssal-plain.css?cachebust=46",
    "Bee": "/src/site_apis-mellifera.css?cachebust=46",
    "Pink Velvet": "/src/site_pink-velvet-cake.css?cachebust=46"
}
 
function switchTo(mode) {
    var currentHTML = $(".ace-code-container").html();
    var currentCSS = $("#custom-css").html();
    $.get("/templates/"+mode+".html", function(data) {
        $("#display-area").html(data);
        updateHTML(currentHTML);
        updateCSS(currentCSS);
    });
}

function toggleTheme(theme){
    $("#theme-css").attr("href", styles[theme]);
}

function updateCSS(newCSS) {
    $("#custom-css").html(newCSS);
}

function updateHTML(newHTML) {
    var content = $.parseHTML(newHTML);
    $(".ace-code-container").empty();
    $(".ace-code-container").append(content);
}