# SoleDomus - Documentazione Completa

## 📋 Indice
1. [Panoramica Progetto](#panoramica-progetto)
2. [User Experience](#user-experience)
3. [Architettura Tecnica](#architettura-tecnica)
4. [Database](#database)
5. [Struttura File](#struttura-file)
6. [Setup e Avvio](#setup-e-avvio)
7. [Funzionalità Implementate](#funzionalità-implementate)

---

## 🎯 Panoramica Progetto

**SoleDomus** è una web app e-commerce specializzata nella vendita di pannelli fotovoltaici. Il progetto permette agli utenti di navigare un catalogo di pannelli solari, visualizzare dettagli tecnici, aggiungere prodotti al carrello e completare l'acquisto con un processo di checkout integrato.

### Tecnologie Utilizzate
- **Backend**: PHP 8+ (built-in server)
- **Database**: MySQL 8.0 (Docker container)
- **Frontend**: Bootstrap 5, CSS personalizzato
- **Architettura**: MVC pattern con routing file-based

---

## 👤 User Experience

### 1. Homepage (Accesso Pubblico)
**Percorso**: `localhost:8000/index.php` oppure `localhost:8000/index.php?route=home`

**Caratteristiche**:
- **Saluto personalizzato**: Se l'utente è loggato, mostra "Ciao [Nome] [Cognome], benvenuto su SoleDomus"
- **Form di login rapido**: Visibile solo per utenti non autenticati, permette di loggarsi direttamente dalla home
- **Sezione "Prodotti in evidenza"**: Mostra dinamicamente i primi 3 prodotti dal database con:
  - Immagine SVG identificativa per categoria
  - Nome prodotto
  - Potenza in Watt e categoria
  - Prezzo
  - Pulsante "Vedi" per dettagli
- **Pulsante "Vai allo store"**: Link al catalogo completo

### 2. Registrazione Utente
**Percorso**: `localhost:8000/index.php?route=register`

**Campi richiesti**:
- Nome
- Cognome
- Email (validata)
- Username (opzionale, min 3 caratteri)
- Password (min 8 caratteri)
- Conferma password

**Validazioni**:
- Email univoca
- Username univoco (se fornito)
- Password complesse
- Matching password/conferma

### 3. Login
**Percorso**: `localhost:8000/index.php?route=login`

**Accesso con**:
- Email + Password
- Username + Password

**Dopo il login**:
- Redirect alla homepage
- Navbar aggiornata con: Dashboard, Carrello, Esci

### 4. Catalogo Prodotti
**Percorso**: `localhost:8000/index.php?route=products`

**Caratteristiche**:
- **Accessibile senza login**
- Griglia responsive con tutti i prodotti
- Barra di ricerca (cerca in nome e descrizione)
- Ogni card mostra:
  - Immagine prodotto
  - Nome
  - Descrizione breve
  - Potenza (W)
  - Prezzo
  - Pulsante "Dettagli"

**Catalogo Attuale (9 Prodotti)**:

#### Monocristallino (Tecnologia Premium)
1. **Pannello Monocristallino da Balcone** (150W) - €199
   - SKU: MONO-S-BALCONE
   - Size: Small
   - Efficienza: 20%
   - Uso: Balconi e terrazze urbane

2. **Pannello Monocristallino Residenziale** (420W) - €349
   - SKU: MONO-M-RESIDENZIALE
   - Size: Medium
   - Efficienza: 21%
   - Uso: Tetti residenziali standard

3. **Pannello Monocristallino Commerciale** (580W) - €499
   - SKU: MONO-L-COMMERCIALE
   - Size: Large
   - Efficienza: 22.5%
   - Uso: Impianti commerciali/industriali

#### Bifacciale (Alta Efficienza)
4. **Pannello Bifacciale da Garden** (300W) - €279
   - SKU: BIFA-S-GARDEN
   - Size: Small
   - Efficienza: 21%
   - Uso: Giardini e aree verdi

5. **Pannello Bifacciale da Pergola** (450W) - €429
   - SKU: BIFA-M-PERGOLA
   - Size: Medium
   - Efficienza: 22%
   - Uso: Pergole fotovoltaiche

6. **Pannello Bifacciale Ground Mount** (650W) - €599
   - SKU: BIFA-L-GROUND
   - Size: Large
   - Efficienza: 23%
   - Uso: Installazioni a terra su larga scala

#### Flessibile (Portatile)
7. **Pannello Flessibile da Zaino** (80W) - €149
   - SKU: FLEX-XS-ZAINO
   - Size: Extra Small
   - Efficienza: 18%
   - Uso: Trekking e outdoor

8. **Pannello Flessibile Marine** (150W) - €229
   - SKU: FLEX-S-MARINE
   - Size: Small
   - Efficienza: 19%
   - Uso: Barche e nautica

9. **Pannello Flessibile Van Life** (300W) - €399
   - SKU: FLEX-L-VANLIFE
   - Size: Large
   - Efficienza: 20%
   - Uso: Camper e van

### 5. Dettaglio Prodotto
**Percorso**: `localhost:8000/index.php?route=product&id=[ID]`

**Caratteristiche**:
- **Accessibile senza login**
- Immagine grande del prodotto
- Descrizione completa
- Specifiche tecniche (potenza, efficienza, categoria, taglia)
- Prezzo
- Form "Aggiungi al carrello" con:
  - Selezione quantità
  - Opzioni prodotto (se disponibili)
  - **Pulsante attivo solo se loggato**

**Comportamento**:
- Se non loggato: mostra messaggio "Effettua il login per acquistare"
- Se loggato: permette di aggiungere al carrello

### 6. Carrello (Richiede Login)
**Percorso**: `localhost:8000/index.php?route=cart`

**Visualizzazione**:
- Lista prodotti con:
  - Immagine
  - Nome + opzione selezionata
  - Prezzo unitario
  - Quantità
  - Subtotale
- **Totale generale** in fondo
- Pulsanti:
  - "Svuota carrello"
  - "Procedi al checkout"

**Funzionalità**:
- Aggiornamento automatico del totale
- Persistenza nel database (tabella `cart_items`)

### 7. Checkout (Richiede Login)
**Percorso**: `localhost:8000/index.php?route=checkout`

**Form Spedizione**:
- Nome destinatario
- Indirizzo (via, città, CAP)
- Paese (default: Italy)

**Form Pagamento**:
- Nome intestatario carta
- Numero carta (min 12 cifre)
- Mese/Anno scadenza
- CVV (non salvato)

**Validazioni**:
- Campi obbligatori
- Numero carta valido
- Carta non scaduta

**Processo**:
1. Validazione dati
2. Creazione indirizzo di spedizione (tabella `addresses`)
3. Creazione ordine (tabella `orders`)
4. Salvataggio articoli ordine (tabella `order_items`)
5. Registrazione pagamento simulato (tabella `payments`)
6. Svuotamento carrello
7. Redirect a conferma ordine

### 8. Conferma Ordine
**Percorso**: `localhost:8000/index.php?route=order_confirmation&id=[ORDER_ID]`

**Visualizzazione**:
- Numero ordine
- Stato: "Pagato"
- Totale importo
- Lista prodotti acquistati
- Messaggio di ringraziamento

### 9. Dashboard (Richiede Login)
**Percorso**: `localhost:8000/index.php?route=dashboard`

**Funzionalità** (placeholder per future implementazioni):
- Storico ordini
- Profilo utente
- Preferiti

---

## 🏗️ Architettura Tecnica

### Pattern MVC Semplificato

```
public/index.php (Router)
    ↓
Controllers/ (Logica business)
    ↓
Models/ (Accesso dati)
    ↓
Database (MySQL)
    ↑
Views/ (Presentazione)
```

### Routing File-Based

Il file `public/index.php` gestisce tutte le richieste tramite parametro `?route=`:

```php
$route = $_GET['route'] ?? '';

// Rotte pubbliche (no autenticazione)
$publicRoutes = ['', 'home', 'products', 'product', 'login', 'register', 'logout'];

// Guard per rotte protette
if (!in_array($route, $publicRoutes) && empty($_SESSION['user_id'])) {
    header('Location: /index.php?route=login');
    exit;
}
```

**Tabella Rotte**:

| Route | Accesso | Controller | View |
|-------|---------|------------|------|
| `` (vuota) | Pubblico | - | `home.php` |
| `home` | Pubblico | - | `home.php` |
| `register` | Pubblico | `AuthController::register()` | `auth/register.php` |
| `login` | Pubblico | `AuthController::login()` | `auth/login.php` |
| `logout` | Pubblico | `AuthController::logout()` | - (redirect) |
| `products` | Pubblico | `ProductController::index()` | `product/catalog.php` |
| `product` | Pubblico | `ProductController::show()` | `product/show.php` |
| `cart` | Protetto | `CartController::view()` | `cart/view.php` |
| `cart_add` | Protetto | `CartController::add()` | - (redirect) |
| `cart_clear` | Protetto | `CartController::clear()` | - (redirect) |
| `checkout` | Protetto | `CheckoutController::index()` | `checkout/form.php` |
| `checkout_process` | Protetto | `CheckoutController::process()` | - (transazione) |
| `order_confirmation` | Protetto | `OrderController::confirmation()` | `order/confirmation.php` |
| `dashboard` | Protetto | - | `dashboard/index.php` |

### Autenticazione

**Sistema basato su sessioni PHP**:

```php
// Login
$_SESSION['user_id'] = $user['id'];
session_regenerate_id(true);

// Logout
session_unset();
session_destroy();

// Verifica
if (empty($_SESSION['user_id'])) {
    // Non autenticato
}
```

**Password**:
- Hash: `password_hash()` con `PASSWORD_DEFAULT` (bcrypt)
- Verifica: `password_verify()`

---

## 🗄️ Database

### Schema Tabelle

#### `users`
```sql
CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) UNIQUE NOT NULL,
  username VARCHAR(100) UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  full_name VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### `products`
```sql
CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  sku VARCHAR(100) UNIQUE NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  category VARCHAR(100),        -- 'Monocristallino', 'Bifacciale', 'Flessibile'
  size_tag VARCHAR(50),         -- 'Small', 'Medium', 'Large', 'Extra Small'
  power_watt INT,               -- Potenza in Watt
  efficiency DECIMAL(5,2),      -- Percentuale efficienza
  price DECIMAL(10,2) NOT NULL,
  stock INT DEFAULT 0,
  image VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### `product_options`
```sql
CREATE TABLE product_options (
  id INT PRIMARY KEY AUTO_INCREMENT,
  product_id INT NOT NULL,
  name VARCHAR(255),
  price_delta DECIMAL(10,2) DEFAULT 0,
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

#### `carts`
```sql
CREATE TABLE carts (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT UNIQUE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### `cart_items`
```sql
CREATE TABLE cart_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  cart_id INT NOT NULL,
  product_id INT NOT NULL,
  product_option_id INT,
  quantity INT DEFAULT 1,
  unit_price DECIMAL(10,2),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cart_id) REFERENCES carts(id),
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (product_option_id) REFERENCES product_options(id)
);
```

#### `addresses`
```sql
CREATE TABLE addresses (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  recipient_name VARCHAR(255),
  street VARCHAR(255),
  city VARCHAR(100),
  postal_code VARCHAR(20),
  country VARCHAR(100) DEFAULT 'Italy',
  is_default BOOLEAN DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### `orders`
```sql
CREATE TABLE orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  address_id INT,
  total_amount DECIMAL(10,2),
  status VARCHAR(50) DEFAULT 'pending', -- 'pending', 'paid', 'shipped', 'delivered'
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (address_id) REFERENCES addresses(id)
);
```

#### `order_items`
```sql
CREATE TABLE order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  product_option_id INT,
  quantity INT,
  unit_price DECIMAL(10,2),
  subtotal DECIMAL(10,2),
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (product_option_id) REFERENCES product_options(id)
);
```

#### `payments`
```sql
CREATE TABLE payments (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  amount DECIMAL(10,2),
  method VARCHAR(50),           -- 'card', 'paypal', etc.
  status VARCHAR(50),            -- 'ok', 'failed', 'pending'
  transaction_ref VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
);
```

#### `payment_cards`
```sql
CREATE TABLE payment_cards (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  cardholder_name VARCHAR(255),
  card_brand VARCHAR(50),
  card_last4 VARCHAR(4),
  exp_month INT,
  exp_year INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

### Relazioni Database

```
users
  ├── carts (1:1)
  │     └── cart_items (1:N)
  │           ├── products (N:1)
  │           └── product_options (N:1)
  ├── addresses (1:N)
  ├── orders (1:N)
  │     ├── order_items (1:N)
  │     │     ├── products (N:1)
  │     │     └── product_options (N:1)
  │     └── payments (1:N)
  └── payment_cards (1:N)

products
  ├── product_options (1:N)
  ├── cart_items (1:N)
  └── order_items (1:N)
```

---

## 📁 Struttura File

```
Lavoro Giuseppe Greco/
│
├── config/
│   └── config.php                    # Configurazione database
│
├── app/
│   ├── Models/
│   │   ├── Database.php              # Singleton PDO connection
│   │   ├── User.php                  # Model utenti
│   │   ├── Product.php               # Model prodotti
│   │   └── Cart.php                  # Model carrello
│   │
│   ├── Controllers/
│   │   ├── AuthController.php        # Login/Register/Logout
│   │   ├── ProductController.php     # Catalogo e dettaglio prodotti
│   │   ├── CartController.php        # Gestione carrello
│   │   ├── CheckoutController.php    # Processo checkout
│   │   └── OrderController.php       # Conferma ordine
│   │
│   └── Views/
│       ├── layout/
│       │   ├── header.php            # Header comune con navbar
│       │   └── footer.php            # Footer comune
│       │
│       ├── auth/
│       │   ├── login.php             # Form login
│       │   └── register.php          # Form registrazione
│       │
│       ├── product/
│       │   ├── catalog.php           # Lista prodotti
│       │   └── show.php              # Dettaglio prodotto
│       │
│       ├── cart/
│       │   └── view.php              # Visualizzazione carrello
│       │
│       ├── checkout/
│       │   └── form.php              # Form checkout
│       │
│       ├── order/
│       │   └── confirmation.php      # Conferma ordine
│       │
│       ├── dashboard/
│       │   └── index.php             # Dashboard utente
│       │
│       └── home.php                  # Homepage
│
├── public/
│   ├── index.php                     # Front controller (router)
│   │
│   ├── css/
│   │   └── style.css                 # Stili personalizzati
│   │
│   └── images/
│       ├── solar_panels_bg.svg       # Sfondo homepage
│       └── products/                 # Immagini prodotti
│           ├── mono-s-balcone.svg
│           ├── mono-m-residenziale.svg
│           ├── mono-l-commerciale.svg
│           ├── bifa-s-garden.svg
│           ├── bifa-m-pergola.svg
│           ├── bifa-l-ground.svg
│           ├── flex-xs-zaino.svg
│           ├── flex-s-marine.svg
│           └── flex-l-vanlife.svg
│
├── scripts/
│   ├── setup_products.php            # Seeding database prodotti
│   ├── verify_database_structure.php # Verifica schema DB
│   ├── generate_placeholder_images.php # Generazione SVG placeholder
│   ├── fix_image_paths.php           # Correzione path immagini
│   ├── install_php_windows.ps1       # Script installazione PHP
│   └── add_php_to_path.ps1           # Aggiunta PHP a PATH
│
├── db/
│   └── sole_domus_schema.sql         # Schema database completo
│
├── docs/
│   └── 01_overview.md                # Documentazione overview
│
├── diagrams/
│   ├── sole_domus_uml.svg            # Diagramma UML
│   └── sole_domus_uml.img
│
├── .gitignore
├── README.md
└── DOCUMENTAZIONE.md                 # Questo file
```

---

## 🚀 Setup e Avvio

### Prerequisiti

1. **PHP 8.0+**
   ```powershell
   # Verifica versione
   php -v
   ```

2. **MySQL 8.0** (Docker)
   ```powershell
   # Avvia container MySQL
   docker run -d --name mysql-test -p 3306:3306 -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=testdb mysql:8.0
   ```

3. **Estensioni PHP Richieste**
   - `pdo_mysql`
   - `mbstring`
   - `session`

### Configurazione Database

1. **Verifica connessione**:
   ```bash
   mysql -h 127.0.0.1 -P 3306 -u root -p testdb
   ```

2. **Importa schema**:
   ```bash
   mysql -h 127.0.0.1 -P 3306 -u root -p testdb < db/sole_domus_schema.sql
   ```

3. **Seeding prodotti**:
   ```powershell
   php scripts/setup_products.php
   ```

4. **Verifica struttura**:
   ```powershell
   php scripts/verify_database_structure.php
   ```

### Configurazione Applicazione

**File `config/config.php`**:
```php
<?php
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'testdb',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4'
    ]
];
```

### Avvio Server

```powershell
# Dalla root del progetto
cd "c:\Users\aless\Desktop\Lavoro Giuseppe Greco"

# Avvia server built-in PHP
php -S 0.0.0.0:8000 -t public

# Output atteso:
# [Mon Dec  2 10:30:00 2024] PHP 8.x Development Server (http://0.0.0.0:8000) started
```

### Accesso

**URL**: `http://localhost:8000/index.php`

**Utente Test** (se presente nel database):
- Email: `user@test.com`
- Password: (quella impostata durante registrazione)

---

## ✨ Funzionalità Implementate

### ✅ Completate

- [x] Autenticazione (login/register/logout)
- [x] Homepage pubblica con login form
- [x] Catalogo prodotti pubblico con ricerca
- [x] Dettaglio prodotto pubblico
- [x] Sistema carrello per utenti loggati
- [x] Checkout completo con validazione
- [x] Gestione ordini
- [x] Conferma ordine
- [x] Database relazionale completo
- [x] 9 prodotti categorizzati (Monocristallino, Bifacciale, Flessibile)
- [x] Immagini SVG placeholder per categorie
- [x] Design responsive Bootstrap 5
- [x] CSS personalizzato con tema blu/bianco
- [x] Validazioni form lato server
- [x] Password hashing (bcrypt)
- [x] Protezione rotte con guard

### 🔄 Parzialmente Implementate

- [ ] Dashboard utente (struttura presente, contenuto minimale)
- [ ] Storico ordini completo
- [ ] Gestione profilo utente
- [ ] Filtri catalogo per categoria/prezzo

### 🎯 Miglioramenti Futuri

**Backend**:
- [ ] CSRF token su tutti i form
- [ ] Flash messages (feedback utente)
- [ ] Validazione input avanzata (XSS prevention)
- [ ] Rate limiting su login
- [ ] Email di conferma registrazione
- [ ] Email notifica ordine
- [ ] Gestione stock prodotti (decremento su acquisto)
- [ ] Sistema di tracking spedizioni
- [ ] Integrazione gateway pagamento reale (Stripe/PayPal)
- [ ] API RESTful per integrazioni esterne

**Frontend**:
- [ ] Filtri prodotti per categoria (dropdown)
- [ ] Ordinamento prodotti (prezzo, potenza, efficienza)
- [ ] Paginazione catalogo
- [ ] Slider immagini prodotto (se multiple)
- [ ] Comparazione prodotti side-by-side
- [ ] Wishlist/Preferiti
- [ ] Reviews e rating prodotti
- [ ] Calcolatore risparmio energetico
- [ ] Chat assistenza clienti
- [ ] Loading states e skeleton screens
- [ ] Progressive Web App (PWA)

**Database**:
- [ ] Indici ottimizzati per query frequenti
- [ ] Stored procedures per operazioni complesse
- [ ] Backup automatici
- [ ] Logging attività utente (audit trail)

**DevOps**:
- [ ] Docker Compose per stack completo (PHP + MySQL + Nginx)
- [ ] CI/CD pipeline (GitHub Actions)
- [ ] Testing automatizzato (PHPUnit)
- [ ] Monitoring e alerting
- [ ] Deployment production-ready

---

## 🎨 Design System

### Palette Colori

```css
:root {
  --sd-blue-900: #0b3d91;  /* Navy scuro - Headers */
  --sd-blue-700: #1e56c6;  /* Blu principale - Bottoni */
  --sd-blue-500: #3b82f6;  /* Blu chiaro - Accenti */
  --sd-white: #ffffff;     /* Sfondo principale */
  --sd-muted: #6b7280;     /* Testo secondario */
}
```

### Colori Categorie Prodotti

- **Monocristallino**: Gradiente blu scuro (#1a1a2e → #0f3460)
- **Bifacciale**: Gradiente blu chiaro (#2193b0 → #6dd5ed)
- **Flessibile**: Gradiente rosa (#ee9ca7 → #ffdde1)

### Componenti UI

**Card Prodotto**:
- Border radius: 12px
- Hover: translateY(-6px) con shadow
- Immagine: 240px height (180px su mobile)

**Bottoni**:
- Primary (`.btn-sd`): Gradient blu con hover lift
- Outline (`.btn-outline-sd`): Border bianco, sfondo trasparente
- Dimensioni: padding 10px 16px, font-weight 700

**Form**:
- Input: border-radius 10px, focus shadow blu
- Validazione: errori in rosso, successo in verde

---

## 🔒 Sicurezza

### Implementate

- ✅ Password hashing con bcrypt
- ✅ Prepared statements PDO (SQL injection prevention)
- ✅ Session-based authentication
- ✅ `htmlspecialchars()` su tutti gli output (XSS prevention)
- ✅ Route guard (protezione rotte riservate)
- ✅ Session regeneration su login

### Da Implementare

- ⚠️ CSRF tokens
- ⚠️ Rate limiting
- ⚠️ Content Security Policy headers
- ⚠️ HTTPS enforcement
- ⚠️ Input sanitization avanzata
- ⚠️ Secure cookie flags (httponly, secure, samesite)

---

## 📊 Database: Dati di Test

### Utenti Esempio
```sql
INSERT INTO users (email, password_hash, full_name) VALUES
('admin@soledomus.it', PASSWORD_HASH, 'Admin SoleDomus'),
('mario.rossi@email.it', PASSWORD_HASH, 'Mario Rossi');
```

### Flow Acquisto Completo

```
1. Utente si registra → users table
2. Visita catalogo → products table (query)
3. Aggiunge prodotti → carts + cart_items tables
4. Procede al checkout → addresses table (nuovo indirizzo)
5. Completa pagamento → orders + order_items + payments tables
6. Carrello svuotato → DELETE cart_items
7. Visualizza conferma → SELECT orders JOIN order_items
```

---

## 📞 Supporto e Contributi

**Autore**: Giuseppe Greco  
**Progetto**: SoleDomus E-Commerce  
**Data Creazione**: Dicembre 2024  
**Versione**: 1.0

### Come Contribuire

1. Fork del repository
2. Crea branch feature (`git checkout -b feature/nuova-funzionalita`)
3. Commit (`git commit -m 'Aggiungi nuova funzionalità'`)
4. Push al branch (`git push origin feature/nuova-funzionalita`)
5. Apri Pull Request

---

## 📝 Note Finali

### Punti di Forza

✅ **Architettura pulita**: MVC pattern chiaro e mantenibile  
✅ **Database normalizzato**: Relazioni ben definite, no duplicazioni  
✅ **UX intuitiva**: Flow d'acquisto lineare e semplice  
✅ **Design moderno**: Bootstrap 5 + CSS custom, responsive  
✅ **Sicurezza base**: Password hash, prepared statements, XSS prevention  
✅ **Catalogo specializzato**: 9 prodotti reali con specs tecniche accurate

### Aree di Miglioramento

⚠️ **Testing**: Assente (aggiungere PHPUnit)  
⚠️ **Logging**: Minimale (implementare logger strutturato)  
⚠️ **Error handling**: Basic (serve gestione errori centralizzata)  
⚠️ **Validazione**: Server-side solo, aggiungere client-side JS  
⚠️ **Performance**: No caching, no ottimizzazioni query avanzate  
⚠️ **Mobile**: Responsive ma non ottimizzato per touch

### Licenza

Progetto didattico/dimostrativo - Uso interno/educativo

---

**Ultima modifica**: 2 Dicembre 2024  
**Status**: Production-Ready (con nota per implementazioni sicurezza aggiuntive)
