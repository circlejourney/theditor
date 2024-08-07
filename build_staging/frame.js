styles = {
    "Default": "../src/site_bootstrap.css",
    "Night": "../src/site_night.css",
    "Pink": "../src/site_black-forest.css",
    "Teal": "../src/site_abyssal-plain.css",
    "Bee": "../src/site_apis-mellifera.css",
    "Pink Velvet": "../src/site_pink-velvet-cake.css"
}

$(document).ready(function(){
    $("body").tooltip({ selector: '[data-toggle=tooltip]' });
    $(".ace-code-container").on("change", function(){
        getWYSIWYG();
    });
});
 
function switchTo(mode) {

    $.get("../templates/"+mode+".html", {"v": lastUpdate }, function(data) {
        $("#display-area").html(data);
        
        const requestHTML = requestFromDB("html");
        const requestBlurb = requestFromDB("blurb");
        const requestCSS = requestFromDB("css");
        
        requestHTML.onsuccess = function(e) {
            updateHTML(e.target.result.code, "ace-code-container");
        }
        requestBlurb.onsuccess = function(e) {
            updateHTML(e.target.result.code, "ace-code-container-2");
        }
        requestCSS.onsuccess = function(e) {
            updateCSS(e.target.result.code);
        }

        if(localStorage.th_cj_importedmeta) {
            renderProfileMeta(localStorage.th_cj_importedmeta);
        }
        
        if(mode == "world" || mode.indexOf("profile") != -1 || mode == "warning") {
            parent.$("#import-meta").prop("disabled", false);
        } else {
            parent.$("#import-meta").prop("disabled", true);
        }
        
        if(localStorage.thcj_hideUI == "true") {
            $("#main").addClass("collapsed-sidebar").removeClass("full-sidebar");
        }
    });
    
}

function toggleTheme(theme){
    $("#theme-css").attr("href", styles[theme]);
}

function updateCSS(newCSS) {
    $("#custom-css").empty();
    $("#custom-css").append(newCSS);
}

function updateHTML(newHTML, className) {
    try {
        update = $.parseHTML(newHTML);
    } catch {
        alert("Please remove all HTML tags (e.g. <div></div>) inside comments, the editor can't parse them!");
    }
    $("."+className).empty().append(update);
    $(".fr-spoiler").on("click", function(e){
        e.pageY - $(this).offset().top < 32 && $(this).toggleClass("fr-spoiler-show");
    });
}

function importProfile(profilePath, importType){
    if(confirm("Import from Toyhouse? This will overwrite existing "+importType+".")) {
        $.post("get.php", { "profilePath": profilePath, "getMeta": importType=="meta" }, function(data){ 
            switchTo(parent.sessionSettings.activeMode);
            
            localStorage["th_cj_imported"+importType] = data;
            
            if(importType=="meta") renderProfileMeta(data);
            else if (importType=="code") renderProfileCode(data);
        });
    }
}

// Page interactivity functions

function toggleUI (){
    $(".hide-ui-label").toggleClass("hide");
    
    if(localStorage.thcj_hideUI == "true") {
        parent.$("#sidebar-toggle-icon").addClass("fa-chevron-left").removeClass("fa-chevron-right");
        $("#main").addClass("full-sidebar").removeClass("collapsed-sidebar");
        $("#toggle-sidebar").text("Collapse sidebar");
        localStorage.thcj_hideUI = "false";
    } else {
        parent.$("#sidebar-toggle-icon").removeClass("xfa-chevron-left").addClass("fa-chevron-right");
        $("#main").addClass("collapsed-sidebar").removeClass("full-sidebar");
        $("#toggle-sidebar").text("Expand sidebar");
        localStorage.thcj_hideUI = "true";
    }
    
}

function toggleLightbox() {
    $(".mfp-ready").toggleClass("hide");
}

function toggleLitTheme(className) {
    $("#main")
        .removeClass()
        .addClass("clearfix container-fluid main-container full-sidebar "+className);
}

function toggleLitFont(fontFamily) {
    $(".literature-chapter-content").css("font-family", fontFamily);
}

function toggleLitSize(delta) {
    if(delta) $(".literature-chapter-content").css("font-size", parseInt($(".literature-chapter-content").css("font-size")) + delta);
    else  $(".literature-chapter-content").css("font-size", "");
}

function toggleWYSIWYG(toState) {
    if(toState === false) {
        $(".ace-code-container").removeAttr("contenteditable").remove(".wysiwyg-placeholder");
    }
    else {
        $(".ace-code-container").attr("contenteditable", true);
        if($(".ace-code-container").text().trim() === "") {
            const placeholder = $("<div class='wysiwyg-placeholder'>Start typing here. When you're done, click on the HTML panel or uncheck WYSIWYG to update the HTML!</div>").on("click", function(e){
                $(e.target)
                    .html("")
                    .removeClass("wysiwyg-placeholder")
                    .off("click");
            });
            $(".ace-code-container").empty().append(placeholder)
        }
    }
}

function getWYSIWYG() {
    return $(".ace-code-container").html();
}