<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pemberitahuan — {{ $student->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&family=Linux+Libertine&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #f0faf3;
        }

        /* ===== SCREEN: Preview wrapper ===== */
        .preview-bar {
            background: #1B6B3A;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            print-color-adjust: exact;
        }
        .preview-bar h2 { font-size: 14px; font-weight: 700; letter-spacing: -0.02em; }
        .preview-bar p  { font-size: 11px; opacity: 0.8; }
        .btn-group { display: flex; gap: 8px; }
        .btn-print {
            background: white;
            color: #1B6B3A;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
            transition: opacity 0.2s;
        }
        .btn-print:hover { opacity: 0.85; }
        .btn-back {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: inherit;
        }

        .page-wrapper {
            display: flex;
            justify-content: center;
            padding: 24px 16px 40px;
        }

        /* ===== SURAT ===== */
        .surat {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 20mm 20mm 18mm 25mm;
            box-shadow: 0 4px 40px rgba(0,0,0,0.12);
            position: relative;
        }

        /* Kop Surat */
        .kop {
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }
        .kop-logo-placeholder {
            width: 70px;
            height: 70px;
            border: 2px solid #1B6B3A;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #1B6B3A;
            font-weight: 700;
            text-align: center;
        }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .instansi  { font-size: 11pt; font-weight: normal; line-height: 1.4; }
        .kop-text .nama-sma  { font-size: 16pt; font-weight: bold; line-height: 1.2; }
        .kop-text .alamat    { font-size: 9pt; line-height: 1.5; }
        .kop-text .web       { font-size: 8.5pt; line-height: 1.4; }

        /* Judul */
        .judul-surat {
            text-align: center;
            margin: 14px 0 4px;
        }
        .judul-surat .judul-utama {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .judul-surat .sub-judul   { font-size: 10pt; margin-top: 2px; }
        .judul-surat .motto       { font-size: 9.5pt; font-style: italic; }

        /* Nomor surat */
        .nomor-surat { margin: 10px 0 14px; font-size: 11pt; }

        /* Kepada */
        .kepada { margin-bottom: 14px; font-size: 11pt; line-height: 1.8; }

        /* Salam & Body */
        .salam { font-size: 11pt; margin-bottom: 10px; }
        .body-text {
            font-size: 11pt;
            line-height: 1.8;
            text-align: justify;
            margin-bottom: 10px;
        }
        .body-text p { margin-bottom: 8px; }

        /* Tabel */
        .tabel-pelanggaran {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 6px;
            font-size: 10.5pt;
        }
        .tabel-pelanggaran th {
            background: #1B6B3A !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color: white !important;
            padding: 6px 8px;
            border: 1px solid #1B6B3A;
            text-align: center;
            font-weight: bold;
        }
        .tabel-pelanggaran td {
            padding: 5px 8px;
            border: 1px solid #aaa;
        }
        .tabel-pelanggaran tr:nth-child(even) td { background: #f5fbf7; }
        .tabel-pelanggaran .poin { text-align: center; font-weight: bold; }
        .tabel-pelanggaran .no   { text-align: center; }
        .tabel-pelanggaran tr.total-row td {
            background: #e8f5ec !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Total & Status */
        .total-box {
            margin: 8px 0 4px;
            font-size: 11pt;
            display: flex;
            gap: 24px;
        }
        .total-item strong { display: inline-block; min-width: 180px; }

        .status-box {
            display: inline-block;
            margin: 4px 0 10px;
            padding: 5px 14px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11pt;
            border: 2px solid;
        }
        @php
            $statusColor = match(true) {
                $netPoints > 100 => ['#dc2626', '#fee2e2'],
                $netPoints > 50  => ['#d97706', '#fef3c7'],
                $netPoints > 20  => ['#2563eb', '#dbeafe'],
                default          => ['#16a34a', '#dcfce7'],
            };
        @endphp

        /* Undangan */
        .undangan-box {
            margin: 10px 0;
            font-size: 11pt;
            line-height: 1.8;
        }
        .undangan-table { border-collapse: collapse; font-size: 11pt; }
        .undangan-table td { padding: 1px 6px; vertical-align: top; }
        .undangan-table td:first-child { white-space: nowrap; min-width: 120px; }

        /* Penutup */
        .penutup { margin-top: 10px; font-size: 11pt; line-height: 1.8; text-align: justify; }

        /* TTD */
        .ttd-section {
            margin-top: 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 11pt;
            gap: 16px;
        }
        .ttd-block {
            text-align: center;
            width: 28%;
            display: flex;
            flex-direction: column;
            padding: 0 8px;
        }
        .ttd-block .ttd-title {
            font-weight: bold;
            min-height: 44px;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            line-height: 1.6;
        }
        .ttd-block .ttd-space  { height: 60px; }
        .ttd-block .ttd-line   { border-top: 1.5px solid #000; padding-top: 5px; }
        .ttd-block .ttd-name   { font-weight: bold; font-size: 11pt; }
        .ttd-block .ttd-nip    { font-size: 9.5pt; color: #333; margin-top: 3px; }

        /* Watermark zona merah */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-35deg);
            font-size: 72pt;
            font-weight: 900;
            opacity: 0.04;
            pointer-events: none;
            white-space: nowrap;
            letter-spacing: 4px;
        }

        /* ===== PRINT ===== */
        @media print {
            body { background: white; }
            .preview-bar { display: none; }
            .page-wrapper { padding: 0; margin: 0; }
            .surat {
                box-shadow: none;
                width: 100%;
                min-height: auto;
                padding: 0 15mm 15mm 15mm;
            }
            .tabel-pelanggaran th {
                background: #1B6B3A !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color: white !important;
            }
            .tabel-pelanggaran tr.total-row td {
                background: #e8f5ec !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A4;
                margin: 15mm 10mm 15mm 10mm;
            }
        }
    </style>
</head>
<body>

    {{-- Preview Bar (hanya di layar) --}}
    <div class="preview-bar">
        <div>
            <h2>📄 Preview Surat Pemberitahuan</h2>
            <p>{{ $student->name }} — {{ $student->schoolClass->name ?? '-' }}</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('students.show', $student) }}" class="btn-back">← Kembali</a>
            <button onclick="window.print()" class="btn-print">
                🖨️ Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="surat">

            {{-- Watermark --}}
            <div class="watermark">SI PINTAR</div>

            {{-- ===== KOP SURAT ===== --}}
            <div class="kop">
                <div class="kop-logo">
                    @if(file_exists(public_path('logo.png')))
                        <img src="{{ asset('logo.png') }}" alt="Logo SMAN 1 Kopang">
                    @else
                        <div class="kop-logo-placeholder">SMAN 1 KOPANG</div>
                    @endif
                </div>
                <div class="kop-text">
                    <div class="instansi">PEMERINTAH PROVINSI NUSA TENGGARA BARAT</div>
                    <div class="nama-sma">SMA NEGERI 1 KOPANG</div>
                    <div class="alamat">
                        Jl. Segara Anak No. 5A Kopang Lombok Tengah &nbsp;☎ (0370) 6156250 &nbsp; Kode Pos 83553
                    </div>
                    <div class="web">
                        Laman: www.smansa-kopang.sch.id &nbsp;|&nbsp; Surel: sma_negeri1kopang@yahoo.co.id
                    </div>
                </div>
            </div>

            {{-- ===== JUDUL ===== --}}
            <div class="judul-surat">
                <div class="judul-utama">SURAT PEMBERITAHUAN KESEHATAN KARAKTER ({{ Str::upper(explode(' — ', $statusText)[0]) }})</div>
                <div class="sub-judul">Sistem Pembinaan Integritas Terpadu (Si Pintar)</div>
                <div class="motto"><em>"Sehat Karakternya, Pintar Orangnya"</em></div>
            </div>

            {{-- ===== NOMOR ===== --}}
            <div class="nomor-surat">
                Nomor : <strong>{{ $nomorSurat }}</strong>
            </div>

            {{-- ===== KEPADA ===== --}}
            <div class="kepada">
                Yth.<br>
                Bapak/Ibu Wali Murid dari: <strong>{{ $student->name }}</strong><br>
                Kelas: <strong>{{ $student->schoolClass->name ?? '-' }}</strong>
                &nbsp;&nbsp; | &nbsp;&nbsp; NISN: <strong>{{ $student->nisn ?? '-' }}</strong><br>
                <br>
                <em>Di Tempat</em>
            </div>

            {{-- ===== SALAM ===== --}}
            <div class="salam"><em>Assalamu'alaikum Warahmatullahi Wabarakatuh</em></div>

            {{-- ===== ISI ===== --}}
            <div class="body-text">
                <p>
                    Bapak/Ibu yang kami hormati, pendidikan adalah sebuah perjalanan panjang untuk membentuk
                    insan yang tidak hanya cerdas secara intelektual, tetapi juga sehat secara karakter. Di SMAN 1
                    Kopang, kami menjalankan program <strong>"Si Pintar"</strong> yang memandang perilaku menyimpang
                    bukan sebagai kejahatan, melainkan sebagai <em>"penyakit"</em> karakter yang memerlukan
                    <em>"resep"</em> atau penanganan yang tepat agar siswa dapat kembali pulih dan berkembang
                    dengan baik.
                </p>
                <p>
                    Melalui surat ini, kami ingin menginformasikan hasil pemantauan <strong>Rekam Medis Karakter</strong>
                    putra/putri Bapak/Ibu. Saat ini, akumulasi poin pelanggaran (gejala) yang bersangkutan telah
                    mencapai ambang batas yang memerlukan perhatian khusus <strong>({{ explode(' — ', $statusText)[0] }})</strong>.
                    Berdasarkan mekanisme program, kondisi ini memerlukan kolaborasi erat antara pihak sekolah dan
                    orang tua agar kami dapat memberikan <em>"resep"</em> pembinaan yang paling sesuai.
                </p>
            </div>

            {{-- ===== TABEL PENYAKIT ===== --}}
            <div class="body-text">
                <p><strong>Adapun rincian diagnosa perilaku/pelanggaran yang telah tercatat adalah sebagai berikut:</strong></p>
            </div>
            <table class="tabel-pelanggaran">
                <thead>
                    <tr>
                        <th style="width:35px">NO</th>
                        <th style="width:110px">Tanggal</th>
                        <th>Jenis Pelanggaran (Diagnosa)</th>
                        <th style="width:130px">Bobot Point (Gejala)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violationRecords as $i => $record)
                    <tr>
                        <td class="no">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}</td>
                        <td>{{ $record->violationType->name ?? '-' }}</td>
                        <td class="poin" style="color: #dc2626;">-{{ $record->violationType->points ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="no">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="no">2</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; font-weight: bold; padding-right: 12px;">Total Akumulasi Point</td>
                        <td class="poin" style="font-weight: bold;">{{ $totalViolation }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- ===== TABEL VITAMIN ===== --}}
            <div class="body-text" style="margin-top: 12px;">
                <p><strong>Adapun rincian vitamin/prestasi/kebaikan yang telah tercatat adalah sebagai berikut:</strong></p>
            </div>
            <table class="tabel-pelanggaran">
                <thead>
                    <tr>
                        <th style="width:35px">NO</th>
                        <th style="width:110px">Tanggal</th>
                        <th>Jenis Vitamin / Prestasi (Diagnosa)</th>
                        <th style="width:130px">Bobot Point (Gejala)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vitaminRecords as $i => $record)
                    <tr>
                        <td class="no">{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}</td>
                        <td>{{ $record->vitaminType->name ?? '-' }}</td>
                        <td class="poin" style="color: #16a34a;">+{{ $record->vitaminType->points ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="no">1</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="no">2</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right; font-weight: bold; padding-right: 12px;">Total Akumulasi Point</td>
                        <td class="poin" style="font-weight: bold;">{{ $totalVitamin }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- ===== TOTAL & STATUS ===== --}}
            <div style="margin-top: 12px; font-size: 11pt; line-height: 2;">
                <table style="font-size: 11pt; border-collapse: collapse;">
                    <tr>
                        <td style="min-width: 240px;">Total Poin Penyakit</td>
                        <td>: <strong style="color: #dc2626;">{{ $totalViolation }} poin</strong></td>
                    </tr>
                    <tr>
                        <td>Total Poin Vitamin (pengurang)</td>
                        <td>: <strong style="color: #16a34a;">{{ $totalVitamin }} poin</strong></td>
                    </tr>
                    <tr style="border-top: 1px solid #000;">
                        <td><strong>Total Akumulasi Poin Bersih</strong></td>
                        <td>: <strong>{{ $netDisplay }} poin</strong>
                            @if($netPoints < 0)
                                <span style="font-size:9.5pt; color:#16a34a;"> (vitamin melebihi penyakit)</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Saat Ini</strong></td>
                        <td>: <strong>{{ $statusText }}</strong> ({{ $statusSP }})</td>
                    </tr>
                </table>
            </div>

            {{-- ===== UNDANGAN ===== --}}
            <div class="body-text" style="margin-top: 12px;">
                <p>
                    Sehubungan dengan hal tersebut, kami mengundang Bapak/Ibu untuk hadir pada:
                </p>
            </div>
            <table class="undangan-table" style="margin-left: 20px; margin-bottom: 10px;">
                <tr>
                    <td>Hari/Tanggal</td>
                    <td>: ............................................................</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>: ............................................................</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>: <strong>Klinik (Ruang BK) SMAN 1 Kopang</strong></td>
                </tr>
                <tr>
                    <td>Agenda</td>
                    <td>: <strong>Konsultasi hasil rekam medis karakter</strong></td>
                </tr>
            </table>

            {{-- ===== PENUTUP ===== --}}
            <div class="body-text">
                <p>
                    Kami sangat berharap kehadiran Bapak/Ibu untuk memberikan dukungan moral bagi putra/putri kita.
                    Kami percaya bahwa dengan kasih sayang dan kerja sama yang baik, setiap <em>"penyakit"</em>
                    karakter dapat disembuhkan melalui pemberian <strong>"Vitamin"</strong> kebaikan yang tepat
                    sehingga siswa memiliki kesempatan kedua untuk memperbaiki citra dirinya.
                </p>
                <p>
                    Demikian undangan ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu demi masa depan
                    ananda, kami ucapkan terima kasih.
                </p>
            </div>
            <div class="salam"><em>Wassalamu'alaikum Warahmatullahi Wabarakatuh</em></div>

            {{-- ===== TTD ===== --}}
            <div style="margin-top: 14px; font-size: 11pt;">
                Kopang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </div>

            <div class="ttd-section">
                {{-- Wakasek --}}
                <div class="ttd-block">
                    <div class="ttd-title">Wakasek Kesiswaan,</div>
                    <div class="ttd-space"></div>
                    <div class="ttd-line">
                        <div class="ttd-name">(__________________________)</div>
                        <div class="ttd-nip">NIP. ................................</div>
                    </div>
                </div>

                {{-- Koordinator BK --}}
                <div class="ttd-block">
                    <div class="ttd-title">Koordinator BK,</div>
                    <div class="ttd-space"></div>
                    <div class="ttd-line">
                        <div class="ttd-name">(__________________________)</div>
                        <div class="ttd-nip">NIP. ................................</div>
                    </div>
                </div>

                {{-- Kepala Sekolah --}}
                <div class="ttd-block">
                    <div class="ttd-title">Mengetahui,<br>Kepala Sekolah,</div>
                    <div class="ttd-space"></div>
                    <div class="ttd-line">
                        <div class="ttd-name">(__________________________)</div>
                        <div class="ttd-nip">NIP. ................................</div>
                    </div>
                </div>
            </div>

        </div>{{-- .surat --}}
    </div>{{-- .page-wrapper --}}

    <script>
        // Auto-trigger print dialog jika URL mengandung ?print=1
        if (new URLSearchParams(window.location.search).get('print') === '1') {
            window.onload = () => setTimeout(() => window.print(), 500);
        }
    </script>
</body>
</html>
