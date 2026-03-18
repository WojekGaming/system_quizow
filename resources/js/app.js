import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


// QUIZ PLAY

document.addEventListener('DOMContentLoaded', () => {
  const answers = document.querySelectorAll('.answer');

  answers.forEach(el => {
    el.addEventListener('click', () => {
      answers.forEach(a => a.classList.remove('active'));
      el.classList.add('active');
    });
  });
});

//ADMIN RESET FILTER

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('adminResetFilters');
    const search = document.getElementById('adminSearch');
    const category = document.getElementById('adminCategory');
    const premium = document.getElementById('adminPremium');

    if (!btn || !search || !category || !premium) return;

    btn.addEventListener('click', function () {
        search.value = '';
        category.selectedIndex = 0;
        premium.selectedIndex = 0;
    });
});