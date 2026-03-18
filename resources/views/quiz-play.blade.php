<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <title>Quiz</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="quiz-play-body">

@php
$quiz = ['title' => 'Historia Polski'];

$question = [
  'index'=>1,
  'total'=>5,
  'content'=>'Kiedy wybuchło powstanie listopadowe?',
  'answers'=>[
    '1830',
    '1918',
    '1410',
    '966'
  ]
];
@endphp

<div class="quiz-box">

  <div class="top">
    <div>{{ $quiz['title'] }}</div>
    <div>Pytanie {{ $question['index'] }} / {{ $question['total'] }}</div>
  </div>

  <div class="question">
    {{ $question['content'] }}
  </div>

  <div class="answers">
    @foreach($question['answers'] as $key => $answer)
      <div class="answer">
        {{ $answer }}
      </div>
    @endforeach
  </div>

  <div class="bottom">
    <a href="{{ url('/') }}">← wyjdź</a>
    <button class="quiz-next-btn">Dalej</button>
  </div>

</div>

</body>
</html>