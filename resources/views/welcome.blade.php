<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Quizzies</title>

  <style>
    :root{
      --primary: #494D50;
      --cta: #FF6B00;
      --ctaHover: #E65F00;
      --teal: #1F7A8C;
      --light: #E6E8EA;
      --dark: #2F3336;
      --border: #C7CACC;
      --disabled: #9AA0A6;
      --success: #2E9E5B;
      --error: #D64545;
      --warning: #F2A541;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background: #23272a;
      color: var(--light);
    }

    .top-auth {
      padding: 20px 32px 8px;
      display: flex;
      justify-content: flex-end;
      gap: 16px;
      max-width: 1600px;
      margin: 0 auto;
    }

    .top-auth a {
      text-decoration: none;
      color: var(--light);
      padding: 10px 18px;
      border: 1px solid var(--border);
      border-radius: 10px;
      background: var(--dark);
      transition: 0.2s ease;
    }

    .top-auth a:hover {
      border-color: var(--cta);
      color: white;
    }

    .navbar {
      background: #1e1e1e;
      padding: 16px 0;
    }

    .navbar__inner {
      max-width: 1600px;
      margin: 0 auto;
      padding: 0 32px;
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .search__input {
      flex: 1;
      height: 48px;
      padding: 0 16px;
      border-radius: 10px;
      border: 1px solid #d0d0d0;
      outline: none;
      font-size: 16px;
      background: #f5f5f5;
      color: #111;
    }

    .search__input:focus {
      border-color: var(--cta);
      box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.25);
    }

    .btn {
      height: 48px;
      padding: 0 18px;
      border-radius: 10px;
      border: 1px solid transparent;
      cursor: pointer;
      font-weight: 600;
      transition: 0.2s ease;
    }

    .btn--ghost {
      background: var(--primary);
      color: #ffffff;
      border-color: #5c6166;
    }

    .btn--ghost:hover {
      background: #5a5f64;
    }

    .btn--primary {
      background: var(--cta);
      color: #ffffff;
      border-color: var(--cta);
      box-shadow: 0 4px 12px rgba(255, 107, 0, 0.35);
    }

    .btn--primary:hover {
      background: var(--ctaHover);
      box-shadow: 0 0 16px rgba(255, 107, 0, 0.5);
    }

    .btn:active {
      transform: translateY(1px);
    }

    .filters-panel {
      background-color: #2a2f33;
      border-bottom: 1px solid #3a4044;
      max-height: 0;
      overflow: hidden;
      opacity: 0;
      transition: max-height .30s ease, opacity .20s ease;
    }

    .filters-panel.open {
      max-height: 600px;
      opacity: 1;
    }

    .filters-inner {
      max-width: 1600px;
      margin: 0 auto;
      padding: 18px 32px;
      display: grid;
      grid-template-columns: 1.2fr 1.2fr 1.2fr 1.6fr 1fr 1fr;
      gap: 18px 24px;
      align-items: end;
    }

    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 6px;
      min-width: 0;
    }

    .filter-group label,
    .filter-label {
      font-size: 13px;
      font-weight: 700;
      color: var(--light);
    }

    .hint {
      font-size: 12px;
      color: var(--border);
      opacity: 0.85;
    }

    .control {
      height: 40px;
      padding: 0 10px;
      border-radius: 9px;
      border: 1px solid #4a5156;
      background: #353b40;
      color: white;
      outline: none;
      width: 100%;
    }

    .control[type="date"] {
      color-scheme: dark;
    }

    .control::placeholder {
      color: var(--disabled);
    }

    .control:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(31, 122, 140, 0.18);
    }

    .control-row {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: nowrap;
      min-width: 0;
    }

    .control-row .control {
      min-width: 0;
      width: 100%;
    }

    .dash {
      color: var(--primary);
      opacity: 0.7;
      flex: 0 0 auto;
    }

    .checkline {
      display: flex;
      align-items: center;
      gap: 10px;
      height: 40px;
      white-space: nowrap;
    }

    .checkline input[type="checkbox"] {
      width: 18px;
      height: 18px;
      accent-color: var(--teal);
      flex: 0 0 auto;
    }

    .filter-group.date {
      grid-column: 4;
      min-width: 0;
    }

    .premium-group {
      grid-column: 5;
      min-width: 0;
    }

    .filter-group.rating {
      grid-column: 6;
      min-width: 0;
    }

    .filters-actions {
      grid-column: 1 / -1;
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      margin-top: 8px;
    }

    .page {
      padding: 24px 32px 48px;
      max-width: 1600px;
      margin: 0 auto;
    }

    .section-title {
      font-size: 24px;
      font-weight: 700;
      color: white;
      margin: 8px 0 24px;
    }

    .quiz-list {
      width: 100%;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .quiz-card {
      background: #2f3336;
      color: var(--light);
      padding: 20px;
      border-radius: 12px;
      position: relative;
      transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
      min-width: 0;
      min-height: 220px;
      border: 1px solid transparent;
    }

    .quiz-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.35);
      border-color: rgba(255, 107, 0, 0.45);
      cursor: pointer;
    }

    .quiz-card_premium {
      position: absolute;
      top: 12px;
      right: 12px;
      background: var(--cta);
      color: white;
      padding: 5px 10px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 700;
      box-shadow: 0 4px 10px rgba(255, 107, 0, 0.25);
    }

    .quiz-card_top {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      margin: 10px 0 18px;
      font-size: 14px;
      color: var(--border);
    }

    .quiz-card_title {
      font-size: 30px;
      margin: 0 0 36px;
      color: white;
    }

    .quiz-card_bottom {
      display: flex;
      justify-content: space-between;
      gap: 16px;
      font-weight: bold;
      margin-top: auto;
    }

    .quiz-card span,
    .quiz-card h3,
    .quiz-card p {
      min-width: 0;
      overflow-wrap: anywhere;
    }

    @media (max-width: 1200px) {
      .filters-inner {
        grid-template-columns: repeat(3, minmax(220px, 1fr));
      }

      .filter-group.date,
      .premium-group,
      .filter-group.rating {
        grid-column: auto;
      }
    }

    @media (max-width: 700px) {
      .top-auth,
      .navbar__inner,
      .page,
      .filters-inner {
        padding-left: 16px;
        padding-right: 16px;
      }

      .navbar__inner {
        flex-direction: column;
        align-items: stretch;
      }

      .filters-inner {
        grid-template-columns: 1fr;
      }

      .control-row {
        flex-direction: column;
        align-items: stretch;
      }

      .dash {
        display: none;
      }

      .quiz-card_top,
      .quiz-card_bottom {
        flex-direction: column;
        align-items: flex-start;
      }

      .quiz-card_title {
        font-size: 24px;
      }
    }
  </style>
</head>
<body>

  <div class="top-auth">
    @if (Route::has('login'))
      @auth
        <a href="{{ url('/dashboard') }}">Dashboard</a>
      @else
        <a href="{{ route('login') }}">Log in</a>

        @if (Route::has('register'))
          <a href="{{ route('register') }}">Register</a>
        @endif
      @endauth
    @endif
  </div>

  <header class="navbar">
    <div class="navbar__inner">
      <input id="q" class="search__input" type="text" placeholder="Wyszukaj quiz..." />

      <button class="btn btn--ghost" id="toggleFilters" type="button">
        Filtry
      </button>

      <button class="btn btn--primary" id="doSearch" type="button">
        Search
      </button>
    </div>
  </header>

  <section class="filters-panel" id="filtersPanel">
    <div class="filters-inner">
      <div class="filter-group">
        <label for="sortBy">Sortuj po</label>
        <select class="control" id="sortBy" name="sortBy">
          <option value="created_desc">Dacie utworzenia (najnowsze)</option>
          <option value="created_asc">Dacie utworzenia (najstarsze)</option>
          <option value="questions_desc">Liczbie pytań (malejąco)</option>
          <option value="questions_asc">Liczbie pytań (rosnąco)</option>
          <option value="rating_desc">Ocenie (malejąco)</option>
          <option value="rating_asc">Ocenie (rosnąco)</option>
          <option value="premium_desc">Premium (najpierw)</option>
          <option value="premium_asc">Bez premium (najpierw)</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="category">Kategoria</label>
        <select class="control" id="category" name="category">
          <option value="">Wszystkie</option>
          <option value="historia">Historia</option>
          <option value="geografia">Geografia</option>
          <option value="angielski">Angielski</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="author">Użytkownik (autor)</label>
        <input class="control" id="author" name="author" type="text" placeholder="np. Jan Kowalski" />
      </div>

      <div class="filter-group date">
        <label>Data utworzenia</label>
        <div class="control-row">
          <input class="control" id="dateFrom" name="dateFrom" type="date" />
          <span class="dash">-</span>
          <input class="control" id="dateTo" name="dateTo" type="date" />
        </div>
      </div>

      <div class="filter-group premium-group">
        <label class="filter-label">Premium</label>
        <label class="checkline">
          <input id="premiumOnly" name="premiumOnly" type="checkbox" />
          <span>Tylko premium</span>
        </label>
      </div>

      <div class="filter-group rating">
        <label for="ratingMin">Ocena min.</label>
        <input class="control" id="ratingMin" name="ratingMin" type="number" min="1" max="6" placeholder="1-6"/>
        <small class="hint">Skala ocen: 1-6</small>
      </div>

      <div class="filters-actions">
        <button class="btn btn--ghost" id="resetFilters" type="button">Reset</button>
        <button class="btn btn--primary" id="applyFilters" type="button">Zastosuj</button>
      </div>
    </div>
  </section>

  <main class="page">
    <h2 class="section-title">Ostatnio dodane quizy</h2>

    <section class="quiz-list">
      <div class="quiz-card">
        <span class="quiz-card_premium">Premium</span>

        <div class="quiz-card_top">
          <span>Kategoria: Historia</span>
          <span>Autor: admin</span>
        </div>

        <h3 class="quiz-card_title">Powstanie listopadowe</h3>

        <div class="quiz-card_bottom">
          <span>12 pytań</span>
          <span>Rating: 5.1</span>
        </div>
      </div>

      <div class="quiz-card">
        <div class="quiz-card_top">
          <span>Kategoria: Geografia</span>
          <span>Autor: Ola123</span>
        </div>

        <h3 class="quiz-card_title">Stolice świata</h3>

        <div class="quiz-card_bottom">
          <span>20 pytań</span>
          <span>Rating: 5.8</span>
        </div>
      </div>

      <div class="quiz-card">
        <div class="quiz-card_top">
          <span>Kategoria: Angielski</span>
          <span>Autor: teacher_pro</span>
        </div>

        <h3 class="quiz-card_title">Present Perfect</h3>

        <div class="quiz-card_bottom">
          <span>15 pytań</span>
          <span>Rating: 4.7</span>
        </div>
      </div>
    </section>
  </main>

  <script>
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
  </script>
</body>
</html>