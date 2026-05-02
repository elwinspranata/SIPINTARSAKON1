<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label class="label">Nama Lengkap</label>
            <div class="input-wrapper">
                <i data-lucide="user" class="input-icon" size="20"></i>
                <input id="name" class="custom-input" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Masukkan nama Anda">
            </div>
            @if($errors->has('name'))
                <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label class="label">Alamat Email</label>
            <div class="input-wrapper">
                <i data-lucide="mail" class="input-icon" size="20"></i>
                <input id="email" class="custom-input" type="email" name="email" value="{{ old('email') }}" required placeholder="Masukkan email Anda">
            </div>
            @if($errors->has('email'))
                <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label class="label">Password</label>
            <div class="input-wrapper">
                <i data-lucide="lock" class="input-icon" size="20"></i>
                <input id="password" class="custom-input" type="password" name="password" required placeholder="••••••••">
            </div>
            @if($errors->has('password'))
                <p style="color: var(--danger); font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label class="label">Konfirmasi Password</label>
            <div class="input-wrapper">
                <i data-lucide="shield-check" class="input-icon" size="20"></i>
                <input id="password_confirmation" class="custom-input" type="password" name="password_confirmation" required placeholder="••••••••">
            </div>
        </div>

        <button type="submit" class="submit-btn">
            Daftar Sekarang
        </button>

        <div style="margin-top: 2rem; text-align: center; font-size: 0.9375rem; color: #64748b;">
            Sudah punya akun? 
            <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 700; text-decoration: none;">Masuk Sekarang</a>
        </div>
    </form>
</x-guest-layout>
