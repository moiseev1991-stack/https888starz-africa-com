# Static export: mirror local WordPress to dist/
# Prerequisite: WP running at $BaseUrl (e.g. http://localhost:8080)
# Usage: .\scripts\export-static.ps1 [-BaseUrl "http://localhost:8080"] [-OutDir "dist"]

param(
    [string]$BaseUrl = "http://localhost:8080",
    [string]$OutDir = "dist"
)

$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path $PSScriptRoot -Parent
if (-not (Get-Location).Path.StartsWith($ProjectRoot)) { Set-Location $ProjectRoot }

$DistPath = Join-Path $ProjectRoot $OutDir
if (Test-Path $DistPath) {
    Write-Host "Removing existing $OutDir..."
    Remove-Item -Recurse -Force $DistPath
}
New-Item -ItemType Directory -Path $DistPath -Force | Out-Null

# Check wget availability (Git for Windows / WSL often provide it)
$wget = Get-Command wget -ErrorAction SilentlyContinue
if (-not $wget) {
    Write-Host "wget not found. Install Git for Windows or use WSL, or run export from scripts/export-static.sh in WSL/Git Bash."
    Write-Host "Alternatively, use Node crawler: npm run export:static (see package.json)"
    exit 1
}

Write-Host "Mirroring $BaseUrl -> $DistPath (this may take a while)..."
# -r recursive, -l 10 depth, -k convert links for local, -p get all requisites, -nH no host dirs, -np no parent
# -E add .html where appropriate, -e robots=off, -X exclude admin/login
& wget --mirror --page-requisites --convert-links --no-host-directories --adjust-extension `
    --restrict-file-names=windows `
    --reject "*.php*" `
    --exclude-directories=wp-admin,wp-login,wp-includes,cgi-bin `
    --user-agent="StaticExport/1.0" `
    --directory-prefix=$DistPath `
    --no-check-certificate `
    $BaseUrl 2>&1 | Out-Host

if ($LASTEXITCODE -ne 0) {
    Write-Host "wget exited with $LASTEXITCODE (some errors may be acceptable)"
}

# Replace base URL in HTML so links work when deployed to production
$baseHost = [System.Uri]$BaseUrl
$replaceFrom = $BaseUrl.TrimEnd('/')
Get-ChildItem -Path $DistPath -Recurse -Filter "*.html" | ForEach-Object {
    (Get-Content $_.FullName -Raw -Encoding UTF8) -replace [regex]::Escape($replaceFrom), '' | Set-Content $_.FullName -Encoding UTF8 -NoNewline
}

# Save URL list
$urlFile = Join-Path $ProjectRoot "URLS.txt"
Get-ChildItem -Path $DistPath -Recurse -Filter "*.html" | ForEach-Object {
    $rel = $_.FullName.Substring($DistPath.Length).Replace('\','/').TrimStart('/')
    if ($rel -eq "index.html") { "/" } else { "/" + $rel.Replace("/index.html","/") }
} | Sort-Object -Unique | Set-Content $urlFile -Encoding UTF8

Write-Host "Export done. Output: $DistPath. URL list: URLS.txt"
