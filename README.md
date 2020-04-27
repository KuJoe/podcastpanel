# PodcastPanel
A very simple script for managing self hosted podcasts. Probably can be used for other stuff too.

# Features
- Upload episodes with artwork (calculates duration and file size automatically)
- Edit/Publish/Hide episodes
- User management for admins
- Comment management for admins
- Login/Authentication for admins
- Admin Logs

# In Progress
- Public facing site (needs to be re-written with a template that doesn't require a paid license)
- Comment system
- Tags/Categories/Seasons
- Social media integration (primarily Twitter)
- Play/Download tracking for episodes
- Installation script
- Better organization and comments in the code

# Requirements
Webserver
PHP 7.4 (briefly tested on 7.1, 7.2)
MySQL 5.7 (probably works on newer and older versions, unsure.)
Set the upload_max_filesize value in your php.ini large enough for your podcast uploads.

# Installation
Working on an install script now for the DB stuffs.

# Credits
Admin Template: https://startbootstrap.com/templates/simple-sidebar/
Authentication code: https://codeshack.io/secure-login-system-php-mysql/#creatingtheloginformdesign
MP3 code: http://www.zedwood.com/article/php-calculate-duration-of-mp3