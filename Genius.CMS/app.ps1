# Leszek Pomianowski CC0

## BEGIN DATA
#$directories = @('code', 'languages', 'assets')
$files = @('LICENSE')
$directories = @('app', 'public', 'vendor')
$exclude = @('*.scss', '*.po', 'app.js', 'app.css')

# Do what you need to do
$ROOT_PATH = '.\'
$DIST_PATH = '.\build\'
$CACHE_PATH ='.\app\storage\cache'
$BLADE_PATH ='.\app\storage\blade'
$LOGS_PATH ='.\app\storage\logs'
$SESSION_PATH ='.\app\storage\session'
## END DATA

## BEGIN FUNCTIONS
function Build {
  if (Test-Path -Path $DIST_PATH) {
    Remove-Item -path $DIST_PATH -Recurse -Force
    Write-Host "[OK] " -ForegroundColor Green -NoNewline
    Write-Host "Directory $DIST_PATH removed!" -ForegroundColor White
  }

  foreach ($directory in $directories) {
    if (Test-Path -Path $ROOT_PATH$directory) {
      Write-Host "[==] " -ForegroundColor Blue -NoNewline
      Write-Host "Copying a directory: " -ForegroundColor White -NoNewline
      Write-Host $ROOT_PATH$directory -NoNewline
      Write-Host ", to: " -ForegroundColor White -NoNewline
      Write-Host $DIST_PATH$directory

      Copy-Item "$ROOT_PATH$directory" -Destination "$DIST_PATH$directory" -Recurse -Force -Exclude $exclude
      Write-Host "[OK] " -ForegroundColor Green -NoNewline
      Write-Host "Success!"
    }
    else {
      Write-Host "[ER] " -ForegroundColor Red -NoNewline
      Write-Host "Error"
    }
  }
  
  foreach ($file in $files) {
    Write-Host "[==] " -ForegroundColor Blue -NoNewline
    Write-Host "Copying a file: " -ForegroundColor White -NoNewline
    Write-Host $ROOT_PATH$file -NoNewline
    Write-Host ", to: " -ForegroundColor White -NoNewline
    Write-Host $DIST_PATH$file

    if (-not(Test-Path -Path $file -PathType Leaf)) {
      Write-Host "[ER] " -ForegroundColor Red -NoNewline
      Write-Host "Error"
    }
    else {
      Copy-Item "$ROOT_PATH$file" -Destination "$DIST_PATH$file" -Force
      Write-Host "[OK] " -ForegroundColor Green -NoNewline
      Write-Host "Success!"
    }
  }
  
  Write-Host ""
  Write-Host "Excluded: " -ForegroundColor White

  foreach ($excluded in $exclude) {
    Write-Host " - "$excluded
  }
}

function FlushBlade {
  if (Test-Path -Path $BLADE_PATH) {
    Remove-Item -path $BLADE_PATH -Recurse -Force
    Write-Host "[OK] " -ForegroundColor Green -NoNewline
    Write-Host "Blade directory $BLADE_PATH flushed!" -ForegroundColor White
  }
}

function FlushCache {
  if (Test-Path -Path $CACHE_PATH) {
    Remove-Item -path $CACHE_PATH -Recurse -Force
    Write-Host "[OK] " -ForegroundColor Green -NoNewline
    Write-Host "Cache directory $CACHE_PATH flushed!" -ForegroundColor White
  }
}

function FlushLogs {
  if (Test-Path -Path $LOGS_PATH) {
    Remove-Item -path $LOGS_PATH -Recurse -Force
    Write-Host "[OK] " -ForegroundColor Green -NoNewline
    Write-Host "Logs directory $LOGS_PATH flushed!" -ForegroundColor White
  }
}

function FlushSession {
  if (Test-Path -Path $SESSION_PATH) {
    Remove-Item -path $SESSION_PATH -Recurse -Force
    Write-Host "[OK] " -ForegroundColor Green -NoNewline
    Write-Host "Session directory $SESSION_PATH flushed!" -ForegroundColor White
  }
}

function FlushAll {
  FlushBlade
  FlushCache
  FlushLogs
  FlushSession
}

## END FUNCTIONS

## BEGIN PRINT INFO
Write-Host "================================" -ForegroundColor White
Write-Host "LEPO.CO" -ForegroundColor Red -NoNewline
Write-Host " | " -ForegroundColor White -NoNewline
Write-Host "Powershell Quick Tools"
Write-Host "================================" -ForegroundColor White
## END PRINT INFO

## BEGIN ARGUMENTS SWITCH
$param = $args[0]

Write-Host "Selected mode: " -ForegroundColor White -NoNewline
Write-Host $param -ForegroundColor Green

Write-Host ""

switch ($param) {
  "build" {
    Build
  }
  "flush-blade" {
    FlushBlade
  }
  "flush-cache" {
    FlushCache
  }
  "flush-logs" {
    FlushLogs
  }
  "flush-session" {
    FlushSession
  }
  "flush-all" {
    FlushAll
  }
  Default {
    Write-Host "[X] " -ForegroundColor Red -NoNewline
    Write-Host "Function not found" -ForegroundColor White
  }
}
## END ARGUMENTS SWITCH
