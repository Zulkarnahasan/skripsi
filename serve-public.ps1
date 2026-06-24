param(
    [int]$Port = 8000,
    [string]$HostAddress = "127.0.0.1"
)

$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

$phpCommand = Get-Command php -ErrorAction SilentlyContinue
$php = if ($phpCommand) { $phpCommand.Source } else { "D:\laragon\bin\php\php-8.3.28-Win32-vs16-x64\php.exe" }

if (-not (Test-Path $php)) {
    throw "PHP tidak ditemukan. Pastikan PHP ada di PATH atau Laragon terpasang di D:\laragon."
}

$cloudflaredCommand = Get-Command cloudflared -ErrorAction SilentlyContinue
$cloudflared = if ($cloudflaredCommand) { $cloudflaredCommand.Source } else { Join-Path $PSScriptRoot "tools\cloudflared.exe" }

if (-not (Test-Path $cloudflared)) {
    throw "cloudflared tidak ditemukan. Letakkan cloudflared.exe di folder tools atau tambahkan ke PATH."
}

$origin = "http://${HostAddress}:$Port"
$env:APP_URL = $origin
$env:TRUSTED_PROXIES = "*"

Write-Host "Menjalankan Laravel di $origin ..."
$server = Start-Process `
    -FilePath $php `
    -ArgumentList @("artisan", "serve", "--host=$HostAddress", "--port=$Port") `
    -WorkingDirectory $PSScriptRoot `
    -WindowStyle Hidden `
    -PassThru

try {
    $ready = $false

    for ($attempt = 1; $attempt -le 30; $attempt++) {
        try {
            Invoke-WebRequest -UseBasicParsing "$origin/up" | Out-Null
            $ready = $true
            break
        } catch {
            Start-Sleep -Milliseconds 500
        }
    }

    if (-not $ready) {
        throw "Laravel belum merespons di $origin."
    }

    Write-Host ""
    Write-Host "Tunnel publik akan dibuat. Salin URL https://...trycloudflare.com yang muncul di bawah ini."
    Write-Host "Biarkan jendela ini tetap terbuka selama website dipakai dari jaringan lain."
    Write-Host ""

    & $cloudflared tunnel --url $origin --logfile (Join-Path $PSScriptRoot "cloudflared-tunnel.log")
} finally {
    if ($server -and -not $server.HasExited) {
        Stop-Process -Id $server.Id -Force
    }
}
