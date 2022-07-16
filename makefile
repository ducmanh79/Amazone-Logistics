start:
	- docker-compose up -d
watch:
	- docker-compose run npm run watch
stop:
	- docker-compose stop
clear-config:
	- docker-compose run php php artisan config:cache
connect-php:
	- docker-compose run php sh
npm-install:
	- docker-compose run npm install
autoload:
	- docker-compose run composer dump-autoload
migrate:
	- docker-compose run php php artisan migrate
go-inside-php-container:
	- docker-compose run php sh
run-cron:
	- docker-compose run php php artisan schedule:run >> /dev/null 2>&1
install:
	- docker-compose up -d
	- docker-compose run composer composer install
	- docker-compose run npm install
	- docker-compose run npm run prod
	- docker-compose run php php artisan migrate
	- docker-compose run php php artisan db:seed
	- docker-compose run php php artisan landingpage:cache

refresh:
	- docker-compose run php php artisan config:cache
	- docker-compose run php php artisan migrate:refresh --seed

update:
	- docker-compose run php php artisan down
	- git pull
	- docker-compose run composer composer install
	- cp -f .test-server.env src/.env
	- docker-compose run php php artisan config:cache
	- docker-compose run php php artisan migrate
	- docker-compose run php php artisan up
	- docker-compose run php php artisan landingpage:cache

refresh-remote:
	- docker-compose run php php artisan migrate:refresh
	- docker-compose run php php artisan db:seed
	- docker-compose run php php artisan passport:install
	- docker-compose run php php artisan landingpage:cache

remote-deploy:
	- ssh -i C:\Users\nguye\Documents\keyvpsfree.pem ubuntu@ec2-18-139-226-191.ap-southeast-1.compute.amazonaws.com 'cd /home/toi/projects/class.tech2.vn && make update'

clear-cache-remote: