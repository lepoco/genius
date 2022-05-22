# This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
# If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
# Copyright (C) 2022 Leszek Pomianowski.
# All Rights Reserved.

Write-Host "======================="
Write-Host "Genius - Expert Systems" -ForegroundColor Green
Write-Host "======================="
Write-Host "Database migration"
Write-Host ""

# dotnet ef database drop

$env:DOTNET = "dotnet"

$Script:Source = ".\src\"
$Script:Solution = "Genius.sln"

function Write-Log([string]$message, [string]$type) {
    Write-Host "[" -NoNewline

    if ($type -eq "empty") {
      Write-Host "  " -NoNewline
    }

    # U+2713 may not be renderable in all terminals
    if ($type -eq "success") {
      Write-Host "OK" -ForegroundColor Green -NoNewline
    }
  
    if ($type -eq "error") {
      Write-Host "ER" -ForegroundColor Red -NoNewline
    }
  
    if ($type -eq "warning") {
      Write-Host "--" -ForegroundColor Yellow -NoNewline
    }

    Write-Host "] " -NoNewline

    Write-Host "$message"
}

function Initialize-Dotnet {
    if (!(Get-Command $env:DOTNET -errorAction SilentlyContinue)) {
      Write-Log -Message "$env:DOTNET (.NET Core) command not found" -Type "error"
      Write-Log -Message "Terminating..." -Type "error"

      exit
    }

    & $env:DOTNET ef --version
}

function Invoke-Migration {
    & $env:DOTNET ef migrations add InitialCreate --project "$($Script:Source)Genius.Core" -o 'Data\Migrations\'
    & $env:DOTNET ef migrations add InitialCreate --project "$($Script:Source)Genius.OAuth" -o 'Data\Migrations\'
    & $env:DOTNET ef migrations add InitialCreate --project "$($Script:Source)Genius.Statistics" -o 'Data\Migrations\'
}

Initialize-Dotnet
Invoke-Migration