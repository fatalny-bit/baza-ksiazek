<?php
declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

require dirname(__DIR__) . '/src/Database.php';
require dirname(__DIR__) . '/src/BookRepository.php';
require dirname(__DIR__) . '/src/BookController.php';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function pluralLabel(int $count, string $one, string $few, string $many): string
{
    $lastTwo = $count % 100;
    $last = $count % 10;

    if ($count === 1) {
        return $one;
    }

    return $last >= 2 && $last <= 4 && ($lastTwo < 12 || $lastTwo > 14) ? $few : $many;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    $controller = new BookController(new BookRepository(Database::connect()));
    $controller->handle();
} catch (Throwable $exception) {
    error_log((string) $exception);
    http_response_code(500);
    echo 'Wystąpił błąd aplikacji. Sprawdź logi kontenera PHP.';
}