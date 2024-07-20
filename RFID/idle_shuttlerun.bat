@echo off
:start
C:\xampp\php\php.exe C:\xampp\htdocs\samapta-copy\api_shuttlerun.php
timeout /t 1 /nobreak
goto start