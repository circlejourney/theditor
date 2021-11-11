var lastClicked;

var styles = {
    "Default": "../src/site_bootstrap.css",
    "Night": "../src/site_night.css",
    "Pink": "../src/site_black-forest.css",
    "Teal": "../src/site_abyssal-plain.css",
    "Bee": "../src/site_apis-mellifera.css",
    "Pink Velvet": "../src/site_pink-velvet-cake.css"
}

$(document).ready(function(){
    $("*").on('show.bs.collapse', function(i) {
      lastClicked = dompath(i.target);
    });
    
    $("*").on('show.bs.tab', function(i) {
      lastClicked = dompath(i.target);
    });
});
 
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
    if(lastClicked) {
        $(lastClicked).click();
    }
}

function dompath(element) {
    var path = "";
    var isFirst = true;
    if($(element).prop("id")) path = "#"+$(element).prop("id");
    else {
        for ( ; element && element.nodeType == 1; element = element.parentNode ) {
            let eleSelector = "";
		    let formatTag = element.tagName.toLowerCase();
            if(formatTag == "html" || formatTag == "body") continue;
            if(element.className) {
                eleSelector += "."+element.className.split(" ")[0];
            } else eleSelector += formatTag;
            if(isFirst) eleSelector += ':contains("' + $(element).text() + '")';
            path = ' ' + eleSelector + path;
            isFirst = false;
        }
    }
    return path;
}