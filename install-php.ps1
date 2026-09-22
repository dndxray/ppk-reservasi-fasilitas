# Define variables
$BASE_URL = "https://download.herdphp.com"
$INSTALL_DIR = "$HOME\.config\herd-lite\bin"
$PHP_BIN = "$INSTALL_DIR\php.exe"
$COMPOSER_BIN = "$INSTALL_DIR\composer.phar"
$COMPOSER_BAT = "$INSTALL_DIR\composer.bat"
$LARAVEL_BIN = "$INSTALL_DIR\laravel.phar"
$LARAVEL_BAT = "$INSTALL_DIR\laravel.bat"
$PHP_INI = "$INSTALL_DIR\php.ini"

# Create the directory if it doesn't exist
if (-Not (Test-Path -Path $INSTALL_DIR)) {
    New-Item -ItemType Directory -Path $INSTALL_DIR | Out-Null
}

function Show-Spinner {
    param (
        [int]$processId
    )
    $spinner = @('|', '/', '-', '\')
    $i = 0
    while (Get-Process -Id $processId -ErrorAction SilentlyContinue) {
        Write-Host -NoNewline -ForegroundColor Yellow ("`b" + $spinner[$i++ % $spinner.Length])
        Start-Sleep -Milliseconds 100
    }
    Write-Host -NoNewline "`b `b`n"
}

function Download-With-Spinner {
    param (
        [string]$url,
        [string]$outputFile
    )
    $webClient = New-Object System.Net.WebClient
    $downloadJob = Start-Job -ScriptBlock {
        param ($url, $outputFile)
        $webClient = New-Object System.Net.WebClient
        $webClient.DownloadFile($url, $outputFile)
    } -ArgumentList $url, $outputFile

    Show-Spinner -processId $downloadJob.Id
    Wait-Job -Id $downloadJob.Id
    Remove-Job -Id $downloadJob.Id

    if (Test-Path -Path $outputFile) {

    } else {
        Write-Host "Failed to download file from $url." -ForegroundColor Red
        exit 1
    }
}

function Info {
    param (
        [string]$message
    )
    Write-Host " INFO  $message" -BackgroundColor Blue -ForegroundColor White
}

function Success {
    param (
        [string]$message
    )
    Write-Host " SUCCESS  $message " -BackgroundColor Green -ForegroundColor Black
}

function Error {
    param (
        [string]$message
    )
    Write-Host " ERROR  $message " -BackgroundColor Red -ForegroundColor White
}

Clear-Host

$HERD_IS_INSTALLED = Test-Path "$HOME\.config\herd\bin\php.bat"
if ($HERD_IS_INSTALLED) {
    Info "You already use Laravel Herd - are you sure you want to install another copy of PHP? (y/n) "
    $response = Read-Host
    if ($response -ne "y") {
        exit 0
    }
}

Info "Downloading PHP binary...  "
Download-With-Spinner "$BASE_URL/herd-lite/windows/8.5/php.exe" "$PHP_BIN"

Info "Creating php.ini...  "

if (-Not (Test-Path -Path $PHP_INI)) {
    New-Item -ItemType File -Path $PHP_INI | Out-Null

    # Write content to php.ini
    @"
variables_order = "GPCS"
opcache.enable=1
opcache.enable_cli=1
"@ | Set-Content -Path $PHP_INI
}

Info "Downloading Composer binary...  "
Download-With-Spinner "$BASE_URL/herd-lite/composer" "$COMPOSER_BIN"
Download-With-Spinner "$BASE_URL/herd-lite/windows/composer.bat" "$COMPOSER_BAT"

Info "Downloading Laravel Installer...  "
Download-With-Spinner "$BASE_URL/resources/laravel" "$LARAVEL_BIN"
Download-With-Spinner "$BASE_URL/herd-lite/windows/laravel.bat" "$LARAVEL_BAT"

# Add the installation directory to the PATH if not already present
$path = [System.Environment]::GetEnvironmentVariable("Path", [System.EnvironmentVariableTarget]::User)
if ($path -notlike "*$INSTALL_DIR*") {
    Info "Adding $INSTALL_DIR to your PATH... `n"
    [System.Environment]::SetEnvironmentVariable("Path", "$INSTALL_DIR;$path", [System.EnvironmentVariableTarget]::User)
    Info "Added $INSTALL_DIR to PATH. Please restart your terminal to apply changes. `n"

    # Add the installation directory to the PATH for the current session
    $env:Path += ";$INSTALL_DIR"
    # Update current process PATH environment variable if it needs updating.
    if ($env:Path -notlike "*$INSTALL_DIR*") {
        $env:Path = [System.Environment]::GetEnvironmentVariable('Path', [System.EnvironmentVariableTarget]::Machine);
    }
} else {
    Info "$INSTALL_DIR is already in your PATH. `n"
}

# Example usage
Write-Host ""

# Success message with green background
Write-Host " " -NoNewline
Write-Host "Success!" -ForegroundColor Black -BackgroundColor Green
Write-Host " " -NoNewline
Write-Host "php" -ForegroundColor White -NoNewline
Write-Host ", " -NoNewline
Write-Host "composer" -ForegroundColor White -NoNewline
Write-Host ", and " -NoNewline
Write-Host "laravel" -ForegroundColor White -NoNewline
Write-Host " have been installed successfully."
Write-Host " "
Write-Host " Pro tip: While php.new gives you the basics, " -NoNewline
Write-Host "Laravel Herd" -ForegroundColor White -NoNewline
Write-Host " provides:"
Write-Host ""
Write-Host " * One-click PHP version switching and updates (7.4 - 8.5)"
Write-Host " * Automatic HTTPS for all sites"
Write-Host " * No more localhost:8000 - access your projects at " -NoNewline
Write-Host "folder-name.test" -ForegroundColor Gray
Write-Host " * ...and much more"
Write-Host ""
Write-Host " Upgrade your workflow -> " -NoNewline
Write-Host "https://herd.laravel.com" -ForegroundColor Cyan
Write-Host ""
