<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Quizzies</title>

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

  <!-- AUTH -->
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

  <!-- NAVBAR -->
  <header class="navbar">
    <div class="navbar__inner">

      <!-- LOGO -->
      <a href="/" class="navbar__logo">
        <img src="{{ asset('images/logo.png') }}" alt="Quizzies logo">
      </a>

      <!-- SEARCH -->
      <input id="q" class="search__input" type="text" placeholder="Wyszukaj quiz..." />

      <!-- CREATE QUIZ -->
      <a href="{{ url('/create-quiz') }}" class="btn btn--ghost btn--create">
        Utwórz quiz
      </a>

      <!-- FILTER BUTTON -->
      <button class="btn btn--ghost" id="toggleFilters" type="button">
        Filtry
      </button>

      <!-- SEARCH BUTTON -->
      <button class="btn btn--primary" id="doSearch" type="button">
        Search
      </button>
    </div>
  </header>

  <!-- FILTERS -->
  <section class="filters-panel" id="filtersPanel">
    <div class="filters-inner">

      <div class="filter-group">
        <label for="sortBy">Sortuj po</label>
        <select class="control" id="sortBy">
          <option value="created_desc">Dacie utworzenia (najnowsze)</option>
          <option value="created_asc">Dacie utworzenia (najstarsze)</option>
          <option value="questions_desc">Liczbie pytań (malejąco)</option>
          <option value="questions_asc">Liczbie pytań (rosnąco)</option>
          <option value="rating_desc">Ocenie (malejąco)</option>
          <option value="rating_asc">Ocenie (rosnąco)</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="category">Kategoria</label>
        <select class="control" id="category">
          <option value="">Wszystkie</option>
          <option>Historia</option>
          <option>Geografia</option>
          <option>Angielski</option>
        </select>
      </div>

      <div class="filter-group">
        <label for="author">Użytkownik</label>
        <input class="control" id="author" type="text" placeholder="np. Jan Kowalski">
      </div>

      <div class="filter-group date">
        <label>Data</label>
        <div class="control-row">
          <input class="control" type="date">
          <span class="dash">-</span>
          <input class="control" type="date">
        </div>
      </div>

      <div class="filter-group premium-group">
        <label>Premium</label>
        <label class="checkline">
          <input type="checkbox">
          <span>Tylko premium</span>
        </label>
      </div>

      <div class="filter-group rating">
        <label>Ocena min.</label>
        <input class="control" type="number" min="1" max="6" placeholder="1-6">
        <small class="hint">Skala ocen 1-6</small>
      </div>

      <div class="filters-actions">
        <button class="btn btn--ghost" id="resetFilters">Reset</button>
        <button class="btn btn--primary" id="applyFilters">Zastosuj</button>
      </div>

    </div>
  </section>

  <!-- CONTENT -->
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

  <!-- JS -->
  <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>