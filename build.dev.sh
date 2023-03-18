#!/bin/bash

rm -r /var/www/html/jlearn/*
rm -r /var/www/html/dev/jlearn/*
cp -r ./src/* /var/www/html/jlearn/
cp -r ./src/* /var/www/html/dev/jlearn/

cp ./src/.htaccess /var/www/html/jlearn/
cp ./src/app/.htaccess /var/www/html/jlearn/app/
cp ./src/system/.htaccess /var/www/html/jlearn/system/
cp ./src/scripts/.htaccess /var/www/html/jlearn/scripts/

chmod 777 /var/www/html/jlearn/system/data/

cp ./src/.htaccess /var/www/html/dev/jlearn/
cp ./src/app/.htaccess /var/www/html/dev/jlearn/app/
cp ./src/system/.htaccess /var/www/html/dev/jlearn/system/
cp ./src/scripts/.htaccess /var/www/html/dev/jlearn/scripts/

chmod 777 /var/www/html/dev/jlearn/system/data/

#cp -r ./* /var/www/html/

