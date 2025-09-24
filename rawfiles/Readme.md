## Install laravel with composer
$ composer create-project --prefer-dist laravel/laravel freelancer-backend

## link to install laravel ui https://github.com/laravel/ui

## Generate login / registration scaffolding...
php artisan ui bootstrap --auth (Type yes)
php artisan ui vue --auth
php artisan ui react --auth

## Create DB freelancerdb

## migrate db
$ php artisan migrate

## Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass

$ npm install 

## go to disable welcome.blade.php (manifest.json)

$ npm run dev
$ composer run dev

## install laravel sanctum 
$ php artisan install:api

## check api route list
$ php artisan route:list --path=api


### to verify email

php artisan config:clear
php artisan route:clear

php artisan serve --host=127.0.0.1 --port=8000 --https



# Clear the old boostrap/cache/compiled.php
php artisan clear-compiled
# Recreate boostrap/cache/compiled.php
php artisan optimize
