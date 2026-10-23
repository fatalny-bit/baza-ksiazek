<?php
$pageTitle = 'Dodaj książkę';
$currentPage = 'form';
require __DIR__ . '/partials/header.php';
?>
<section class="form-panel">
    <div class="form-intro">
        <span class="form-index">NOWA POZYCJA / 01</span>
        <h2>Co ostatnio<br>czytasz?</h2>
        <p>Uzupełnij podstawowe informacje. Książka od razu pojawi się w Twoim katalogu.</p>
    </div>
    <form class="book-form" method="post" action="/">
        <input type="hidden" name="csrf_token" value="<?= e($csrfToken) ?>">
        <input type="hidden" name="action" value="add">
        <label for="tytul">Tytuł książki <span>*</span></label>
        <input id="tytul" name="tytul" maxlength="200" placeholder="np. Solaris" required autofocus>
        <label for="autor">Autor lub autorka <span>*</span></label>
        <input id="autor" name="autor" maxlength="160" placeholder="np. Stanisław Lem" required>
        <div class="form-actions">
            <a class="button button-quiet" href="/">Anuluj</a>
            <button class="button" type="submit">Zapisz książkę <span aria-hidden="true">→</span></button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/partials/footer.php'; ?>