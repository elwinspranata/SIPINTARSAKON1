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
            background-color: #f0f7ff;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            background: linear-gradient(135deg, #e0f2fe 0%, #f0f9ff 100%);
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
            max-width: 440px;
            background: #ffffff;
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 25px 50px -12px rgba(43, 94, 167, 0.15),
                        0 0 0 1px rgba(0, 0, 0, 0.02);
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .banner-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1rem;
        }

        .si-pintar-text {
            font-size: 2.5rem;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .si-text { color: #00D26A; } 
        .pintar-text { color: #2B5EA7; } 

        .motto-text {
            font-size: 0.875rem;
            font-weight: 700;
            color: #475569;
            font-style: italic;
            margin-bottom: 1.5rem;
        }

        .school-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #f1f5f9;
            padding: 0.5rem 1rem;
            border-radius: 99px;
            font-size: 0.75rem;
            font-weight: 800;
            color: #2B5EA7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .school-badge img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .copyright {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 700;
            letter-spacing: 0.025em;
        }

        /* Form elements reused from refined design */
        .form-group { margin-bottom: 1.5rem; text-align: left; }
        .label {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.5rem;
            display: block;
            padding-left: 0.25rem;
        }
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute;
            left: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        .custom-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3.25rem;
            border-radius: 16px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            font-size: 0.9375rem;
            font-family: inherit;
            transition: all 0.2s;
            color: #1e293b;
        }
        .custom-input:focus {
            outline: none;
            border-color: #2B5EA7;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(43, 94, 167, 0.1);
        }
        .submit-btn {
            width: 100%;
            padding: 1rem;
            border-radius: 16px;
            background: #2B5EA7;
            color: white;
            border: none;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 20px -5px rgba(43, 94, 167, 0.3);
            margin-top: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }
        .submit-btn:hover {
            background: #1e4a85;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(43, 94, 167, 0.4);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 2.5rem 1.5rem;
                border-radius: 20px;
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
                    <div class="si-pintar-text">
                        <span class="si-text">Si</span> <span class="pintar-text">Pintar</span>
                    </div>
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
