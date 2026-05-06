<x-app-layout>
    @section('header_title', 'Rekapitulasi Kolektif')
    @section('header_subtitle', 'Rekap data pelanggaran dan kebaikan seluruh siswa secara kolektif.')

    @push('styles')
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes fadeInDown {
                from {
                    opacity: 0;
                    transform: translateY(-15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .premium-row {
                transition: all 0.2s;
            }

            .premium-row:hover {
                background: #f8fafc;
                box-shadow: inset 4px 0 0 var(--primary);
            }

            .print-only {
                display: none !important;
            }

            @media print {

                .sidebar,
                .top-nav,
                .btn,
                .no-print,
                .main-header,
                .user-profile-mini,
                .mobile-menu-toggle {
                    display: none !important;
                }

                .main-content {
                    margin-left: 0 !important;
                    padding: 0 !important;
                }

                .content-area {
                    padding: 0 !important;
                    margin: 0 !important;
                }

                .card {
                    border: none !important;
                    box-shadow: none !important;
                    background: transparent !important;
                }

                body {
                    background: white !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }

                .print-only {
                    display: block !important;
                }

                table {
                    border-collapse: collapse !important;
                    border: 1px solid #000 !important;
                    width: 100% !important;
                    font-family: 'Times New Roman', Times, serif !important;
                }

                th,
                td {
                    border: 1px solid #000 !important;
                    padding: 8px !important;
                    color: #000 !important;
                }

                th {
                    background: #f2f2f2 !important;
                }

                @page {
                    size: A4;
                    margin: 0;
                    /* Hapus header/footer bawaan browser (URL, Tanggal, dll) */
                }
            }
        </style>
    @endpush

    <div class="print-container">
        <!-- Print Only Letterhead -->
        <div class="print-only">
            <div class="kop"
                style="display: flex; align-items: center; gap: 14px; border-bottom: 4px solid #000; padding-bottom: 8px; margin-bottom: 12px; position: relative;">
                <img src="{{ asset('logo_provinsi.png') }}" style="width: 80px; height: 100px; object-fit: contain;"
                    alt="Logo NTB">
                <div style="flex: 1; text-align: center; font-family: 'Times New Roman', Times, serif;">
                    <div style="font-size: 14pt; font-weight: bold; line-height: 1.2;">PEMERINTAH PROVINSI NUSA TENGGARA
                        BARAT</div>
                    <div style="font-size: 18pt; font-weight: bold; line-height: 1.2;">SMA NEGERI 1 KOPANG</div>
                    <div style="font-size: 10pt; line-height: 1.4; margin-top: 4px;">Jl. Segara Anak No. 5A Kopang
                        Lombok Tengah (0370) 6156250 Kode Pos 83553</div>
                    <div style="font-size: 9pt; line-height: 1.4;">Laman: www.smansa-kopang.sch.id Surel:
                        sma_negeri1kopang@yahoo.co.id</div>
                </div>
                <img src="{{ asset('logo_sekolah.jpg') }}" style="width: 80px; height: 100px; object-fit: contain;"
                    alt="Logo Sekolah">
            </div>
            <div style="text-align: center; margin: 20px 0; font-family: 'Times New Roman', Times, serif;">
                <div style="font-size: 13pt; font-weight: bold; text-decoration: underline; text-transform: uppercase;">
                    LAPORAN REKAPITULASI PEMBINAAN KARAKTER SISWA</div>
                @if($startDate || $endDate)
                    <div style="font-size: 10pt; margin-top: 8px;">Periode:
                        {{ $startDate ? \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') : 'Awal' }} s/d
                        {{ $endDate ? \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') : 'Sekarang' }}</div>
                @endif
            </div>
        </div>

        <div class="no-print"
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; animation: fadeInDown 0.4s ease-out;">
            <!-- Card Total Siswa -->
            <div class="card"
                style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--border-light); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div
                    style="width: 56px; height: 56px; border-radius: 16px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="users" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <div
                        style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Total Siswa</div>
                    <div style="font-size: 1.5rem; font-weight: 900; color: var(--primary-dark);">{{ $totalStudents }}
                    </div>
                </div>
            </div>

            <!-- Card Rata-rata Skor -->
            <div class="card"
                style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--border-light); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div
                    style="width: 56px; height: 56px; border-radius: 16px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="trending-up" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <div
                        style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Rata-rata Skor</div>
                    <div style="font-size: 1.5rem; font-weight: 900; color: #059669;">{{ number_format($avgScore, 1) }}
                    </div>
                </div>
            </div>

            <!-- Card Siswa Kritis -->
            <div class="card"
                style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--border-light); background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%);">
                <div
                    style="width: 56px; height: 56px; border-radius: 16px; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="alert-circle" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <div
                        style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Siswa Kritis</div>
                    <div style="font-size: 1.5rem; font-weight: 900; color: #dc2626;">{{ $criticalCount }}</div>
                </div>
            </div>
        </div>

        <div class="card"
            style="padding: 0; overflow: hidden; border: 1px solid var(--border-light); animation: fadeInUp 0.4s ease-out;">
            <div class="no-print"
                style="padding: 1.25rem 1.5rem; background: #ffffff; border-bottom: 1px solid var(--border-light); display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3
                        style="font-size: 1rem; font-weight: 800; color: var(--primary-dark); display: flex; align-items: center; gap: 0.75rem;">
                        <i data-lucide="clipboard-list" style="width: 20px; height: 20px;"></i>
                        Data Rekap Siswa
                    </h3>
                    <div style="display: flex; gap: 0.75rem;">
                        <button onclick="window.print()" class="btn"
                            style="background: #1B6B3A; color: white; font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; border-radius: 10px; border: none; box-shadow: 0 4px 12px rgba(27, 107, 58, 0.2);">
                            <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
                            Cetak Laporan Rekapitulasi
                        </button>
                    </div>
                </div>

                <form action="{{ route('records.recap') }}" method="GET"
                    style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end; background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-light);">
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label
                            style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Dari
                            Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            style="padding: 0.5rem 1rem; border-radius: 10px; border: 1px solid var(--border-light); font-size: 0.8125rem; outline: none; background: white;">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label
                            style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Sampai
                            Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            style="padding: 0.5rem 1rem; border-radius: 10px; border: 1px solid var(--border-light); font-size: 0.8125rem; outline: none; background: white;">
                    </div>
                    <button type="submit" class="btn"
                        style="background: var(--primary); color: white; padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 800; font-size: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i data-lucide="filter" style="width: 16px; height: 16px;"></i>
                        Filter Data
                    </button>
                    <a href="{{ route('records.recap') }}" class="btn"
                        style="background: white; color: var(--text-muted); padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 800; font-size: 0.75rem; border: 1px solid var(--border-light);">
                        Reset
                    </a>

                    <div style="flex: 1; min-width: 200px; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <div style="position: relative; width: 100%; max-width: 200px;">
                            <i data-lucide="search"
                                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; color: var(--text-muted);"></i>
                            <input type="text" id="searchInput" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama..."
                                style="width: 100%; padding: 0.5rem 1rem 0.5rem 2.25rem; border-radius: 10px; border: 1px solid var(--border-light); font-size: 0.75rem; outline: none;">
                        </div>
                        <select id="classFilter" name="class_id"
                            style="padding: 0.5rem 1rem; border-radius: 10px; border: 1px solid var(--border-light); font-size: 0.75rem; outline: none; background: white;">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div class="table-container" id="recapTableContainer" style="overflow-x: auto;">
                <table id="recapTable" style="width: 100%; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr>
                            <th
                                style="padding: 1.25rem 1rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: center; width: 50px;">
                                No</th>
                            <th
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: left;">
                                Siswa</th>
                            <th
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: left;">
                                Kelas</th>
                            <th
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: center;">
                                Penyakit</th>
                            <th
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: center;">
                                Vitamin</th>
                            <th
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: center;">
                                Skor Akhir</th>
                            <th
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: center;">
                                Status</th>
                            <th class="no-print"
                                style="padding: 1.25rem 1.5rem; background: #f8fafc; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid var(--border-light); text-align: center;">
                                Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                                                    $sortedStudents = $students->sortBy(fn($s) => $s->net_points);
                            $no = 1;
                        @endphp

                                
                                                   @foreach($sortedStudents as $student)
                                                    <tr class="premium-row">

                                                                                            <td style="padding: 1.25rem 1rem; text-align: center; font-size: 0.8125rem; font-weight:
                                                                700; color: var(--text-muted);">{{ $no++ }}</td>
                                                        <td style="padding: 1.25rem 1.5rem;">
                                                            <div style="font-weight: 800; color: var(--primary-dark); font-size: 0.9375rem;">{{ $student->name }}</div>
                                                            <div 
                                                               style="font-size: 0.6875rem; color: var(--text-muted); font-weight: 600;">NISN: {{ $student->nisn }}</div>
                                                        </td>
                                                        <td style="padding: 1.25rem 1.5rem;">
                                                            <span style="background: #f1f5f9; color: #475569; padding: 0.25rem 0.
                                                                6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 800;">{{ $student->schoolClass->name ?? '-' }}</span>
                                                        </td>


                                                                                            <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                                            <div style="font-weight: 800; color: #ef4444; font-size: 0.9375rem;">{{ $student->violation_points }}</div>
                                                            <div class="no-print" style="font-size: 0.625rem; color: var(--text-m
                                                                uted); font-weight: 700; text-transform: uppercase;">Poin</div>
                                                        </td>


                                                                                           <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                                            <div style="font-weight: 800; color: #10b981; font-size: 0.9375rem;">{{ $student->vitamin_points }}</div>
                                                            <div
                                                                class="no-print" style="font-size: 0.625rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Poin</div>
                                                        </td>
                                                        <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                                            <div style="font-weight: 900; color: {{ $student->net_points >= 0 ? '#10b981' : '#ef4444' }}; font-size: 1.125rem;">
                                                                {{ $student->net_points > 0 ? '+' : '' }}{{ $student->net_points }}
                                                            </div>
                                                        </td>

                                                                                           <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                                            @php $status = $student->point_status; @endphp
                                                            <span style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 0.4rem 0.8rem; border-radius: 12px; font-size: 0.6875rem; font-weight: 900; letter-spacing: 0.02em;">
                                                                {{ $status['label'] }}
                                                            </span>



                                                                                           </td>
                                                        <td class="no-print" style="padding: 1.25rem 1.5rem; text-align: center;">
                                                            <a href="{{ route('students.show', $student) }}" class="btn" style="background: var(--primary-light); color: var(--primary); width: 32px; height: 32px; border-radius: 10px; padding: 0; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.background='var(--primary)';this.style.color='white'" onmouseout="this.style.background='var(--primary-light)';this.style.color='var(--primary)'">
                                                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                @endforeach
                </tbody>
                </table>
            </div>

                   
            <!-- Pagination Controls -->
    
                   
                           <div class="no-print" style="padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem; border-top: 1px solid var(--border-light); background: #ffffff;">
                <div style="font-size: 0.8125rem; color: #64748b; font-weight: 600;">
                    Menampilkan <span style="font-weight: 800; color: #1e293b;">{{ $students->firstItem() ?? 0 }}</span> s/d <span style="font-weight: 800; color: #1e293b;">{{ $students->lastItem() ?? 0 }}</span> dari <span style="font-weight: 800; color: #1e293b;">{{ $students->total() }}</span> hasil
                </div>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                {{ $students->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            </div>
    
            <div class="print-only">
                <div style="margin-top: 40px; display: flex; justify-content: flex-end; padding: 0 40px;">
                    <div style="text-align: center; font-family: 'Times New Roman', Times, serif;">
                        <p>Kopang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p style="margin-top: 5px;">Mengetahui,</p>
                        <p>Kepala SMAN 1 KOPANG</p>
                        <div style="height: 80px;"></div>
                        <p style="font-weight: bold; text-decoration: underline;">(___________________________)</p>
                        <p>NIP. ...........................</p>
                </div>
            </div>
        </div>
        </div>
</div>
 @push('scripts')
    <script>
        window.onbeforeprint = function() {
            this.originalTitle = docum ent.title;
            document.title = "";
        };
        window.onafterprint = function() {
                document.title = this.originalTitle;
            };
        </script>
@endpush
</x-app-layout>
