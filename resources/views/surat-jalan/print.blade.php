<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Jalan — {{ $sj->no_surat_jalan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { background-color: #f7f9fa; font-family: Arial, sans-serif; font-size: 10pt; font-weight: normal; }
        @page { size: A4 portrait; margin: 0; }
        .page-a4 { width: 210mm; min-height: 297mm; padding: 8mm 10mm; margin: 10mm auto; background: white; box-shadow: 0 4px 15px rgba(0,0,0,.12); box-sizing: border-box; display: flex; flex-direction: column; }
        .company-header { display: flex; align-items: flex-start; margin-bottom: 6mm; }
        .bauer-logo-img { width: 65px; object-fit: contain; margin-right: 8px; }
        .company-name { font-size: 12pt; font-weight: bold; margin-bottom: 1px; }
        .company-sub  { font-size: 8pt; font-style: italic; font-weight: bold; margin-bottom: 2px; }
        .company-addr { font-size: 7pt; line-height: 1.35; }
        .doc-title { text-align: center; font-size: 13pt; font-weight: bold; letter-spacing: 1px; margin-bottom: 4mm; }
        .info-section { font-size: 9pt; font-weight: bold; margin-bottom: 4mm; }
        .info-row { display: flex; margin-bottom: 2px; }
        .info-row-top { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .info-label { width: 95px; flex-shrink: 0; }
        .info-colon { width: 12px; }
        .info-value { flex: 1; }
        .info-no { font-weight: bold; white-space: nowrap; }
        .table-custom { width: 100%; border-collapse: collapse; border-top: 3px solid #000; font-size: 7.7pt; }
        .table-custom thead tr { border-bottom: 2px solid #000; }
        .table-custom th { padding: 4px 3px; background-color: #eff1f0; -webkit-print-color-adjust: exact; print-color-adjust: exact; text-align: center; font-weight: bold; }
        .table-custom td { padding: 3px; vertical-align: top; }
        .page-spacer { flex: 1; }
        .footer-info { display: grid; grid-template-columns: 100px 12px auto; row-gap: 3px; font-size: 9pt; font-weight: bold; text-transform: uppercase; margin-top: 6mm; margin-bottom: 2mm; }
        .certify-line { font-size: 8.5pt; font-style: italic; margin-top: 3mm; margin-bottom: 3mm; }
        .issued-row { display: flex; justify-content: space-between; align-items: flex-start; font-size: 9pt; margin-bottom: 2mm; }
        .issued-right { text-align: center; font-weight: bold; margin-right: 35px; }
        .sig-container { display: flex; justify-content: space-between; align-items: flex-end; text-align: center; font-size: 8.5pt; margin-top: 6mm; }
        .sig-box { flex: 1; padding: 0 5px; }
        .sig-sign-area { height: 50px; display: flex; justify-content: center; font-size: 8pt; }
        .sig-sign-area.dashed { align-items: flex-end; }
        .sig-sign-area.named { align-items: flex-end; font-weight: bold; text-decoration: underline; text-underline-offset: 3px; }
        .sig-label { padding-top: 3px; }
        .doc-ref { font-size: 7pt; color: #888; margin-top: 3mm; }
        @media print {
            body { background: transparent; margin: 0; padding: 0 !important; overflow: visible !important; }
            .page-a4 { margin: 0; box-shadow: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-light">
    {{-- Toolbar --}}
    <div class="no-print bg-dark py-3 px-4 shadow d-flex justify-content-between align-items-center">
        <button onclick="history.back()" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</button>
        <h5 class="text-white mb-0">Preview Surat Jalan (Format BAUER)</h5>
        @if($sj->status === 'APPROVED' && !$sj->deleted_at)
        <button class="btn btn-primary" onclick="window.print()">
            <i class="bi bi-printer me-2"></i>Cetak Dokumen
        </button>
        @else
        <button class="btn btn-secondary" disabled>
            <i class="bi bi-info-circle me-2"></i>
            {{ $sj->deleted_at ? 'Sudah Dihapus (Preview Only)' : 'Belum di-Approve' }}
        </button>
        @endif
    </div>

    {{-- A4 --}}
    <div class="pb-5" style="width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <div class="page-a4" id="printArea" style="margin-left: auto; margin-right: auto;">
        <div class="company-header">
            <img src="{{ asset('img/bauer-logo.jpeg') }}" alt="BAUER Logo" class="bauer-logo-img">
            <div>
                <div class="company-name">PT. BAUER Pratama Indonesia</div>
                <div class="company-sub">International Foundation Specialist</div>
                <div class="company-addr">
                    <div>Alamanda Tower 19th Floor Jalan TB.Simatupang Kav.23-24 Cilandak Barat Jakarta Selatan 12430 &nbsp; Indonesia</div>
                    <div>Telp &nbsp; : +62 21 2966 1988 (Hunting) Fax. : +62 21 2966 0188</div>
                    <div>Workshop : Kp. Cipicung Rt. 18 / Rw. 04 Desa Mekarsari Kec. Cileungsi Kab. Bogor</div>
                    <div>Telp &nbsp; : +62 21-2923 2795</div>
                </div>
            </div>
        </div>

        <div class="doc-title">DELIVERY / TRANSFER NOTE</div>

        <div class="info-section">
            <div class="info-row-top">
                <div class="d-flex">
                    <div class="info-label">DATE</div>
                    <div class="info-colon">:</div>
                    <div class="info-value">{{ $sj->tanggal?->translatedFormat('l, d F Y') ?? '-' }}</div>
                </div>
                <div class="info-no">No. &nbsp;&nbsp; {{ $sj->no_surat_jalan }}</div>
            </div>
            <div class="info-row"><div class="info-label">DELIVERY TO</div><div class="info-colon">:</div><div class="info-value">{{ $sj->tujuan ?? '-' }}</div></div>
            <div class="info-row"><div class="info-label">ATTN</div><div class="info-colon">:</div><div class="info-value">{{ $sj->attn ?? '-' }}</div></div>
            <div class="info-row"><div class="info-label">PHONE</div><div class="info-colon">:</div><div class="info-value">{{ $sj->phone_header ?? '-' }}</div></div>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="12%">Asset /<br>ID No.</th>
                    <th width="43%">Description of Goods</th>
                    <th width="8%">Qty</th>
                    <th width="8%">Unit</th>
                    <th width="24%">Remark</th>
                </tr>
            </thead>
            <tbody>
                @php $rowNum = 1; $insideGroup = false; @endphp
                @foreach($sj->details as $detail)
                    @if($detail->type === 'group_title')
                        @php $insideGroup = true; @endphp
                        <tr>
                            <td class="text-center">{{ $rowNum++ }}</td>
                            <td></td>
                            <td colspan="4" class="ps-2 fw-bold">{{ $detail->group_title_text }}</td>
                        </tr>
                    @else
                        @php $numStr = $insideGroup ? '-' : $rowNum++; @endphp
                        <tr>
                            <td class="text-center">{{ $numStr }}</td>
                            <td class="text-center">{{ $detail->barang?->sku ?? '-' }}</td>
                            <td class="{{ $insideGroup ? 'ps-4' : 'ps-2' }}">{{ $detail->barang?->nama_barang ?? '-' }}</td>
                            <td class="text-center fw-bold">{{ $detail->qty }}</td>
                            <td class="text-center">{{ $detail->barang?->satuan ?? '-' }}</td>
                            <td class="text-center">{{ $detail->remark ?? '' }}</td>
                        </tr>
                    @endif
                @endforeach
                @if($sj->details->isEmpty())
                <tr><td colspan="6" class="text-center py-2 text-muted">Tidak ada item.</td></tr>
                @endif
            </tbody>
        </table>

        <div class="page-spacer"></div>

        <div class="footer-info">
            <div>NOTE</div><div>:</div><div class="text-capitalize fw-bold">{{ $sj->note ?? '-' }}</div>
            <div>TAKEN BY</div><div>:</div><div class="text-capitalize">{{ $sj->taken_by ?? '-' }}</div>
            <div>VEHICLE NO.</div><div>:</div><div class="text-capitalize">{{ $sj->vehicle_no ?? '-' }}</div>
            <div>PHONE</div><div>:</div><div>{{ $sj->phone_footer ?? '-' }}</div>
            <div>E.T.A</div><div>:</div>
            <div>{{ $sj->eta ? $sj->eta->translatedFormat('d F Y') : '-' }}</div>
        </div>

        <div class="certify-line">I certify that I have examined and received the above in good condition</div>

        <div class="issued-row">
            <div>Signature</div>
            <div class="issued-right">Issued By :<br>PT. Bauer Pratama Indonesia</div>
        </div>

        <div class="sig-container">
            <div class="sig-box"><div class="sig-sign-area dashed">................</div><div class="sig-label">Driver</div></div>
            <div class="sig-box"><div class="sig-sign-area dashed">................</div><div class="sig-label">Receiver</div></div>
            <div class="sig-box"><div class="sig-sign-area named">{{ $sj->foreman ?? '' }}</div><div class="sig-label">Foreman</div></div>
            <div class="sig-box" style="max-width:90px;"><div class="sig-sign-area named">{{ $sj->woc ?? '' }}</div><div class="sig-label">WOC</div></div>
            <div class="sig-box"><div class="sig-sign-area dashed">..........................</div><div class="sig-label">Store Keeper</div></div>
        </div>

        <div class="doc-ref">BPI-QR-WS-009 Rev.00</div>
    </div>

    </div>

    <script>
        // Auto-print if ?print=1 and status APPROVED
        @if(request('print') == '1' && $sj->status === 'APPROVED' && !$sj->deleted_at)
        window.addEventListener('load', () => setTimeout(() => window.print(), 600));
        @endif
    </script>
</body>
</html>
