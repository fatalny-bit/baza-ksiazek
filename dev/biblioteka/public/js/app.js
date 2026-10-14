const searchInput = document.querySelector('#book-search');

if (searchInput) {
    const rows = [...document.querySelectorAll('.book-row')];
    const visibleCount = document.querySelector('#visible-count');
    const noResults = document.querySelector('#no-results');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim().toLocaleLowerCase('pl');
        let visible = 0;

        for (const row of rows) {
            const matches = row.dataset.search.includes(query);
            row.hidden = !matches;
            visible += Number(matches);
        }

        visibleCount.textContent = String(visible);
        noResults.hidden = visible !== 0;
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === '/' && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
            event.preventDefault();
            searchInput.focus();
        }
    });
}

for (const form of document.querySelectorAll('[data-confirm]')) {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.confirm)) event.preventDefault();
    });
}

for (const button of document.querySelectorAll('.notice-close')) {
    button.addEventListener('click', () => button.closest('.notice').remove());
}