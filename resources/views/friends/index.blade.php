<x-app-layout>
    <x-slot name="title">Znajomi</x-slot>

    <div class="dash-container">

        <div class="dash-welcome">
            <div>
                <h1 class="dash-welcome__title">Znajomi</h1>
                <p class="dash-welcome__sub">Zarządzaj swoją listą znajomych</p>
            </div>
        </div>

        @if(session('success'))
            <div class="dash-alert dash-alert--success">{{ session('success') }}</div>
        @endif

        {{-- Search --}}
        <div class="dash-panel" style="margin-bottom:1.5rem;">
            <div class="dash-panel__head">
                <h2 class="dash-panel__title">🔍 Znajdź użytkownika</h2>
            </div>
            <form method="GET" action="{{ route('friends.index') }}" style="display:flex; gap:10px;">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Wpisz nazwę lub email..."
                    class="auth-input"
                    style="flex:1;"
                />
                <button type="submit" class="dash-cta-btn" style="padding:11px 20px;">Szukaj</button>
            </form>

            @if(request('search') && $searchResults->isNotEmpty())
                <div style="margin-top:1.2rem; display:flex; flex-direction:column; gap:8px;">
                    @foreach($searchResults as $u)
                        <div class="dash-friend-item" style="padding:10px 0;">
                            <div class="dash-friend-item__avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                            <div style="flex:1;">
                                <div class="dash-friend-item__name">{{ $u->name }}</div>
                                <div style="font-size:12px; color:rgba(255,255,255,0.3);">{{ $u->email }}</div>
                            </div>
                            @if($u->request_sent)
                                <span style="font-size:12px; color:rgba(255,255,255,0.3); padding:6px 12px;">Wysłano ✓</span>
                            @else
                                <form method="POST" action="{{ route('friends.request', $u) }}">
                                    @csrf
                                    <button type="submit" class="dash-cta-btn" style="padding:7px 14px; font-size:13px;">
                                        + Dodaj
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif(request('search'))
                <p style="margin-top:1rem; color:rgba(255,255,255,0.35); font-size:13px;">Brak wyników dla "{{ request('search') }}"</p>
            @endif
        </div>

        <div class="dash-grid">

            {{-- Friends list --}}
            <div class="dash-panel">
                <div class="dash-panel__head">
                    <h2 class="dash-panel__title">👥 Znajomi ({{ $friends->count() }})</h2>
                </div>

                @forelse($friends as $friend)
                    <div class="dash-friend-item" style="flex-direction:column; align-items:stretch; gap:10px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="dash-friend-item__avatar">{{ strtoupper(substr($friend->name, 0, 1)) }}</div>
                            <div style="flex:1;">
                                <div class="dash-friend-item__name">{{ $friend->name }}</div>
                                <div style="font-size:12px; color:rgba(255,255,255,0.3);">
                                    {{ $friend->quizzes_count }} quizów
                                </div>
                            </div>
                            @php
                                $friendship = \App\Models\Friendship::where(function($q) use ($friend) {
                                    $q->where('requester_id', auth()->id())->where('addressee_id', $friend->id);
                                })->orWhere(function($q) use ($friend) {
                                    $q->where('requester_id', $friend->id)->where('addressee_id', auth()->id());
                                })->where('status', 'accepted')->first();
                            @endphp
                            @if($friendship)
                                <form method="POST" action="{{ route('friends.remove', $friendship) }}"
                                      onsubmit="return confirm('Usunąć znajomego?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dash-friend-btn dash-friend-btn--reject" title="Usuń znajomego">✕</button>
                                </form>
                            @endif
                        </div>

                        {{-- Ostatnie 5 quizów --}}
                        @if($friend->quizAttempts->isNotEmpty())
                            <div style="padding-left:48px;">
                                <div style="font-size:11px; color:rgba(255,255,255,0.3); text-transform:uppercase; letter-spacing:.5px; margin-bottom:6px;">Ostatnie quizy</div>
                                <div style="display:flex; flex-direction:column; gap:5px;">
                                    @foreach($friend->quizAttempts as $attempt)
                                        <div style="display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.04); border-radius:8px; padding:7px 10px;">
                                            <span style="font-size:13px; color:rgba(255,255,255,0.75);">{{ $attempt->quiz->title ?? 'Usunięty quiz' }}</span>
                                            <span style="font-size:12px; color:#ff6b00; font-weight:600; white-space:nowrap; margin-left:10px;">{{ $attempt->score_percentage }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div style="padding-left:48px; font-size:12px; color:rgba(255,255,255,0.2);">Brak wypełnionych quizów</div>
                        @endif
                    </div>
                @empty
                    <div class="dash-empty">
                        <div class="dash-empty__icon">👥</div>
                        <p>Nie masz jeszcze znajomych.<br>Znajdź kogoś używając wyszukiwarki.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pending --}}
            <div class="dash-side">

                {{-- Received --}}
                <div class="dash-panel">
                    <div class="dash-panel__head">
                        <h2 class="dash-panel__title">📬 Zaproszenia ({{ $pendingReceived->count() }})</h2>
                    </div>
                    @forelse($pendingReceived as $req)
                        <div class="dash-friend-item">
                            <div class="dash-friend-item__avatar">{{ strtoupper(substr($req->requester->name, 0, 1)) }}</div>
                            <div style="flex:1;">
                                <div class="dash-friend-item__name">{{ $req->requester->name }}</div>
                                <div style="font-size:11px; color:rgba(255,255,255,0.3);">chce zostać znajomym</div>
                            </div>
                            <div class="dash-friend-item__actions">
                                <form method="POST" action="{{ route('friends.accept', $req) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dash-friend-btn dash-friend-btn--accept" title="Akceptuj">✓</button>
                                </form>
                                <form method="POST" action="{{ route('friends.reject', $req) }}" style="display:inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dash-friend-btn dash-friend-btn--reject" title="Odrzuć">✕</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="dash-empty" style="padding:1rem 0;">
                            <p>Brak nowych zaproszeń.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Sent --}}
                <div class="dash-panel">
                    <div class="dash-panel__head">
                        <h2 class="dash-panel__title">📤 Wysłane ({{ $pendingSent->count() }})</h2>
                    </div>
                    @forelse($pendingSent as $req)
                        <div class="dash-friend-item">
                            <div class="dash-friend-item__avatar">{{ strtoupper(substr($req->addressee->name, 0, 1)) }}</div>
                            <div style="flex:1;">
                                <div class="dash-friend-item__name">{{ $req->addressee->name }}</div>
                                <div style="font-size:11px; color:rgba(255,255,255,0.3);">oczekuje na odpowiedź</div>
                            </div>
                        </div>
                    @empty
                        <div class="dash-empty" style="padding:1rem 0;">
                            <p>Brak wysłanych zaproszeń.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</x-app-layout>
