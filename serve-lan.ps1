param(
    [int]$Port = 8002
)

$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

$phpCommand = Get-Command php -ErrorAction SilentlyContinue
$php = if ($phpCommand) { $phpCommand.Source } else { "D:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" }

if (-not (Test-Path $php)) {
    throw "PHP tidak ditemukan. Pastikan PHP ada di PATH atau Laragon terpasang di D:\laragon."
}

$localIp = (Get-NetIPAddress -AddressFamily IPv4 |
    Where-Object { $_.IPAddress -notlike "127.*" -and $_.PrefixOrigin -ne "WellKnown" } |
    Select-Object -First 1 -ExpandProperty IPAddress)

Write-Host "Menjalankan Laravel untuk jaringan lokal..."
Write-Host "Akses dari komputer ini: http://127.0.0.1:$Port"

if ($localIp) {
    Write-Host "Akses dari perangkat satu WiFi/LAN: http://${localIp}:$Port"
}

& $php artisan serve --host=0.0.0.0 --port=$Port
