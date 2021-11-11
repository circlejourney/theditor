<!DOCTYPE html>
    <!-- Toyhouse code editor version: 27-12-2020.
         The previous version can be found at th.circlejourney.net/backup_20200913
    -->
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="An editor for live previewing Toyhouse code.">
    <title>Circlejourney's Toyhouse editor</title>
    <link rel="icon" href="https://circlejourney.net/resources/images/favicon.png">
	<link href="src/main.css" rel="stylesheet">
	<link id="theme-css" href="src/site_black-forest.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.4/ace.js" type="text/javascript" charset="utf-8"></script>
    <script src="src/site.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
     
     
    
    <script src="sass.js-master/dist/sass.js">
    </script>
    
    <script src="script.js?4">
    </script>
	
	<style id="custom-css" type="text/css"></style>
	
    <link rel="stylesheet" href="style.css?4">
</head>
<body>
    <div id="loader">
            <img style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);" src="https://media0.giphy.com/media/3oEjI6SIIHBdRxXI40/giphy.gif">
    </div>
    
    <div id="noconflict-info" class="d-none">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h2 class="m-0 w-75 d-inline-block p-2">Welcome to Circlejourney's code editor!</h2>
                <a title="You can view this again by clicking the info button in the bottom left corner." data-toggle="tooltip" class="close" onclick="showInfo()"><i class="fa fa-times"></i></a>
            </div>
            <div class="card-block text-center">
                <p style="font-size: 12pt;">Thanks for checking out my code editor! I'm maintaining and updating it for fun, and it will always be free to use.</p>
                 <p>
                     <a class="btn btn-lg" target="_blank" href="https://ko-fi.com/circlejourney"><i class="fa fa-donate"></i> Ko-fi</a>
                     <a class="btn btn-lg" target="_blank" href="https://toyhou.se/circlejourney"><i class="fas fa-home"></i> My Toyhouse</a>
                     <a class="btn btn-lg" target="_blank" href="https://toyhou.se/~forums/16.htmlcss-graphics/101021.-open-circlejourney-s-code-customs"><i class="fa fa-code"></i> Code customs</a>
                     <a class="btn btn-lg" target="_blank" href="https://toyhou.se/~forums/16.htmlcss-graphics/75580.circlejourney-s-code-creations-free-"><i class="fa fa-laptop-code"></i> Code freebies</a>
                 </p>
                    <p>Created with <a target="_blank" href="https://ace.c9.io/">Ace</a>, <a target="_blank" href="https://github.com/medialize/sass.js/">sass.js</a> and Toyhouse's stylesheets. Feel free to <a href="#">respond to my thread</a> with bug reports and suggestions!
                    Major bugs will be fixed quickly, but suggestions for improvement may take longer to be implemented. (Do note that certain issues cannot be fixed due to limitations, which are listed in detail in the <a data-toggle="collapse" href="#" data-target="#issues">"known issues"</a> section.)</p>
                <p>This editor uses cookies to save your work automatically, and tracks usage statistics. I am not affiliated with Toyhouse, and I make no profit from this code editor. I disclaim responsibility for any loss of work that may arise from usage of my code editor. Do save a copy of your work regularly!</p>
                <p><b>By using my code editor, you agree to all of the above.</b></p>
                <a class="btn btn-primary" onclick="showInfo()">Got it!</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#issues">Show/hide known issues</a>
                <a class="btn btn-secondary" data-toggle="collapse" data-target="#changelog">Show/hide changelog</a>
                <p></p>
                
                <div class="collapse" id="changelog">
                    <div class="card mt-2">
                        <div class="card-block">
                            <p>
                                <b>13 September 2020:</b> Moved all page layout templates to separate files for organisational reasons, added navigation bar.
                            </p>
                            <p>
                                <b>1 June 2020:</b> Copied all Toyhouse CSS and JS files to my server, to prevent cross-origin request errors. They will be manually updated whenever Toyhouse updates its files.</b>
                            </p>
                            <p>
                                <b>11 May 2020:</b> Reorganised layout, option buttons are now in the bottom left corner. Also added a popup containing information about the project and disclaimers.
                            </p>
        
                            <p>
                                <b>14 December 2019:</b> Added a hotfix that slows down the page update rate (when Auto-update is turned on) to prevent webpage display crash errors. May return to this in the future to implement a more elegant solution.
                            </p>
                            
                            <p>
                                <b>5 November 2019:</b> Clicking in the HTML and CSS text areas to bring them into focus now makes the placeholder text disappear.
                            </p>
                            
                            <p>
                                <b>30 October 2019:</b> CSS panel visibility can now be toggled on and off with the CSS panel checkbox.
                            </p>
                            
                            <p>
                                <b>5 September 2019:</b> Fixed bug that made it possible to drag the adjustment bar off the bottom of the page rendering it unreachable.
                            </p>
                            
                            <p>
                                <b>24 June 2019:</b> Added Layout: World page.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="collapse" id="issues">
                    <div class="card mt-2">
                        <div class="card-block">
                        <p><b><i class="fa fa-warning"></i> Some CSS errors cause the webpage to crash.</b> A fix is pending. To avoid these happening, always close your curly braces <span class="code">{}</span> before you start typing in them.</p>
                        <p><b>Right click &gt; Undo doesn't undo changes.</b> Pending a fix. Currently, undoing works with Ctrl + Z and &#8984; + Z.</p>
                        <p><b>Cursor is sometimes visually displaced from actual position.</b> This is a problem with Ace Editor panels not resizing correctly. It is a difficult error to recreate and as such, I have yet to be able to fix it. Currently, refreshing the page will fix it.</p>
                        <p><b>Page switches back to the first tab whenever code is updated.</b> The reason this happens is that every time you update the code, the page refreshes, because the Ace library has no way of telling me exactly where the new characters were inputted, so you're being presented with a fresh version every time. To prevent this happening would probably be very complicated so I'm uncertain if I can change it.</p>
                        <p><b>Some Font Awesome icons do not appear correctly.</b> I cannot fix this as I don't have a pro Font Awesome licence. You will have to upload the code to Toyhouse to see how the FA icons appear there.</p>
                        <p><b>CSS using the @import rule does not work.</b> Again, I cannot fix this: this syntax is used to reference a protected stylesheet on the Toyhouse website, a measure designed to prevent people from stealing them. Without the original CSS code, you can't preview it in this editor.</p>
                        </div>
                    </div>
                </div>
             </div>
         </div>
    </div>
    
    <div id="noconflict-main">
        
        <div id="adjustbar">
        </div>
        
        <div id="editor">
            <div id="html-editor"></div> 
            <div id="css-editor"></div>
            <div id="footer" class="bg-light text-dark d-flex justify-content-between ">
                <div class="flex-grow-1">
                    <a class="btn btn-secondary" href="#" onclick="showInfo()"><i class="fa fa-info"></i></a>
        
                    <div id="themes" class="dropdown d-inline">
                      <a class="btn btn-secondary dropdown-toggle" href="#" id="dropdownbutton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Theme 
                      </a>
                    
                      <div class="dropdown-menu" aria-labelledby="dropdownbutton">
                        <a class="dropdown-item" onclick="toggleTheme('Default')" href="#">Default</a>
                        <a class="dropdown-item" onclick="toggleTheme('Night')" href="#">Night</a>
                        <a class="dropdown-item" onclick="toggleTheme('Pink')" href="#">pink is just the best color sorry i dont make the rules</a>
                        <a class="dropdown-item" onclick="toggleTheme('Teal')" href="#">what??? teal is clearly the superior colour</a>
                        <a class="dropdown-item" onclick="toggleTheme('Bee')" href="#">According to all known laws of aviation, there is no way a b</a>
                        <a class="dropdown-item" onclick="toggleTheme('Pink Velvet')" href="#">Pink Velvet Snake</a>
                      </div>
                    </div>
                    <div id="modes" class="dropdown d-inline">
                      <a class="btn btn-secondary dropdown-toggle" href="#" id="dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Layout
                      </a>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" onclick="switchTo('profile')" href="#">
                            User profile
                        </a>
                        <a class="dropdown-item" onclick="switchTo('char-profile')" href="#">
                            Character profile
                        </a>
                        <a class="dropdown-item" onclick="switchTo('thread')" href="#">
                            Forum thread
                        </a>
                        <a class="dropdown-item" onclick="switchTo('forum-post')" href="#">
                            Forum post
                        </a>
                        <a class="dropdown-item" onclick="switchTo('folder')" href="#">
                            Folder
                        </a>
                        <a class="dropdown-item" onclick="switchTo('world')" href="#">
                            World
                        </a>
                        </div>
                    </div> 
                    <div class="d-inline btn btn-secondary"><a href="/watermark" target="_blank">New: Toyhouse watermark previewer!</a></div>
                </div>
                
                <div>
                <span>
                    <input type="checkbox" id="auto" onchange="setAutoUpdate();" checked="true"> <label for="auto">Auto-update </label>
                </span>
                <span>
                    <input type="checkbox" id="css-panel" onclick="setCSSPanel();" checked="true"> <label for="css-panel">Show CSS panel </label>
                </span>
                <a class="btn btn-primary update-btn" onclick="updateCode()" href="#">Update preview</a>
                </div>
            </div>
        </div>
        
        <div id="show">
            <nav class="navbar navbar-toggleable-sm navbar-inverse header" data-topbar="" role="navigation" id="header">
                        <a class="navbar-brand" href="#">TOYHOU.SE</a>
            <div class="collapse navbar-collapse" id="headerContent">
            
                <ul class="navbar-nav mr-auto navbar-left">
                    <li class="nav-item">
                    <a class="nav-link" href="#">
                    <i class="fa fa-home"></i> Profile
                    </a>
                    </li>
                    
                    <li class="nav-item">
                    <a class="nav-link" href="#">
                    <i class="fa fa-comments"></i> Forums
                    </a>
                    </li>
                    
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-target="#dropdownBrowse"> <span class="fa fa-fw fa-search"></span> Browse </a>
                    <div class="dropdown-menu" id="dropdownBrowse">
                    <h6 class="dropdown-header">Characters</h6>
                    <a class="dropdown-item" href="#"><i class="fa fa-star fa-fw mr-1"></i> Hot </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-binoculars fa-fw mr-1"></i> Feed </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-search mr-1"></i> Browse </a>
                    <h6 class="dropdown-header">Literatures</h6>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-star mr-1"></i> Hot </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-book mr-1"></i> Recent </a>
                    </div>
                    </li>
                    
                    <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-target="#dropdownCreate"> <span class="fa fa-fw fa-plus"></span> Submit </a>
                    <div class="dropdown-menu" id="dropdownCreate">
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-user mr-1"></i> Character </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-newspaper mr-1"></i> Bulletin </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-image mr-1"></i> Image </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-book mr-1"></i> Literature </a>
                    <a class="dropdown-item" href="#"><i class="fa fa-fw fa-globe mr-1"></i> World </a>
                    </div>
                    </li>
                </ul>
            
            
                <ul class="navbar-nav navbar-right">
                    <li class="navbar-notifications">
                        <div class="navbar-notifications-group hidden-md-down">
                            <a href="#" class="btn btn-primary" title="Notifications">
                                <span class="fi-heart"></span>0
                            </a>
                            <a href="#" class="btn btn-primary" title="Communications">
                                <span class="fi-comments"></span>0
                            </a>
                        </div>
                    </li>
                            
                    <li class="nav-item dropdown hidden-lg-up mb-2 mb-md-0">
                        <a class="dropdown-toggle btn btn-primary" data-toggle="dropdown" data-target="#dropdownNotifications">
                            <span class="fa fa-inbox mr-2"></span>339
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" id="dropdownNotifications">
                            <a href="#" class="dropdown-item d-flex justify-content-between" title="Notifications">
                                <span class="dropdown-item-title"><span class="fi-heart mr-2"></span>Notifications</span>
                                <span class="dropdown-item-count badge badge-pill badge-primary badge-notification">
                                0
                                </span>
                            </a>
                            <a href="#" class="dropdown-item d-flex justify-content-between" title="Communications">
                                <span class="dropdown-item-title"><span class="fi-comments mr-2"></span>Communications</span>
                                <span class="dropdown-item-count badge badge-pill badge-primary badge-notification">
                                0
                                </span>
                            </a>
                        </div>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle nav-user-link" data-toggle="dropdown" data-target="#dropdownProfile">
                        <span class="display-user-tiny"><img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6" class="display-user-avatar"><span class="display-user-username">User</span></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" id="dropdownProfile">
                            <a class="dropdown-item" href="#">Profile</a>
                            <a class="dropdown-item" href="#">Characters</a>
                            <a class="dropdown-item" href="#">Inbox</a>
                            <a class="dropdown-item" href="#">Settings</a>
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Manage</h6>
                            <a class="dropdown-item" href="#">Folders</a>
                            <a class="dropdown-item" href="#">Tags</a>
                            <a class="dropdown-item" href="#">Characters</a>
                            <a class="dropdown-item" href="#">Sort Characters</a>
                            <a class="dropdown-item" href="#">Designs</a>
                            <a class="dropdown-item" href="#">Images</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"> Logout </a>
                        </div>
                    </li>
                </ul>
            
            </div>
            </nav>
            
            <section id="display-area" class="mode">
            </section>
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