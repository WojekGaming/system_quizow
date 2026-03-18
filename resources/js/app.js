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