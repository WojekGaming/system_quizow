const toggleBtn = document.getElementById('toggleFilters');
const panel = document.getElementById('filtersPanel');
const resetBtn = document.getElementById('resetFilters');
const applyBtn = document.getElementById('applyFilters');
const searchBtn = document.getElementById('doSearch');
const qInput = document.getElementById('q');

if (toggleBtn && panel) {
  toggleBtn.addEventListener('click', () => {
    panel.classList.toggle('open');
  });
}

if (resetBtn && panel) {
  resetBtn.addEventListener('click', () => {
    const inputs = panel.querySelectorAll('input, select');
    inputs.forEach(input => {
      if (input.type === 'checkbox') {
        input.checked = false;
      } else {
        input.value = '';
      }
    });
  });
}

if (applyBtn && panel) {
  applyBtn.addEventListener('click', () => {
    panel.classList.remove('open');
  });
}

if (qInput && searchBtn) {
  qInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') searchBtn.click();
  });
}

document.querySelectorAll('.quiz-card').forEach(card => {
  card.addEventListener('click', () => {
    window.location.href = "/quiz.html?id=1";
  });
});