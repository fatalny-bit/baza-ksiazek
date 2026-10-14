<?php
declare(strict_types=1);

final class BookController
{
    public function __construct(private readonly BookRepository $books)
    {
    }

    public function handle(): void
    {
        $action = $_POST['action'] ?? $_GET['action'] ?? 'list';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost((string) $action);
            return;
        }

        if ($action === 'add') {
            $this->render('dodaj.php');
            return;
        }

        if ($action === 'edit') {
            try {
                $book = $this->books->find($this->bookId());
            } catch (DomainException $exception) {
                $this->flash('error', $exception->getMessage());
                $this->redirect();
            }

            if ($book === null) {
                $this->flash('error', 'Nie znaleziono wskazanej książki.');
                $this->redirect();
            }

            $this->render('edytuj.php', ['book' => $book]);
            return;
        }

        $this->render('lista.php', ['books' => $this->books->all()]);
    }

    private function handlePost(string $action): void
    {
        if (!hash_equals($_SESSION['csrf_token'], (string) ($_POST['csrf_token'] ?? ''))) {
            http_response_code(400);
            $this->flash('error', 'Formularz wygasł. Odśwież stronę i spróbuj ponownie.');
            $this->redirect();
        }

        try {
            switch ($action) {
                case 'add':
                    [$title, $author] = $this->bookFields();
                    $this->books->add($title, $author);
                    $message = 'Książka została dodana.';
                    break;
                case 'update':
                    [$title, $author] = $this->bookFields();
                    if (!$this->books->update($this->bookId(), $title, $author)) {
                        throw new DomainException('Nie znaleziono wskazanej książki.');
                    }
                    $message = 'Zmiany zostały zapisane.';
                    break;
                case 'delete':
                    if (!$this->books->delete($this->bookId())) {
                        throw new DomainException('Nie można usunąć wypożyczonej książki lub książka już nie istnieje.');
                    }
                    $message = 'Książka została usunięta.';
                    break;
                case 'checkout':
                    $person = trim((string) ($_POST['osoba'] ?? ''));
                    if ($person === '' || mb_strlen($person) > 120) {
                        throw new DomainException('Podaj imię i nazwisko (maksymalnie 120 znaków).');
                    }
                    if (!$this->books->checkout($this->bookId(), $person)) {
                        throw new DomainException('Książka jest już wypożyczona lub nie istnieje.');
                    }
                    $message = 'Wypożyczenie zostało zapisane.';
                    break;
                case 'return':
                    if (!$this->books->returnBook($this->bookId())) {
                        throw new DomainException('Ta książka nie ma aktywnego wypożyczenia.');
                    }
                    $message = 'Zwrot książki został zapisany.';
                    break;
                default:
                    throw new DomainException('Nieznana operacja.');
            }

            $this->flash('success', $message);
        } catch (DomainException $exception) {
            $this->flash('error', $exception->getMessage());
        }

        $this->redirect();
    }

    private function bookFields(): array
    {
        $title = trim((string) ($_POST['tytul'] ?? ''));
        $author = trim((string) ($_POST['autor'] ?? ''));

        if ($title === '' || $author === '' || mb_strlen($title) > 200 || mb_strlen($author) > 160) {
            throw new DomainException('Tytuł i autor są wymagane. Maksymalna długość to 200 i 160 znaków.');
        }

        return [$title, $author];
    }

    private function bookId(): int
    {
        $id = filter_var($_POST['id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id < 1) {
            throw new DomainException('Nieprawidłowy identyfikator książki.');
        }

        return $id;
    }

    private function render(string $view, array $data = []): void
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        $csrfToken = $_SESSION['csrf_token'];
        extract($data, EXTR_SKIP);
        require dirname(__DIR__) . '/views/' . $view;
    }

    private function flash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    private function redirect(): never
    {
        header('Location: /');
        exit;
    }
}