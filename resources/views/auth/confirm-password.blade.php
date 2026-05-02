<x-guest-layout>
    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 1.5rem; color: #0f172a; font-weight: 800; margin-bottom: 0.5rem;">Konfirmasi Password</h2>
        <p style="font-size: 0.875rem; color: #64748b;">Ini adalah area aman. Harap konfirmasi password Anda sebelum melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="form-group">
            <label class="label">Password</label>
            <div class="input-wrapper">
                <i data-lucide="lock" class="input-icon" size="20"></i>
                <input id="password" class="custom-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
            </div>
            @if($errors->has('password'))
                <p style="color: #ef4444; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <button type="submit" class="submit-btn">
            Konfirmasi
        </button>
    </form>
</x-guest-layout>
