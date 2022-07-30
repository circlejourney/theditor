# Toyhouse Live Code Editor
An editor with live preview for [Toyhouse](https://toyhou.se) themes. Created by Circlejourney, licenced under a CC-BY-SA-4.0 licence.

## Previewing on local server
To preview a local copy of the code, you will need to Install [XAMPP](https://www.apachefriends.org/) from Apache Friends. Once added to your PATH variable, navigate to  the theditor root directory on the command line and start a local server:

> `php -S localhost:8000`

## On Font Awesome
The Toyhouse Editor has Font Awesome Pro courtesy of [Min](https://github.com/liwoyadan) who very kindly shared their subscription with us 💙 This gives us access to premium FA icons. Font Awesome icons should load normally on a local server, but will not work on other public domains.

## Directory tree
- src: contains all project dependencies and source files from Toyhouse. Includes various Toyhouse theme CSS files. Files in this folder should generally not change unless the external sources change (such as if Toyhouse updates their code).
- templates: HTML templates for layouts. These file names are referenced by frame.js > `switchTo()` and any new layouts should also be added to the layout menu in index.php.

# How to guides

## Adding a new page layout
1. Grab the full HTML page from Toyhouse. You can do this by going to a page in the target layout and hitting Ctrl + U (Windows) or Option + Command + U (Mac OS).
2. Create an HTML file with a suitable filename in the templates directory, and paste the .
3. Process the code to create a clean copy. This includes removing the existing custom content, changing all `href` properties to the value `#`, and deidentifying dates and users.
4. The div containing the custom code (typically with the class `user-select`) should have the class name `ace-code-container`, and the blurb section should have the class name `ace-code-container 2`. This tells the editor where to put the code
5. Add a new item to the dropdown menu in index.php. Its onclick property should be `switchTo( <HTML filename without extension> )`