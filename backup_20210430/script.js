// Toyhouse code editor version: 28-12-2020. The previous version can be found at th.circlejourney.net/20201227 //

var showval = "\<!-- Enter HTML here... --\>";
var activemode = "profile";
var activetheme = "Default";
var showcss = "\/* Enter CSS here... *\/";
var showtext = "Paste drafts and snippets here...";
var htmlChanged = true;
var cssChanged = true;
var textChanged = true;
var codeheight, cssPanel, textPanel, editor, css_editor, text_editor, frame, user, isSafari;
var lastUpdate = "20210327";
    
$(window).on("load", function(){
    user = navigator.userAgent.toLowerCase();
    isSafari = user.indexOf("safari/") !== -1 && user.indexOf("chrome/") == -1;
    debug_log(isSafari);
    
    frame = document.getElementById("frame");
    
    loadNotes();
    loadLocal();
    initEditors();
    
    $("#adjustbar").mousedown(function(){
        $(window).mousemove(dragHandler);
        $(frame.contentWindow).mousemove(dragHandler);
        
        $(window).mouseup(cancelDrag);
        $(frame.contentWindow).mouseup(cancelDrag);
    });
    
    $("#adjustbar").on("touchstart", function(e){
        e.stopPropagation();
        
        $(window).on("touchmove", dragHandler);
        $(frame.contentWindow).on("touchmove", dragHandler);
        
        $(frame.contentWindow).on("touchend", cancelDrag);
        $(window).on("touchend", cancelDrag);
    });
    
    $("#mobile-switch").on("touchstart", mobileSwitch);

    editor.on("focus", function() {
        if(editor.getValue()=="<!-- Enter HTML here... -->") editor.setValue("");
    });

    css_editor.on("focus", function() {
        if(css_editor.getValue()=="\/* Enter CSS here... *\/") css_editor.setValue();
    })

    text_editor.on("focus", function() {
        if(text_editor.getValue()=="Paste drafts and snippets here...") text_editor.setValue();
    })
    
    updateCode();
    
    switchTo(activemode);
    toggleTheme(activetheme);
    resizeElements();
    updateHeight(parseFloat(codeheight));
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
        resizeElements();
        resizeEditors();
    });
    
    setTimeout(function(){
        $("#loader").addClass("invisible");
    }, 500);
});


function loadNotes() {
    $.get("../known-issues.html", (data) => {
        $("#issues-text").html(data);
    });
    
    $.get("../changelog.html", (data) => {
        $("#changelog-text").html(data);
    });
    
    $.get("../notes.html", (data) => {
        $("#notes").html(data);
    });
    
    $.get("../versions.html", (data) => {
        $("#versions-text").html(data);
    });
}

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
    
    if(localStorage.th_cj_lastUpdate != lastUpdate) {
        $("#info").removeClass("d-none");
        $("#info-back").removeClass("d-none");  
        localStorage.th_cj_lastUpdate = lastUpdate;
    }
    
    if(!localStorage.th_cj_hidenotif2) {
       localStorage.removeItem("th_cj_hidenotif2");
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
        htmlChanged=true;
    });
    
    css_editor = ace.edit("css-editor");
    css_editor.setTheme("ace/theme/monokai");
    css_editor.session.setMode("ace/mode/sass");
    css_editor.setShowPrintMargin(false);
    css_editor.setValue(showcss);
    css_editor.session.setUseWrapMode(true);
    css_editor.session.on("change", function(){
        cssChanged = true;
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
    if(frame) frame.contentWindow.switchTo(mode);
}

function updateCode(){
    updateHTML();
    updateCSS();
}

function updateHTML(){
    var val = editor.getValue();
    localStorage.th_cj = val;
    val = val.replace(/(<)(script|style|head)(.*>)/g, "$1div$3");
    if(frame) frame.contentWindow.updateHTML(val);
};

function updateCSS() {
    var sass = new Sass();
    var raw_css = css_editor.getValue();
    
    if(raw_css) {
        sass.compile(raw_css, function(result) {
            let css = result.text;
            if(css) frame.contentWindow.updateCSS(css);
            else frame.contentWindow.updateCSS(raw_css);
        });
    } else {
        frame.contentWindow.updateCSS("");
    }
    
    localStorage.th_cj_css = raw_css;
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
    } else $(".css-visible").addClass("d-none");
    localStorage.th_cj_csspanel = cssPanel;
    resizeEditors();
}

function setTextPanel() {
    textPanel = $("#text-panel").prop("checked");
    if(textPanel){
        $(".text-visible").removeClass("d-none");
    } else {
        $(".text-visible").addClass("d-none");
    }
    localStorage.th_cj_textpanel = textPanel;
    resizeEditors();
}

function resizeElements() {
    debug_log("Resizing to "+screen.width);
    if(screen.width < 576) {
        if(!$("#main").hasClass("mobile-display")) {
            $("#main").addClass("mobile-display");
        }
    } else {
        $("#main").removeClass("mobile-display");
    }
}

function resizeEditors() {
    if(isSafari) $(".ace_editor").css("height", window.innerHeight - $("#frame").height() - $("#adjustbar").height() - $("#footer").height() - $("#titles").height());
    editor.resize();
    css_editor.resize();
    text_editor.resize();
}

function updateHeight(newheight) {
    if(!newheight) {
        newheight = window.innerHeight*0.55;
    }
    
    $("#frame").css("height", newheight);
    codeheight = newheight;
    localStorage.th_cj_height = newheight;
    
}

function dragHandler(e) {
    e.stopPropagation();
    
    if(e.clientY) {
        newHeight = e.clientY-4;
    } else if(e.originalEvent.targetTouches) {
        newHeight = e.originalEvent.targetTouches[0].clientY-4;
    }
    
    $("#frame").css("height", newHeight);
    localStorage.th_cj_height = codeheight = newHeight;
    
    resizeEditors();
}

function cancelDrag(e) {
    e.stopPropagation();
    
    $(window).off("mousemove");
    $(window).off("mouseup");
    $(window).off("touchmove");
    $(window).off("touchend");
    
    $(frame.contentWindow).off("mousemove");
    $(frame.contentWindow).off("mouseup");
    $(frame.contentWindow).off("touchmove");
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
            .data("justcleared", "true");
        toClear.setValue("");
    } else {
        toClear.undo();
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


// DEBUG 

var debugdiv;

$(document).ready(function(){
    if(location.hash == "#debug") {
        debugdiv = $('<div id="debug" style="background-color: white; width: 40%; height: 90%; position: fixed; top: 0; right: 0; z-index: 10; overflow: auto;" onclick="shrinkGrow(this)"></div>');
        $(document.body).append(debugdiv);
    }
});

function debug_log(text) {
    console.log(text);
    if(debugdiv) debugdiv.append(text+"<br>");
}

function shrinkGrow(div) {
    if(!$(div).hasClass("shrunk")) {
        $(div).css("width", "10%");
        $(div).css("height", "10%");
        $(div).css("opacity", "0.5");
        $(div).addClass("shrunk");
    } else {
        $(div).css("width", "40%");
        $(div).css("height", "90%");
        $(div).css("opacity", "1");
        $(div).removeClass("shrunk");
    }
}