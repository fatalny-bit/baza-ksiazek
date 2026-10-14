CREATE TABLE IF NOT EXISTS ksiazki (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    tytul TEXT NOT NULL,
    autor TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS wypozyczenia (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    ksiazka_id INTEGER NOT NULL,
    osoba TEXT NOT NULL,
    data_wypozyczenia DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_zwrotu DATETIME,
    FOREIGN KEY (ksiazka_id) REFERENCES ksiazki(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_wypozyczenia_aktywne
    ON wypozyczenia (ksiazka_id, data_zwrotu);

CREATE UNIQUE INDEX IF NOT EXISTS idx_jedno_aktywne_wypozyczenie
    ON wypozyczenia (ksiazka_id)
    WHERE data_zwrotu IS NULL;