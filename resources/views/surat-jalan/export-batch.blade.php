<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Export Batch Surat Jalan</title>
    <style>
        body { 
            font-family: Helvetica, Arial, sans-serif; 
            font-size: 10pt; 
            color: #000; 
            background: #fff; 
            margin: 0; 
            padding: 0; 
        }
        @page { size: A4 portrait; margin: 8mm 10mm; }

        .page { width: 100%; position: relative; }
        .page-break { page-break-after: always; }

        /* ── HEADER ── */
        .company-header { width: 100%; margin-bottom: 6mm; }
        .bauer-logo-img { width: 65px; float: left; margin-right: 15px; }
        .company-info { float: left; }
        .company-name { font-size: 12pt; font-weight: bold; margin-bottom: 1px; }
        .company-sub  { font-size: 8pt; font-style: italic; font-weight: bold; margin-bottom: 2px; }
        .company-addr { font-size: 7pt; line-height: 1.35; }
        .clear { clear: both; }

        /* ── TITLE ── */
        .doc-title { text-align: center; font-size: 13pt; font-weight: bold; letter-spacing: 1px; margin-bottom: 4mm; margin-top: 10px; }

        /* ── INFO SECTION ── */
        .info-table { width: 100%; font-size: 9pt; font-weight: bold; margin-bottom: 4mm; border-collapse: collapse; }
        .info-table td { vertical-align: top; padding-bottom: 2px; }
        
        /* ── ITEMS TABLE ── */
        .table-custom { width: 100%; border-collapse: collapse; border-top: 3px solid #000; font-size: 7.7pt; }
        .table-custom thead tr { border-bottom: 2px solid #000; }
        .table-custom th { padding: 4px 3px; background-color: #eff1f0; text-align: center; font-weight: bold; }
        .table-custom td { padding: 3px; vertical-align: top; }
        
        /* ── FOOTER ── */
        .footer-table { width: 100%; margin-top: 6mm; margin-bottom: 2mm; font-size: 9pt; font-weight: bold; text-transform: uppercase; border-collapse: collapse; }
        .footer-table td { vertical-align: top; padding-bottom: 3px; }

        .certify-line { font-size: 8.5pt; font-style: italic; margin-top: 3mm; margin-bottom: 3mm; }
        
        /* ── ISSUED ── */
        .issued-table { width: 100%; margin-bottom: 2mm; font-size: 9pt; border-collapse: collapse; }
        .issued-table td { vertical-align: top; }
        .issued-right { text-align: center; font-weight: bold; padding-right: 35px; }

        /* ── SIGNATURES ── */
        .sig-table { width: 100%; text-align: center; font-size: 8.5pt; margin-top: 4mm; table-layout: fixed; }
        .sig-table td { vertical-align: bottom; padding: 0 5px; }
        .sig-space { height: 40px; }
        .sig-dashed { color: #000; }
        .sig-named { font-weight: bold; text-decoration: underline; }
        .sig-label { padding-top: 3px; }
        
        .doc-ref { font-size: 7pt; color: #888; margin-top: 3mm; }
        
        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }
        .text-capitalize { text-transform: capitalize; }
    </style>
</head>
<body>
@foreach($suratJalans as $i => $sj)
<div class="page {{ $i < count($suratJalans) - 1 ? 'page-break' : '' }}">

    {{-- HEADER --}}
    <div class="company-header">
        @if(!empty($logoSrc))
        <img src="{{ $logoSrc }}" alt="BAUER Logo" class="bauer-logo-img">
        @endif
        <div class="company-info">
            <div class="company-name">PT. BAUER Pratama Indonesia</div>
            <div class="company-sub">International Foundation Specialist</div>
            <div class="company-addr">
                Alamanda Tower 19th Floor Jalan TB.Simatupang Kav.23-24 Cilandak Barat Jakarta Selatan 12430 &nbsp; Indonesia<br>
                Telp &nbsp; : +62 21 2966 1988 (Hunting) Fax. : +62 21 2966 0188<br>
                Workshop : Kp. Cipicung Rt. 18 / Rw. 04 Desa Mekarsari Kec. Cileungsi Kab. Bogor<br>
                Telp &nbsp; : +62 21-2923 2795
            </div>
        </div>
        <div class="clear"></div>
    </div>

    {{-- TITLE --}}
    <div class="doc-title">DELIVERY / TRANSFER NOTE</div>

    {{-- INFO SECTION --}}
    <table class="info-table">
        <tr>
            <td width="95">DATE</td>
            <td width="15">:</td>
            <td>{{ $sj->tanggal?->translatedFormat('l, d F Y') ?? '-' }}</td>
            <td align="right">No. &nbsp;&nbsp; {{ $sj->no_surat_jalan }}</td>
        </tr>
        <tr>
            <td>DELIVERY TO</td>
            <td>:</td>
            <td colspan="2">{{ $sj->tujuan ?? '-' }}</td>
        </tr>
        <tr>
            <td>ATTN</td>
            <td>:</td>
            <td colspan="2">{{ $sj->attn ?? '-' }}</td>
        </tr>
        <tr>
            <td>PHONE</td>
            <td>:</td>
            <td colspan="2">{{ $sj->phone_header ?? '-' }}</td>
        </tr>
    </table>

    {{-- ITEMS TABLE --}}
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
            @php $rowNum = 1; $insideGroup = false; $printedRows = 0; @endphp
            @foreach($sj->details as $detail)
                @if($detail->type === 'group_title')
                    @php $insideGroup = true; $printedRows++; @endphp
                    <tr>
                        <td class="text-center">{{ $rowNum++ }}</td>
                        <td></td>
                        <td colspan="4" class="fw-bold" style="padding-left:6px;">{{ $detail->group_title_text }}</td>
                    </tr>
                @elseif($detail->type === 'end_group')
                    @php $insideGroup = false; @endphp
                @elseif($detail->type === 'manual_item')
                    @php $numStr = $insideGroup ? '' : $rowNum++; $printedRows++; @endphp
                    <tr>
                        <td class="text-center">{{ $numStr }}</td>
                        <td class="text-center">{{ $detail->manual_asset_id ?? '-' }}</td>
                        <td style="padding-left:{{ $insideGroup ? '16px' : '6px' }}">{{ $detail->manual_nama_barang }}</td>
                        <td class="text-center fw-bold">{{ $detail->qty }}</td>
                        <td class="text-center">{{ $detail->manual_satuan ?? '-' }}</td>
                        <td class="text-center">{{ $detail->remark ?? '' }}</td>
                    </tr>
                @else
                    @php $numStr = $insideGroup ? '' : $rowNum++; $printedRows++; @endphp
                    <tr>
                        <td class="text-center">{{ $numStr }}</td>
                        <td class="text-center">{{ $detail->barang?->sku ?? '-' }}</td>
                        <td style="padding-left:{{ $insideGroup ? '16px' : '6px' }}">{{ $detail->barang?->nama_barang ?? '-' }}</td>
                        <td class="text-center fw-bold">{{ $detail->qty }}</td>
                        <td class="text-center">{{ $detail->barang?->satuan ?? '-' }}</td>
                        <td class="text-center">{{ $detail->remark ?? '' }}</td>
                    </tr>
                @endif
            @endforeach
            @if($sj->details->isEmpty())
                @php $printedRows++; @endphp
                <tr><td colspan="6" class="text-center text-muted" style="padding:6px;">Tidak ada item.</td></tr>
            @endif
            
            {{-- DOMPDF Footer Pusher Hack --}}
            @php
                $minRows = 18;
                if ($printedRows < $minRows) {
                    $padHeight = ($minRows - $printedRows) * 22;
                    echo '<tr><td colspan="6" style="border: none; height: ' . $padHeight . 'px;"></td></tr>';
                }
            @endphp
        </tbody>
    </table>

    <div class="footer-wrapper" style="page-break-inside: avoid; break-inside: avoid; width: 100%;">
        {{-- FOOTER INFO --}}
        <table class="footer-table">
            <tr>
                <td width="100">NOTE</td>
                <td width="15">:</td>
                <td class="text-capitalize fw-bold">{{ $sj->note ?? '-' }}</td>
            </tr>
            <tr>
                <td>TAKEN BY</td>
                <td>:</td>
                <td class="text-capitalize">{{ $sj->taken_by ?? '-' }}</td>
            </tr>
            <tr>
                <td>VEHICLE NO.</td>
                <td>:</td>
                <td class="text-capitalize">{{ $sj->vehicle_no ?? '-' }}</td>
            </tr>
            <tr>
                <td>PHONE</td>
                <td>:</td>
                <td>{{ $sj->phone_footer ?? '-' }}</td>
            </tr>
            <tr>
                <td>E.T.A</td>
                <td>:</td>
                <td>{{ $sj->eta ? $sj->eta->translatedFormat('d F Y') : '-' }}</td>
            </tr>
        </table>

        {{-- CERTIFY --}}
        <div class="certify-line">I certify that I have examined and received the above in good condition</div>

        {{-- ISSUED --}}
        <table class="issued-table">
            <tr>
                <td>Signature</td>
                <td class="issued-right">Issued By :<br>PT. Bauer Pratama Indonesia</td>
            </tr>
        </table>

        {{-- SIGNATURES --}}
        <table class="sig-table">
            <tr>
                <td style="width: 20%;"><div class="sig-space"></div><div class="sig-dashed">................</div><div class="sig-label">Driver</div></td>
                <td style="width: 20%;"><div class="sig-space"></div><div class="sig-dashed">................</div><div class="sig-label">Receiver</div></td>
                <td style="width: 20%;"><div class="sig-space"></div><div>@if($sj->foreman)<span class="sig-named">{{ $sj->foreman }}</span>@else<span class="sig-dashed">................</span>@endif</div><div class="sig-label">Foreman</div></td>
                <td style="width: 15%;"><div class="sig-space"></div><div>@if($sj->woc)<span class="sig-named">{{ $sj->woc }}</span>@else<span class="sig-dashed">...........</span>@endif</div><div class="sig-label">WOC</div></td>
                <td style="width: 25%;"><div class="sig-space"></div><div class="sig-dashed">..........................</div><div class="sig-label">Store Keeper</div></td>
            </tr>
        </table>

        <div class="doc-ref">BPI-QR-WS-009 Rev.00</div>
    </div>
</div>
@endforeach
</body>
</html>
