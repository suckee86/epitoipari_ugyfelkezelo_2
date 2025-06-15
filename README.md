# Építőipari Ügyfélkezelő (példaprojekt)

Ez egy egyszerű PHP alapú bemutató az ügyfélkezelő rendszerhez. A projekt csak példa, nem tartalmazza a részletes specifikáció minden elemét.

## Követelmények
- PHP 8.3
- SQLite3

## Telepítés
1. Telepítsd a szükséges függőségeket (PHP, Composer, SQLite3).
2. Futtasd az adatbázis inicializálását:
   ```bash
   php src/init_db.php
   ```
3. Indítsd el a beépített webszervert:
   ```bash
   php -S localhost:8000 -t src
   ```
4. Lépj be a böngészőben: [http://localhost:8000](http://localhost:8000)

Admin belépéshez a kezdeti adatok:
- email: `admin@example.com`
- jelszó: `admin`

A felület nagyon egyszerű: az admin hozhat létre felhasználókat, a *Felmérő* jogosultságú felhasználó új projektet vehet fel.
