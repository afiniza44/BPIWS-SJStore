@extends('layouts.app')
@section('title', 'Buat Surat Jalan Baru')
@push('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
@endpush

@section('content')
<div class="fade-in pb-5">
<div class="container" style="max-width:960px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="page-title">Buat Surat Jalan Baru</h1>
        <span class="badge bg-primary fs-6 fw-normal px-3 py-2">Format: PT. BAUER Pratama</span>
    </div>

    <form id="formSuratJalan">
        @csrf

        {{-- Section 0: Project --}}
        <div class="card p-4 mb-4 shadow-sm border-0" style="border-left: 4px solid #2563eb !important;">
            <div class="d-flex align-items-center gap-3">
                <i class="bi bi-folder2-open fs-4 text-primary"></i>
                <div class="flex-grow-1">
                    <label class="form-label text-muted small fw-bold mb-1">SIMPAN KE PROJECT</label>
                    <select class="form-select" id="sjProjectId">
                        <option value="">— Tanpa Project —</option>
                        @foreach($projects as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Section 1: Header --}}
        <div class="card p-4 mb-4 shadow-sm border-0">
            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-1-circle me-2"></i>Informasi Header</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">No. SJ (Kosongkan = Auto)</label>
                    <input type="text" class="form-control" id="sjNo" placeholder="Contoh: 255 /03/BPI/2026">
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-bold">DATE (Tanggal)</label>
                    <input type="date" class="form-control" id="sjTanggal" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold">DELIVERY TO (Tujuan)</label>
                    <input type="text" class="form-control" id="sjTujuan" placeholder="Nama Perusahaan Tujuan" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold">ATTN (U.p)</label>
                    <input type="text" class="form-control" id="sjAttn" placeholder="Nama Kontak Person">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-muted small fw-bold">PHONE (Telepon Atas)</label>
                    <input type="text" class="form-control" id="sjPhoneHeader" placeholder="Nomor Telepon Tujuan">
                </div>
            </div>
        </div>

        {{-- Section 2: Goods --}}
        <div class="card p-4 mb-4 shadow-sm border-0">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-2-circle me-2"></i>Description of Goods</h5>
                <div>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 me-2" onclick="addGroupRow()">
                        <i class="bi bi-collection me-1"></i>Tambah Grup (Judul)
                    </button>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addBarangRow()">
                        <i class="bi bi-plus-circle me-1"></i>Tambah Item Barang
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-borderless align-middle" id="detailBarangTable">
                    <thead class="border-bottom text-muted small">
                        <tr>
                            <th width="50%">Pilih Barang / Judul Grup</th>
                            <th width="15%">Qty</th>
                            <th width="25%">Remark</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="sjItemList" style="min-height:320px; display:table-row-group;"></tbody>
                </table>
            </div>
        </div>

        {{-- Section 3: Footer --}}
        <div class="card p-4 shadow-sm border-0">
            <h5 class="fw-bold mb-3 text-primary"><i class="bi bi-3-circle me-2"></i>Informasi Footer</h5>
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label text-muted small fw-bold">NOTE</label>
                    <input type="text" class="form-control" id="sjNote" placeholder="Catatan Tambahan">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">TAKEN BY</label>
                    <input type="text" class="form-control" id="sjTakenBy" placeholder="Dibawa oleh">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">VEHICLE NO.</label>
                    <input type="text" class="form-control" id="sjVehicleNo" placeholder="Plat Kendaraan">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">PHONE (Sopir/Pengirim)</label>
                    <input type="text" class="form-control" id="sjPhoneFooter" placeholder="No HP Sopir">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">E.T.A (Perkiraan Tiba)</label>
                    <input type="date" class="form-control" id="sjEta">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">FOREMAN</label>
                    <input type="text" class="form-control" id="sjForeman" placeholder="Nama Foreman">
                </div>
                <div class="col-md-4">
                    <label class="form-label text-muted small fw-bold">WOC</label>
                    <input type="text" class="form-control" id="sjWoc" placeholder="WOC">
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4 pt-3 border-top gap-2">
                <a href="{{ route('surat-jalan.index') }}" class="btn btn-light me-auto">Batal</a>
                <button type="button" class="btn btn-outline-secondary px-4" onclick="showPreview()">
                    <i class="bi bi-eye me-2"></i>Preview
                </button>
                <button type="button" class="btn btn-primary px-4 shadow-sm" onclick="submitSuratJalan()">
                    <i class="bi bi-check-lg me-2"></i>Simpan Surat Jalan
                </button>
            </div>
        </div>
    </form>
</div>
</div> <!-- End of fade-in container -->

{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title"><i class="bi bi-file-earmark-text me-2"></i>Preview Surat Jalan</h5>
                <div class="ms-auto d-flex gap-2">
                    <button class="btn btn-sm btn-outline-light" onclick="printPreview()">
                        <i class="bi bi-printer me-1"></i>Print
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body" style="background:#e8ecef; padding:24px;">
                <div id="previewSheet" style="width:210mm;min-height:297mm;padding:8mm 10mm;margin:0 auto;background:white;box-shadow:0 4px 20px rgba(0,0,0,.15);box-sizing:border-box;display:flex;flex-direction:column;font-family:Arial,sans-serif;font-size:10pt;">
                    <div style="display:flex;align-items:flex-start;margin-bottom:6mm;">
                        <img src="{{ asset('img/bauer-logo.jpeg') }}" alt="BAUER" style="width:65px;object-fit:contain;margin-right:8px;">
                        <div>
                            <div style="font-size:12pt;font-weight:bold;margin-bottom:1px;">PT. BAUER Pratama Indonesia</div>
                            <div style="font-size:8pt;font-style:italic;font-weight:bold;margin-bottom:2px;">International Foundation Specialist</div>
                            <div style="font-size:7pt;line-height:1.35;">
                                <div>Alamanda Tower 19th Floor Jalan TB.Simatupang Kav.23-24 Cilandak Barat Jakarta Selatan 12430 &nbsp; Indonesia</div>
                                <div>Telp &nbsp; : +62 21 2966 1988 (Hunting) Fax. : +62 21 2966 0188</div>
                                <div>Workshop : Kp. Cipicung Rt. 18 / Rw. 04 Desa Mekarsari Kec. Cileungsi Kab. Bogor</div>
                                <div>Telp &nbsp; : +62 21-2923 2795</div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:center;font-size:13pt;font-weight:bold;letter-spacing:1px;margin-bottom:4mm;">DELIVERY / TRANSFER NOTE</div>
                    <div style="font-size:9pt;font-weight:bold;margin-bottom:4mm;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:2px;">
                            <div style="display:flex;"><div style="width:95px;flex-shrink:0;">DATE</div><div style="width:12px;">:</div><div id="pv_date">-</div></div>
                            <div style="white-space:nowrap;">No. &nbsp;&nbsp; <span id="pv_no">—</span></div>
                        </div>
                        <div style="display:flex;margin-bottom:2px;"><div style="width:95px;flex-shrink:0;">DELIVERY TO</div><div style="width:12px;">:</div><div id="pv_dest">-</div></div>
                        <div style="display:flex;margin-bottom:2px;"><div style="width:95px;flex-shrink:0;">ATTN</div><div style="width:12px;">:</div><div id="pv_attn">-</div></div>
                        <div style="display:flex;margin-bottom:2px;"><div style="width:95px;flex-shrink:0;">PHONE</div><div style="width:12px;">:</div><div id="pv_phone">-</div></div>
                    </div>
                    <table style="width:100%;border-collapse:collapse;border-top:3px solid #000;font-size:7.7pt;">
                        <thead>
                            <tr style="border-bottom:2px solid #000;">
                                <th style="width:5%;padding:4px 3px;background:#eff1f0;text-align:center;">No</th>
                                <th style="width:12%;padding:4px 3px;background:#eff1f0;text-align:center;">Asset /<br>ID No.</th>
                                <th style="width:43%;padding:4px 3px;background:#eff1f0;text-align:center;">Description of Goods</th>
                                <th style="width:8%;padding:4px 3px;background:#eff1f0;text-align:center;">Qty</th>
                                <th style="width:8%;padding:4px 3px;background:#eff1f0;text-align:center;">Unit</th>
                                <th style="width:24%;padding:4px 3px;background:#eff1f0;text-align:center;">Remark</th>
                            </tr>
                        </thead>
                        <tbody id="pv_items"></tbody>
                    </table>
                    <div style="flex:1;"></div>
                    <div style="display:grid;grid-template-columns:100px 12px auto;row-gap:3px;font-size:9pt;font-weight:bold;text-transform:uppercase;margin-top:6mm;margin-bottom:2mm;">
                        <div>NOTE</div><div>:</div><div id="pv_note" style="color:#1a56db;">-</div>
                        <div>TAKEN BY</div><div>:</div><div id="pv_takenby">-</div>
                        <div>VEHICLE NO.</div><div>:</div><div id="pv_vehicle">-</div>
                        <div>PHONE</div><div>:</div><div id="pv_phonef">-</div>
                        <div>E.T.A</div><div>:</div><div id="pv_eta">-</div>
                    </div>
                    <div style="font-size:8.5pt;font-style:italic;margin-top:3mm;margin-bottom:3mm;">I certify that I have examined and received the above in good condition</div>
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;font-size:9pt;margin-bottom:2mm;">
                        <div>Signature</div>
                        <div style="text-align:center;font-weight:bold;margin-right:35px;">Issued By :<br>PT. Bauer Pratama Indonesia</div>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:flex-end;text-align:center;font-size:8.5pt;margin-top:6mm;">
                        <div style="flex:1;padding:0 5px;"><div style="height:50px;display:flex;align-items:flex-end;justify-content:center;font-size:8pt;">..........................</div><div style="padding-top:3px;">Driver</div></div>
                        <div style="flex:1;padding:0 5px;"><div style="height:50px;display:flex;align-items:flex-end;justify-content:center;font-size:8pt;">..........................</div><div style="padding-top:3px;">Receiver</div></div>
                        <div style="flex:1;padding:0 5px;"><div id="pv_foreman" style="height:50px;display:flex;align-items:flex-end;justify-content:center;font-weight:bold;text-decoration:underline;text-underline-offset:3px;"></div><div style="padding-top:3px;">Foreman</div></div>
                        <div style="flex:1;max-width:90px;padding:0 5px;"><div id="pv_woc" style="height:50px;display:flex;align-items:flex-end;justify-content:center;font-weight:bold;text-decoration:underline;text-underline-offset:3px;"></div><div style="padding-top:3px;">WOC</div></div>
                        <div style="flex:1;padding:0 5px;"><div style="height:50px;display:flex;align-items:flex-end;justify-content:center;font-size:8pt;">..........................</div><div style="padding-top:3px;">Store Keeper</div></div>
                    </div>
                    <div style="font-size:7pt;color:#888;margin-top:3mm;">BPI-QR-WS-009 Rev.00</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const masterBarang = @json($barang);

    // Pre-select project from URL
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('sjTanggal').valueAsDate = new Date();
        addBarangRow();

        const params = new URLSearchParams(window.location.search);
        const pid = params.get('project_id');
        if (pid) {
            const sel = document.getElementById('sjProjectId');
            if (sel) sel.value = pid;
        }
    });

    // ─── Item Rows ────────────────────────────────────────────────────────────
    function addBarangRow() {
        const tbody = document.getElementById('sjItemList');
        const tr    = document.createElement('tr');
        tr.className  = 'fade-in row-item';
        tr.dataset.type = 'item';
        const uid = 'ts-' + Date.now() + '-' + Math.random().toString(36).slice(2);
        tr.innerHTML = `
            <td class="pb-3 ps-4 align-top">
                <select id="${uid}" class="sj-barang" required>
                    <option value="">-- Pilih Barang --</option>
                    ${masterBarang.map(b => `<option value="${b.id}">${b.sku} — ${b.nama_barang}</option>`).join('')}
                </select>
            </td>
            <td class="pb-3 align-top"><input type="number" class="form-control sj-qty" min="1" value="1" required></td>
            <td class="pb-3 align-top"><input type="text" class="form-control sj-remark" placeholder="Remark"></td>
            <td class="text-center pb-3 align-top">
                <button type="button" class="btn btn-outline-danger rounded px-3" onclick="this.closest('tr').remove()"><i class="bi bi-x-lg"></i></button>
            </td>
        `;
        tbody.appendChild(tr);

        new TomSelect(`#${uid}`, {
            maxOptions: 100,
            placeholder: 'Cari SKU atau nama barang...',
            dropdownParent: 'body',
            render: {
                option: (data, escape) => {
                    const parts = data.text.split(' — ');
                    const sku   = parts[0] || '';
                    const nama  = parts.slice(1).join(' — ') || data.text;
                    return `<div class="d-flex align-items-center gap-2"><span class="badge bg-secondary" style="font-size:.7rem;white-space:nowrap">${escape(sku)}</span><span>${escape(nama)}</span></div>`;
                },
                item: (data, escape) => `<div>${escape(data.text)}</div>`,
            }
        });
    }

    function addGroupRow() {
        const tbody = document.getElementById('sjItemList');
        const tr    = document.createElement('tr');
        tr.className   = 'fade-in row-item';
        tr.dataset.type = 'group_title';
        tr.innerHTML = `
            <td colspan="3" class="pb-3 pt-3">
                <div class="input-group">
                    <span class="input-group-text bg-light text-dark fw-bold">Judul Grup Barang</span>
                    <input type="text" class="form-control sj-group-text" placeholder="Contoh: 23 x Plastic Box consist of:" required>
                </div>
            </td>
            <td class="text-center pb-3 pt-3">
                <button type="button" class="btn btn-outline-danger rounded px-3" onclick="this.closest('tr').remove()"><i class="bi bi-x-lg"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // ─── Submit ───────────────────────────────────────────────────────────────
    function submitSuratJalan() {
        const payload = {
            manual_no:    document.getElementById('sjNo').value,
            tanggal:      document.getElementById('sjTanggal').value,
            tujuan:       document.getElementById('sjTujuan').value,
            attn:         document.getElementById('sjAttn').value,
            phone_header: document.getElementById('sjPhoneHeader').value,
            note:         document.getElementById('sjNote').value,
            taken_by:     document.getElementById('sjTakenBy').value,
            vehicle_no:   document.getElementById('sjVehicleNo').value,
            phone_footer: document.getElementById('sjPhoneFooter').value,
            eta:          document.getElementById('sjEta').value,
            foreman:      document.getElementById('sjForeman').value,
            woc:          document.getElementById('sjWoc').value,
            project_id:   document.getElementById('sjProjectId').value || null,
            items:        [],
        };

        if (!payload.tanggal || !payload.tujuan) { alert('Tanggal dan Tujuan wajib diisi.'); return; }

        document.querySelectorAll('#sjItemList .row-item').forEach((row, idx) => {
            const type = row.dataset.type;
            if (type === 'group_title') {
                const text = row.querySelector('.sj-group-text')?.value;
                if (text) payload.items.push({ type: 'group_title', text });
            } else {
                const barang_id = row.querySelector('.sj-barang')?.value;
                const qty       = row.querySelector('.sj-qty')?.value;
                const remark    = row.querySelector('.sj-remark')?.value || '';
                if (barang_id && qty) payload.items.push({ type: 'item', id: parseInt(barang_id), qty: parseInt(qty), remark });
            }
        });

        if (payload.items.length === 0) { alert('Silakan tambah minimal 1 item atau grup barang.'); return; }

        fetch('/surat-jalan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: JSON.stringify(payload),
        })
        .then(r => r.json())
        .then(res => {
            if (!res.success) { alert('Gagal: ' + (res.message || JSON.stringify(res.errors))); return; }
            const msg = res.status === 'APPROVED'
                ? `Sukses. Surat Jalan dibuat: ${res.no_surat_jalan}.`
                : `Sukses. Surat Jalan diajukan: ${res.no_surat_jalan}. Menunggu Approval Admin.`;
            alert(msg);
            window.location.href = '/surat-jalan';
        })
        .catch(() => alert('Terjadi kesalahan koneksi.'));
    }

    // ─── Preview ──────────────────────────────────────────────────────────────
    function showPreview() {
        const tanggal    = document.getElementById('sjTanggal').value;
        const dateObj    = tanggal ? new Date(tanggal) : new Date();
        const fmtDate    = dateObj.toLocaleDateString('en-GB', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        const noSj       = document.getElementById('sjNo').value.trim() || '(Auto)';
        document.getElementById('pv_date').innerText   = fmtDate;
        document.getElementById('pv_no').innerText     = noSj;
        document.getElementById('pv_dest').innerText   = document.getElementById('sjTujuan').value      || '-';
        document.getElementById('pv_attn').innerText   = document.getElementById('sjAttn').value        || '-';
        document.getElementById('pv_phone').innerText  = document.getElementById('sjPhoneHeader').value || '-';
        document.getElementById('pv_note').innerText   = document.getElementById('sjNote').value        || '-';
        document.getElementById('pv_takenby').innerText = document.getElementById('sjTakenBy').value    || '-';
        document.getElementById('pv_vehicle').innerText = document.getElementById('sjVehicleNo').value  || '-';
        document.getElementById('pv_phonef').innerText  = document.getElementById('sjPhoneFooter').value|| '-';
        const etaVal = document.getElementById('sjEta').value;
        document.getElementById('pv_eta').innerText = etaVal
            ? new Date(etaVal).toLocaleDateString('id-ID', { year:'numeric', month:'long', day:'numeric' }) : '-';
        document.getElementById('pv_foreman').innerText = document.getElementById('sjForeman').value || '';
        document.getElementById('pv_woc').innerText     = document.getElementById('sjWoc').value     || '';

        const tbody = document.getElementById('pv_items');
        tbody.innerHTML = '';
        let rowNum = 1; let insideGroup = false;
        document.querySelectorAll('#sjItemList .row-item').forEach(row => {
            const type = row.dataset.type;
            if (type === 'group_title') {
                const text = row.querySelector('.sj-group-text')?.value || '';
                if (!text) return;
                const tr = document.createElement('tr');
                tr.innerHTML = `<td style="text-align:center;padding:3px;">${rowNum++}</td><td style="padding:3px;"></td><td colspan="4" style="text-align:left;padding:3px 3px 3px 8px;font-weight:bold;">${text}</td>`;
                tbody.appendChild(tr); insideGroup = true;
            } else {
                const selectEl = row.querySelector('.sj-barang');
                const barangId = selectEl?.value;
                const qty      = row.querySelector('.sj-qty')?.value    || '';
                const remark   = row.querySelector('.sj-remark')?.value || '';
                if (!barangId) return;
                const barang   = masterBarang.find(b => b.id == barangId);
                const sku      = barang?.sku         || '-';
                const nama     = barang?.nama_barang || '-';
                const satuan   = barang?.satuan      || '-';
                const numStr   = insideGroup ? '-' : rowNum++;
                const pad      = insideGroup ? 'padding:3px 3px 3px 20px;' : 'padding:3px 3px 3px 8px;';
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="text-align:center;padding:3px;">${numStr}</td>
                    <td style="text-align:center;padding:3px;">${sku}</td>
                    <td style="text-align:left;${pad}">${nama}</td>
                    <td style="text-align:center;font-weight:bold;padding:3px;">${qty}</td>
                    <td style="text-align:center;padding:3px;">${satuan}</td>
                    <td style="text-align:center;padding:3px;">${remark}</td>
                `;
                tbody.appendChild(tr);
            }
        });
        if (tbody.children.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:8px;color:#888;">Belum ada item.</td></tr>';
        }
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    }

    function printPreview() {
        const sheet = document.getElementById('previewSheet').outerHTML;
        const win   = window.open('', '_blank');
        win.document.write(`<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Print Preview</title>
        <style>
          @page { size:A4 portrait; margin-top:.06in; margin-bottom:.29in; margin-left:0; margin-right:0; }
          body { margin:0; padding:0; background:white; position:relative; }
          body::before { content:"PREVIEW ONLY"; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%) rotate(-40deg); font-size:72pt; font-weight:900; font-family:Arial,sans-serif; color:rgba(220,38,38,.18); letter-spacing:6px; white-space:nowrap; pointer-events:none; z-index:9999; -webkit-print-color-adjust:exact; print-color-adjust:exact; }
        </style></head><body>${sheet}</body></html>`);
        win.document.close(); win.focus();
        setTimeout(() => win.print(), 400);
    }
</script>
@endpush
