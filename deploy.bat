@echo off
echo.
echo ========================================
echo   STII E-VOTE HOSTING DEPLOYMENT
echo ========================================
echo.
echo This will make your system hosting-ready!
echo Make sure you've updated hosting-config.php first.
echo.
pause

php make-hosting-ready.php

echo.
echo Press any key to exit...
pause >nul