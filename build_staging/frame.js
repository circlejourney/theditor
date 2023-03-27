styles = {
    "Default": "../src/site_bootstrap.css",
    "Night": "../src/site_night.css",
    "Pink": "../src/site_black-forest.css",
    "Teal": "../src/site_abyssal-plain.css",
    "Bee": "../src/site_apis-mellifera.css",
    "Pink Velvet": "../src/site_pink-velvet-cake.css"
}
 
function switchTo(mode) {

    $.get("../templates/"+mode+".html?"+lastUpdate, function(data) {
        $("#display-area").html(data);

        updateHTML(localStorage.th_cj_blurb, "ace-code-container-2");
        updateHTML(localStorage.th_cj, "ace-code-container");
        updateCSS(localStorage.th_cj_css);

        if(parent.importedmeta) {
            parent.renderProfileMeta(parent.importedmeta);
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

function updateHTML(newHTML, div) {
    var content = $.parseHTML(newHTML);
    $("."+div).empty();
    $("."+div).append(content);
}

function importProfile(profilePath, importType){
    $.post("get.php", { "profilePath": profilePath, "getMeta": importType=="meta" }, function(data){ 
        switchTo(parent.activemode);
        
        parent["imported"+importType] = data;
        localStorage["th_cj_imported"+importType] = data;
        
        if(importType=="meta") parent.renderProfileMeta(data);
        else if (importType=="code") parent.renderProfileCode(data);
    });
}

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