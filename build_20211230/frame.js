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
    $.get("../templates/"+mode+".html?"+parent.lastUpdate, function(data) {
        $("#display-area").html(data);
        updateHTML(currentHTML);
        updateCSS(currentCSS);
        if(parent.importedmeta) {
            importProfileMeta(parent.importedmeta);
        }
        console.log(mode);
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

function updateHTML(newHTML) {
    var content = $.parseHTML(newHTML);
    $(".ace-code-container").empty();
    $(".ace-code-container").append(content);
}

function importProfile(profilePath, importType){
    $.get("get.php", { "profileCode": profilePath, "getMeta": importType=="meta" }, function(data){    
        if(data==="") {
            alert("Private or invalid profile.");
            return;
        }
        
        switchTo(parent.activemode);
        
        parent["imported"+importType] = data;
        localStorage["th_cj_imported"+importType] = data;
        
        if(importType=="meta") importProfileMeta(parent.importedmeta);
        else if (importType=="code") importProfileCode(parent.importedcode);
    });
}

function importProfileMeta(data) {
        
        var profileHeader = $(data).find(".profile-header").html();
        $(".profile-header").html(profileHeader);
        $(".profile-header a").prop("href", "#");
        
        var worldHeader = $(data).find(".profile-name-section").html();
        $(".profile-name-section").html(worldHeader);
        $(".profile-name-section a").prop("href", "#");
        
        var profileSidebar = $(data).find("#sidebar").html();
        $("#sidebar").html(profileSidebar);
        $("#sidebar a").prop("href", "#");
}

function importProfileCode(data) {

        const changeHTML = confirm("Import HTML? This will overwrite your current work.");
        
        if(changeHTML) {
            parent.editor.setValue(
                $(data).find(".user-content:not(.blurb)").html()
            );
        }
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