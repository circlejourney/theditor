<?php
    $settings = parse_ini_file(__DIR__."/../.env");
    $lastUpdate = (int)$settings["lastUpdate"]; // Change latest update date to make the popup appear.
    $latestBuild = $settings["latestBuild"]; // Set this to the latest build directory to select the directory for source files
?>

<!DOCTYPE html>
<html lang="en" data-last-update="<?php echo $lastUpdate ?>">

<head>
    <title>Circlejourney's Toyhouse Live Code Editor</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="An editor for live previewing Toyhouse code.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://circlejourney.net/resources/images/favicon.png">

    <!-- Misc libraries -->
    <script src="/src/jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <script src="/src/ace-1.4.14/src-min/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="/src/ace-1.4.14/src-min/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
    <script src="/src/sass.js-master/dist/sass.js"></script>


    <!-- Ace Colorpicker -->
    <link rel="stylesheet" href="/src/ace-colorpicker.css" />
    <script type="text/javascript" src="/src/ace-colorpicker.js?4"></script>

    <!-- Beautify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-html.min.js"></script>

    <!-- TH source -->
    <link id="theme-css" href="/src/site_black-forest.css?2" rel="stylesheet">
    <link id="night-css" rel="stylesheet">

    <!-- FONT AWESOME -->
    <script src="https://kit.fontawesome.com/0ddae54ad8.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="/build_<?php echo $latestBuild ?>/style.css?v=<?php echo filemtime(__DIR__ . DIRECTORY_SEPARATOR . "style.css") ?>">
    
    <style>
        #fields {
            height: 100%;
        }

        #editor {
            height: 100%;
        }
    </style>

    <script>
        let lastRequest;

        const defaultHTML = "\<!-- Enter HTML here... --\>";
        const defaultCSS = "\/* Enter CSS here... *\/";
        const defaultText =  "Paste drafts and snippets here...";
        const codeTypes = {
            "html": {
                aceEditor: "editor",
                defaultContent: defaultHTML
            },
            "blurb": {
                aceEditor: "editor",
                defaultContent: defaultHTML
            },
            "css": {
                backupName: "th_cj_cssbackup",
                aceEditor: "css_editor",
                defaultContent: defaultCSS
            },
            "text": {
                aceEditor: "text_editor",
                defaultContent: defaultText
            }
        };
        
        $(window).on("load", ()=>{
            console.log("test");
            initEditors();
        });

        function initEditors() {
            // Initialise the three Ace editors on the page with initial settings.
                
            editor = ace.edit("html-editor");
            editor.session.setMode("ace/mode/html", () => {
                if($("#colorpicker").prop("checked")) toggleColorpicker();
            });
            editor.setTheme("ace/theme/monokai");
            editor.setShowPrintMargin(false);
            editor.session.setUseWrapMode(true);
            editor.isBlurb = false;
            editor.session.on("change", () => {
                checkForChanges("html");
            });
            editor.on("focus", () => {
                if(editor.getValue()==defaultHTML) editor.setValue("");
            });
            $(editor.container).on("keydown", (e) => {
                if(e.ctrlKey && e.key=="s") {
                    e.preventDefault();
                    downloadFile("html");
                }
            });
            
            css_editor = ace.edit("css-editor");
            css_editor.session.setMode("ace/mode/scss", () => {
                if($("#colorpicker").prop("checked")) toggleColorpicker();
            });
            css_editor.setTheme("ace/theme/monokai");
            css_editor.setShowPrintMargin(false);
            css_editor.session.setUseWrapMode(true);
            css_editor.session.on("change", () => {
                checkForChanges("css");
            });
            css_editor.on("focus", () => {
                if(css_editor.getValue()==defaultCSS) css_editor.setValue("");
            })
            $(css_editor.container).on("keydown", (e) => {
                if(e.ctrlKey && e.key=="s") {
                    e.preventDefault();
                    downloadFile("text");
                }
            });

            text_editor = ace.edit("text-editor");
            text_editor.setTheme("ace/theme/monokai");
            text_editor.setShowPrintMargin(false);
            text_editor.renderer.setShowGutter(false);
            text_editor.session.setUseWrapMode(true);
            text_editor.session.on("change", () => {
                checkForChanges("text");
            });
            text_editor.on("focus", () => {
                if(text_editor.getValue()==defaultText) text_editor.setValue("");
            })
            $(text_editor.container).on("keydown", (e) => {
                if(e.ctrlKey && e.key=="s") {
                    e.preventDefault();
                    downloadFile("html");
                }
            });

        }

        function checkForChanges(panel) {
            clearTimeout(lastRequest);
            lastRequest = setTimeout( () => {
                if(panel == "html") {
                    if(editor.getValue().indexOf("Please reset!") !== -1) {
                        editor.setValue( editor.getValue().replace("Please reset!", "") );
                        hardReset();
                        return false;
                    } else {
                        updateHTML();
                    }
                }
                if(panel == "css") updateCSS();
                if(panel == "text") updateText();
            }, 400 );
        }

        function updateHTML() {
            console.log(editor.getValue());
        }

        function sendData(key, value) {
            const data = {};
            return[key] = value;
            window.opener.postMessage( data );
        }

    </script>
</head>

<body>
<?php include("editor.html") ?>
</body>

</html>