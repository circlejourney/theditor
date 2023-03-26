# Toyhouse Live Code Editor
An editor with live preview for [Toyhouse](https://toyhou.se) themes. Created by Circlejourney, licenced under a CC-BY-SA-4.0 licence. Thank you so much for your help, I'm a busy person and I would love all the help I can get with updating the editor!

## Previewing on local server
To preview a local copy of the code, you will need to Install [XAMPP](https://www.apachefriends.org/) from Apache Friends. Once added to your PATH variable, navigate to  the theditor root directory on the command line and start a local server:

> `php -S localhost:8000`

Once you've started up the server, to actually see the code editor, you will have to navigate to the url `localhost:8000/build_staging`.

## On Font Awesome
The Toyhouse Editor has Font Awesome Pro courtesy of [Min](https://github.com/liwoyadan) who very kindly shared their subscription with us 💙 This gives us access to premium FA icons. Font Awesome icons should load normally on a local server, but will not work on other public domains.

## Directory tree
- **root**: Every file in the root is meant update across all versions of the editor, so for example clicking the changelog button on an older version of the code editor still brings up the latest changelog. Changelog, known issues, notes and versions are placed in the root folder. There is also a dummy index.php that simply pulls the contents from the build_staging folder's index.php.
- **build_staging**: contains all files related to the direct functioning of the editor. Overwhelmingly, this is where you'll be making changes. script.js contains the functionality of the code editor UI, and frame.js contains the functionality of the preview frame. The frame DOM element can be accessed with `frame`; you can get `frame`'s window object with `frame.contentWindow`. Conversely, frame.js can interact with the UI window with the `parent` object.
- **src**: contains all project dependencies and source files from Toyhouse. Includes various Toyhouse theme CSS files. Files in this folder should generally not change unless the external sources change (such as if Toyhouse updates their code).
- **templates**: HTML templates for layouts. These file names are referenced by frame.js > `switchTo()` and any new layouts should also be added to the layout menu in index.php.

# How to guides

## General
- After making your changes, do add a line to the changelog to record the new version number!
- 

## Edit code editor functionality
- If you're editing the UI functionality (anything related to buttons on the UI and frame appearance), almost everything you need to edit will be found in /index.php and build_staging/script.js.
- Occasionally you may need to change things inside the preview frame; this is mostly housed in frame.html and its associated script, frame.js.

## Adding a new page layout
- Open the HTML page on Toyhouse using the inspector or view source and locate the element containing user-created content; usually it has the class name `user-content`. Grab *only* the `user-content` element.
- Process the code to create a clean copy. This includes removing the existing custom content, changing all `href` properties to the value `#`, and deidentifying dates (I use **1 Jan 1900 00:00**) and users (I use the username **user**).
- The `user-content` element should also have the additional class name `ace-code-container`, and the blurb section should have the class name `ace-code-container-2`. This tells the editor where to put the code.
- Create an HTML file with a suitable filename in the templates folder and paste the processed code inside.
- Add a new item to the dropdown menu in index.php. Its onclick property should be `switchTo( <HTML filename without extension> )`