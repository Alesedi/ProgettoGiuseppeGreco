<#
  install_php_windows.ps1
  Installa PHP in modo permanente su Windows.

  Comportamento:
  - Se PHP è già in PATH esce indicando la versione.
  - Se Chocolatey è disponibile, offre di installare PHP con `choco install php -y`.
  - Se Chocolatey non è disponibile, mostra istruzioni rapide per installarlo
    o per eseguire un'installazione manuale (download ZIP da windows.php.net).

  Esegui come utente normale; per installare Chocolatey potrebbero essere necessari
  permessi amministrativi.
#>

function Write-Info($msg) { Write-Host $msg -ForegroundColor Cyan }
function Write-Success($msg) { Write-Host $msg -ForegroundColor Green }
function Write-Warn($msg) { Write-Host $msg -ForegroundColor Yellow }

Write-Info "Controllo presenza di PHP in PATH..."
$phpCmd = Get-Command php -ErrorAction SilentlyContinue
if ($phpCmd) {
    Write-Success "PHP già presente: $(php --version | Select-Object -First 1)"
    exit 0
}

Write-Info "PHP non trovato nel PATH. Verifico se Chocolatey è installato..."
$choco = Get-Command choco -ErrorAction SilentlyContinue
if ($choco) {
    Write-Info "Chocolatey trovato. Procedo con l'installazione di PHP tramite choco."
    $confirm = Read-Host "Vuoi installare PHP con Chocolatey ora? (Y/N)"
    if ($confirm -match '^[Yy]') {
        Write-Info "Eseguo: choco install php -y"
        choco install php -y
        if ($LASTEXITCODE -eq 0) {
            Write-Success "Installazione completata. Chiudi e riapri PowerShell per aggiornare il PATH."
            Write-Info "Verifica con: php --version"
            exit 0
        } else {
            Write-Warn "L'installazione con choco ha restituito un codice non-zero: $LASTEXITCODE"
            Write-Warn "Controlla l'output di Chocolatey per i dettagli."
            exit 2
        }
    } else {
        Write-Info "Installazione annullata dall'utente."
        exit 0
    }
}

Write-Warn "Chocolatey non trovato. Offro istruzioni alternative."
Write-Host "Opzioni disponibili:" -ForegroundColor Cyan
Write-Host "  1) Installare Chocolatey (consigliato)" -ForegroundColor Cyan
Write-Host "  2) Scaricare manualmente PHP dal sito ufficiale e aggiungere la cartella a PATH" -ForegroundColor Cyan
Write-Host "  3) Aprire il sito ufficiale per scegliere la versione da scaricare" -ForegroundColor Cyan

$choice = Read-Host "Scegli 1, 2 o 3 (Enter per uscire)"
if ([string]::IsNullOrWhiteSpace($choice)) { Write-Info "Uscita."; exit 0 }

switch ($choice) {
    '1' {
        Write-Host "Per installare Chocolatey automaticamente, esegui in una sessione PowerShell con privilegi di amministratore:" -ForegroundColor Yellow
        Write-Host "Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))" -ForegroundColor White
        Write-Host "Dopo l'installazione di Chocolatey, riesegui questo script e potrai installare PHP con: choco install php -y" -ForegroundColor Green
        exit 0
    }
    '2' {
        Write-Host "Istruzioni manuali (sintesi):" -ForegroundColor Yellow
        Write-Host "  1) Scarica la ZIP di PHP per Windows da https://windows.php.net/download/" -ForegroundColor White
        Write-Host '  2) Estrai il contenuto in una cartella permanente, es: C:\tools\php\php-8.x' -ForegroundColor White
        Write-Host "  3) Aggiungi la cartella (es: C:\tools\php\php-8.x) al PATH utente (Impostazioni -> Variabili d'ambiente) oppure usa lo script `scripts/add_php_to_path.ps1`" -ForegroundColor White
        Write-Host "  4) Verifica con: php --version" -ForegroundColor Green
        Write-Host "Vuoi che apra la pagina di download ufficiale nel browser ora? (Y/N)" -ForegroundColor Cyan
        $open = Read-Host
        if ($open -match '^[Yy]') { Start-Process 'https://windows.php.net/download/' }
        exit 0
    }
    '3' {
        Start-Process 'https://windows.php.net/download/'
        Write-Info "Aperto browser sul sito di download di PHP."; exit 0
    }
    Default {
        Write-Info "Scelta non valida. Uscita."; exit 1
    }
}
