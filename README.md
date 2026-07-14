# Sistem za upravljanje zadacima (Todo App)

Jednostavna aplikacija za praćenje zadataka — dodavanje, obeležavanje kao
završen i brisanje, bez osvežavanja stranice.

## Tehnologije

HTML5, CSS, Vanilla JavaScript (Fetch API), PHP. Podaci se čuvaju u JSON fajlu
(data/zadaci.json).

## Pokretanje (macOS)

1. Instalirati PHP ako nije već instaliran: `brew install php`
2. Iz root foldera projekta pokrenuti: `php -S localhost:8000`
3. Otvoriti u browseru: `http://localhost:8000`

## Struktura projekta

- index.html — glavna stranica (forma + tabela zadataka)
- css/style.css
- js/app.js — dinamički prikaz i Ajax pozivi
- api/helpers.php — funkcije za rad sa JSON fajlom
- api/zadaci.php — obrada zahteva (GET/POST/PUT/DELETE)
- data/zadaci.json — lista zadataka

## Funkcionalnosti

- Prikaz liste zadataka u tabeli
- Dodavanje novog zadatka putem forme, bez osvežavanja stranice
- Obeležavanje zadatka kao završenog/nezavršenog (ID se prenosi preko hidden polja)
- Brisanje zadatka
- Validacija unosa na serverskoj strani (obavezan naziv, ispravan format datuma)
