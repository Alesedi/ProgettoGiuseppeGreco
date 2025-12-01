# SoleDomus — Panoramica del progetto

Obiettivo: realizzare una web app in PHP + MySQL che simuli un e-commerce per la vendita di pannelli fotovoltaici chiamata "SoleDomus".

Requisiti principali
- Registrazione utente con verifica password (conferma e regole minime di complessità).
- Login utente (sessioni sicure).
- Catalogo prodotti (pannelli fotovoltaici) con possibilità di selezionare tipo e opzioni.
- Carrello: aggiungi/rimuovi prodotti, modifica quantità.
- Checkout: inserimento indirizzo di consegna e dati carta per pagamento simulato (numero carta, scadenza, CVV, intestatario).
- Creazione ordine e visualizzazione fattura/ordine.

Vincoli e note di sicurezza
- Usare PHP (idealmente 8.x) e MySQL 8.
- Utilizzare PDO con prepared statements per evitare SQL injection.
- Password salvate con `password_hash()` (bcrypt/argon2 quando disponibile).
- Non memorizzare il CVV in chiaro; per la simulazione si può validare lato server ma non salvare CVV. Salvare solo dati tokenizzati o ultimo4 della carta se necessario.
- Tutte le pagine sensibili dovrebbero essere servite via HTTPS in produzione.
- Implementare CSRF token nei form critici (registrazione, login, checkout).

Architettura ad alto livello

Frontend
- Tecnologie: HTML5, CSS3, Bootstrap 5 (o altro framework CSS), JavaScript (vanilla o piccola libreria come Alpine.js).
- Pagine principali:
  - `index.php` (landing/catalogo)
  - `register.php` (registrazione)
  - `login.php`
  - `product.php` (dettaglio prodotto + opzioni)
  - `cart.php`
  - `checkout.php` (indirizzo + pagamento)
  - `order_confirmation.php`
  - `profile.php` (visualizza ordini, indirizzi salvati)

Backend
- Linguaggio: PHP con pattern MVC leggero (file controller + template views) oppure semplice file-based routing.
- Database: MySQL. Connessione via PDO e file di configurazione centralizzato (`config.php`).
- Structure consigliata:
  - `public/` (file accessibili via web: index.php, css, js, immagini)
  - `app/Controllers/` (gestione richieste)
  - `app/Models/` (classi per interazione DB)
  - `app/Views/` (template)
  - `config/` (connessione DB e costanti)

API / Endpoint (esempi)
- `POST /register` — crea utente
- `POST /login` — autentica
- `GET /products` — lista prodotti
- `GET /product?id={id}` — dettaglio prodotto
- `POST /cart/add` — aggiungi prodotto al carrello
- `POST /checkout` — crea ordine + processo pagamento simulato

Flusso utente tipico
1. L'utente si registra e conferma password.
2. Effettua login.
3. Naviga il catalogo, apre il dettaglio di un pannello, seleziona opzioni (es. inverter, kit montaggio) e aggiunge al carrello.
4. Vai al checkout: inserisce o seleziona indirizzo di consegna, inserisce dati carta (simulazione), conferma pagamento.
5. Sistema crea ordine, registra dati di pagamento in forma non sensibile (ultimo4, brand, token simulato), invia conferma.

Scelte tecniche raccomandate
- Autenticazione: `password_hash()` / `password_verify()` e sessioni PHP standard con `session_regenerate_id()` al login.
- Validazione: doppia valida — client e server.
- Template: usare PHP nativo con include per header/footer oppure un micro-template.
- Javascript: per migliorare UX (aggiornamento carrello inline, validazione carta lato client).

Prossimi passi consigliati
1. Confermare questo documento.
2. Applicare lo schema ER fornito in `db/sole_domus_schema.sql` in DB Designer 4 o MySQL.
3. Scaffold progetto e implementare autenticazione.

File correlati creati ora:
- `docs/01_overview.md` (questo file)
- `db/sole_domus_schema.sql` (schema SQL per import)

Se vuoi, procedo subito a generare la struttura iniziale del progetto (file PHP base, config, connessione PDO e pagine di registrazione/login). Dimmi se preferisci un micro-framework o file PHP semplici.

