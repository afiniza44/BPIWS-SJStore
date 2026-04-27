<!DOCTYPE html>
<html lang="id" style="zoom: 0.8;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Surat Jalan BAUER') — BPI Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @stack('head')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap');

        :root {
            --primary:        #2563eb;
            --primary-hover:  #1d4ed8;
            --primary-light:  #eff6ff;
            --primary-glow:   rgba(37,99,235,.15);
            --success:        #16a34a;
            --danger:         #dc2626;
            --warning:        #d97706;
            --bg:             #f1f5f9;
            --surface:        #ffffff;
            --surface-2:      #f8fafc;
            --text:           #0f172a;
            --text-muted:     #64748b;
            --border:         #e2e8f0;
            --border-focus:   #93c5fd;
            --radius-sm:      8px;
            --radius-md:      12px;
            --radius-lg:      16px;
            --shadow-sm:      0 1px 3px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.05);
            --shadow-md:      0 4px 16px rgba(0,0,0,.08), 0 2px 6px rgba(0,0,0,.04);
            --shadow-lg:      0 10px 30px rgba(0,0,0,.10), 0 4px 10px rgba(0,0,0,.05);
        }

        /* ─── Base ─────────────────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        /* ─── Navbar ────────────────────────────────────────────────────────── */
        .navbar {
            background: rgba(255,255,255,0.92) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 0 var(--border), var(--shadow-sm);
            padding-top: .65rem;
            padding-bottom: .65rem;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--primary) !important;
            letter-spacing: -.5px;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .navbar-brand img { height: 28px; border-radius: 4px; }
        .navbar-brand .brand-sep {
            width: 1px; height: 18px;
            background: var(--border);
            display: inline-block;
            margin: 0 4px;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            font-size: .875rem;
            color: var(--text-muted) !important;
            padding: .4rem .8rem !important;
            border-radius: var(--radius-sm);
            transition: all .2s;
        }
        .navbar-nav .nav-link:hover  { color: var(--primary) !important; background: var(--primary-light); }
        .navbar-nav .nav-link.active { color: var(--primary) !important; background: var(--primary-light); font-weight: 600; }
        .user-badge {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: .3rem .75rem .3rem .4rem;
            font-size: .82rem;
            font-weight: 500;
            color: var(--text-muted);
        }
        .user-badge .avatar {
            width: 26px; height: 26px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: .7rem;
        }
        .role-tag {
            font-size: .68rem;
            font-weight: 700;
            background: var(--primary-light);
            color: var(--primary);
            padding: 1px 7px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: .3px;
        }

        /* ─── Cards ─────────────────────────────────────────────────────────── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: box-shadow .2s;
        }
        .card:hover { box-shadow: var(--shadow-md); }

        /* ─── Buttons ───────────────────────────────────────────────────────── */
        .btn {
            font-weight: 500;
            font-size: .875rem;
            border-radius: var(--radius-sm);
            transition: all .18s ease;
            letter-spacing: .1px;
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 1px 2px rgba(37,99,235,.2);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px var(--primary-glow);
        }
        .btn-outline-primary { border-color: var(--border-focus); color: var(--primary); }
        .btn-outline-primary:hover { background: var(--primary-light); border-color: var(--primary); color: var(--primary); transform: translateY(-1px); }
        .btn-outline-danger:hover  { transform: translateY(-1px); }
        .btn-outline-success:hover { transform: translateY(-1px); }
        .btn-outline-warning:hover { transform: translateY(-1px); }
        .btn-outline-secondary:hover { transform: translateY(-1px); }
        .btn-sm { font-size: .8rem; padding: .28rem .7rem; }

        /* ─── Tables ────────────────────────────────────────────────────────── */
        .table { vertical-align: middle; font-size: .875rem; }
        .table thead th {
            background: var(--surface-2);
            color: var(--text-muted);
            font-weight: 600;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .6px;
            border-bottom: 1.5px solid var(--border);
            padding: .75rem 1rem;
            white-space: nowrap;
        }
        .table tbody td { border-color: var(--border); padding: .7rem 1rem; }
        .table tbody tr { transition: background .12s; }
        .table tbody tr:hover { background: var(--primary-light); }
        .table tbody tr:last-child td { border-bottom: none; }

        /* ─── Forms ─────────────────────────────────────────────────────────── */
        .form-control, .form-select {
            border-radius: var(--radius-sm);
            border: 1.5px solid var(--border);
            padding: .55rem .9rem;
            font-size: .875rem;
            transition: border-color .2s, box-shadow .2s, background .2s;
            background: var(--surface);
            color: var(--text);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
            background: #fff;
            outline: none;
        }
        .form-label {
            font-size: .775rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: .35rem;
        }
        .input-group-text {
            background: var(--surface-2);
            border: 1.5px solid var(--border);
            color: var(--text-muted);
        }

        /* ─── Badge / Status ─────────────────────────────────────────────────── */
        .badge { font-weight: 600; font-size: .7rem; letter-spacing: .3px; padding: .35em .7em; border-radius: 6px; }
        .badge.bg-warning { background: #fef3c7 !important; color: #92400e !important; border: 1px solid #fde68a; }
        .badge.bg-success { background: #dcfce7 !important; color: #166534 !important; border: 1px solid #bbf7d0; }
        .badge.bg-danger  { background: #fee2e2 !important; color: #991b1b !important; border: 1px solid #fecaca; }
        .badge.bg-secondary { background: #f1f5f9 !important; color: #475569 !important; border: 1px solid #e2e8f0; }

        /* ─── Page Title ─────────────────────────────────────────────────────── */
        .page-title {
            font-weight: 800;
            font-size: 1.6rem;
            letter-spacing: -.5px;
            color: var(--text);
        }

        /* ─── Animations ─────────────────────────────────────────────────────── */
        .fade-in { animation: fadeUp .35s ease-out both; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── Project Grid ──────────────────────────────────────────────────── */
        .project-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
        }
        @media (max-width: 900px)  { .project-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 576px)  { .project-grid { grid-template-columns: 1fr; } }

        .project-card {
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-md);
            padding: 1.1rem 1rem;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, border-color .2s;
            display: flex;
            align-items: center;
            gap: .85rem;
            position: relative;
            overflow: hidden;
        }
        .project-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, var(--primary-light), transparent 60%);
            opacity: 0;
            transition: opacity .2s;
        }
        .project-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); border-color: var(--primary); }
        .project-card:hover::before { opacity: 1; }
        .project-card .folder-icon { font-size: 2rem; color: var(--primary); flex-shrink: 0; position: relative; z-index: 1; }
        .project-card .folder-info { flex: 1; min-width: 0; position: relative; z-index: 1; }
        .project-card .folder-name { font-weight: 700; font-size: .9rem; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .project-card .folder-count { font-size: .75rem; color: var(--text-muted); margin-top: 2px; }
        .project-card .folder-actions { display: none; gap: .3rem; position: absolute; right: .65rem; top: .65rem; z-index: 2; }
        .project-card:hover .folder-actions { display: flex; }
        .project-card.no-project { border-style: dashed; border-color: #cbd5e1; }
        .project-card.no-project .folder-icon { color: #94a3b8; }
        .project-card.no-project:hover { border-color: #94a3b8; box-shadow: var(--shadow-md); }

        /* ─── Section Header ─────────────────────────────────────────────────── */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: .75rem;
        }

        /* ─── Alert overrides ────────────────────────────────────────────────── */
        .alert { border-radius: var(--radius-md); font-size: .875rem; border-width: 1px; }
        .alert-success { background: #f0fdf4; border-color: #bbf7d0; color: #15803d; }
        .alert-danger  { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }

        /* ─── Print ─────────────────────────────────────────────────────────── */
        @media print {
            html { zoom: 1 !important; }
            .no-print { display: none !important; }
            body { background: white; }
        }

        /* ─── Bootstrap Modal Zoom Fix ───────────────────────────────────────
           zoom: 0.8 on <html> causes position:fixed elements (backdrop, modal)
           to only cover 80% of the viewport. Compensate by scaling them up
           to 125% (= 1 / 0.8) so they cover the full screen again.         */
        .modal-backdrop {
            width:  125vw !important;
            height: 125vh !important;
        }
        .modal {
            width:  125vw !important;
            height: 125vh !important;
            max-width: none;
        }
    </style>
</head>
<body>
    {{-- ── Navbar ──────────────────────────────────────────────────────────── --}}
    <nav class="navbar navbar-expand-lg sticky-top no-print">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('surat-jalan.index') }}">
                <img src="{{ asset('img/bauer-logo.jpeg') }}" alt="Logo">
                <span class="brand-sep"></span>
                <span>BPI <span style="color:var(--text-muted);font-weight:500;">Store</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto ms-3 gap-1">
                    @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}"
                           href="{{ route('barang.index') }}">
                            <i class="bi bi-box-seam me-1"></i>Master Barang
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('surat-jalan.create') ? 'active' : '' }}"
                           href="{{ route('surat-jalan.create') }}">
                            <i class="bi bi-plus-square me-1"></i>Buat SJ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('surat-jalan.index') ? 'active' : '' }}"
                           href="{{ route('surat-jalan.index') }}">
                            <i class="bi bi-journal-text me-1"></i>Daftar SJ
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    {{-- User dropdown --}}
                    <div class="dropdown">
                        <button class="user-badge border-0 bg-transparent dropdown-toggle-no-arrow"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                style="cursor:pointer;">
                            <div class="avatar" id="navAvatar">{{ strtoupper(substr(auth()->user()->username, 0, 1)) }}</div>
                            <span id="navUsername">{{ auth()->user()->username }}</span>
                            <span class="role-tag">{{ auth()->user()->role }}</span>
                            <i class="bi bi-chevron-down ms-1" style="font-size:.65rem;color:var(--text-muted);"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2"
                            style="border-radius:12px;min-width:190px;padding:.5rem;">
                            <li>
                                <div class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-700 small text-dark" id="ddUsername">{{ auth()->user()->username }}</div>
                                    <div class="text-muted" style="font-size:.72rem;">{{ auth()->user()->role }}</div>
                                </div>
                            </li>
                            <li>
                                <button class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2"
                                        onclick="openProfileModal()">
                                    <i class="bi bi-person-gear text-primary"></i>
                                    Edit Profil
                                </button>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item rounded-2 d-flex align-items-center gap-2 py-2 text-danger">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- ── Flash Messages ────────────────────────────────────────────────────── --}}
    @if(session('success'))
    <div class="container-fluid px-4 mt-3">
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="container-fluid px-4 mt-3">
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-x-circle-fill flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    {{-- ── Main Content ──────────────────────────────────────────────────────── --}}
    <main class="py-4 px-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    {{-- ── Profile Modal ────────────────────────────────────────────────────── --}}
    <div class="modal fade" id="profileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
            <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
                {{-- Header --}}
                <div class="modal-header border-0 pb-0 px-4 pt-4">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:44px;height:44px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:1.1rem;" id="profileAvatar">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:1rem;" id="profileDisplayName">{{ auth()->user()->username }}</div>
                            <div class="text-muted" style="font-size:.78rem;">{{ auth()->user()->role }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body px-4 pb-4 pt-3">
                    {{-- Tabs --}}
                    <ul class="nav nav-pills mb-4" id="profileTab" style="background:var(--surface-2);border-radius:10px;padding:4px;gap:4px;">
                        <li class="nav-item flex-fill">
                            <button class="nav-link active w-100" id="tab-info" data-bs-toggle="pill"
                                    data-bs-target="#pane-info" style="border-radius:8px;font-size:.82rem;font-weight:600;">
                                <i class="bi bi-person me-1"></i>Info Akun
                            </button>
                        </li>
                        <li class="nav-item flex-fill">
                            <button class="nav-link w-100" id="tab-pw" data-bs-toggle="pill"
                                    data-bs-target="#pane-pw" style="border-radius:8px;font-size:.82rem;font-weight:600;">
                                <i class="bi bi-lock me-1"></i>Password
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        {{-- Pane 1: Info --}}
                        <div class="tab-pane fade show active" id="pane-info">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control mb-3" id="profileUsername"
                                   value="{{ auth()->user()->username }}" placeholder="Username baru">
                            <div id="infoMsg" class="small mb-2" style="display:none;"></div>
                            <button class="btn btn-primary w-100" onclick="saveProfileInfo()">
                                <i class="bi bi-check-lg me-2"></i>Simpan Perubahan
                            </button>
                        </div>

                        {{-- Pane 2: Password --}}
                        <div class="tab-pane fade" id="pane-pw">
                            <label class="form-label">Password Lama</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="currentPw" placeholder="Password saat ini">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePw('currentPw',this)"><i class="bi bi-eye"></i></button>
                            </div>
                            <label class="form-label">Password Baru</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="newPw" placeholder="Min. 6 karakter">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePw('newPw',this)"><i class="bi bi-eye"></i></button>
                            </div>
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" id="confirmPw" placeholder="Ulangi password baru">
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePw('confirmPw',this)"><i class="bi bi-eye"></i></button>
                            </div>
                            <div id="pwStrength" class="mb-3" style="display:none;">
                                <div class="progress" style="height:4px;border-radius:4px;">
                                    <div class="progress-bar" id="pwStrengthBar" style="transition:width .3s;"></div>
                                </div>
                                <small id="pwStrengthLabel" class="text-muted"></small>
                            </div>
                            <div id="pwMsg" class="small mb-2" style="display:none;"></div>
                            <button class="btn btn-primary w-100" onclick="savePassword()">
                                <i class="bi bi-shield-lock me-2"></i>Ganti Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const _CSRF = document.querySelector('meta[name="csrf-token"]').content;

        function openProfileModal() {
            // Reset fields & messages
            document.getElementById('currentPw').value = '';
            document.getElementById('newPw').value     = '';
            document.getElementById('confirmPw').value = '';
            hideMsg('infoMsg'); hideMsg('pwMsg');
            document.getElementById('pwStrength').style.display = 'none';
            new bootstrap.Modal(document.getElementById('profileModal')).show();
        }

        function showMsg(id, text, isOk) {
            const el = document.getElementById(id);
            el.style.display  = 'block';
            el.className      = 'small mb-2 fw-semibold ' + (isOk ? 'text-success' : 'text-danger');
            el.innerHTML      = (isOk ? '<i class="bi bi-check-circle me-1"></i>' : '<i class="bi bi-x-circle me-1"></i>') + text;
        }
        function hideMsg(id) {
            const el = document.getElementById(id);
            if (el) { el.style.display = 'none'; el.textContent = ''; }
        }

        function togglePw(fieldId, btn) {
            const inp  = document.getElementById(fieldId);
            const show = inp.type === 'password';
            inp.type   = show ? 'text' : 'password';
            btn.innerHTML = show ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
        }

        // Password strength indicator
        document.getElementById('newPw')?.addEventListener('input', function() {
            const v = this.value;
            const bar   = document.getElementById('pwStrengthBar');
            const label = document.getElementById('pwStrengthLabel');
            const wrap  = document.getElementById('pwStrength');
            if (!v) { wrap.style.display = 'none'; return; }
            wrap.style.display = 'block';
            let score = 0;
            if (v.length >= 6)  score++;
            if (v.length >= 10) score++;
            if (/[A-Z]/.test(v)) score++;
            if (/[0-9]/.test(v)) score++;
            if (/[^A-Za-z0-9]/.test(v)) score++;
            const levels = [
                { w: '20%',  color: '#ef4444', text: 'Sangat Lemah' },
                { w: '40%',  color: '#f97316', text: 'Lemah' },
                { w: '60%',  color: '#eab308', text: 'Cukup' },
                { w: '80%',  color: '#22c55e', text: 'Kuat' },
                { w: '100%', color: '#16a34a', text: 'Sangat Kuat' },
            ];
            const lvl      = levels[Math.min(score, 4)];
            bar.style.width      = lvl.w;
            bar.style.background = lvl.color;
            label.textContent    = lvl.text;
            label.style.color    = lvl.color;
        });

        function saveProfileInfo() {
            const username = document.getElementById('profileUsername').value.trim();
            if (!username) { showMsg('infoMsg', 'Username tidak boleh kosong.', false); return; }
            hideMsg('infoMsg');

            fetch('/profile/info', {
                method: 'PUT',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':_CSRF, 'Accept':'application/json' },
                body: JSON.stringify({ username }),
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success) {
                    const msg = res.errors ? Object.values(res.errors).flat().join(' ') : res.message;
                    showMsg('infoMsg', msg, false);
                } else {
                    showMsg('infoMsg', res.message, true);
                    // Update all navbar references live
                    const initial = res.username.charAt(0).toUpperCase();
                    document.getElementById('navUsername').textContent     = res.username;
                    document.getElementById('navAvatar').textContent       = initial;
                    document.getElementById('ddUsername').textContent      = res.username;
                    document.getElementById('profileAvatar').textContent   = initial;
                    document.getElementById('profileDisplayName').textContent = res.username;
                }
            })
            .catch(() => showMsg('infoMsg', 'Kesalahan koneksi.', false));
        }

        function savePassword() {
            const currentPw = document.getElementById('currentPw').value;
            const newPw     = document.getElementById('newPw').value;
            const confirmPw = document.getElementById('confirmPw').value;
            hideMsg('pwMsg');

            if (!currentPw)          { showMsg('pwMsg', 'Password lama wajib diisi.', false); return; }
            if (newPw.length < 6)    { showMsg('pwMsg', 'Password baru minimal 6 karakter.', false); return; }
            if (newPw !== confirmPw) { showMsg('pwMsg', 'Konfirmasi password tidak cocok.', false); return; }

            fetch('/profile/password', {
                method: 'PUT',
                headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN':_CSRF, 'Accept':'application/json' },
                body: JSON.stringify({
                    current_password: currentPw,
                    password:         newPw,
                    password_confirmation: confirmPw,
                }),
            })
            .then(r => r.json())
            .then(res => {
                showMsg('pwMsg', res.message, res.success);
                if (res.success) {
                    document.getElementById('currentPw').value = '';
                    document.getElementById('newPw').value     = '';
                    document.getElementById('confirmPw').value = '';
                    document.getElementById('pwStrength').style.display = 'none';
                }
            })
            .catch(() => showMsg('pwMsg', 'Kesalahan koneksi.', false));
        }
    </script>
</body>
</html>
