# Biblioteka

Prosty katalog książek z rejestrem wypożyczeń i zwrotów. Aplikacja działa w PHP-FPM i Nginx, a dane przechowuje w SQLite.

## Uruchomienie

Wymagany jest Docker z wtyczką Docker Compose. W katalogu `dev/biblioteka` uruchom:

```sh
docker compose up --build -d
```

Aplikacja będzie dostępna pod adresem [http://localhost:7001](http://localhost:7001). Port można zmienić zmienną `APP_PORT`, np. `APP_PORT=7002 docker compose up --build -d`.

Dane bazy są przechowywane w wolumenie `sqlite_data`, niezależnie od cyklu życia kontenerów. Zatrzymanie środowiska nie usuwa danych:

```sh
docker compose down
```

Logi aplikacji można sprawdzić poleceniem `docker compose logs -f php nginx`. Usunięcie wolumenu `sqlite_data` kasuje katalog i wszystkie wypożyczenia.

## Funkcje

- Dodawanie, edycja i usuwanie książek.
- Wypożyczanie książki konkretnej osobie i rejestrowanie zwrotu.
- Lista dostępności z aktualnym wypożyczającym oraz datą wypożyczenia.
- Wyszukiwanie po tytule, autorze i osobie wypożyczającej.
- Walidacja formularzy, zapytania PDO i tokeny CSRF.