// Toyhouse code editor version: 28-12-2020. The previous version can be found at th.circlejourney.net/20201227 //

var showval = "\<!-- Enter HTML here... --\>";
var activemode = "profile";
var activetheme = "Default";
var showcss = "\/* Enter CSS here... *\/";
var showtext = "";
var htmlChanged = true;
var cssChanged = true;
var textChanged = true;
var codeheight, cssPanel, textPanel, editor, css_editor, text_editor, frame;

styles = {
    "Default": "../src/site_bootstrap.css?cachebust=46",
    "Night": "../src/site_night.css?cachebust=46",
    "Pink": "../src/site_black-forest.css?cachebust=46",
    "Teal": "../src/site_abyssal-plain.css?cachebust=46",
    "Bee": "../src/site_apis-mellifera.css?cachebust=46",
    "Pink Velvet": "../src/site_pink-velvet-cake.css?cachebust=46"
}


$(window).on("load", function(){
    frame = document.getElementById("frame");
    
    $.get("known-issues.html", (data) => {
        $("#issues-text").html(data);
    });
    
    $.get("changelog.html", (data) => {
        $("#changelog-text").html(data);
    });
    
    loadLocal();
    initEditors();
    
    $("#adjustbar").mousedown(function(){
        $(window).mousemove((e) => {
            e.preventDefault();
            newHeight = e.clientY-4;
            $("#frame").css("height", newHeight);
            localStorage.th_cj_height = codeheight = newHeight;
            editor.resize();
            css_editor.resize();
            text_editor.resize();
        }); 
    
        $(window).mouseup(cancelMouseFunctions);
        
        $(frame.contentWindow).mouseup(cancelMouseFunctions);
    });
    
    $("#adjustbar").on("touchstart", function(e){
        $(window).on("touchmove", (e) => {
            var newHeight = e.originalEvent.targetTouches[0].clientY-4;
            $("#frame").css("height", newHeight);
            localStorage.th_cj_height = codeheight = newHeight;
            editor.resize();
            css_editor.resize();
            text_editor.resize();
        }); 
    
        $(window).on("touchend", cancelTouchFunctions);
        
        $(frame.contentWindow).on("touchend", cancelTouchFunctions);
    });
    
    $("#mobile-switch").on("touchstart", mobileSwitch);

    editor.on("focus", function() {
        if(editor.getValue()=="<!-- Enter HTML here... -->") editor.setValue("");
    });

    css_editor.on("focus", function() {
        if(css_editor.getValue()=="\/* Enter CSS here... *\/") css_editor.setValue();
    })
    
    updateCode();
    
    switchTo(activemode);
    toggleTheme(activetheme);
    updateHeight(parseFloat(codeheight));
    resizeElements();
    setCSSPanel();
    setTextPanel();
    
    var tick = setInterval(function() {
        if($("#auto").prop("checked")) {
            if(htmlChanged) {
                if(editor.getValue()) {
                    $("#clear-html")
                        .html("<i class='fa fa-trash'></i>")
                        .attr("data-original-title", "Clear")
                        .removeData("justcleared");
                }
                updateHTML();
                htmlChanged=false;
            }
            
            if(cssChanged) {
                if(css_editor.getValue()) {
                    $("#clear-css")
                        .html("<i class='fa fa-trash'></i>")
                        .attr("data-original-title", "Clear")
                        .removeData("justcleared");
                }
                updateCSS();
                cssChanged=false;
            }
            
            if(textChanged) {
                if(text_editor.getValue()) {
                    $("#clear-text")
                        .html("<i class='fa fa-trash'></i>")
                        .attr("data-original-title", "Clear")
                        .removeData("justcleared");
                }
                updateText();
                textChanged=false;
            }
        }
    }, 1000);


    $(window).keypress(function(e){
        if(e.keyCode == 10 && e.ctrlKey) {
            e.preventDefault();
            updateCode();
        }
    });
    
    $(window).resize(function(){
        editor.resize();
        resizeElements();
    });
    
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
    
    if(localStorage.th_cj_text) {
        showtext = localStorage.th_cj_text;
    }
    
    if(localStorage.th_cj_height) {
        codeheight = localStorage.th_cj_height;
    }
    
    if(localStorage.th_cj_csspanel) {
        $("#css-panel").prop("checked", localStorage.th_cj_csspanel == "true");
    }
    
    if(localStorage.th_cj_textpanel) {
        $("#text-panel").prop("checked", localStorage.th_cj_textpanel == "true");
    }
    
    if(localStorage.th_cj_auto) {
        $("#auto").prop("checked", localStorage.th_cj_auto == "true");
    }
    
    if(!localStorage.th_cj_hidenotif2) {
        $("#info").removeClass("d-none");
        $("#info-back").removeClass("d-none");  
    }
    
    if(localStorage.th_cj_hidenotif) {
       localStorage.removeItem("th_cj_hidenotif");
    }
}

function initEditors() {
    editor = ace.edit("html-editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/html");
    editor.setShowPrintMargin(false);
    editor.setValue(showval);
    editor.session.setUseWrapMode(true);
    editor.session.on("change", function(){
        if($("#auto").prop("checked")) {
            htmlChanged=true;
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
        }
    });
    
    text_editor = ace.edit("text-editor");
    text_editor.setTheme("ace/theme/monokai");
    text_editor.session.setMode("ace/mode/plain_text");
    text_editor.setShowPrintMargin(false);
    text_editor.renderer.setShowGutter(false);
    text_editor.setValue(showtext);
    text_editor.session.setUseWrapMode(true);
    text_editor.session.on("change", function(){
        textChanged = true;
    });
}

function toggleTheme(theme) {
    activetheme = theme;
    localStorage.th_cj_theme = theme;
    if(frame){
        frame.contentWindow.toggleTheme(theme);
    }
}

function switchTo(mode) {
    activemode = mode;
    localStorage.th_cj_mode = mode;
    
    if(frame) {
        frame.contentWindow.switchTo(mode);
    }
}

function updateCode(){
    updateHTML();
    updateCSS();
}

function updateHTML(){
    var val = editor.getValue();
    localStorage.th_cj = val;
    val = val.replace(/(<)(script|style|head)(.*>)/g, "$1div$3");
    if(frame) {
        frame.contentWindow.updateHTML(val);
    }
};

function updateCSS() {
    var sass = new Sass();
    var raw_css = css_editor.getValue();
    localStorage.th_cj_css = raw_css;
    if(raw_css) {
        sass.compile(raw_css, function(result) {
            css = result.text;
            if(css) {
                frame.contentWindow.updateCSS(css);
            } else {
                frame.contentWindow.updateCSS("");
            }
        });
    } else {
        frame.contentWindow.updateCSS("");
    }
}

function updateText() {
    localStorage.th_cj_text = text_editor.getValue();
};

function showInfo() {
    localStorage.th_cj_hidenotif2 = "true";
    $("#info").toggleClass("d-none");
    $("#info-back").toggleClass("d-none");
}
 
function setAutoUpdate() {
    localStorage.th_cj_auto = $("#auto").prop("checked");
    if($("#auto").prop("checked")) {
        updateCode();
    }
}

function setCSSPanel() {
    cssPanel = $("#css-panel").prop("checked");
    if(cssPanel){
        $(".css-visible").removeClass("d-none");
        editor.resize();
    } else {
        $(".css-visible").addClass("d-none");
    }
    localStorage.th_cj_csspanel = cssPanel;
    editor.resize();
    css_editor.resize();
    text_editor.resize();
}

function setTextPanel() {
    textPanel = $("#text-panel").prop("checked");
    console.log(textPanel);
    if(textPanel){
        $(".text-visible").removeClass("d-none");
    } else {
        $(".text-visible").addClass("d-none");
    }
    localStorage.th_cj_textpanel = textPanel;
    editor.resize();
    css_editor.resize();
    text_editor.resize();
}

function resizeElements() {
    console.log("resizing to "+screen.width);
    if(screen.width < 576) {
        if(!$("#main").hasClass("mobile-display")) {
            $("#main").addClass("mobile-display");
        }
        $("#frame").css("height", "");
    } else {
        $("#main").removeClass("mobile-display");
        updateHeight(parseFloat(codeheight));
    }
}

function updateHeight(newheight) {
    if(!newheight) {
        newheight = window.innerHeight*0.55;
    }
    
    $("#frame").css("height", newheight);
    codeheight = newheight;
    localStorage.th_cj_height = newheight;
}

function cancelMouseFunctions(event) {
    event.preventDefault();
    $(window).off("mousemove");
    $(window).off("mouseup");
    $(frame.contentWindow).off("mouseup");   
}

function cancelTouchFunctions(event) {
    e.preventDefault();
    $(window).off("touchmove");
    $(window).off("touchend");
    $(frame.contentWindow).off("touchend");
}

function downloadFile(panel) {
    var thedate = new Date();
    if(panel == "html") {
        var file = new Blob([editor.getValue()], {type: "text/plain"});
        var filetitle = `THeditor_HTML_${thedate.toLocaleDateString('en-GB')}_${thedate.toLocaleTimeString('en-GB')}.txt`
            .replace(/\:|\//g, "-");
    } else if(panel == "css") {
        var file = new Blob([css.getValue()], {type: "text/plain"});
        var filetitle = `THeditor_CSS_${thedate.toLocaleDateString('en-GB')}_${thedate.toLocaleTimeString('en-GB')}.txt`
            .replace(/\:|\//g, "-");
    }
    
    if (window.navigator.msSaveOrOpenBlob)
        window.navigator.msSaveOrOpenBlob(file, filetitle);
    else {
        var a = document.createElement("a")
        var url = URL.createObjectURL(file);
        a.href = url;
        a.download = filetitle;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);  
    }
}

function uploadFileDialogue(panel) {
    $("#fileupload").data("target-panel", panel)
    $("#fileupload").click()
}

function uploadFile(div){
    var panel = $(div).data("target-panel");
    console.log(panel);
    div.addEventListener('change', function() {
        var fr=new FileReader(); 
        fr.onload=function() {
            if(panel == "html")
                editor.setValue(fr.result);
            else if(panel == "css")
                css_editor.setValue(fr.result);
        }
        fr.readAsText(this.files[0]); 
    })
    
}

function clearEditor(div, panel) {
    var toClear;
    
    if(panel == "html") toClear = editor;
    else if(panel == "css") toClear = css_editor
    else if(panel == "text") toClear = text_editor;
    
    if(!$(div).data("justcleared")) {
        $(div)
            .html("<i class='fa fa-undo'></i>")
            .attr("data-original-title", "Restore")
            .data("justcleared", toClear.getValue());
        toClear.setValue("");
    } else {
        toClear.setValue($(div).data("justcleared"));
        $(div)
            .html("<i class='fa fa-trash'></i>")
            .attr("data-original-title", "Clear")
            .removeData("justcleared");
    }
    
    toClear.focus();
}

function mobileSwitch(e) {
    e.stopPropagation();
    if(!$("#editor").hasClass("shrunk")) {
        $("#editor").addClass("shrunk");
        $("#frame").addClass("expanded");
        $("#mobile-switch").html("<i class='fa fa-caret-up'></i>");
    } else {
        $("#editor").removeClass("shrunk");
        $("#frame").removeClass("expanded");
        $("#mobile-switch").html("<i class='fa fa-caret-down'></i>");
    }
}