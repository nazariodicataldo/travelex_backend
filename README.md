# Travelex Backend

Backend API sviluppato con **Laravel** per la piattaforma Travelex.

## Descrizione

Il backend gestisce:

- Autenticazione utenti tramite Laravel Sanctum (token-based)
- CRUD dei post di viaggio
- Commenti e like con toggle automatico
- Relazioni tra utenti e contenuti
- API REST per il frontend Next.js

---

## Tecnologie

- PHP 8.2+
- Laravel
- Laravel Sanctum (autenticazione)
- PostgreSQL
- Eloquent ORM

---

## Installazione

Clona il progetto:

```bash
git clone https://github.com/nazariodicataldo/travelex_backend
cd travelex-backend
composer install
cp .env.example .env
php artisan key:generate
```

---

## Configurazione Database

Modifica il file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=travelex
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## Configurazione CORS

Sempre nel `.env`, aggiungi l'origine del frontend per abilitare le CORS:

```env
FRONTEND_URL=http://localhost:3000
```

---

## Migrazioni

```bash
php artisan migrate
```

---

## Avvio

```bash
php artisan serve
```

Il backend sarà disponibile su: `http://localhost:8000`