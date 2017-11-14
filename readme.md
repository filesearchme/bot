# FileSearch.me Crawler Bot
This is the core website crawler and download link parser for FileSearch.me.

# How can you help?
Donate a small amount of your server resources to help crawl websites and pull file data from download links!

Feel free to look at the current file host classes to create one for a file host currently not listed!

# Before installing
If your public directory is not called "public" you can rename "public" directory to the same name as your public directory. Upload files so files within public directory are within your public directory and all other files/diretories are BELOW public directory.

# How-to install
It's easy to get set up and ready to start processing URLs.

- Download files
- Edit .env.example and rename to .env
- Upload files to server
- Create cronjob "* * * * * /direct/path/to/bot/artisan work"

# Easier less secure install
- Download files
- Edit .env.example and rename to .env
- Rename server.php to index.php
- Copy public/.htacess to root directory
- Upload all files to public directory on server
- Create cronjob "* * * * * /direct/path/to/bot/artisan work"
