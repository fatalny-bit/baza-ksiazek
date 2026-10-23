<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f4f5f1">
    <title><?= e($pageTitle) ?> · Biblioteka</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <script src="/js/app.js" defer></script>
</head>
<body>
<header class="topbar">
    <a class="brand" href="/" aria-label="Biblioteka, strona główna">
        <span class="brand-mark" aria-hidden="true">B</span>
        <span>Biblioteka<span class="brand-dot">.</span></span>
    </a>
    <nav aria-label="Nawigacja główna">
        <a class="nav-link<?= $currentPage === 'list' ? ' is-current' : '' ?>" href="/">Księgozbiór</a>
        <a class="button button-small" href="/?action=add"><span aria-hidden="true">＋</span> Dodaj książkę</a>
    </nav>
</header>
<main class="page-shell">
    <div class="page-heading">
        <div>
            <p class="eyebrow">KATALOG DOMOWY</p>
            <h1><?= e($pageTitle) ?></h1>
        </div>
        <?php if ($currentPage === 'list'): ?>
            <label class="search-box">
                <span class="search-icon" aria-hidden="true">⌕</span>
                <span class="sr-only">Szukaj w księgozbiorze</span>
                <input id="book-search" type="search" placeholder="Tytuł lub autor" autocomplete="off">
                <kbd>/</kbd>
            </label>
        <?php endif; ?>
    </div>
    <?php if ($flash !== null): ?>
        <div class="notice notice-<?= e($flash['type']) ?>" role="status">
            <span><?= e($flash['message']) ?></span>
            <button class="notice-close" type="button" aria-label="Zamknij komunikat">×</button>
        </div>
    <?php endif; ?>