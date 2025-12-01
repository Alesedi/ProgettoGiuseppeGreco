<#
  add_php_to_path.ps1
  Cerca automaticamente php.exe in percorsi comuni e offre di aggiungerlo al PATH utente.
  Esegui da PowerShell: .\scripts\add_php_to_path.ps1

  Nota: lo script modifica il PATH utente (non richiede privilegi di amministratore).
#>

Write-Host "Cerco php.exe in percorsi comuni (potrebbe impiegare qualche secondo)..."

$searchPaths = @(
    'C:\Program Files',
    'C:\Program Files (x86)',
    'C:\xampp',
    'C:\wamp64',
    'C:\tools',
    "$env:USERPROFILE\AppData\Local\Programs\"  # per installazioni via installers come PHP Manager
)

$found = @()
foreach ($p in $searchPaths) {
    if (Test-Path $p) {
        try {
            $res = Get-ChildItem -Path $p -Filter 'php.exe' -Recurse -ErrorAction SilentlyContinue | Select-Object -First 1 -ExpandProperty FullName
            if ($res) { $found += $res }
        } catch {}
    }
}

if (-not $found) {
    Write-Host "php.exe non trovato automaticamente. Inserisci il percorso completo della cartella che contiene php.exe (es: C:\\php\\) o premi Invio per uscire:"
    $manual = Read-Host "Percorso php (cartella)"
    if (-not [string]::IsNullOrWhiteSpace($manual)) {
        $candidate = Join-Path $manual 'php.exe'
        if (Test-Path $candidate) { $found += $candidate } else { Write-Host "php.exe non trovato in $manual" }
    }
}

if (-not $found) {
    Write-Host "Nessun php.exe trovato. Puoi installare PHP o fornire manualmente il percorso."
    exit 1
}

Write-Host "Trovati i seguenti php.exe (scegli uno):"
[int]$i = 0
foreach ($f in $found) { Write-Host "[$i] $f"; $i++ }

$sel = Read-Host "Seleziona indice (es. 0) oppure premi Invio per usare il primo"
if ([string]::IsNullOrWhiteSpace($sel)) { $sel = 0 }
[int]$selIndex = 0
try { $selIndex = [int]$sel } catch { $selIndex = 0 }

if ($selIndex -lt 0 -or $selIndex -ge $found.Count) { Write-Host "Selezione non valida"; exit 1 }

$phpExe = $found[$selIndex]
$phpBin = Split-Path -Path $phpExe -Parent

Write-Host "Hai scelto: $phpExe`nCartella bin: $phpBin"

$confirm = Read-Host "Aggiungere questa cartella al PATH utente? (Y/N)"
if ($confirm -notmatch '^[Yy]') { Write-Host "Operazione annullata dall'utente."; exit 0 }

# Leggi PATH utente e aggiungi se manca
$userPath = [Environment]::GetEnvironmentVariable('Path','User')
if ($userPath -like "*$phpBin*") {
    Write-Host "Il percorso è già presente nel PATH utente."
    exit 0
}

$newPath = if ([string]::IsNullOrEmpty($userPath)) { $phpBin } else { $userPath + ';' + $phpBin }
[Environment]::SetEnvironmentVariable('Path', $newPath, 'User')
Write-Host "Percorso aggiunto al PATH utente con successo. Chiudi e riapri PowerShell per applicare le modifiche." -ForegroundColor Green

Write-Host "Verifica ora con: `n  mysql --version  (se mysql è in PATH) `n  php --version" -ForegroundColor Yellow
