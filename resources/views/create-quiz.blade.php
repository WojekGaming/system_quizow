<!doctype html>
<html lang="pl">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Quiz Builder</title>
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
      --page: #1f2326;
      --panel: #2a2f33;
      --panel2: #31363b;
      --input: #3a4045;
    }

    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      background: linear-gradient(180deg, #23272a 0%, #1d2023 100%);
      color: var(--light);
    }

    .app {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 340px 1fr;
    }

    .sidebar {
      background: #202427;
      border-right: 1px solid rgba(255,255,255,0.08);
      padding: 24px 18px;
      display: flex;
      flex-direction: column;
      gap: 22px;
    }

    .brand {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .brand h1 {
      margin: 0;
      font-size: 28px;
      letter-spacing: 0.3px;
    }

    .brand p {
      margin: 0;
      color: var(--border);
      font-size: 14px;
    }

    .side-block {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 16px;
      padding: 16px;
    }

    .side-block h2 {
      margin: 0 0 14px;
      font-size: 16px;
    }

    .field {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 14px;
    }

    .field:last-child { margin-bottom: 0; }

    label {
      font-size: 13px;
      font-weight: 700;
      color: white;
    }

    .control, textarea, select {
      width: 100%;
      background: var(--input);
      color: white;
      border: 1px solid #4a5156;
      border-radius: 10px;
      padding: 12px 13px;
      outline: none;
      font: inherit;
    }

    .control:focus, textarea:focus, select:focus {
      border-color: var(--teal);
      box-shadow: 0 0 0 3px rgba(31,122,140,0.18);
    }

    textarea {
      resize: vertical;
      min-height: 90px;
    }

    .questions-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .question-item {
      background: #2b3034;
      border: 1px solid transparent;
      border-radius: 12px;
      padding: 12px 14px;
      cursor: pointer;
      transition: 0.2s ease;
    }

    .question-item:hover {
      border-color: rgba(255,107,0,0.35);
      transform: translateY(-1px);
    }

    .question-item.active {
      border-color: var(--cta);
      box-shadow: 0 0 0 2px rgba(255,107,0,0.15);
    }

    .question-item_top {
      display: flex;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 6px;
    }

    .question-number {
      font-size: 12px;
      color: var(--border);
      text-transform: uppercase;
      letter-spacing: 0.4px;
    }

    .question-state {
      font-size: 12px;
      color: var(--warning);
      font-weight: 700;
    }

    .question-name {
      font-size: 14px;
      line-height: 1.35;
      color: white;
    }

    .btn {
      height: 44px;
      padding: 0 16px;
      border-radius: 10px;
      border: 1px solid transparent;
      cursor: pointer;
      font: inherit;
      font-weight: 700;
      transition: 0.2s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-secondary {
      background: var(--primary);
      color: white;
      border-color: #5c6166;
    }

    .btn-secondary:hover {
      background: #5a5f64;
    }

    .btn-primary {
      background: var(--cta);
      color: white;
      border-color: var(--cta);
      box-shadow: 0 4px 12px rgba(255,107,0,0.22);
    }

    .btn-primary:hover {
      background: var(--ctaHover);
      box-shadow: 0 0 16px rgba(255,107,0,0.38);
    }

    .btn-full { width: 100%; }

    .workspace {
      padding: 28px;
      display: flex;
      flex-direction: column;
      gap: 22px;
    }

    .workspace-header {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      align-items: center;
    }

    .workspace-title h2 {
      margin: 0;
      font-size: 30px;
    }

    .workspace-title p {
      margin: 6px 0 0;
      color: var(--border);
    }

    .editor {
      background: var(--panel);
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 20px;
      padding: 24px;
      display: grid;
      gap: 22px;
    }

    .editor-grid {
      display: grid;
      grid-template-columns: 1.25fr 0.95fr;
      gap: 22px;
      align-items: start;
    }

    .editor-block {
      background: rgba(255,255,255,0.03);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 16px;
      padding: 18px;
    }

    .editor-block h3 {
      margin: 0 0 14px;
      font-size: 18px;
    }

    .upload-zone {
      min-height: 260px;
      border: 2px dashed #4b5257;
      border-radius: 16px;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 18px;
      text-align: center;
      gap: 14px;
    }

    .upload-zone img {
      max-width: 100%;
      max-height: 260px;
      object-fit: contain;
      display: none;
      border-radius: 12px;
    }

    .upload-placeholder {
      color: var(--disabled);
      line-height: 1.5;
      max-width: 280px;
    }

    .small-note {
      font-size: 12px;
      color: var(--border);
    }

    .hidden-input {
      display: none;
    }

    .answers {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 16px;
    }

    .answer-tile {
      background: var(--panel2);
      border: 1px solid rgba(255,255,255,0.06);
      border-radius: 14px;
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 12px;
      transition: 0.2s ease;
    }

    .answer-tile:hover {
      border-color: rgba(255,107,0,0.28);
      transform: translateY(-1px);
    }

    .answer-head {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
    }

    .answer-letter {
      width: 32px;
      height: 32px;
      border-radius: 999px;
      background: var(--primary);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
    }

    .correct-line {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--border);
      font-size: 13px;
      white-space: nowrap;
    }

    .correct-line input {
      accent-color: var(--cta);
    }

    .editor-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      flex-wrap: wrap;
    }

    @media (max-width: 1100px) {
      .app {
        grid-template-columns: 1fr;
      }

      .sidebar {
        border-right: none;
        border-bottom: 1px solid rgba(255,255,255,0.08);
      }

      .editor-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 700px) {
      .workspace {
        padding: 18px;
      }

      .answers {
        grid-template-columns: 1fr;
      }

      .workspace-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .editor-actions .btn {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand">
        <h1>Quiz Builder</h1>
        <p>Tworzenie quizu w układzie edytora</p>
      </div>

      <div class="side-block">
        <h2>Ustawienia quizu</h2>

        <div class="field">
          <label for="quizTitle1">Tytuł quizu</label>
          <input id="quizTitle1" class="control" type="text" placeholder="np. Historia Polski">
        </div>

        <div class="field">
          <label for="quizCategory1">Kategoria</label>
          <select id="quizCategory1" class="control">
            <option value="">Wybierz kategorię</option>
            <option>Historia</option>
            <option>Geografia</option>
            <option>Angielski</option>
            <option>Sport</option>
          </select>
        </div>

        <div class="field">
          <label for="quizDesc1">Opis</label>
          <textarea id="quizDesc1" placeholder="Krótki opis quizu..."></textarea>
        </div>
      </div>

      <div class="side-block">
        <h2>Pytania</h2>

        <div class="questions-list">
          <div class="question-item active">
            <div class="question-item_top">
              <span class="question-number">Pytanie 1</span>
              <span class="question-state">edytujesz</span>
            </div>
            <div class="question-name">Które wydarzenie rozpoczęło powstanie listopadowe?</div>
          </div>

          <div class="question-item">
            <div class="question-item_top">
              <span class="question-number">Pytanie 2</span>
              <span class="question-state">robocze</span>
            </div>
            <div class="question-name">Dodaj treść kolejnego pytania...</div>
          </div>

          <div class="question-item">
            <div class="question-item_top">
              <span class="question-number">Pytanie 3</span>
              <span class="question-state">robocze</span>
            </div>
            <div class="question-name">Dodaj treść kolejnego pytania...</div>
          </div>
        </div>

        <div style="margin-top:14px;">
          <button class="btn btn-primary btn-full" type="button">+ Dodaj pytanie</button>
        </div>

        <div style="margin-top:14px;">
          <a href="{{ url('/') }}" class="btn btn-secondary btn-full">← Wróć</a>
        </div>
      </div>
    </aside>

    <main class="workspace">
      <div class="workspace-header">
        <div class="workspace-title">
          <h2>Edytujesz: Pytanie 1</h2>
          <p>Wypełnij treść, dodaj zdjęcie i ustaw poprawną odpowiedź.</p>
        </div>

        <div>
          <button class="btn btn-secondary" type="button">Podgląd quizu</button>
        </div>
      </div>

      <section class="editor">
        <div class="editor-grid">
          <div class="editor-block">
            <h3>Treść pytania</h3>

            <div class="field">
              <label for="questionText1">Treść</label>
              <textarea id="questionText1" placeholder="Wpisz pytanie..."></textarea>
            </div>

            <div class="small-note">
              Dobrze działa krótka, jednoznaczna treść. Unikaj zbyt długich pytań.
            </div>
          </div>

          <div class="editor-block">
            <h3>Zdjęcie do pytania</h3>

            <div class="upload-zone">
              <input type="file" id="imageInput1" class="hidden-input" accept="image/*">
              <img id="previewImg1" alt="Podgląd zdjęcia">
              <div class="upload-placeholder" id="uploadPlaceholder1">
                Dodaj zdjęcie, ilustrację albo mapę pomocniczą do pytania.
              </div>
              <div style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
                <button class="btn btn-secondary" type="button" id="chooseBtn1">Wybierz plik</button>
                <button class="btn btn-secondary" type="button" id="removeBtn1">Usuń</button>
              </div>
              <div class="small-note" id="fileName1">Nie wybrano pliku</div>
            </div>
          </div>
        </div>

        <div class="editor-block">
          <h3>Odpowiedzi</h3>

          <div class="answers">
            <div class="answer-tile">
              <div class="answer-head">
                <span class="answer-letter">A</span>
                <label class="correct-line">
                  <input type="radio" name="correctBuilder" value="A">
                  Poprawna
                </label>
              </div>
              <input class="control" type="text" placeholder="Odpowiedź A">
            </div>

            <div class="answer-tile">
              <div class="answer-head">
                <span class="answer-letter">B</span>
                <label class="correct-line">
                  <input type="radio" name="correctBuilder" value="B">
                  Poprawna
                </label>
              </div>
              <input class="control" type="text" placeholder="Odpowiedź B">
            </div>

            <div class="answer-tile">
              <div class="answer-head">
                <span class="answer-letter">C</span>
                <label class="correct-line">
                  <input type="radio" name="correctBuilder" value="C">
                  Poprawna
                </label>
              </div>
              <input class="control" type="text" placeholder="Odpowiedź C">
            </div>

            <div class="answer-tile">
              <div class="answer-head">
                <span class="answer-letter">D</span>
                <label class="correct-line">
                  <input type="radio" name="correctBuilder" value="D">
                  Poprawna
                </label>
              </div>
              <input class="control" type="text" placeholder="Odpowiedź D">
            </div>
          </div>
        </div>

        <div class="editor-actions">
          <button class="btn btn-secondary" type="button">Usuń pytanie</button>
          <button class="btn btn-secondary" type="button">Zapisz pytanie</button>
          <button class="btn btn-primary" type="button">Zapisz quiz</button>
        </div>
      </section>
    </main>
  </div>

  <script>
    const imageInput1 = document.getElementById('imageInput1');
    const chooseBtn1 = document.getElementById('chooseBtn1');
    const removeBtn1 = document.getElementById('removeBtn1');
    const previewImg1 = document.getElementById('previewImg1');
    const uploadPlaceholder1 = document.getElementById('uploadPlaceholder1');
    const fileName1 = document.getElementById('fileName1');

    chooseBtn1.addEventListener('click', () => imageInput1.click());

    imageInput1.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (!file) return;

      fileName1.textContent = file.name;
      const reader = new FileReader();
      reader.onload = (event) => {
        previewImg1.src = event.target.result;
        previewImg1.style.display = 'block';
        uploadPlaceholder1.style.display = 'none';
      };
      reader.readAsDataURL(file);
    });

    removeBtn1.addEventListener('click', () => {
      imageInput1.value = '';
      previewImg1.src = '';
      previewImg1.style.display = 'none';
      uploadPlaceholder1.style.display = 'block';
      fileName1.textContent = 'Nie wybrano pliku';
    });
  </script>
</body>
</html>