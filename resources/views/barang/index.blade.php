@extends('layouts.app')
@section('title', 'Master Barang')
@push('head')
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
@endpush

@section('content')
<div class="fade-in pb-5">
    <div class="section-header">
        <h1 class="page-title"><i class="bi bi-box-seam me-2 text-primary"></i>Master Barang</h1>
        @if(auth()->user()->isAdmin())
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success shadow-sm" data-bs-toggle="modal" data-bs-target="#importModal" onclick="resetImport()">
                <i class="bi bi-file-earmark-excel me-2"></i>Import Excel
            </button>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#barangModal" onclick="openAddModal()">
                <i class="bi bi-plus-lg me-2"></i>Tambah Barang
            </button>
        </div>
        @endif
    </div>

    {{-- Search --}}
    <div class="mb-3">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="searchBarang" class="form-control border-start-0 ps-0"
                placeholder="Cari SKU atau Nama Barang..." oninput="filterBarang(this.value)">
            <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn"
                onclick="clearSearch()" style="display:none;"><i class="bi bi-x-lg"></i></button>
        </div>
        <div id="searchResultInfo" class="text-muted small mt-1" style="display:none;"></div>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="barangTable">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th width="20%">SKU</th>
                        <th width="45%">Nama Barang</th>
                        <th width="15%">Satuan</th>
                        @if(auth()->user()->isAdmin())
                        <th width="15%" class="text-end">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody id="barangTableBody">
                    @forelse($barang as $item)
                    <tr>
                        <td class="fw-bold">{{ $item->id }}</td>
                        <td><span class="badge bg-secondary">{{ $item->sku }}</span></td>
                        <td class="fw-medium text-dark">{{ $item->nama_barang }}</td>
                        <td>{{ $item->satuan }}</td>
                        @if(auth()->user()->isAdmin())
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1"
                                onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->sku) }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->satuan }}')">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                onclick="deleteBarang({{ $item->id }})">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="text-center py-4 text-muted">Belum ada data barang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Tambah/Edit Barang --}}
<div class="modal fade" id="barangModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="barangId">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">SKU</label>
                    <input type="text" class="form-control" id="sku" placeholder="Contoh: BRG-001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" placeholder="Contoh: Pipa PVC 2 inch" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Satuan</label>
                    <select class="form-select" id="satuan" required>
                        <option value="" disabled selected>Pilih satuan...</option>
                        @foreach(['Pcs','Box','Kg','Liter','Meter','Pairs','Set','ST','Unit'] as $s)
                        <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveBarang()">Simpan Data</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-excel text-success me-2"></i>Import Master Barang dari Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info d-flex align-items-start gap-2 py-2 mb-3">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div class="small">
                        File Excel harus memiliki kolom: <strong>sku</strong>, <strong>nama_barang</strong>, <strong>satuan</strong>.
                        SKU duplikat akan dilewati secara otomatis.<br>
                        <a href="#" onclick="downloadTemplate(); return false;" class="fw-bold"><i class="bi bi-download me-1"></i>Unduh Template Excel</a>
                    </div>
                </div>
                <div id="importDropZone" class="border border-2 rounded-3 p-5 text-center position-relative"
                     style="border-color:#ced4da; border-style:dashed !important; background:#f8f9fa; cursor:pointer; transition:all .2s;"
                     ondragover="handleDragOver(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)"
                     onclick="document.getElementById('excelFileInput').click()">
                    <i class="bi bi-cloud-arrow-up display-4 text-success"></i>
                    <p class="mt-2 mb-0 fw-semibold text-secondary">Klik atau seret file Excel ke sini</p>
                    <p class="small text-muted mb-0">.xlsx / .xls &mdash; Maks. 100 MB</p>
                    <input type="file" id="excelFileInput" accept=".xlsx,.xls" class="d-none" onchange="handleFileSelect(this.files[0])">
                </div>
                <div id="importFilePreview" class="d-none mt-3 p-3 rounded-3 bg-light d-flex align-items-center gap-3">
                    <i class="bi bi-file-earmark-excel-fill text-success fs-2"></i>
                    <div class="flex-grow-1">
                        <div class="fw-semibold" id="importFileName"></div>
                        <div class="text-muted small" id="importFileSize"></div>
                    </div>
                    <button class="btn btn-sm btn-outline-danger" onclick="clearImportFile()"><i class="bi bi-x-lg"></i></button>
                </div>
                <div id="importProgress" class="d-none mt-3">
                    <div class="progress" style="height:8px">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success w-100"></div>
                    </div>
                </div>
                <div id="importResult" class="d-none mt-3"></div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="importSubmitBtn" onclick="submitImport()" disabled>
                    <i class="bi bi-upload me-2"></i>Mulai Import
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    let _importFile = null;

    // ─── Search ───────────────────────────────────────────────────────────────
    function filterBarang(query) {
        const clearBtn = document.getElementById('clearSearchBtn');
        const infoEl   = document.getElementById('searchResultInfo');
        const tbody    = document.getElementById('barangTableBody');
        const q        = query.trim().toLowerCase();
        if (clearBtn) clearBtn.style.display = q ? 'inline-block' : 'none';
        if (!q) {
            Array.from(tbody.querySelectorAll('tr')).forEach(tr => tr.style.display = '');
            if (infoEl) infoEl.style.display = 'none';
            return;
        }
        const rows = Array.from(tbody.querySelectorAll('tr'));
        let matchCount = 0;
        rows.forEach(tr => {
            const sku  = (tr.cells[1]?.textContent || '').toLowerCase();
            const nama = (tr.cells[2]?.textContent || '').toLowerCase();
            const ok   = sku.includes(q) || nama.includes(q);
            tr.style.display = ok ? '' : 'none';
            if (ok) matchCount++;
        });
        if (infoEl) {
            infoEl.style.display = 'block';
            infoEl.innerHTML = matchCount === 0
                ? `<i class="bi bi-exclamation-circle me-1"></i>Tidak ada hasil untuk <strong>"${query}"</strong>.`
                : `<i class="bi bi-check-circle me-1 text-success"></i>Ditemukan <strong>${matchCount}</strong> barang.`;
        }
    }
    function clearSearch() {
        const el = document.getElementById('searchBarang');
        if (el) { el.value = ''; el.focus(); }
        filterBarang('');
    }

    // ─── Modal helpers ────────────────────────────────────────────────────────
    function openAddModal() {
        document.getElementById('barangId').value = '';
        document.getElementById('sku').value = '';
        document.getElementById('nama_barang').value = '';
        document.getElementById('satuan').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Barang';
    }
    function openEditModal(id, sku, nama, satuan) {
        document.getElementById('barangId').value = id;
        document.getElementById('sku').value = sku;
        document.getElementById('nama_barang').value = nama;
        document.getElementById('satuan').value = satuan;
        document.getElementById('modalTitle').innerText = 'Edit Barang';
        new bootstrap.Modal(document.getElementById('barangModal')).show();
    }

    // ─── Save Barang ──────────────────────────────────────────────────────────
    function saveBarang() {
        const id  = document.getElementById('barangId').value;
        const data = {
            sku:         document.getElementById('sku').value,
            nama_barang: document.getElementById('nama_barang').value,
            satuan:      document.getElementById('satuan').value,
        };
        if (!data.sku || !data.nama_barang || !data.satuan) { alert('Data tidak boleh kosong!'); return; }

        const url    = id ? `/barang/${id}` : '/barang';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(data),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) { location.reload(); }
            else { alert(res.message || res.errors ? Object.values(res.errors).flat().join('\n') : 'Gagal menyimpan.'); }
        })
        .catch(() => alert('Kesalahan koneksi.'));
    }

    // ─── Delete Barang ────────────────────────────────────────────────────────
    function deleteBarang(id) {
        if (!confirm('Yakin ingin menghapus barang ini?')) return;
        fetch(`/barang/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(res => { if (res.success) location.reload(); else alert(res.message); })
        .catch(() => alert('Kesalahan koneksi.'));
    }

    // ─── Import Excel ─────────────────────────────────────────────────────────
    function resetImport() {
        _importFile = null;
        const fi = document.getElementById('excelFileInput');
        if (fi) fi.value = '';
        document.getElementById('importFilePreview')?.classList.add('d-none');
        document.getElementById('importProgress')?.classList.add('d-none');
        const r = document.getElementById('importResult');
        if (r) { r.classList.add('d-none'); r.innerHTML = ''; }
        const btn = document.getElementById('importSubmitBtn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-upload me-2"></i>Mulai Import'; }
    }
    function handleFileSelect(file) {
        if (!file) return;
        if (!/\.(xlsx|xls)$/i.test(file.name)) { alert('Hanya file Excel (.xlsx/.xls).'); return; }
        if (file.size > 100 * 1024 * 1024) { alert('File terlalu besar (maks. 100 MB).'); return; }
        _importFile = file;
        document.getElementById('importFileName').textContent = file.name;
        document.getElementById('importFileSize').textContent = (file.size / 1024).toFixed(1) + ' KB';
        document.getElementById('importFilePreview').classList.remove('d-none');
        document.getElementById('importResult').classList.add('d-none');
        document.getElementById('importProgress').classList.add('d-none');
        const btn = document.getElementById('importSubmitBtn');
        if (btn) btn.disabled = false;
    }
    function clearImportFile() { resetImport(); }
    function handleDragOver(e) {
        e.preventDefault();
        const dz = document.getElementById('importDropZone');
        dz.style.borderColor = '#198754'; dz.style.background = '#e8f5e9';
    }
    function handleDragLeave(e) {
        const dz = document.getElementById('importDropZone');
        dz.style.borderColor = '#ced4da'; dz.style.background = '#f8f9fa';
    }
    function handleDrop(e) {
        e.preventDefault(); handleDragLeave(e);
        const file = e.dataTransfer.files[0];
        if (file) handleFileSelect(file);
    }
    function downloadTemplate() {
        const rows = [['sku','nama_barang','satuan'],['BRG-001','Contoh Barang 1','Pcs'],['BRG-002','Contoh Barang 2','Box']];
        if (window.XLSX) {
            const ws = XLSX.utils.aoa_to_sheet(rows);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Master Barang');
            XLSX.writeFile(wb, 'template_master_barang.xlsx');
        }
    }
    function submitImport() {
        if (!_importFile) { alert('Pilih file terlebih dahulu.'); return; }
        const btn = document.getElementById('importSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
        document.getElementById('importProgress').classList.remove('d-none');
        document.getElementById('importResult').classList.add('d-none');

        const formData = new FormData();
        formData.append('file', _importFile);

        fetch('/barang/import', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('importProgress').classList.add('d-none');
            const res = document.getElementById('importResult');
            res.classList.remove('d-none');
            if (!data.success) {
                res.innerHTML = `<div class="alert alert-danger d-flex gap-2"><i class="bi bi-x-circle-fill mt-1"></i><div><strong>Gagal:</strong> ${data.message}</div></div>`;
                btn.disabled = false; btn.innerHTML = '<i class="bi bi-upload me-2"></i>Coba Lagi';
            } else {
                let errRows = '';
                if (data.errors?.length) {
                    errRows = `<div class="mt-2 small text-danger"><strong>Detail error:</strong><ul>${data.errors.map(e => `<li>Baris ${e.row} (${e.sku}): ${e.reason}</li>`).join('')}</ul></div>`;
                }
                res.innerHTML = `<div class="alert alert-success d-flex gap-2 mb-0"><i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i><div><strong>Import Selesai!</strong><br><span class="text-success fw-semibold">${data.inserted} data berhasil ditambahkan</span> &bull; <span class="text-muted">${data.skipped} dilewati (SKU duplikat)</span>${errRows}</div></div>`;
                btn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Selesai';
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(err => {
            document.getElementById('importProgress').classList.add('d-none');
            document.getElementById('importResult').classList.remove('d-none');
            document.getElementById('importResult').innerHTML = `<div class="alert alert-danger">Kesalahan koneksi: ${err.message}</div>`;
            btn.disabled = false; btn.innerHTML = '<i class="bi bi-upload me-2"></i>Coba Lagi';
        });
    }
</script>
@endpush
