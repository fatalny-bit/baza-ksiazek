<?php
$pageTitle = 'Twój księgozbiór';
$currentPage = 'list';
require __DIR__ . '/partials/header.php';
$borrowedCount = count(array_filter($books, static fn (array $book): bool => $book['kto_pozyczyl'] !== null));
?>
<section class="catalog-summary" aria-label="Podsumowanie księgozbioru">
    <div class="summary-copy">
        <span class="summary-label">RAZEM W KATALOGU</span>
        <strong><?= count($books) ?></strong>
        <span><?= e(pluralLabel(count($books), 'książka', 'książki', 'książek')) ?></span>
    </div>
    <div class="summary-divider" aria-hidden="true"></div>
    <div class="summary-copy summary-secondary">
        <span class="summary-label">AKTUALNIE U CZYTELNIKÓW</span>
        <strong><?= $borrowedCount ?></strong>
        <span><?= e(pluralLabel($borrowedCount, 'wypożyczona', 'wypożyczone', 'wypożyczonych')) ?></span>
    </div>
    <span class="summary-decoration" aria-hidden="true">Aa</span>
</section>

<section class="catalog" aria-label="Lista książek">
    <div class="catalog-topline">
        <h2>Wszystkie pozycje <span class="count-pill" id="visible-count"><?= count($books) ?></span></h2>
        <span class="sort-label">TYTUŁ <span aria-hidden="true">↑</span></span>
    </div>
    <?php if ($books === []): ?>
        <div class="empty-state">
            <span class="empty-glyph" aria-hidden="true">▤</span>
            <h3>Tu zaczyna się Twoja biblioteka</h3>
            <p>Dodaj pierwszą książkę, aby uporządkować swój księgozbiór.</p>
            <a class="button" href="/?action=add">＋ Dodaj książkę</a>
        </div>
    <?php else: ?>
        <div class="book-list" id="book-list">
            <?php foreach ($books as $book): ?>
                <?php $isBorrowed = $book['kto_pozyczyl'] !== null; ?>
                <article class="book-row" data-search="<?= e(mb_strtolower($book['tytul'] . ' ' . $book['autor'] . ' ' . ($book['kto_pozyczyl'] ?? ''), 'UTF-8')) ?>">
                    <div class="book-index"><?= str_pad((string) $book['id'], 2, '0', STR_PAD_LEFT) ?></div>
                    <div class="book-info">
                        <h3><?= e($book['tytul']) ?></h3>
                        <p><?= e($book['autor']) ?></p>
                    </div>
                    <div class="book-status">
                        <span class="status-dot<?= $isBorrowed ? ' is-borrowed' : '' ?>" aria-hidden="true"></span>
                        <div>
                            <strong><?= $isBorrowed ? 'Wypożyczona' : 'Dostępna' ?></strong>
                            <?php if ($isBorrowed): ?>
                                <span><?= e($book['kto_pozyczyl']) ?><?= $book['data_wypozyczenia'] ? ' · ' . e(date('d.m.Y', strtotime($book['data_wypozyczenia']))) : '' ?></span>
                            <?php else: ?>
                                <span>Na półce</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="book-actions">
                        <?php if ($isBorrowed): ?>
                            <form method="post" action="/" class="inline-form">
                                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                <input type="hidden" name="action" value="return">
                                <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                                <button class="button button-outline" type="submit" title="Zarejestruj zwrot">Zwrot</button>
                            </form>
                        <?php else: ?>
                            <form method="post" action="/" class="checkout-form">
                                <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                                <input type="hidden" name="action" value="checkout">
                                <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                                <label class="sr-only" for="person-<?= (int) $book['id'] ?>">Wypożyczający</label>
                                <input id="person-<?= (int) $book['id'] ?>" name="osoba" maxlength="120" placeholder="Imię i nazwisko" required>
                                <button class="button button-outline" type="submit">Wypożycz</button>
                            </form>
                        <?php endif; ?>
                        <a class="icon-button" href="/?action=edit&amp;id=<?= (int) $book['id'] ?>" aria-label="Edytuj: <?= e($book['tytul']) ?>" title="Edytuj książkę">✎</a>
                        <form method="post" action="/" class="inline-form" data-confirm="Usunąć książkę „<?= e($book['tytul']) ?>”?">
                            <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
                            <button class="icon-button icon-danger" type="submit" aria-label="Usuń: <?= e($book['tytul']) ?>" title="Usuń książkę">⌫</button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
            <p class="no-results" id="no-results" hidden>Nie znaleziono książek pasujących do wyszukiwania.</p>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>