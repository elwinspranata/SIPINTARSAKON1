<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Si Pintar - Sistem Pembinaan Integritas Terpadu SMAN 1 Kopang. Sehat Karakternya, Pintar Orangnya.">
    <title>Si Pintar - Sistem Pembinaan Integritas Terpadu</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #1b6b3a;
            --primary-dark: #0e3d22;
            --primary-light: rgba(27, 107, 58, 0.08);
            --primary-glow: rgba(27, 107, 58, 0.18);
            --accent: #22c55e;
            --accent-light: rgba(34, 197, 94, 0.1);
            --orange: #ff9100;
            --bg: #f0faf3;
            --text: #1a2e1f;
            --text-secondary: #3d5c45;
            --text-muted: #7fa98a;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 3rem;
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(27,107,58,0.06);
            transition: all 0.4s ease;
        }
        .navbar.scrolled {
            padding: 0.75rem 3rem;
            box-shadow: 0 4px 30px rgba(27,107,58,0.08);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 0.75rem;
            text-decoration: none;
        }
        .nav-brand img { height: 40px; width: 40px; object-fit: contain; }
        .nav-brand-text { font-size: 1.25rem; font-weight: 900; letter-spacing: -0.03em; }
        .nav-brand-si { color: #22c55e; }
        .nav-brand-pintar { color: #1b6b3a; }
        .nav-actions { display: flex; align-items: center; gap: 0.75rem; }
        .btn-login {
            padding: 0.625rem 1.5rem; border-radius: 12px;
            font-weight: 700; font-size: 0.875rem; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            text-decoration: none; font-family: inherit;
            background: transparent; border: 2px solid var(--primary);
            color: var(--primary);
        }
        .btn-login:hover { background: var(--primary-light); transform: translateY(-2px); }
        .btn-register {
            padding: 0.625rem 1.5rem; border-radius: 12px;
            font-weight: 700; font-size: 0.875rem; cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
            text-decoration: none; font-family: inherit;
            background: var(--primary); border: 2px solid var(--primary);
            color: white; box-shadow: 0 6px 16px -4px rgba(27,107,58,0.3);
        }
        .btn-register:hover { background: var(--primary-dark); border-color: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 10px 24px -4px rgba(27,107,58,0.4); }

        /* ===== HERO ===== */
        .hero {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 8rem 2rem 4rem;
            position: relative;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(34,197,94,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(58,190,249,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(255,145,0,0.04) 0%, transparent 50%);
        }

        /* Floating orbs */
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.4; pointer-events: none;
            animation: orbFloat 8s ease-in-out infinite;
        }
        .orb-1 { width: 400px; height: 400px; background: rgba(34,197,94,0.2); top: 10%; left: -5%; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: rgba(27,107,58,0.15); bottom: 10%; right: -3%; animation-delay: -3s; }
        .orb-3 { width: 200px; height: 200px; background: rgba(255,145,0,0.12); top: 50%; left: 60%; animation-delay: -5s; }

        @keyframes orbFloat {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -20px) scale(1.05); }
            66% { transform: translate(-20px, 15px) scale(0.95); }
        }

        .hero-inner {
            max-width: 900px; text-align: center;
            position: relative; z-index: 2;
            animation: fadeUp 1s ease-out;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: white; padding: 0.5rem 1.25rem; border-radius: 99px;
            font-size: 0.75rem; font-weight: 800; color: var(--primary);
            text-transform: uppercase; letter-spacing: 0.08em;
            border: 1px solid rgba(27,107,58,0.1);
            box-shadow: 0 4px 20px rgba(27,107,58,0.06);
            margin-bottom: 2rem;
            animation: fadeUp 1s ease-out 0.1s both;
        }
        .hero-badge img { width: 22px; height: 22px; object-fit: contain; }
        .hero-badge-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--accent);
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse { 0%,100% { opacity:1; transform:scale(1); } 50% { opacity:0.5; transform:scale(1.5); } }

        .hero-logo {
            margin-bottom: 1.5rem;
            animation: fadeUp 1s ease-out 0.2s both;
        }
        .hero-logo img {
            height: 120px; width: auto;
            filter: drop-shadow(0 10px 30px rgba(27,107,58,0.15));
        }

        .hero-title {
            font-size: clamp(1.75rem, 4vw, 2.75rem);
            font-weight: 900; letter-spacing: -0.04em;
            line-height: 1.2; margin-bottom: 0.5rem;
            color: var(--text);
            animation: fadeUp 1s ease-out 0.3s both;
        }
        .hero-title-green { color: var(--primary); }

        .hero-motto {
            font-size: 1.125rem; font-weight: 700; font-style: italic;
            color: var(--accent); margin-bottom: 1.75rem;
            animation: fadeUp 1s ease-out 0.4s both;
        }

        .hero-desc {
            font-size: 1.0625rem; line-height: 1.8; color: var(--text-secondary);
            max-width: 700px; margin: 0 auto 2.5rem;
            font-weight: 500;
            animation: fadeUp 1s ease-out 0.5s both;
        }

        .hero-cta {
            display: flex; align-items: center; justify-content: center;
            gap: 1rem; flex-wrap: wrap;
            animation: fadeUp 1s ease-out 0.6s both;
        }
        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: 0.625rem;
            padding: 1rem 2rem; border-radius: 16px;
            background: var(--primary); color: white;
            font-weight: 800; font-size: 1rem;
            text-decoration: none; border: none; cursor: pointer; font-family: inherit;
            box-shadow: 0 10px 30px -6px rgba(27,107,58,0.4);
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
        }
        .btn-hero-primary:hover { background: var(--primary-dark); transform: translateY(-3px); box-shadow: 0 16px 40px -6px rgba(27,107,58,0.5); }
        .btn-hero-secondary {
            display: inline-flex; align-items: center; gap: 0.625rem;
            padding: 1rem 2rem; border-radius: 16px;
            background: white; color: var(--primary);
            font-weight: 800; font-size: 1rem;
            text-decoration: none; border: 2px solid rgba(27,107,58,0.15);
            cursor: pointer; font-family: inherit;
            transition: all 0.3s cubic-bezier(0.16,1,0.3,1);
        }
        .btn-hero-secondary:hover { border-color: var(--primary); background: var(--primary-light); transform: translateY(-3px); }

        /* ===== SAMPURNE Section ===== */
        .sampurne-section {
            padding: 5rem 2rem;
            background: white;
            position: relative;
        }
        .sampurne-section::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--primary), var(--orange));
        }

        .section-inner { max-width: 1000px; margin: 0 auto; }

        .section-header {
            text-align: center; margin-bottom: 3.5rem;
        }
        .section-tag {
            display: inline-flex; align-items: center; gap: 0.5rem;
            background: var(--accent-light); color: var(--accent);
            padding: 0.375rem 1rem; border-radius: 99px;
            font-size: 0.6875rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.1em;
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: clamp(1.5rem, 3vw, 2.25rem);
            font-weight: 900; letter-spacing: -0.03em;
            color: var(--text); margin-bottom: 0.75rem;
        }
        .section-subtitle {
            font-size: 1rem; color: var(--text-muted); font-weight: 500;
            max-width: 600px; margin: 0 auto;
        }

        .sampurne-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
        }
        .sampurne-card {
            background: var(--bg);
            border-radius: 20px; padding: 1.75rem 1.5rem;
            border: 1px solid rgba(27,107,58,0.06);
            transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
            text-align: center; position: relative; overflow: hidden;
        }
        .sampurne-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(27,107,58,0.12);
            border-color: rgba(27,107,58,0.15);
        }
        .sampurne-icon {
            width: 56px; height: 56px; border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem; font-size: 1.25rem;
            transition: all 0.3s ease;
        }
        .sampurne-card:hover .sampurne-icon { transform: scale(1.1) rotate(5deg); }
        .sampurne-letter {
            font-size: 1.75rem; font-weight: 900; color: var(--primary);
            margin-bottom: 0.25rem; letter-spacing: -0.02em;
        }
        .sampurne-word {
            font-size: 0.8125rem; font-weight: 800; color: var(--text);
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }
        .sampurne-desc {
            font-size: 0.8125rem; color: var(--text-muted); font-weight: 500;
            line-height: 1.6;
        }

        /* ===== FEATURES ===== */
        .features-section {
            padding: 5rem 2rem;
            background:
                radial-gradient(ellipse at 0% 50%, rgba(34,197,94,0.04) 0%, transparent 50%),
                var(--bg);
        }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .feature-card {
            background: white; border-radius: 20px;
            padding: 2rem 1.75rem;
            border: 1px solid rgba(27,107,58,0.06);
            transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
            position: relative; overflow: hidden;
        }
        .feature-card::after {
            content: ''; position: absolute; top: 0; left: 0; right: 0;
            height: 3px; opacity: 0; transition: opacity 0.3s;
        }
        .feature-card:hover::after { opacity: 1; }
        .feature-card:nth-child(1)::after { background: linear-gradient(90deg, var(--accent), var(--primary)); }
        .feature-card:nth-child(2)::after { background: linear-gradient(90deg, var(--orange), #f59e0b); }
        .feature-card:nth-child(3)::after { background: linear-gradient(90deg, #3abef9, #6366f1); }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px -12px rgba(27,107,58,0.1);
        }
        .feature-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
        }
        .feature-title {
            font-size: 1.0625rem; font-weight: 800;
            margin-bottom: 0.5rem; color: var(--text);
        }
        .feature-desc {
            font-size: 0.875rem; color: var(--text-muted);
            line-height: 1.7; font-weight: 500;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--primary-dark); color: white;
            padding: 3rem 2rem; text-align: center;
        }
        .footer-brand {
            display: flex; align-items: center; justify-content: center;
            gap: 0.75rem; margin-bottom: 1rem;
        }
        .footer-brand img { height: 36px; width: 36px; object-fit: contain; filter: brightness(10); }
        .footer-brand-text { font-size: 1.125rem; font-weight: 900; letter-spacing: -0.03em; }
        .footer-motto {
            font-size: 0.8125rem; font-weight: 600; font-style: italic;
            color: rgba(255,255,255,0.6); margin-bottom: 1.5rem;
        }
        .footer-copy {
            font-size: 0.75rem; color: rgba(255,255,255,0.4); font-weight: 600;
        }

        /* ===== Scroll Reveal ===== */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.16,1,0.3,1);
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar { padding: 0.75rem 1.25rem; }
            .navbar.scrolled { padding: 0.625rem 1.25rem; }
            .nav-brand-text { display: none; }
            .btn-login, .btn-register { padding: 0.5rem 1rem; font-size: 0.8125rem; }
            .hero { padding: 7rem 1.25rem 3rem; }
            .hero-logo img { height: 90px; }
            .hero-desc { font-size: 0.9375rem; }
            .sampurne-grid { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .sampurne-grid { grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .sampurne-card { padding: 1.25rem 1rem; }
            .hero-cta { flex-direction: column; }
            .btn-hero-primary, .btn-hero-secondary { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar" id="navbar">
    <a href="/" class="nav-brand">
        <img src="{{ asset('logo_sipintar.png') }}" alt="Logo Si Pintar">
        <span class="nav-brand-text">
            <span class="nav-brand-si">SI</span> <span class="nav-brand-pintar">PINTAR</span>
        </span>
    </a>
    <div class="nav-actions">
        <a href="{{ route('login') }}" class="btn-login">Masuk</a>
        <a href="{{ route('register') }}" class="btn-register">Daftar</a>
    </div>
</nav>

<!-- Hero -->
<section class="hero" id="hero">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="hero-inner">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            <img src="{{ asset('logo_sekolah.jpg') }}" alt="Logo Sekolah">
            SMA Negeri 1 Kopang
        </div>

        <div class="hero-logo">
            <img src="{{ asset('logo_sipintar.png') }}" alt="Logo Si Pintar">
        </div>

        <h1 class="hero-title">
            Selamat Datang ke<br>
            <span class="hero-title-green">Sistem Pembinaan Integritas Terpadu</span>
        </h1>

        <p class="hero-motto">"Sehat Karakternya, Pintar Orangnya"</p>

        <p class="hero-desc">
            Program ini hadir sebagai partner dan penunjang terbaik dalam membentuk pribadi pelajar yang berintegritas, 
            menyembuhkan tabiat negatif dengan pendekatan ala medis yang humanis, demi mewujudkan generasi yang 
            <strong style="color: var(--primary); font-weight: 800;">SAMPURNE RAGE ILMU ATE</strong>.
        </p>

        <div class="hero-cta">
            <a href="{{ route('login') }}" class="btn-hero-primary">
                <i data-lucide="log-in" style="width:20px;height:20px;"></i>
                Masuk ke Sistem
            </a>
            <a href="{{ route('register') }}" class="btn-hero-secondary">
                <i data-lucide="user-plus" style="width:20px;height:20px;"></i>
                Daftar Akun Baru
            </a>
        </div>
    </div>
</section>

<!-- SAMPURNE RAGE ILMU ATE -->
<section class="sampurne-section" id="sampurne">
    <div class="section-inner">
        <div class="section-header reveal">
            <div class="section-tag">
                <i data-lucide="sparkles" style="width:14px;height:14px;"></i>
                Nilai-Nilai Kami
            </div>
            <h2 class="section-title">SAMPURNE RAGE ILMU ATE</h2>
            <p class="section-subtitle">Generasi unggul yang kami cita-citakan memegang teguh nilai-nilai luhur berikut</p>
        </div>

        <div class="sampurne-grid">
            <div class="sampurne-card reveal">
                <div class="sampurne-icon" style="background: rgba(34,197,94,0.1); color: #22c55e;">
                    <i data-lucide="sparkles" style="width:24px;height:24px;"></i>
                </div>
                <div class="sampurne-letter">S</div>
                <div class="sampurne-word">Sampurne</div>
                <div class="sampurne-desc">Sempurna dalam akhlak dan budi pekerti</div>
            </div>
            <div class="sampurne-card reveal">
                <div class="sampurne-icon" style="background: rgba(255,145,0,0.1); color: #ff9100;">
                    <i data-lucide="flame" style="width:24px;height:24px;"></i>
                </div>
                <div class="sampurne-letter">R</div>
                <div class="sampurne-word">Rage</div>
                <div class="sampurne-desc">Semangat yang berkobar dalam menuntut ilmu</div>
            </div>
            <div class="sampurne-card reveal">
                <div class="sampurne-icon" style="background: rgba(58,190,249,0.1); color: #3abef9;">
                    <i data-lucide="book-open" style="width:24px;height:24px;"></i>
                </div>
                <div class="sampurne-letter">I</div>
                <div class="sampurne-word">Ilmu</div>
                <div class="sampurne-desc">Berilmu pengetahuan yang luas dan bermanfaat</div>
            </div>
            <div class="sampurne-card reveal">
                <div class="sampurne-icon" style="background: rgba(239,68,68,0.1); color: #ef4444;">
                    <i data-lucide="heart" style="width:24px;height:24px;"></i>
                </div>
                <div class="sampurne-letter">A</div>
                <div class="sampurne-word">Ate</div>
                <div class="sampurne-desc">Berjiwa hati yang bersih dan penuh empati</div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features-section" id="features">
    <div class="section-inner">
        <div class="section-header reveal">
            <div class="section-tag" style="background: rgba(27,107,58,0.08); color: var(--primary);">
                <i data-lucide="zap" style="width:14px;height:14px;"></i>
                Fitur Unggulan
            </div>
            <h2 class="section-title">Pendekatan Medis yang Humanis</h2>
            <p class="section-subtitle">Si Pintar menggunakan analogi kesehatan untuk membina karakter pelajar secara efektif</p>
        </div>

        <div class="features-grid">
            <div class="feature-card reveal">
                <div class="feature-icon" style="background: rgba(34,197,94,0.1); color: #22c55e;">
                    <i data-lucide="stethoscope" style="width:24px;height:24px;"></i>
                </div>
                <div class="feature-title">Rekam Penyakit</div>
                <div class="feature-desc">Catat setiap pelanggaran sebagai "penyakit" yang perlu disembuhkan dengan pendekatan yang tepat dan terukur.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon" style="background: rgba(255,145,0,0.1); color: #ff9100;">
                    <i data-lucide="pill" style="width:24px;height:24px;"></i>
                </div>
                <div class="feature-title">Berikan Vitamin</div>
                <div class="feature-desc">Apresiasi prestasi sebagai "vitamin" yang memperkuat karakter pelajar dan memotivasi perilaku positif.</div>
            </div>
            <div class="feature-card reveal">
                <div class="feature-icon" style="background: rgba(58,190,249,0.1); color: #3abef9;">
                    <i data-lucide="bar-chart-3" style="width:24px;height:24px;"></i>
                </div>
                <div class="feature-title">Rekap & Monitoring</div>
                <div class="feature-desc">Pantau perkembangan karakter setiap pelajar melalui dashboard data yang komprehensif dan real-time.</div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-brand">
        <img src="{{ asset('logo_sipintar.png') }}" alt="Logo">
        <span class="footer-brand-text">SI PINTAR</span>
    </div>
    <p class="footer-motto">"Sehat Karakternya, Pintar Orangnya"</p>
    <p class="footer-copy">&copy; {{ date('Y') }} SMA Negeri 1 Kopang &mdash; Sistem Pembinaan Integritas Terpadu</p>
</footer>

<script>
    lucide.createIcons();

    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        document.getElementById('navbar').classList.toggle('scrolled', window.scrollY > 50);
    });

    // Scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>

</body>
</html>
