# SoleDomus - Analisi e Progettazione Pre-Sviluppo

## 📋 Indice
1. [Analisi dei Requisiti](#analisi-dei-requisiti)
2. [Diagramma dei Casi d'Uso (Use Case)](#diagramma-dei-casi-duso)
3. [Tabella Casi d'Uso](#tabella-casi-duso)
4. [Attori del Sistema](#attori-del-sistema)
5. [Casi d'Uso Dettagliati](#casi-duso-dettagliati)
6. [Modello Entità-Relazioni Dettagliato](#modello-entità-relazioni-dettagliato)
7. [Diagramma delle Classi (Class Diagram)](#diagramma-delle-classi)
8. [Diagramma Entità-Relazioni (ER Diagram)](#diagramma-entità-relazioni)
9. [Diagramma di Sequenza](#diagramma-di-sequenza)
10. [Architettura del Sistema](#architettura-del-sistema)
11. [Business Rules](#business-rules)
12. [User Stories](#user-stories)
13. [Requisiti Funzionali](#requisiti-funzionali)
14. [Requisiti Non Funzionali](#requisiti-non-funzionali)
15. [Mockup e Wireframe](#mockup-e-wireframe)

---

## 📊 Analisi dei Requisiti

### Obiettivo del Progetto
Sviluppare una piattaforma e-commerce specializzata nella vendita di pannelli fotovoltaici, permettendo agli utenti di:
- Navigare un catalogo di prodotti specializzati
- Aggiungere prodotti al carrello
- Completare acquisti con pagamento simulato
- Tracciare ordini e storico acquisti

### Dominio Applicativo
**E-commerce B2C** nel settore delle energie rinnovabili (pannelli solari fotovoltaici)

### Target Utenti
- Privati cittadini (installazioni residenziali)
- Piccole imprese (installazioni commerciali)
- Appassionati outdoor/campeggio (pannelli portatili)

### Vincoli di Progetto
- **Tecnologie**: PHP 8+, MySQL 8, Bootstrap 5
- **Architettura**: MVC pattern
- **Deployment**: Server PHP built-in (dev), Docker per database
- **Sicurezza**: Password hashing, prepared statements, validazione server-side

---

## 🎭 Diagramma dei Casi d'Uso (Use Case)

```
                    ┌─────────────────────────────────────┐
                    │     Sistema SoleDomus E-Commerce    │
                    └─────────────────────────────────────┘
                                    │
        ┌───────────────────────────┼───────────────────────────┐
        │                           │                           │
   ┌────▼────┐                 ┌────▼────┐              ┌──────▼──────┐
   │ Visitatore│                │ Cliente │              │Amministratore│
   │  (Guest) │                │Registrato│             │   (Admin)   │
   └─────────┘                 └─────────┘              └─────────────┘
        │                           │                           │
        │                           │                           │
        ├─ UC1: Visualizza Homepage │                           │
        ├─ UC2: Naviga Catalogo     │                           │
        ├─ UC3: Cerca Prodotti      │                           │
        ├─ UC4: Vedi Dettaglio Prod.│                           │
        ├─ UC5: Registrazione       │                           │
        ├─ UC6: Login               │                           │
        │                           │                           │
        │                           ├─ UC7: Gestione Carrello   │
        │                           ├─ UC8: Aggiungi al Carrello│
        │                           ├─ UC9: Rimuovi dal Carrello│
        │                           ├─ UC10: Checkout           │
        │                           ├─ UC11: Inserisci Indirizzo│
        │                           ├─ UC12: Inserisci Pagamento│
        │                           ├─ UC13: Conferma Ordine    │
        │                           ├─ UC14: Visualizza Storico │
        │                           ├─ UC15: Dashboard Utente   │
        │                           ├─ UC16: Gestione Profilo   │
        │                           ├─ UC17: Logout             │
        │                           │                           │
        │                           │                           ├─ UC18: Gestione Prodotti
        │                           │                           ├─ UC19: Gestione Ordini
        │                           │                           ├─ UC20: Report Vendite
        │                           │                           └─ UC21: Gestione Utenti

                    ┌───────────────────────────────────┐
                    │    Attori Esterni (Include)       │
                    └───────────────────────────────────┘
                                    │
                        ┌───────────┴───────────┐
                        │                       │
                  ┌─────▼─────┐         ┌──────▼──────┐
                  │  Sistema   │         │   Gateway   │
                  │   Email    │         │  Pagamento  │
                  └────────────┘         │  (Simulato) │
                                        └─────────────┘
```

---

## 📋 Tabella Casi d'Uso

| ID | Nome Caso d'Uso | Attore Primario | Priorità | Complessità | Precondizioni | Postcondizioni |
|---|---|---|---|---|---|---|
| UC1 | Visualizza Homepage | Visitatore, Cliente | HIGH | LOW | Nessuna | Homepage visualizzata con prodotti in evidenza |
| UC2 | Naviga Catalogo | Visitatore, Cliente | HIGH | LOW | Accesso a sito | Catalogo prodotti visualizzato |
| UC3 | Cerca Prodotti | Visitatore, Cliente | MEDIUM | MEDIUM | Catalogo aperto | Risultati ricerca visualizzati |
| UC4 | Vedi Dettaglio Prodotto | Visitatore, Cliente | HIGH | LOW | Catalogo aperto | Pagina dettaglio prodotto visualizzata |
| UC5 | Registrazione Utente | Visitatore | HIGH | MEDIUM | Non autenticato | Nuovo utente creato, sessione attiva |
| UC6 | Login | Visitatore | HIGH | MEDIUM | Non autenticato, user registrato | Sessione utente attiva |
| UC7 | Visualizza Carrello | Cliente | HIGH | LOW | Autenticato | Carrello visualizzato |
| UC8 | Aggiungi al Carrello | Cliente | HIGH | MEDIUM | Autenticato, prodotto disponibile | Prodotto aggiunto a cart_items |
| UC9 | Rimuovi dal Carrello | Cliente | MEDIUM | LOW | Autenticato, carrello non vuoto | Prodotto rimosso da cart_items |
| UC10 | Checkout | Cliente | CRITICAL | HIGH | Autenticato, carrello non vuoto | Ordine creato, pagamento registrato |
| UC11 | Inserisci Indirizzo Spedizione | Cliente | HIGH | MEDIUM | Checkout avviato | Indirizzo salvato in addresses |
| UC12 | Inserisci Dati Pagamento | Cliente | CRITICAL | HIGH | Checkout avviato, indirizzo salvato | Carta salvata, pagamento autorizzato |
| UC13 | Conferma Ordine | Cliente | CRITICAL | MEDIUM | Pagamento autorizzato | Ordine confermato, carrello svuotato |
| UC14 | Visualizza Storico Ordini | Cliente | HIGH | MEDIUM | Autenticato | Lista ordini visualizzata da purchase_history |
| UC15 | Dashboard Utente | Cliente | HIGH | LOW | Autenticato | Dashboard con saluto e ultimi ordini |
| UC16 | Gestione Profilo | Cliente | MEDIUM | MEDIUM | Autenticato | Profilo modificabile (nome, email, pwd) |
| UC17 | Logout | Cliente | HIGH | LOW | Autenticato | Sessione distrutta, redirect login |
| UC18 | Gestione Prodotti | Admin | MEDIUM | MEDIUM | Admin loggato | CRUD prodotti disponibile |
| UC19 | Gestione Ordini | Admin | MEDIUM | MEDIUM | Admin loggato | Ordini modifiable da admin |
| UC20 | Report Vendite | Admin | LOW | HIGH | Admin loggato | Report su purchase_history |
| UC21 | Gestione Utenti | Admin | LOW | MEDIUM | Admin loggato | Utenti modifiable/eliminabili |

### Legenda Priorità
- **CRITICAL**: Funzionalità essenziale per MVP
- **HIGH**: Importante, richiesta in fase 1
- **MEDIUM**: Desiderabile per fase 2
- **LOW**: Nice-to-have, fase 3+

### Legenda Complessità
- **LOW**: < 1 giorno sviluppo
- **MEDIUM**: 1-3 giorni
- **HIGH**: > 3 giorni (richiede design articolato)

---

## 👤 Attori del Sistema

### 1. Visitatore (Guest User)
**Descrizione**: Utente non autenticato che naviga il sito  
**Obiettivi**:
- Esplorare il catalogo prodotti
- Visualizzare informazioni tecniche
- Decidere se registrarsi per acquistare

**Permessi**:
✅ Visualizza homepage  
✅ Naviga catalogo  
✅ Cerca prodotti  
✅ Vede dettagli prodotto  
✅ Può registrarsi  
❌ NON può aggiungere al carrello  
❌ NON può acquistare  

### 2. Cliente Registrato (Authenticated User)
**Descrizione**: Utente autenticato che può effettuare acquisti  
**Obiettivi**:
- Acquistare pannelli solari
- Gestire il carrello
- Tracciare ordini

**Permessi**:
✅ Tutti i permessi del Visitatore  
✅ Aggiunge/rimuove prodotti dal carrello  
✅ Procede al checkout  
✅ Visualizza storico ordini  
✅ Gestisce profilo personale  

### 3. Amministratore (Admin)
**Descrizione**: Gestore del sistema con privilegi elevati  
**Obiettivi**:
- Gestire catalogo prodotti
- Monitorare vendite
- Amministrare utenti

**Permessi**:
✅ Tutti i permessi del Cliente  
✅ CRUD prodotti (Create, Read, Update, Delete)  
✅ Visualizza/modifica ordini  
✅ Report vendite  
✅ Gestione utenti  

### 4. Sistema Email (Attore Esterno)
**Descrizione**: Servizio di invio notifiche email  
**Interazioni**:
- Conferma registrazione
- Notifica ordine
- Reset password

### 5. Gateway Pagamento (Attore Esterno)
**Descrizione**: Sistema di elaborazione pagamenti (simulato)  
**Interazioni**:
- Validazione carta
- Autorizzazione pagamento
- Gestione transazioni

---

## 📝 Casi d'Uso Dettagliati

### UC1: Visualizza Homepage
**Attore Primario**: Visitatore, Cliente Registrato  
**Precondizioni**: Nessuna  
**Postcondizioni**: Homepage visualizzata con prodotti in evidenza  

**Flusso Principale**:
1. Utente accede a `localhost:8000/`
2. Sistema mostra homepage con:
   - Saluto personalizzato (se loggato)
   - Form login rapido (se non loggato)
   - Sezione "Prodotti in evidenza" (3 prodotti)
   - Pulsante "Vai allo store"

**Flussi Alternativi**:
- A1: Se utente loggato → mostra nome utente nella navbar
- A2: Se utente non loggato → mostra link "Accedi" e "Registrati"

---

### UC5: Registrazione Utente
**Attore Primario**: Visitatore  
**Precondizioni**: Utente non autenticato  
**Postcondizioni**: Nuovo utente creato, sessione attiva, redirect a homepage  

**Flusso Principale**:
1. Visitatore clicca "Registrati"
2. Sistema mostra form registrazione
3. Visitatore compila:
   - Nome
   - Cognome
   - Email
   - Username (opzionale)
   - Password (min 8 caratteri)
   - Conferma password
4. Sistema valida dati:
   - Email formato valido e univoca
   - Username univoco (se fornito)
   - Password ≥ 8 caratteri
   - Password = Conferma password
5. Sistema crea hash password (bcrypt)
6. Sistema salva utente in database
7. Sistema crea sessione PHP
8. Sistema redirect a homepage
9. Sistema mostra messaggio "Benvenuto, [Nome]"

**Flussi Alternativi**:
- A1: Email già esistente → mostra errore "Email già registrata"
- A2: Username già esistente → mostra errore "Username già in uso"
- A3: Password non corrispondono → mostra errore "Le password non coincidono"
- A4: Validazione fallita → mostra errori inline, mantiene dati inseriti (tranne password)

**Flussi Eccezionali**:
- E1: Errore database → mostra pagina errore 500

---

### UC6: Login
**Attore Primario**: Visitatore  
**Precondizioni**: Utente registrato ma non autenticato  
**Postcondizioni**: Sessione utente attiva, redirect a homepage  

**Flusso Principale**:
1. Visitatore inserisce:
   - Email o Username
   - Password
2. Sistema cerca utente per email
3. Se non trovato, cerca per username
4. Sistema verifica password con `password_verify()`
5. Se corretto, sistema crea sessione PHP
6. Sistema rigenera session ID (sicurezza)
7. Sistema redirect a homepage

**Flussi Alternativi**:
- A1: Credenziali errate → mostra "Credenziali non valide"

---

### UC8: Aggiungi al Carrello
**Attore Primario**: Cliente Registrato  
**Precondizioni**: Utente autenticato, prodotto disponibile  
**Postcondizioni**: Prodotto aggiunto al carrello in database  

**Flusso Principale**:
1. Cliente naviga a dettaglio prodotto
2. Cliente seleziona quantità
3. Cliente seleziona opzione (se disponibile)
4. Cliente clicca "Aggiungi al carrello"
5. Sistema verifica autenticazione
6. Sistema recupera/crea carrello per user_id
7. Sistema calcola prezzo unitario (prezzo_base + prezzo_opzione)
8. Sistema verifica se item già nel carrello:
   - Se SÌ: incrementa quantità esistente
   - Se NO: crea nuovo cart_item
9. Sistema salva in `cart_items`
10. Sistema redirect a pagina carrello
11. Sistema mostra messaggio "Prodotto aggiunto"

**Flussi Alternativi**:
- A1: Utente non loggato → redirect a login con messaggio "Effettua login per acquistare"

**Flussi Eccezionali**:
- E1: Prodotto non disponibile → mostra "Prodotto esaurito"

---

### UC10: Checkout
**Attore Primario**: Cliente Registrato  
**Precondizioni**: Carrello non vuoto  
**Postcondizioni**: Ordine creato, pagamento registrato, carrello svuotato  

**Flusso Principale**:
1. Cliente clicca "Procedi al checkout" da carrello
2. Sistema mostra form checkout con 2 sezioni:
   - **Dati Spedizione**:
     - Nome destinatario
     - Via
     - Città
     - CAP
     - Paese (default: Italy)
   - **Dati Pagamento**:
     - Nome intestatario carta
     - Numero carta
     - Mese/Anno scadenza
     - CVV
3. Cliente compila form
4. Sistema valida:
   - Campi spedizione obbligatori compilati
   - Numero carta ≥ 12 cifre
   - Carta non scaduta
5. Sistema calcola totale ordine
6. Sistema avvia transazione database:
   - a) Crea record `addresses`
   - b) Crea record `orders` (status: 'paid')
   - c) Per ogni item carrello:
     - Crea record `order_items`
     - Crea record `purchase_history` (NUOVA TABELLA)
   - d) Salva carta in `payment_cards` (NO CVV, solo last4)
   - e) Crea record `payments` con payment_card_id
   - f) Svuota `cart_items`
7. Sistema commit transazione
8. Sistema redirect a pagina conferma
9. Sistema mostra:
   - Numero ordine
   - Riepilogo prodotti
   - Totale pagato
   - Messaggio "Grazie per l'acquisto"

**Flussi Alternativi**:
- A1: Carrello vuoto → redirect a catalogo con "Carrello vuoto"
- A2: Validazione fallita → mostra errori, mantiene dati (tranne CVV)

**Flussi Eccezionali**:
- E1: Errore transazione → rollback, mostra errore "Errore durante il checkout"

---

### UC14: Visualizza Storico Ordini
**Attore Primario**: Cliente Registrato  
**Precondizioni**: Utente autenticato  
**Postcondizioni**: Lista ordini visualizzata  

**Flusso Principale**:
1. Cliente accede a Dashboard
2. Sistema query `purchase_history` WHERE user_id = :current_user
3. Sistema mostra tabella con:
   - Numero ordine
   - Data
   - Prodotti acquistati (nome, quantità)
   - Totale ordine
   - Carta usata (last4)
   - Stato ordine
   - Città spedizione
4. Cliente può ordinare per:
   - Data (default: più recenti)
   - Importo
   - Prodotto

**Flussi Alternativi**:
- A1: Nessun ordine → mostra "Non hai ancora effettuato ordini" + link catalogo

---

## 🏗️ Diagramma delle Classi (Class Diagram)

```
┌─────────────────────────┐
│       Database          │
│─────────────────────────│
│ - pdo: PDO (static)     │
│─────────────────────────│
│ + getConnection(): PDO  │
└─────────────────────────┘
            △
            │ uses
            │
┌───────────┴──────────┬────────────────┬──────────────────┐
│                      │                │                  │
┌─────────────┐  ┌─────────────┐  ┌──────────┐  ┌─────────────────┐
│    User     │  │   Product   │  │   Cart   │  │ PurchaseHistory │
│─────────────│  │─────────────│  │──────────│  │─────────────────│
│ - id        │  │ - id        │  │ - id     │  │ - id            │
│ - email     │  │ - sku       │  │ - user_id│  │ - order_id      │
│ - password  │  │ - name      │  │──────────│  │ - user_id       │
│ - full_name │  │ - price     │  │ + get()  │  │ - product_id    │
│ - username  │  │ - stock     │  │ + add()  │  │ - quantity      │
│─────────────│  │ - image     │  │ + clear()│  │ - card_last4    │
│ + find()    │  │─────────────│  └──────────┘  │ - order_date    │
│ + create()  │  │ + all()     │                │─────────────────│
│ + getById() │  │ + find()    │                │ + getByUser()   │
└─────────────┘  │ + getAll()  │                │ + getByOrder()  │
                 └─────────────┘                └─────────────────┘

┌──────────────────────┐
│   AuthController     │
│──────────────────────│
│ + register()         │
│ + login()            │
│ + logout()           │
└──────────────────────┘

┌──────────────────────┐
│  ProductController   │
│──────────────────────│
│ + index()            │ ← Catalogo
│ + show()             │ ← Dettaglio
└──────────────────────┘

┌──────────────────────┐
│   CartController     │
│──────────────────────│
│ + view()             │
│ + add()              │
│ + clear()            │
└──────────────────────┘

┌──────────────────────┐
│ CheckoutController   │
│──────────────────────│
│ + index()            │
│ + process()          │
└──────────────────────┘

┌──────────────────────┐
│  OrderController     │
│──────────────────────│
│ + confirmation()     │
└──────────────────────┘
```

**Relazioni**:
- Controllers → Models (dependency)
- Models → Database (uses static method)
- Cart → User (1:1)
- CartItems → Cart (N:1)
- Order → User (N:1)
- OrderItems → Order (N:1)
- PurchaseHistory → Order (N:1)
- PurchaseHistory → User (N:1)
- PurchaseHistory → Product (N:1)

---

## 📊 Modello Entità-Relazioni Dettagliato

### Definizione Entità e Attributi

#### Entità: USERS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco utente |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Email univoca per login |
| password | VARCHAR(255) | NOT NULL | Hash password bcrypt |
| full_name | VARCHAR(100) | NOT NULL | Nome completo |
| username | VARCHAR(50) | UNIQUE, NULL | Username alternativo per login |
| created_at | TIMESTAMP | DEFAULT NOW() | Data registrazione |
| updated_at | TIMESTAMP | DEFAULT NOW() ON UPDATE | Data ultimo aggiornamento |
| role | ENUM('user', 'admin') | DEFAULT 'user' | Ruolo utente (user/admin) |

**Primary Key**: id  
**Unique**: email, username  
**Indici**: idx_email, idx_username  

---

#### Entità: PRODUCTS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco prodotto |
| sku | VARCHAR(50) | UNIQUE, NOT NULL | Stock Keeping Unit |
| name | VARCHAR(100) | NOT NULL | Nome commerciale |
| description | TEXT | NOT NULL | Descrizione dettagliata |
| price | DECIMAL(10,2) | NOT NULL | Prezzo in EUR |
| wattage | INT | NOT NULL | Potenza in Watt |
| efficiency | DECIMAL(5,2) | NOT NULL | Efficienza in % |
| category | VARCHAR(50) | NOT NULL | Categoria (Monocristallino, Bifacciale, Flessibile) |
| size | VARCHAR(20) | NOT NULL | Taglia (S, M, L) |
| image | VARCHAR(255) | NULL | Path immagine SVG |
| stock | INT | DEFAULT 0 | Quantità disponibile |
| created_at | TIMESTAMP | DEFAULT NOW() | Data inserimento |
| updated_at | TIMESTAMP | DEFAULT NOW() ON UPDATE | Data modifica |

**Primary Key**: id  
**Unique**: sku  
**Indici**: idx_sku, idx_category, idx_wattage  

---

#### Entità: CARTS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco carrello |
| user_id | INT | FK, NOT NULL | Riferimento a utente |
| total | DECIMAL(10,2) | DEFAULT 0 | Totale carrello |
| created_at | TIMESTAMP | DEFAULT NOW() | Data creazione |
| updated_at | TIMESTAMP | DEFAULT NOW() ON UPDATE | Data ultimo aggiornamento |

**Primary Key**: id  
**Foreign Key**: user_id → users(id)  
**Indici**: idx_user_id (UNIQUE per garantire 1 carrello per utente)  

---

#### Entità: CART_ITEMS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco item |
| cart_id | INT | FK, NOT NULL | Riferimento a carrello |
| product_id | INT | FK, NOT NULL | Riferimento a prodotto |
| quantity | INT | NOT NULL, CHECK(>0) | Quantità ordinata |
| unit_price | DECIMAL(10,2) | NOT NULL | Prezzo unitario al momento dell'aggiunta |
| subtotal | DECIMAL(10,2) | NOT NULL | quantity × unit_price |
| created_at | TIMESTAMP | DEFAULT NOW() | Data aggiunta |

**Primary Key**: id  
**Foreign Keys**: cart_id → carts(id), product_id → products(id)  
**Indici**: idx_cart_id, idx_product_id  
**Constraint Unico**: (cart_id, product_id) per evitare duplicati  

---

#### Entità: ORDERS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco ordine |
| user_id | INT | FK, NOT NULL | Riferimento a cliente |
| address_id | INT | FK, NOT NULL | Riferimento a indirizzo spedizione |
| total | DECIMAL(10,2) | NOT NULL | Importo totale ordine |
| status | ENUM | DEFAULT 'pending' | Stato ordine (pending, paid, shipped, delivered, cancelled) |
| created_at | TIMESTAMP | DEFAULT NOW() | Data ordine |
| updated_at | TIMESTAMP | DEFAULT NOW() ON UPDATE | Data ultimo aggiornamento |

**Primary Key**: id  
**Foreign Keys**: user_id → users(id), address_id → addresses(id)  
**Indici**: idx_user_id, idx_created_at, idx_status  

---

#### Entità: ORDER_ITEMS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco riga ordine |
| order_id | INT | FK, NOT NULL | Riferimento a ordine |
| product_id | INT | FK, NOT NULL | Riferimento a prodotto |
| quantity | INT | NOT NULL, CHECK(>0) | Quantità acquistata |
| unit_price | DECIMAL(10,2) | NOT NULL | Prezzo unitario al momento dell'ordine |
| subtotal | DECIMAL(10,2) | NOT NULL | quantity × unit_price |

**Primary Key**: id  
**Foreign Keys**: order_id → orders(id), product_id → products(id)  
**Indici**: idx_order_id, idx_product_id  

---

#### Entità: ADDRESSES
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco indirizzo |
| user_id | INT | FK, NOT NULL | Riferimento a utente |
| street | VARCHAR(255) | NOT NULL | Via e numero civico |
| city | VARCHAR(100) | NOT NULL | Città |
| postal_code | VARCHAR(10) | NOT NULL | CAP |
| country | VARCHAR(50) | NOT NULL | Paese |
| is_primary | BOOLEAN | DEFAULT 0 | Indirizzo principale (per future spedizioni) |
| created_at | TIMESTAMP | DEFAULT NOW() | Data creazione |

**Primary Key**: id  
**Foreign Key**: user_id → users(id)  
**Indici**: idx_user_id, idx_city  

---

#### Entità: PAYMENT_CARDS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco carta |
| user_id | INT | FK, NOT NULL | Riferimento a utente |
| cardholder_name | VARCHAR(100) | NOT NULL | Intestatario carta |
| last4 | CHAR(4) | NOT NULL | Ultimi 4 digit della carta (NO numero completo) |
| exp_month | INT | NOT NULL, CHECK(1-12) | Mese scadenza |
| exp_year | INT | NOT NULL | Anno scadenza (YYYY) |
| card_type | ENUM('visa', 'mastercard', 'amex') | NOT NULL | Tipo carta |
| is_default | BOOLEAN | DEFAULT 0 | Carta predefinita |
| created_at | TIMESTAMP | DEFAULT NOW() | Data aggiunta |

**Primary Key**: id  
**Foreign Key**: user_id → users(id)  
**Indici**: idx_user_id, idx_last4  
**NOTE SICUREZZA**: CVV NEVER salvato in database per conformità PCI-DSS  

---

#### Entità: PAYMENTS
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco transazione |
| order_id | INT | FK, NOT NULL, UNIQUE | Riferimento a ordine (1:1) |
| payment_card_id | INT | FK, NOT NULL | Riferimento a carta usata |
| amount | DECIMAL(10,2) | NOT NULL | Importo pagato |
| status | ENUM | DEFAULT 'pending' | Stato pagamento (pending, authorized, completed, declined, refunded) |
| transaction_id | VARCHAR(100) | UNIQUE, NULL | ID transazione gateway pagamento |
| created_at | TIMESTAMP | DEFAULT NOW() | Data transazione |
| updated_at | TIMESTAMP | DEFAULT NOW() ON UPDATE | Data ultimo aggiornamento |

**Primary Key**: id  
**Foreign Keys**: order_id → orders(id), payment_card_id → payment_cards(id)  
**Indici**: idx_order_id, idx_card_id, idx_created_at  

---

#### Entità: PURCHASE_HISTORY (Denormalizzata per Report)
| Attributo | Tipo | Vincoli | Descrizione |
|---|---|---|---|
| id | INT | PK, AI | Identificatore univoco record storico |
| order_id | INT | FK, NOT NULL | Riferimento a ordine |
| user_id | INT | FK, NOT NULL | ID cliente |
| user_email | VARCHAR(255) | NOT NULL | Email cliente (snapshot) |
| user_name | VARCHAR(100) | NOT NULL | Nome cliente (snapshot) |
| product_id | INT | FK, NOT NULL | ID prodotto |
| product_name | VARCHAR(100) | NOT NULL | Nome prodotto (snapshot) |
| product_sku | VARCHAR(50) | NOT NULL | SKU prodotto (snapshot) |
| quantity | INT | NOT NULL | Quantità ordinata |
| unit_price | DECIMAL(10,2) | NOT NULL | Prezzo unitario |
| subtotal | DECIMAL(10,2) | NOT NULL | quantity × unit_price |
| order_total | DECIMAL(10,2) | NOT NULL | Totale ordine |
| payment_method | VARCHAR(50) | NOT NULL | Metodo pagamento |
| payment_status | VARCHAR(20) | NOT NULL | Stato pagamento |
| card_id | INT | NULL | ID carta utilizzata |
| card_last4 | CHAR(4) | NULL | Ultimi 4 digit carta |
| cardholder_name | VARCHAR(100) | NULL | Nome intestatario (snapshot) |
| shipping_city | VARCHAR(100) | NOT NULL | Città spedizione |
| shipping_country | VARCHAR(50) | NOT NULL | Paese spedizione |
| order_status | VARCHAR(20) | NOT NULL | Stato ordine |
| order_date | TIMESTAMP | NOT NULL | Data ordine |
| created_at | TIMESTAMP | DEFAULT NOW() | Data inserimento record |

**Primary Key**: id  
**Foreign Keys**: order_id, user_id, product_id, card_id  
**Indici**: idx_purchase_order (order_id), idx_purchase_user (user_id), idx_purchase_product (product_id), idx_purchase_card (card_last4), idx_purchase_date (order_date)  
**NOTE**: Denormalizzata intenzionalmente per query reporting veloci (non violazione 3NF ma per performance)  

---

### Diagramma ER Completo (Tabellare)

| Entità | Colonne | PK | FK | Cardinalità |
|---|---|---|---|---|
| USERS | 8 | id | - | - |
| PRODUCTS | 13 | id | - | - |
| CARTS | 5 | id | user_id | 1:1 |
| CART_ITEMS | 7 | id | cart_id, product_id | N:1, N:1 |
| ORDERS | 7 | id | user_id, address_id | N:1, N:1 |
| ORDER_ITEMS | 6 | id | order_id, product_id | N:1, N:1 |
| ADDRESSES | 8 | id | user_id | N:1 |
| PAYMENT_CARDS | 10 | id | user_id | N:1 |
| PAYMENTS | 8 | id | order_id, payment_card_id | 1:1, N:1 |
| PURCHASE_HISTORY | 21 | id | order_id, user_id, product_id, card_id | N:1, N:1, N:1, N:1 |

**Totale Entità**: 10  
**Totale Attributi**: 87  
**Totale Relazioni Esplicite**: 12  
**Totale Indici Previsti**: 20+  

---

### Relazioni Dettagliate

#### Relazione 1: USER → CARTS (1:1)
- **Tipo**: One-to-One
- **Cardinalità**: 1..1 per user, 1..1 per cart
- **FK**: carts.user_id
- **Significato**: Ogni utente ha esattamente 1 carrello
- **Operazione Cascata**: DELETE user → DELETE cart (o soft-delete)

#### Relazione 2: USER → ORDERS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 user può avere N ordini
- **FK**: orders.user_id
- **Significato**: Un cliente può effettuare più ordini
- **Operazione Cascata**: DELETE user → DELETE orders

#### Relazione 3: USER → ADDRESSES (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 user può avere N indirizzi
- **FK**: addresses.user_id
- **Significato**: Un cliente può avere più indirizzi di spedizione
- **Operazione Cascata**: DELETE user → DELETE addresses

#### Relazione 4: USER → PAYMENT_CARDS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 user può avere N carte
- **FK**: payment_cards.user_id
- **Significato**: Un cliente può registrare più carte
- **Operazione Cascata**: DELETE user → DELETE payment_cards

#### Relazione 5: CART → CART_ITEMS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 cart ha N items
- **FK**: cart_items.cart_id
- **Significato**: Un carrello contiene uno o più articoli
- **Operazione Cascata**: DELETE cart → DELETE cart_items

#### Relazione 6: PRODUCT → CART_ITEMS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 product in N cart_items
- **FK**: cart_items.product_id
- **Significato**: Un prodotto può essere in più carrelli
- **Operazione Cascata**: DELETE product → SET NULL o CASCADE

#### Relazione 7: ORDERS → ORDER_ITEMS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 order ha N items
- **FK**: order_items.order_id
- **Significato**: Un ordine contiene uno o più articoli
- **Operazione Cascata**: DELETE order → DELETE order_items

#### Relazione 8: PRODUCT → ORDER_ITEMS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 product in N order_items
- **FK**: order_items.product_id
- **Significato**: Un prodotto è stato ordinato più volte
- **Operazione Cascata**: DELETE product → SET NULL o CASCADE

#### Relazione 9: ADDRESSES → ORDERS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 address in N orders
- **FK**: orders.address_id
- **Significato**: Uno stesso indirizzo può essere usato per più ordini
- **Operazione Cascata**: DELETE address → SET NULL

#### Relazione 10: ORDERS → PAYMENTS (1:1)
- **Tipo**: One-to-One
- **Cardinalità**: 1 order ha 1 payment
- **FK**: payments.order_id (UNIQUE)
- **Significato**: Un ordine ha esattamente una transazione di pagamento
- **Operazione Cascata**: DELETE order → DELETE payment

#### Relazione 11: PAYMENT_CARDS → PAYMENTS (1:N)
- **Tipo**: One-to-Many
- **Cardinalità**: 1 card in N payments
- **FK**: payments.payment_card_id
- **Significato**: Una carta può essere usata per più pagamenti
- **Operazione Cascata**: DELETE card → SET NULL o CASCADE

#### Relazione 12: ORDERS → PURCHASE_HISTORY (1:N)
- **Tipo**: One-to-Many (denormalizzato)
- **Cardinalità**: 1 order ha N purchase_history items
- **FK**: purchase_history.order_id
- **Significato**: Ogni riga di purchase_history rappresenta un item ordinato
- **Operazione Cascata**: DELETE order → DELETE purchase_history

---

## 🏗️ Diagramma Entità-Relazioni (ER Diagram)

```
┌──────────┐
│  users   │
│──────────│
│ PK id    │──┐
│    email │  │
│    pass  │  │
│    name  │  │
└──────────┘  │
              │ 1
              │
              │ N
          ┌───┴────────┐
          │            │
     ┌────▼──────┐ ┌───▼──────────┐
     │   carts   │ │   orders     │
     │───────────│ │──────────────│
     │ PK id     │ │ PK id        │──┐
     │ FK user_id│ │ FK user_id   │  │
     └───────────┘ │ FK address_id│  │
          │        │    total     │  │
          │ 1      │    status    │  │
          │        └──────────────┘  │
          │ N                        │ 1
     ┌────▼────────┐                 │
     │ cart_items  │                 │ N
     │─────────────│            ┌────▼─────────┐         ┌──────────────┐
     │ PK id       │            │ order_items  │         │   payments   │
     │ FK cart_id  │            │──────────────│         │──────────────│
     │ FK prod_id  │            │ PK id        │         │ PK id        │
     │    quantity │            │ FK order_id  │◄────────│ FK order_id  │
     │    price    │            │ FK product_id│         │ FK card_id   │
     └─────────────┘            │    quantity  │         │    amount    │
                                │    subtotal  │         │    status    │
                                └──────────────┘         └──────────────┘
                                        │
                                        │ duplica
                                        ▼
                              ┌─────────────────────┐
                              │ purchase_history    │
                              │─────────────────────│
                              │ PK id               │
                              │ FK order_id         │
                              │ FK user_id          │
                              │ FK product_id       │
                              │    user_name        │
                              │    product_name     │
                              │    quantity         │
                              │    card_last4       │
                              │    order_date       │
                              │    subtotal         │
                              └─────────────────────┘

┌────────────┐
│  products  │
│────────────│
│ PK id      │◄────┐
│    sku     │     │
│    name    │     │
│    price   │     │
│    image   │     │
│    stock   │     │
└────────────┘     │
                   │
            ┌──────┴───────┐
            │              │
    (cart_items)    (order_items)

┌──────────────┐
│  addresses   │
│──────────────│
│ PK id        │
│ FK user_id   │
│    street    │
│    city      │
│    postal    │
└──────────────┘

┌──────────────┐
│payment_cards │
│──────────────│
│ PK id        │◄────(payments.card_id)
│ FK user_id   │
│    last4     │
│    exp_month │
│    exp_year  │
└──────────────┘
```

**Cardinalità Principali**:
- User → Cart: 1:1
- User → Orders: 1:N
- User → PaymentCards: 1:N
- Cart → CartItems: 1:N
- Order → OrderItems: 1:N
- Order → Payments: 1:N
- Order → PurchaseHistory: 1:N
- Product → CartItems: 1:N
- Product → OrderItems: 1:N
- PaymentCard → Payments: 1:N

---

## ⚙️ Business Rules

### BR1: Regole di Validazione Utente

**BR1.1**: Email Univocità
- Requisito: Ogni email deve essere unica nel sistema
- Logica: Prima di registrare utente, verificare `SELECT COUNT(*) FROM users WHERE email = ?`
- Eccezione: Impossibile registrare se email già esiste
- Implementazione: UNIQUE constraint a DB + validazione applicativa

**BR1.2**: Password Minima Complessità
- Requisito: Password minimo 8 caratteri
- Logica: Validazione lato server su lunghezza
- Eccezione: Rifiutare registrazione se pwd < 8 caratteri
- Implementazione: strlen($password) >= 8 in AuthController

**BR1.3**: Password Hashing
- Requisito: Password NON salvata in chiaro
- Logica: Usare `password_hash($pwd, PASSWORD_BCRYPT, ['cost' => 10])`
- Verifica: `password_verify($pwd, $hashedPwd)`
- Implementazione: PHP built-in password functions

**BR1.4**: Role Utente Default
- Requisito: Nuovo utente automaticamente 'user' (non 'admin')
- Logica: `INSERT INTO users (..., role) VALUES (..., 'user')`
- Eccezione: Solo admin può cambiare ruoli
- Implementazione: DEFAULT 'user' in schema

---

### BR2: Regole Carrello

**BR2.1**: Un Carrello per Utente
- Requisito: Ogni utente ha esattamente 1 carrello
- Logica: Check (1:1) relationship CARTS.user_id UNIQUE
- Azione: Se non esiste carrello per user_id, crearlo automaticamente
- Implementazione: UNIQUE constraint + trigger/application logic

**BR2.2**: Aggiunta Prodotto Duplicato
- Requisito: Se prodotto già nel carrello, incrementare quantità
- Logica: 
  ```sql
  IF EXISTS (SELECT 1 FROM cart_items WHERE cart_id=? AND product_id=?)
    UPDATE cart_items SET quantity = quantity + ?, subtotal = unit_price * (quantity + ?)
  ELSE
    INSERT INTO cart_items (cart_id, product_id, quantity, unit_price, subtotal)
  ```
- Implementazione: CartController::add() con logica IF/ELSE

**BR2.3**: Calcolo Subtotale Item
- Requisito: subtotal = unit_price × quantity
- Logica: Calcolare sia lato applicazione che DB (trigger/stored procedure opzionale)
- Validazione: Se quantity ≤ 0, rifiutare (CHECK constraint)
- Implementazione: Calcolo in CartController + UPDATE statement

**BR2.4**: Calcolo Totale Carrello
- Requisito: Total = SUM(subtotal di tutti items)
- Logica: `SELECT SUM(subtotal) FROM cart_items WHERE cart_id = ?`
- Aggiornamento: Dopo ogni add/remove/modify di item
- Implementazione: Query in CartController::view()

**BR2.5**: Prezzo Congelato al Momento Aggiunta
- Requisito: Se prezzo prodotto cambia, carrello preserva prezzo originale
- Logica: Salvare `unit_price` al momento dell'aggiunta (non riferimento FK)
- Implicazione: Modifiche future a products.price NON influenzano carrelli esistenti
- Implementazione: Colonna `unit_price` in cart_items (NOT NULL, snapshot)

---

### BR3: Regole Ordine e Checkout

**BR3.1**: Validazione Carrello Non Vuoto
- Requisito: Non è possibile fare checkout con carrello vuoto
- Logica: Verificare `SELECT COUNT(*) FROM cart_items WHERE cart_id = ?` > 0
- Eccezione: Rifiutare checkout, mostrare "Carrello vuoto"
- Implementazione: Check in CheckoutController::index()

**BR3.2**: Validazione Dati Spedizione Obbligatori
- Requisito: Tutti i campi spedizione obbligatori
- Campi: street, city, postal_code, country
- Logica: Validazione NOT NULL lato applicazione
- Implementazione: isset() e !empty() per ogni campo

**BR3.3**: Validazione Numero Carta
- Requisito: Numero carta minimo 12 cifre (PCI-DSS min requirement)
- Logica: `strlen(preg_replace('/\D/', '', $cardNumber)) >= 12`
- Algoritmo Luhn: Opzionale per validazione aggiuntiva
- Implementazione: Validazione regex + lunghezza

**BR3.4**: CVV Non Salvato
- Requisito: CVV NEVER salvato in database (PCI-DSS requirement)
- Logica: Accettare CVV come input, validare, poi scartare
- Salvataggio: Solo last4 cifre della carta in payment_cards
- Implementazione: Non includere CVV in INSERT statements

**BR3.5**: Transazione Atomica Checkout
- Requisito: Tutto o niente - se un'operazione fallisce, tutto rollback
- Sequenza:
  1. BEGIN TRANSACTION
  2. INSERT addresses
  3. INSERT orders
  4. INSERT order_items (per ogni item carrello)
  5. INSERT payment_cards
  6. INSERT payments
  7. INSERT purchase_history (per ogni item)
  8. DELETE cart_items
  9. COMMIT or ROLLBACK
- Implementazione: try/catch con BEGIN/COMMIT/ROLLBACK in CheckoutController

**BR3.6**: Auto-Popolazione Purchase History
- Requisito: Durante checkout, populate automaticamente purchase_history per report
- Logica: Per ogni order_item, inserire riga in purchase_history con dati snapshot
- Dati Snapshot: user_name, product_name, card_last4, etc. (per storico)
- Implicazione: Se prezzo/nome cambia, purchase_history preserva valore originale
- Implementazione: Loop nella transazione checkout

**BR3.7**: Svuotamento Carrello Post-Ordine
- Requisito: Dopo pagamento confermato, eliminare tutti cart_items
- Logica: `DELETE FROM cart_items WHERE cart_id = ?`
- Timing: Parte della transazione checkout (step 8)
- Implicazione: Cliente inizia con carrello vuoto per prossimo ordine
- Implementazione: Parte del commit checkout

**BR3.8**: Prezzo Ordine Congelato
- Requisito: order.total salvato al momento checkout, indipendente da futuri cambi
- Logica: Calcolare SUM(subtotal) da order_items al momento INSERT
- Validazione: order.total = SUM(order_items.subtotal)
- Implementazione: Salvataggio esplicito in CheckoutController

---

### BR4: Regole Pagamento

**BR4.1**: Uno Pagamento per Ordine
- Requisito: 1 order ha esattamente 1 payment
- Cardinalità: 1:1 (UNIQUE su payments.order_id)
- Implicazione: Non è possibile pagare 2 volte stesso ordine (UNIQUE constraint)
- Implementazione: UNIQUE(order_id) in schema

**BR4.2**: Collegamento Pagamento a Carta
- Requisito: Ogni pagamento registra QUALE carta è stata usata
- Logica: payment_card_id = FK a payment_cards(id)
- Implicazione: Possibile tracciare storico: utente → ordine → pagamento → carta
- Implementazione: FK constraint payments.payment_card_id → payment_cards(id)

**BR4.3**: Ultimo Pagamento Salva Metadati
- Requisito: Salvare informazioni sulla carta per storico
- Dati Salvati: last4, exp_month, exp_year, cardholder_name
- NON Salvare: numero completo, CVV
- Timing: Prima del pagamento, INSERT in payment_cards
- Implementazione: Separate INSERT payment_cards, poi INSERT payments con FK

**BR4.4**: Stato Pagamento
- Requisito: Payment ha stato per tracciare ciclo di vita
- Stati: pending → completed → refunded (opzionale)
- Default: 'pending' per nuovi pagamenti, 'completed' dopo autenticazione gateway
- Implementazione: ENUM in schema, UPDATE post-transazione

---

### BR5: Regole Storico e Report

**BR5.1**: Purchase History è Denormalizzata
- Requisito: purchase_history contiene copie di dati per report veloce
- Implicazione: NOT 3NF (intenzionale per performance)
- Snapshot: Nome prodotto, prezzo, nome utente, last4 carta - salvati al momento acquisto
- Vantaggio: Query su storico NON necessita JOIN complessi (E-R normale)
- Implementazione: Popolo durante checkout con dati snapshot

**BR5.2**: Purchase History Immutabile
- Requisito: purchase_history record sono immutabili (audit trail)
- Logica: INSERT only, NO UPDATE/DELETE
- Implicazione: Storico accurato di transazioni passate
- Implementazione: Trigger DB con REJECT su UPDATE/DELETE (opzionale), policy applicativa

**BR5.3**: Storico Filtrato per Utente
- Requisito: Ogni utente vede solo OWN purchase_history
- Logica: Query sempre con WHERE user_id = ?current_user
- Sicurezza: Admin può vedere tutti
- Implementazione: CHECK in controller prima di query

**BR5.4**: Visibilità Dati Sensibili
- Requisito: Numero carta completo mai mostrato, solo last4
- Logica: SELECT card_last4 FROM purchase_history, NEVER numero completo
- Implicazione: Sicurezza dati cliente anche in reporting
- Implementazione: Column masking in SELECT statements

---

### BR6: Regole Prodotto

**BR6.1**: SKU Univocità
- Requisito: Ogni prodotto ha SKU unico (Stock Keeping Unit)
- Logica: UNIQUE constraint su products.sku
- Implicazione: Impossibile duplicare prodotto stesso SKU
- Implementazione: UNIQUE(sku) in schema

**BR6.2**: Prezzo Non Negativo
- Requisito: price ≥ 0
- Logica: CHECK (price >= 0)
- Implicazione: Impossibile prodotto negativo o gratis (a meno che promo)
- Implementazione: CHECK constraint + validazione app

**BR6.3**: Stock Non Negativo
- Requisito: stock ≥ 0
- Logica: CHECK (stock >= 0)
- Validazione Carrello: Se stock insufficiente, warning
- Implicazione: Possibile oversell se stock non gestito (feature future)
- Implementazione: CHECK constraint

---

### BR7: Regole Sicurezza e Privacy

**BR7.1**: Password Never Logged
- Requisito: Password NEVER salvata in log o sessione plaintext
- Logica: Usare solo hash per validazione
- Implicazione: Password recovery via email token, non password reset
- Implementazione: Nessun log di password, nessun plaintext in sessione

**BR7.2**: Session Regeneration Post-Login
- Requisito: Session ID rigenerato dopo login per prevenire session fixation
- Logica: `session_regenerate_id(true)` dopo password_verify success
- Implicazione: Old session ID invalidato
- Implementazione: AuthController::login() con session_regenerate_id()

**BR7.3**: Timeout Sessione
- Requisito: Sessione scade dopo inattività (opzionale, implementare futura)
- Timeout suggerito: 30 minuti
- Logica: Check $_SESSION['last_activity']
- Implementazione: Middleware future

**BR7.4**: Validazione Lato Server
- Requisito: TUTTI i dati validati lato server, mai fidarsi del client
- Implicazione: Validazione JS è UX, validazione server è SECURITY
- Implementazione: Tutti controller hanno validazione explicity

**BR7.5**: Protezione CSRF (Future)
- Requisito: Token CSRF su form POST/PUT/DELETE
- Logica: Generare random token in session, includere in form, validare submit
- Implementazione: Future, non in MVP

---

### BR8: Regole Autorizzazione

**BR8.1**: Protezione Rotte Private
- Requisito: Rotte carrello, checkout, dashboard solo per utenti loggati
- Logica: Check `isset($_SESSION['user_id'])` all'inizio rotta
- Eccezione: Redirect a login se not set
- Implementazione: Middleware check in router o controller base

**BR8.2**: Isolamento Dati Utente
- Requisito: Utente vede solo OWN dati (carrello, ordini, profilo)
- Logica: Query sempre con WHERE user_id = ?current_user
- Eccezione: Admin vede tutti
- Implementazione: Check prima di query

**BR8.3**: Nessun Accesso Admin Senza Ruolo
- Requisito: Rotte admin richiedono role='admin'
- Logica: Check `$_SESSION['user']['role'] === 'admin'`
- Eccezione: Redirect a home se user.role != 'admin'
- Implementazione: Controller admin con role check

---

### BR9: Regole di Integrità Dati

**BR9.1**: Foreign Key Cascade/Restrict
- Requisito: Mantenere integrità referenziale
- Politiche:
  - DELETE user → CASCADE DELETE orders/carts/payment_cards
  - DELETE product → SET NULL order_items.product_id (per storico)
  - DELETE address → SET NULL orders.address_id
- Implementazione: ON DELETE CASCADE/SET NULL nella definizione FK

**BR9.2**: Indici su Colonne Frequenti
- Requisito: Performance query su FK e WHERE comuni
- Indici: user_id, product_id, order_id, created_at, status, email, sku
- Beneficio: Join e filter più veloce su milioni di record
- Implementazione: CREATE INDEX statements

**BR9.3**: Evitare Deviazioni da Schema
- Requisito: Nessun insert/update con dati che violano schema
- Logica: Prepared statements + validazione app
- Implicazione: No SQL injection, type safety
- Implementazione: PDO prepared statements

---

### BR10: Regole Business Specifiche SoleDomus

**BR10.1**: Categoria Prodotto Fissa
- Requisito: Prodotto rientra in una sola categoria (Monocristallino, Bifacciale, Flessibile)
- Logica: category ENUM limitato a 3 valori
- Implicazione: Filtri catalogo per categoria precisi
- Implementazione: ENUM('Monocristallino', 'Bifacciale', 'Flessibile')

**BR10.2**: Taglia Prodotto Fissa
- Requisito: Prodotto ha una sola taglia (S, M, L)
- Logica: size ENUM limitato a 3 valori
- Implicazione: SKU = categoria + taglia + id univoco
- Implementazione: ENUM('S', 'M', 'L')

**BR10.3**: Wattage Positivo
- Requisito: Potenza pannello > 0 W
- Logica: CHECK (wattage > 0)
- Implicazione: Impossibile inserire pannello 0W
- Implementazione: CHECK constraint

**BR10.4**: Efficienza Tra 0 e 100%
- Requisito: Efficienza pannello tra 0% e 100%
- Logica: CHECK (efficiency BETWEEN 0 AND 100)
- Implicazione: Validazione realistica dati tecnici
- Implementazione: CHECK constraint

---

## 🔄 Diagramma di Sequenza

### Sequenza: Processo di Acquisto Completo

```
Cliente    Browser    Router    ProductCtrl    CartCtrl    CheckoutCtrl    Database    PaymentGateway
  │           │          │            │             │              │            │              │
  │──Naviga─►│          │            │             │              │            │              │
  │           │──GET /──►│            │             │              │            │              │
  │           │          │──products──►            │              │            │              │
  │           │          │            │──getAll()──────────────────────►       │              │
  │           │          │            │◄───products────────────────────────────│              │
  │           │          │◄───HTML────│             │              │            │              │
  │           │◄─render──│            │             │              │            │              │
  │◄─Visualizza           │            │             │              │            │              │
  │                       │            │             │              │            │              │
  │──Aggiungi────────────────────────────────────────►            │            │              │
  │   carrello            │            │             │──add()──────────►        │              │
  │                       │            │             │             │    INSERT cart_items      │
  │                       │            │             │◄────OK───────────────────│              │
  │◄──Redirect cart───────────────────────────────────            │            │              │
  │                       │            │             │              │            │              │
  │──Checkout────────────────────────────────────────────────────►│            │              │
  │                       │            │             │              │──BEGIN────►│              │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──INSERT────►              │
  │                       │            │             │              │  addresses │              │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──INSERT────►              │
  │                       │            │             │              │  orders    │              │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──INSERT────►              │
  │                       │            │             │              │  order_items              │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──INSERT────►              │
  │                       │            │             │              │  payment_cards            │
  │                       │            │             │              │            │              │
  │                       │            │             │              │─validate──────────────────►
  │                       │            │             │              │            │   card       │
  │                       │            │             │              │◄──OK───────────────────────
  │                       │            │             │              │            │              │
  │                       │            │             │              │──INSERT────►              │
  │                       │            │             │              │  payments  │              │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──INSERT────►              │
  │                       │            │             │              │  purchase_history         │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──DELETE────►              │
  │                       │            │             │              │  cart_items│              │
  │                       │            │             │              │            │              │
  │                       │            │             │              │──COMMIT────►              │
  │                       │            │             │              │            │              │
  │◄──Conferma────────────────────────────────────────────────────────────────────            │
  │   ordine              │            │             │              │            │              │
```

---

## 🏛️ Architettura del Sistema

### Pattern Architetturale: MVC (Model-View-Controller)

```
┌─────────────────────────────────────────────────────────────┐
│                      PRESENTATION LAYER                      │
│                                                               │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   Browser    │  │  Bootstrap 5 │  │   Custom CSS │      │
│  │  (Client)    │  │   Framework  │  │    Styles    │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
└───────────────────────────┬─────────────────────────────────┘
                            │ HTTP Request
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                      ROUTING LAYER                           │
│                                                               │
│               public/index.php (Front Controller)            │
│                                                               │
│  • Session Management                                        │
│  • Route Parsing (?route=xxx)                               │
│  • Authentication Guard                                      │
│  • Controller Dispatch                                       │
└───────────────────────────┬─────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                    CONTROLLER LAYER                          │
│                                                               │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │    Auth      │  │   Product    │  │     Cart     │      │
│  │ Controller   │  │  Controller  │  │  Controller  │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│                                                               │
│  ┌──────────────┐  ┌──────────────┐                         │
│  │   Checkout   │  │    Order     │                         │
│  │  Controller  │  │  Controller  │                         │
│  └──────────────┘  └──────────────┘                         │
│                                                               │
│  • Request Validation                                        │
│  • Business Logic Orchestration                             │
│  • View Selection                                            │
└───────────────────────────┬─────────────────────────────────┘
                            │
                ┌───────────┴───────────┐
                ▼                       ▼
┌──────────────────────────┐  ┌──────────────────────────┐
│      MODEL LAYER         │  │      VIEW LAYER          │
│                          │  │                          │
│  ┌────────────┐          │  │  ┌────────────┐         │
│  │  Database  │          │  │  │   Header   │         │
│  │  (Singleton)          │  │  │   Footer   │         │
│  └────────────┘          │  │  └────────────┘         │
│         │                │  │                          │
│    ┌────┴────┐           │  │  ┌────────────┐         │
│    ▼         ▼           │  │  │    Home    │         │
│ ┌──────┐ ┌──────┐        │  │  │  Catalog   │         │
│ │ User │ │ Prod │        │  │  │   Cart     │         │
│ └──────┘ └──────┘        │  │  │  Checkout  │         │
│                          │  │  │   Order    │         │
│ ┌──────┐ ┌──────┐        │  │  └────────────┘         │
│ │ Cart │ │ Order│        │  │                          │
│ └──────┘ └──────┘        │  │  • PHP Templates        │
│                          │  │  • HTML + Bootstrap     │
│ • Data Access            │  │  • Dynamic Content      │
│ • Business Entities      │  └──────────────────────────┘
│ • Validation             │
└───────────┬──────────────┘
            ▼
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                              │
│                                                               │
│                    MySQL Database (Docker)                   │
│                                                               │
│  Tables: users, products, carts, cart_items,                │
│          orders, order_items, payments, payment_cards,      │
│          addresses, purchase_history                         │
└─────────────────────────────────────────────────────────────┘
```

### Principi Architetturali Applicati

1. **Separation of Concerns**: MVC separa logica, dati, presentazione
2. **Single Responsibility**: Ogni classe/file ha un'unica responsabilità
3. **DRY (Don't Repeat Yourself)**: Layout comuni (header/footer)
4. **Dependency Injection**: Database connection iniettata nei Model
5. **Front Controller Pattern**: Unico entry point (`index.php`)

---

## 📖 User Stories

### Epic 1: Gestione Utente

**US1.1**: Come visitatore, voglio registrarmi al sito per poter acquistare prodotti  
**Criteri di accettazione**:
- Form con nome, cognome, email, username, password
- Validazione email univoca
- Password min 8 caratteri con hashing bcrypt
- Redirect automatico a homepage dopo registrazione

**US1.2**: Come visitatore registrato, voglio fare login per accedere al mio account  
**Criteri di accettazione**:
- Login con email O username
- Verifica password con `password_verify()`
- Session regeneration per sicurezza
- Messaggio errore generico se credenziali errate

**US1.3**: Come cliente loggato, voglio visualizzare la mia dashboard per vedere ordini  
**Criteri di accettazione**:
- Link "Dashboard" visibile solo se loggato
- Mostra saluto personalizzato con nome
- Lista ultimi 10 ordini
- Link veloce al catalogo

---

### Epic 2: Gestione Catalogo

**US2.1**: Come visitatore, voglio navigare il catalogo per vedere i pannelli disponibili  
**Criteri di accettazione**:
- Griglia responsive con card prodotti
- Ogni card mostra: immagine, nome, potenza, prezzo
- Pulsante "Dettagli" su ogni card
- Catalogo accessibile senza login

**US2.2**: Come visitatore, voglio cercare prodotti per trovare pannelli specifici  
**Criteri di accettazione**:
- Barra di ricerca nel catalogo
- Ricerca in nome E descrizione prodotto
- Risultati in tempo reale
- Messaggio "Nessun risultato" se non trovato

**US2.3**: Come visitatore, voglio vedere dettagli prodotto per valutare specifiche tecniche  
**Criteri di accettazione**:
- Pagina dedicata con immagine grande
- Specifiche: potenza (W), efficienza (%), categoria, taglia
- Descrizione completa
- Form "Aggiungi al carrello" (visibile solo se loggato)

---

### Epic 3: Gestione Carrello

**US3.1**: Come cliente loggato, voglio aggiungere prodotti al carrello per acquistarli  
**Criteri di accettazione**:
- Pulsante "Aggiungi" nella pagina prodotto
- Selezione quantità (default: 1)
- Redirect automatico a carrello dopo aggiunta
- Messaggio conferma "Prodotto aggiunto"

**US3.2**: Come cliente loggato, voglio visualizzare il carrello per vedere cosa acquisterò  
**Criteri di accettazione**:
- Tabella con: immagine, nome, quantità, prezzo unitario, subtotale
- Totale generale in grassetto
- Pulsanti: "Svuota carrello", "Procedi al checkout"
- Messaggio "Carrello vuoto" se vuoto

**US3.3**: Come cliente loggato, voglio svuotare il carrello per ricominciare  
**Criteri di accettazione**:
- Pulsante "Svuota carrello" ben visibile
- Conferma azione (alert JS o modal)
- DELETE tutti cart_items per user_id
- Messaggio "Carrello svuotato"

---

### Epic 4: Processo di Acquisto

**US4.1**: Come cliente loggato, voglio procedere al checkout per completare l'acquisto  
**Criteri di accettazione**:
- Form con 2 sezioni: Spedizione + Pagamento
- Validazione server-side di tutti i campi
- Calcolo automatico totale
- Nessuna doppia sottomissione

**US4.2**: Come cliente, voglio inserire dati spedizione per ricevere i prodotti  
**Criteri di accettazione**:
- Campi: nome, via, città, CAP, paese
- Paese default "Italy"
- Validazione: tutti i campi obbligatori
- Salvataggio in `addresses` table

**US4.3**: Come cliente, voglio inserire dati pagamento per pagare l'ordine  
**Criteri di accettazione**:
- Campi: intestatario, numero carta, exp_month, exp_year, CVV
- Validazione: numero carta ≥ 12 cifre, carta non scaduta
- CVV NON salvato (sicurezza PCI-DSS)
- Solo last4 cifre salvate in `payment_cards`

**US4.4**: Come cliente, voglio vedere conferma ordine per sapere che è andato a buon fine  
**Criteri di accettazione**:
- Pagina dedicata con numero ordine
- Riepilogo prodotti acquistati
- Totale pagato
- Messaggio "Grazie per l'acquisto"
- Link a "Dashboard" e "Continua shopping"

---

### Epic 5: Storico e Reporting

**US5.1**: Come cliente, voglio vedere storico ordini per tracciare acquisti  
**Criteri di accettazione**:
- Query su `purchase_history` WHERE user_id = current
- Tabella con: data, numero ordine, prodotti, totale, carta usata
- Ordinamento per data (più recenti prima)
- Filtri: per data, per importo, per prodotto

**US5.2**: Come cliente, voglio vedere dettagli ordine per verificare prodotti acquistati  
**Criteri di accettazione**:
- Click su numero ordine → pagina dettaglio
- Mostra tutti i prodotti dell'ordine
- Indirizzo spedizione
- Carta utilizzata (last4)
- Stato ordine (pending/paid/shipped/delivered)

---

## ✅ Requisiti Funzionali

### RF1: Autenticazione e Autorizzazione
- RF1.1: Sistema deve permettere registrazione con email, password, nome completo
- RF1.2: Sistema deve permettere login con email O username
- RF1.3: Sistema deve hashare password con bcrypt (cost ≥ 10)
- RF1.4: Sistema deve rigenerare session ID dopo login
- RF1.5: Sistema deve proteggere rotte private (carrello, checkout, dashboard)
- RF1.6: Sistema deve permettere logout con distruzione sessione

### RF2: Gestione Prodotti
- RF2.1: Sistema deve mostrare catalogo con tutti i prodotti
- RF2.2: Sistema deve permettere ricerca per nome/descrizione
- RF2.3: Sistema deve mostrare dettaglio prodotto con specifiche complete
- RF2.4: Sistema deve caricare immagini prodotto (SVG o JPG)
- RF2.5: Sistema deve categorizzare prodotti (Monocristallino, Bifacciale, Flessibile)

### RF3: Gestione Carrello
- RF3.1: Sistema deve creare carrello per user_id se non esiste
- RF3.2: Sistema deve permettere aggiunta prodotto al carrello
- RF3.3: Sistema deve incrementare quantità se prodotto già nel carrello
- RF3.4: Sistema deve calcolare subtotale per ogni item (unit_price × quantity)
- RF3.5: Sistema deve calcolare totale carrello (SUM subtotali)
- RF3.6: Sistema deve permettere svuotamento carrello

### RF4: Processo Checkout
- RF4.1: Sistema deve validare dati spedizione (campi obbligatori)
- RF4.2: Sistema deve validare dati pagamento (numero carta, scadenza)
- RF4.3: Sistema deve creare ordine con stato 'paid'
- RF4.4: Sistema deve salvare order_items da cart_items
- RF4.5: Sistema deve salvare carta in payment_cards (NO CVV)
- RF4.6: Sistema deve collegare payment a payment_card tramite FK
- RF4.7: Sistema deve popolare purchase_history per reporting
- RF4.8: Sistema deve svuotare cart_items dopo checkout
- RF4.9: Sistema deve usare transazioni database (BEGIN/COMMIT/ROLLBACK)

### RF5: Storico e Reporting
- RF5.1: Sistema deve mostrare storico ordini per utente loggato
- RF5.2: Sistema deve query purchase_history per report veloci
- RF5.3: Sistema deve mostrare dettagli ordine singolo
- RF5.4: Sistema deve permettere filtri: per data, importo, prodotto, carta

---

## ⚙️ Requisiti Non Funzionali

### RNF1: Performance
- RNF1.1: Tempo risposta pagine < 2 secondi (su rete locale)
- RNF1.2: Query database ottimizzate con indici su FK
- RNF1.3: Lazy loading immagini prodotto
- RNF1.4: Caching CSS con versioning (?v=timestamp)

### RNF2: Sicurezza
- RNF2.1: Password hashing con bcrypt (OWASP compliant)
- RNF2.2: Prepared statements PDO per prevenire SQL injection
- RNF2.3: htmlspecialchars() su tutti gli output per prevenire XSS
- RNF2.4: Session regeneration dopo login per prevenire session fixation
- RNF2.5: CVV non salvato in database (PCI-DSS requirement)
- RNF2.6: HTTPS obbligatorio in produzione (non in dev)

### RNF3: Usabilità
- RNF3.1: Design responsive (mobile-first con Bootstrap 5)
- RNF3.2: Navigazione intuitiva (max 3 click per checkout)
- RNF3.3: Messaggi errore user-friendly (no stack trace)
- RNF3.4: Feedback visivo azioni (success/error messages)
- RNF3.5: Form con validazione inline

### RNF4: Manutenibilità
- RNF4.1: Codice commentato per logica complessa
- RNF4.2: Struttura MVC chiara e separata
- RNF4.3: Nomi variabili/funzioni descrittivi (no abbreviazioni)
- RNF4.4: Documentazione completa (README, DOCUMENTAZIONE.md)
- RNF4.5: Versionamento Git con commit atomici

### RNF5: Scalabilità
- RNF5.1: Database normalizzato (3NF) per evitare duplicazioni
- RNF5.2: Indici su colonne usate in WHERE/JOIN
- RNF5.3: Tabella purchase_history denormalizzata per report veloci
- RNF5.4: Prepared statements riutilizzabili

### RNF6: Affidabilità
- RNF6.1: Transazioni database per checkout (ACID compliance)
- RNF6.2: Rollback automatico in caso errore transazione
- RNF6.3: Validazione dati lato server (mai fidarsi del client)
- RNF6.4: Gestione errori con try/catch

---

## 🎨 Mockup e Wireframe

### Homepage (Non Loggato)

```
┌────────────────────────────────────────────────────────────┐
│  SoleDomus                  Catalogo  Registrati  Accedi   │
└────────────────────────────────────────────────────────────┘
│                                                              │
│  Ciao, benvenuto su SoleDomus                               │
│                                                              │
│  Benvenuto in SoleDomus — qui puoi confrontare e scegliere │
│  pannelli fotovoltaici ad alta efficienza...                │
│                                                              │
│  ┌──────────────────────────────────────────────────────┐  │
│  │ Email o username  │ Password  │ [Accedi]             │  │
│  └──────────────────────────────────────────────────────┘  │
│                                                              │
│  [Vai allo store]                                           │
│                                                              │
│  ─────────────────────────────────────────────────────────  │
│                                                              │
│  Prodotti in evidenza                                       │
│                                                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                 │
│  │ [IMG]    │  │ [IMG]    │  │ [IMG]    │                 │
│  │ Pannello │  │ Pannello │  │ Pannello │                 │
│  │ Mono 150W│  │ Bifa 300W│  │ Flex 80W │                 │
│  │ €199     │  │ €279     │  │ €149     │                 │
│  │ [Vedi]   │  │ [Vedi]   │  │ [Vedi]   │                 │
│  └──────────┘  └──────────┘  └──────────┘                 │
│                                                              │
└────────────────────────────────────────────────────────────┘
│  © SoleDomus                        Progetto demo          │
└────────────────────────────────────────────────────────────┘
```

### Catalogo Prodotti

```
┌────────────────────────────────────────────────────────────┐
│  SoleDomus                  Catalogo  Dashboard  Carrello  │
└────────────────────────────────────────────────────────────┘
│                                                              │
│  Catalogo Pannelli Solari                                   │
│                                                              │
│  [Cerca prodotti...]                                        │
│                                                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                 │
│  │ [IMG]    │  │ [IMG]    │  │ [IMG]    │                 │
│  │ Pannello │  │ Pannello │  │ Pannello │                 │
│  │ Mono     │  │ Bifa     │  │ Flex     │                 │
│  │ 150W     │  │ 300W     │  │ 80W      │                 │
│  │ €199     │  │ €279     │  │ €149     │                 │
│  │ [Dettagli│  │ [Dettagli│  │ [Dettagli│                 │
│  └──────────┘  └──────────┘  └──────────┘                 │
│                                                              │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                 │
│  │ ...      │  │ ...      │  │ ...      │                 │
│  └──────────┘  └──────────┘  └──────────┘                 │
│                                                              │
└────────────────────────────────────────────────────────────┘
```

### Carrello

```
┌────────────────────────────────────────────────────────────┐
│  SoleDomus                  Catalogo  Dashboard  Carrello  │
└────────────────────────────────────────────────────────────┘
│                                                              │
│  Il tuo carrello                                            │
│                                                              │
│  ┌────────────────────────────────────────────────────────┐│
│  │ [IMG] │ Pannello Mono 150W │ x2 │ €199 │ €398        │││
│  │ [IMG] │ Pannello Bifa 300W │ x1 │ €279 │ €279        │││
│  └────────────────────────────────────────────────────────┘│
│                                                              │
│                                        Totale: €677         │
│                                                              │
│  [Svuota carrello]              [Procedi al checkout]      │
│                                                              │
└────────────────────────────────────────────────────────────┘
```

### Checkout

```
┌────────────────────────────────────────────────────────────┐
│  SoleDomus                  Catalogo  Dashboard  Carrello  │
└────────────────────────────────────────────────────────────┘
│                                                              │
│  Checkout                                                    │
│                                                              │
│  ┌─ Dati Spedizione ─────────────────────────────────────┐ │
│  │ Nome destinatario: [____________]                      │ │
│  │ Via:               [____________]                      │ │
│  │ Città:             [____________]  CAP: [_____]        │ │
│  │ Paese:             [Italy      ▼]                      │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│  ┌─ Dati Pagamento ──────────────────────────────────────┐ │
│  │ Intestatario:      [____________]                      │ │
│  │ Numero carta:      [________________]                  │ │
│  │ Scadenza:          [MM▼] [YYYY▼]    CVV: [___]        │ │
│  └────────────────────────────────────────────────────────┘ │
│                                                              │
│                                        Totale: €677         │
│                                                              │
│  [← Torna al carrello]                 [Conferma ordine]   │
│                                                              │
└────────────────────────────────────────────────────────────┘
```

---

## 📝 Note Finali

### Metodologia di Sviluppo
- **Approccio**: Agile iterativo
- **Sprint**: 1 settimana per epic
- **Testing**: Manuale per ogni feature
- **Deployment**: Continuo su branch main

### Tool di Progettazione Utilizzati
- **UML**: Draw.io / Lucidchart
- **Database**: MySQL Workbench / DBDesigner4
- **Mockup**: Balsamiq / Figma
- **Diagrammi**: Mermaid / PlantUML

### Prossimi Passi
1. Review documentazione con stakeholder
2. Approvazione requisiti
3. Setup ambiente sviluppo
4. Inizio sviluppo sprint 1 (Autenticazione)

---

**Data Creazione**: 5 Dicembre 2025  
**Versione**: 1.0  
**Autore**: Giuseppe Greco / SoleDomus Team  
**Status**: Approved for Development
