<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Si Pintar') }} - Login</title>

    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            background-color: #f0faf3;
            color: #1a2e1f;
            -webkit-font-smoothing: antialiased;
            width: 100%;
            overflow-x: hidden;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            background: linear-gradient(135deg, #dcfce7 0%, #f0faf3 100%);
            overflow: hidden;
        }

        /* Sparkles background effect */
        .sparkle {
            position: absolute;
            background: white;
            border-radius: 50%;
            opacity: 0.6;
            animation: twinkle var(--d) infinite ease-in-out;
        }

        @keyframes twinkle {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.3;
            }

            50% {
                transform: scale(1.5);
                opacity: 0.8;
            }
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border-radius: 20px;
            padding: 2.25rem 2rem;
            box-shadow: 0 20px 40px -12px rgba(27, 107, 58, 0.12),
                0 0 0 1px rgba(0, 0, 0, 0.01);
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .banner-logo {
            text-align: center;
            margin-bottom: 0.75rem;
        }

        .logo-img {
            height: 90px;
            width: auto;
            margin-bottom: -0.5rem;
        }

        .motto-text {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #3d5c45;
            font-style: italic;
            margin-bottom: 1rem;
        }

        .school-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f0faf3;
            padding: 0.375rem 0.875rem;
            border-radius: 99px;
            font-size: 0.7rem;
            font-weight: 800;
            color: #1B6B3A;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.25rem;
            border: 1px solid #e8f5ec;
        }

        .school-badge img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .copyright {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.7rem;
            color: #7fa98a;
            font-weight: 700;
            letter-spacing: 0.025em;
        }

        /* Form elements reused from refined design */
        .form-group {
            margin-bottom: 1.125rem;
            text-align: left;
        }

        .label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #1a2e1f;
            margin-bottom: 0.375rem;
            display: block;
            padding-left: 0.25rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1.125rem;
            top: 50%;
            transform: translateY(-50%);
            color: #7fa98a;
        }

        .custom-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            border-radius: 12px;
            border: 2px solid #e8f5ec;
            background: #f8fafc;
            font-size: 0.875rem;
            font-family: inherit;
            transition: all 0.2s;
            color: #1a2e1f;
        }

        .custom-input:focus {
            outline: none;
            border-color: #1B6B3A;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(27, 107, 58, 0.08);
        }

        .submit-btn {
            width: 100%;
            padding: 0.875rem;
            border-radius: 12px;
            background: #1B6B3A;
            color: white;
            border: none;
            font-size: 0.9375rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 16px -5px rgba(27, 107, 58, 0.25);
            margin-top: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .submit-btn:hover {
            background: #155730;
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -5px rgba(27, 107, 58, 0.35);
        }

        /* Forgot Password & Other Auth Pages */
        .content-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .content-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #1a2e1f;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .content-subtitle {
            font-size: 0.875rem;
            color: #7fa98a;
            line-height: 1.5;
            font-weight: 500;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #1a2e1f;
            margin-bottom: 0.5rem;
            padding-left: 0.25rem;
        }

        .input-control {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border-radius: 12px;
            border: 2px solid #e8f5ec;
            background: #f8fafc;
            font-size: 0.9375rem;
            transition: all 0.2s;
            color: #1a2e1f;
            font-family: inherit;
        }

        .input-control:focus {
            outline: none;
            border-color: #1B6B3A;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(27, 107, 58, 0.08);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            background: #1B6B3A;
            color: white;
            border: none;
            font-size: 0.9375rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 16px -5px rgba(27, 107, 58, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: #155730;
            transform: translateY(-1px);
            box-shadow: 0 12px 24px -5px rgba(27, 107, 58, 0.35);
        }

        .footer-link {
            margin-top: 2rem;
            text-align: center;
        }

        .footer-link a {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #1B6B3A;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .footer-link a:hover {
            color: #155730;
            transform: translateX(-3px);
        }

        @media (max-width: 480px) {
            .login-wrapper {
                padding: 1rem;
            }

            .login-card {
                padding: 2rem 1.25rem;
                border-radius: 16px;
                width: 100%;
                margin: 0 auto;
            }

            .logo-img {
                height: 70px;
            }

            .motto-text {
                font-size: 0.75rem;
            }

            .school-badge {
                font-size: 0.65rem;
                padding: 0.25rem 0.6rem;
            }
        }

        /* ===== WELCOME MODAL ===== */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(10, 20, 14, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            opacity: 0;
            animation: modalFadeIn 0.5s ease-out forwards;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-overlay.closing {
            animation: modalFadeOut 0.35s ease-in forwards;
        }

        @keyframes modalFadeOut {
            from {
                opacity: 1;
            }

            to {
                opacity: 0;
            }
        }

        .modal-card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 64px -16px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            transform: translateY(30px) scale(0.96);
            animation: modalSlideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;
            position: relative;
        }

        @keyframes modalSlideUp {
            from {
                transform: translateY(30px) scale(0.96);
                opacity: 0;
            }

            to {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
        }

        .modal-overlay.closing .modal-card {
            animation: modalSlideDown 0.3s ease-in forwards;
        }

        @keyframes modalSlideDown {
            from {
                transform: translateY(0) scale(1);
                opacity: 1;
            }

            to {
                transform: translateY(20px) scale(0.97);
                opacity: 0;
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.75rem;
            border-bottom: 1px solid #e8f5ec;
            background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
        }

        .modal-header-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .modal-header-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: linear-gradient(135deg, #22c55e, #1b6b3a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px -2px rgba(27, 107, 58, 0.35);
        }

        .modal-header-text h3 {
            font-size: 1.0625rem;
            font-weight: 800;
            color: #1a2e1f;
            margin: 0;
            letter-spacing: -0.02em;
        }

        .modal-header-text span {
            font-size: 0.6875rem;
            font-weight: 700;
            color: #22c55e;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .modal-close-btn {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 2px solid #e8f5ec;
            background: white;
            color: #7fa98a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .modal-close-btn:hover {
            background: #f0fdf4;
            border-color: #1b6b3a;
            color: #1b6b3a;
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 1.75rem;
            max-height: 55vh;
            overflow-y: auto;
        }

        .modal-body::-webkit-scrollbar {
            width: 4px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: transparent;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #d1e7d8;
            border-radius: 99px;
        }

        .modal-welcome-title {
            font-size: 1.125rem;
            font-weight: 800;
            color: #1a2e1f;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .modal-welcome-desc {
            font-size: 0.875rem;
            line-height: 1.8;
            color: #3d5c45;
            font-weight: 500;
            margin-bottom: 1.5rem;
        }

        .modal-welcome-desc strong {
            color: #1b6b3a;
            font-weight: 800;
        }

        .modal-points {
            background: #f0fdf4;
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e8f5ec;
        }

        .modal-point {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
        }

        .modal-point+.modal-point {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e0f0e5;
        }

        .modal-point-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: linear-gradient(135deg, #22c55e, #1b6b3a);
            flex-shrink: 0;
            margin-top: 6px;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15);
        }

        .modal-point-text {
            font-size: 0.8125rem;
            line-height: 1.7;
            color: #3d5c45;
            font-weight: 500;
        }

        .modal-point-text strong {
            color: #1a2e1f;
            font-weight: 700;
        }

        .modal-disclaimer {
            font-size: 0.8125rem;
            line-height: 1.7;
            color: #7fa98a;
            font-weight: 500;
            padding-bottom: 0.25rem;
        }

        .modal-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.75rem;
            border-top: 1px solid #e8f5ec;
            background: #fafffe;
            gap: 1rem;
        }

        .modal-footer-link {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #1b6b3a;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .modal-footer-link:hover {
            color: #155730;
            transform: translateX(2px);
        }

        .modal-agree-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.75rem;
            border-radius: 14px;
            background: linear-gradient(135deg, #1b6b3a, #155730);
            color: white;
            border: none;
            font-size: 0.875rem;
            font-weight: 800;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 8px 20px -4px rgba(27, 107, 58, 0.35);
            white-space: nowrap;
        }

        .modal-agree-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(27, 107, 58, 0.45);
            background: linear-gradient(135deg, #22874a, #1b6b3a);
        }

        .modal-agree-btn:active {
            transform: translateY(0);
        }

        /* Blur login content when modal is open */
        .login-wrapper.blurred .login-card {
            filter: blur(4px);
            opacity: 0.4;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .login-wrapper.unblurred .login-card {
            filter: blur(0);
            opacity: 1;
            pointer-events: all;
            transition: all 0.5s ease 0.1s;
        }

        @media (max-width: 480px) {
            .modal-overlay {
                padding: 1rem 0.75rem;
                align-items: center;
                justify-content: center;
            }

            .modal-card {
                border-radius: 20px;
                max-height: 90vh;
            }

            .modal-header {
                padding: 1rem 1.25rem;
            }

            .modal-body {
                padding: 1.25rem;
                max-height: 50vh;
            }

            .modal-footer {
                padding: 1rem 1.25rem;
                flex-direction: column;
            }

            .modal-agree-btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Welcome Modal -->
    <div class="modal-overlay" id="welcomeModal">
        <div class="modal-card">
            <div class="modal-header">
                <div class="modal-header-brand">
                    <div class="modal-header-icon">
                        <i data-lucide="shield-check" style="width:22px;height:22px;"></i>
                    </div>
                    <div class="modal-header-text">
                        <h3>Selamat Datang</h3>
                        <span>Si Pintar &mdash; SMAN 1 Kopang</span>
                    </div>
                </div>
                <button class="modal-close-btn" onclick="closeWelcomeModal()" aria-label="Tutup">
                    <i data-lucide="x" style="width:18px;height:18px;"></i>
                </button>
            </div>

            <div class="modal-body">
                <div class="modal-welcome-title">Selamat Datang di Si Pintar</div>

                <p class="modal-welcome-desc">
                    Selamat datang ke <strong>Sistem Pembinaan Integritas Terpadu (Si Pintar) SMAN 1 Kopang</strong>, “Sehat Karakternya, Pintar Orangnya”. Program ini hadir sebagai partner dan penunjang terbaik dalam membentuk pribadi pelajar yang berintegriti, menyembuhkan tabiat negatif dengan pendekatan ala medis yang humanis, demi mewujudkan generasi yang : <strong>SAMPURNE RAGE ILMU ATE</strong>. Slogan ini bukan sekadar rangkaian kata, melainkan fondasi filosofis yang menopang tiga pilar utama pendidikan di sekolah kami:
                </p>

                <div class="modal-points">
                    <div class="modal-point">
                        <div class="modal-point-dot"></div>
                        <div class="modal-point-text">
                            Penyempurnaan <strong>RAGA</strong> (fisik dan kesehatan)
                        </div>
                    </div>
                    <div class="modal-point">
                        <div class="modal-point-dot"></div>
                        <div class="modal-point-text">
                            Pendalaman <strong>ILMU</strong> (pengetahuan dan kompetensi)
                        </div>
                    </div>
                    <div class="modal-point">
                        <div class="modal-point-dot"></div>
                        <div class="modal-point-text">
                            Penataan <strong>ATE</strong> (hati, karakter, dan akhlak)
                        </div>
                    </div>
                </div>

                <p class="modal-disclaimer">
                    Dengan melanjutkan penggunaan layanan ini, Anda dianggap telah memahami dan menyetujui seluruh
                    ketentuan yang berlaku di <strong>SMAN 1 Kopang</strong>.
                </p>
            </div>

            <div class="modal-footer">
                <a href="{{ url('/') }}" class="modal-footer-link">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i>
                    Kembali ke Beranda
                </a>
                <button class="modal-agree-btn" onclick="closeWelcomeModal()">
                    Saya Setuju & Lanjutkan
                    <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="login-wrapper blurred" id="loginWrapper">
        <div class="sparkle" style="top: 10%; left: 10%; width: 4px; height: 4px; --d: 3s;"></div>
        <div class="sparkle" style="top: 20%; right: 15%; width: 6px; height: 6px; --d: 4s;"></div>
        <div class="sparkle" style="bottom: 15%; left: 20%; width: 5px; height: 5px; --d: 5s;"></div>
        <div class="sparkle" style="top: 50%; right: 5%; width: 3px; height: 3px; --d: 2s;"></div>

        <div class="login-card">
            <div class="header">
                <div class="banner-logo">
                    <img src="{{ asset('logo_sipintar.png') }}" alt="Logo" class="logo-img">
                </div>
                <p class="motto-text" style="margin-bottom: 0.25rem;">Sistem Pembinaan Integritas Terpadu</p>
                <p class="motto-text" style="margin-bottom: 1rem; color: #1B6B3A;">“Sehat Karakternya, Pintar Orangnya”</p>

                <div class="school-badge" style="margin-bottom: 0.5rem;">
                    <img src="{{ asset('logo.png') }}" alt="Logo">
                    SMA NEGERI 1 KOPANG
                </div>
                <p class="motto-text">Sampurne Rage Ilmu Ate</p>
            </div>

            {{ $slot }}

            <div class="copyright">
                &copy; {{ date('Y') }} SMA NEGERI 1 KOPANG
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function closeWelcomeModal() {
            const modal = document.getElementById('welcomeModal');
            const wrapper = document.getElementById('loginWrapper');

            modal.classList.add('closing');
            wrapper.classList.remove('blurred');
            wrapper.classList.add('unblurred');

            setTimeout(() => {
                modal.style.display = 'none';
            }, 350);
        }
    </script>
</body>

</html>