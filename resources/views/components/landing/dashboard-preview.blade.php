@props([
    'workspaceMatrix' => [],
])

<section id="preview-dashboard" class="bg-white py-16 sm:py-20 lg:py-24 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-20 lg:space-y-24">

        {{-- Part A: Interactive Multi-Role Dashboard Preview --}}
        <div>
            <div data-reveal class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                        Preview Dashboard
                    </p>
                    <h2 class="mt-3 text-3xl sm:text-[38px] lg:text-[40px] font-semibold text-[#182B35] leading-[1.2] tracking-[-0.02em]">
                        Satu sistem, tampilan kerja yang sesuai tiap peran.
                    </h2>
                    <p class="mt-3 text-sm sm:text-base text-[#627680] leading-[1.6]">
                        Pilih peran di bawah untuk melihat simulasi antarmuka ruang kerja KLINIKA dari pendaftaran hingga operasional klinik.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 self-start lg:self-auto px-3.5 py-2 rounded-lg bg-[#FFF5E4] border border-[#F5D9A8] text-[#96611C] text-xs font-medium">
                    <img src="{{ asset('images/landing/info.svg') }}" alt="" width="15" height="15" class="w-[15px] h-[15px] shrink-0">
                    <span>Mockup interaktif dengan data simulasi (bukan data pasien nyata)</span>
                </div>
            </div>

            {{-- Role Switcher Tabs --}}
            <div data-reveal class="mt-8 flex flex-wrap gap-2.5" role="tablist" aria-label="Pilih simulasi dashboard peran">
                <button type="button"
                        role="tab"
                        id="tab-dokter"
                        aria-selected="true"
                        aria-controls="panel-dokter"
                        tabindex="0"
                        data-preview-tab="dokter"
                        data-browser-title="Klinika  /  Ruang kerja dokter"
                        class="px-4 py-2.5 rounded-lg border text-sm font-semibold transition-all cursor-pointer bg-[#087F83] text-white border-[#087F83] shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                    Ruang Kerja Dokter
                </button>
                <button type="button"
                        role="tab"
                        id="tab-pendaftaran"
                        aria-selected="false"
                        aria-controls="panel-pendaftaran"
                        tabindex="-1"
                        data-preview-tab="pendaftaran"
                        data-browser-title="Klinika  /  Dashboard pendaftaran"
                        class="px-4 py-2.5 rounded-lg border text-sm font-semibold transition-all cursor-pointer bg-white text-[#182B35] border-[#DDE8E8] hover:border-[#087F83]/45 hover:bg-[#F3F9F8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                    Pendaftaran &amp; Antrean
                </button>
                <button type="button"
                        role="tab"
                        id="tab-apoteker"
                        aria-selected="false"
                        aria-controls="panel-apoteker"
                        tabindex="-1"
                        data-preview-tab="apoteker"
                        data-browser-title="Klinika  /  Pelayanan farmasi"
                        class="px-4 py-2.5 rounded-lg border text-sm font-semibold transition-all cursor-pointer bg-white text-[#182B35] border-[#DDE8E8] hover:border-[#087F83]/45 hover:bg-[#F3F9F8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                    Pelayanan Farmasi
                </button>
                <button type="button"
                        role="tab"
                        id="tab-kasir"
                        aria-selected="false"
                        aria-controls="panel-kasir"
                        tabindex="-1"
                        data-preview-tab="kasir"
                        data-browser-title="Klinika  /  Kas & tagihan"
                        class="px-4 py-2.5 rounded-lg border text-sm font-semibold transition-all cursor-pointer bg-white text-[#182B35] border-[#DDE8E8] hover:border-[#087F83]/45 hover:bg-[#F3F9F8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                    Kasir &amp; Tagihan
                </button>
                <button type="button"
                        role="tab"
                        id="tab-admin"
                        aria-selected="false"
                        aria-controls="panel-admin"
                        tabindex="-1"
                        data-preview-tab="admin"
                        data-browser-title="Klinika  /  Operasional klinik"
                        class="px-4 py-2.5 rounded-lg border text-sm font-semibold transition-all cursor-pointer bg-white text-[#182B35] border-[#DDE8E8] hover:border-[#087F83]/45 hover:bg-[#F3F9F8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#087F83] focus-visible:ring-offset-2">
                    Operasional Admin
                </button>
            </div>

            {{-- Browser Mockup Frame --}}
            <div data-reveal class="mt-6 bg-white border border-[#CCDCDC] rounded-[14px] shadow-[0_14px_40px_rgba(10,51,51,0.08)] overflow-hidden">
                {{-- Top Browser Bar --}}
                <div class="bg-[#F4F8F8] border-b border-[#DFE7EA] px-4 py-3 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-1.5 shrink-0" aria-hidden="true">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#9BB6B6]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#B4C9C9]"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-[#CBDADA]"></span>
                    </div>
                    <p id="preview-browser-label" class="text-xs font-medium text-[#627680] truncate">
                        Klinika &nbsp;/&nbsp; Ruang kerja dokter
                    </p>
                    <span class="text-[11px] font-semibold text-[#087F83] bg-[#E8F5F3] px-2.5 py-0.5 rounded shrink-0">
                        Simulasi
                    </span>
                </div>

                {{-- TAB 1: DOKTER (mirrors doctor/index.blade.php & Figma 351:32) --}}
                <div id="panel-dokter" role="tabpanel" aria-labelledby="tab-dokter" data-preview-panel="dokter" class="landing-tab-panel bg-[#F4F7F8] p-4 sm:p-6 lg:p-7 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-[11px] text-[#627680]">Beranda / Ruang praktik dokter</p>
                            <h3 class="text-lg font-bold text-[#182B35] mt-0.5">Ruang praktik dr. Andi</h3>
                            <p class="text-xs text-[#627680]">Poli Umum &bull; Sesi Pagi 08.00–12.00 &bull; Contoh antarmuka RME</p>
                        </div>
                        <span class="self-start sm:self-auto px-3.5 py-2 rounded-lg bg-[#087F83] text-white text-xs font-semibold">
                            Buka antrean lengkap
                        </span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Menunggu dokter</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">5</p>
                            <p class="text-[11px] text-[#627680] mt-1">Pemeriksaan awal selesai</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Dalam pemeriksaan</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">1</p>
                            <p class="text-[11px] text-[#627680] mt-1">Kunjungan aktif saat ini</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">RME final hari ini</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">18</p>
                            <p class="text-[11px] text-[#627680] mt-1">Terkunci dan tercatat</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Draf RME</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">2</p>
                            <p class="text-[11px] text-[#627680] mt-1">Belum diteruskan ke farmasi</p>
                        </div>
                    </div>

                    <div class="bg-white border border-[#DFE7EA] rounded-lg overflow-hidden">
                        <div class="px-4 py-3 border-b border-[#DFE7EA] flex items-center justify-between">
                            <p class="text-xs font-semibold text-[#182B35]">Antrean dokter &bull; penugasan saya</p>
                            <span class="text-[11px] text-[#627680]">Data contoh</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-[#F4F7F8] text-[#627680] border-b border-[#DFE7EA]">
                                    <tr>
                                        <th class="py-2.5 px-4 font-semibold">Antrean</th>
                                        <th class="py-2.5 px-4 font-semibold">Pasien / RM</th>
                                        <th class="py-2.5 px-4 font-semibold">Kunjungan</th>
                                        <th class="py-2.5 px-4 font-semibold">Tahap</th>
                                        <th class="py-2.5 px-4 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#DFE7EA] text-[#182B35]">
                                    <tr>
                                        <td class="py-2.5 px-4 font-semibold">A-023</td>
                                        <td class="py-2.5 px-4"><span class="font-medium">Siti Aminah</span> <span class="block text-[11px] text-[#627680]">RM-000128</span></td>
                                        <td class="py-2.5 px-4 text-[#627680]">KJ-20261006-023</td>
                                        <td class="py-2.5 px-4"><span class="px-2 py-0.5 rounded-full bg-[#FEF9C3] text-[#854D0E] text-[11px] font-medium">Menunggu dokter</span></td>
                                        <td class="py-2.5 px-4 text-right font-semibold text-[#087F83]">Mulai pemeriksaan &rarr;</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 px-4 font-semibold">A-019</td>
                                        <td class="py-2.5 px-4"><span class="font-medium">Hendra Wijaya</span> <span class="block text-[11px] text-[#627680]">RM-000135</span></td>
                                        <td class="py-2.5 px-4 text-[#627680]">KJ-20261006-019</td>
                                        <td class="py-2.5 px-4"><span class="px-2 py-0.5 rounded-full bg-[#DCFCE7] text-[#15803D] text-[11px] font-medium">Dalam pemeriksaan</span></td>
                                        <td class="py-2.5 px-4 text-right font-semibold text-[#087F83]">Lanjutkan RME &rarr;</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2.5 px-4 font-semibold">A-018</td>
                                        <td class="py-2.5 px-4"><span class="font-medium">Lilis Suryani</span> <span class="block text-[11px] text-[#627680]">RM-000136</span></td>
                                        <td class="py-2.5 px-4 text-[#627680]">KJ-20261006-018</td>
                                        <td class="py-2.5 px-4"><span class="px-2 py-0.5 rounded-full bg-[#FFF5E4] text-[#96611C] text-[11px] font-medium">Draf RME</span></td>
                                        <td class="py-2.5 px-4 text-right font-semibold text-[#087F83]">Finalisasi draf &rarr;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: PENDAFTARAN (mirrors pendaftaran/dashboard.blade.php) --}}
                <div id="panel-pendaftaran" role="tabpanel" aria-labelledby="tab-pendaftaran" data-preview-panel="pendaftaran" class="landing-tab-panel hidden bg-[#F4F7F8] p-4 sm:p-6 lg:p-7 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-[11px] text-[#627680]">Dashboard Pendaftaran</p>
                            <h3 class="text-lg font-bold text-[#182B35] mt-0.5">Loket Pendaftaran &amp; Antrean</h3>
                            <p class="text-xs text-[#627680]">Pencarian nomor RM, registrasi kunjungan, dan pantauan antrean poli</p>
                        </div>
                        <span class="self-start sm:self-auto px-3.5 py-2 rounded-lg bg-[#087F83] text-white text-xs font-semibold">
                            Cari / daftar pasien
                        </span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Terdaftar hari ini</p>
                            <p class="text-2xl font-bold text-[#087F83] mt-1">24</p>
                            <p class="text-[11px] text-[#627680] mt-1">19 pasien lama &bull; 5 baru</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Menunggu awal</p>
                            <p class="text-2xl font-bold text-[#D97706] mt-1">4</p>
                            <p class="text-[11px] text-[#627680] mt-1">Belum dipanggil perawat</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Dalam pelayanan</p>
                            <p class="text-2xl font-bold text-[#2563EB] mt-1">6</p>
                            <p class="text-[11px] text-[#627680] mt-1">Sedang ditangani poli</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Selesai</p>
                            <p class="text-2xl font-bold text-[#16A34A] mt-1">14</p>
                            <p class="text-[11px] text-[#627680] mt-1">Kunjungan tuntas</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#BFDBFE] bg-[#EFF6FF] p-3.5 text-xs text-[#1E40AF]">
                        <span class="font-semibold">Pasien lama, kunjungan baru</span> — Cari nomor RM terlebih dahulu untuk mencegah duplikasi data pasien sebelum menerbitkan nomor antrean.
                    </div>
                </div>

                {{-- TAB 3: APOTEKER (mirrors apoteker/index.blade.php) --}}
                <div id="panel-apoteker" role="tabpanel" aria-labelledby="tab-apoteker" data-preview-panel="apoteker" class="landing-tab-panel hidden bg-[#F4F7F8] p-4 sm:p-6 lg:p-7 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-[11px] text-[#627680]">Beranda / Pelayanan farmasi</p>
                            <h3 class="text-lg font-bold text-[#182B35] mt-0.5">Pelayanan farmasi</h3>
                            <p class="text-xs text-[#627680]">Resep final yang masuk dari dokter dan kesiapan persediaan obat</p>
                        </div>
                        <span class="self-start sm:self-auto px-3.5 py-2 rounded-lg bg-[#087F83] text-white text-xs font-semibold">
                            Buka daftar resep
                        </span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Menunggu verifikasi</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">6</p>
                            <p class="text-[11px] text-[#627680] mt-1">Resep dari RME final</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Sedang diracik</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">3</p>
                            <p class="text-[11px] text-[#627680] mt-1">Dalam proses apoteker</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Diserahkan hari ini</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">28</p>
                            <p class="text-[11px] text-[#627680] mt-1">Stok telah dikurangi</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Stok di bawah minimum</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">2</p>
                            <p class="text-[11px] text-[#627680] mt-1">Perlu peninjauan stok</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-amber-200 bg-[#FFF5E4] p-3.5 text-xs text-[#96611C] flex items-start gap-2.5">
                        <img src="{{ asset('images/landing/info.svg') }}" alt="" width="15" height="15" class="w-[15px] h-[15px] mt-0.5 shrink-0">
                        <span><strong>Persediaan perlu ditinjau (simulasi):</strong> Contoh notifikasi otomatis saat jumlah obat berada di bawah ambang stok minimum.</span>
                    </div>
                </div>

                {{-- TAB 4: KASIR (mirrors kasir/index.blade.php) --}}
                <div id="panel-kasir" role="tabpanel" aria-labelledby="tab-kasir" data-preview-panel="kasir" class="landing-tab-panel hidden bg-[#F4F7F8] p-4 sm:p-6 lg:p-7 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-[11px] text-[#627680]">Beranda / Kas dan tagihan</p>
                            <h3 class="text-lg font-bold text-[#182B35] mt-0.5">Kas dan tagihan hari ini</h3>
                            <p class="text-xs text-[#627680]">Penyusunan tagihan layanan, pembayaran invoice, cetak kwitansi, dan tutup shift</p>
                        </div>
                        <span class="self-start sm:self-auto px-3.5 py-2 rounded-lg bg-[#087F83] text-white text-xs font-semibold">
                            Buka invoice
                        </span>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5">
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Belum dibayar</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">4</p>
                            <p class="text-[11px] text-[#627680] mt-1">Menunggu penyelesaian kasir</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Pembayaran hari ini</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">21</p>
                            <p class="text-[11px] text-[#627680] mt-1">Transaksi tercatat</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Rekap metode bayar</p>
                            <p class="text-2xl font-bold text-[#087F83] mt-1">Tunai / Non</p>
                            <p class="text-[11px] text-[#627680] mt-1">Terpisah otomatis di rekap kas</p>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-4">
                            <p class="text-xs text-[#627680] font-medium">Selisih kas</p>
                            <p class="text-2xl font-bold text-[#182B35] mt-1">Rp 0</p>
                            <p class="text-[11px] text-[#627680] mt-1">Saldo sesuai rekap shift</p>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[#C1ECEC] bg-[#E6F7F7] p-3.5 text-xs text-[#087F83]">
                        <strong>Konfirmasi sebelum mencatat pembayaran:</strong> Cocokkan pasien, ID kunjungan, total tagihan, metode, dan nominal diterima untuk menghindari pencatatan ganda.
                    </div>
                </div>

                {{-- TAB 5: ADMIN OPERASIONAL (mirrors admin/operasional.blade.php) --}}
                <div id="panel-admin" role="tabpanel" aria-labelledby="tab-admin" data-preview-panel="admin" class="landing-tab-panel hidden bg-[#F4F7F8] p-4 sm:p-6 lg:p-7 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-[11px] text-[#627680]">Beranda / Operasional Klinik</p>
                            <h3 class="text-lg font-bold text-[#182B35] mt-0.5">Operasional &amp; Ringkasan Klinik</h3>
                            <p class="text-xs text-[#627680]">Gambaran kunjungan tujuh hari, jadwal dokter, kesiapan poli, dan pengguna aktif</p>
                        </div>
                        <span class="self-start sm:self-auto px-3.5 py-2 rounded-lg bg-[#087F83] text-white text-xs font-semibold">
                            Kelola jadwal
                        </span>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="lg:col-span-2 bg-white border border-[#DFE7EA] rounded-lg p-5">
                            <p class="text-xs font-semibold text-[#182B35] mb-5">Contoh grafik kunjungan 7 hari (simulasi)</p>
                            <div class="h-36 flex items-end justify-between gap-2.5 px-2 pt-3 border-b border-[#DFE7EA]">
                                @foreach ([
                                    ['label' => 'Sen', 'pct' => '55%', 'val' => 18, 'active' => false],
                                    ['label' => 'Sel', 'pct' => '72%', 'val' => 24, 'active' => false],
                                    ['label' => 'Rab', 'pct' => '64%', 'val' => 21, 'active' => false],
                                    ['label' => 'Kam', 'pct' => '84%', 'val' => 28, 'active' => false],
                                    ['label' => 'Jum', 'pct' => '68%', 'val' => 22, 'active' => false],
                                    ['label' => 'Sab', 'pct' => '48%', 'val' => 16, 'active' => false],
                                    ['label' => 'Hari ini', 'pct' => '90%', 'val' => 30, 'active' => true],
                                ] as $bar)
                                    <div class="flex-1 flex flex-col items-center h-full justify-end">
                                        <span class="text-[10px] font-medium text-[#627680] mb-1">{{ $bar['val'] }}</span>
                                        <div class="w-full max-w-[38px] rounded-t-sm {{ $bar['active'] ? 'bg-[#087F83]' : 'bg-[#BCDCD8]' }}" style="height: {{ $bar['pct'] }};"></div>
                                        <span class="text-[10px] text-[#627680] mt-1.5">{{ $bar['label'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-white border border-[#DFE7EA] rounded-lg p-5 flex flex-col justify-between">
                            <div>
                                <p class="text-xs font-semibold text-[#182B35]">Kesiapan Layanan Poli</p>
                                <ul class="mt-3 space-y-2.5 text-xs">
                                    <li class="flex items-center justify-between border-b border-[#EEF3F3] pb-2">
                                        <span class="font-medium text-[#182B35]">Poli Umum</span>
                                        <span class="text-emerald-700 font-semibold">Aktif</span>
                                    </li>
                                    <li class="flex items-center justify-between border-b border-[#EEF3F3] pb-2">
                                        <span class="font-medium text-[#182B35]">Poli Gigi</span>
                                        <span class="text-emerald-700 font-semibold">Aktif</span>
                                    </li>
                                    <li class="flex items-center justify-between">
                                        <span class="font-medium text-[#182B35]">Poli KIA</span>
                                        <span class="text-emerald-700 font-semibold">Aktif</span>
                                    </li>
                                </ul>
                            </div>
                            <p class="mt-4 text-[11px] text-[#627680]">Master poli, jadwal dokter, &amp; RBAC dikelola terpusat oleh Admin.</p>
                        </div>
                    </div>
                </div>

                {{-- Honest Simulation Footer Bar --}}
                <div class="bg-white border-t border-[#DFE7EA] px-4 sm:px-6 py-2.5 flex flex-wrap items-center justify-between gap-2 text-[11px] text-[#627680]">
                    <span>Data simulasi &bull; Bukan data pasien nyata</span>
                    <span>Klinika RME &bull; Ilustrasi antarmuka berbasis modul aplikasi</span>
                </div>
            </div>
        </div>

        {{-- Part B: Exact Figma 351:8 Role-Based Workspace Split --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center pt-4">
            <div data-reveal class="lg:col-span-5">
                <p class="text-xs font-semibold tracking-[0.08em] uppercase text-[#087F83]">
                    Ruang Kerja Sesuai Peran
                </p>
                <h3 class="mt-3 text-3xl sm:text-[38px] lg:text-[42px] font-semibold text-[#182B35] leading-[1.2] tracking-[-0.02em]">
                    Tim yang berbeda.<br>Tujuan yang sama.
                </h3>
                <p class="mt-5 text-sm sm:text-base text-[#627680] leading-[1.65]">
                    Berikan setiap anggota tim ruang kerja yang relevan. Informasi yang dibutuhkan tersedia secara langsung, tanpa membuat tanggung jawab antarunit saling tumpang tindih.
                </p>

                <div class="mt-8 space-y-3.5">
                    <div class="flex items-center gap-3 text-[15px] font-semibold text-[#182B35]">
                        <span class="text-[#087F83] text-sm font-bold">01</span>
                        <span>Dashboard otomatis menyesuaikan peran aktif</span>
                    </div>
                    <div class="flex items-center gap-3 text-[15px] font-semibold text-[#182B35]">
                        <span class="text-[#087F83] text-sm font-bold">02</span>
                        <span>Konteks pasien dan kunjungan tetap terhubung lintas unit</span>
                    </div>
                </div>
            </div>

            <div data-reveal class="lg:col-span-7">
                <div class="bg-[#F0F7F6] rounded-[18px] p-4 sm:p-6">
                    <div class="bg-white border border-[#DDE8E8] rounded-[10px] overflow-hidden">
                        <div class="px-5 py-4 border-b border-[#DDE8E8]">
                            <h4 class="text-lg font-semibold text-[#182B35]">Ruang kerja tim Anda</h4>
                            <p class="text-xs text-[#627680] mt-0.5">Setiap peran, fokus pada tugasnya.</p>
                        </div>

                        <div class="divide-y divide-[#EEF3F3]">
                            @foreach ($workspaceMatrix as $item)
                                <div class="px-5 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4 min-w-0">
                                        <span class="text-[13px] font-semibold text-[#182B35] sm:w-36 shrink-0">{{ $item['role'] }}</span>
                                        <span class="text-[13px] text-[#627680]">{{ $item['scope'] }}</span>
                                    </div>
                                    <span class="self-start sm:self-auto inline-flex items-center px-2.5 py-1 rounded-[6px] bg-[#E8F5F3] text-[#087F83] text-[11px] font-semibold shrink-0">
                                        {{ $item['badge'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="mt-2.5 px-1 text-[11px] text-[#627680]">
                        Ilustrasi pembagian ruang lingkup akses di KLINIKA
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
