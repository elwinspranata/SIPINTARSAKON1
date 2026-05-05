<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Si Pintar') }} - Login</title>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            background-color: #f0faf3;
            color: #1a2e1f;
            -webkit-font-smoothing: antialiased;
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
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.5); opacity: 0.8; }
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
            margin-bottom: 0.25rem;
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
        .form-group { margin-bottom: 1.125rem; text-align: left; }
        .label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #1a2e1f;
            margin-bottom: 0.375rem;
            display: block;
            padding-left: 0.25rem;
        }
        .input-wrapper { position: relative; }
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
        .content-header { text-align: center; margin-bottom: 2rem; }
        .content-title { font-size: 1.5rem; font-weight: 800; color: #1a2e1f; margin-bottom: 0.5rem; letter-spacing: -0.02em; }
        .content-subtitle { font-size: 0.875rem; color: #7fa98a; line-height: 1.5; font-weight: 500; }
        
        .form-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.75rem; font-weight: 700; color: #1a2e1f; margin-bottom: 0.5rem; padding-left: 0.25rem; }
        .input-control { width: 100%; padding: 0.875rem 1.125rem; border-radius: 12px; border: 2px solid #e8f5ec; background: #f8fafc; font-size: 0.9375rem; transition: all 0.2s; color: #1a2e1f; font-family: inherit; }
        .input-control:focus { outline: none; border-color: #1B6B3A; background: #fff; box-shadow: 0 0 0 4px rgba(27, 107, 58, 0.08); }
        
        .btn-submit { width: 100%; padding: 1rem; border-radius: 12px; background: #1B6B3A; color: white; border: none; font-size: 0.9375rem; font-weight: 800; cursor: pointer; transition: all 0.3s; box-shadow: 0 8px 16px -5px rgba(27, 107, 58, 0.25); display: flex; align-items: center; justify-content: center; gap: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 1rem; }
        .btn-submit:hover { background: #155730; transform: translateY(-1px); box-shadow: 0 12px 24px -5px rgba(27, 107, 58, 0.35); }
        
        .footer-link { margin-top: 2rem; text-align: center; }
        .footer-link a { display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: #1B6B3A; font-weight: 700; text-decoration: none; transition: all 0.2s; }
        .footer-link a:hover { color: #155730; transform: translateX(-3px); }

        @media (max-width: 480px) {
            .login-card {
                padding: 2rem 1.25rem;
                border-radius: 16px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="sparkle" style="top: 10%; left: 10%; width: 4px; height: 4px; --d: 3s;"></div>
        <div class="sparkle" style="top: 20%; right: 15%; width: 6px; height: 6px; --d: 4s;"></div>
        <div class="sparkle" style="bottom: 15%; left: 20%; width: 5px; height: 5px; --d: 5s;"></div>
        <div class="sparkle" style="top: 50%; right: 5%; width: 3px; height: 3px; --d: 2s;"></div>

        <div class="login-card">
            <div class="header">
                <div class="banner-logo">
                    <img src="{{ asset('logo_sipintar.png') }}" alt="Logo" class="logo-img">
                </div>
                <p class="motto-text">Sehat Karakternya, Pintar Orangnya</p>
                
                <div class="school-badge">
                    <img src="{{ asset('logo.png') }}" alt="Logo">
                    SMA NEGERI 1 KOPANG
                </div>
            </div>

            {{ $slot }}

            <div class="copyright">
                &copy; {{ date('Y') }} SMA NEGERI 1 KOPANG
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
