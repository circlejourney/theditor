<?php
    $settings = parse_ini_file(__DIR__."/../.env");
    $lastUpdate = (int)$settings["lastUpdate"]; // Set to last update to make the popup appear.
    $latestBuild = $settings["latestBuild"]; // Set this to the latest build directory to select the directory for source files
    $slash = DIRECTORY_SEPARATOR; 
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
        <script type="text/javascript" src="/src/ace-colorpicker.js?4" ></script>

        <!-- Beautify -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-css.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-html.min.js"></script>
        
        <!-- TH source -->
    	<link id="theme-css" href="/src/site_black-forest.css?2" rel="stylesheet">
        <link id="night-css" rel="stylesheet">
    	
        <!-- FONT AWESOME -->
		<script src="https://kit.fontawesome.com/0ddae54ad8.js" crossorigin="anonymous"></script>

        <script src="/build_<?php echo $latestBuild ?>/script.js?v=<?php echo filemtime(__DIR__ . $slash . "script.js"); ?>" type="text/javascript"></script>
        <link rel="stylesheet" href="/build_<?php echo $latestBuild ?>/style.css?v=<?php echo filemtime(__DIR__ . $slash . "style.css") ?>">
            
    </head>
    <body>
    
    <div id="loader" style="text-align: center; display: flex; flex-direction: column; justify-content: center;">
        <div class="loader-inner">
            <img src="https://media0.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" style="margin: -50px 0;">
            <p style="font-size: 20pt;">Stuck on the loading screen? <a href="https://toyhou.se/~forums/26275.feedback-bugs-suggestions/298364.stuck-on-the-loading-screen-error">Post a bug report on our Toyhouse world</a> or try a <a href="/versions.html" target="_blank">legacy version</a>.</p>
            <p style="font-size: 20pt;">If you suspect your code has crashed the editor, <a href="#" onclick="hardReset()">click here</a> to download your code as text files and hard reset the editor.</p>
            <p>Fun fact! You can also hard reset by typing <code>Please reset!</code> in the HTML panel.</p>
        </div>
    </div>
    <div id="info" class="d-none">
        <div id="info-back" onclick="showInfo();"></div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="m-0 w-75 d-inline-block p-2">Welcome to the Toyhouse Live Code Editor!</h2>
                <a title="You can view this again by clicking the info button in the bottom left corner." data-toggle="tooltip" class="close" onclick="showInfo()"><i class="fa fa-times"></i></a>
            </div>
            
            <div id="info-main" class="card-block text-center">
                <div id="notes">
                    <?php include __DIR__ . $slash . ".." . $slash . "notes.html"; ?>
                </div>
                
                <a class="btn btn-primary text-white" onclick="showInfo()">Got it!</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#changelog">Changelog</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#issues">Known issues</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#versions">Versions</a>
                <p></p>

                <div id="accordion">
                    <div class="collapse" id="changelog" data-parent="#accordion">
                        <div class="card mt-2">
                            <div class="card-block" id="changelog-text">
                                <?php echo file_get_contents(__DIR__ . $slash . ".." . $slash . "changelog.html"); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="collapse" id="issues" data-parent="#accordion">
                        <div class="card mt-2">
                            <div class="card-block" id="issues-text">
                                <?php echo file_get_contents(__DIR__ . $slash  . ".." . $slash . "known-issues.html"); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="collapse" id="versions" data-parent="#accordion">
                        <div class="card mt-2">
                            <div class="card-block" id="versions-text">
                                <?php echo file_get_contents(__DIR__ . $slash . ".." . $slash . "versions.html"); ?>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
         </div>
    </div>
    
    <div id="main">
        <iframe src="/build_<?php echo $latestBuild ?>/frame.php?2" id="frame" class="d-flex align-self-center">
        </iframe>
        
        <div id="adjustbar" class="progress-bar progress-bar-striped bg-secondary">
            <button class="btn btn-primary" id="mobile-switch" onclick="mobileSwitch()">
                <i class="mobile-switch-arrow fa fa-caret-down"></i>
            </button>
        </div>
        
        <div id="editor">
            
            <div id="titles">
                <div class="field-title html-visible">
                    <div class="panel-title-tabs">
                        <a class="nav-tab" id="html-tab" onclick="toggleBlurb('html')">HTML</a> &nbsp; <a class="nav-tab text-dark" id="blurb-tab" onclick="toggleBlurb('blurb')">Blurb</a>
                    </div>
                    <span class="panel-options" id="html-options">
                        <a class="edit-button" onclick="editor.undo()" data-toggle="tooltip" title="Undo"><i class="fas fa-undo-alt"></i></a>
                        <a class="edit-button" onclick="editor.redo()" data-toggle="tooltip" title="Redo"><i class="fas fa-redo-alt"></i></a>
                        <a class="edit-button" onclick="editor.insert(loremipsum)" data-toggle="tooltip" title="Insert lorem ipsum"><i class="fas fa-text"></i></a>
                        <a class="edit-button" onclick="beautifyHTML()" data-toggle="tooltip" title="Format HTML"><i class="fas fa-brackets-curly"></i></a>
                        <a class="edit-button" onclick="uploadFileDialogue('html')" data-toggle="tooltip" title="Import file"><i class="fa fa-file-import"></i></a>                        
                        <a class="edit-button" onclick="downloadFile('html')" data-toggle="tooltip" title="Save as file"><i class="fa fa-save"></i></a>
                        <a class="edit-button clear-button" id="clear-html" onclick="editor.setValue('')" data-toggle="tooltip" title="Clear"><i class="fa fa-trash"></i></a>
                        <a class="edit-button restore-button d-none" id="restore-html" onclick="restoreBackup('html')" data-toggle="tooltip" title="Restore backup"><i class="fa fa-trash-undo"></i></a>
                    </span>
                </div>
                <div class="field-title css-visible">
                    CSS
                    <span class="panel-options" id="css-options">
                        <a class="edit-button" onclick="css_editor.undo()" data-toggle="tooltip" title="Undo"><i class="fas fa-undo-alt"></i></a>
                        <a class="edit-button" onclick="css_editor.redo()" data-toggle="tooltip" title="Redo"><i class="fas fa-redo-alt"></i></a>
                        <a class="edit-button" onclick="beautifyCSS()" data-toggle="tooltip" title="Format CSS"><i class="fas fa-brackets-curly"></i></a>
                        <a class="edit-button" onclick="uploadFileDialogue('css')" data-toggle="tooltip" title="Import file"><i class="fa fa-file-import"></i></a>
                        <a class="edit-button" onclick="downloadFile('css')" data-toggle="tooltip" title="Save as file"><i class="fa fa-save"></i></a>
                        <a class="edit-button clear-button" id="clear-css" onclick="css_editor.setValue('')" data-toggle="tooltip" title="Clear"><i class="fa fa-trash"></i></a>
                        <a class="edit-button restore-button d-none" id="restore-css" onclick="restoreBackup('css')" data-toggle="tooltip" title="Restore backup"><i class="fa fa-trash-undo"></i></a>
                    </span>
                </div>
                <div class="field-title text-visible">
                    Scratchpad
                    
                    <span class="panel-options" id="text-options">
                        <a class="edit-button" onclick="text_editor.undo()" data-toggle="tooltip" title="Undo"><i class="fas fa-undo-alt"></i></a>
                        <a class="edit-button" onclick="text_editor.redo()" data-toggle="tooltip" title="Redo"><i class="fas fa-redo-alt"></i></a>
                        <a class="edit-button clear-button" id="clear-text" onclick="text_editor.setValue('')" data-toggle="tooltip" title="Clear"><i class="fa fa-trash"></i></a>
                        <a class="edit-button restore-button d-none" id="restore-text" onclick="restoreBackup('text')" data-toggle="tooltip" title="Restore backup"><i class="fa fa-trash-undo"></i></a>
                    </span>
                </div>
            </div>
            
            <div id="fields">
                <div class="html-visible editor-panel" id="html-editor"></div>
                <div class="d-none blurb-visible editor-panel" id="blurb-editor"></div>
                <div class="css-visible editor-panel" id="css-editor"></div>
                <div class="text-visible editor-panel" id="text-editor"></div>
            </div>
        </div>
            
        <div id="footer" class="bg-light justify-content-between ">
            <div id="footer-left">
                <a class="btn btn-secondary" onclick="showInfo()"><i class="fa fa-info"></i></a>
    
                <div id="themes" class="dropdown d-sm-inline">
                  <a class="btn btn-secondary dropdown-toggle" id="dropdownbutton" data-toggle="dropdown" data-trigger="focus" aria-haspopup="true" aria-expanded="false">
                    Theme 
                  </a>
                
                  <div class="dropdown-menu" aria-labelledby="dropdownbutton">
                    <a class="dropdown-item" onclick="toggleTheme('Default')">Default</a>
                    <a class="dropdown-item" onclick="toggleTheme('Night')">Night</a>
                    <a class="dropdown-item" onclick="toggleTheme('Pink')">pink is just the best color sorry i dont make the rules</a>
                    <a class="dropdown-item" onclick="toggleTheme('Teal')">what??? teal is clearly the superior colour</a>
                    <a class="dropdown-item" onclick="toggleTheme('Bee')">According to all known laws of aviation, there is no way a b</a>
                    <a class="dropdown-item" onclick="toggleTheme('Pink Velvet')">Pink Velvet Snake</a>
                  </div>
                </div>

                <div id="modes" class="dropdown d-sm-inline">
                  <a class="btn btn-secondary dropdown-toggle" id="dropdown" data-toggle="dropdown" data-trigger="focus" aria-haspopup="true" aria-expanded="false">
                    Layout
                  </a>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" onclick="switchTo('profile')">
                        User profile
                    </a>
                    <a class="dropdown-item" onclick="switchTo('char-profile')">
                        Character profile
                    </a>
                    
                    <a class="dropdown-item" onclick="switchTo('char-tab')">
                        Character basic tab
                    </a>
                    
                    <a class="dropdown-item" onclick="switchTo('char-gallery')">
                        Character gallery (static)
                    </a>
                    
                    <a class="dropdown-item" onclick="switchTo('forum-thread')">
                        Forum thread
                    </a>
                    <a class="dropdown-item" onclick="switchTo('forum-post')">
                        Forum post
                    </a>
                    
                    <a class="dropdown-item" onclick="switchTo('char-library')">
                        Character library (static)
                    </a>

                    <a class="dropdown-item" onclick="switchTo('lit-chapter')">
                        Literature chapter
                    </a>

                    <a class="dropdown-item" onclick="switchTo('bulletin')">
                        Bulletin
                    </a>

                    <a class="dropdown-item" onclick="switchTo('bulletin-nopoll')">
                        Bulletin with no poll
                    </a>

                    <a class="dropdown-item" onclick="switchTo('folder')">
                        Folder
                    </a>
                    <a class="dropdown-item" onclick="switchTo('world')">
                        World
                    </a>
                    <a class="dropdown-item" onclick="switchTo('world-page')">
                        World page
                    </a>

                    <a class="dropdown-item" onclick="switchTo('warning')">
                        Warning
                    </a>

                    </div>
                </div>
                
    
                <!-- <div id="import" class="dropdown d-sm-inline">
                  <a class="btn btn-secondary dropdown-toggle" id="dropdownbutton2" data-toggle="dropdown" data-trigger="focus" aria-haspopup="true" aria-expanded="false">
                    Import from TH
                  </a>
                
                  <div class="dropdown-menu px-2" aria-labelledby="dropdownbutton2" onclick="event.stopPropagation()">
                      <div class="d-flex flex-column">
                          <div class="d-flex mb-1">
                            <input type="text" id="char-id" class="form-control mr-1 import-button" placeholder="Enter ID"></input>
                             <abbr class="d-inline-block" title="The numerical ID (for a character profile, e.g. 1776660) or username (for a user profile). Profile must be public and contain the string allow-thcj-import. You can also set all profiles on your account to be importable by adding allow-thcj-import-<b>all</b> to your user profile." data-toggle="tooltip" data-html="true">
                                 <i class="fa fa-question-circle"></i>
                             </abbr>
                         </div>
                         <div class="d-flex">
                        <button class="btn btn-primary mr-1 import-button" id="import-meta" onclick="startImport('meta')">Import meta</button>
                        <button class="btn btn-primary mr-1 import-button" id="import-html" onclick="startImport('code')">Import HTML</button>
                        <button class="btn btn-primary" onclick="importedmeta=null; importedcode=null; localStorage.removeItem('th_cj_importedmeta'); localStorage.removeItem('th_cj_importedcode'); switchTo(activeMode);">Reset</button>
                        </div>
                    </div>
                  </div>
                </div> -->
                
                <a id="toggle-sidebar" class="btn btn-secondary" onclick="frame.contentWindow.toggleUI()">
                     <i id='sidebar-toggle-icon' class="fa fa-chevron-left"></i> Sidebar
                </a>
                        
            </div>
            
            <div id="footer-right">
                

                <span class="checkbox-container">
                    <input type="checkbox" id="wysiwyg" onchange="toggleWYSIWYG(this.checked===true)" autocomplete="off">&nbsp;<label for="wysiwyg">WYSIWYG</label>
                </span>

                <span class="checkbox-container">
                    <input type="checkbox" id="auto" onchange="toggleAuto()" checked="true">&nbsp;<label for="auto">Auto-update</label>
                </span>

                <span class="checkbox-container hide-small">
                    <input type="checkbox" id="mobile" onchange="toggleMobilePreview()">&nbsp;<label for="mobile">Mobile view</label>
                </span>

                <div id="ui-options" class="dropdown d-sm-inline">
                    <a class="btn btn-secondary dropdown-toggle" id="dropdownbutton" data-toggle="dropdown" data-trigger="focus" aria-haspopup="true" aria-expanded="false">
                        UI options 
                    </a>
                    
                    <div class="dropdown-menu ui-options px-2" aria-labelledby="dropdownbutton">

                        <span class="checkbox-container">
                            <input type="checkbox" id="html-panel" onclick="toggleHTMLPanel();" checked="true">&nbsp;<label for="html-panel">HTML</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="css-panel" onclick="toggleCSSPanel();" checked="true">&nbsp;<label for="css-panel">CSS</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="text-panel" onclick="toggleTextPanel();" checked="true">&nbsp;<label for="text-panel">Scratchpad</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="autocomplete" onclick="toggleAutocomplete();"> <label for="autocomplete">Autocomplete</label>
                        </span>

                        <span class="checkbox-container hide-small">
                            <input type="checkbox" id="colorpicker" onchange="toggleColorpicker()" checked="true">&nbsp;<label for="colorpicker">Colorpicker</label>
                        </span>

                        <span class="checkbox-container hide-small">
                            <input type="checkbox" id="vertical" onchange="toggleVertical()">&nbsp;<label for="vertical" class="hide-small">Vertical view</label>
                        </span>

                        <span class="checkbox-container hide-small">
                            <input type="checkbox" id="gutter" onchange="toggleGutter()" checked>&nbsp;<label for="gutter">Line numbers</label>
                        </span>

                        <span class="checkbox-container" id="ui-theme">
                            <input type="radio" name="colour-mode" id="dark" onchange="toggleUITheme();">&nbsp;<label for="dark">Dark mode</label>
                            <input type="radio" name="colour-mode" id="low-contrast" onchange="toggleUITheme();">&nbsp;<label for="low-contrast">Low contrast</label>
                            <input type="radio" name="colour-mode" id="light" onchange="toggleUITheme();">&nbsp;<label for="light">Light mode</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="big-text" onclick="toggleBigText();"> <label for="big-text">Big text</label>
                        </span>
                    </div>
                </div>
                <a class="btn btn-primary update-btn d-inline-block" id="update-preview" onclick="updateHTML(true); updateCSS(true);" href="#">Update preview</a>
            </div>
            
            <input type="file" class="d-none" id="fileupload" onclick="uploadFile">

        </div>
    </div>
    
    <script src="/src/site.js" type="text/javascript"></script>
    
    
        <!-- Default Statcounter code for Th.circlejourney.net
        https://th.circlejourney.net -->
        <script type="text/javascript">
            var sc_project=12027999; 
            var sc_invisible=1; 
            var sc_security="eafd79f2"; 
            </script>
            <script type="text/javascript"
            src="https://www.statcounter.com/counter/counter.js"
            async>
        </script>
        <noscript><div class="statcounter"><a title="web stats"
        href="https://statcounter.com/" target="_blank"><img
        class="statcounter"
        src="https://c.statcounter.com/12027999/0/eafd79f2/1/"
        alt="web stats"></a></div></noscript>
        <!-- End of Statcounter Code -->
    </body>
</html>