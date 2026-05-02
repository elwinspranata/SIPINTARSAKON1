<x-guest-layout>
    <div class="content-header">
        <h2 class="content-title">Lupa Password</h2>
        <p class="content-subtitle">Masukkan email Anda untuk menerima link reset password</p>
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div style="margin-bottom: 1.5rem; background: var(--success-light); color: var(--success); padding: 0.875rem; border-radius: 12px; font-size: 0.8125rem; text-align: center; font-weight: 700; border: 1px solid rgba(0,210,106,0.1);">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">
                <i data-lucide="mail" size="14"></i>
                <span>Alamat Email</span>
            </label>
            <input id="email" class="input-control" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
            @if($errors->has('email'))
                <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <button type="submit" class="btn-submit">
            <i data-lucide="send" size="18"></i>
            <span>KIRIM LINK RESET</span>
        </button>

        <div class="footer-link">
            <a href="{{ route('login') }}">
                <i data-lucide="arrow-left" size="16"></i>
                <span>Kembali ke Login</span>
            </a>
        </div>
    </form>
</x-guest-layout>
