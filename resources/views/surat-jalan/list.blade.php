@extends('layouts.app')
@section('title', 'Daftar Surat Jalan')

@section('content')
<div class="fade-in pb-5">

    {{-- ═══ VIEW 1: PROJECT GRID ═══ --}}
    <div id="view-projects" class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h1 class="page-title mb-0"><i class="bi bi-journal-text me-2"></i>Daftar Surat Jalan</h1>
            <div class="d-flex gap-2 flex-wrap">
                @if(auth()->user()->isAdmin())
                <button class="btn btn-outline-danger btn-sm shadow-sm" onclick="showDeletedView()">
                    <i class="bi bi-archive me-1"></i>Arsip Terhapus
                </button>
                <button class="btn btn-outline-secondary btn-sm shadow-sm" onclick="openAddProjectModal()">
                    <i class="bi bi-folder-plus me-1"></i>Tambah Folder
                </button>
                @endif
                <a href="{{ route('surat-jalan.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Buat Baru
                </a>
            </div>
        </div>
        <div id="projectGrid" class="project-grid">
            <div class="text-center text-muted py-5" style="grid-column:1/-1">
                <div class="spinner-border spinner-border-sm me-2"></div>Memuat data...
            </div>
        </div>
    </div>

    {{-- ═══ VIEW 1.5: PROJECT SUB-FOLDERS ═══ --}}
    <div id="view-project-subfolders" class="container mt-4" style="display:none;">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="showProjectGrid()">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </button>
                <span class="text-muted small">Daftar Surat Jalan /</span>
                <span class="fw-bold ms-1" id="breadcrumbProjectSubName">—</span>
            </div>
        </div>
        <div class="project-grid">
            {{-- Active Documents Folder --}}
            <div class="project-card" onclick="showActiveDocuments()">
                <i class="bi bi-folder-fill folder-icon text-primary"></i>
                <div class="folder-info">
                    <div class="folder-name">Surat Jalan (Active)</div>
                    <div class="folder-count">Lihat Daftar Dokumen</div>
                </div>
            </div>
            {{-- Export Folder --}}
            <div class="project-card" onclick="downloadProjectZip()">
                <i class="bi bi-folder-fill folder-icon text-success"></i>
                <div class="folder-info">
                    <div class="folder-name">Export (PDF)</div>
                    <div class="folder-count text-success fw-bold"><i class="bi bi-download me-1"></i>Download Project ZIP</div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══ VIEW 2: SJ TABLE INSIDE A PROJECT ═══ --}}
    <div id="view-sj-table" style="display:none;">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="backToSubfolders()">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </button>
                <span class="text-muted small" id="breadcrumbFull">Daftar Surat Jalan /</span>
                <span class="fw-bold ms-1" id="breadcrumbProjectName">—</span>

            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if(auth()->user()->isAdmin())
                <button class="btn btn-sm btn-outline-warning shadow-sm" id="btnRenameProject" style="display:none;" onclick="openRenameProjectModal()">
                    <i class="bi bi-pencil me-1"></i>Rename
                </button>
                <button class="btn btn-sm btn-outline-danger shadow-sm" id="btnDeleteProject" style="display:none;" onclick="deleteCurrentProject()">
                    <i class="bi bi-trash me-1"></i>Hapus Folder
                </button>
                @endif
                <a id="btnBuatBaruProject" href="{{ route('surat-jalan.create') }}" class="btn btn-primary shadow-sm btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Buat Baru
                </a>
            </div>
        </div>
        <div class="card p-4 shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="18%">No. SJ</th>
                            <th width="12%">Tanggal</th>
                            <th width="22%">Tujuan</th>
                            <th width="13%">Pembuat</th>
                            <th width="12%">Status</th>
                            <th width="23%" class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="sjTableBody">
                        <tr><td colspan="6" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══ VIEW 3: DELETED ARCHIVE (admin only) ═══ --}}
    @if(auth()->user()->isAdmin())
    <div id="view-deleted" style="display:none;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <button class="btn btn-sm btn-outline-secondary me-2" onclick="showProjectGrid()">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </button>
                <span class="text-muted small">Daftar Surat Jalan /</span>
                <span class="fw-bold ms-1 text-danger">Arsip Terhapus</span>
            </div>
        </div>
        <div class="card p-4 shadow-sm border-0">
            <div class="alert alert-warning d-flex gap-2 align-items-center mb-3">
                <i class="bi bi-shield-exclamation fs-5"></i>
                <div><strong>Arsip Audit Trail.</strong> Surat Jalan yang telah dihapus tersimpan di sini. Hanya mode Preview yang tersedia.</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No. SJ</th><th>Tanggal</th><th>Tujuan</th><th>Project</th>
                            <th>Dihapus Oleh</th><th>Waktu Hapus</th><th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="deletedSjTableBody">
                        <tr><td colspan="7" class="text-center py-4 text-muted">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Modal Add/Rename Project --}}
<div class="modal fade" id="projectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="projectModalTitle">Tambah Folder Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-3">
                <label class="form-label text-muted small fw-bold">Nama Project</label>
                <input type="text" class="form-control" id="projectNameInput" placeholder="Contoh: Project Sicincin 2026">
                <div id="projectModalError" class="text-danger small mt-2" style="display:none;"></div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="projectModalSaveBtn" onclick="saveProject()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF       = document.querySelector('meta[name="csrf-token"]').content;
    const IS_ADMIN   = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
    let currentProjectId   = null;
    let currentProjectName = null;
    let _editingProjectId  = null;

    // ─── Load projects on page load ──────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => loadProjects());

    function loadProjects() {
        fetch('/projects', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(renderProjectGrid)
            .catch(() => {
                document.getElementById('projectGrid').innerHTML =
                    '<p class="text-danger" style="grid-column:1/-1">Gagal memuat project.</p>';
            });
    }

    function renderProjectGrid(projects) {
        const grid = document.getElementById('projectGrid');
        grid.innerHTML = '';

        if (projects.length === 0) {
            const empty = document.createElement('div');
            empty.style.gridColumn = '1 / -1';
            empty.className = 'text-center text-muted py-4';
            empty.innerHTML = '<i class="bi bi-folder-x fs-2 d-block mb-2"></i>Belum ada folder project. Klik "Tambah Folder" untuk membuat.';
            grid.appendChild(empty);
        }

        projects.forEach(p => {
            const card = document.createElement('div');
            card.className = 'project-card';
            card.innerHTML = `
                <i class="bi bi-folder-fill folder-icon"></i>
                <div class="folder-info">
                    <div class="folder-name">${p.name}</div>
                    <div class="folder-count">${p.sj_count} Surat Jalan</div>
                </div>
                ${IS_ADMIN ? `
                <div class="folder-actions">
                    <button class="btn btn-sm btn-outline-warning py-0 px-2" title="Rename"
                        onclick="event.stopPropagation(); openRenameProjectModal(${p.id}, '${p.name.replace(/'/g,'\\\'')}')"
                    ><i class="bi bi-pencil"></i></button>
                    <button class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus"
                        onclick="event.stopPropagation(); deleteProject(${p.id}, '${p.name.replace(/'/g,'\\\'')}')"
                    ><i class="bi bi-trash"></i></button>
                </div>` : ''}
            `;
            card.addEventListener('click', () => openProjectFolder(p.id, p.name));
            grid.appendChild(card);

        });

        // "Tanpa Project" card
        const noProj = document.createElement('div');
        noProj.className = 'project-card no-project';
        noProj.innerHTML = `
            <i class="bi bi-folder2 folder-icon"></i>
            <div class="folder-info">
                <div class="folder-name">Tanpa Project</div>
                <div class="folder-count">SJ tanpa folder</div>
            </div>
        `;
        noProj.addEventListener('click', () => loadSJByProject('none', 'Tanpa Project'));
        grid.appendChild(noProj);
    }

    function showProjectGrid() {
        currentProjectId = null; currentProjectName = null;
        document.getElementById('view-projects').style.display = '';
        document.getElementById('view-project-subfolders').style.display = 'none';
        document.getElementById('view-sj-table').style.display = 'none';
        if (IS_ADMIN) document.getElementById('view-deleted').style.display = 'none';
        loadProjects();
    }

    function openProjectFolder(projectId, projectName) {
        if (projectId === 'none') {
            loadSJByProject('none', 'Tanpa Project');
            return;
        }
        currentProjectId   = projectId;
        currentProjectName = projectName;
        
        document.getElementById('view-projects').style.display = 'none';
        document.getElementById('view-sj-table').style.display = 'none';
        document.getElementById('view-project-subfolders').style.display = '';
        document.getElementById('breadcrumbProjectSubName').textContent = projectName;
    }

    function backToSubfolders() {
        if (currentProjectId === 'none') {
            showProjectGrid();
        } else {
            openProjectFolder(currentProjectId, currentProjectName);
        }
    }

    function downloadProjectZip() {
        if (!currentProjectId) return;
        
        const btn = event.currentTarget.querySelector('.folder-count');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
        
        window.location.href = `/projects/${currentProjectId}/export-pdf`;
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 3000);
    }

    function showActiveDocuments() {
        loadSJByProject(currentProjectId, currentProjectName);
    }

    function loadSJByProject(projectId, projectName) {
        currentProjectId   = projectId;
        currentProjectName = projectName;
        document.getElementById('view-projects').style.display = 'none';
        document.getElementById('view-project-subfolders').style.display = 'none';
        document.getElementById('view-sj-table').style.display = '';
        if (IS_ADMIN) document.getElementById('view-deleted').style.display = 'none';

        const breadcrumbFull = document.getElementById('breadcrumbFull');
        if (breadcrumbFull) {
            breadcrumbFull.innerHTML = `Daftar Surat Jalan / <span class="text-secondary">${projectName}</span> /`;
        }
        document.getElementById('breadcrumbProjectName').textContent = 'Active Documents';


        const btnBuat = document.getElementById('btnBuatBaruProject');
        if (btnBuat) btnBuat.href = projectId === 'none' ? '/surat-jalan/create' : `/surat-jalan/create?project_id=${projectId}`;

        const btnRename = document.getElementById('btnRenameProject');
        const btnDelete = document.getElementById('btnDeleteProject');
        if (btnRename) btnRename.style.display = (IS_ADMIN && projectId !== 'none') ? '' : 'none';
        if (btnDelete) btnDelete.style.display = (IS_ADMIN && projectId !== 'none') ? '' : 'none';

        let url = '/surat-jalan/json';
        if (projectId === 'none') url += '?unassigned=true';
        else url += `?project_id=${projectId}`;

        const tbody = document.getElementById('sjTableBody');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4"><div class="spinner-border spinner-border-sm me-2"></div>Memuat...</td></tr>';

        fetch(url, { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada Surat Jalan di folder ini.</td></tr>';
                    return;
                }
                data.forEach(sj => {
                    let badge = '';
                    if (sj.status === 'PENDING') badge = '<span class="badge bg-warning text-dark">PENDING</span>';
                    else if (sj.status === 'APPROVED') badge = '<span class="badge bg-success">APPROVED</span>';
                    else badge = `<span class="badge bg-danger">${sj.status}</span>`;

                    let aksi = `<a href="/surat-jalan/${sj.id}/print" class="btn btn-sm btn-outline-info rounded-pill px-3 me-1 mb-1"><i class="bi bi-eye"></i> Detail</a>`;
                    if (sj.status === 'APPROVED') {
                        aksi += `<a href="/surat-jalan/${sj.id}/print?print=1" class="btn btn-sm btn-outline-dark rounded-pill px-3 mb-1"><i class="bi bi-printer"></i> Cetak</a>`;
                    } else if (sj.status === 'PENDING' && IS_ADMIN) {
                        aksi += `<button class="btn btn-sm btn-success rounded-pill px-3 me-1 mb-1" onclick="approveSJ(${sj.id})"><i class="bi bi-check-circle"></i> Approve</button>`;
                        aksi += `<button class="btn btn-sm btn-danger rounded-pill px-3 mb-1" onclick="rejectSJ(${sj.id})"><i class="bi bi-x-circle"></i> Deny</button>`;
                    }
                    if (IS_ADMIN) {
                        aksi += `<button class="btn btn-sm btn-outline-danger rounded-pill px-2 mb-1 ms-1" onclick="deleteSJ(${sj.id})"><i class="bi bi-trash"></i></button>`;
                    }

                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="fw-bold text-primary">${sj.no_surat_jalan}</td>
                        <td>${sj.tanggal}</td>
                        <td>${sj.tujuan}</td>
                        <td><i class="bi bi-person me-1 text-muted"></i>${sj.creator}</td>
                        <td>${badge}</td>
                        <td class="text-end">${aksi}</td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger">Gagal memuat data.</td></tr>';
            });
    }

    function refreshCurrentSJList() {
        if (currentProjectId !== null) loadSJByProject(currentProjectId, currentProjectName);
    }

    // ─── Status actions ───────────────────────────────────────────────────────
    function approveSJ(id) {
        if (!confirm('Approve Surat Jalan ini?')) return;
        fetch(`/surat-jalan/${id}/status`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ status: 'APPROVED' }),
        }).then(r => r.json()).then(res => { if (res.success) refreshCurrentSJList(); else alert(res.message); });
    }
    function rejectSJ(id) {
        if (!confirm('Tolak Surat Jalan ini?')) return;
        fetch(`/surat-jalan/${id}/status`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify({ status: 'REJECTED' }),
        }).then(r => r.json()).then(res => { if (res.success) refreshCurrentSJList(); else alert(res.message); });
    }
    function deleteSJ(id) {
        if (!confirm('Hapus Surat Jalan ini? Data akan tersimpan di Arsip Terhapus.')) return;
        fetch(`/surat-jalan/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        }).then(r => r.json()).then(res => { if (res.success) refreshCurrentSJList(); else alert(res.message); });
    }

    // ─── Deleted Archive ──────────────────────────────────────────────────────
    function showDeletedView() {
        if (!IS_ADMIN) return;
        document.getElementById('view-projects').style.display = 'none';
        document.getElementById('view-sj-table').style.display = 'none';
        document.getElementById('view-deleted').style.display = '';

        const tbody = document.getElementById('deletedSjTableBody');
        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted"><div class="spinner-border spinner-border-sm me-2"></div>Memuat...</td></tr>';

        fetch('/surat-jalan/deleted', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } })
            .then(r => r.json())
            .then(data => {
                tbody.innerHTML = '';
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-muted"><i class="bi bi-check-circle fs-3 d-block mb-2"></i>Belum ada Surat Jalan terhapus.</td></tr>';
                    return;
                }
                data.forEach(sj => {
                    const delDate = sj.deleted_at ? new Date(sj.deleted_at).toLocaleString('id-ID') : '-';
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td class="fw-bold text-secondary">${sj.no_surat_jalan}</td>
                        <td>${sj.tanggal}</td>
                        <td>${sj.tujuan}</td>
                        <td>${sj.project_name || '<em class="text-muted">Tanpa Project</em>'}</td>
                        <td>${sj.deleted_by_name || '-'}</td>
                        <td><small>${delDate}</small></td>
                        <td class="text-end">
                            <a href="/surat-jalan/${sj.id}/print" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                <i class="bi bi-eye"></i> Preview
                            </a>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            })
            .catch(() => { tbody.innerHTML = '<tr><td colspan="7" class="text-danger text-center py-4">Gagal memuat arsip.</td></tr>'; });
    }

    // ─── Project Modal ────────────────────────────────────────────────────────
    function openAddProjectModal() {
        _editingProjectId = null;
        document.getElementById('projectModalTitle').textContent = 'Tambah Folder Project';
        document.getElementById('projectNameInput').value = '';
        document.getElementById('projectModalError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('projectModal')).show();
    }
    function openRenameProjectModal(id, name) {
        _editingProjectId = id || currentProjectId;
        const _name = name || currentProjectName;
        document.getElementById('projectModalTitle').textContent = 'Rename Folder';
        document.getElementById('projectNameInput').value = _name;
        document.getElementById('projectModalError').style.display = 'none';
        new bootstrap.Modal(document.getElementById('projectModal')).show();
    }
    function saveProject() {
        const name   = document.getElementById('projectNameInput').value.trim();
        const errEl  = document.getElementById('projectModalError');
        if (!name) { errEl.textContent = 'Nama project tidak boleh kosong.'; errEl.style.display = 'block'; return; }
        errEl.style.display = 'none';

        const isEdit = !!_editingProjectId;
        const url    = isEdit ? `/projects/${_editingProjectId}` : '/projects';
        const method = isEdit ? 'PUT' : 'POST';

        const btn = document.getElementById('projectModalSaveBtn');
        btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }, body: JSON.stringify({ name }) })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false; btn.innerHTML = 'Simpan';
                if (!data.success) {
                    const msg = data.errors ? Object.values(data.errors).flat().join('\n') : data.message;
                    errEl.textContent = msg; errEl.style.display = 'block';
                } else {
                    bootstrap.Modal.getInstance(document.getElementById('projectModal')).hide();
                    if (isEdit && currentProjectId == _editingProjectId) {
                        currentProjectName = name;
                        document.getElementById('breadcrumbProjectName').textContent = name;
                    }
                    loadProjects();
                    if (document.getElementById('view-sj-table').style.display !== 'none') {
                        loadSJByProject(currentProjectId, currentProjectName);
                    }
                }
            })
            .catch(() => { btn.disabled = false; btn.innerHTML = 'Simpan'; errEl.textContent = 'Kesalahan koneksi.'; errEl.style.display = 'block'; });
    }
    function deleteProject(id, name) {
        if (!confirm(`Hapus folder "${name}"?\n\nFolder hanya dapat dihapus jika tidak ada Surat Jalan aktif.`)) return;
        fetch(`/projects/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => { if (!data.success) alert(data.message); else loadProjects(); })
            .catch(() => alert('Kesalahan koneksi.'));
    }
    function deleteCurrentProject() {
        if (currentProjectId && currentProjectId !== 'none') {
            deleteProject(currentProjectId, currentProjectName);
            showProjectGrid();
        }
    }
</script>
@endpush
