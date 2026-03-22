<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quizzies — Sprawdź swoją wiedzę</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --cta: #FF6B00;
            --cta2: #FF8C33;
            --glass-bg: rgba(255,255,255,0.04);
            --glass-border: rgba(255,255,255,0.08);
            --glass-hover: rgba(255,255,255,0.07);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Outfit', system-ui, sans-serif;
            background: #080809;
            color: #E6E8EA;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .bg { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .bg-orb { position: absolute; border-radius: 50%; filter: blur(90px); animation: orbFloat 12s ease-in-out infinite; }
        .bg-orb-1 { width:600px;height:600px;background:radial-gradient(circle,rgba(255,107,0,.18),transparent 70%);top:-200px;right:-150px;animation-delay:0s; }
        .bg-orb-2 { width:500px;height:500px;background:radial-gradient(circle,rgba(255,140,51,.12),transparent 70%);bottom:-150px;left:-100px;animation-delay:-4s; }
        .bg-orb-3 { width:350px;height:350px;background:radial-gradient(circle,rgba(31,122,140,.1),transparent 70%);top:40%;left:30%;animation-delay:-8s; }
        @keyframes orbFloat { 0%,100%{transform:translateY(0) scale(1)} 50%{transform:translateY(-30px) scale(1.05)} }
        .bg-grid { position:absolute;inset:0;background-image:linear-gradient(rgba(255,107,0,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(255,107,0,.03) 1px,transparent 1px);background-size:60px 60px; }
        .bg-noise { position:absolute;inset:0;opacity:.025;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E"); }

        /* ── NAVBAR ── */
        .nav { position:fixed;top:0;left:0;right:0;z-index:200;transition:background .3s,border-color .3s; }
        .nav.scrolled { background:rgba(8,8,9,.92);border-bottom:1px solid rgba(255,107,0,.12);backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px); }

        .nav__inner {
            max-width:1280px;margin:0 auto;padding:0 2rem;height:68px;
            display:grid;
            grid-template-columns: auto 1fr auto;
            align-items:center;
            gap:1.5rem;
        }

        .nav__logo { display:flex;align-items:center;gap:10px;text-decoration:none;flex-shrink:0; }
        .nav__logo-icon { width:38px;height:38px;background:linear-gradient(135deg,#ff6b00,#ff9a3c);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:20px;box-shadow:0 4px 16px rgba(255,107,0,.35); }
        .nav__logo-text { font-size:22px;font-weight:800;color:#fff;letter-spacing:-.5px; }
        .nav__logo-text span { color:#ff6b00; }

        /* Centre column: search + filters pill together */
        .nav__centre {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .nav__search { position:relative; width: 100%; max-width: 460px; }
        .nav__search input { width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:10px 44px 10px 16px;color:#fff;font-family:'Outfit',sans-serif;font-size:14px;outline:none;transition:all .25s; }
        .nav__search input:focus { border-color:rgba(255,107,0,.5);background:rgba(255,107,0,.05);box-shadow:0 0 0 3px rgba(255,107,0,.08); }
        .nav__search input::placeholder { color:rgba(255,255,255,.3); }
        .nav__search-ico { position:absolute;right:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.3);font-size:15px;pointer-events:none; }

        .nav__right { display:flex;align-items:center;gap:10px;justify-content:flex-end; }
        .nav__link { font-size:14px;font-weight:500;color:rgba(255,255,255,.55);text-decoration:none;padding:8px 14px;border-radius:9px;transition:all .2s;white-space:nowrap; }
        .nav__link:hover { color:#fff;background:rgba(255,255,255,.07); }
        .nav__btn { display:inline-flex;align-items:center;gap:7px;background:linear-gradient(135deg,#ff6b00,#ff8c33);border:none;border-radius:11px;padding:9px 20px;color:#fff;font-family:'Outfit',sans-serif;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;transition:all .2s;box-shadow:0 4px 18px rgba(255,107,0,.28);white-space:nowrap; }
        .nav__btn:hover { transform:translateY(-1px);box-shadow:0 6px 24px rgba(255,107,0,.4); }
        .nav__filter-btn { display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:11px;padding:9px 14px;color:rgba(255,255,255,.6);font-family:'Outfit',sans-serif;font-size:14px;font-weight:500;cursor:pointer;transition:all .2s;white-space:nowrap;flex-shrink:0; }
        .nav__filter-btn:hover,.nav__filter-btn.active { background:rgba(255,107,0,.1);border-color:rgba(255,107,0,.35);color:#ff8c33; }
        .nav__filter-btn .chevron { font-size:10px;transition:transform .25s; }
        .nav__filter-btn.active .chevron { transform:rotate(180deg); }

        /* ── MOBILE HAMBURGER (new) ── */
        .nav__hamburger {
            display: none;
            background: none;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 9px;
            color: rgba(255,255,255,.7);
            font-size: 18px;
            cursor: pointer;
            padding: 7px 11px;
            line-height: 1;
            transition: all .2s;
            flex-shrink: 0;
        }
        .nav__hamburger:hover { background: rgba(255,255,255,.07); color: #fff; }

        /* Mobile dropdown menu */
        .nav__mobile-menu {
            display: none;
            flex-direction: column;
            gap: 6px;
            padding: 12px 1.5rem 16px;
            background: rgba(8,8,9,.98);
            border-bottom: 1px solid rgba(255,107,0,.12);
            backdrop-filter: blur(20px);
        }
        .nav__mobile-menu.open { display: flex; }

        .nav__mobile-search {
            position: relative;
            margin-bottom: 4px;
        }
        .nav__mobile-search input {
            width: 100%;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 11px;
            padding: 10px 40px 10px 15px;
            color: #fff;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            outline: none;
        }
        .nav__mobile-search input::placeholder { color: rgba(255,255,255,.3); }
        .nav__mobile-search-ico { position:absolute;right:13px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,.3);font-size:14px;pointer-events:none; }

        .nav__mobile-link {
            display: flex; align-items: center;
            padding: 10px 12px;
            font-size: 14px; font-weight: 500;
            color: rgba(255,255,255,.6);
            text-decoration: none;
            border-radius: 9px;
            transition: all .2s;
        }
        .nav__mobile-link:hover { background: rgba(255,255,255,.06); color: #fff; }

        .nav__mobile-btn {
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg,#ff6b00,#ff8c33);
            border: none; border-radius: 11px;
            padding: 12px;
            color: #fff; font-family: 'Outfit', sans-serif;
            font-size: 14px; font-weight: 600;
            text-decoration: none;
            margin-top: 4px;
            box-shadow: 0 4px 16px rgba(255,107,0,.28);
        }

        .nav__mobile-filter {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 11px;
            padding: 10px;
            color: rgba(255,255,255,.6);
            font-family: 'Outfit', sans-serif;
            font-size: 14px; font-weight: 500;
            cursor: pointer;
            transition: all .2s;
            margin-top: 2px;
        }
        .nav__mobile-filter:hover { background: rgba(255,107,0,.1); border-color: rgba(255,107,0,.3); color: #ff8c33; }

        /* ── USER DROPDOWN ── */
        .nav__user {
            position: relative;
            display: flex; align-items: center; gap: 8px;
            padding: 5px 10px 5px 5px;
            border-radius: 11px;
            cursor: pointer;
            border: 1px solid rgba(255,255,255,.09);
            transition: background .2s;
            flex-shrink: 0;
        }
        .nav__user:hover { background: rgba(255,255,255,.06); }
        .nav__avatar { width:30px;height:30px;background:linear-gradient(135deg,#ff6b00,#ff8c33);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0; }
        .nav__username { font-size:13px;font-weight:500;color:rgba(255,255,255,.85);max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
        .nav__chevron-user { font-size:9px;color:rgba(255,255,255,.35);transition:transform .25s; }
        .nav__user.open .nav__chevron-user { transform:rotate(180deg); }
        .nav__dropdown { position:absolute;top:calc(100% + 8px);right:0;min-width:210px;background:rgba(14,14,16,.98);border:1px solid rgba(255,107,0,.18);border-radius:14px;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,.55);backdrop-filter:blur(20px);display:none;z-index:300; }
        .nav__dropdown.open { display:block; }
        .nav__dropdown-header { padding:13px 15px 11px; }
        .nav__dropdown-name { font-size:14px;font-weight:600;color:#fff;margin-bottom:2px; }
        .nav__dropdown-email { font-size:12px;color:rgba(255,255,255,.35); }
        .nav__dropdown-divider { height:1px;background:rgba(255,255,255,.07); }
        .nav__dropdown-item { display:flex;align-items:center;gap:8px;width:100%;padding:10px 15px;font-family:'Outfit',sans-serif;font-size:13px;font-weight:500;color:rgba(255,255,255,.65);text-decoration:none;background:none;border:none;cursor:pointer;transition:background .15s,color .15s;text-align:left; }
        .nav__dropdown-item:hover { background:rgba(255,255,255,.05);color:#fff; }
        .nav__dropdown-item--danger { color:rgba(248,113,113,.8); }
        .nav__dropdown-item--danger:hover { background:rgba(220,50,50,.08);color:#f87171; }

        /* ── FILTERS PANEL (redesigned) ── */
        .filters-wrap {
            position: fixed;
            top: 68px; left: 0; right: 0;
            z-index: 190;
            overflow: hidden;
            max-height: 0;
            transition: max-height .4s cubic-bezier(.4,0,.2,1);
        }
        .filters-wrap.open { max-height: 300px; }

        .filters-panel {
            background: rgba(10,10,12,.97);
            border-bottom: 1px solid rgba(255,255,255,.06);
            backdrop-filter: blur(20px);
        }

        .filters-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1.4rem 2rem 1.6rem;
            display: grid;
            grid-template-columns: repeat(6, 1fr) auto;
            gap: 12px;
            align-items: end;
        }

        .fg { display: flex; flex-direction: column; gap: 5px; min-width: 0; }

        .fg-label {
            font-size: 10px; font-weight: 700;
            color: rgba(255,255,255,.35);
            text-transform: uppercase; letter-spacing: .8px;
        }

        /* Filter controls — dark glass */
        .fc {
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px;
            padding: 9px 12px;
            color: #fff;
            font-family: 'Outfit', sans-serif; font-size: 13px;
            outline: none; width: 100%;
            transition: border-color .2s, background .2s;
        }
        .fc:focus { border-color: rgba(255,107,0,.5); background: rgba(255,107,0,.04); }
        .fc::placeholder { color: rgba(255,255,255,.28); }
        .fc option { background: #141416; color: #fff; }

        /* Wrapper for custom select arrow */
        .fc-select-wrap { position: relative; }
        .fc-select-wrap::after {
            content: '▾';
            position: absolute; right: 11px; top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.35); font-size: 10px;
            pointer-events: none;
        }
        .fc-select-wrap .fc { appearance: none; -webkit-appearance: none; padding-right: 28px; }

        .date-row { display: flex; align-items: center; gap: 6px; }
        .date-row .fc { flex: 1; min-width: 0; padding: 9px 8px; font-size: 12px; }
        .date-sep { color: rgba(255,255,255,.3); font-size: 12px; flex-shrink: 0; }

        .filter-hint { font-size: 10px; color: rgba(255,255,255,.25); margin-top: 2px; }

        .filters-actions { display: flex; flex-direction: column; gap: 7px; }

        .btn-reset {
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px; padding: 9px 14px;
            color: rgba(255,255,255,.5);
            font-family: 'Outfit', sans-serif; font-size: 12px;
            cursor: pointer; transition: all .2s; white-space: nowrap;
        }
        .btn-reset:hover { background: rgba(255,255,255,.1); color: #fff; }

        .btn-apply {
            background: linear-gradient(135deg, #ff6b00, #ff8c33);
            border: none; border-radius: 10px; padding: 9px 14px;
            color: #fff; font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 700; cursor: pointer;
            transition: opacity .2s; white-space: nowrap;
        }
        .btn-apply:hover { opacity: .88; }

        /* ── HERO (original, unchanged) ── */
        .hero { position:relative;z-index:1;padding:160px 2rem 80px;text-align:center;max-width:1280px;margin:0 auto; }
        .hero__eyebrow { display:inline-flex;align-items:center;gap:8px;background:rgba(255,107,0,.1);border:1px solid rgba(255,107,0,.25);border-radius:20px;padding:6px 16px;font-size:13px;font-weight:600;color:#ff8c33;margin-bottom:1.8rem;animation:fadeUp .6s ease both; }
        .hero__title { font-size:clamp(40px,6vw,72px);font-weight:800;color:#fff;letter-spacing:-2px;line-height:1.05;margin-bottom:1.4rem;animation:fadeUp .6s .1s ease both; }
        .hero__title .hl { background:linear-gradient(135deg,#ff6b00,#ffb366);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
        .hero__sub { font-size:18px;font-weight:400;color:rgba(255,255,255,.45);max-width:520px;margin:0 auto 2.5rem;line-height:1.65;animation:fadeUp .6s .2s ease both; }
        .hero__ctas { display:flex;align-items:center;justify-content:center;gap:14px;flex-wrap:wrap;animation:fadeUp .6s .3s ease both; }
        .hero__cta-main { display:inline-flex;align-items:center;gap:9px;background:linear-gradient(135deg,#ff6b00,#ff8c33);border:none;border-radius:14px;padding:15px 32px;color:#fff;font-family:'Outfit',sans-serif;font-size:16px;font-weight:700;cursor:pointer;text-decoration:none;transition:all .25s;box-shadow:0 8px 28px rgba(255,107,0,.35); }
        .hero__cta-main:hover { transform:translateY(-2px);box-shadow:0 12px 36px rgba(255,107,0,.5); }
        .hero__cta-ghost { display:inline-flex;align-items:center;gap:9px;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.14);border-radius:14px;padding:14px 28px;color:rgba(255,255,255,.75);font-family:'Outfit',sans-serif;font-size:16px;font-weight:600;text-decoration:none;transition:all .25s; }
        .hero__cta-ghost:hover { background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.25); }
        .hero__stats { display:flex;justify-content:center;gap:3rem;margin-top:4rem;flex-wrap:wrap;animation:fadeUp .6s .4s ease both; }
        .hero__stat { text-align:center; }
        .hero__stat-val { font-size:28px;font-weight:800;color:#fff;letter-spacing:-.5px; }
        .hero__stat-val span { color:#ff6b00; }
        .hero__stat-label { font-size:13px;color:rgba(255,255,255,.35);margin-top:2px;font-weight:400; }

        @keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }

        /* ── CONTENT (original, unchanged) ── */
        .content { position:relative;z-index:1;max-width:1280px;margin:0 auto;padding:0 2rem 6rem; }
        .pills { display:flex;flex-wrap:wrap;gap:8px;margin-bottom:1.4rem;animation:fadeUp .4s ease both; }
        .pill { display:inline-flex;align-items:center;gap:7px;background:rgba(255,107,0,.1);border:1px solid rgba(255,107,0,.22);border-radius:20px;padding:5px 13px;font-size:12px;color:#ff8c33; }
        .pill button { background:none;border:none;color:rgba(255,140,51,.7);cursor:pointer;font-size:15px;padding:0;line-height:1;transition:color .15s; }
        .pill button:hover { color:#ff6b00; }
        .section-head { display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:1.8rem;gap:1rem;flex-wrap:wrap; }
        .section-title { font-size:26px;font-weight:800;color:#fff;letter-spacing:-.5px; }
        .section-title span { color:#ff6b00; }
        .section-meta { font-size:13px;color:rgba(255,255,255,.35); }
        .section-meta strong { color:rgba(255,255,255,.7); }
        .quiz-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.2rem; }
        .qcard { position:relative;background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px;padding:1.5rem;display:flex;flex-direction:column;gap:12px;cursor:pointer;transition:transform .25s,border-color .25s,box-shadow .25s,background .25s;overflow:hidden;animation:fadeUp .5s ease both; }
        .qcard::before { content:'';position:absolute;inset:0;background:radial-gradient(circle at top right,rgba(255,107,0,.06),transparent 60%);opacity:0;transition:opacity .3s;pointer-events:none; }
        .qcard:hover { transform:translateY(-4px);border-color:rgba(255,107,0,.28);box-shadow:0 20px 40px rgba(0,0,0,.4),0 0 0 1px rgba(255,107,0,.1),inset 0 1px 0 rgba(255,255,255,.06);background:var(--glass-hover); }
        .qcard:hover::before { opacity:1; }
        .qcard__badges { display:flex;gap:7px;flex-wrap:wrap; }
        .badge { display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;letter-spacing:.2px; }
        .badge-premium { background:rgba(255,107,0,.15);border:1px solid rgba(255,107,0,.28);color:#ff8c33; }
        .badge-category { background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:rgba(255,255,255,.55); }
        .qcard__title { font-size:17px;font-weight:700;color:#fff;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
        .qcard__desc { font-size:13px;color:rgba(255,255,255,.38);line-height:1.6;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
        .qcard__meta { display:flex;flex-wrap:wrap;gap:12px;font-size:12px;color:rgba(255,255,255,.35); }
        .qcard__meta-item { display:flex;align-items:center;gap:5px; }
        .qcard__meta-item .ico { font-size:13px; }
        .qcard__rating { display:flex;align-items:center;gap:4px; }
        .stars { display:flex;gap:2px; }
        .star { font-size:11px; }
        .star.on { color:#fbbf24; }
        .star.off { color:rgba(255,255,255,.15); }
        .rating-val { font-size:12px;font-weight:600;color:rgba(255,255,255,.5); }
        .qcard__foot { display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:12px;border-top:1px solid rgba(255,255,255,.05);gap:10px; }
        .qcard__author { display:flex;align-items:center;gap:8px; }
        .author-av { width:26px;height:26px;background:linear-gradient(135deg,rgba(255,107,0,.3),rgba(255,140,51,.2));border:1px solid rgba(255,107,0,.25);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#ff8c33;flex-shrink:0; }
        .author-name { font-size:12px;color:rgba(255,255,255,.35);max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap; }
        .qcard__play { display:inline-flex;align-items:center;gap:6px;background:rgba(255,107,0,.12);border:1px solid rgba(255,107,0,.22);border-radius:10px;padding:7px 16px;color:#ff8c33;font-size:13px;font-weight:600;text-decoration:none;white-space:nowrap;transition:all .2s; }
        .qcard__play:hover { background:rgba(255,107,0,.22);border-color:rgba(255,107,0,.4);color:#fff; }
        .empty { grid-column:1/-1;text-align:center;padding:5rem 1rem;background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:20px; }
        .empty__icon { font-size:52px;margin-bottom:1.2rem; }
        .empty p { color:rgba(255,255,255,.35);font-size:15px;margin-bottom:1.2rem; }
        .empty a { color:#ff6b00;text-decoration:none;font-weight:600; }
        .empty a:hover { text-decoration:underline; }
        .pag { display:flex;justify-content:center;margin-top:3rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1100px) {
            .filters-inner { grid-template-columns: repeat(3, 1fr) auto; }
        }

        @media (max-width: 900px) {
            .hero { padding-top: 120px; }
            .hero__title { font-size: 42px; }
            .hero__stats { gap: 2rem; }
            .filters-inner { grid-template-columns: repeat(2, 1fr) auto; }
        }

        @media (max-width: 640px) {
            /* Hide desktop elements */
            .nav__centre { display: none; }
            .nav__right { display: none; }
            /* Show hamburger */
            .nav__hamburger { display: flex; }
            .nav__inner { grid-template-columns: auto 1fr; gap: 10px; }

            .filters-wrap { top: 68px; }
            .filters-inner { grid-template-columns: 1fr 1fr; gap: 10px; padding: 1.2rem 1rem; }
            .filters-actions { flex-direction: row; gap: 8px; }

            .hero { padding: 110px 1rem 60px; }
            .hero__title { font-size: 34px; letter-spacing: -1px; }
            .hero__sub { font-size: 15px; }
            .hero__stats { gap: 1.5rem; }
            .hero__stat-val { font-size: 22px; }
            .hero__ctas { flex-direction: column; align-items: stretch; }
            .hero__cta-main, .hero__cta-ghost { justify-content: center; }

            .content { padding: 0 1rem 4rem; }
            .quiz-grid { grid-template-columns: 1fr; }
            .section-title { font-size: 20px; }
        }

        @media (max-width: 420px) {
            .filters-inner { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="bg">
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    <div class="bg-grid"></div>
    <div class="bg-noise"></div>
</div>

{{-- ══ NAVBAR ══ --}}
<nav class="nav" id="mainNav">
    <div class="nav__inner">

        <a href="{{ url('/') }}" class="nav__logo">
            <div class="nav__logo-icon">⚡</div>
            <span class="nav__logo-text">Quizz<span>ies</span></span>
        </a>

        {{-- Centre: search + filters --}}
        <div class="nav__centre">
            <div class="nav__search">
                <input type="text" id="navSearch"
                       placeholder="Szukaj quizu..."
                       value="{{ request('search') }}"
                       autocomplete="off">
                <span class="nav__search-ico">🔍</span>
            </div>
            <button class="nav__filter-btn" id="filterToggle">
                ⚙ Filtry <span class="chevron">▾</span>
            </button>
        </div>

        {{-- Right: auth links --}}
        <div class="nav__right">
            @auth
                <a href="{{ route('quizzes.create') }}" class="nav__btn">＋ Stwórz quiz</a>

                {{-- User dropdown --}}
                <div class="nav__user" id="userDropdownToggle">
                    <div class="nav__avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="nav__username">{{ auth()->user()->name }}</span>
                    <span class="nav__chevron-user">▾</span>

                    <div class="nav__dropdown" id="userDropdown">
                        <div class="nav__dropdown-header">
                            <div class="nav__dropdown-name">{{ auth()->user()->name }}</div>
                            <div class="nav__dropdown-email">{{ auth()->user()->email }}</div>
                        </div>
                        <div class="nav__dropdown-divider"></div>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.quizzes') }}" class="nav__dropdown-item">🛡 Panel admina</a>
                            <div class="nav__dropdown-divider"></div>
                        @endif
                        <a href="{{ route('dashboard') }}" class="nav__dropdown-item">📊 Dashboard</a>
                        <a href="{{ route('profile.edit') }}" class="nav__dropdown-item">✏️ Edycja profilu</a>
                        <div class="nav__dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav__dropdown-item nav__dropdown-item--danger">🚪 Wyloguj</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav__link">Zaloguj się</a>
                <a href="{{ route('register') }}" class="nav__btn">Dołącz za darmo</a>
            @endauth
        </div>

        {{-- Mobile hamburger --}}
        <button class="nav__hamburger" id="mobileToggle" aria-label="Menu">☰</button>

    </div>

    {{-- Mobile menu --}}
    <div class="nav__mobile-menu" id="mobileMenu">
        <div class="nav__mobile-search">
            <input type="text" id="mobileSearch"
                   placeholder="Szukaj quizu..."
                   value="{{ request('search') }}">
            <span class="nav__mobile-search-ico">🔍</span>
        </div>
        @auth
            @if(auth()->user()->is_admin)
                <a href="{{ route('admin.quizzes') }}" class="nav__mobile-link">🛡 Panel admina</a>
            @endif
            <a href="{{ route('dashboard') }}" class="nav__mobile-link">📊 Dashboard</a>
            <a href="{{ route('profile.edit') }}" class="nav__mobile-link">✏️ Edycja profilu</a>
            <a href="{{ route('quizzes.create') }}" class="nav__mobile-btn">＋ Stwórz quiz</a>
            <form method="POST" action="{{ route('logout') }}" style="margin-top:4px;">
                @csrf
                <button type="submit" style="width:100%;background:rgba(220,50,50,.1);border:1px solid rgba(220,50,50,.2);border-radius:11px;padding:11px;color:#f87171;font-family:'Outfit',sans-serif;font-size:14px;font-weight:600;cursor:pointer;">🚪 Wyloguj</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav__mobile-link">Zaloguj się</a>
            <a href="{{ route('register') }}" class="nav__mobile-btn">⚡ Dołącz za darmo</a>
        @endauth
        <button class="nav__mobile-filter" id="mobileFilterToggle">⚙ Filtry</button>
    </div>
</nav>

{{-- ══ FILTERS ══ --}}
<div class="filters-wrap" id="filtersWrap">
    <div class="filters-panel">
        <form class="filters-inner" method="GET" action="{{ url('/') }}">

            <div class="fg">
                <span class="fg-label">Sortuj po</span>
                <div class="fc-select-wrap">
                    <select class="fc" name="sort">
                        <option value="newest"         {{ request('sort','newest')==='newest'        ?'selected':'' }}>Najnowsze</option>
                        <option value="oldest"         {{ request('sort')==='oldest'                 ?'selected':'' }}>Najstarsze</option>
                        <option value="popular"        {{ request('sort')==='popular'                ?'selected':'' }}>Najpopularniejsze</option>
                        <option value="rating"         {{ request('sort')==='rating'                 ?'selected':'' }}>Najlepiej oceniane</option>
                        <option value="questions_desc" {{ request('sort')==='questions_desc'         ?'selected':'' }}>Pytania ↓</option>
                        <option value="questions_asc"  {{ request('sort')==='questions_asc'          ?'selected':'' }}>Pytania ↑</option>
                    </select>
                </div>
            </div>

            <div class="fg">
                <span class="fg-label">Kategoria</span>
                <div class="fc-select-wrap">
                    <select class="fc" name="category">
                        <option value="">Wszystkie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="fg">
                <span class="fg-label">Autor</span>
                <input class="fc" type="text" name="author" placeholder="Nazwa użytkownika" value="{{ request('author') }}">
            </div>

            <div class="fg">
                <span class="fg-label">Data dodania</span>
                <div class="date-row">
                    <input class="fc" type="date" name="date_from" value="{{ request('date_from') }}">
                    <span class="date-sep">–</span>
                    <input class="fc" type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>

            <div class="fg">
                <span class="fg-label">Typ</span>
                <div class="fc-select-wrap">
                    <select class="fc" name="premium">
                        <option value="">Wszystkie</option>
                        <option value="0" {{ request('premium')==='0'?'selected':'' }}>Darmowe</option>
                        <option value="1" {{ request('premium')==='1'?'selected':'' }}>Premium</option>
                    </select>
                </div>
            </div>

            <div class="fg">
                <span class="fg-label">Min. ocena</span>
                <input class="fc" type="number" name="min_rating" min="1" max="5" step="0.5" placeholder="np. 3.5" value="{{ request('min_rating') }}">
                <span class="filter-hint">Skala 1–5</span>
            </div>

            <div class="filters-actions">
                <button type="button" class="btn-reset" id="resetBtn">✕ Reset</button>
                <button type="submit" class="btn-apply">Zastosuj</button>
            </div>

        </form>
    </div>
</div>

{{-- ══ HERO ══ --}}
@guest
<section class="hero">
    <div class="hero__eyebrow">⚡ Platforma quizowa #1</div>
    <h1 class="hero__title">
        Sprawdź swoją<br>
        <span class="hl">wiedzę</span> i wygrywaj
    </h1>
    <p class="hero__sub">
        Tysiące quizów z różnych dziedzin. Twórz własne,
        rywalizuj ze znajomymi i śledź swoje postępy.
    </p>
    <div class="hero__ctas">
        <a href="{{ route('register') }}" class="hero__cta-main">⚡ Zacznij za darmo</a>
        <a href="#quizzes" class="hero__cta-ghost">Przeglądaj quizy ↓</a>
    </div>
    <div class="hero__stats">
        <div class="hero__stat">
            <div class="hero__stat-val">{{ $quizzes->total() }}<span>+</span></div>
            <div class="hero__stat-label">Dostępnych quizów</div>
        </div>
        <div class="hero__stat">
            <div class="hero__stat-val">{{ $categories->count() }}<span>+</span></div>
            <div class="hero__stat-label">Kategorii tematycznych</div>
        </div>
        <div class="hero__stat">
            <div class="hero__stat-val">100<span>%</span></div>
            <div class="hero__stat-label">Za darmo na start</div>
        </div>
    </div>
</section>
@endguest

{{-- ══ CONTENT ══ --}}
<section class="content" id="quizzes" style="{{ auth()->check() ? 'padding-top:110px;' : '' }}">

    @php
        $activeFilters = array_filter([
            'search'     => request('search') ? '🔍 '.request('search') : null,
            'category'   => $categories->firstWhere('id', request('category'))?->name ? '🏷 '.$categories->firstWhere('id', request('category'))->name : null,
            'author'     => request('author') ? '👤 '.request('author') : null,
            'date_from'  => request('date_from') ? 'Od: '.request('date_from') : null,
            'date_to'    => request('date_to') ? 'Do: '.request('date_to') : null,
            'premium'    => request('premium')==='1' ? '⭐ Premium' : (request('premium')==='0' ? 'Darmowe' : null),
            'min_rating' => request('min_rating') ? '⭐ min '.request('min_rating') : null,
        ]);
    @endphp

    @if(count($activeFilters) > 0)
        <div class="pills">
            @foreach($activeFilters as $key => $label)
                <span class="pill">
                    {{ $label }}
                    <button type="button" onclick="removeFilter('{{ $key }}')">×</button>
                </span>
            @endforeach
        </div>
    @endif

    <div class="section-head">
        <h2 class="section-title">
            @if(request()->hasAny(['search','category','author','premium','min_rating','date_from','date_to']))
                Wyniki <span>wyszukiwania</span>
            @else
                Ostatnio dodane <span>quizy</span>
            @endif
        </h2>
        <span class="section-meta">Znaleziono <strong>{{ $quizzes->total() }}</strong> quizów</span>
    </div>

    <div class="quiz-grid">
        @forelse($quizzes as $i => $quiz)
            <div class="qcard" style="animation-delay:{{ $i * 0.05 }}s">
                <div class="qcard__badges">
                    @if($quiz->is_premium)<span class="badge badge-premium">⭐ Premium</span>@endif
                    @if($quiz->category)<span class="badge badge-category">{{ $quiz->category->name }}</span>@endif
                </div>
                <h3 class="qcard__title">{{ $quiz->title }}</h3>
                @if($quiz->description)<p class="qcard__desc">{{ $quiz->description }}</p>@endif
                <div class="qcard__meta">
                    <span class="qcard__meta-item"><span class="ico">📋</span>{{ $quiz->questions_count }} pytań</span>
                    <span class="qcard__meta-item"><span class="ico">▶</span>{{ number_format($quiz->attempts_count ?? 0) }} podejść</span>
                    @if($quiz->average_rating > 0)
                        <span class="qcard__meta-item qcard__rating">
                            <span class="stars">
                                @for($s=1;$s<=5;$s++)
                                    <span class="star {{ $s<=round($quiz->average_rating)?'on':'off' }}">★</span>
                                @endfor
                            </span>
                            <span class="rating-val">{{ number_format($quiz->average_rating,1) }}</span>
                        </span>
                    @endif
                </div>
                <div class="qcard__foot">
                    <div class="qcard__author">
                        <div class="author-av">{{ strtoupper(substr($quiz->user->name ?? 'A',0,1)) }}</div>
                        <span class="author-name">{{ $quiz->user->name ?? 'Anonimowy' }}</span>
                    </div>
                    <a href="{{ route('quiz.show', $quiz) }}" class="qcard__play">Zagraj →</a>
                </div>
            </div>
        @empty
            <div class="empty">
                <div class="empty__icon">🔍</div>
                <p>Brak quizów spełniających podane kryteria.</p>
                <a href="{{ url('/') }}">Pokaż wszystkie quizy →</a>
            </div>
        @endforelse
    </div>

    <div class="pag">{{ $quizzes->withQueryString()->links() }}</div>

</section>

<script>
    // Scroll effect
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => nav.classList.toggle('scrolled', scrollY > 40), {passive:true});

    // User dropdown
    const userToggle = document.getElementById('userDropdownToggle');
    const userDropdown = document.getElementById('userDropdown');
    if (userToggle && userDropdown) {
        userToggle.addEventListener('click', e => {
            e.stopPropagation();
            userToggle.classList.toggle('open');
            userDropdown.classList.toggle('open');
        });
        document.addEventListener('click', () => {
            userToggle?.classList.remove('open');
            userDropdown?.classList.remove('open');
        });
    }

    // Desktop filter toggle
    const filterToggle = document.getElementById('filterToggle');
    const filtersWrap  = document.getElementById('filtersWrap');
    filterToggle.addEventListener('click', () => {
        const open = filtersWrap.classList.toggle('open');
        filterToggle.classList.toggle('active', open);
    });

    // Mobile hamburger
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu   = document.getElementById('mobileMenu');
    mobileToggle.addEventListener('click', () => mobileMenu.classList.toggle('open'));

    // Mobile filter toggle (inside mobile menu)
    document.getElementById('mobileFilterToggle').addEventListener('click', () => {
        filtersWrap.classList.toggle('open');
        mobileMenu.classList.remove('open');
    });

    // Auto-open filters if active
    @if(request()->hasAny(['sort','category','author','date_from','date_to','premium','min_rating']))
        filtersWrap.classList.add('open');
        filterToggle.classList.add('active');
    @endif

    // Search on Enter — desktop
    document.getElementById('navSearch').addEventListener('keydown', e => {
        if (e.key !== 'Enter') return;
        const params = new URLSearchParams(window.location.search);
        const val = e.target.value.trim();
        val ? params.set('search', val) : params.delete('search');
        window.location.search = params.toString();
    });

    // Search on Enter — mobile
    document.getElementById('mobileSearch').addEventListener('keydown', e => {
        if (e.key !== 'Enter') return;
        const params = new URLSearchParams(window.location.search);
        const val = e.target.value.trim();
        val ? params.set('search', val) : params.delete('search');
        window.location.search = params.toString();
    });

    // Reset
    document.getElementById('resetBtn').addEventListener('click', () => window.location.href = '/');

    // Remove single filter pill
    function removeFilter(key) {
        const params = new URLSearchParams(window.location.search);
        params.delete(key);
        window.location.search = params.toString();
    }

    // Card stagger
    const io = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.style.opacity='1'; io.unobserve(e.target); }});
    }, {threshold:0.1});
    document.querySelectorAll('.qcard').forEach(c => { c.style.opacity='0'; io.observe(c); });
</script>

</body>
</html>