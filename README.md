# SoleDomus — Scaffold iniziale

Questo repository contiene una struttura minima per iniziare lo sviluppo di SoleDomus (PHP + MySQL).

Come usare
1. Modifica `config/config.php` con le credenziali del tuo DB.
2. Crea il database e importa `db/sole_domus_schema.sql` (MySQL Workbench o `mysql` CLI).
3. Espone la cartella `public/` come web root (es. in XAMPP: `htdocs/sole_domus/public`).
4. Apri `http://localhost/.../index.php`.

File/Cartelle principali
- `public/index.php` — front controller
- `config/config.php` — configurazione DB
- `app/Models` — Database e modello User
- `app/Controllers` — AuthController
- `app/Views` — header/footer e view per auth

Prossimi passi raccomandati
- Implementare CSRF token, input sanitization più robusta, validazione server-side approfondita.
- Aggiungere routing e controller per prodotti, carrello e checkout.
