document.querySelectorAll('.js-confirm-delete').forEach((link) => {
    link.addEventListener('click', (event) => {
        if (!confirm('Yakin ingin menghapus reservasi ini?')) {
            event.preventDefault();
        }
    });
});
