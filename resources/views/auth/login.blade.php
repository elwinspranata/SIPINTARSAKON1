<x-guest-layout>
    <!-- Session Status -->
    @if(session('status'))
        <div style="margin-bottom: 1.5rem; background: #f0fdf4; color: #166534; padding: 0.875rem; border-radius: 12px; font-size: 0.875rem; text-align: center; font-weight: 600; border: 1px solid #dcfce7;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label class="label">Alamat Email</label>
            <div class="input-wrapper">
                <i data-lucide="mail" class="input-icon" size="20"></i>
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan email Anda">
            </div>
            @if($errors->has('email'))
                <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label class="label">Password</label>
            <div class="input-wrapper">
                <i data-lucide="lock" class="input-icon" size="20"></i>
                <input id="password" class="custom-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            </div>
            @if($errors->has('password'))
                <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Remember Me & Forgot Password -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem;">
            <label for="remember_me" style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #7fa98a; cursor: pointer; font-weight: 500;">
                <input id="remember_me" type="checkbox" name="remember" style="width: 1.125rem; height: 1.125rem; border-radius: 6px; accent-color: #1B6B3A; cursor: pointer;">
                <span>Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a style="font-size: 0.875rem; color: #1B6B3A; font-weight: 700; text-decoration: none;" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <button type="submit" class="submit-btn">
            Masuk ke Sistem
        </button>

        @if (Route::has('register'))
        <div style="margin-top: 2rem; text-align: center; font-size: 0.9375rem; color: #7fa98a;">
            Belum punya akun? 
            <a href="{{ route('register') }}" style="color: #1B6B3A; font-weight: 700; text-decoration: none;">Daftar Sekarang</a>
        </div>
        @endif
    </form>
</x-guest-layout>
