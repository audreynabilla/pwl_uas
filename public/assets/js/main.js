document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-filter]').forEach((button) => {
    button.addEventListener('click', () => {
      const filter = button.dataset.filter;
      document.querySelectorAll('[data-filter]').forEach((btn) => btn.classList.remove('active'));
      button.classList.add('active');
      document.querySelectorAll('[data-category]').forEach((card) => {
        card.style.display = filter === 'Semua' || card.dataset.category === filter ? '' : 'none';
      });
    });
  });

  document.querySelectorAll('.image-input').forEach((input) => {
    input.addEventListener('change', () => {
      const preview = document.querySelector(input.dataset.preview);
      const file = input.files && input.files[0];
      if (!preview || !file) return;
      preview.src = URL.createObjectURL(file);
      preview.classList.remove('d-none');
    });
  });

  document.querySelectorAll('.flash-alert').forEach((alert) => {
    setTimeout(() => alert.remove(), 4000);
  });

  document.querySelectorAll('form').forEach((form) => {
    form.addEventListener('submit', () => {
      const button = form.querySelector('button[type="submit"]');
      if (!button || button.dataset.noSpinner === 'true') return;
      button.disabled = true;
      button.dataset.originalText = button.innerHTML;
      button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });
  });
});
