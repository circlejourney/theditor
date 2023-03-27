 <?php
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $lastUpdate = "staging"; ?>
        <script>
            const lastUpdate = '<?php echo $lastUpdate ?>';
        </script>
        
        <title>Circlejourney's Toyhouse Live Code Editor</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="An editor for live previewing Toyhouse code.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="https://circlejourney.net/resources/images/favicon.png">
        
        <!-- Misc libraries -->
    	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/src/ace-builds/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
        <link rel="stylesheet" href="/src/ace-colorpicker.css" />
        <script type="text/javascript" src="/src/ace-colorpicker.js" ></script>
        <script src="../src/sass.js-master/dist/sass.js"></script>
        
        <!-- TH source -->
    	<link id="theme-css" href="../src/site_black-forest.css?cachebust=2" rel="stylesheet">
    	
        <!-- FONT AWESOME -->
        <link rel="stylesheet" href="../src/fontawesome-pro-6.0.0-beta3-web/css/all.min.css">
        <script src="../src/fontawesome-pro-6.0.0-beta3-web/js/all.min.js" crossorigin="anonymous"></script>
        
        <script src="/build_<?php echo $lastUpdate ?>/script.js?cachebust=1" type="text/javascript"></script>
        <link rel="stylesheet" href="/build_<?php echo $lastUpdate ?>/style.css?cachebust=1">
            
    </head>
    <body>
    
    <div id="loader" style="text-align: center; display: flex; flex-direction: column; justify-content: center;">
        <div class="loader-inner">
            <img src="https://media0.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" style="margin: -50px 0;">
            <p style="font-size: 20pt;">Stuck on the loading screen? <a href="https://toyhou.se/~forums/26275.feedback-bugs-suggestions/298364.stuck-on-the-loading-screen-error">Post a bug report on our Toyhouse world</a>.</p>
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
                <div id="notes"></div>
                
                <a class="btn btn-primary" onclick="showInfo()">Got it!</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#changelog">Changelog</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#issues">Known issues</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#versions">Versions</a>
                <p></p>

                <div id="accordion">
                    <div class="collapse" id="changelog" data-parent="#accordion">
                        <div class="card mt-2">
                            <div class="card-block" id="changelog-text">
                            </div>
                        </div>
                    </div>
                    
                    <div class="collapse" id="issues" data-parent="#accordion">
                        <div class="card mt-2">
                            <div class="card-block" id="issues-text">
                            </div>
                        </div>
                    </div>
                    
                    <div class="collapse" id="versions" data-parent="#accordion">
                        <div class="card mt-2">
                            <div class="card-block" id="versions-text">
                            </div>
                        </div>
                    </div>
                </div>
             </div>
         </div>
    </div>
    
    <div id="main">
        <iframe src="/build_<?php echo $lastUpdate ?>/frame.html" id="frame" class="d-flex">
        </iframe>
        
        <div id="adjustbar" class="progress-bar progress-bar-striped bg-secondary">
            <button class="btn btn-primary show-small" id="mobile-switch">
                <i class="fa fa-caret-down"></i>
            </button>
        </div>
        
        <div id="editor" class="d-flex">
            
            <div id="titles">
                <div class="field-title html-visible">
                    <div>
                        <a class="nav-tab" id="html-tab" onclick="toggleBlurb('html')">HTML</a> &ensp; <a class="nav-tab text-dark" id="blurb-tab" onclick="toggleBlurb('blurb')">Blurb</a>
                    </div>
                    <span class="panel-options" id="html-options">
                        <a class="edit-button" onclick="insertLorem('html')" data-toggle="tooltip" title="Insert lorem ipsum"><i class="fas fa-text"></i></a>
                        <a class="edit-button" onclick="uploadFileDialogue('html')" data-toggle="tooltip" title="Upload file"><i class="fa fa-file-upload"></i></a>                        
                        <a class="edit-button" onclick="downloadFile('html')" data-toggle="tooltip" title="Save as file"><i class="fa fa-save"></i></a>
                        <a class="edit-button clear-button" id="clear-html" onclick="clearEditor(this, 'html')" data-toggle="tooltip" title="Clear"><i class="fa fa-trash"></i></a>
                    </span>
                </div>
                <div class="field-title css-visible">
                    CSS
                    <span class="panel-options" id="css-options">
                        <a class="edit-button" onclick="insertLorem('css')" data-toggle="tooltip" title="Insert lorem ipsum"><i class="fas fa-text"></i></a>
                        
                        <a class="edit-button" onclick="uploadFileDialogue('css')" data-toggle="tooltip" title="Upload file"><i class="fa fa-file-upload"></i></a>
                        <a class="edit-button" onclick="downloadFile('css')" data-toggle="tooltip" title="Save as file"><i class="fa fa-save"></i></a>
                        <a class="edit-button clear-button" id="clear-css" onclick="clearEditor(this, 'css')" data-toggle="tooltip" title="Clear"><i class="fa fa-trash"></i></a>
                    </span>
                </div>
                <div class="field-title text-visible">
                    Scratch pad
                    
                    <span class="panel-options" id="text-options">
                        <a class="edit-button" onclick="insertLorem('text')" data-toggle="tooltip" title="Insert lorem ipsum"><i class="fas fa-text"></i></a>
                        
                        <a class="edit-button clear-button" id="clear-text" onclick="clearEditor(this, 'text')" data-toggle="tooltip" title="Clear"><i class="fa fa-trash"></i></a>
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
            
        <div id="footer" class="bg-light d-flex justify-content-between ">
            <div id="footer-left">
                <a class="btn btn-secondary" onclick="showInfo()"><i class="fa fa-info"></i></a>
    
                <div id="themes" class="dropdown d-sm-inline">
                  <a class="btn btn-secondary dropdown-toggle" id="dropdownbutton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                  <a class="btn btn-secondary dropdown-toggle" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                    
                    <a class="dropdown-item" onclick="switchTo('thread')">
                        Forum thread
                    </a>
                    <a class="dropdown-item" onclick="switchTo('forum-post')">
                        Forum post
                    </a>
                    <a class="dropdown-item" onclick="switchTo('folder')">
                        Folder
                    </a>
                    <a class="dropdown-item" onclick="switchTo('world')">
                        World
                    </a>

                    <a class="dropdown-item" onclick="switchTo('warning')">
                        Warning
                    </a>
                    
                    <a class="dropdown-item" onclick="switchTo('char-gallery')">
                        Character gallery (static)
                    </a>
                    
                    <a class="dropdown-item" onclick="switchTo('char-library')">
                        Character library (static)
                    </a>
                    </div>
                </div>
                
    
                <div id="import" class="dropdown d-sm-inline">
                  <a class="btn btn-secondary dropdown-toggle" id="dropdownbutton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Import from TH
                  </a>
                
                  <div class="dropdown-menu px-2" aria-labelledby="dropdownbutton2">
                      <div class="d-flex flex-column">
                          <div class="d-flex mb-1">
                            <input type="text" id="char-id" class="form-control mr-1 import-button" placeholder="Enter ID"></input>
                             <abbr class="d-inline-block" title="The numerical ID (for a character profile, e.g. 1776660) or username (for a user profile). Page must be public and not have a warning." data-toggle="tooltip">
                                 <i class="fa fa-question-circle"></i>
                             </abbr>
                         </div>
                         <div class="d-flex">
                        <button class="btn btn-primary mr-1 import-button" id="import-meta" onclick="startImport('meta')">Import meta</button>
                        <button class="btn btn-primary mr-1 import-button" id="import-html" onclick="startImport('code')">Import HTML</button>
                        <button class="btn btn-primary" onclick="importedmeta=null; importedcode=null; localStorage.removeItem('th_cj_importedmeta'); localStorage.removeItem('th_cj_importedcode'); switchTo(activemode);">Reset</button>
                        </div>
                    </div>
                  </div>
                </div>
                
                <a id="toggle-sidebar" class="btn btn-secondary" onclick="frame.contentWindow.toggleUI()">
                     <i id='sidebar-toggle-icon' class="fa fa-chevron-left"></i> Sidebar
                </a>
                        
            </div>
            
            <div id="footer-right">
                

                <span class="checkbox-container">
                    <input type="checkbox" id="auto" onchange="setAutoUpdate();" checked="true">&nbsp;<label for="auto">Auto-update</label>
                </span>

                <span class="checkbox-container">
                    <input type="checkbox" class="hide-small" id="vertical" onchange="setVerticalLayout();">&nbsp;<label for="vertical" class="hide-small">Vertical</label>
                </span>

                <div id="ui-options" class="dropdown d-sm-inline">
                    <a class="btn btn-secondary dropdown-toggle" id="dropdownbutton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        UI options 
                    </a>
                    
                    <div class="dropdown-menu ui-options px-2" aria-labelledby="dropdownbutton">

                        <span class="checkbox-container">
                            <input type="checkbox" id="low-contrast" onchange="toggleUITheme();">&nbsp;<label for="low-contrast">Low contrast</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="html-panel" onclick="setHTMLPanel();" checked="true">&nbsp;<label for="html-panel">HTML</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="css-panel" onclick="setCSSPanel();" checked="true">&nbsp;<label for="css-panel">CSS</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="text-panel" onclick="setTextPanel();" checked="true">&nbsp;<label for="text-panel">Scratchpad</label>
                        </span>

                        <span class="checkbox-container">
                            <input type="checkbox" id="big-text" onclick="toggleBigFont();"> <label for="big-text">Big text</label>
                        </span>
                    </div>
                </div>
                <a class="btn btn-primary update-btn d-inline-block" id="update-preview" onclick="updateCode()" href="#">Update preview</a>
            </div>
            
            <input type="file" class="d-none" id="fileupload" onclick="uploadFile(this)">
        </div>
    </div>
    
    <script src="../src/site.js" type="text/javascript"></script>
    
    
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