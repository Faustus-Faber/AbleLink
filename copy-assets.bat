@echo off
REM Simple script to copy CSS and JS files to public directory
REM Run this whenever you modify resources/css/app.css or resources/js/*.js

echo Copying CSS and JS files to public directory...

if not exist "public\css" mkdir "public\css"
if not exist "public\js" mkdir "public\js"

copy /Y "resources\css\app.css" "public\css\app.css"
copy /Y "resources\js\app.js" "public\js\app.js"
copy /Y "resources\js\bootstrap.js" "public\js\bootstrap.js"

echo Done! Files copied successfully.

