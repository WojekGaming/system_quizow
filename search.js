const toggleBtn = document.getElementById('toggleFilters');
const panel = document.getElementById('filtersPanel');
const resetBtn = document.getElementById('resetFilters');

toggleBtn.addEventListener('click', () => {
    panel.classList.toggle('open');
});

resetBtn.addEventListener('click', () => {
    const inputs = panel.querySelectorAll('input, select');
    inputs.forEach(input => {
        if (input.type === 'checkbox'){
            input.checked = false;
        }   else {
            input.value = '';
        }
    });
});

const applyBtn = document.getElementById('applyFilters');
const searchBtn = document.getElementById('doSearch');
const qInput = document.getElementById('q')

if (applyBtn) {
    applyBtn.addEventListener('click', () => {
        panel.classList.remove('open');
    });
}

if (qInput && searchBtn) {
    qInput.addEventListener('keydown', (e) => {
        if (e.key ==='Enter') searchBtn.click();
    });
}
