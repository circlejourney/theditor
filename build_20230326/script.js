// Setup variables. TODO: Put everything in a container object
var showval = "\<!-- Enter HTML here... --\>";
var activemode = "profile";
var activetheme = "Default";
var showcss = "\/* Enter CSS here... *\/";
var showtext = "Paste drafts and snippets here...";
var isBlurb = false;
const isSafari = navigator.userAgent.indexOf("Safari") > -1;
const isMobile = typeof screen.orientation !== 'undefined';
var htmlChanged = true;
var cssChanged = true;
var textChanged = true;
var cssPanel, textPanel, editor, css_editor, text_editor, frame, importedmeta, importedcode;
const loremipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sollicitudin elit sed tellus blandit viverra sed eget odio. Donec accumsan tempor lacus, et venenatis elit feugiat non. Duis porta eros et velit blandit dapibus. Curabitur ac finibus eros. Duis placerat velit vitae massa sodales, eget mattis nibh pellentesque.";
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

// Initialisation of web app is housed inside the window.onload event listener. TODO: Make this promise-based, i.e. upon loading all required files
$(window).on("load", function(){

    // Get frame and set frame's internal lastUpdate to parent lastUpdate
    frame = document.getElementById("frame");
    frame.contentWindow.lastUpdate = lastUpdate;

    const showChar = location.hash.substring(1);
    if(location.hash.substring(1)) {
        frame.contentWindow.importProfile(showChar);
    }

    $(".ui-options").click(function(e){
        e.stopPropagation();
    })
    
    loadNotes();
    loadLocal();
    initEditors();
    toggleUITheme();
    
    // Init click/touch events. Needs tidying and Safari compatibility
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


    // Set blank ACE text field behaviour
    
    updateCode();
    switchTo(activemode);
    toggleTheme(activetheme);
    resizeElements();
    setHTMLPanel();
    setCSSPanel();
    setTextPanel();
    
    // Ugly solution to not updating screen every time the user enters a new character.
    // TODO: Needs tidier behaviour e.g. a "queue" or something where each update request cancels the last.
    var tick = setInterval(function() {
        if($("#auto").prop("checked")) {
            if(htmlChanged) {
                if(editor.getValue()) {
                    $("#clear-html")
                        .html("<i class='fa fa-trash'></i>")
                        .attr("data-original-title", "Clear")
                        .removeData("justcleared");
                    if(editor.getValue()=="Please reset!") {
                        var confirmReset = confirm("Really hard reset? You will lose all drafts and settings.");
                        if(confirmReset === true) hardReset();
                        else editor.setValue("");
                    }
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
    
    $("#import .dropdown-menu").click(function(e){
        console.log("clock");
        e.stopPropagation();
    });


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

// TODO: Change this to promise-based
function loadNotes() {
    $.get("../known-issues.html?"+lastUpdate, function(data) {
        $("#issues-text").html(data);
    });
    
    $.get("../changelog.html?"+lastUpdate, function (data) {
        $("#changelog-text").html(data);
    });
    
    $.get("../notes.html?"+lastUpdate, function(data) {
		let year = Math.floor(lastUpdate / 10000);
		let month = months[Math.floor( (lastUpdate % 10000) / 100 ) - 1];
		let day = lastUpdate % 100;
        $("#notes").html(data).find("#latest").text("Latest update: "+day+" "+month+" "+year);
    });
    
    $.get("../versions.html?"+lastUpdate, function(data) {
        $("#versions-text").html(data);
    });
}

// TODO: Use destructuring of localStorage object to tidy this up
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
    
    if(localStorage.th_cj_importedmeta) {
       importedmeta = localStorage.th_cj_importedmeta;
    }
    
    if(localStorage.th_cj_importedcode) {
       importedcode = localStorage.th_cj_importedcode;
    }
    
    if(localStorage.th_cj_vertical) {
        $("#vertical").prop("checked", localStorage.th_cj_vertical == "true");
        setVerticalLayout();
    }
    
    if(localStorage.th_cj_htmlpanel) {
        $("#html-panel").prop("checked", localStorage.th_cj_htmlpanel == "true");
    }
    
    if(localStorage.th_cj_csspanel) {
        $("#css-panel").prop("checked", localStorage.th_cj_csspanel == "true");
    }
    
    if(localStorage.th_cj_textpanel) {
        $("#text-panel").prop("checked", localStorage.th_cj_textpanel == "true");
    }
    
    if(localStorage.th_cj_bigtext) {
        if(localStorage.th_cj_bigtext == "true") $(".editor-panel").addClass("big-text");
        $("#big-text").prop("checked", localStorage.th_cj_bigtext == "true");
    }
    
    if(localStorage.th_cj_auto) {
        $("#auto").prop("checked", localStorage.th_cj_auto == "true");
    }
    
    if(localStorage.th_cj_lastUpdate != lastUpdate && location.pathname.indexOf("/unstable") == -1) {
        $("#info").removeClass("d-none");
        $("#info-back").removeClass("d-none");
        localStorage.th_cj_lastUpdate = lastUpdate;
    }
    
    if(localStorage.th_cj_lowContrast) {
        $("#low-contrast").prop("checked", localStorage.th_cj_lowContrast == "true");
    }
}

function initEditors() {

    editor = ace.edit("html-editor");
    editor.session.setMode("ace/mode/html", () => {
        AceColorPicker.load(ace, editor);
    });
    editor.setShowPrintMargin(false);
    editor.setValue(showval);
    editor.session.setUseWrapMode(true);
    editor.session.on("change", function(){
        htmlChanged=true;
    });
    editor.on("focus", function() {
        if(editor.getValue()=="<!-- Enter HTML here... -->") editor.setValue("");
    });
    
    css_editor = ace.edit("css-editor");
    css_editor.session.setMode("ace/mode/css", () => {
        AceColorPicker.load(ace, editor);
    });
    css_editor.setShowPrintMargin(false);
    css_editor.setValue(showcss);
    css_editor.session.setUseWrapMode(true);
    css_editor.session.on("change", function(){
        cssChanged = true;
    });
    css_editor.on("focus", function() {
        if(css_editor.getValue()=="\/* Enter CSS here... *\/") css_editor.setValue("");
    })

    text_editor = ace.edit("text-editor");
    text_editor.setShowPrintMargin(false);
    text_editor.renderer.setShowGutter(false);
    text_editor.setValue(showtext);
    text_editor.session.setUseWrapMode(true);
    text_editor.session.on("change", function(){
        textChanged = true;
    });
    text_editor.on("focus", function() {
        if(text_editor.getValue()=="Paste drafts and snippets here...") text_editor.setValue("");
    })

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
    frame.contentWindow.switchTo(mode);
}

function updateCode(){
    updateHTML();
    updateCSS();
}

function updateHTML(){
    var val = editor.getValue();
    let updateDiv;
    if(isBlurb) {
        localStorage.th_cj_blurb = val;
        updateDiv = "ace-code-container-2";
    } else {
        localStorage.th_cj = val;
        updateDiv = "ace-code-container";
    }
    val = val.replace(/(<\/*)(script|style|head)(.*>)/g, "$1div$3");
    frame.contentWindow.updateHTML(val, updateDiv);
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
}
 
function setAutoUpdate() {
    localStorage.th_cj_auto = $("#auto").prop("checked");
    if($("#auto").prop("checked")) {
        updateCode();
    }
}

function setVerticalLayout() {
    localStorage.th_cj_vertical = $("#vertical").prop("checked");
    var codeheight, codewidth;
    
    if($("#vertical").prop("checked")) {
        
        $("#fields").append($(".html-visible"));
        $("#fields").append($(".css-visible"));
        $("#fields").append($(".text-visible"));
        
        $(document.body).addClass("vertical");
        $("#main").addClass("vertical");
        $("#frame").addClass("vertical");
        $("#adjustbar").addClass("vertical");
        $("#editor").addClass("vertical");
        $("#titles").addClass("vertical");
        $("#fields").addClass("vertical");
        
        $(document.body).append($("#footer"));
    
        codewidth = localStorage.th_cj_width;
        codeheight = "100%";
        
    } else {
        
        $("#titles").append($(".field-title"));
        
        $(document.body).removeClass("vertical");
        $("#main").removeClass("vertical");
        $("#frame").removeClass("vertical");
        $("#adjustbar").removeClass("vertical");
        $("#editor").removeClass("vertical");
        $("#titles").removeClass("vertical");
        $("#fields").removeClass("vertical");
        
        $("#main").append($("#footer"));
    
        codeheight = localStorage.th_cj_height;
        codewidth = "100%";
    }
    
    initHeight(codeheight, codewidth);
}

function setHTMLPanel() {
    htmlPanel = $("#html-panel").prop("checked");
    if(htmlPanel){
        $(".html-visible").removeClass("d-none");
    } else $(".html-visible").addClass("d-none");
    localStorage.th_cj_htmlpanel = htmlPanel;
    resizeEditors();
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

function toggleBigFont() {
    $(".ace_editor").toggleClass("big-text");
    localStorage.th_cj_bigtext = $("#big-text").prop("checked");
}

function resizeElements() {
    if(screen.width < 576) {
        if(!$("#main").hasClass("mobile-display")) {
            $("#main").addClass("mobile-display");
        }
    } else {
        $("#main").removeClass("mobile-display");
    }
}

function resizeEditors() {

    if(isSafari && !isMobile) {
        if(! $("#vertical").prop("checked")) {
            $(".ace_editor").css("height", window.innerHeight - $("#frame").height() - $("#adjustbar").height() - $("#footer").height() - $("#titles").height());
        } else {
            $(".ace_editor").css("width", window.innerWidth - $("#frame").width() - $("#adjustbar").width() - $("#footer").width() - $("#titles").width());
        }
    }

    editor.resize();
    css_editor.resize();
    text_editor.resize();
}

function initHeight(newheight, newwidth) {
    if(!newheight) {
        newheight = window.innerHeight*0.55;
    }
    if(!newwidth) {
        newwidth = window.innerWidth*0.55;
    }
    
    $("#frame").css("width", newwidth);
    
    $("#frame").css("height", newheight);
    
    if(editor) {
        resizeEditors();
    }
}

function dragHandler(e) {
    e.stopPropagation();
    
    if(!$("#vertical").prop("checked")) {
        if(e.clientY) {
            newHeight = e.clientY-4;
        } else if(e.originalEvent.targetTouches) {
            newHeight = e.originalEvent.targetTouches[0].clientY-4;
        }
        $("#frame").css("height", newHeight);
        localStorage.th_cj_height = newHeight;
    } else {
        if(e.clientX) {
            newWidth = e.clientX-4;
        } else if(e.originalEvent.targetTouches) {
            newWidth = e.originalEvent.targetTouches[0].clientX-4;
        }
        $("#frame").css("width", newWidth);
        localStorage.th_cj_width = newWidth;
    }
    
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
    panel == "html" ? storageName = "" : storageName = "_"+panel;
    var file = new Blob([ localStorage["th_cj"+storageName] ], {type: "text/plain"});
    var filetitle = "THeditor_"+panel.toUpperCase()+"_"+thedate.toLocaleDateString('en-GB')+"_"+thedate.toLocaleTimeString('en-GB')+".txt"
        .replace(/\:|\//g, "-");
    
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

function startImport(importType){
    frame.contentWindow.importProfile($("#char-id").val(), importType);
}

function hardReset() {
    if(!confirm("Download all code as text files and reset?")) return false;
    
    ["html", "css", "text"].forEach(function(i){
        downloadFile(i);
    });

    localStorage.removeItem("th_cj");
    localStorage.removeItem("th_cj_mode");
    localStorage.removeItem("th_cj_theme");
    localStorage.removeItem("th_cj_css");
    localStorage.removeItem("th_cj_text");
    localStorage.removeItem("th_cj_vertical");
    localStorage.removeItem("th_cj_htmlpanel");
    localStorage.removeItem("th_cj_csspanel");
    localStorage.removeItem("th_cj_textpanel");
    localStorage.removeItem("th_cj_auto");
    localStorage.removeItem("th_cj_lastUpdate");
    localStorage.removeItem("th_cj_hidenotif");
    localStorage.removeItem("th_cj_hidenotif2");
    location.reload();
}

function insertLorem(panel) {
    if(panel=='html') {
        editor.session.insert(editor.getCursorPosition(), loremipsum)
    } else if(panel=='css') {
        css_editor.session.insert(editor.getCursorPosition(), loremipsum)
    } else if(panel=='text') {
        text_editor.session.insert(editor.getCursorPosition(), loremipsum)
    }
}

function toggleUITheme(){

    localStorage.th_cj_lowContrast = $("#low-contrast").prop("checked");

    if($("#low-contrast").prop("checked")) { 

        $("#theme-css").attr("href", "../src/site_night-forest.css");
        $(".bg-light").removeClass("bg-light").addClass("bg-dark");

        editor.setTheme("ace/theme/tomorrow_night");
        css_editor.setTheme("ace/theme/tomorrow_night");
        text_editor.setTheme("ace/theme/tomorrow_night");

    } else {
        
        $("#theme-css").attr("href", "../src/site_black-forest.css")
        $(".bg-dark").removeClass("bg-dark").addClass("bg-light");

        editor.setTheme("ace/theme/monokai");
        css_editor.setTheme("ace/theme/monokai");
        text_editor.setTheme("ace/theme/monokai");
    }

}

function toggleBlurb() {
    if(isBlurb) {
        isBlurb = false;
        $("#html-tab").removeClass("text-dark");
        $("#blurb-tab").addClass("text-dark");
        editor.setValue(localStorage.th_cj);
    } else {
        isBlurb = true;
        $("#blurb-tab").removeClass("text-dark");
        $("#html-tab").addClass("text-dark");
        editor.setValue(localStorage.th_cj_blurb);
    }
}