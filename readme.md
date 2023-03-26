# Welcome to the Toyhouse Editor Github repo!

Thank you so much for your help, I'm a busy person and I would love all the help I can get with updating the editor!

Currently things are a bit of a mess here, as I dropped the entire filetree from the server into the repo. So it's still using the old versioning system where each new minor version gets put its own directory, so users can continue to use the previous version in case the latest has bugs.

My intention in the future is to reorganise things so you can simply preview one copy of the files and not have to mess with that.

In the meantime, here's how to update!

## How to
Generally, the files you'll be updating will be located in the individual build_[date] folders. If you want to edit those, you'd want to do the following:
- Create a new folder in root called build_[today's date YYYYMMDD format] and copy all the contents of the previous build folder (the most recent date) into it
- Open index.php in the new build_[today's date] folder and change the value of `lastUpdate` to today's date YYYYMMDD format (this will tell cache bust and also serve the new files)
- Make all your changes in the new build_[today's date] folder!

Do not worry about updating the changelog text, and generally you would have no reason to update anything outside of the build_[date] folders.

Thanks again!