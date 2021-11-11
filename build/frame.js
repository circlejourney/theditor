var lastClicked, before;
var styles = {
    "Default": "../src/site_bootstrap.css?cachebust=46",
    "Night": "../src/site_night.css?cachebust=46",
    "Pink": "../src/site_black-forest.css?cachebust=46",
    "Teal": "../src/site_abyssal-plain.css?cachebust=46",
    "Bee": "../src/site_apis-mellifera.css?cachebust=46",
    "Pink Velvet": "../src/site_pink-velvet-cake.css?cachebust=46"
}

$(document).ready(function(){
    $("*").click('show.bs.collapse', function(i) {
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
    $("#custom-css").html(newCSS);
}

function updateHTML(newHTML) {
    var content = $.parseHTML(newHTML);
    $(".ace-code-container").empty();
    $(".ace-code-container").append(content);
    
    if(parent.$("#edit-preview").prop("checked")) {
    $(".ace-code-container").attr("contenteditable", "true");
        before = content;
    }
    
    if(lastClicked) {
        $(lastClicked).click();
    }
}

function dompath(element) {
    var path = "";
    if($(element).prop("id")) path = "#"+$(element).prop("id");
    else {
        for ( ; element && element.nodeType == 1; element = element.parentNode ) {
            let eleSelector = "";
		    let formatTag = element.tagName.toLowerCase();
            if(formatTag == "html" || formatTag == "body") continue;
            if(element.className) {
                eleSelector += "."+element.className.split(" ")[0];
            } else eleSelector += formatTag;
            let inner = $(element).children().length === 0 ? $(element).text() : '';
            eleSelector += ((inner.length > 0) ? ':contains("' + inner + '")' : '');
            path = ' ' + eleSelector + path;
        }
    }
    return path;
}