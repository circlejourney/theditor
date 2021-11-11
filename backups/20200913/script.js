var showval = "\<!-- Enter HTML here... --\>";
var activemode = "profile";
var activetheme = "Default";
var showcss = "\/* Enter CSS here... *\/";
var codeheight = "45";
var htmlChanged = false;
var cssChanged = false;
var editor, css_editor;

styles = {
    "Default": "src/site_bootstrap.css?cachebust=46",
    "Night": "src/site_night.css?cachebust=46",
    "Pink": "src/site_black-forest.css?cachebust=46",
    "Teal": "src/site_abyssal-plain.css?cachebust=46",
    "Bee": "src/site_apis-mellifera.css?cachebust=46",
    "Pink Velvet": "src/site_pink-velvet-cake.css?cachebust=46"
}


$(window).keypress(function(e){
    if(e.keyCode == 10 && e.ctrlKey) updateCode();
});

$(window).resize(function(){
    editor.resize();
})


$(window).on("load", function(){
    loadLocal();
    
    editor = ace.edit("html-editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/html");
    editor.setShowPrintMargin(false);
    editor.setValue(showval);
    editor.session.setUseWrapMode(true);
    editor.session.on("change", function(){
        if($("#auto").prop("checked")) {
        htmlChanged=true;
            //updateCode();
        }
    });
    
    css_editor = ace.edit("css-editor");
    css_editor.setTheme("ace/theme/monokai");
    css_editor.session.setMode("ace/mode/sass");
    css_editor.setShowPrintMargin(false);
    css_editor.setValue(showcss);
    css_editor.session.setUseWrapMode(true);
    css_editor.session.on("change", function(){
        if($("#auto").prop("checked")) {
        cssChanged = true;
            //updateCode();
        }
    });
    
    $("#adjustbar").mousedown(function(){
        $(window).mousemove(function(e) {
            var percent = 100*e.clientY / window.innerHeight;
            updateHeight(percent);
            codeheight = percent.toString();
            localStorage.th_cj_height = codeheight;
        }); 
    
        $(window).mouseup(function(e){
            $(window).off("mousemove");
            $(window).off("mouseup");
        });
    });

    editor.on("focus", function() {
        if(editor.getValue()=="\<!-- Enter HTML here... --\>") editor.setValue();
    });
    editor.on("blur", function() {
        if(editor.getValue()=="") editor.setValue("\<!-- Enter HTML here... --\>");
    });

    css_editor.on("focus", function() {
        if(css_editor.getValue()=="\/* Enter CSS here... *\/") css_editor.setValue();
    })

    css_editor.on("blur", function() {
        if(css_editor.getValue()=="") css_editor.setValue("\/* Enter CSS here... *\/");
    })
    
    updateCode();
    
    switchTo(activemode);
    toggleTheme(activetheme);
    updateHeight(parseFloat(codeheight));
    setCSSPanel();
    
    var tick = setInterval(function() {
    if($("#auto").prop("checked")) {
    if(htmlChanged) {
    updateHTML();
    htmlChanged=false;
    }
    if(cssChanged) {
        updateCSS();
            cssChanged=false;
            }
        }
    }, 1000);
    
    setTimeout(function(){
        $("#loader").addClass("invisible");
    }, 500);
});

function loadLocal() {    
    if(localStorage.th_cj) {
        showval = localStorage.th_cj;
    }
    
    if(localStorage.th_cj_mode) {
        activemode = localStorage.th_cj_mode;
    }
    
    if(localStorage.th_cj_theme) {
        activetheme = localStorage.th_cj_theme;
    }
    
    if(localStorage.th_cj_css) {
        showcss = localStorage.th_cj_css;
    }
    
    if(localStorage.th_cj_height) {
        codeheight = localStorage.th_cj_height;
    }
    
    if(localStorage.th_cj_csspanel) {
        $("#css-panel").prop("checked", localStorage.th_cj_csspanel == "true");
    }
    
    if(localStorage.th_cj_auto) {
        $("#auto").prop("checked", localStorage.th_cj_auto == "true");
    }
    
    if(!localStorage.th_cj_hidenotif) {
        $("#info").removeClass("d-none");
        $("#info-back").removeClass("d-none");
    }
}
 
function hideNotif() {
    localStorage.th_cj_hidenotif = "true";
    $("#info").addClass("d-none");
    $("#info-back").addClass("d-none");
}

function toggleTheme(theme) {
    activetheme = theme;
    $("#theme-css").attr("href", styles[theme]);
    localStorage.th_cj_theme = theme;
}

function updateCode(){
    var val = editor.getValue();
    var content = $.parseHTML(val);
    $(".ace-code-container").empty();
    $("#"+activemode).find(".ace-code-container").append(content);
    localStorage.th_cj = val;
    
    var sass = new Sass();
    var raw_css = css_editor.getValue();
    sass.compile(raw_css, function(result) {
          css = result.text;
        $("#custom-css").html(css);
        localStorage.th_cj_css = raw_css;
    });
}

function updateHTML(){
var val = editor.getValue();
    var content = $.parseHTML(val);
    $(".ace-code-container").empty();
    $("#"+activemode).find(".ace-code-container").append(content);
    localStorage.th_cj = val;
};

function updateCSS() {
var sass = new Sass();
    var raw_css = css_editor.getValue();
    sass.compile(raw_css, function(result) {
          css = result.text;
        $("#custom-css").html(css);
        localStorage.th_cj_css = raw_css;
    });
};

function switchTo(mode) {
    activemode = mode;
    $(".mode").addClass("d-none");
    $("#"+mode).removeClass("d-none");
    localStorage.th_cj_mode = mode;
    updateCode();
}

function toggleTheme(theme) {
    activetheme = theme;
    $("#theme-css").attr("href", styles[theme]);
    localStorage.th_cj_theme = theme;
    updateCode();
}

function showInfo() {
    $("#noconflict-info").toggleClass("d-none");
    $("#info-back").toggleClass("d-none");
}
 
function setAutoUpdate() {
    localStorage.th_cj_auto = $("#auto").prop("checked");
    if($("#auto").prop("checked")) updateCode();
}

function setCSSPanel() {
    cssPanel = $("#css-panel").prop("checked");
    if(cssPanel){
        $("#html-editor").css("width", "50%");
        $("#css-editor").css("display", "initial");
        editor.resize();
    } else {
        $("#html-editor").css("width", "100%");
        $("#css-editor").css("display", "none");
        editor.resize();
    }
    localStorage.th_cj_csspanel = cssPanel;
}
function updateHeight(percent) {
    if(percent < 90 && percent > 0) {
        $("#editor").css("height", 100-percent+"%");
        $("#show").css("height", percent-0.5+"%");
        $("#adjustbar").css("bottom", 100-percent+"%");
        //$("#options").css("bottom", 100-percent+3+"%");
        //$("#info-button").css("bottom", 100-percent+3+"%");
    }
}

function resetHeight() {
    updateHeight(45);
}