/**************************************
    vvv  Setup variables  vvv
**************************************/
// TODO: Put everything in sessionSettings object
// TODO: better way to determine if device is mobile (specifically to target desktop Safari for weird flex sizing) or a more elegant fix for weird sizing
const isSafari = navigator.userAgent.indexOf("Safari") > -1;
const isMobile = typeof screen.orientation !== 'undefined';
var sessionSettings = { activeMode: "profile", activeTheme: "Default" };
// DOM elements
var editor, css_editor, text_editor, frame;
const sass = new Sass();

/**************************************
    vvv  Constants  vvv
**************************************/
const defaultHTML = "\<!-- Enter HTML here... --\>";
const defaultCSS = "\/* Enter CSS here... *\/";
const defaultText =  "Paste drafts and snippets here...";
const loremipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis sollicitudin elit sed tellus blandit viverra sed eget odio. Donec accumsan tempor lacus, et venenatis elit feugiat non. Duis porta eros et velit blandit dapibus. Curabitur ac finibus eros. Duis placerat velit vitae massa sodales, eget mattis nibh pellentesque.";
const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const beautify_HTML_Options = { "indent_size": "1", "indent_char": "\t", "max_preserve_newlines": "-1", "preserve_newlines": false, "keep_array_indentation": false, "break_chained_methods": false, "indent_scripts": "normal", "brace_style": "expand", "space_before_conditional": true, "unescape_strings": false, "jslint_happy": false, "end_with_newline": false, "wrap_line_length": "0", "indent_inner_html": false, "comma_first": false, "e4x": false, "indent_empty_lines": false }
const beautify_CSS_Options = { "indent_size": "2", "indent_char": " ", "max_preserve_newlines": "-1", "preserve_newlines": false, "keep_array_indentation": false, "break_chained_methods": false, "indent_scripts": "normal", "brace_style": "collapse", "space_before_conditional": true, "unescape_strings": false, "jslint_happy": false, "end_with_newline": false, "wrap_line_length": "0", "indent_inner_html": false, "comma_first": false, "e4x": false, "indent_empty_lines": false };
const storagePrefix = "th_cj_";
const storages = ["th_cj", "th_cj_mode", "th_cj_theme", "th_cj_css", "th_cj_text", "th_cj_vertical", "th_cj_htmlpanel", "th_cj_csspanel", "th_cj_textpanel", "th_cj_auto", "th_cj_mobile", "th_cj_gutter", "th_cj_lastUpdate", "th_cj_hidenotif", "th_cj_hidenotif2", "th_cj_projectname"];
const codeTypes = {
    "html": {
        storageName: "th_cj",
        backupName: "th_cj_backup",
        aceEditor: "editor",
        defaultContent: defaultHTML
    },
    "blurb": {
        storageName: "th_cj_blurb",
        backupName: "th_cj_blurbbackup",
        aceEditor: "editor",
        defaultContent: defaultHTML
    },
    "css": {
        storageName: "th_cj_css",
        backupName: "th_cj_cssbackup",
        aceEditor: "css_editor",
        defaultContent: defaultCSS
    },
    "text": {
        storageName: "th_cj_text",
        backupName: "th_cj_textbackup",
        aceEditor: "text_editor",
        defaultContent: defaultText
    }
}


/**************************************
    vvv  Window events  vvv
**************************************/

$(window).on("load", () => {
    // Web app is initialised here; code should only start running after all DOM elements are loaded.
    // TODO: Make this promise-based, i.e. upon loading all required files

    // Get frame and set frame's internal lastUpdate date to parent lastUpdate date
    frame = document.getElementById("frame");
    frame.contentWindow.lastUpdate = lastUpdate;
    
    // Import notes, changelog, version list etc.
    loadNotes();
    
    // initEditors is called before loadLocalSettings so that editors are initialised before changing their appearance and contents.
    initEditors();
    loadLocalSettings();
    updateHTML();
    updateCSS();
    switchTo(sessionSettings.activeMode);
    toggleTheme(sessionSettings.activeTheme);

    
    // Start backup cycle
    setInterval(updateBackup, 300000);

    // Init event listeners. Needs tidying and Safari compatibility
    $(window).resize(() => {
        resizeScreen();
        resizeEditors();
    });

    $("#adjustbar").mousedown(() => {
        $(window).mousemove(dragHandler);
        $(frame.contentWindow).mousemove(dragHandler);
        
        $(window).mouseup(cancelDrag);
        $(frame.contentWindow).mouseup(cancelDrag);
    });
    
    $("#adjustbar").on("touchstart", (e) => {
        e.stopPropagation();
        
        $(window).on("touchmove", dragHandler);
        $(frame.contentWindow).on("touchmove", dragHandler);
        
        $(frame.contentWindow).on("touchend", cancelDrag);
        $(window).on("touchend", cancelDrag);
    });

    $(".ui-options").click((e) => {
        e.stopPropagation();
    });

    $("#fileupload").click(uploadFile);

    $(window).on("beforeunload", updateBackup);
    
    setTimeout(() => {
        $("#loader").addClass("invisible");
        resizeScreen();
        resizeEditors();
        // Ugly solution, waiting .5 seconds before resizing the editors ensures they resize to fit the content after the content is loaded. Can we make this promise based?
    }, 500);
});


function initEditors() {
    // Initialise the three Ace editors on the page with initial settings.
        
    editor = ace.edit("html-editor");
    editor.session.setMode("ace/mode/html", () => {
        if($("#colorpicker").prop("checked")) toggleColorpicker();
    });
    editor.setShowPrintMargin(false);
    editor.session.setUseWrapMode(true);
    editor.isBlurb = false;
    editor.session.on("change", () => {
        waitForIdle("html");
    });
    editor.on("focus", () => {
        if(editor.getValue()==defaultHTML) editor.setValue("");
    });
    
    css_editor = ace.edit("css-editor");
    css_editor.session.setMode("ace/mode/scss", () => {
        if($("#colorpicker").prop("checked")) toggleColorpicker();
    });
    css_editor.setShowPrintMargin(false);
    css_editor.session.setUseWrapMode(true);
    css_editor.session.on("change", () => {
        waitForIdle("css");
    });
    css_editor.on("focus", () => {
        if(css_editor.getValue()==defaultCSS) css_editor.setValue("");
    })

    text_editor = ace.edit("text-editor");
    text_editor.setShowPrintMargin(false);
    text_editor.renderer.setShowGutter(false);
    text_editor.session.setUseWrapMode(true);
    text_editor.session.on("change", () => {
        waitForIdle("text");
    });
    text_editor.on("focus", () => {
        if(text_editor.getValue()==defaultText) text_editor.setValue("");
    })

}


function loadLocalSettings() {
    // Extract the user's settings and content from local storage and update UI + editors.
    // TODO: Use destructuring of localStorage object to tidy this up
    editor.setValue( localStorage.th_cj ? localStorage.th_cj : defaultHTML );
    css_editor.setValue( localStorage.th_cj_css ? localStorage.th_cj_css : defaultCSS );
    text_editor.setValue( localStorage.th_cj_text ? localStorage.th_cj_text : defaultText );
    if(localStorage.th_cj === defaultHTML) {
        $("#clear-html").addClass("d-none");
        $("#restore-html").removeClass("d-none");
    }
    
    if(localStorage.th_cj_colorpicker) {
        $("#colorpicker").prop("checked", localStorage.th_cj_colorpicker == "true");
        toggleColorpicker();
    }
    
    if(localStorage.th_cj_mode) {
        sessionSettings.activeMode = localStorage.th_cj_mode;
    }
    
    if(localStorage.th_cj_theme) {
        sessionSettings.activeTheme = localStorage.th_cj_theme;
    }
    
    if(localStorage.th_cj_vertical) {
        $("#vertical").prop("checked", localStorage.th_cj_vertical == "true");
        toggleVertical();
    }
    
    if(localStorage.th_cj_gutter) {
        $("#gutter").prop("checked", localStorage.th_cj_gutter == "true");
        toggleGutter();
    }
    
    if(localStorage.th_cj_lowContrast) {
        $("#low-contrast").prop("checked", localStorage.th_cj_lowContrast == "true");
        toggleUITheme();
    } else {
        editor.setTheme("ace/theme/monokai");
        css_editor.setTheme("ace/theme/monokai");
        text_editor.setTheme("ace/theme/monokai");
    }
    
    if(localStorage.th_cj_htmlpanel) {
        $("#html-panel").prop("checked", localStorage.th_cj_htmlpanel == "true");
        toggleHTMLPanel();
    }
    
    if(localStorage.th_cj_csspanel) {
        $("#css-panel").prop("checked", localStorage.th_cj_csspanel == "true");
        toggleCSSPanel();
    }
    
    if(localStorage.th_cj_textpanel) {
        $("#text-panel").prop("checked", localStorage.th_cj_textpanel == "true");
        toggleTextPanel();
    }
    
    if(localStorage.th_cj_bigtext) {
        if(localStorage.th_cj_bigtext == "true") toggleBigText();
        $("#big-text").prop("checked", localStorage.th_cj_bigtext == "true");
    }
    
    if(localStorage.th_cj_auto) {
        $("#auto").prop("checked", localStorage.th_cj_auto == "true");
    }
    
    if(localStorage.th_cj_mobile) {
        $("#mobile").prop("checked", localStorage.th_cj_mobile == "true");
        toggleMobilePreview();
    }
    
    if(localStorage.th_cj_autocomplete) {
        $("#autocomplete").prop("checked", localStorage.th_cj_autocomplete == "true");
        toggleAutocomplete();
    }
    
    if(localStorage.th_cj_lastUpdate != lastUpdate && location.pathname.indexOf("/unstable") == -1) {
        $("#info").removeClass("d-none");
        $("#info-back").removeClass("d-none");
        localStorage.th_cj_lastUpdate = lastUpdate;
    }
}


function loadNotes() {
    // Get the current meta info and imports it into the info modal.
    // TODO: Change this to promise-based

    $.get("../known-issues.html?"+lastUpdate, (data) => {
        $("#issues-text").html(data);
    });
    
    $.get("../changelog.html?"+lastUpdate, function (data) {
        $("#changelog-text").html(data);
    });
    
    $.get("../notes.html?"+lastUpdate, (data) => {
        lastUpdate = lastUpdate;
		let year = Math.floor( lastUpdate / 10000);
		let month = months[Math.floor( (lastUpdate % 10000) / 100 ) - 1];
		let day = lastUpdate % 100;
        $("#notes").html(data).find("#latest").text("Latest update: "+day+" "+month+" "+year);
    });
    
    $.get("../versions.html?"+lastUpdate, (data) => {
        $("#versions-text").html(data);
    });
}


/**************************************
    vvv  Code update functions  vvv
**************************************/

function waitForIdle(panel) {
    console.log("Waiting for idle...");
    const { aceEditor, defaultContent, backupName } = codeTypes[panel];
    if(window[aceEditor].getValue() && window[aceEditor].getValue() !== defaultContent && localStorage[backupName]) {
        $("#clear-"+panel).removeClass("d-none");
        $("#restore-"+panel).addClass("d-none");
    } else {
        $("#clear-"+panel).addClass("d-none");
        $("#restore-"+panel).removeClass("d-none");
    }

    const wait = setTimeout( () => {checkForChanges(panel)}, 400 );
    window[aceEditor].session.on("change", () => {
        console.log("Update cancelled by user input.");
        clearTimeout(wait);
    })
}

function checkForChanges(panel) {
    console.log("Checking "+ panel + " panel for changes...");
    const { aceEditor } = codeTypes[panel];
    console.log(panel + " changed.");

    if(panel == "html") {
        if(editor.getValue().indexOf("Please reset!") !== -1) {
            editor.setValue( editor.getValue().replace("Please reset!", "") );
            hardReset();
            return false;
        } else {
            updateHTML();
        }
    }

    if(panel == "css") {
        updateCSS();
    }
    
    if(panel == "text") {
        updateText();
    }

}

function updateHTML(buttonTriggered=false){
    let val = editor.getValue();
    let updateEditor;
    if(editor.isBlurb) {
        localStorage.th_cj_blurb = val;
        updateEditor = "ace-code-container-2";
    } else {
        localStorage.th_cj = val;
        updateEditor = "ace-code-container";
    }

    if($("#auto").prop("checked") || buttonTriggered) {
        val = val.replace(/(<\/*)(script|style|head)(.*>)/g, "$1div$3");
        frame.contentWindow.updateHTML(val, updateEditor);
    }
};

function updateCSS(buttonTriggered=false) {
    //var sass = new Sass();
    var raw_css = css_editor.getValue();
    localStorage.th_cj_css = raw_css;
    
    if($("#auto").prop("checked") || buttonTriggered) {
        if(raw_css) {
            sass.compile(raw_css, (result) => {
                let css = result.text;
                if(css) frame.contentWindow.updateCSS(css);
                else frame.contentWindow.updateCSS(raw_css);
            });
        } else {
            frame.contentWindow.updateCSS("");
        }
    }
}

function updateText() {
    localStorage.th_cj_text = text_editor.getValue();
};

function beautifyHTML() {
    var beautifiedText = html_beautify(editor.getValue(), beautify_HTML_Options);
    editor.setValue(beautifiedText);
    editor.clearSelection();
}

function beautifyCSS() {
    var beautifiedText = css_beautify(css_editor.getValue(), beautify_CSS_Options);
    css_editor.setValue(beautifiedText);
    css_editor.clearSelection();
}


/**************************************
      vvv  Sizing functions  vvv
**************************************/

function resizeScreen() {
    // This function resizes the main element (where preview window and Ace panels are located) after screen size changes e.g. rotating device.
    if(screen.width < 576) {
        if(!$("#main").hasClass("mobile-display")) {
            $("#main").addClass("mobile-display");
        }
    } else {
        $("#main").removeClass("mobile-display");
    }
}

function resizeFrame(newheight, newwidth) {
    // This function resizes the frame element, usually after the drag bar has been moved.
    if(!newheight) {
        newheight = window.innerHeight*0.55;
    }
    if(!newwidth) {
        newwidth = window.innerWidth*0.55;
    }
    
    $("#frame").css("width", newwidth);
    
    $("#frame").css("height", newheight);
    
    if(editor) resizeEditors();
}

function resizeEditors() {
    // This function resizes the Ace editors to fill the space left by the preview window, useful after preview window has changed size. On Safari, which handles flex differently, this includes forcibly setting the width/height of the editors.
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

function mobileSwitch() {
    event.stopPropagation();
    if(!$("#editor").hasClass("d-none")) {
        $("#editor").addClass("d-none");
        $("#footer").addClass("d-none");
        $("#frame").addClass("expanded");
        $("#mobile-switch").html("<i class='fa fa-caret-up'></i>");
    } else {
        $("#editor").removeClass("d-none");
        $("#footer").removeClass("d-none");
        $("#frame").removeClass("expanded");
        $("#mobile-switch").html("<i class='fa fa-caret-down'></i>");
    }
}


/******************************************
    vvv  Backup functions  vvv
 ******************************************/

function updateBackup() {
    const updatePanels = ["html", "blurb", "css", "text"];
    console.log("Backing up " + updatePanels.join(", ") + "...");
    updatePanels.forEach( (panel) => {
        const { storageName, defaultContent, backupName } = codeTypes[panel];
        if(localStorage[storageName] &&  localStorage[storageName] !== defaultContent) {
            localStorage[backupName] = localStorage[storageName];
            console.log(panel+" backed up as localStorage."+backupName+".");
        }
        else console.log(panel+" is empty,  not backing up.");
    } );
}

function restoreBackup(panel) {
    if(editor.isBlurb) panel = "blurb";
    const { backupName, aceEditor } = codeTypes[panel];
    const useEditor = window[aceEditor];
    useEditor.setValue(localStorage[backupName]);
}


/*************************************************
    vvv  Local file upload/download functions  vvv
 *************************************************/

function downloadFile(panel) {
    var thedate = new Date();
    let datestring = thedate.toLocaleDateString('en-GB')+"_"+thedate.toLocaleTimeString('en-GB');
    const { storageName } = codeTypes[panel];
    var file = new Blob([ localStorage[storageName] ], {type: "text/plain"});
    var filesuffix = " " + panel.toUpperCase()+" "+datestring+".txt"
        .replace(/\:|\//g, "-");

    const projectname = prompt("Downloading "+panel+", project name:", localStorage.th_cj_projectname);
    if(!projectname) return false;
    localStorage.th_cj_projectname = projectname || "";
    
    if (window.navigator.msSaveOrOpenBlob)
        window.navigator.msSaveOrOpenBlob(file, (projectname || "THeditor") + filesuffix);
    else {
        var a = document.createElement("a")
        var url = URL.createObjectURL(file);
        a.href = url;
        a.download = (projectname || "THeditor") + filesuffix;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);  
    }
}

function uploadFileDialogue(panel) {
    // This function uses a dummy file input to element #fileupload to open upload dialogues for all 3 panels.
    $("#fileupload").data("target-panel", panel)
    $("#fileupload").click()
}

function uploadFile(){
    const panel = $(this).data("target-panel");
    const { aceEditor } = codeTypes[panel];
    $(this).on('change', function() {
        const filereader = new FileReader(); 
        filereader.onload = () => {
            window[aceEditor].setValue(filereader.result)
        }
        filereader.readAsText(this.files[0]);
    });
}

/**************************************
    vvv  TH import functions  vvv
**************************************/

function startImport(importType){
    frame.contentWindow.importProfile($("#char-id").val(), importType);
}

function renderProfileCode(data) {
        let customCSS;

        if(data.indexOf("<style>") !== -1) {
            customCSS = data.split("<style>")[1].split("</style>")[0].replace("<![CDATA[", "").replace("]]>", "").trim();
        }

        if(editor.isBlurb) toggleBlurb();
        editor.setValue(
            $(data).find(".user-content:not(.blurb)").html()
        );
        
        if(customCSS) css_editor.setValue(customCSS);
}

function renderProfileMeta(data) {
        
        let profileHeader = $(data).find(".profile-header").html();
        frame.contentWindow.$(".profile-header").html(profileHeader);
        frame.contentWindow.$(".profile-header a").prop("href", "#");
        
        let worldHeader = $(data).find(".profile-name-section").html();
        frame.contentWindow.$(".profile-name-section").html(worldHeader);
        frame.contentWindow.$(".profile-name-section a").prop("href", "#");
        
        let profileSidebar = $(data).find("#sidebar").html();
        frame.contentWindow.$("#sidebar").html(profileSidebar);
        frame.contentWindow.$("#sidebar a").prop("href", "#");
        
        frame.contentWindow.$(".blurb").addClass("ace-code-container-2");
        localStorage.th_cj_blurb = $(data).find(".blurb").html();
        if(editor.isBlurb) editor.setValue(localStorage.th_cj_blurb);

}


/**************************************
    vvv  UI functionality  vvv
**************************************/

function showInfo() {
    localStorage.th_cj_hidenotif2 = "true";
    $("#info").toggleClass("d-none");
}

function hardReset() {
    if(!confirm("Download all code as text files and reset?")) return false;
    ["html", "css", "text"].forEach((i) => { downloadFile(i); });
    storages.forEach((storageKey) => { localStorage.removeItme(storageKey); });

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
    localStorage.removeItem("th_cj_mobile");
    localStorage.removeItem("th_cj_gutter");
    localStorage.removeItem("th_cj_lastUpdate");
    localStorage.removeItem("th_cj_hidenotif");
    localStorage.removeItem("th_cj_hidenotif2");
    location.reload();
}

/**************************************
    vvv  UI toggles  vvv
**************************************/

function toggleTheme(theme) {
    sessionSettings.activeTheme = theme;
    localStorage.th_cj_theme = theme;
    if(frame){
        frame.contentWindow.toggleTheme(theme);
    }
}

function switchTo(mode) {
    sessionSettings.activeMode = mode;
    localStorage.th_cj_mode = mode;
    frame.contentWindow.switchTo(mode);
}

function toggleBlurb() {
    if(editor.isBlurb) {
        $("#html-tab").removeClass("text-dark");
        $("#blurb-tab").addClass("text-dark");
        editor.setValue(localStorage.th_cj);
    } else {
        $("#blurb-tab").removeClass("text-dark");
        $("#html-tab").addClass("text-dark");
        editor.setValue(localStorage.th_cj_blurb);
    }
    editor.isBlurb = !editor.isBlurb;
}

function toggleHTMLPanel() {
    htmlPanel = $("#html-panel").prop("checked");
    if(htmlPanel){
        $(".html-visible").removeClass("d-none");
    } else $(".html-visible").addClass("d-none");
    localStorage.th_cj_htmlpanel = htmlPanel;
    resizeEditors();
}

function toggleCSSPanel() {
    if( $("#css-panel").prop("checked") ){
        $(".css-visible").removeClass("d-none");
    } else $(".css-visible").addClass("d-none");
    localStorage.th_cj_csspanel = $("#css-panel").prop("checked");
    resizeEditors();
}

function toggleTextPanel() {
    if( $("#text-panel").prop("checked") ){
        $(".text-visible").removeClass("d-none");
    } else {
        $(".text-visible").addClass("d-none");
    }
    localStorage.th_cj_textpanel = $("#text-panel").prop("checked");
    resizeEditors();
}
 
function toggleAuto() {
    localStorage.th_cj_auto = $("#auto").prop("checked");
    if($("#auto").prop("checked")) {
        updateHTML();
        updateCSS();
    }
}

function toggleVertical() {
    localStorage.th_cj_vertical = $("#vertical").prop("checked");
    var codeheight, codewidth;
    
    if($("#vertical").prop("checked")) {
        
        $("#fields").append($(".html-visible"));
        $("#fields").append($(".css-visible"));
        $("#fields").append($(".text-visible"));
        
        // TODO: Tidy this up!  Too many things having the same classname toggled. Consider styling based on parent (body element?) with vertical class
        $(document.body).addClass("vertical");
        $("#main").addClass("vertical");
        $("#frame").addClass("vertical");
        $("#adjustbar").addClass("vertical");
        $("#editor").addClass("vertical");
        $("#titles").addClass("vertical");
        $("#fields").addClass("vertical");
        $("#mobile-switch").addClass("vertical")
        
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
        $("#mobile-switch").removeClass("vertical")
        
        $("#main").append($("#footer"));
    
        codeheight = localStorage.th_cj_height;
        codewidth = "100%";
    }
    
    resizeFrame(codeheight, codewidth);
}

function toggleMobilePreview() {
    localStorage.th_cj_mobile = $("#mobile").prop("checked");
    if($("#mobile").prop("checked")) $("#frame").addClass("mobile-preview");
    else $("#frame").removeClass("mobile-preview");
    resizeEditors();
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

function toggleBigText() {
    localStorage.th_cj_bigtext = $("#big-text").prop("checked");
    if($("#big-text").prop("checked")) $(".ace_editor").addClass("big-text");
    else  $(".ace_editor").removeClass("big-text");
}

function toggleGutter() {
    localStorage.th_cj_gutter = $("#gutter").prop("checked");

    if($("#gutter").prop("checked")) { 
        editor.renderer.setShowGutter(true);
        css_editor.renderer.setShowGutter(true);
        
    } else {
        editor.renderer.setShowGutter(false);
        css_editor.renderer.setShowGutter(false);
    }

}

function toggleAutocomplete() {
    localStorage.th_cj_autocomplete = $("#autocomplete").prop("checked");
    if($("#autocomplete").prop("checked")) {
        editor.setOptions({behavioursEnabled: true});
        css_editor.setOptions({behavioursEnabled: true});
    } else {
        editor.setOptions({behavioursEnabled: false});
        css_editor.setOptions({behavioursEnabled: false});
    }
}

function toggleColorpicker() {
    localStorage.th_cj_colorpicker = $("#colorpicker").prop("checked");

    if( !$("#colorpicker").prop("checked") ) {
        [editor.session, css_editor.session].forEach((session) => {

            let rules = session.$mode.$highlightRules.getRules();
            for (let stateName in rules) {
                rules[stateName].map(
                    i => {
                        if(i.token == "color" && i.regex == '#(?:[\\da-f]{8})|#(?:[\\da-f]{3}){1,2}|rgb\\((?:\\s*\\d{1,3},\\s*){2}\\d{1,3}\\s*\\)|rgba\\((?:\\s*\\d{1,3},\\s*){3}\\d*\\.?\\d+\\s*\\)|hsl\\(\\s*\\d{1,3}(?:,\\s*\\d{1,3}%){2}\\s*\\)|hsla\\(\\s*\\d{1,3}(?:,\\s*\\d{1,3}%){2},\\s*\\d*\\.?\\d+\\s*\\)') delete i.regex;
                    }
                );
            }
            // force recreation of tokenizer
            session.$mode.$tokenizer = null;
            session.bgTokenizer.setTokenizer(session.$mode.getTokenizer());
            // force re-highlight whole document
            session.bgTokenizer.start(0);
            
        });

        delete editor.colorview;
        delete css_editor.colorview;

    } else {
        editor.colorview = AceColorPicker.load(ace, editor);
        css_editor.colorview = AceColorPicker.load(ace, css_editor);
    }
}