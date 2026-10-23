<?php
declare(strict_types=1);

final class BookRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function all(): array
    {
        return $this->pdo->query(
            'SELECT k.id, k.tytul, k.autor, w.osoba AS kto_pozyczyl,
                    w.data_wypozyczenia
             FROM ksiazki k
             LEFT JOIN wypozyczenia w
               ON w.ksiazka_id = k.id AND w.data_zwrotu IS NULL
             ORDER BY k.tytul COLLATE NOCASE, k.autor COLLATE NOCASE'
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, tytul, autor FROM ksiazki WHERE id = :id');
        $statement->execute(['id' => $id]);
        $book = $statement->fetch();

        return $book === false ? null : $book;
    }

    public function add(string $title, string $author): void
    {
        $statement = $this->pdo->prepare('INSERT INTO ksiazki (tytul, autor) VALUES (:title, :author)');
        $statement->execute(['title' => $title, 'author' => $author]);
    }

    public function update(int $id, string $title, string $author): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE ksiazki SET tytul = :title, autor = :author WHERE id = :id'
        );
        $statement->execute(['id' => $id, 'title' => $title, 'author' => $author]);

        return $statement->rowCount() > 0 || $this->find($id) !== null;
    }

    public function delete(int $id): bool
    {
        $statement = $this->pdo->prepare(
            'DELETE FROM ksiazki WHERE id = :id
             AND NOT EXISTS (
                 SELECT 1 FROM wypozyczenia
                 WHERE ksiazka_id = :id AND data_zwrotu IS NULL
             )'
        );
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    public function checkout(int $id, string $person): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO wypozyczenia (ksiazka_id, osoba)
             SELECT :id, :person
             WHERE EXISTS (SELECT 1 FROM ksiazki WHERE id = :id)
               AND NOT EXISTS (
                   SELECT 1 FROM wypozyczenia
                   WHERE ksiazka_id = :id AND data_zwrotu IS NULL
               )'
        );
        $statement->execute(['id' => $id, 'person' => $person]);

        return $statement->rowCount() > 0;
    }

    public function returnBook(int $id): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE wypozyczenia SET data_zwrotu = CURRENT_TIMESTAMP
             WHERE ksiazka_id = :id AND data_zwrotu IS NULL'
        );
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }
}