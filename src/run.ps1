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

$env:WINDOWSTERMINAL = "wt"

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

function Initialize-Command {
  if (!(Get-Command $env:WINDOWSTERMINAL -errorAction SilentlyContinue)) {
    Write-Log -Message "$env:WINDOWSTERMINAL (Window Terminal) command not found" -Type "error"
    Write-Log -Message "Terminating..." -Type "error"

    exit
  }
}

function Start-WT {
  [OutputType([Nullable], [Diagnostics.Process])]
  [Diagnostics.CodeAnalysis.SuppressMessageAttribute(
    "PSUseShouldProcessForStateChangingFunctions", "", Justification = "Not changing state"
  )]
  param(
    # Launches the terminal in a specific window.
    [Parameter(ValueFromPipelineByPropertyName)]
    [string]
    $Window,

    # The working directory used by Windows Terminal
    [Parameter(ValueFromPipelineByPropertyName)]
    [string]
    $WorkingDirectory,

    # The command line run by Windows Terminal
    [Parameter(ValueFromPipelineByPropertyName)]
    [string]
    $CommandLine
  )

  begin {
    $allArgs = [Collections.Generic.List[Object]]::new()

  }
  process {
    $allArgs.AddRange(
      @(
        if ($CommandLine -or $ProfileName -or $WorkingDirectory) {
          if ($Window) {
            '-w'
            "`"$Window`""
          }
          if ($WorkingDirectory) {
            '--startingDirectory'
            $WorkingDirectory
          }
          if ($CommandLine) {
            $CommandLine -replace ';' , '\;'
          }
        })
    )
  }

  end {
    Start-Process wt.exe -ArgumentList $allArgs -PassThru:$PassThru
  }
}

function Invoke-Run {
  if ($Run -eq "no") {
    Write-Log -Message "Invoke-Run: Applications will not run" -Type "warning"

    return
  }

  Write-Log -Message "Invoke-Run: Starting applications..." -Type "empty"

  Start-WT -Window 0 -WorkingDirectory "Genius\bin\$Configuration\net6.0\" -CommandLine 'powershell .\Genius'

  Write-Log -Message "Invoke-Run: '.\Genius\bin\$Configuration\net6.0\Genius.exe' started, waiting 1 second..." -Type "success"

  Start-Sleep 1

  Write-Log -Message "Invoke-Run: '.\Genius.Client\bin\$Configuration\net6.0\Genius.Client.exe' started." -Type "success"

  Start-WT -Window 0 -WorkingDirectory "Genius.Client\bin\$Configuration\net6.0\" -CommandLine 'powershell .\Genius.Client'
}

Initialize-Command
Invoke-Run

Write-Log -Message "Script operation completed" -Type "success"