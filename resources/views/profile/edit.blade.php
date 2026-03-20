<x-app-layout>
    <x-slot name="title">Edytuj profil</x-slot>

    <div class="dash-container" style="max-width: 860px;">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">Twój <span>profil</span></h1>
                <p class="dash-welcome__sub">Zarządzaj swoimi danymi i ustawieniami konta</p>
            </div>
        </div>

        @if(session('success'))
            <div class="dash-alert dash-alert--success" style="margin-bottom:1.5rem;">
                ✓ {{ session('success') }}
            </div>
        @endif

        {{-- Avatar + name card --}}
        <div class="dash-panel" style="margin-bottom:1.5rem;">
            <div style="display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">

                {{-- Avatar --}}
                <div style="position:relative; flex-shrink:0;">
                    @if($user->avatar_path)
                        <img src="{{ Storage::url($user->avatar_path) }}"
                             alt="Avatar"
                             style="width:80px; height:80px; border-radius:18px; object-fit:cover; border:2px solid rgba(255,107,0,0.3);">
                    @else
                        <div style="width:80px; height:80px; background:linear-gradient(135deg,#ff6b00,#ff8c33);
                             border-radius:18px; display:flex; align-items:center; justify-content:center;
                             font-size:32px; font-weight:700; color:#fff;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div style="flex:1;">
                    <div style="font-size:20px; font-weight:700; color:#fff;">{{ $user->name }}</div>
                    <div style="font-size:13px; color:rgba(255,255,255,0.4); margin-top:2px;">{{ $user->email }}</div>
                    <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                        @if($user->isPremium())
                            <span class="app-nav__premium-badge">⭐ Premium do {{ $user->premium_until->format('d.m.Y') }}</span>
                        @endif
                        <span style="font-size:12px; color:rgba(255,255,255,0.3);">
                            Konto od {{ $user->created_at->format('d.m.Y') }}
                        </span>
                    </div>
                </div>

                {{-- Avatar upload --}}
                <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data"
                      style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                    @csrf
                    <label style="cursor:pointer;">
                        <input type="file" name="avatar" accept="image/*" style="display:none;"
                               onchange="this.form.submit()">
                        <span class="dash-cta-btn" style="padding:9px 16px; font-size:13px; display:inline-flex; align-items:center; gap:6px;">
                            📷 Zmień zdjęcie
                        </span>
                    </label>
                    @error('avatar')
                        <span style="font-size:12px; color:#f87171;">{{ $message }}</span>
                    @enderror
                    <span style="font-size:11px; color:rgba(255,255,255,0.25);">JPG, PNG, WebP · max 2MB</span>
                </form>

            </div>
        </div>

        {{-- Edit form --}}
        <div class="dash-panel" style="margin-bottom:1.5rem;">
            <div class="dash-panel__head">
                <h2 class="dash-panel__title">Dane konta</h2>
            </div>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf @method('PATCH')

                <div class="auth-field">
                    <label class="auth-label" for="name">Nazwa użytkownika</label>
                    <input id="name" type="text" name="name" class="auth-input"
                           value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @error('name')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field" style="margin-top:1rem;">
                    <label class="auth-label" for="email">Adres email</label>
                    <input id="email" type="email" name="email" class="auth-input"
                           value="{{ old('email', $user->email) }}" required autocomplete="email">
                    @error('email')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                    @if($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <p style="font-size:12px; color:#f87171; margin-top:6px;">
                            ⚠ Email niezweryfikowany.
                            <a href="{{ route('verification.send') }}" style="color:#ff8c33; text-decoration:underline;">
                                Wyślij link weryfikacyjny
                            </a>
                        </p>
                    @endif
                </div>

                <div style="margin-top:1.5rem; display:flex; justify-content:flex-end;">
                    <button type="submit" class="dash-cta-btn">Zapisz zmiany</button>
                </div>
            </form>
        </div>

        {{-- Change password --}}
        <div class="dash-panel" style="margin-bottom:1.5rem;">
            <div class="dash-panel__head">
                <h2 class="dash-panel__title">Zmień hasło</h2>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf @method('PUT')

                <div class="auth-field">
                    <label class="auth-label" for="current_password">Obecne hasło</label>
                    <input id="current_password" type="password" name="current_password"
                           class="auth-input" placeholder="••••••••" autocomplete="current-password">
                    @error('current_password', 'updatePassword')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field" style="margin-top:1rem;">
                    <label class="auth-label" for="password">Nowe hasło</label>
                    <input id="password" type="password" name="password"
                           class="auth-input" placeholder="••••••••" autocomplete="new-password">
                    @error('password', 'updatePassword')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="auth-field" style="margin-top:1rem;">
                    <label class="auth-label" for="password_confirmation">Potwierdź nowe hasło</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                           class="auth-input" placeholder="••••••••" autocomplete="new-password">
                </div>

                <div style="margin-top:1.5rem; display:flex; justify-content:flex-end;">
                    <button type="submit" class="dash-cta-btn">Zmień hasło</button>
                </div>
            </form>
        </div>

        {{-- Delete account --}}
        <div class="dash-panel" style="border-color:rgba(220,50,50,0.2);">
            <div class="dash-panel__head">
                <h2 class="dash-panel__title" style="color:#f87171;">Usuń konto</h2>
            </div>
            <p style="font-size:13px; color:rgba(255,255,255,0.4); margin-bottom:1.2rem;">
                Po usunięciu konta wszystkie Twoje dane, quizy i statystyki zostaną trwale usunięte.
            </p>

            <button onclick="document.getElementById('deleteModal').style.display='flex'"
                    style="background:rgba(220,50,50,0.1); border:1px solid rgba(220,50,50,0.25);
                           color:#f87171; border-radius:10px; padding:10px 20px; font-family:'Outfit',sans-serif;
                           font-size:14px; font-weight:600; cursor:pointer; transition:background 0.2s;"
                    onmouseover="this.style.background='rgba(220,50,50,0.2)'"
                    onmouseout="this.style.background='rgba(220,50,50,0.1)'">
                🗑 Usuń konto
            </button>
        </div>

    </div>

    {{-- Delete modal --}}
    <div id="deleteModal"
         style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7);
                align-items:center; justify-content:center; z-index:500; padding:1rem;">
        <div style="background:#141414; border:1px solid rgba(220,50,50,0.3); border-radius:18px;
                    padding:2rem; max-width:400px; width:100%;">
            <h3 style="color:#f87171; font-size:18px; margin-bottom:0.5rem;">Usuń konto</h3>
            <p style="font-size:13px; color:rgba(255,255,255,0.45); margin-bottom:1.5rem;">
                Wpisz swoje hasło aby potwierdzić usunięcie konta. Ta operacja jest nieodwracalna.
            </p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <div class="auth-field">
                    <label class="auth-label" for="del_password">Hasło</label>
                    <input id="del_password" type="password" name="password"
                           class="auth-input" placeholder="••••••••" required>
                    @error('password', 'userDeletion')
                        <p class="auth-error">{{ $message }}</p>
                    @enderror
                </div>
                <div style="display:flex; gap:10px; margin-top:1.2rem; justify-content:flex-end;">
                    <button type="button"
                            onclick="document.getElementById('deleteModal').style.display='none'"
                            style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1);
                                   color:rgba(255,255,255,0.6); border-radius:10px; padding:10px 18px;
                                   font-family:'Outfit',sans-serif; cursor:pointer; font-size:14px;">
                        Anuluj
                    </button>
                    <button type="submit"
                            style="background:rgba(220,50,50,0.15); border:1px solid rgba(220,50,50,0.3);
                                   color:#f87171; border-radius:10px; padding:10px 18px;
                                   font-family:'Outfit',sans-serif; font-weight:600; cursor:pointer; font-size:14px;">
                        Usuń na zawsze
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
