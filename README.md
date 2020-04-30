# PodcastPanel
A very simple script for managing self hosted podcasts. Probably can be used for other stuff too.

# Features
- Upload episodes with artwork (calculates duration and file size automatically)
- Edit/Publish/Hide episodes
- User management for admins
- Comment management for admins
- Login/Authentication for admins
- Admin Logs
- Installation script

# In Progress
- Public facing site (still needs work along with other pages besides the main page)
- Comment system
- Tags/Categories/Seasons
- Social media integration (primarily Twitter)
- Play/Download tracking for episodes
- Better organization and comments in the code

# Requirements
- Webserver
- PHP 7.4 (briefly tested on 7.1, 7.2)
- MySQL 5.7 (probably works on newer and older versions, unsure.)
- Set the upload_max_filesize value in your php.ini large enough for your podcast uploads.

# Installation
1. Download the files from GitHub.
2. Edit the config.php file with your database settings and other information.
3. Upload all of the files to your webhost.
4. Navigate to the install.php file in the root directory.
5. Login to the admin directory with the username and password provided on the install script and start adding podcasts!

# Credits
- Admin Template: https://startbootstrap.com/templates/simple-sidebar/
- Authentication code: https://codeshack.io/secure-login-system-php-mysql/#creatingtheloginformdesign
- MP3 code: http://www.zedwood.com/article/php-calculate-duration-of-mp3
- Icons: https://www.flaticon.com/authors/freepik
- Site Template: https://startbootstrap.com/template-overviews/scrolling-nav
- Audio Player: https://github.com/greghub/green-audio-player