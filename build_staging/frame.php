<!DOCTYPE html>
<html lang="en">
<head>
    <title>Circlejourney's Toyhouse editor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="An editor for live previewing Toyhouse code.">
    <link rel="icon" href="https://circlejourney.net/resources/images/favicon.png">

    <link href="../src/main.css?cachebust=2" rel="stylesheet">
	<link id="theme-css" href="../src/site_black-forest_20220703.css" rel="stylesheet">
    
    <!-- LIBRARIES -->
    <script src="/src/jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <script src="../src/sass.js-master/dist/sass.js"></script>

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="../src/fontawesome-pro-6.0.0-beta3-web/css/all.min.css">
    <script src="https://kit.fontawesome.com/0ddae54ad8.js" crossorigin="anonymous"></script>
    
    <script src="frame.js?v=<?php echo rand() ?>"></script>
	<style id="custom-css" type="text/css"></style>
</head>
<body>
    <div id="noconflict-main">
        <div id="noconflict-show">
            <nav class="navbar navbar-toggleable-sm navbar-inverse header" data-topbar="" role="navigation" id="header">
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#headerContent" aria-controls="headerContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
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
                            <span class="fa fa-inbox mr-2"></span>0
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

    <script src="../src/site.js" type="text/javascript"></script>

    </body>
</html>