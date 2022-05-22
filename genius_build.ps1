# This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
# If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
# Copyright (C) 2022 Leszek Pomianowski.
# All Rights Reserved.

[CmdletBinding()]
Param(
  [Parameter(Mandatory = $true)]
  [ValidateSet("Debug", "Release")]
  [Alias("c")]
  [System.String] $Configuration = "Debug"
)

$env:BUILDCOMMAND = "dotnet"
$env:NPMCOMMAND = "npm"

$Script:Source = ".\src\"
$Script:Solution = "Genius.sln"
$Script:ClientApp = "GeniusClient\ClientApp\"

Write-Host "======================="
Write-Host "Genius - Expert Systems" -ForegroundColor Green
Write-Host "======================="
Write-Host "Configuration: $Configuration"
Write-Host ""

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

function Initialize-BuildCommand {
  if (!(Get-Command $env:BUILDCOMMAND -errorAction SilentlyContinue)) {
    Write-Log -Message "$env:BUILDCOMMAND (.NET Core) command not found" -Type "error"
    Write-Log -Message "Terminating..." -Type "error"

    exit
  }
}

function Initialize-NpmCommand {
  if (!(Get-Command $env:NPMCOMMAND -errorAction SilentlyContinue)) {
    Write-Log -Message "$env:NPMCOMMAND (Node.js) command not found" -Type "error"
    Write-Log -Message "Terminating..." -Type "error"

    exit
  }
}

function Invoke-Restore
{
  Write-Log -Message "Invoke-Restore: Started" -Type "empty"

  & $env:BUILDCOMMAND restore --nologo $Script:Source$Script:Solution

  if ($lastexitcode -eq 0) {
    Write-Log -Message "Invoke-Restore: Completed" -Type "success"
  }
  else {
    Write-Log -Message "Invoke-Restore: Failed" -Type "error"
    Write-Log -Message "Terminating..." -Type "error"

    exit
  }
}

function Invoke-Build {
  Write-Log -Message "Invoke-Build: Started" -Type "empty"

  & $env:BUILDCOMMAND build --nologo $Script:Source$Script:Solution -property:Configuration=$Configuration

  if ($lastexitcode -eq 0) {
    Write-Log -Message "Invoke-Build: Completed" -Type "success"
  }
  else {
    Write-Log -Message "Invoke-Build: Failed" -Type "error"
    Write-Log -Message "Terminating..." -Type "error"

    exit
  }
}

function Invoke-BuildNpm {
  Write-Log -Message "Invoke-BuildNpm: Started" -Type "empty"

  #$ npm install -WorkingDirectory "$Script:Source$Script:ClientApp"

  # if ($lastexitcode -eq 0) {
  #   Write-Log -Message "Invoke-BuildNpm: Completed" -Type "success"
  # }
  # else {
  #   Write-Log -Message "Invoke-BuildNpm: Failed" -Type "error"
  #   Write-Log -Message "Terminating..." -Type "error"

  #   exit
  # }
}

function Invoke-DatabaseUpdate {
  if ($UpdateDatabase -eq "no") {
    Write-Log -Message "Invoke-DatabaseUpdate: Database will not be updated." -Type "warning"

    return
  }

  Write-Log -Message "Invoke-DatabaseUpdate: Started" -Type "empty"

  & $env:BUILDCOMMAND ef database update --project "$($Script:Source)Genius" --context ExpertContext
  & $env:BUILDCOMMAND ef database update --project "$($Script:Source)Genius.OAuth" --context SystemContext
  & $env:BUILDCOMMAND ef database update --project "$($Script:Source)Genius.Statistics" --context StatisticsContext

  if ($lastexitcode -eq 0) {
    Write-Log -Message "Invoke-DatabaseUpdate: Completed" -Type "success"
  }
  else {
    Write-Log -Message "Invoke-DatabaseUpdate: Failed" -Type "error"
    Write-Log -Message "Terminating..." -Type "error"

    exit
  }
}

Initialize-BuildCommand
Invoke-Restore
Invoke-Build
Invoke-DatabaseUpdate
Initialize-NpmCommand
Invoke-BuildNpm

Write-Log -Message "Script operation completed" -Type "success"