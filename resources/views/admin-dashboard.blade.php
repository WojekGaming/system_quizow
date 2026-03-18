<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="quiz-play-body">

@php
$quizzes = [
  [
    'title' => 'Powstanie listopadowe',
    'author' => 'admin',
    'category' => 'Historia',
    'questions' => 12,
    'rating' => 5.1,
    'premium' => true,
  ],
  [
    'title' => 'Stolice świata',
    'author' => 'Ola123',
    'category' => 'Geografia',
    'questions' => 20,
    'rating' => 5.8,
    'premium' => false,
  ],
  [
    'title' => 'Present Perfect',
    'author' => 'teacher_pro',
    'category' => 'Angielski',
    'questions' => 15,
    'rating' => 4.7,
    'premium' => false,
  ],
];
@endphp

<div class="admin-container">

  <h1 class="admin-title">Panel administratora</h1>

  <!-- FILTRY -->
  <div class="admin-filters" id="adminFilters">

  <input class="control" id="adminSearch" placeholder="Szukaj quizu...">

  <select class="control" id="adminCategory">
    <option value="">Kategoria</option>
    <option value="Historia">Historia</option>
    <option value="Geografia">Geografia</option>
    <option value="Angielski">Angielski</option>
  </select>

  <select class="control" id="adminPremium">
    <option value="">Premium</option>
    <option value="Tak">Tak</option>
    <option value="Nie">Nie</option>
  </select>

  <button class="btn btn-secondary" type="button" id="adminResetFilters">
    Reset
  </button>

</div>

  <!-- LISTA -->
  <div class="admin-list">

    @foreach($quizzes as $quiz)
      <div class="admin-card">

        <div class="admin-card-top">
          <div>
            <div class="admin-title-small">{{ $quiz['title'] }}</div>
            <div class="admin-meta">
              {{ $quiz['category'] }} • {{ $quiz['author'] }}
            </div>
          </div>

          @if($quiz['premium'])
            <span class="badge premium">Premium</span>
          @endif
        </div>

        <div class="admin-card-bottom">
          <span>{{ $quiz['questions'] }} pytań</span>
          <span>⭐ {{ $quiz['rating'] }}</span>
        </div>

        <div class="admin-actions">
          <button class="btn btn-secondary">Podgląd</button>
          <button class="btn btn-secondary">Ukryj</button>
          <button class="btn btn-danger">Usuń</button>
        </div>

      </div>
    @endforeach

  </div>

</div>

</body>
</html>