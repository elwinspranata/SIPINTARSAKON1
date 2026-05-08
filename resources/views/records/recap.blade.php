<x-app-layout>
    @section('header_title', 'Rekapitulasi Kolektif')
    @section('header_subtitle', 'Rekap data penyakit dan vitamin seluruh siswa secara kolektif.')

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

            @media (max-width: 768px) {
                .filter-bar {
                    flex-direction: column;
                    gap: 1rem !important;
                }
                .filter-bar .filter-search {
                    width: 100%;
                }
                .filter-buttons {
                    width: 100%;
                }
                .filter-buttons .btn {
                    flex: 1;
                    justify-content: center;
                }
                .recap-header {
                    flex-direction: column !important;
                    align-items: stretch !important;
                    gap: 1rem !important;
                }
                .recap-header-main {
                    flex-direction: column;
                    align-items: flex-start !important;
                    gap: 1rem !important;
                }
                .recap-filters {
                    width: 100%;
                    gap: 0.5rem !important;
                }
                .date-filter-container {
                    width: 100%;
                    justify-content: space-between;
                }
                .filter-select {
                    flex: 1 1 45%;
                }
                .filter-divider {
                    display: none;
                }
                .print-btn {
                    width: 100%;
                    justify-content: center;
                    margin-top: 0.5rem;
                }
                .recap-summary {
                    width: 100%;
                    justify-content: center !important;
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
            <a href="{{ route('records.recap', request()->except(['page', 'status'])) }}" class="card"
                style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--border-light); background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); text-decoration: none; transition: all 0.2s ease;"
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px -5px rgba(0,0,0,0.05)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
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
            </a>

            <!-- Card Siswa Sehat -->
            <a href="{{ route('records.recap', array_merge(request()->except('page'), ['status' => 'SEHAT'])) }}" class="card"
                style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--border-light); background: linear-gradient(135deg, #ffffff 0%, #ecfef9 100%); text-decoration: none; transition: all 0.2s ease;"
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px -5px rgba(0,0,0,0.05)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                <div
                    style="width: 56px; height: 56px; border-radius: 16px; background: #ecfef9; color: #06b6d4; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="check-circle" style="width: 28px; height: 28px;"></i>
                </div>
                <div>
                    <div
                        style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.25rem;">
                        Siswa Sehat</div>
                    <div style="font-size: 1.5rem; font-weight: 900; color: #0891b2;">{{ $healthyCount }}
                    </div>
                </div>
            </a>

            <!-- Card Siswa Kritis -->
            <a href="{{ route('records.recap', array_merge(request()->except('page'), ['status' => 'KRITIS'])) }}" class="card"
                style="padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; border: 1px solid var(--border-light); background: linear-gradient(135deg, #ffffff 0%, #fef2f2 100%); text-decoration: none; transition: all 0.2s ease;"
                onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 25px -5px rgba(0,0,0,0.05)';"
                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
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
            </a>
        </div>

        {{-- Filters --}}
        <form id="recapFilterForm" method="GET" action="{{ route('records.recap') }}" class="no-print">
            <div class="filter-bar" style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                <div class="filter-search" style="flex: 1; min-width: 200px;">
                    <i data-lucide="search" class="filter-search-icon" style="width: 16px; height: 16px;"></i>
                    <input type="text" id="searchInput" name="search" class="filter-input" style="border: none; background: transparent; width: 100%; outline: none;"
                        placeholder="Cari nama atau NISN..." value="{{ request('search') }}">
                </div>
                <div class="filter-buttons" style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem; border-radius: 12px;">
                        <i data-lucide="filter" style="width: 14px; height: 14px;"></i> <span>Filter</span>
                    </button>
                    <a href="{{ route('records.recap') }}" class="btn btn-outline" style="padding: 0.5rem 1rem; border-radius: 12px;" title="Reset Filter">
                        <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                    </a>
                </div>
            </div>
        </form>

        <div class="card"
            style="padding: 0; overflow: hidden; border: 1px solid var(--border-light); animation: fadeInUp 0.4s ease-out;">
            <div class="no-print recap-header"
                style="padding: 1.5rem; border-bottom: 1px solid var(--border-light); background: linear-gradient(to right, #f8fafc, #ffffff); display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
                <div class="recap-header-main" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; width: 100%;">
                <div>
                    <h3
                        style="font-size: 1rem; font-weight: 800; display: flex; align-items: center; gap: 0.6rem; color: var(--primary-dark);">
                        <i data-lucide="clipboard-list" style="width: 20px; height: 20px; color: var(--primary);"></i>
                        Data Rekap Siswa
                    </h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">Tabel rekapitulasi poin perilaku siswa</p>
                </div>
                
                <div class="recap-filters" style="display: flex; align-items: center; justify-content: flex-end; gap: 0.5rem; flex-wrap: wrap;">
                    <div class="date-filter-container" style="display: flex; align-items: center; gap: 0.3rem; background: #f8fafc; border: 1px solid var(--border-light); padding: 0.2rem 0.4rem; border-radius: 6px;">
                        <input type="date" name="start_date" form="recapFilterForm" value="{{ $startDate }}"
                            onchange="document.getElementById('recapFilterForm').submit()"
                            style="border: none; background: transparent; padding: 0; font-size: 0.7rem; font-weight: 700; color: var(--primary-dark); outline: none; width: auto;" title="Dari Tanggal">
                        <span style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted);">—</span>
                        <input type="date" name="end_date" form="recapFilterForm" value="{{ $endDate }}"
                            onchange="document.getElementById('recapFilterForm').submit()"
                            style="border: none; background: transparent; padding: 0; font-size: 0.7rem; font-weight: 700; color: var(--primary-dark); outline: none; width: auto;" title="Sampai Tanggal">
                    </div>
                    <select name="class_id" form="recapFilterForm" class="filter-select" style="border: 1px solid var(--border-light); background-color: #f8fafc; font-size: 0.7rem; font-weight: 700; color: var(--primary-dark); padding: 0.25rem 0.75rem; border-radius: 6px; height: auto;" onchange="document.getElementById('recapFilterForm').submit()">
                        <option value="">Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <select name="status" form="recapFilterForm" class="filter-select" style="border: 1px solid var(--border-light); background-color: #f8fafc; font-size: 0.7rem; font-weight: 700; color: var(--primary-dark); padding: 0.25rem 0.75rem; border-radius: 6px; height: auto;" onchange="document.getElementById('recapFilterForm').submit()">
                        <option value="">Status</option>
                        <option value="SEHAT" {{ request('status') == 'SEHAT' ? 'selected' : '' }}>Sehat</option>
                        <option value="AMAN" {{ request('status') == 'AMAN' ? 'selected' : '' }}>Aman</option>
                        <option value="BAIK" {{ request('status') == 'BAIK' ? 'selected' : '' }}>Baik</option>
                        <option value="WASPADA" {{ request('status') == 'WASPADA' ? 'selected' : '' }}>Waspada</option>
                        <option value="KRITIS" {{ request('status') == 'KRITIS' ? 'selected' : '' }}>Kritis</option>
                    </select>
                    <button type="button" onclick="window.print()" class="btn btn-primary print-btn" style="padding: 0.35rem 0.8rem; border-radius: 8px; font-size: 0.7rem;">
                        <i data-lucide="printer" style="width: 12px; height: 12px;"></i>
                        <span>Cetak Laporan</span>
                    </button>
                    <div class="filter-divider" style="width: 1px; height: 14px; background: var(--border);"></div>
                    <div class="recap-summary" style="display: flex; align-items: center; justify-content: flex-end; gap: 0.35rem; flex-wrap: wrap;">
                        <span class="siswa-count" style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary);">{{ $students->total() }} Siswa</span>
                        <span class="status-badge"
                            style="background: var(--primary-light); color: var(--primary); font-size: 0.65rem; padding: 0.3rem 0.6rem; border-radius: 6px;">Hal {{ $students->currentPage() }}</span>
                    </div>
                </div>
                </div>
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

            @if($students->hasPages())
                <div class="no-print" style="padding: 1.25rem 1.5rem; border-top: 1px solid var(--border-light); background: #fcfdfe;">
                    {{ $students->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            @endif
    
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
        (function () {
            const filterForm = document.getElementById('recapFilterForm');
            const searchInput = document.getElementById('searchInput');

            if (searchInput && filterForm) {
                let debounceTimer;

                const submitFilter = () => {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(() => filterForm.submit(), 300);
                };

                searchInput.addEventListener('input', submitFilter);
                searchInput.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        filterForm.submit();
                    }
                });
            }
        })();

        window.onbeforeprint = function() {
            this.originalTitle = document.title;
            document.title = "";
        };
        window.onafterprint = function() {
            document.title = this.originalTitle;
        };
    </script>
@endpush
</x-app-layout>
