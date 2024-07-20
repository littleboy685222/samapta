@echo off
:start
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_situp.php
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_pullup.php
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_pushup.php
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_renang.php
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_shuttlerun.php
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_lari.php
timeout /t 1 /nobreak
goto start