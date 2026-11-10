@echo off
title Push Project Laravel ke GitHub
color 0A
echo ======================================================================
echo    PRODUCTHUB LARAVEL - PUSH OTOMATIS KE REPOSITORI GITHUB
echo ======================================================================
echo.
echo Pastikan Anda sudah membuat repositori baru di GitHub (misal: laravel-product-crud)
echo.

set /p REPO_URL="Masukkan URL Repositori GitHub Anda: "

if "%REPO_URL%"=="" (
    echo [ERROR] URL Repositori tidak boleh kosong!
    pause
    exit /b
)

echo.
echo Mengonfigurasi remote origin ke: %REPO_URL%
"C:\Users\ryy\bin\mingit\cmd\git.exe" remote remove origin 2>nul
"C:\Users\ryy\bin\mingit\cmd\git.exe" remote add origin %REPO_URL%
"C:\Users\ryy\bin\mingit\cmd\git.exe" branch -M main

echo.
echo Melakukan push ke GitHub...
"C:\Users\ryy\bin\mingit\cmd\git.exe" push -u origin main

if %ERRORLEVEL% equ 0 (
    echo.
    echo ======================================================================
    echo   [SUKSES] PROYEK BERHASIL DI-PUSH KE GITHUB!
    echo   Silakan salin link repositori Anda untuk dikirim via email.
    echo ======================================================================
) else (
    echo.
    echo ======================================================================
    echo   [INFO] Jika diminta login, silakan ikuti petunjuk browser/token GitHub.
    echo ======================================================================
)

echo.
pause
