<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#173b2c">
    <title>@yield('title', 'Cropwise') · Crop Disease Detector</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #1e3026;
            --muted: #718077;
            --line: #e4e9e4;
            --paper: #f6f8f5;
            --white: #fff;
            --green: #173b2c;
            --green-2: #255b40;
            --green-pale: #e8f0e9;
            --lime: #c8e66c;
            --gold: #e9b653;
            --red: #bd5a43;
            --shadow: 0 14px 36px rgba(27, 53, 38, .07);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; color: var(--ink); background: var(--paper); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; font-size: 14px; }
        a { color: inherit; text-decoration: none; }
        button, input { font: inherit; }
        .app-shell { min-height: 100vh; }
        .sidebar { position: fixed; inset: 0 auto 0 0; z-index: 5; display: flex; width: 246px; flex-direction: column; padding: 25px 17px 20px; color: #eff5ef; background: var(--green); }
        .brand { display: flex; align-items: center; gap: 11px; padding: 3px 10px 28px; }
        .brand-mark { display: grid; width: 38px; height: 38px; place-items: center; border-radius: 13px; color: var(--green); background: var(--lime); font-size: 20px; }
        .brand-name { font-size: 16px; font-weight: 750; letter-spacing: -.04em; }
        .brand-subtitle { margin-top: 3px; color: #aac2b1; font-size: 10px; letter-spacing: .1em; text-transform: uppercase; }
        .nav-label { margin: 8px 11px 9px; color: #91ad9b; font-size: 10px; font-weight: 700; letter-spacing: .13em; text-transform: uppercase; }
        .nav-list { display: grid; gap: 5px; }
        .nav-link { display: flex; min-height: 42px; align-items: center; gap: 12px; padding: 0 12px; border-radius: 10px; color: #b7cbbd; font-size: 13px; transition: background .16s ease, color .16s ease; }
        .nav-link:hover, .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
        .nav-icon { display: inline-grid; width: 18px; place-items: center; color: #c6d9cc; font-size: 15px; }
        .nav-count { margin-left: auto; padding: 3px 7px; border-radius: 20px; color: #d5e8a0; background: rgba(200,230,108,.12); font-size: 10px; }
        .sidebar-spacer { flex: 1; }
        .offline-card { margin: 12px 3px 17px; padding: 14px; border: 1px solid rgba(220,238,223,.12); border-radius: 13px; background: rgba(255,255,255,.055); }
        .offline-heading { display: flex; align-items: center; gap: 8px; color: #f2f7f1; font-size: 12px; font-weight: 650; }
        .offline-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--lime); box-shadow: 0 0 0 4px rgba(200,230,108,.12); }
        .offline-card p { margin: 8px 0 0; color: #adc3b3; font-size: 11px; line-height: 1.55; }
        .sidebar-footer { padding: 13px 10px 0; border-top: 1px solid rgba(255,255,255,.12); color: #97b09f; font-size: 11px; }
        .main { min-height: 100vh; margin-left: 246px; }
        .topbar { position: sticky; top: 0; z-index: 4; display: flex; height: 68px; align-items: center; justify-content: space-between; padding: 0 38px; border-bottom: 1px solid var(--line); background: rgba(255,255,255,.92); backdrop-filter: blur(12px); }
        .crumb { color: #89958e; font-size: 12px; }
        .crumb strong { color: #33473a; font-weight: 650; }
        .top-actions { display: flex; align-items: center; gap: 18px; }
        .today { color: #78857d; font-size: 12px; }
        .profile { display: flex; align-items: center; gap: 9px; }
        .avatar { display: grid; width: 33px; height: 33px; place-items: center; border-radius: 50%; color: var(--green); background: #e5eddf; font-size: 12px; font-weight: 750; }
        .profile-label { color: #516258; font-size: 11px; font-weight: 650; }
        .content { width: min(1260px, 100%); margin: 0 auto; padding: 34px 38px 50px; }
        .page-heading { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; margin-bottom: 23px; }
        .eyebrow { margin: 0 0 8px; color: #6c8b70; font-size: 10px; font-weight: 750; letter-spacing: .15em; text-transform: uppercase; }
        h1, h2, h3, p { margin-top: 0; }
        h1 { margin-bottom: 7px; font-family: Georgia, "Times New Roman", serif; font-size: clamp(29px, 3vw, 38px); font-weight: 500; letter-spacing: -.045em; }
        .page-description { margin: 0; color: var(--muted); font-size: 13px; }
        .date-chip { display: inline-flex; align-items: center; gap: 8px; padding: 9px 12px; border: 1px solid var(--line); border-radius: 9px; color: #536359; background: #fff; font-size: 11px; white-space: nowrap; }
        .date-chip span { color: var(--green-2); }
        .hero { position: relative; display: grid; min-height: 210px; grid-template-columns: 1fr 250px; align-items: center; overflow: hidden; margin-bottom: 21px; padding: 28px 35px; border-radius: 17px; color: white; background: linear-gradient(115deg, #214c35, #173b2c 66%, #23553b); box-shadow: 0 18px 35px rgba(23,59,44,.14); }
        .hero::after { position: absolute; top: -120px; right: 120px; width: 330px; height: 330px; border: 1px solid rgba(226,242,197,.12); border-radius: 50%; content: ""; box-shadow: 0 0 0 35px rgba(226,242,197,.025), 0 0 0 75px rgba(226,242,197,.025); }
        .hero-copy { position: relative; z-index: 1; max-width: 600px; }
        .hero-kicker { margin-bottom: 12px; color: #d1e4aa; font-size: 10px; font-weight: 750; letter-spacing: .15em; text-transform: uppercase; }
        .hero h2 { max-width: 500px; margin-bottom: 10px; font-family: Georgia, "Times New Roman", serif; font-size: clamp(24px, 2.7vw, 34px); font-weight: 500; letter-spacing: -.04em; line-height: 1.13; }
        .hero p { max-width: 510px; margin-bottom: 18px; color: #cfddd1; font-size: 12px; line-height: 1.65; }
        .hero-button { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 8px; color: #1f3b29; background: var(--lime); font-size: 11px; font-weight: 750; }
        .hero-button:hover { background: #d5ef83; }
        .hero-art { position: relative; z-index: 1; display: grid; place-items: center; height: 160px; color: rgba(207,232,160,.92); }
        .hero-art .sun { position: absolute; top: 17px; right: 28px; width: 78px; height: 78px; border-radius: 50%; background: rgba(211,234,165,.11); }
        .leaf { position: absolute; border: 1px solid rgba(216,237,183,.53); border-radius: 100% 0 100% 0; transform: rotate(-35deg); }
        .leaf.one { width: 90px; height: 125px; top: 20px; left: 70px; }
        .leaf.two { width: 67px; height: 97px; top: 57px; left: 126px; transform: rotate(23deg); }
        .leaf.three { width: 56px; height: 81px; top: 10px; left: 139px; transform: rotate(48deg); }
        .stem { position: absolute; top: 51px; left: 128px; width: 1px; height: 109px; background: rgba(216,237,183,.55); transform: rotate(17deg); }
        .stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 13px; margin-bottom: 23px; }
        .stat-card, .panel { border: 1px solid var(--line); border-radius: 13px; background: var(--white); box-shadow: 0 4px 16px rgba(35,60,43,.025); }
        .stat-card { min-height: 112px; padding: 17px 18px; }
        .stat-top { display: flex; align-items: center; justify-content: space-between; gap: 8px; color: #77847c; font-size: 11px; }
        .stat-symbol { display: grid; width: 30px; height: 30px; place-items: center; border-radius: 9px; color: var(--green-2); background: var(--green-pale); font-size: 14px; }
        .stat-value { margin-top: 11px; color: #23382b; font-family: Georgia, "Times New Roman", serif; font-size: 28px; line-height: 1; }
        .stat-note { margin-top: 7px; color: #98a29c; font-size: 10px; }
        .section-grid { display: grid; grid-template-columns: minmax(0, 1.55fr) minmax(270px, .85fr); gap: 17px; align-items: start; }
        .panel { padding: 20px; }
        .panel-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
        .panel-title { margin: 0; font-size: 14px; font-weight: 750; letter-spacing: -.015em; }
        .panel-subtitle { margin: 5px 0 0; color: #8b978f; font-size: 11px; }
        .text-link { color: #4c7954; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .text-link:hover { color: var(--green); }
        .disease-list { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
        .disease-card { position: relative; min-height: 115px; padding: 14px; border: 1px solid #edf0ed; border-radius: 11px; transition: border-color .16s ease, transform .16s ease; }
        .disease-card:hover { border-color: #cbd8cb; transform: translateY(-2px); }
        .disease-card.healthy { background: #f7fbf1; }
        .crop-tag { display: inline-flex; align-items: center; gap: 5px; margin-bottom: 10px; color: #809087; font-size: 9px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .disease-card h3 { margin: 0 0 5px; color: #293d30; font-size: 12px; font-weight: 750; }
        .disease-card p { display: -webkit-box; overflow: hidden; margin: 0; color: #849087; font-size: 10px; line-height: 1.5; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
        .severity { position: absolute; top: 13px; right: 12px; padding: 4px 7px; border-radius: 20px; color: #9d6234; background: #fcf2df; font-size: 8px; font-weight: 750; text-transform: capitalize; }
        .severity.high { color: #9d4c3b; background: #faece8; }
        .severity.none { color: #4c7650; background: #eaf3e6; }
        .activity-list { display: grid; }
        .activity-item { display: grid; grid-template-columns: 38px 1fr auto; gap: 10px; align-items: center; padding: 11px 0; border-bottom: 1px solid #eef1ee; }
        .activity-item:last-child { padding-bottom: 0; border-bottom: 0; }
        .activity-thumb { display: grid; width: 38px; height: 38px; overflow: hidden; place-items: center; border-radius: 9px; color: #66826d; background: #edf2e9; font-size: 16px; }
        .activity-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .activity-name { overflow: hidden; margin: 0 0 4px; color: #304236; font-size: 11px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .activity-meta { color: #95a098; font-size: 9px; }
        .activity-status { padding: 4px 7px; border-radius: 20px; color: #876b33; background: #fbf4df; font-size: 8px; font-weight: 700; white-space: nowrap; }
        .activity-status.confirmed { color: #42724c; background: #eaf4e8; }
        .activity-status.corrected { color: #4a6783; background: #eaf1f6; }
        .empty-state { display: grid; min-height: 145px; place-items: center; padding: 18px; color: #8b978f; text-align: center; }
        .empty-icon { display: grid; width: 38px; height: 38px; place-items: center; margin: 0 auto 10px; border-radius: 50%; color: #65816b; background: #edf3eb; font-size: 17px; }
        .empty-state strong { display: block; margin-bottom: 4px; color: #59685e; font-size: 11px; }
        .empty-state p { max-width: 210px; margin: 0; font-size: 10px; line-height: 1.5; }
        .crop-strip { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 17px; }
        .crop-pill { padding: 7px 10px; border: 1px solid #e8ede7; border-radius: 8px; color: #65746a; background: #fbfcfa; font-size: 10px; }
        .crop-pill strong { margin-left: 6px; color: #304c37; }
        .footer-note { display: flex; justify-content: space-between; gap: 12px; margin-top: 22px; padding: 15px 2px 0; border-top: 1px solid var(--line); color: #97a199; font-size: 10px; }
        .footer-note strong { color: #68786d; font-weight: 650; }
        @media (max-width: 1100px) {
            .sidebar { width: 215px; }
            .main { margin-left: 215px; }
            .topbar { padding: 0 25px; }
            .content { padding: 28px 25px 40px; }
            .hero { grid-template-columns: 1fr 190px; padding: 25px; }
            .hero-art { transform: scale(.86); }
            .stats { gap: 9px; }
            .stat-card { padding: 14px; }
        }
        @media (max-width: 820px) {
            .sidebar { position: static; width: 100%; height: auto; padding: 13px 17px; }
            .brand { padding: 0; }
            .nav-label, .sidebar-spacer, .offline-card, .sidebar-footer { display: none; }
            .nav-list { display: flex; overflow-x: auto; margin-top: 12px; }
            .nav-link { min-height: 34px; flex: 0 0 auto; padding: 0 10px; font-size: 11px; }
            .nav-count { display: none; }
            .main { margin-left: 0; }
            .topbar { height: 54px; padding: 0 19px; }
            .content { padding: 25px 19px 34px; }
            .hero { grid-template-columns: 1fr 145px; min-height: 195px; }
            .hero-art { transform: scale(.7); }
            .stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .section-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 520px) {
            .top-actions { gap: 8px; }
            .today { display: none; }
            .profile-label { display: none; }
            .page-heading { align-items: flex-start; flex-direction: column; }
            .hero { grid-template-columns: 1fr; padding: 23px; }
            .hero-art { display: none; }
            .disease-list { grid-template-columns: 1fr; }
            .panel { padding: 16px; }
            .footer-note { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a class="brand" href="{{ route('dashboard') }}" aria-label="Cropwise home">
                <span class="brand-mark" aria-hidden="true">✳</span>
                <span>
                    <span class="brand-name">Cropwise</span>
                    <span class="brand-subtitle">Field intelligence</span>
                </span>
            </a>

            <div class="nav-label">Workspace</div>
            <nav class="nav-list" aria-label="Main navigation">
                <a class="nav-link active" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span>Overview</a>
                <a class="nav-link" href="#diseases"><span class="nav-icon">❋</span>Disease library<span class="nav-count">{{ $diseases->count() }}</span></a>
                <a class="nav-link" href="#activity"><span class="nav-icon">▤</span>Recent diagnoses</a>
                <a class="nav-link" href="#field-trials"><span class="nav-icon">⌖</span>Field programme</a>
            </nav>

            <div class="sidebar-spacer"></div>
            <div class="offline-card">
                <div class="offline-heading"><span class="offline-dot"></span>Offline-ready diagnosis</div>
                <p>Farmers can identify leaf diseases on-device. Records sync when a connection returns.</p>
            </div>
            <div class="sidebar-footer">Bindura, Zimbabwe <span style="float:right">BUSE · 2026</span></div>
        </aside>

        <main class="main">
            <header class="topbar">
                <div class="crumb">Crop disease detector <span aria-hidden="true">/</span> <strong>@yield('breadcrumb', 'Overview')</strong></div>
                <div class="top-actions">
                    <div class="today">{{ now()->format('D, d M Y') }}</div>
                    <div class="profile"><span class="avatar">CD</span><span class="profile-label">Field dashboard</span></div>
                </div>
            </header>
            <div class="content">
                @yield('content')
                <footer class="footer-note">
                    <span><strong>Cropwise</strong> · Crop Disease Detector</span>
                    <span>Diagnosis runs on the farmer’s phone. This server supports review and synchronisation.</span>
                </footer>
            </div>
        </main>
    </div>
</body>
</html>
