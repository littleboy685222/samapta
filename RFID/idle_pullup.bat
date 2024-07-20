@echo off
:start
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_pullup.php
timeout /t 1 /nobreak
goto start