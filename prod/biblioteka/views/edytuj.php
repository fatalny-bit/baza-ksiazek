<?php
$pageTitle = 'Edytuj książkę';
$currentPage = 'form';
require __DIR__ . '/partials/header.php';
?>
<section class="form-panel">
    <div class="form-intro">
        <span class="form-index">EDYCJA / <?= str_pad((string) $book['id'], 2, '0', STR_PAD_LEFT) ?></span>
        <h2>Dobre dane,<br>dobry porządek.</h2>
        <p>Zaktualizuj tytuł lub autora. Zmiany będą widoczne w katalogu od razu.</p>
    </div>
    <form class="book-form" method="post" action="/">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= (int) $book['id'] ?>">
        <label for="tytul">Tytuł książki <span>*</span></label>
        <input id="tytul" name="tytul" maxlength="200" value="<?= e($book['tytul']) ?>" required autofocus>
        <label for="autor">Autor lub autorka <span>*</span></label>
        <input id="autor" name="autor" maxlength="160" value="<?= e($book['autor']) ?>" required>
        <div class="form-actions">
            <a class="button button-quiet" href="/">Anuluj</a>
            <button class="button" type="submit">Zapisz zmiany <span aria-hidden="true">→</span></button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>