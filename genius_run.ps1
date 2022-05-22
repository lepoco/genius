# This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
# If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
# Copyright (C) 2022 Leszek Pomianowski.
# All Rights Reserved.

[CmdletBinding()]
Param(
  [Alias("d")]
  [System.String] $WorkingDirectory = ".\build"
)

$env:WINDOWSTERMINAL = "wt"

Write-Host "======================="
Write-Host "Genius - Expert Systems" -ForegroundColor Green
Write-Host "======================="
Write-Host "Starting services..."
Write-Host ""

function Invoke-Run {
  $geniusService = Resolve-Path "$($WorkingDirectory)\Genius.Core\" | select -ExpandProperty Path
  $oauthService = Resolve-Path "$($WorkingDirectory)\Genius.OAuth\" | select -ExpandProperty Path
  $statsService = Resolve-Path "$($WorkingDirectory)\Genius.Statistics\" | select -ExpandProperty Path
  $clientService = Resolve-Path "$($WorkingDirectory)\Genius.Client\" | select -ExpandProperty Path


  & $env:WINDOWSTERMINAL --colorScheme "Campbell Powershell" --title 'Genius.Core' --tabColor "#f54545" --startingDirectory $geniusService "$($geniusService)Genius.Core.exe" `; split-pane -H --title 'Genius.OAuth' --tabColor "#b181f0" --startingDirectory $oauthService "$($oauthService)Genius.OAuth.exe" `; split-pane --colorScheme "One Half Dark" --title 'Genius.Statistics' --tabColor "#eaf797" --startingDirectory $statsService "$($statsService)Genius.Statistics.exe" `; split-pane --colorScheme "Tango Light" --title 'Genius.Client' --tabColor "#45f5c0" --startingDirectory $clientService "$($clientService)Genius.Client.exe"

}

Invoke-Run
