# Delievry Service

# SymLink

# Ununtu Urls
ln -s /var/www/html/delivery_service/storage/app/public /var/www/html/delivery_service/public/storage
ln -s /var/www/html/storage/app/public /var/www/html/public/storage
ln -s /var/www/html/delivery_service/resources/assets/pdf /var/www/html/delivery_service/public/pdf
ln -s /var/www/html/resources/assets/pdf /var/www/html/public/pdf
ln -s /mnt /var/www/html/storage/mnt


# Windows URLs
ln -s  "D:\Work\Xampp\htdocs\delivery_service_new_git\resources\assets\pdf" "D:\Work\Xampp\htdocs\delivery_service_new_git\public\pdf"
ln -s "D:\Work\Xampp\htdocs\delivery_service_new_git\storage\app\public" "D:\Work\Xampp\htdocs\delivery_service_new_git\public\storage

# Instructions

create these folders under storage/framework:

sessions
views
cache


Create public folder with index.php

# Full working backup
D:\Work\Xampp\htdocs\delivery_service_new_git\full_working_backup.zip
# Original Folder Path
D:\Work\Xampp\htdocs\delivery_service_new_git


# Some extra instuctions

-----------------------------------------------------------------------------------------------------------
# Cache Clear:
php artisan cache:clear
php artisan config:clear
php artisan view:clear
-----------------------------------------------------------------------------------------------------------
# Jobs Laravel

php artisan queue:listen --timeout=0
php artisan queue:work --timeout=0
php artisan queue:flush
php artisan queue:work redis --sleep=1 --tries=1
php artisan queue:clear redis
-----------------------------------------------------------------------------------------------------------
# Socket laravel
php artisan websocket:serve
-----------------------------------------------------------------------------------------------------------
# Setup supervisor in ubuntu Tutorial: https://learn2torials.com/a/how-to-setup-laravel-supervisor
-----------------------------------------------------------------------------------------------------------
Jobs Supervsior (For Server)
Commands After Change in conf file
sudo systemctl status supervisor
sudo supervisorctl start laravel-worker:*
sudo supervisorctl stop laravel-worker:*
sudo supervisorctl reread
sudo supervisorctl update
-----------------------------------------------------------------------------------------------------------
# Program File used for scott should (update the user accordingly root for every job) 
-----------------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------------
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work redis --sleep=1 --tries=1
autostart=true
autorestart=true
user=ubuntu
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/supervisor/logs/worker.log​

[program:laravel-websocket]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan websocket:serve
autostart=true
autorestart=true
user=ubuntu
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/supervisor/logs/websocket.log​


