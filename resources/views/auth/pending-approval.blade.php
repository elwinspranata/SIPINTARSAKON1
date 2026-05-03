<x-guest-layout>
    <div style="text-align: center; padding: 2rem 0;">
        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, rgba(255, 145, 0, 0.1), rgba(255, 215, 0, 0.15)); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i data-lucide="clock" style="color: #FF9100;" size="36"></i>
        </div>
        
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin-bottom: 0.5rem;">Menunggu Persetujuan</h2>
        <p style="color: #64748b; font-size: 0.9375rem; line-height: 1.7; margin-bottom: 2rem;">
            Akun Anda sudah terdaftar dan sedang menunggu<br>
            persetujuan dari Administrator sekolah.
        </p>

        <div style="background: rgba(255, 145, 0, 0.06); border: 1px solid rgba(255, 145, 0, 0.15); border-radius: 16px; padding: 1.25rem; margin-bottom: 2rem; text-align: left;">
            <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                <i data-lucide="info" style="color: #FF9100; flex-shrink: 0; margin-top: 2px;" size="18"></i>
                <div>
                    <div style="font-weight: 700; font-size: 0.875rem; color: #1e293b; margin-bottom: 0.25rem;">Apa yang harus dilakukan?</div>
                    <ul style="font-size: 0.8125rem; color: #64748b; padding-left: 1rem; display: flex; flex-direction: column; gap: 0.35rem;">
                        <li>Hubungi administrator sekolah</li>
                        <li>Berikan email yang Anda gunakan untuk mendaftar</li>
                        <li>Tunggu hingga akun disetujui</li>
                    </ul>
                </div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <button onclick="window.location.reload()" class="submit-btn" style="background: linear-gradient(135deg, #2B5EA7, #1e4a85);">
                <i data-lucide="refresh-cw" size="16" style="margin-right: 0.5rem;"></i>
                Cek Status
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="width: 100%; padding: 0.875rem; background: transparent; border: 2px solid #e2e8f0; border-radius: 14px; color: #64748b; font-weight: 700; font-size: 0.9375rem; cursor: pointer; transition: all 0.2s; font-family: inherit;" onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444'" onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
                    Keluar
                </button>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</x-guest-layout>
