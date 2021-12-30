<!DOCTYPE html>
<html lang="en">
    <head>
        <?php $lastUpdate = "20211230"; ?>
        
        <script>
            const lastUpdate = <?php echo $lastUpdate ?>;
        </script>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="An editor for live previewing Toyhouse code.">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title>Circlejourney's Toyhouse editor</title>
        <link rel="icon" href="https://circlejourney.net/resources/images/favicon.png">
        
        <!-- Misc libraries -->
    	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.4/ace.js" type="text/javascript" charset="utf-8"></script>
        <script src="../src/sass.js-master/dist/sass.js">
        </script>
        
        <!-- TH source -->
    	<link href="../src/main.css" rel="stylesheet">
    	<script src="../src/site.js"></script>
    	<link id="theme-css" href="../src/site_black-forest.css" rel="stylesheet">
    	
    	<!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/0ddae54ad8.js" crossorigin="anonymous"></script>
        
        <script src="/build_<?php echo $lastUpdate ?>/script.js" type="text/javascript"></script>
        <link rel="stylesheet" href="/build_<?php echo $lastUpdate ?>/style.css">
            
    </head>
    <body>
    
    <div id="loader" style="text-align: center; display: flex; flex-direction: column; justify-content: center;">
        <div>
            <img src="https://media0.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif" style="margin: -50px 0;">
            <p style="font-size: 20pt;">Stuck on the loading screen? <a href="https://toyhou.se/~messages/create/circlejourney">Send me a bug report</a>.</p>
        </div>
    </div>
    
    <div id="info" class="d-none">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="m-0 w-75 d-inline-block p-2">Welcome to Circlejourney's Code Editor!</h2>
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
                    HTML
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
                <div id="html-editor" class="html-visible"></div>
                <div class="css-visible" id="css-editor"></div>
                <div class="text-visible" id="text-editor"></div>
            </div>
        </div>
            
        <div id="footer" class="bg-light text-dark d-flex justify-content-between ">
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
                    Import profile
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
                        
                <a class="hide-small btn btn-primary" href="/watermark" target="_blank">Watermark previewer</a>
            </div>
            
            <div id="footer-right">
                <span>
                    <input type="checkbox" class="hide-small" id="vertical" onchange="setVerticalLayout();"> <label for="vertical" class="hide-small">Vertical layout</label>
                </span>
                
                <span>
                    <input type="checkbox" id="auto" onchange="setAutoUpdate();" checked="true"> <label for="auto">Auto-update</label>
                </span>
                
                <span>
                    <input type="checkbox" id="css-panel" onclick="setCSSPanel();" checked="true"> <label for="css-panel">CSS</label>
                </span>
                
                <span class="show-small"><br></span>
                
                <span>
                    <input type="checkbox" id="text-panel" onclick="setTextPanel();" checked="true"> <label for="text-panel">Scratch pad</label>
                </span>
                <a class="btn btn-primary update-btn d-inline-block" id="update-preview" onclick="updateCode()">Update preview</a>
            </div>
            
            <input type="file" class="d-none" id="fileupload" onclick="uploadFile(this)">
        </div>
    </div>
    
    
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