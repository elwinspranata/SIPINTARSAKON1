<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pemberitahuan — {{ $student->name }}</title>
    <style>
        * { 
            margin: 0; padding: 0; box-sizing: border-box; 
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact; 
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #f0faf3;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
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
            padding: 15mm 20mm 15mm 25mm;
            box-shadow: 0 4px 40px rgba(0,0,0,0.12);
            position: relative;
        }

        /* Kop Surat */
        .kop {
            display: flex;
            align-items: center;
            gap: 14px;
            border-bottom: 4px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
            position: relative;
        }
        /* Garis tipis di bawah garis tebal kop */
        .kop::after {
            content: "";
            position: absolute;
            bottom: -6px;
            left: 0;
            right: 0;
            height: 1px;
            background: #000;
        }
        .logo-kiri { width: 80px; height: 100px; object-fit: contain; }
        .logo-kanan { width: 80px; height: 100px; object-fit: contain; }
        
        .kop-text { flex: 1; text-align: center; }
        .kop-text .provinsi { font-size: 14pt; font-weight: bold; line-height: 1.2; }
        .kop-text .sma      { font-size: 18pt; font-weight: bold; line-height: 1.2; }
        .kop-text .alamat   { font-size: 10pt; line-height: 1.4; margin-top: 4px; }
        .kop-text .kontak   { font-size: 9pt; line-height: 1.4; }

        /* Judul */
        .judul-surat {
            text-align: center;
            margin: 20px 0 10px;
        }
        .judul-surat .judul-utama {
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-surat .sub-judul   { font-size: 11pt; margin-top: 4px; font-weight: bold; }
        .judul-surat .motto       { font-size: 10pt; font-style: italic; margin-top: 2px; }

        /* Nomor surat */
        .nomor-surat { margin: 15px 0 20px; font-size: 12pt; }

        /* Kepada */
        .kepada { margin-bottom: 20px; font-size: 12pt; line-height: 1.5; }

        /* Salam & Body */
        .salam { font-size: 12pt; margin-bottom: 12px; font-weight: bold; }
        .body-text {
            font-size: 12pt;
            line-height: 1.6;
            text-align: justify;
            margin-bottom: 15px;
        }
        .body-text p { margin-bottom: 10px; }

        /* Tabel */
        .tabel-pelanggaran {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 11pt;
        }
        .tabel-pelanggaran th {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            font-weight: bold;
        }
        .tabel-pelanggaran td {
            padding: 8px;
            border: 1px solid #000;
        }
        .tabel-pelanggaran .no { text-align: center; }
        .tabel-pelanggaran .poin { text-align: center; }

        /* Total */
        .total-row { font-weight: bold; }

        /* Status */
        .status-box {
            margin: 15px 0;
            font-size: 12pt;
            font-weight: bold;
        }

        /* Undangan */
        .undangan-table { margin: 15px 0 15px 20px; border-collapse: collapse; font-size: 12pt; }
        .undangan-table td { padding: 4px 8px; vertical-align: top; }
        .undangan-table td:first-child { width: 150px; }

        /* TTD */
        .ttd-container {
            margin-top: 30px;
            width: 100%;
        }
        .ttd-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .ttd-block {
            width: 45%;
            text-align: center;
        }
        .ttd-space { height: 70px; }
        .ttd-name { font-weight: bold; text-decoration: underline; }

        /* ===== PRINT ===== */
        @media print {
            body { background: white; }
            .preview-bar { display: none; }
            .page-wrapper { padding: 0; margin: 0; }
            .surat {
                box-shadow: none;
                width: 100%;
                min-height: auto;
                padding: 0;
            }
            @page {
                size: A4;
                margin: 20mm 15mm 20mm 15mm;
            }
        }
    </style>
</head>
<body>

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

            {{-- ===== KOP SURAT ===== --}}
            <div class="kop">
                <img src="{{ asset('logo_provinsi.png') }}" class="logo-kiri" alt="Logo NTB">
                <div class="kop-text">
                    <div class="provinsi">PEMERINTAH PROVINSI NUSA TENGGARA BARAT</div>
                    <div class="sma">SMA NEGERI 1 KOPANG</div>
                    <div class="alamat">Jl. Segara Anak No. 5A Kopang Lombok Tengah (0370) 6156250 Kode Pos 83553</div>
                    <div class="kontak">Laman: www.smansa-kopang.sch.id Surel: sma_negeri1kopang@yahoo.co.id</div>
                </div>
                <img src="{{ asset('logo_sekolah.jpg') }}" class="logo-kanan" alt="Logo Sekolah">
            </div>

            {{-- ===== JUDUL ===== --}}
            <div class="judul-surat">
                <div class="judul-utama">SURAT PEMBERITAHUAN KESEHATAN KARAKTER ({{ Str::upper(explode(' — ', $statusText)[0]) }})</div>
                <div class="sub-judul">Sistem Pembinaan Integritas Terpadu (Si Pintar)</div>
                <div class="motto">"Sehat Karakternya , Pintar Orangnya "</div>
            </div>

            {{-- ===== NOMOR ===== --}}
            <div class="nomor-surat">
                Nomor : ..... / ..... / 202...
            </div>

            {{-- ===== KEPADA ===== --}}
            <div class="kepada">
                Yth . Bapak/Ibu Wali Murid dari : <strong>{{ $student->name }}</strong><br>
                Di Tempat
            </div>

            {{-- ===== SALAM ===== --}}
            <div class="salam">Assalamu’alaikum Warahmatullahi Wabarakatuh</div>

            {{-- ===== ISI ===== --}}
            <div class="body-text">
                <p>
                    Bapak/Ibu yang kami hormati, pendidikan adalah sebuah perjalanan panjang untuk membentuk insan yang tidak hanya cerdas secara intelektual, tetapi juga sehat secara karakter. Di SMAN 1 Kopang, kami menjalankan program "Si Pintar" yang memandang perilaku menyimpang bukan sebagai kejahatan, melainkan sebagai "penyakit" karakter yang memerlukan "resep" atau penanganan yang tepat agar siswa dapat kembali pulih dan berkembang dengan baik.
                </p>
                <p>
                    Melalui surat ini, kami ingin menginformasikan hasil pemantauan Rekam Medis Karakter putra/putri Bapak/Ibu. Saat ini, akumulasi poin pelanggaran (gejala) yang bersangkutan telah mencapai ambang batas yang memerlukan perhatian khusus (Zona Merah). Berdasarkan mekanisme program, kondisi ini memerlukan kolaborasi erat antara pihak sekolah dan orang tua agar kami dapat memberikan "resep" pembinaan yang paling sesuai.
                </p>
                <p>
                    Adapun rincian diagnosa perilaku/pelanggaran yang telah tercatat adalah sebagai berikut:
                </p>
            </div>

            {{-- ===== TABEL PELANGGARAN ===== --}}
            <table class="tabel-pelanggaran" style="border-collapse: collapse; width: 100%; border: 1px solid #000;">
                <thead style="background-color: #1B6B3A; color: white;">
                    <tr>
                        <th style="width: 40px; padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">NO</th>
                        <th style="width: 120px; padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">Tanggal</th>
                        <th style="padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">Jenis Pelanggaran (Diagnosa)</th>
                        <th style="width: 150px; padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">Bobot Point (Gejala)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @forelse($violationRecords as $record)
                    <tr>
                        <td class="no" style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $i++ }}</td>
                        <td style="padding: 8px; border: 1px solid #000;">{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }}</td>
                        <td style="padding: 8px; border: 1px solid #000;">{{ $record->violationType->name ?? '-' }}</td>
                        <td class="poin" style="padding: 8px; border: 1px solid #000; text-align: center; color: #ef4444; font-weight: bold;">-{{ $record->violationType->points ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="no" style="padding: 8px; border: 1px solid #000; text-align: center;">1</td>
                        <td style="padding: 8px; border: 1px solid #000;"></td>
                        <td style="padding: 8px; border: 1px solid #000;">Belum ada catatan pelanggaran</td>
                        <td style="padding: 8px; border: 1px solid #000;"></td>
                    </tr>
                    @endforelse
                    <tr class="total-row" style="background-color: #e8f5e9;">
                        <td colspan="3" style="text-align: right; padding-right: 15px; border: 1px solid #000; font-weight: bold; background-color: #e8f5e9 !important;">Total Akumulasi Point</td>
                        <td class="poin" style="padding: 8px; border: 1px solid #000; text-align: center; font-weight: bold; color: #000; background-color: #e8f5e9 !important;">{{ $totalViolation }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="body-text" style="margin-top: 15px;">
                <p style="font-weight: bold; margin-bottom: 10px;">Adapun rincian vitamin/prestasi/kebaikan yang telah tercatat adalah sebagai berikut :</p>
            </div>

            {{-- ===== TABEL VITAMIN ===== --}}
            <table class="tabel-pelanggaran" style="border-collapse: collapse; width: 100%; border: 1px solid #000;">
                <thead style="background-color: #1B6B3A; color: white;">
                    <tr>
                        <th style="width: 40px; padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">NO</th>
                        <th style="width: 120px; padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">Tanggal</th>
                        <th style="padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">Jenis Vitamin / Prestasi (Diagnosa)</th>
                        <th style="width: 150px; padding: 8px; border: 1px solid #000; background-color: #1B6B3A; color: white !important;">Bobot Point (Gejala)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vitaminRecords as $record)
                    <tr>
                        <td class="no" style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $i++ }}</td>
                        <td style="padding: 8px; border: 1px solid #000;">{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d F Y') }}</td>
                        <td style="padding: 8px; border: 1px solid #000;">{{ $record->vitaminType->name ?? '-' }}</td>
                        <td class="poin" style="padding: 8px; border: 1px solid #000; text-align: center; color: #10b981; font-weight: bold;">+{{ $record->vitaminType->points ?? 0 }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td class="no" style="padding: 8px; border: 1px solid #000; text-align: center;">{{ $i }}</td>
                        <td style="padding: 8px; border: 1px solid #000;"></td>
                        <td style="padding: 8px; border: 1px solid #000;">Belum ada catatan vitamin/prestasi</td>
                        <td style="padding: 8px; border: 1px solid #000;"></td>
                    </tr>
                    @endforelse
                    <tr class="total-row" style="background-color: #e8f5e9;">
                        <td colspan="3" style="text-align: right; padding-right: 15px; border: 1px solid #000; font-weight: bold; background-color: #e8f5e9 !important;">Total Akumulasi Point</td>
                        <td class="poin" style="padding: 8px; border: 1px solid #000; text-align: center; font-weight: bold; color: #000; background-color: #e8f5e9 !important;">{{ $totalVitamin }}</td>
                    </tr>
                </tbody>
            </table>

            <hr style="border: none; border-top: 15px solid #f1f5f9; margin: 30px 0;">

            {{-- ===== TOTAL AKUMULASI PENGURANGAN ===== --}}
            <table style="width: 100%; font-size: 12pt; border-collapse: collapse; margin-bottom: 25px;">
                <tr>
                    <td style="width: 250px; padding: 5px 0;">Total Poin Penyakit</td>
                    <td style="padding: 5px 0;">: <strong style="color: #ef4444;">-{{ $totalViolation }} poin</strong></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;">Total Poin Vitamin</td>
                    <td style="padding: 5px 0;">: <strong style="color: #10b981;">+{{ $totalVitamin }} poin</strong></td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 0;"><hr style="border: none; border-top: 2px solid #000; margin: 5px 0; width: 68%;"></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;"><strong>Total Akumulasi Poin Bersih</strong></td>
                    <td style="padding: 5px 0;">: <strong>{{ abs($netPoints) }} poin</strong></td>
                </tr>
                <tr>
                    <td style="padding: 5px 0;"><strong>Status Saat Ini</strong></td>
                    <td style="padding: 5px 0;">: <strong>{{ $statusText }}</strong> ({{ $statusSP }})</td>
                </tr>
            </table>

            {{-- ===== GRAFIK TREN PERILAKU ===== --}}
            <div style="margin-top: 24px; margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px;">
                    <div>
                        <div style="font-size: 11pt; font-weight: bold; display: flex; align-items: center; gap: 6px;">
                            <span>📈 Tren Perilaku Siswa</span>
                        </div>
                        <div style="font-size: 9pt; color: #64748b; margin-top: 2px;">Perbandingan vitamin vs penyakit (6 bulan terakhir)</div>
                    </div>
                    <div style="display: flex; gap: 12px; font-size: 9pt; font-weight: bold; background: #f8fafc; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0; align-items: center;">
                        <span style="display: flex; align-items: center; gap: 6px; color: #334155;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; display: inline-block;"></span> Vitamin</span>
                        <span style="display: flex; align-items: center; gap: 6px; color: #334155; margin-left: 8px;"><span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span> Penyakit</span>
                    </div>
                </div>
                <div style="height: 180px; width: 100%; position: relative;">
                    <canvas id="behaviorChart"></canvas>
                </div>
            </div>

            {{-- ===== UNDANGAN ===== --}}
            <div class="body-text">
                <p>Sehubungan dengan hal tersebut , kami mengundang Bapak/Ibu untuk hadir pada:</p>
            </div>
            <table class="undangan-table">
                <tr>
                    <td>Hari/ Tanggal</td>
                    <td>: ............................................................</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>: ............................................................</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>: Klinik (Ruang BK) SMAN 1 Kopang</td>
                </tr>
                <tr>
                    <td>Agenda</td>
                    <td>: Konsultasi hasil rekam medis karakter dan penyusunan langkah terapi pemulihan siswa .</td>
                </tr>
            </table>

            {{-- ===== PENUTUP ===== --}}
            <div class="body-text">
                <p>
                    Kami sangat berharap kehadiran Bapak/Ibu untuk memberikan dukungan moral bagi putra / putri kita . Kami percaya bahwa dengan kasih sayang dan kerja sama yang baik , setiap " penyakit " karakter dapat disembuhkan melalui pemberian "Vitamin" kebaikan yang tepat sehingga siswa memiliki kesempatan kedua untuk memperbaiki citra dirinya .
                </p>
                <p>
                    Demikian undangan ini kami sampaikan . Atas perhatian dan kerja sama Bapak/Ibu demi masa depan ananda , kami ucapkan terima kasih.
                </p>
            </div>

            <div style="text-align: right; margin-top: 20px; font-size: 12pt;">
                Kopang , ........... 202 …
            </div>

            {{-- ===== TTD ===== --}}
            <div class="ttd-container">
                <div class="ttd-row">
                    <div class="ttd-block">
                        <p>Wakasek Kesiswaan,</p>
                        <div class="ttd-space"></div>
                        <p class="ttd-name">(___________________________)</p>
                    </div>
                    <div class="ttd-block">
                        <p>Koordinator BK,</p>
                        <div class="ttd-space"></div>
                        <p class="ttd-name">(___________________________)</p>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <p>Mengetahui ,</p>
                    <p>Kepala SMAN 1 KOPANG,</p>
                    <div class="ttd-space"></div>
                    <p class="ttd-name">(___________________________)</p>
                </div>
            </div>

        </div>{{-- .surat --}}
    </div>{{-- .page-wrapper --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('behaviorChart').getContext('2d');
            Chart.defaults.animation = false;

            const labels = {!! json_encode($chartLabels) !!};
            const vitaminData = {!! json_encode($chartVitaminData) !!};
            const penyakitData = {!! json_encode($chartViolationData) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Vitamin',
                        data: vitaminData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2,
                    }, {
                        label: 'Penyakit',
                        data: penyakitData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointRadius: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'top' } },
                    scales: {
                        y: { beginAtZero: true },
                        x: { grid: { display: false } }
                    }
                }
            });

            if (new URLSearchParams(window.location.search).get('print') === '1') {
                setTimeout(() => window.print(), 800);
            }
        });
    </script>
</body>
</html>
