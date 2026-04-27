<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Batch Surat Jalan</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 9.5pt; color: #000; background: #fff; }
        @page { size: A4 portrait; margin: 10mm 12mm; }

        /* ── Page wrapper ── */
        .page { width: 100%; }
        .page-break { page-break-after: always; }

        /* ── Company header ── */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 5mm; }
        .logo-cell { width: 70px; vertical-align: middle; padding-right: 8px; }
        .logo-cell img { width: 65px; }
        .company-cell { vertical-align: top; }
        .company-name { font-size: 11.5pt; font-weight: bold; margin-bottom: 1px; }
        .company-sub  { font-size: 8pt; font-style: italic; font-weight: bold; margin-bottom: 2px; }
        .company-addr { font-size: 7pt; line-height: 1.4; }

        /* ── Document title ── */
        .doc-title { text-align: center; font-size: 13pt; font-weight: bold;
                     letter-spacing: 1px; margin-bottom: 4mm; }

        /* ── Info section ── */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 4mm; font-size: 9pt; font-weight: bold; }
        .info-label { width: 95px; vertical-align: top; }
        .info-colon { width: 10px; vertical-align: top; }
        .info-value { vertical-align: top; }
        .info-no    { text-align: right; white-space: nowrap; font-weight: bold; vertical-align: top; }

        /* ── Items table ── */
        .items-table { width: 100%; border-collapse: collapse; border-top: 3px solid #000;
                       font-size: 7.7pt; margin-bottom: 0; }
        .items-table thead tr { border-bottom: 2px solid #000; }
        .items-table th { padding: 4px 3px; background-color: #eff1f0; text-align: center;
                          font-weight: bold; }
        .items-table td { padding: 3px; vertical-align: top; }

        /* ── Footer info ── */
        .footer-table { width: 100%; border-collapse: collapse; margin-top: 5mm;
                        margin-bottom: 2mm; font-size: 9pt; font-weight: bold; }
        .footer-label { width: 100px; text-transform: uppercase; vertical-align: top; }
        .footer-colon { width: 10px; vertical-align: top; }
        .footer-value { vertical-align: top; }

        /* ── Certify & signatures ── */
        .certify { font-size: 8.5pt; font-style: italic; margin-top: 3mm; margin-bottom: 3mm; }
        .issued-table { width: 100%; border-collapse: collapse; margin-bottom: 2mm; font-size: 9pt; }
        .sig-table { width: 100%; border-collapse: collapse; margin-top: 6mm; font-size: 8.5pt; text-align: center; }
        .sig-cell { width: 20%; vertical-align: bottom; padding: 0 4px; text-align: center; }
        .sig-line { height: 50px; vertical-align: bottom; }
        .sig-name { font-weight: bold; text-decoration: underline; }
        .doc-ref { font-size: 7pt; color: #888; margin-top: 3mm; }
    </style>
</head>
<body>
@foreach($suratJalans as $i => $sj)
<div class="page {{ $i < count($suratJalans) - 1 ? 'page-break' : '' }}">

    {{-- ── HEADER ── --}}
    <table class="header-table">
        <tr>
            @if(!empty($logoSrc))
            <td class="logo-cell"><img src="{{ $logoSrc }}" alt="BAUER"></td>
            @endif
            <td class="company-cell">
                <div class="company-name">PT. BAUER Pratama Indonesia</div>
                <div class="company-sub">International Foundation Specialist</div>
                <div class="company-addr">
                    Alamanda Tower 19th Floor Jalan TB.Simatupang Kav.23-24 Cilandak Barat Jakarta Selatan 12430 &nbsp; Indonesia<br>
                    Telp &nbsp; : +62 21 2966 1988 (Hunting) &nbsp; Fax. : +62 21 2966 0188<br>
                    Workshop : Kp. Cipicung Rt. 18 / Rw. 04 Desa Mekarsari Kec. Cileungsi Kab. Bogor<br>
                    Telp &nbsp; : +62 21-2923 2795
                </div>
            </td>
        </tr>
    </table>

    {{-- ── TITLE ── --}}
    <div class="doc-title">DELIVERY / TRANSFER NOTE</div>

    {{-- ── INFO SECTION ── --}}
    <table class="info-table">
        <tr>
            <td>
                <table style="border-collapse:collapse; width:100%">
                    <tr>
                        <td class="info-label">DATE</td>
                        <td class="info-colon">:</td>
                        <td class="info-value">{{ $sj->tanggal?->translatedFormat('l, d F Y') ?? '-' }}</td>
                        <td class="info-no">No. &nbsp;&nbsp; {{ $sj->no_surat_jalan }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">DELIVERY TO</td>
                        <td class="info-colon">:</td>
                        <td class="info-value" colspan="2">{{ $sj->tujuan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">ATTN</td>
                        <td class="info-colon">:</td>
                        <td class="info-value" colspan="2">{{ $sj->attn ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">PHONE</td>
                        <td class="info-colon">:</td>
                        <td class="info-value" colspan="2">{{ $sj->phone_header ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ── ITEMS TABLE ── --}}
    <table class="items-table">
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
                        <td style="text-align:center">{{ $rowNum++ }}</td>
                        <td></td>
                        <td colspan="4" style="padding-left:6px;font-weight:bold">{{ $detail->group_title_text }}</td>
                    </tr>
                @else
                    @php $numStr = $insideGroup ? '-' : $rowNum++; @endphp
                    <tr>
                        <td style="text-align:center">{{ $numStr }}</td>
                        <td style="text-align:center">{{ $detail->barang?->sku ?? '-' }}</td>
                        <td style="padding-left:{{ $insideGroup ? '16px' : '6px' }}">{{ $detail->barang?->nama_barang ?? '-' }}</td>
                        <td style="text-align:center;font-weight:bold">{{ $detail->qty }}</td>
                        <td style="text-align:center">{{ $detail->barang?->satuan ?? '-' }}</td>
                        <td style="text-align:center">{{ $detail->remark ?? '' }}</td>
                    </tr>
                @endif
            @endforeach
            @if($sj->details->isEmpty())
                <tr><td colspan="6" style="text-align:center;padding:6px;color:#888">Tidak ada item.</td></tr>
            @endif
        </tbody>
    </table>

    {{-- ── FOOTER INFO ── --}}
    <table class="footer-table">
        <tr>
            <td class="footer-label">NOTE</td>
            <td class="footer-colon">:</td>
            <td class="footer-value" style="text-transform:capitalize">{{ $sj->note ?? '-' }}</td>
        </tr>
        <tr>
            <td class="footer-label">TAKEN BY</td>
            <td class="footer-colon">:</td>
            <td class="footer-value" style="text-transform:capitalize">{{ $sj->taken_by ?? '-' }}</td>
        </tr>
        <tr>
            <td class="footer-label">VEHICLE NO.</td>
            <td class="footer-colon">:</td>
            <td class="footer-value" style="text-transform:capitalize">{{ $sj->vehicle_no ?? '-' }}</td>
        </tr>
        <tr>
            <td class="footer-label">PHONE</td>
            <td class="footer-colon">:</td>
            <td class="footer-value">{{ $sj->phone_footer ?? '-' }}</td>
        </tr>
        <tr>
            <td class="footer-label">E.T.A</td>
            <td class="footer-colon">:</td>
            <td class="footer-value">{{ $sj->eta ? $sj->eta->translatedFormat('d F Y') : '-' }}</td>
        </tr>
    </table>

    {{-- ── CERTIFY ── --}}
    <div class="certify">I certify that I have examined and received the above in good condition</div>

    {{-- ── ISSUED ── --}}
    <table class="issued-table">
        <tr>
            <td style="vertical-align:top">Signature</td>
            <td style="text-align:center;font-weight:bold;padding-right:35px">
                Issued By :<br>PT. Bauer Pratama Indonesia
            </td>
        </tr>
    </table>

    {{-- ── SIGNATURES ── --}}
    <table class="sig-table">
        <tr>
            <td class="sig-cell"><div class="sig-line">................</div><div>Driver</div></td>
            <td class="sig-cell"><div class="sig-line">................</div><div>Receiver</div></td>
            <td class="sig-cell">
                <div class="sig-line">
                    @if($sj->foreman)<span class="sig-name">{{ $sj->foreman }}</span>@else ................ @endif
                </div>
                <div>Foreman</div>
            </td>
            <td class="sig-cell" style="width:12%">
                <div class="sig-line">
                    @if($sj->woc)<span class="sig-name">{{ $sj->woc }}</span>@else ........ @endif
                </div>
                <div>WOC</div>
            </td>
            <td class="sig-cell"><div class="sig-line">..........................</div><div>Store Keeper</div></td>
        </tr>
    </table>

    <div class="doc-ref">BPI-QR-WS-009 Rev.00</div>
</div>
@endforeach
</body>
</html>
