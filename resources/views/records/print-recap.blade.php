<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapitulasi - SIPINTAR SAKON</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: white;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .print-container {
            padding: 15mm 20mm;
            max-width: 210mm;
            margin: 0 auto;
        }

        .kop {
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 4px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }

        .kop::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            border-bottom: 1px solid #000;
        }

        .kop img {
            width: 80px;
            height: 100px;
            object-fit: contain;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text .gov {
            font-size: 14pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .kop-text .school {
            font-size: 18pt;
            font-weight: bold;
            line-height: 1.2;
        }

        .kop-text .address {
            font-size: 10pt;
            line-height: 1.4;
            margin-top: 4px;
        }

        .kop-text .contact {
            font-size: 9pt;
            line-height: 1.4;
        }

        .title-container {
            text-align: center;
            margin: 20px 0;
        }

        .main-title {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .sub-title {
            font-size: 10pt;
            margin-top: 8px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 10pt;
        }
        
        td.text-left {
            text-align: left;
        }

        th {
            background: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            padding: 0 40px;
        }

        .signature {
            text-align: center;
        }

        .signature p {
            margin: 0 0 5px 0;
        }

        .signature .space {
            height: 80px;
        }

        .signature .name {
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body { margin: 0; padding: 0; }
            .print-container { padding: 0; max-width: none; }
            @page { size: A4; margin: 15mm 20mm; }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Letterhead -->
        <div class="kop">
            <img src="{{ asset('logo_provinsi.png') }}" alt="Logo NTB">
            <div class="kop-text">
                <div class="gov">PEMERINTAH PROVINSI NUSA TENGGARA BARAT</div>
                <div class="school">SMA NEGERI 1 KOPANG</div>
                <div class="address">Jl. Segara Anak No. 5A Kopang Lombok Tengah (0370) 6156250 Kode Pos 83553</div>
                <div class="contact">Laman: www.smansa-kopang.sch.id Surel: sma_negeri1kopang@yahoo.co.id</div>
            </div>
            <img src="{{ asset('logo_sekolah.jpg') }}" alt="Logo Sekolah">
        </div>

        <!-- Title -->
        <div class="title-container">
            <div class="main-title">LAPORAN REKAPITULASI PEMBINAAN KARAKTER SISWA</div>
            @if($className)
                <div class="sub-title">Kelas: {{ $className }}</div>
            @endif
            @if($startDate || $endDate)
                <div class="sub-title">Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Awal' }} s/d {{ $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Sekarang' }}</div>
            @endif
        </div>

        <!-- Table -->
        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Penyakit</th>
                    <th>Vitamin</th>
                    <th>Skor Akhir</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($students as $student)
                    @php
                        $status = $student->point_status;
                        
                        $diseasePoints = $student->behaviorRecords->where('violation_type_id', '!=', null)->sum(function($record) {
                            return $record->violationType->points;
                        });
                        
                        $vitaminPoints = $student->behaviorRecords->where('vitamin_type_id', '!=', null)->sum(function($record) {
                            return $record->vitaminType->points;
                        });
                    @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td class="text-left">
                            <div style="font-weight: bold;">{{ $student->name }}</div>
                            <div style="font-size: 8pt; color: #555;">NISN: {{ $student->nisn ?? '-' }}</div>
                        </td>
                        <td>{{ $student->schoolClass->name ?? '-' }}</td>
                        <td style="color: red; font-weight: bold;">{{ $diseasePoints > 0 ? '-' : '' }}{{ $diseasePoints }}</td>
                        <td style="color: green; font-weight: bold;">{{ $vitaminPoints > 0 ? '+' : '' }}{{ $vitaminPoints }}</td>
                        <td style="font-weight: bold;">
                            {{ $student->net_points > 0 ? '+' : '' }}{{ $student->net_points }}
                        </td>
                        <td style="font-weight: bold;">{{ $status['label'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">Tidak ada data siswa ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer / Signature -->
        <div class="footer">
            <div class="signature">
                <p>Kopang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin-top: 5px;">Mengetahui,</p>
                <p>Kepala SMAN 1 KOPANG</p>
                <div class="space"></div>
                <p class="name">(___________________________)</p>
                <p>NIP. ...........................</p>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
