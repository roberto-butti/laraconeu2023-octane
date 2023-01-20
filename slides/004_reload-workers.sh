# 1) Manual Reload:
php artisan octane:reload --server=swoole

#2) Via the “watcher”
npm install --save-dev chokidar

php artisan octane:start --watch
# INFO  Application change detected. Restarting workers...

