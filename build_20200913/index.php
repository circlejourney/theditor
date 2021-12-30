<!DOCTYPE html>
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
     
     
    
    <script src="../src/sass.js-master/dist/sass.js">
    </script>
    
    <script src="./script.js<?php echo '?'.rand();?>">
    </script>
	
	<style id="custom-css" type="text/css"></style>
	
    <link rel="stylesheet" href="./style.css<?php echo '?'.rand();?>">
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
                    <p>Created with <a target="_blank" href="https://ace.c9.io/">Ace</a>, <a target="_blank" href="https://github.com/medialize/sass.js/">sass.js</a> and Toyhouse's stylesheets. Feel free to <a href="https://toyhou.se/~forums/16.htmlcss-graphics/107219.-toyhouse-code-editor-with-live-preview">respond to my thread</a> with bug reports and suggestions!
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
                                <b>1 June 2020: Copied all Toyhouse CSS and JS files to my server, to prevent cross-origin request errors. They will be manually updated whenever Toyhouse updates its files.</b>
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
            <section id="forum-post" class="mode d-none">
                <div id="main" class="clearfix container-fluid main-container full-sidebar">
                    <div class="row row-offcanvas row-offcanvas-left" style="min-height: 647px;">
                        <div class="col-md-3 col-lg-2 sidebar sidebar-offcanvas forum-sidebar" id="sidebar">
                            <ul class="side-nav list-unstyled">
                            <li class="header">
                                <a id="menu-toggle" href="#">
                                Forums <span class="forum-sidebar-main-icon fa fa-align-justify tooltipster" data-placement="right" title="" data-original-title="Toggle Menu"></span>
                                </a>
                            </li>
                            <li class="subheader hide-on-collapse">
                                Characters &amp; RP
                                <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Characters &amp; RP"></span>
                            </li>
                            <li class=" hide-on-collapse">
                                <a href="#">
                                    Character Discussion
                                    <span class="forum-sidebar-sub-icon fa fa-user-circle tooltipster" data-placement="right" title="" data-original-title="Character Discussion"></span>
                                </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Species Discussion
                            <span class="forum-sidebar-sub-icon fa fa-users tooltipster" data-placement="right" title="" data-original-title="Species Discussion"></span>
                            </a>
                            </li>
                             <li class=" hide-on-collapse">
                            <a href="#">
                            Worlds
                            <span class="forum-sidebar-sub-icon fa fa-globe tooltipster" data-placement="right" title="" data-original-title="Worlds"></span>
                            </a>
                            </li>
                            <li class="subheader hide-on-collapse">
                            Commerce
                            <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Commerce"></span>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Art Marketplace
                            <span class="forum-sidebar-sub-icon fa fa-paint-brush tooltipster" data-placement="right" title="" data-original-title="Art Marketplace"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Design Marketplace
                            <span class="forum-sidebar-sub-icon fa fa-gift tooltipster" data-placement="right" title="" data-original-title="Design Marketplace"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Adoption Center
                            <span class="forum-sidebar-sub-icon fa fa-exchange tooltipster" data-placement="right" title="" data-original-title="Adoption Center"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Service Reviews
                            <span class="forum-sidebar-sub-icon far fa-star-half tooltipster" data-placement="right" title="" data-original-title="Service Reviews"></span>
                            </a>
                            </li>
                            <li class="subheader hide-on-collapse">
                            Creative
                            <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Creative"></span>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Creator's Corner
                            <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Creator's Corner"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            HTML/CSS &amp; Graphics
                            <span class="forum-sidebar-sub-icon fa fa-code tooltipster" data-placement="right" title="" data-original-title="HTML/CSS &amp; Graphics"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Art Freebies
                            <span class="forum-sidebar-sub-icon far fa-heart tooltipster" data-placement="right" title="" data-original-title="Art Freebies"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Art Trades
                            <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Art Trades"></span>
                            </a>
                            </li>
                            <li class="subheader hide-on-collapse">
                            Forum Games
                            <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Forum Games"></span>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Art Games
                            <span class="forum-sidebar-sub-icon fa fa-coffee tooltipster" data-placement="right" title="" data-original-title="Art Games"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Character Games &amp; Freebies
                            <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Character Games &amp; Freebies"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Forum Games
                            <span class="forum-sidebar-sub-icon fa fa-coffee tooltipster" data-placement="right" title="" data-original-title="Forum Games"></span>
                            </a>
                            </li>
                            <li class="subheader hide-on-collapse">
                            General &amp; Site
                            <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="General &amp; Site"></span>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Announcements
                            <span class="forum-sidebar-sub-icon fa fa-bullhorn tooltipster" data-placement="right" title="" data-original-title="Announcements"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            General &amp; Off-Topic
                            <span class="forum-sidebar-sub-icon far fa-comment-dots tooltipster" data-placement="right" title="" data-original-title="General &amp; Off-Topic"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Help &amp; Questions
                            <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Help &amp; Questions"></span>
                            </a>
                            </li>
                            <li class=" hide-on-collapse">
                            <a href="#">
                            Suggestions &amp; Bugs
                            <span class="forum-sidebar-sub-icon fa fa-bug tooltipster" data-placement="right" title="" data-original-title="Suggestions &amp; Bugs"></span>
                            </a>
                            </li>
                            <li class="subheader hide-on-collapse">
                            Tools
                            <span class="forum-sidebar-sub-icon fa fa-cog tooltipster" data-placement="right" title="" data-original-title="Tools"></span>
                            </li>
                            <li class="">
                            <a href="#">
                            Recent Activity
                            <span class="forum-sidebar-sub-icon far fa-clock tooltipster" data-placement="right" title="" data-original-title="Recent Activity"></span>
                            </a>
                            </li>
                            <li class="">
                            <a href="#">
                            Subscriptions
                            <span class="forum-sidebar-sub-icon fa fa-star tooltipster" data-placement="right" title="" data-original-title="Subscriptions"></span>
                            </a>
                            </li>
                            <li class="">
                            <a href="#">
                            My Threads
                            <span class="forum-sidebar-sub-icon fa fa-reply tooltipster" data-placement="right" title="" data-original-title="My Threads"></span>
                            </a>
                            </li>
                            <li class="">
                            <a href="#">
                            My Posts
                            <span class="forum-sidebar-sub-icon fa fa-reply-all tooltipster" data-placement="right" title="" data-original-title="My Posts"></span>
                            </a>
                            </li>
                        </ul>
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-10 content-main" id="content">
                            <div class="thread-wrapper">
                                <div class="forum-post">
                                    <div class="row forum-post-post">
                                        <div class="col-sm-2 hidden-xs-down forum-post-avatar">
                                            <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6">
                                        </div>
                                        <div class="forum-post-body col-sm-10 ">
                                            <div class="forum-post-caption clearfix">
                                                <div class="hidden-sm-up forum-post-avatar small-avatar">
                                                    <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6" class="">
                                                </div>
                                                <div class=" post-header-1424528">
                                                    <span class=" forum-post-user-badge"><a href="#" class="btn btn-sm btn-default user-name-badge"><i class="fi-torso user-name-icon"></i>User</a></span>
                                                </div>
                                            </div>
                                        
                                            <div class="card forum-post-content">
                                                <div class="card-block bg-faded user-content fr-view post-body-2102666 ace-code-container">
                                                </div>
                                            </div>
                                        </div>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section id="thread" class="mode d-none">
                <div id="main" class="clearfix container-fluid main-container full-sidebar">
                    <div class="row row-offcanvas row-offcanvas-left" data-sticky-wrap="" style="min-height: 647px;">
                        <div class="col-md-3 col-lg-2 sidebar sidebar-offcanvas forum-sidebar" id="sidebar">
                        <ul class="side-nav list-unstyled">
                        <li class="header">
                        <a id="menu-toggle" href="#">
                        Forums <span class="forum-sidebar-main-icon fa fa-align-justify tooltipster" data-placement="right" title="" data-original-title="Toggle Menu"></span>
                        </a>
                        </li>
                        <li class="subheader hide-on-collapse">
                        Characters &amp; RP
                        <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Characters &amp; RP"></span>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Character Discussion
                        <span class="forum-sidebar-sub-icon fa fa-user-circle tooltipster" data-placement="right" title="" data-original-title="Character Discussion"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Species Discussion
                        <span class="forum-sidebar-sub-icon fa fa-users tooltipster" data-placement="right" title="" data-original-title="Species Discussion"></span>
                        </a>
                        </li>
                         <li class=" hide-on-collapse">
                        <a href="#">
                        Worlds
                        <span class="forum-sidebar-sub-icon fa fa-globe tooltipster" data-placement="right" title="" data-original-title="Worlds"></span>
                        </a>
                        </li>
                        <li class="subheader hide-on-collapse">
                        Commerce
                        <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Commerce"></span>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Art Marketplace
                        <span class="forum-sidebar-sub-icon fa fa-paint-brush tooltipster" data-placement="right" title="" data-original-title="Art Marketplace"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Design Marketplace
                        <span class="forum-sidebar-sub-icon fa fa-gift tooltipster" data-placement="right" title="" data-original-title="Design Marketplace"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Adoption Center
                        <span class="forum-sidebar-sub-icon fa fa-exchange tooltipster" data-placement="right" title="" data-original-title="Adoption Center"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Service Reviews
                        <span class="forum-sidebar-sub-icon far fa-star-half tooltipster" data-placement="right" title="" data-original-title="Service Reviews"></span>
                        </a>
                        </li>
                        <li class="subheader hide-on-collapse">
                        Creative
                        <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Creative"></span>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Creator's Corner
                        <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Creator's Corner"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        HTML/CSS &amp; Graphics
                        <span class="forum-sidebar-sub-icon fa fa-code tooltipster" data-placement="right" title="" data-original-title="HTML/CSS &amp; Graphics"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Art Freebies
                        <span class="forum-sidebar-sub-icon far fa-heart tooltipster" data-placement="right" title="" data-original-title="Art Freebies"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Art Trades
                        <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Art Trades"></span>
                        </a>
                        </li>
                        <li class="subheader hide-on-collapse">
                        Forum Games
                        <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="Forum Games"></span>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Art Games
                        <span class="forum-sidebar-sub-icon fa fa-coffee tooltipster" data-placement="right" title="" data-original-title="Art Games"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Character Games &amp; Freebies
                        <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Character Games &amp; Freebies"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Forum Games
                        <span class="forum-sidebar-sub-icon fa fa-coffee tooltipster" data-placement="right" title="" data-original-title="Forum Games"></span>
                        </a>
                        </li>
                        <li class="subheader hide-on-collapse">
                        General &amp; Site
                        <span class="forum-sidebar-sub-icon fa fa-th-list tooltipster" data-placement="right" title="" data-original-title="General &amp; Site"></span>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Announcements
                        <span class="forum-sidebar-sub-icon fa fa-bullhorn tooltipster" data-placement="right" title="" data-original-title="Announcements"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        General &amp; Off-Topic
                        <span class="forum-sidebar-sub-icon far fa-comment-dots tooltipster" data-placement="right" title="" data-original-title="General &amp; Off-Topic"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Help &amp; Questions
                        <span class="forum-sidebar-sub-icon fa fa-comment tooltipster" data-placement="right" title="" data-original-title="Help &amp; Questions"></span>
                        </a>
                        </li>
                        <li class=" hide-on-collapse">
                        <a href="#">
                        Suggestions &amp; Bugs
                        <span class="forum-sidebar-sub-icon fa fa-bug tooltipster" data-placement="right" title="" data-original-title="Suggestions &amp; Bugs"></span>
                        </a>
                        </li>
                        <li class="subheader hide-on-collapse">
                        Tools
                        <span class="forum-sidebar-sub-icon fa fa-cog tooltipster" data-placement="right" title="" data-original-title="Tools"></span>
                        </li>
                        <li class="">
                        <a href="#">
                        Recent Activity
                        <span class="forum-sidebar-sub-icon far fa-clock tooltipster" data-placement="right" title="" data-original-title="Recent Activity"></span>
                        </a>
                        </li>
                        <li class="">
                        <a href="#">
                        Subscriptions
                        <span class="forum-sidebar-sub-icon fa fa-star tooltipster" data-placement="right" title="" data-original-title="Subscriptions"></span>
                        </a>
                        </li>
                        <li class="">
                        <a href="#">
                        My Threads
                        <span class="forum-sidebar-sub-icon fa fa-reply tooltipster" data-placement="right" title="" data-original-title="My Threads"></span>
                        </a>
                        </li>
                        <li class="">
                        <a href="#">
                        My Posts
                        <span class="forum-sidebar-sub-icon fa fa-reply-all tooltipster" data-placement="right" title="" data-original-title="My Posts"></span>
                        </a>
                        </li>
                        </ul>
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-10 content-main" id="content">
                            <ul class="breadcrumb profanity-filter">
                            <li class="breadcrumb-item">
                            <a href="#"><span>Forums</span></a> </li>
                            <li class="breadcrumb-item">
                            <a href="#"><span>Forum Name</span></a> </li>
                            <li class="breadcrumb-item">
                            <span>Thread Placeholder Title</span> </li>
                            </ul>
                            <div class="thread-wrapper">
                            <div class="thread-content">
                            <div class="thread-header"> 
                                <div class="thread-header-content row">
                                    <div class="thread-header-avatar col-sm-2 col-5">
                                        <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6" class="thread-avatar">
                                    </div>
                                    <div class="thread-caption col-sm-10 col-12">
                                        <h1 class="thread-title">
                                        <a href="#">Thread Placeholder Title</a>
                                        </h1>
                                        <div class="small thread-author">
                                            Posted <abbr class="tooltipster datetime" title="" data-original-title="1 Jan 1900, 0:00:00 am">0 days, 0 hours ago</abbr> by
                                            <span class="thread-user-badge"><a href="#" class="btn btn-sm btn-default user-name-badge"><i class="fi-torso user-name-icon"></i>User</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="thread-post">
                                <div class="card">
                                    <div class="card-block bg-faded fr-view ace-code-container">
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section id="profile" class="mode d-none">
                <div id="main" class="clearfix container-fluid main-container full-sidebar">
                <div class="row row-offcanvas row-offcanvas-left" data-sticky-wrap="" style="min-height: 100%;">
                    <div class="col-md-3 col-lg-2 sidebar sidebar-offcanvas" id="sidebar">
                        <ul class="side-nav list-unstyled">
                        <li class="header"><i class="fi-torso header-icon"></i>User</li>
                        <li><span class="display-user"><a href="#"><img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6?0" class="display-user-avatar"><span class="display-user-username">User</span></a></span></li>
                        <li class="divider sidebar-divider-username"></li>
                        <li class=" sidebar-li-bulletins">
                        <a href="#">
                        <i class="far fa-newspaper fa-fw sidebar-icon"></i>Bulletins
                        </a>
                        </li>
                        <li class=" sidebar-li-characters">
                        <a href="#">
                        <i class="fa fa-users fa-fw sidebar-icon"></i>Characters
                        </a>
                        </li>
                        <li class=" sidebar-li-links">
                        <a href="#">
                        <i class="fa fa-link fa-fw sidebar-icon"></i>Links
                        </a>
                        </li>
                        <li class=" sidebar-li-worlds">
                        <a href="#">
                        <i class="fa fa-globe fa-fw sidebar-icon"></i>Worlds
                        </a>
                        </li>
                        <li class=" sidebar-li-favorites">
                        <a href="#">
                        <i class="fa fa-star fa-fw sidebar-icon"></i>Favorites
                        </a>
                        </li>
                        <li class="divider sidebar-divider-collections"></li>
                        <li class=" sidebar-li-created">
                        <a href="#">
                        <i class="fa fa-palette fa-fw sidebar-icon"></i>Designs
                        </a>
                        </li>
                        <li class=" sidebar-li-art">
                        <a href="#">
                        <i class="fa fa-paint-brush fa-fw sidebar-icon"></i>Art
                        </a>
                        </li>
                        <li class=" sidebar-li-literatures">
                        <a href="#">
                        <i class="fa fa-book fa-fw sidebar-icon"></i>Library
                        </a>
                        </li>
                        <li class="divider sidebar-divider-stats"></li>
                        <li class=" sidebar-li-comments">
                        <a href="#">
                        <i class="fa fa-comment fa-fw sidebar-icon"></i>0 Comments
                        </a>
                        </li>
                        <li class=" sidebar-li-stats">
                        <a href="#">
                        <i class="fa fa-chart-bar fa-fw sidebar-icon"></i>Stats
                        </a>
                        </li>
                        </ul>
                        </div>
                        <div class="col-sm-12 col-md-9 col-lg-10 content-main" id="content">
                            <div class="character-profile">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="profile-section profile-content-section user-content fr-view">
                                            <div class="profile-content-content user-content fr-view ace-code-container">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section id="char-profile" class="mode d-none">
                <div id="main" class="clearfix container-fluid main-container full-sidebar">
                <div class="row row-offcanvas row-offcanvas-left" data-sticky-wrap="" style="min-height: 100%;"><div class="col-md-3 col-lg-2 sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="side-nav list-unstyled">
                    <li class="header"><i class="fa fa-heart mr-2"></i> Character</li>
                    <li class="user-name"><span class="display-user"><a href="#"><img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6?0" class="display-user-avatar"><span class="display-user-username">User</span></a></span></li>
                    <li class="character-folder subheader">
                    <a href="#"><i class="mr-1 fa fa-folder"></i>Folder</a>
                    </li>
                    <li class="character-name">
                    <span class="display-character"><a href="#"><img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6" class="mr-2">Character Name</a></span>
                    </li>
                    
                    <li class="divider sidebar-divider-collections"></li>
                    <li class=" sidebar-li-gallery">
                    <a href="#">
                    <i class="far fa-image fa-fw sidebar-icon"></i>Gallery
                    <span class="pull-right sidebar-stat sidebar-stat-gallery">0</span>
                    </a>
                    </li>
                    <li class=" sidebar-li-library">
                    <a href="#">
                    <i class="fa fa-book fa-fw sidebar-icon"></i>Library
                    </a>
                    </li>
                    <li class=" sidebar-li-worlds">
                    <a href="#">
                    <i class="fa fa-globe fa-fw sidebar-icon"></i>Worlds
                    </a>
                    </li>
                    <li class=" sidebar-li-links">
                    <a href="#">
                    <i class="fa fa-link fa-fw sidebar-icon"></i>Links
                    </a>
                    </li>
                    <li class="divider sidebar-divider-interactions"></li>
                    <li class="  sidebar-li-comments">
                    <a href="#">
                    <i class="fa fa-comment fa-fw sidebar-icon"></i>Comments
                    </a>
                    </li>
                    <li class="sidebar-li-favorites" data-url="#" th-favorite="1">
                    <a href="#">
                    <i class="far fa-star fa-fw sidebar-icon"></i>Favorite
                    <span class="pull-right sidebar-stat sidebar-stat-favorites th-favorite-count">0</span>
                    </a>
                    </li>
                    <li class="divider sidebar-divider-ownership"></li>
                    <li class=" sidebar-li-ownership">
                    <a href="#">
                    <i class="far fa-check-square fa-fw sidebar-icon"></i>Ownership
                    </a>
                    </li>
                    <li class="divider sidebar-divider-mod"></li>
                    <li class="sidebar-li-report">
                    <a href="#">
                    <i class="fa fa-exclamation-triangle fa-fw sidebar-icon"></i>Report
                    </a>
                    </li>
                    <li class="sidebar-li-block">
                    <a href="#" th-modal-trigger="">
                    <i class="fa fa-ban fa-fw sidebar-icon"></i>Block
                    </a>
                    </li>
                    </ul>
                    </div>
                        <div class="col-sm-12 col-md-9 col-lg-10 content-main" id="content">
                            <div class="character-profile">
                                <div class="row profile-header">
        
                            <div class="col-lg-6 col-12 profile-section profile-name-section">
                            <div class="img-thumbnail"> 
                            <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6?0" class="profile-name-icon">
                            </div>
                            <div class="profile-name-info">
                            <h1 class="display-4">Character Name</h1>
                            <h2><span class="display-user"><a href="#"><i class="fi-torso user-name-icon"></i>User</a></span></h2>
                            </div>
                            </div>
                            
                            
                            <div class="col-lg-6 col-12 profile-section profile-info-section">
                            <div class="card">
                            <div class="card-block bg-faded">
                            <div class="profile-info-title hidden-lg-up"><h2>Info</h2><hr></div>
                            <div class="profile-info-content row">
                            <div class="profile-stats-content col-lg-8 col-md-6 col-12">
                            <dl class="fields">
                            <div class="row fields-field">
                            <dt class="field-title col-sm-4">
                            Created
                            </dt>
                            <dd class="field-value col-sm-8">
                            <abbr class="tooltipster datetime" title="" data-original-title="1 Jan 1900, 0:00:00 am">0 years, 0 months ago</abbr>
                            </dd>
                            </div>
                            <div class="row fields-field">
                            <dt class="field-title col-sm-4">
                            Creator
                            </dt>
                            <dd class="field-value col-sm-8">
                            <span class="display-user"><a href="#"><i class="fi-torso user-name-icon"></i>User</a></span>
                            </dd>
                            </div>
                            <div class="row fields-field">
                            <dt class="field-title col-sm-4">
                            Favorites
                            </dt>
                            <dd class="field-value col-sm-8">
                            <a href="#" th-modal-trigger="">0</a>
                            </dd>
                            </div>
                            </dl>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12 profile-tags-content">
                                <a href="#" class="badge badge-primary badge-pill">tag 1</a>
                                <a href="#" class="badge badge-primary badge-pill">tag 2</a>
                            
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            
                            </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="profile-section profile-content-section user-content fr-view ace-code-container">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <section id="folder" class="mode d-none">
                <div id="main" class="clearfix container-fluid main-container full-sidebar">
                    <div class="row row-offcanvas row-offcanvas-left" data-sticky-wrap="" style="min-height: 647px;">
                    <div class="col-md-3 col-lg-2 sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="side-nav list-unstyled">
                    <li class="header"><i class="fi-torso header-icon"></i>User</li>
                    <li><span class="display-user"><a href="#"><img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6" class="display-user-avatar"><span class="display-user-username">User</span></a></span></li>
                    <li class="divider sidebar-divider-username"></li>
                    <li class=" sidebar-li-bulletins">
                    <a href="#">
                    <i class="far fa-newspaper fa-fw sidebar-icon"></i>Bulletins
                    </a>
                    </li>
                    <li class="active sidebar-li-characters">
                    <a href="#">
                    <i class="fa fa-users fa-fw sidebar-icon"></i>Characters
                    </a>
                    </li>
                    <li class=" sidebar-li-links">
                    <a href="#">
                    <i class="fa fa-link fa-fw sidebar-icon"></i>Links
                    </a>
                    </li>
                    <li class=" sidebar-li-worlds">
                    <a href="#">
                    <i class="fa fa-globe fa-fw sidebar-icon"></i>Worlds
                    </a>
                    </li>
                    <li class=" sidebar-li-favorites">
                    <a href="#">
                    <i class="fa fa-star fa-fw sidebar-icon"></i>Favorites
                    </a>
                    </li>
                    <li class="divider sidebar-divider-collections"></li>
                    <li class=" sidebar-li-created">
                    <a href="#">
                    <i class="fa fa-palette fa-fw sidebar-icon"></i>Designs
                    </a>
                    </li>
                    <li class=" sidebar-li-art">
                    <a href="#">
                    <i class="fa fa-paint-brush fa-fw sidebar-icon"></i>Art
                    </a>
                    </li>
                    <li class=" sidebar-li-literatures">
                    <a href="#">
                    <i class="fa fa-book fa-fw sidebar-icon"></i>Library
                    </a>
                    </li>
                    <li class="divider sidebar-divider-stats"></li>
                    <li class=" sidebar-li-comments">
                    <a href="#">
                    <i class="fa fa-comment fa-fw sidebar-icon"></i>0 Comments
                    </a>
                    </li>
                    <li class=" sidebar-li-stats">
                    <a href="#">
                    <i class="fa fa-chart-bar fa-fw sidebar-icon"></i>Stats
                    </a>
                    </li>
                    </ul>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-10 content-main" id="content">
                    <div class="user-characters" id="folder-page-854630">
                    <div class="characters-header">
                    <div class="characters-folders-top">
                    <a href="#" class="btn btn-default btn-sm">
                    <i class="fi-arrow-left"></i> Back
                    </a>
                    <a href="#" class="btn btn-default btn-sm">
                    <i class="fi-folder"></i> View Unsorted
                    </a>
                    <a href="#" class="btn btn-default btn-sm">
                    <i class="fi-folder"></i> View All
                    </a>
                    </div>
                    <h1 class="characters-title">
                    <i class="fi-folder"></i>
                    Folder Name
                    </h1>
                    <hr>
                    </div>
                    
                    <div class="characters-folder-description">
                    <div class="characters-folder-description-header">
                    <h4>
                    <i class="mr-1 fa fa-folder"></i>Folder Name
                    </h4>
                    </div>
                    <div class="characters-folder-description-content user-content fr-view ace-code-container">
                    </div>
                    </div>
                    <hr>
                    <div class="characters-filterby text-right">
                        <span class="characters-order-text"><strong>Order by: </strong></span>
                        <span class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" aria-haspopup="false" aria-expanded="false">
                        Default
                        </button>
                        </span>
                        <span class="characters-tags-text"><strong>Tags: </strong></span> <a href="#" class="characters-tags-view btn btn-default">
                        Show
                        </a>
                        </div>
                        
                    <div class="user-characters-gallery characters-gallery">
                            <div class="gallery-row">
                            
                            <div class="text-center gallery-thumb character-thumb gallery-item">
                                <div class="thumb-image">
                                <a href="#" class="img-thumbnail">
                                <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6">
                                </a>
                                </div>
                                <div class="thumb-caption">
                                    <span class="thumb-character-name"><a href="#" class="btn btn-sm btn-primary character-name-badge">Character Name</a></span>
                                    <div class="small thumb-character-stats text-center">
                                    <a href="#" class="hide">
                                    <span class="thumb-character-stat favorites favorited" title="Favorites">
                                    <i class="fi-star"></i><span class="th-favorite-count">0</span>
                                    </span>
                                    </a>
                                    <a href="#" class="">
                                    <span class="thumb-character-stat favorites" title="Favorites">
                                    <i class="fi-star"></i><span class="th-favorite-count">0</span>
                                    </span>
                                    </a>
                                    <a class="thumb-character-stat images" href="#" title="Images">
                                    <i class="fi-photo"></i>0
                                    </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center gallery-thumb character-thumb gallery-item">
                                <div class="thumb-image">
                                <a href="#" class="img-thumbnail">
                                <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6">
                                </a>
                                </div>
                                <div class="thumb-caption">
                                    <span class="thumb-character-name"><a href="#" class="btn btn-sm btn-primary character-name-badge">Character Name</a></span>
                                    <div class="small thumb-character-stats text-center">
                                    <a href="#" class="hide">
                                    <span class="thumb-character-stat favorites favorited" title="Favorites">
                                    <i class="fi-star"></i><span class="th-favorite-count">0</span>
                                    </span>
                                    </a>
                                    <a href="#" class="">
                                    <span class="thumb-character-stat favorites" title="Favorites">
                                    <i class="fi-star"></i><span class="th-favorite-count">0</span>
                                    </span>
                                    </a>
                                    <a class="thumb-character-stat images" href="#" title="Images">
                                    <i class="fi-photo"></i>0
                                    </a>
                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>
                    </div>
                    </div>
                    </div>
                    </div>
            </section>
            <section id="world" class="mode d-none">
                <div id="main" class="clearfix container-fluid main-container full-sidebar">
                    <div class="row row-offcanvas row-offcanvas-left" data-sticky-wrap="" style="min-height: 647px;">
                    <div class="col-md-3 col-lg-2 sidebar sidebar-offcanvas" id="sidebar">
                    <ul class="side-nav list-unstyled">
                    <li class="header"><i class="fi-web header-icon"></i>World</li>
                    <li><span class="display-group"><a href="#"><img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6">&nbsp;World Name</a></span></li>
                    <li class="  sidebar-li-bulletins">
                    <a href="#"><i class="fa fa-fw fa-newspaper sidebar-icon"></i>Bulletins</a>
                    </li>
                    <li class=" sidebar-li-characters">
                    <a href="#"><i class="fa fa-fw fa-user sidebar-icon"></i>Characters</a>
                    </li>
                    <li class="  sidebar-li-forums">
                    <a href="#"><i class="fa fa-fw fa-comments sidebar-icon"></i>Forums</a>
                    </li>
                    <li class=" sidebar-li-members">
                    <a href="#"><i class="fa fa-fw fa-users sidebar-icon"></i>Members</a>
                    </li>
                    <li class="divider sidebar-divider-interaction"></li>
                    <li class=" sidebar-li-comments">
                    <a href="#">
                    <i class="fa fa-fw fa-comment sidebar-icon"></i>Comments
                    </a>
                    </li>
                    <li class="subheader subheader-margin world-functions-subheader">
                    <a href="#">
                    <i class="fa fa-wrench sidebar-icon"></i>World Functions
                    </a>
                    </li>
                    <li class="sidebar-li-submit-character">
                    <a href="#">
                    <i class="fa fa-fw fa-plus sidebar-icon"></i>Submit Character
                    </a>
                    </li>
                    <li class="divider sidebar-divider-manage"></li>
                    <li class="subnavigation-header    ">
                    <a href="#" class="subnavigation-header-link">
                    Mod Panel
                    <span class="subnavigation-header-icon fa fa-caret-right subnavigation-header-icon-closed"></span>
                    <span class="subnavigation-header-icon fa fa-caret-down subnavigation-header-icon-open"></span>
                    </a>
                    <ul>
                    <li class=" sidebar-li-inbox">
                    <a href="#">
                    <i class="fa fa-fw fa-envelope sidebar-icon"></i>Inbox (0)
                    </a>
                    </li>
                    <li class=" sidebar-li-create-bulletin">
                    <a href="#">
                    <i class="fa fa-fw fa-newspaper sidebar-icon"></i>Create Bulletin
                    </a>
                    </li>
                    <li class=" sidebar-li-edit-characters">
                    <a href="#">
                    <i class="fa fa-fw fa-user-cog sidebar-icon"></i>Manage Characters
                    </a>
                    </li>
                    <li class=" sidebar-li-edit-members">
                    <a href="#">
                    <i class="fa fa-fw fa-users-cog sidebar-icon"></i>Manage Members
                    </a>
                    </li>
                    </ul>
                    </li>
                    <li class="divider sidebar-divider-mod"></li>
                    <li class="subnavigation-header      ">
                    <a href="#" class="subnavigation-header-link">
                    Admin Panel
                    <span class="subnavigation-header-icon fa fa-caret-right subnavigation-header-icon-closed"></span>
                    <span class="subnavigation-header-icon fa fa-caret-down subnavigation-header-icon-open"></span>
                    </a>
                    <ul>
                    <li class=" sidebar-li-folders">
                    <a href="#">
                    <i class="fa fa-fw fa-folder sidebar-icon"></i>Manage Folders
                    </a>
                    </li>
                    <li class=" sidebar-li-edit-pages">
                    <a href="#">
                    <i class="fa fa-fw fa-file sidebar-icon"></i>Manage Pages
                    </a>
                    </li>
                    <li class=" sidebar-li-edit-forums">
                    <a href="#">
                    <i class="fa fa-fw fa-comments sidebar-icon"></i>Manage Forums
                    </a>
                    </li>
                    <li class=" sidebar-li-edit-world">
                    <a href="#">
                    <i class="fa fa-fw fa-pencil sidebar-icon"></i>Manage World
                    </a>
                    </li>
                    <li class=" sidebar-li-edit-world">
                    <a href="#">
                    <i class="fa fa-fw fa-image sidebar-icon"></i>Manage Avatar
                    </a>
                    </li>
                    <li class=" sidebar-li-settings">
                    <a href="#">
                    <i class="fa fa-fw fa-wrench sidebar-icon"></i>World Settings
                    </a>
                    </li>
                    </ul>
                    </li>
                    <li class="divider sidebar-divider-admin"></li>
                    </ul>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-10 content-main" id="content">
                    <div class="group-profile">
                    
                    <div class="profile-section profile-name-section">
                    <div class="img-thumbnail">
                    <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6" class="profile-name-icon">
                    </div>
                    <div class="profile-name-info">
                    <h1 class="display-4"><a href="#"><i class="fa fa-globe"></i>&nbsp;World Name</a></h1>
                     </div>
                    </div>
                    
                    <div class="row">
                    <div class="col-lg-12">
                    <div class="col-12 profile-section profile-content-section user-content fr-view ace-code-container">
                    </div> </div>
                    </div>
                    <div class="row">
                    <div class="col-lg-12">
                    <div class="profile-section profile-posts-section">
                    <div class="profile-posts-header">
                    <h1 class="profile-posts-title">Recent Forum Activity</h1>
                    <hr>
                    </div>
                    <div class="forum-view-table-wrapper">
                    <table class="forum-view-table table table-hover table-striped">
                    <tbody>
                    <tr class="row forum-thread-row ">
                    <td class="col-6">
                    <h3 class="forum-thread-title">
                    <a href="#">
                    Test
                    </a>
                    </h3>
                    <div class="forum-thread-info small">
                    Posted by <span class="display-user"><a href="#"><i class="fi-torso user-name-icon"></i>User</a></span> <abbr class="tooltipster datetime" title="" data-original-title="1 Jan 1900, 0:00:00 am">0 months, 0 day ago</abbr>
                    in <a href="#">Forum Name</a> </div>
                    </td>
                    <td class="col-2">
                        <div class="forum-thread-posts">0 Posts</div>
                    </td>
                    <td class="col-4">
                    <div class="forum-last-update">
                    <div class="forum-last-update-content">
                    <div class="forum-last-update-image">
                    <img src="https://f2.toyhou.se/file/f2-toyhou-se/users/admin?6">
                    </div>
                    <div class="forum-last-update-info">
                    <div class="forum-last-update-date">
                    <a href="#"> <abbr class="tooltipster datetime" title="" data-original-title="1 Jan 1900, 0:00:00 am">0 months, 0 day ago</abbr></a>
                    </div>
                    <div class="forum-last-update-author">
                    <span class="display-user"><a href="#"><i class="fi-torso user-name-icon"></i>User</a></span>
                    </div>
                    </div>
                    </div>
                    </div> </td>
                    </tr>
                    </tbody>
                    </table>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    </div>
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