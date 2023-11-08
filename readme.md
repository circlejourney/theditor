# Toyhouse Live Code Editor
An editor with live preview for [Toyhouse](https://toyhou.se) themes. View changes as you type, in various site skins/themes and page layouts.

## Previewing on local server
To preview a local copy of the code, you will need to Install [XAMPP](https://www.apachefriends.org/) from Apache Friends. Once you add XAMPP's programme directory to your PATH variable, navigate to the theditor root directory in the command line and start a local server:

> `php -S localhost:8000`

Once you've started up the server, you can view a local preview of the code editor by navigating to the url `localhost:8000` in your web browser.

## Directory tree
- **root**: Every file in the root is meant update across all versions of the editor, so for example clicking the changelog button on an older version of the code editor still brings up the latest changelog. Other files do live here in the production version, but only changelog.html is included as this is the only file I expect will need to be edited during development. There is also a dummy index.php that simply pulls the contents from the build_staging folder's index.php.
- **build_staging**: contains all files related to the direct functioning of the editor. Overwhelmingly, this is where you'll be making changes. script.js contains the functionality of the code editor UI, and frame.js contains the functionality of the preview frame. The frame DOM element can be accessed from script.js with the `frame` object; you can get the frame's window object (and access all its functions etc.) with `frame.contentWindow`. Conversely, frame.js can interact with the UI window through the `parent` object.
- **src**: contains all project dependencies and source files from Toyhouse. Includes various Toyhouse theme CSS files. Files in this folder should generally not change unless the external sources change (such as if Toyhouse updates their code).
- **templates**: HTML templates for layouts. These file names are referenced by frame.js > `switchTo()` and any new layouts should also be added to the layout menu in index.php.

# Dependencies and copyright
This editor uses [jQuery](https://code.jquery.com/), [Ace Editor](https://ace.c9.io/), [ace-colorpicker](https://github.com/easylogic/ace-colorpicker), [Bootstrap](https://getbootstrap.com/), [Font Awesome](https://fontawesome.com/), [Foundation Icons](https://zurb.com/playground/foundation-icon-fonts-3), and of course the [Toyhouse](https://toyhou.se) source files. The source files are included in the codebase. I do not own any of these libraries or resources.

# How to guides

## General
- After making your changes, do add a line to the changelog to record the new version number!

## Edit code editor functionality
- If you're editing the UI functionality (anything related to buttons on the UI and frame appearance), almost everything you need to edit will be found in /index.php and build_staging/script.js.
- Occasionally you may need to change things inside the preview frame; this is mostly housed in frame.php and its associated script, frame.js.

## Adding a new page layout
- Open the HTML page on Toyhouse using the inspector or view source and locate the element with the `#main` ID and copy *only* the that element.
- Process the code to create a clean copy. This includes removing the existing custom content (usually identified by the `.user-content` class name), changing all `href` properties to the value `#`, and deidentifying dates (I use **1 Jan 1900 00:00**) and users (I use the username **user**).
- The `.user-content` element should also have the additional class name `.ace-code-container`, and the blurb section should have the class name `.ace-code-container-2`. This tells the editor where to put the code.
- Create an HTML file with a suitable filename in the templates folder and paste the processed code inside.
- Add a new item to the dropdown menu in index.php. Its onclick property should be `switchTo( <HTML filename without extension> )`

## Set up import credentials
Since Version 1.10.3, the code editor's importer now authenticates itself with Toyhouse so that it can retrieve guest-blocked profiles. The live production version uses Circlejourney's bot account [fuchsiamoonrise](https://toyhou.se/fuchsiamoonrise), but these are not committed to the repo for security purposes.

To make the feature work in full, you can set up your local version to log in with your own Toyhouse credentials. To do so, create a file called `settings.conf` *one directory above* the editor's root directory. For example, if the editor is in `C:/theditor`, `settings.conf` should be in `C:/`.

`settings.conf` is formatted  as a [PHP ini file](https://www.php.net/manual/en/function.parse-ini-file.php) containing two settings: a username and a password. The structure of this file is demonstrated in `settings.conf.example`. This file should *not* be committed to the repository.

## Thank yous
Huge thank you [Min](https://github.com/liwoyadan) for very kindly shared their Font Awesome Pro courtesy of subscription with us 💙 This gives us access to premium FA icons.

And thank you [venfaaniik](https://github.com/venfaaniik) for collaborating with me on the colour picker extension for Ace—your time and help are much appreciated!
