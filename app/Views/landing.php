<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<!-- Load Outfit font specifically for Landing Page only -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="landing-page min-h-screen flex flex-col bg-slate-50 text-slate-900 selection:bg-[#5e0078] selection:text-white">



    <!-- Top Announcement Bar (Dinamis dari Pengaturan Super Admin) -->
    <?php 
    $announcementActive = ($settings['announcement_active'] ?? '1') === '1';
    $showBadge = ($settings['announcement_show_badge'] ?? '1') === '1';
    $showLink = ($settings['announcement_show_link'] ?? '1') === '1';
    $badgeText = $settings['announcement_badge_text'] ?? 'Resmi & Terpercaya';
    $announcementText = $settings['announcement_text'] ?? ($settings['site_announcement'] ?? 'Pendaftaran Umroh & Haji Musim 1448 H Telah Dibuka. Kuota Terbatas!');
    $linkText = $settings['announcement_link_text'] ?? 'Lihat Jadwal →';
    $linkUrl = $settings['announcement_link_url'] ?? base_url('/catalog');
    if (empty($linkUrl)) {
        $linkUrl = base_url('/catalog');
    }
    $targetBlank = ($settings['announcement_link_target_blank'] ?? '0') === '1';
    ?>
    <?php if ($announcementActive && (!empty($announcementText) || ($showBadge && !empty($badgeText)))): ?>
    <div id="topAnnouncementBar" class="bg-[#3e004f] text-purple-100 text-xs py-2 px-4 text-center font-medium border-b border-purple-900/50 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-center gap-2 sm:gap-3">
            <?php if ($showBadge && !empty($badgeText)): ?>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#5e0078] text-white text-[11px] font-semibold tracking-wide shadow-xs">
                <i class="fas fa-star text-amber-300 text-[10px]"></i> <?= esc($badgeText) ?>
            </span>
            <?php endif; ?>

            <?php if (!empty($announcementText)): ?>
            <span class="leading-relaxed"><?= esc($announcementText) ?></span>
            <?php endif; ?>

            <?php if ($showLink && !empty($linkText) && !empty($linkUrl)): ?>
            <a href="<?= esc($linkUrl, 'attr') ?>" <?= $targetBlank ? 'target="_blank" rel="noopener noreferrer"' : '' ?> class="underline hover:text-white font-semibold ml-1 transition-colors">
                <?= esc($linkText) ?>
            </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Navigation Header (Royal Purple Glassmorphism, Smooth Animations & Micro-Interactions) -->
    <header id="mainHeader" class="sticky top-0 z-40 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400 rounded-xl" title="Amanah Safar">
                    <img src="<?= base_url('assets/images/logo-white.png') ?>" alt="Amanah Safar" class="h-8 sm:h-9 w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-sm">
                </a>

                <!-- Desktop Menu -->
                <nav id="desktopNav" class="hidden md:flex items-center gap-7 lg:gap-8 text-sm font-medium">
                    <a href="#hero" class="nav-link-item active">Beranda</a>
                    <a href="#keunggulan" class="nav-link-item">Keunggulan</a>
                    <a href="#program" class="nav-link-item">Paket Program</a>
                    <a href="#fasilitas" class="nav-link-item">Fasilitas</a>
                    <a href="#testimoni" class="nav-link-item">Testimoni</a>
                    <a href="#faq" class="nav-link-item">FAQ</a>
                </nav>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <?php if (session('logged_in')): ?>
                        <a href="<?= base_url('/dashboard') ?>" class="btn-nav-register flex items-center gap-2">
                            <i class="fas fa-columns text-xs"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" class="btn-nav-login">
                            Masuk
                        </a>
                        <a href="<?= base_url('/register') ?>" class="btn-nav-register">
                            Daftar Mitra
                        </a>
                    <?php endif; ?>

                    <!-- Mobile Menu Button (md:hidden) -->
                    <button id="mobileMenuBtn" type="button" aria-label="Toggle Navigation Menu" aria-expanded="false" 
                            class="md:hidden w-9 h-9 rounded-xl text-white/90 hover:text-amber-300 hover:bg-white/10 flex items-center justify-center transition-colors duration-300 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-amber-400">
                        <i id="mobileMenuIcon" class="fas fa-bars text-lg"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Drawer -->
        <div id="mobileMenuDrawer" class="hidden md:hidden border-t border-purple-800/40 bg-[#240030] backdrop-blur-xl px-4 py-3 space-y-1 shadow-2xl transition-all duration-300">
            <a href="#hero" class="mobile-nav-link active block py-2.5 px-3.5 rounded-xl text-sm font-medium transition-colors duration-300">Beranda</a>
            <a href="#keunggulan" class="mobile-nav-link block py-2.5 px-3.5 rounded-xl text-sm font-medium transition-colors duration-300">Keunggulan</a>
            <a href="#program" class="mobile-nav-link block py-2.5 px-3.5 rounded-xl text-sm font-medium transition-colors duration-300">Paket Program</a>
            <a href="#fasilitas" class="mobile-nav-link block py-2.5 px-3.5 rounded-xl text-sm font-medium transition-colors duration-300">Fasilitas</a>
            <a href="#testimoni" class="mobile-nav-link block py-2.5 px-3.5 rounded-xl text-sm font-medium transition-colors duration-300">Testimoni</a>
            <a href="#faq" class="mobile-nav-link block py-2.5 px-3.5 rounded-xl text-sm font-medium transition-colors duration-300">FAQ</a>
        </div>
    </header>

    <!-- Scoped Typography: Outfit Font for Landing Page Only -->
    <style>
        html {
            scroll-behavior: smooth;
        }

        .landing-page,
        .landing-page button,
        .landing-page input,
        .landing-page select,
        .landing-page textarea {
            font-family: "Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        }
        .landing-page h1,
        .landing-page h2,
        .landing-page h3,
        .landing-page h4,
        .landing-page h5,
        .landing-page h6 {
            font-family: "Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
            font-weight: 700;
        }
        .landing-page nav a,
        .landing-page button,
        .landing-page a.btn-gold-royal,
        .landing-page .font-semibold,
        .landing-page .font-bold {
            font-family: "Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
        }
        .landing-page p,
        .landing-page span,
        .landing-page li {
            font-family: "Outfit", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        /* ========================================================
           HEADER REDESIGN: ROYAL PURPLE GLASSMORPHISM & SMOOTH SCROLL
           ======================================================== */
        #mainHeader {
            /* Kondisi Sebelum Scroll: Ungu Footer Transparan ~82% + Backdrop Blur Halus */
            background-color: rgba(36, 0, 48, 0.82);
            backdrop-filter: blur(14px) saturate(180%);
            -webkit-backdrop-filter: blur(14px) saturate(180%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.18), 0 2px 8px -2px rgba(36, 0, 48, 0.35);
            transition: background-color 300ms cubic-bezier(0.22, 1, 0.36, 1),
                        box-shadow 300ms cubic-bezier(0.22, 1, 0.36, 1),
                        border-color 300ms cubic-bezier(0.22, 1, 0.36, 1),
                        backdrop-filter 300ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Kondisi Setelah Halaman Di-scroll: Ungu Gelap Sama Seperti Background Footer (#240030) */
        #mainHeader.scrolled {
            background-color: #240030;
            backdrop-filter: blur(16px) saturate(190%);
            -webkit-backdrop-filter: blur(16px) saturate(190%);
            border-bottom: 1px solid rgba(212, 175, 55, 0.22);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.40), 0 4px 16px -2px rgba(36, 0, 48, 0.50);
        }

        /* Nav Item (Text & Animated Underline) */
        .nav-link-item {
            position: relative;
            display: inline-flex;
            align-items: center;
            padding-top: 0.375rem;
            padding-bottom: 0.375rem;
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.875rem;
            font-weight: 500;
            letter-spacing: 0.01em;
            transition: color 280ms cubic-bezier(0.22, 1, 0.36, 1);
            outline: none;
            text-decoration: none;
        }

        .nav-link-item::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 2.5px;
            background: linear-gradient(90deg, #d4af37, #f6cf57, #d4af37);
            border-radius: 9999px;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 300ms cubic-bezier(0.22, 1, 0.36, 1),
                        opacity 260ms ease-out;
            opacity: 0;
            box-shadow: 0 0 8px rgba(246, 207, 87, 0.6);
            pointer-events: none;
        }

        /* Hover State */
        .nav-link-item:hover {
            color: #ffffff;
        }

        .nav-link-item:hover::after {
            transform: scaleX(1);
            opacity: 0.85;
        }

        /* Active State */
        .nav-link-item.active {
            color: #fce79a;
            font-weight: 600;
        }

        .nav-link-item.active::after {
            transform: scaleX(1);
            opacity: 1;
        }

        .nav-link-item:focus-visible {
            outline: 2px solid #f6cf57;
            outline-offset: 4px;
            border-radius: 0.25rem;
        }

        /* Mobile Nav Link */
        .mobile-nav-link {
            color: rgba(255, 255, 255, 0.85);
            background-color: transparent;
        }
        .mobile-nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.08);
        }
        .mobile-nav-link.active {
            background-color: rgba(94, 0, 120, 0.50);
            color: #fce79a;
            font-weight: 600;
            border-left: 3px solid #f6cf57;
        }

        /* Action Button: Masuk */
        .btn-nav-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
            border-radius: 0.75rem;
            background-color: rgba(255, 255, 255, 0.10);
            border: 1px solid rgba(255, 255, 255, 0.20);
            backdrop-filter: blur(8px);
            transition: color 260ms cubic-bezier(0.22, 1, 0.36, 1),
                        background-color 260ms cubic-bezier(0.22, 1, 0.36, 1),
                        border-color 260ms cubic-bezier(0.22, 1, 0.36, 1),
                        transform 150ms ease-out;
            outline: none;
            text-decoration: none;
        }

        .btn-nav-login:hover {
            color: #fce79a;
            background-color: rgba(255, 255, 255, 0.18);
            border-color: rgba(246, 207, 87, 0.40);
            transform: translateY(-1px);
        }

        .btn-nav-login:active {
            transform: scale(0.97) translateY(0);
        }

        .btn-nav-login:focus-visible {
            outline: 2px solid #f6cf57;
            outline-offset: 2px;
        }

        /* Action Button: Daftar Mitra (CTA Putih Solid Elegan & Mewah) */
        .btn-nav-register {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.45rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 700;
            letter-spacing: 0.015em;
            color: #240030;
            background-color: #ffffff;
            border: 1px solid #ffffff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 14px -2px rgba(0, 0, 0, 0.25), 0 2px 6px rgba(255, 255, 255, 0.20);
            transition: background-color 260ms ease,
                        color 260ms ease,
                        box-shadow 260ms cubic-bezier(0.22, 1, 0.36, 1),
                        border-color 260ms ease,
                        transform 150ms ease-out;
            outline: none;
            text-decoration: none;
        }

        .btn-nav-register:hover {
            background-color: #faf5ff;
            color: #5e0078;
            border-color: rgba(246, 207, 87, 0.60);
            box-shadow: 0 6px 20px -3px rgba(255, 255, 255, 0.40), 0 0 12px rgba(212, 175, 55, 0.30);
            transform: translateY(-1.5px);
        }

        .btn-nav-register:active {
            transform: scale(0.97) translateY(0);
        }

        .btn-nav-register:focus-visible {
            outline: 2px solid #f6cf57;
            outline-offset: 2px;
        }

        /* FAQ Accordion Smooth Motion */
        .faq-item {
            transition: border-color 200ms ease, box-shadow 200ms ease;
        }

        .faq-panel {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 280ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .faq-panel > div {
            overflow: hidden;
            min-height: 0;
        }

        .faq-content-inner {
            opacity: 0;
            transition: opacity 180ms ease-out;
        }

        .faq-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: transform 280ms cubic-bezier(0.22, 1, 0.36, 1), opacity 200ms ease;
            transform-origin: center;
        }

        /* FAQ Open State */
        .faq-item.is-open .faq-panel {
            grid-template-rows: 1fr;
            transition: grid-template-rows 320ms cubic-bezier(0.22, 1, 0.36, 1);
        }

        .faq-item.is-open .faq-content-inner {
            opacity: 1;
            transition: opacity 220ms ease-out 40ms;
        }

        .faq-item.is-open .faq-icon {
            transform: rotate(180deg);
        }

        /* Accessibility: Respect Reduced Motion Preferences */
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto !important;
            }
            .nav-link-item,
            .nav-link-item::after,
            .btn-nav-login,
            .btn-nav-register,
            #mainHeader,
            .faq-panel,
            .faq-content-inner,
            .faq-icon,
            .faq-item {
                transition: none !important;
                animation: none !important;
            }
        }

        .hero-pattern {
            background-color: #2b0038;
            background-image: radial-gradient(rgba(212, 175, 55, 0.15) 1px, transparent 0), radial-gradient(rgba(255, 255, 255, 0.08) 1px, #2b0038 0);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }
        .gold-badge {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.25), rgba(197, 155, 39, 0.1));
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #d4af37;
        }
        .btn-gold-royal {
            background: linear-gradient(135deg, #d4af37, #c59b27 50%, #9e7418);
            color: #ffffff;
            box-shadow: 0 10px 25px -8px rgba(212, 175, 55, 0.6);
            transition: all var(--dur-fast, 160ms) var(--ease-out, ease-out);
        }
        .btn-gold-royal:hover {
            background: linear-gradient(135deg, #e3c048, #d2a731 50%, #af8220);
            transform: translateY(-1.5px);
            box-shadow: 0 14px 28px -8px rgba(212, 175, 55, 0.75);
        }
        .btn-gold-royal:active {
            transform: scale(0.98) translateY(0);
        }
        .luxury-card {
            background: #ffffff;
            border: 1px solid #f1e6f5;
            box-shadow: 0 10px 30px -15px rgba(94, 0, 120, 0.08);
            transition: transform var(--dur-normal, 220ms) var(--ease-out, ease-out), 
                        border-color var(--dur-normal, 220ms) var(--ease-out, ease-out), 
                        box-shadow var(--dur-normal, 220ms) var(--ease-out, ease-out);
        }
        .luxury-card:hover {
            transform: translateY(-3px);
            border-color: #dfb2f1;
            box-shadow: 0 20px 35px -15px rgba(94, 0, 120, 0.14);
        }
        .brand-gradient-text {
            background: linear-gradient(135deg, #5e0078 0%, #8f20ad 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Hero Slider Full Viewport Height 100vh */
        .hero-slider-100vh {
            height: calc(100vh - 4rem);
            height: calc(100dvh - 4rem);
            min-height: 560px;
        }
        @media (max-width: 639.98px) {
            .hero-slider-100vh {
                height: calc(100vh - 4rem);
                height: calc(100dvh - 4rem);
                min-height: 520px;
            }
        }
    </style>

    <!-- SECTION 1: HERO SLIDER CAROUSEL (Dinamis dari Database Hero Slides - 100vh Full Viewport) -->
    <section id="hero" class="relative overflow-hidden bg-slate-950 text-white scroll-mt-20">
        <!-- Carousel Container (100vh Viewport Responsive) -->
        <div class="relative w-full overflow-hidden hero-slider-100vh" id="heroSlider" style="height: calc(100vh - 4rem); height: calc(100dvh - 4rem); min-height: 560px;">
            
            <?php 
            $slideList = !empty($slides) ? $slides : [
                [
                    'image_url' => 'https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?auto=format&fit=crop&w=1920&q=85',
                    'badge_text' => 'PPIU Resmi Kemenag RI',
                    'title' => 'Wujudkan Ibadah Khusyuk & Terencana',
                    'subtitle' => 'Bersama Keluarga Tercinta',
                    'description' => 'Hotel bintang 5 sejarak langkah kaki ke Masjidil Haram, pembimbing berilmu, dan kepastian fasilitas tanpa rasa cemas.',
                    'cta_primary_text' => 'Konsultasi WhatsApp',
                    'cta_primary_url' => 'https://wa.me/6281234567890',
                    'cta_secondary_text' => 'Jelajahi Paket',
                    'cta_secondary_url' => '#program',
                ]
            ];
            foreach ($slideList as $index => $slide): 
                $isFirst = ($index === 0);
            ?>
                <!-- Slide <?= $index + 1 ?> -->
                <div class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-in-out <?= $isFirst ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none' ?>">
                    <?php 
                    $isVideo = (!empty($slide['media_type']) && $slide['media_type'] === 'video') || preg_match('/\.(mp4|webm|ogg|mov)$/i', $slide['image_url']);
                    ?>
                    <?php if ($isVideo): ?>
                        <video src="<?= esc($slide['image_url']) ?>" 
                               autoplay muted loop playsinline
                               class="w-full h-full object-cover object-center filter brightness-[0.70]"></video>
                    <?php else: ?>
                        <?php 
                        $slideImg = $slide['image_url'];
                        $slideWebp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $slideImg);
                        $slideWebpExists = ($slideWebp !== $slideImg && (str_contains($slideImg, 'uploads/slides/') ? file_exists(FCPATH . 'uploads/slides/' . basename($slideWebp)) : false));
                        ?>
                        <picture>
                            <?php if ($slideWebpExists): ?>
                                <source srcset="<?= esc($slideWebp) ?>" type="image/webp">
                            <?php endif; ?>
                            <img src="<?= esc($slideImg) ?>" 
                                 alt="<?= esc($slide['title']) ?>" 
                                 width="1920" height="1080"
                                 <?= $isFirst ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                                 decoding="async"
                                 class="w-full h-full object-cover object-center filter brightness-[0.70] transform scale-100 transition-transform duration-[7000ms]">
                        </picture>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-black/30"></div>
                    <div class="absolute inset-0 flex items-center">
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                            <div class="max-w-2xl space-y-4 text-left">
                                <?php if (!empty($slide['badge_text'])): ?>
                                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-400/20 backdrop-blur-md border border-amber-300/40 text-amber-300 text-xs font-bold uppercase tracking-wider">
                                        <i class="fas fa-certificate text-amber-300"></i> <?= esc($slide['badge_text']) ?>
                                    </span>
                                <?php endif; ?>
                                
                                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-tight drop-shadow-md">
                                    <?= esc($slide['title']) ?>
                                    <?php if (!empty($slide['subtitle'])): ?>
                                        <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-300 via-amber-200 to-amber-400"><?= esc($slide['subtitle']) ?></span>
                                    <?php endif; ?>
                                </h1>
                                
                                <?php if (!empty($slide['description'])): ?>
                                    <p class="text-sm sm:text-lg text-purple-100/90 leading-relaxed font-light drop-shadow">
                                        <?= esc($slide['description']) ?>
                                    </p>
                                <?php endif; ?>

                                <div class="pt-3 flex flex-wrap items-center gap-3">
                                    <?php if (!empty($slide['cta_primary_text'])): ?>
                                        <a href="<?= esc($slide['cta_primary_url'] ?: '#') ?>" target="<?= strpos($slide['cta_primary_url'], 'http') === 0 ? '_blank' : '_self' ?>" rel="noopener noreferrer" 
                                           class="btn-gold-royal inline-flex items-center justify-center gap-2 h-12 px-6 rounded-xl font-bold text-sm shadow-lg border border-transparent transition">
                                            <?php if (strpos($slide['cta_primary_url'], 'wa.me') !== false): ?>
                                                <i class="fab fa-whatsapp text-base"></i>
                                            <?php endif; ?>
                                            <span><?= esc($slide['cta_primary_text']) ?></span>
                                        </a>
                                    <?php endif; ?>

                                    <?php if (!empty($slide['cta_secondary_text'])): ?>
                                        <a href="<?= esc($slide['cta_secondary_url'] ?: '#') ?>" 
                                           class="inline-flex items-center justify-center gap-2 h-12 px-6 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-sm backdrop-blur-md border border-white/25 transition">
                                            <span><?= esc($slide['cta_secondary_text']) ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Slide Controls (Prev / Next Buttons) -->
            <button type="button" onclick="changeHeroSlide(-1)" aria-label="Slide Sebelumnya" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition hover:scale-105">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button type="button" onclick="changeHeroSlide(1)" aria-label="Slide Selanjutnya" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-11 h-11 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition hover:scale-105">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>

            <!-- Slide Indicators (Bullets) -->
            <div class="absolute bottom-8 sm:bottom-10 left-0 right-0 z-20 flex justify-center items-center gap-2.5">
                <?php foreach ($slideList as $index => $s): ?>
                    <button type="button" onclick="setHeroSlide(<?= $index ?>)" 
                            class="hero-dot <?= $index === 0 ? 'w-9 h-2 bg-amber-400' : 'w-2.5 h-2 bg-white/40 hover:bg-white/70' ?> rounded-full transition-all duration-300" 
                            aria-label="Slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- Script Slider Logic Dinamis dengan Pengaturan Detik Super Admin -->
        <script>
            let currentHeroSlideIndex = 0;
            const heroSlides = document.querySelectorAll('.hero-slide');
            const heroDots = document.querySelectorAll('.hero-dot');
            let heroSlideInterval = null;
            // Interval waktu perpindahan slide diatur oleh Super Admin (dalam milidetik)
            const slideIntervalMs = (<?= (int)($settings['slide_interval_seconds'] ?? 5) ?>) * 1000;

            function showHeroSlide(index) {
                if (!heroSlides.length) return;
                if (index >= heroSlides.length) currentHeroSlideIndex = 0;
                else if (index < 0) currentHeroSlideIndex = heroSlides.length - 1;
                else currentHeroSlideIndex = index;

                heroSlides.forEach((slide, i) => {
                    if (i === currentHeroSlideIndex) {
                        slide.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                        slide.classList.add('opacity-100', 'z-10');
                    } else {
                        slide.classList.remove('opacity-100', 'z-10');
                        slide.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                    }
                });

                heroDots.forEach((dot, i) => {
                    if (i === currentHeroSlideIndex) {
                        dot.className = 'hero-dot w-9 h-2 rounded-full bg-amber-400 transition-all duration-300';
                    } else {
                        dot.className = 'hero-dot w-2.5 h-2 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300';
                    }
                });
            }

            function changeHeroSlide(step) {
                showHeroSlide(currentHeroSlideIndex + step);
                resetHeroAutoSlide();
            }

            function setHeroSlide(index) {
                showHeroSlide(index);
                resetHeroAutoSlide();
            }

            function startHeroAutoSlide() {
                if (heroSlides.length > 1) {
                    heroSlideInterval = setInterval(() => {
                        showHeroSlide(currentHeroSlideIndex + 1);
                    }, slideIntervalMs);
                }
            }

            function resetHeroAutoSlide() {
                if (heroSlideInterval) clearInterval(heroSlideInterval);
                startHeroAutoSlide();
            }

            document.addEventListener('DOMContentLoaded', () => {
                showHeroSlide(0);
                startHeroAutoSlide();
            });

            // Pause slider timer when tab is not active to conserve CPU/RAM, resume when tab is active
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    if (heroSlideInterval) {
                        clearInterval(heroSlideInterval);
                        heroSlideInterval = null;
                    }
                } else {
                    if (!heroSlideInterval) {
                        startHeroAutoSlide();
                    }
                }
            });
        </script>
    </section>



    <!-- SECTION 3: KEUNGGULAN UTAMA (DealTech UI Inspired Card List) -->
    <section id="keunggulan" class="py-20 bg-slate-50 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-purple-700 bg-purple-100 px-4 py-1.5 rounded-full">
                    Mengapa Memilih Amanah Safar
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Kepastian Layanan &amp; Ketenteraman Ibadah Anda
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Kami memprioritaskan rasa aman dan kemudahan jamaah sejak pendaftaran hingga kembali ke tanah air.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="luxury-card p-8 rounded-2xl space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-[#5e0078]/10 text-[#5e0078] flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Legalitas &amp; Izin Resmi</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Terdaftar resmi sebagai PPIU Kemenag RI dengan rekam jejak tanpa kendala visa maupun tiket penerbangan.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="luxury-card p-8 rounded-2xl space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Hotel Pelataran Haram</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Akomodasi setaraf bintang 5 di pelataran Masjidil Haram dan Masjid Nabawi, aman dan nyaman untuk jamaah lansia.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="luxury-card p-8 rounded-2xl space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-[#5e0078]/10 text-[#5e0078] flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Bimbingan Sesuai Sunnah</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Didampingi Asatidz berpengalaman alumni Timur Tengah yang siap membimbing setiap rukun dan doa ibadah.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="luxury-card p-8 rounded-2xl space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-train"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Kereta Cepat Haramain</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        Perjalanan efisien Mekkah – Madinah menggunakan kereta cepat modern, menghemat waktu dan stamina fisik jamaah.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: PROGRAM DAN KATALOG PAKET UNGGULAN -->
    <section id="program" class="py-20 bg-white border-y border-slate-200/80 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#5e0078]">Pilihan Paket</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Paket Keberangkatan Terpopuler</h2>
                    <p class="text-slate-600 text-sm mt-1">Pilih paket sesuai tanggal, durasi, dan fasilitas yang Anda kehendaki.</p>
                </div>
                <a href="<?= base_url('/catalog') ?>" class="inline-flex items-center gap-2 text-sm font-bold text-[#5e0078] hover:text-[#4c0062] transition">
                    <span>Lihat Semua Paket di Katalog</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $pkg): ?>
                        <div class="luxury-card rounded-2xl overflow-hidden flex flex-col">
                            <div class="relative h-56 overflow-hidden bg-slate-900">
                                <?php if (!empty($pkg['gambar'])): 
                                    $pkgJpg = base_url('uploads/packages/' . esc($pkg['gambar']));
                                    $pkgWebpName = pathinfo($pkg['gambar'], PATHINFO_FILENAME) . '.webp';
                                    $hasPkgWebp = file_exists(FCPATH . 'uploads/packages/' . $pkgWebpName);
                                    $pkgWebp = base_url('uploads/packages/' . $pkgWebpName);
                                ?>
                                    <picture>
                                        <?php if ($hasPkgWebp): ?>
                                            <source srcset="<?= esc($pkgWebp) ?>" type="image/webp">
                                        <?php endif; ?>
                                        <img src="<?= esc($pkgJpg) ?>" 
                                             alt="<?= esc($pkg['nama']) ?>" 
                                             width="1000" height="1250"
                                             loading="lazy"
                                             decoding="async"
                                             class="w-full h-full object-cover hover:scale-105 transition duration-500">
                                    </picture>
                                <?php else: ?>
                                    <div class="w-full h-full bg-gradient-to-br from-[#5e0078] via-[#4a0060] to-[#30003e] flex flex-col items-center justify-center text-center p-6 text-white select-none">
                                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center mb-2 border border-white/15 shadow-inner">
                                            <i class="fas fa-kaaba text-2xl text-amber-300"></i>
                                        </div>
                                        <span class="text-[11px] font-semibold tracking-wider uppercase text-purple-200"><?= esc($pkg['kategori'] ?? 'Paket') ?></span>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute top-4 left-4 bg-[#5e0078] text-white text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    <?= esc($pkg['kategori'] ?? 'Umroh') ?>
                                </span>
                                <div class="absolute bottom-3 right-3 bg-white/90 backdrop-blur-md px-3 py-1 rounded-lg text-xs font-bold text-slate-800">
                                    <i class="fas fa-check-circle text-amber-500 mr-1"></i> Tersedia
                                </div>
                            </div>
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                                        <i class="fas fa-plane text-[#5e0078]"></i> Maskapai Resmi &amp; Terjadwal
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-900"><?= esc($pkg['nama']) ?></h3>
                                    <p class="mt-2 text-xs text-slate-600 line-clamp-2">
                                        <?= esc($pkg['deskripsi'] ?? 'Paket perjalanan ibadah berkualitas dengan bimbingan dan fasilitas hotel terbaik.') ?>
                                    </p>
                                </div>
                                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[11px] text-slate-400 block">Investasi Ibadah</span>
                                        <span class="text-lg font-extrabold text-[#5e0078]">Rp <?= number_format((float)($pkg['harga'] ?? 0), 0, ',', '.') ?></span>
                                    </div>
                                    <a href="<?= base_url('/catalog/' . $pkg['id']) ?>" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-[#5e0078] text-white text-xs font-bold transition">
                                        Detail Paket
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-16 text-center text-slate-500 text-sm">
                        <i class="fas fa-box-open text-3xl text-gray-300 block mb-2"></i>
                        Belum ada paket perjalanan yang aktif saat ini.
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>

    <!-- SECTION 5: STEP BY STEP REGISTRATION (Alur Pendaftaran & Fasilitas) -->
    <section id="fasilitas" class="py-20 bg-slate-50 scroll-mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-purple-700">Mudah &amp; Transparan</span>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">4 Langkah Menuju Baitullah</h2>
                <p class="text-slate-600 text-sm">Proses pendaftaran yang praktis didukung verifikasi digital yang aman.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                    <span class="text-4xl font-black text-purple-200 block mb-2">01</span>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Pilih Tanggal &amp; Paket</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Konsultasikan jadwal atau pilih paket yang tersedia di katalog sesuai preferensi keluarga Anda.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                    <span class="text-4xl font-black text-amber-200 block mb-2">02</span>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Booking Seat &amp; DP</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Kunci kuota tiket dan akomodasi dengan DP resmi yang tercatat secara transparan di sistem.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                    <span class="text-4xl font-black text-purple-200 block mb-2">03</span>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Dokumen &amp; Manasik</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Penyerahan paspor, pembuatan visa umroh, serta pembekalan manasik intensif sebelum berangkat.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm relative">
                    <span class="text-4xl font-black text-amber-200 block mb-2">04</span>
                    <h3 class="text-base font-bold text-slate-900 mb-2">Berangkat Mabrur</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pemberangkatan dari Bandara Soekarno-Hatta dengan pendampingan tim tour leader dan muthowif.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 6: TESTIMONI JAMAAH (INFINITE CONTINUOUS MARQUEE CAROUSEL) -->
    <style>
    /* Continuous Marquee Track Animation */
    .testi-marquee-track {
        display: flex;
        width: max-content;
        animation: testiContinuousMarquee 42s linear infinite;
        will-change: transform;
    }

    /* Smooth pause on hover without resetting position */
    .testi-marquee-container:hover .testi-marquee-track {
        animation-play-state: paused;
    }

    @keyframes testiContinuousMarquee {
        0% {
            transform: translate3d(0, 0, 0);
        }
        100% {
            transform: translate3d(-50%, 0, 0);
        }
    }

    /* Edge Gradient Fades confined to the carousel container */
    .testi-gradient-left,
    .testi-gradient-right {
        position: absolute;
        top: 0;
        bottom: 0;
        width: clamp(70px, 8vw, 130px);
        z-index: 20;
        pointer-events: none;
    }

    .testi-gradient-left {
        left: 0;
        background: linear-gradient(to right, #ffffff 0%, rgba(255, 255, 255, 0.95) 25%, rgba(255, 255, 255, 0) 100%);
    }

    .testi-gradient-right {
        right: 0;
        background: linear-gradient(to left, #ffffff 0%, rgba(255, 255, 255, 0.95) 25%, rgba(255, 255, 255, 0) 100%);
    }

    @media (max-width: 640px) {
        .testi-gradient-left,
        .testi-gradient-right {
            width: 48px;
        }
    }

    /* Accessibility: Respect Reduced Motion Preferences */
    @media (prefers-reduced-motion: reduce) {
        .testi-marquee-track {
            animation: none;
            overflow-x: auto;
        }
    }
    </style>

    <section id="testimoni" class="py-20 sm:py-24 bg-white border-t border-slate-200/80 overflow-hidden relative scroll-mt-24">
        <!-- Subtle Decorative Background Glow -->
        <div class="absolute -top-32 -left-32 w-96 h-96 bg-purple-100/50 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber-100/40 rounded-full blur-3xl pointer-events-none"></div>

        <?php
        $marqueeItems = !empty($testimonials) ? $testimonials : [];
        if (!empty($marqueeItems) && count($marqueeItems) < 4) {
            while (count($marqueeItems) < 4) {
                $marqueeItems = array_merge($marqueeItems, $testimonials);
            }
        }

        $renderCard = function($t, $isDuplicate = false) {
        ?>
            <!-- Testimonial Card (Matches Reference Composition) -->
            <div class="testi-card w-[310px] sm:w-[340px] md:w-[350px] shrink-0 bg-white rounded-3xl border border-slate-200/90 shadow-sm hover:shadow-xl hover:shadow-purple-950/10 transition-all duration-300 hover:-translate-y-2 flex flex-col overflow-hidden group select-none">
                <!-- Top: Portrait Photo Container with Pills -->
                <div class="h-72 sm:h-80 w-full relative overflow-hidden bg-slate-900">
                    <?php if (!empty($t['foto'])): 
                        $tFoto = $t['foto'];
                        $tWebp = preg_replace('/\.(jpe?g|png)$/i', '.webp', $tFoto);
                        $hasTWebp = ($tWebp !== $tFoto && (str_contains($tFoto, 'uploads/testimonials/') ? file_exists(FCPATH . 'uploads/testimonials/' . basename($tWebp)) : false));
                    ?>
                        <picture>
                            <?php if ($hasTWebp): ?>
                                <source srcset="<?= esc($tWebp) ?>" type="image/webp">
                            <?php endif; ?>
                            <img src="<?= esc($tFoto) ?>" 
                                 alt="<?= esc($t['nama_jamaah']) ?>" 
                                 width="800" height="1058"
                                 loading="lazy"
                                 decoding="async"
                                 class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out">
                        </picture>
                        <!-- Gradient Overlay to keep pills legible -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-slate-950/20 to-transparent"></div>
                    <?php else: ?>
                        <!-- Fallback: Murni warna ungu elegan jika foto NULL/kosong sesuai instruksi database -->
                        <div class="w-full h-full bg-gradient-to-br from-[#5e0078] via-[#48005d] to-[#2d003a] flex flex-col items-center justify-center relative p-6 text-center">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-amber-300 text-2xl shadow-inner mb-2.5 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-kaaba"></i>
                            </div>
                            <span class="text-xs font-bold text-white tracking-widest uppercase">Amanah Safar</span>
                            <span class="text-[11px] text-purple-200 font-medium mt-0.5">Tamu Mulia Baitullah</span>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent pointer-events-none"></div>
                        </div>
                    <?php endif; ?>

                    <!-- DUA FLOATING PILLS DI ATAS FOTO (Reference Composition) -->
                    <div class="absolute bottom-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2 z-10 pointer-events-none">
                        <!-- Pill Kiri: Warna Kuning/Amber (Asal Jamaah) -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-bold text-xs shadow-md tracking-tight">
                            <i class="fas fa-map-marker-alt text-[10px]"></i>
                            <span><?= esc($t['kota_asal'] ?: 'Indonesia') ?></span>
                        </span>

                        <!-- Pill Kanan: Warna Gelap/Hitam (Tahun Keberangkatan) -->
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950/85 backdrop-blur-md text-white font-semibold text-xs shadow-md border border-white/15 tracking-tight">
                            <i class="fas fa-calendar-alt text-[10px] text-amber-400"></i>
                            <span><?= esc($t['tahun_keberangkatan'] ?: date('Y')) ?></span>
                        </span>
                    </div>
                </div>

                <!-- Bottom: Card Content & Testimonial Info -->
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4 bg-white">
                    <div class="space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="inline-block text-[11px] font-bold text-[#5e0078] bg-purple-50 border border-purple-100 px-2.5 py-0.5 rounded-md uppercase tracking-wider">
                                <?= esc($t['program_paket'] ?: 'Umroh Reguler') ?>
                            </span>
                            <div class="flex items-center gap-0.5 text-amber-400 text-xs">
                                <?php for ($s = 1; $s <= (int)($t['rating'] ?: 5); $s++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-[#5e0078] transition-colors leading-snug line-clamp-1">
                            <?= esc($t['nama_jamaah']) ?>
                        </h3>

                        <p class="text-slate-600 text-xs sm:text-sm italic leading-relaxed line-clamp-3">
                            “<?= esc($t['testimoni']) ?>”
                        </p>
                    </div>

                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" 
                                onclick='openTestiModal(<?= htmlspecialchars(json_encode($t), ENT_QUOTES, "UTF-8") ?>)' 
                                class="text-xs font-bold text-[#5e0078] hover:text-[#450059] flex items-center gap-1.5 transition group/btn focus:outline-none cursor-pointer">
                            <span>Lihat Kisah Lengkap</span>
                            <i class="fas fa-arrow-right text-[10px] transform group-hover/btn:translate-x-1 transition-transform"></i>
                        </button>
                        <span class="text-[11px] text-slate-400 font-medium">Amanah Safar</span>
                    </div>
                </div>
            </div>
        <?php
        };
        ?>

        <!-- MAIN CONTAINER (Centered with controlled max-width matching layout) -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto space-y-3 mb-12 sm:mb-14">
                <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-[#5e0078] bg-purple-100/80 px-3.5 py-1 rounded-full border border-purple-200">
                    <i class="fas fa-quote-left text-[10px]"></i> Ulasan Nyata Jamaah
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                    Kata Jamaah Amanah Safar
                </h2>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Pengalaman berharga dan kisah haru para tamu Allah yang telah menunaikan ibadah suci bersama kami.
                </p>
            </div>

            <!-- CONTINUOUS MARQUEE VIEWPORT CONTAINER (Confined within content container) -->
            <div class="testi-marquee-container relative w-full overflow-hidden py-4 select-none">
                <!-- SISI KIRI: Lapisan Gradasi Putih Transparan pada batas kiri container carousel -->
                <div class="testi-gradient-left"></div>

                <!-- SISI KANAN: Lapisan Gradasi Putih Transparan pada batas kanan container carousel -->
                <div class="testi-gradient-right"></div>

                <!-- Track Marquee Berjalan Tanpa Henti -->
                <div class="testi-marquee-track flex will-change-transform">
                    <?php if (empty($marqueeItems)): ?>
                        <div class="w-full text-center py-12 text-slate-500 text-sm">
                            Belum ada testimoni yang ditampilkan saat ini.
                        </div>
                    <?php else: ?>
                        <!-- Kelompok 1: Kartu Utama dari Database -->
                        <div class="testi-marquee-group flex gap-6 shrink-0 pr-6">
                            <?php foreach ($marqueeItems as $t): ?>
                                <?php $renderCard($t, false); ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Kelompok 2: Duplikat Visual untuk Perputaran Loop Mulus Tanpa Jeda -->
                        <div class="testi-marquee-group flex gap-6 shrink-0 pr-6" aria-hidden="true">
                            <?php foreach ($marqueeItems as $t): ?>
                                <?php $renderCard($t, true); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- MODAL DETAIL TESTIMONI LENGKAP -->
        <div id="modalTestiDetail" class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-hidden flex flex-col relative transform transition-all duration-300">
                <!-- Close Button -->
                <button onclick="closeTestiModal()" 
                        class="absolute top-4 right-4 z-20 w-9 h-9 rounded-full bg-slate-900/60 hover:bg-slate-900 text-white flex items-center justify-center transition focus:outline-none shadow-md">
                    <i class="fas fa-times text-sm"></i>
                </button>

                <!-- Modal Top: Photo or Purple Fallback -->
                <div id="modalTestiPhotoWrapper" class="h-64 sm:h-72 w-full relative overflow-hidden bg-slate-900 flex-shrink-0">
                    <img id="modalTestiImg" src="" alt="Foto Jamaah" class="w-full h-full object-cover hidden">
                    <div id="modalTestiPurple" class="w-full h-full bg-gradient-to-br from-[#5e0078] via-[#48005d] to-[#2d003a] flex flex-col items-center justify-center p-6 text-center hidden">
                        <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-amber-300 text-2xl shadow-inner mb-2.5">
                            <i class="fas fa-kaaba"></i>
                        </div>
                        <span class="text-xs font-bold text-white tracking-widest uppercase">Amanah Safar</span>
                        <span class="text-[11px] text-purple-200 font-medium mt-0.5">Tamu Mulia Baitullah</span>
                    </div>

                    <!-- Pills in Modal Photo -->
                    <div class="absolute bottom-3.5 left-3.5 right-3.5 flex items-center justify-between gap-2 z-10 pointer-events-none">
                        <span id="modalTestiKota" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-bold text-xs shadow-md">
                            <i class="fas fa-map-marker-alt text-[10px]"></i> <span>Bogor</span>
                        </span>
                        <span id="modalTestiTahun" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-950/85 backdrop-blur-md text-white font-semibold text-xs shadow-md border border-white/15">
                            <i class="fas fa-calendar-alt text-[10px] text-amber-400"></i> <span>1447 H</span>
                        </span>
                    </div>
                </div>

                <!-- Modal Body: Full Testimony Info -->
                <div class="p-6 sm:p-7 overflow-y-auto space-y-4">
                    <div class="flex items-center justify-between gap-3">
                        <span id="modalTestiProgram" class="inline-block text-[11px] font-bold text-[#5e0078] bg-purple-50 border border-purple-100 px-3 py-1 rounded-md uppercase tracking-wider">
                            Umroh Reguler
                        </span>
                        <div id="modalTestiStars" class="flex text-amber-400 text-xs">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>

                    <h3 id="modalTestiNama" class="text-xl sm:text-2xl font-bold text-slate-900">
                        Nama Jamaah
                    </h3>

                    <div class="bg-purple-50/50 p-4 rounded-2xl border border-purple-100/70">
                        <p id="modalTestiQuote" class="text-slate-700 text-sm leading-relaxed italic">
                            Ulasan testimoni jamaah...
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <span>Amanah Safar Travel &bull; Ibadah Nyaman Sesuai Sunnah</span>
                        <button type="button" onclick="closeTestiModal()" class="font-bold text-[#5e0078] hover:underline cursor-pointer">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIAL MODAL SCRIPT -->
    <script>
    function openTestiModal(data) {
        if (!data) return;
        const modal = document.getElementById('modalTestiDetail');
        if (!modal) return;

        document.getElementById('modalTestiNama').textContent = data.nama_jamaah || 'Jamaah Amanah Safar';
        document.getElementById('modalTestiProgram').textContent = data.program_paket || 'Umroh Reguler';
        document.getElementById('modalTestiKota').querySelector('span').textContent = data.kota_asal || 'Indonesia';
        document.getElementById('modalTestiTahun').querySelector('span').textContent = data.tahun_keberangkatan || '<?= date("Y") ?>';
        document.getElementById('modalTestiQuote').textContent = `“${data.testimoni || ''}”`;

        const imgEl = document.getElementById('modalTestiImg');
        const purpleEl = document.getElementById('modalTestiPurple');

        if (data.foto && data.foto.trim() !== '') {
            imgEl.src = data.foto;
            imgEl.classList.remove('hidden');
            purpleEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            purpleEl.classList.remove('hidden');
        }

        // Rating Stars
        const starsContainer = document.getElementById('modalTestiStars');
        starsContainer.innerHTML = '';
        const rating = parseInt(data.rating) || 5;
        for (let i = 0; i < rating; i++) {
            starsContainer.innerHTML += '<i class="fas fa-star mr-0.5"></i>';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTestiModal() {
        const modal = document.getElementById('modalTestiDetail');
        if (!modal) return;
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Close on click backdrop & Esc key
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modalTestiDetail');
        if (modal && e.target === modal) {
            closeTestiModal();
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTestiModal();
        }
    });
    </script>

    <!-- SECTION 7: FAQ (Tanya Jawab) -->
    <section id="faq" class="py-20 bg-slate-50 scroll-mt-24">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 space-y-2">
                <span class="text-xs font-bold uppercase tracking-widest text-[#5e0078]">Pertanyaan Umum</span>
                <h2 class="text-3xl font-extrabold text-slate-900">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4">
                <!-- FAQ Item 1 -->
                <div class="faq-item bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm transition hover:border-slate-300">
                    <button type="button" 
                            class="faq-trigger w-full flex justify-between items-center text-left font-bold text-slate-900 cursor-pointer text-sm sm:text-base focus:outline-none focus-visible:ring-2 focus-visible:ring-[#5e0078]/40 focus-visible:ring-offset-2 rounded-lg group select-none"
                            aria-expanded="false" 
                            aria-controls="faq-ans-1" 
                            id="faq-btn-1">
                        <span class="group-hover:text-[#5e0078] transition-colors duration-200">Apakah tiket dan visa sudah termasuk dalam paket?</span>
                        <span class="faq-icon text-[#5e0078] group-hover:opacity-80 transition shrink-0 ml-4">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                    <div id="faq-ans-1" class="faq-panel" role="region" aria-labelledby="faq-btn-1">
                        <div>
                            <div class="faq-content-inner pt-3 mt-3 border-t border-slate-100">
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Ya, seluruh paket yang kami tampilkan sudah termasuk tiket pesawat internasional pulang-pergi, visa umroh resmi, hotel di Madinah &amp; Makkah, transportasi bus full AC, serta makan 3x sehari.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="faq-item bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm transition hover:border-slate-300">
                    <button type="button" 
                            class="faq-trigger w-full flex justify-between items-center text-left font-bold text-slate-900 cursor-pointer text-sm sm:text-base focus:outline-none focus-visible:ring-2 focus-visible:ring-[#5e0078]/40 focus-visible:ring-offset-2 rounded-lg group select-none"
                            aria-expanded="false" 
                            aria-controls="faq-ans-2" 
                            id="faq-btn-2">
                        <span class="group-hover:text-[#5e0078] transition-colors duration-200">Berapa minimal DP untuk mengunci tempat keberangkatan?</span>
                        <span class="faq-icon text-[#5e0078] group-hover:opacity-80 transition shrink-0 ml-4">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                    <div id="faq-ans-2" class="faq-panel" role="region" aria-labelledby="faq-btn-2">
                        <div>
                            <div class="faq-content-inner pt-3 mt-3 border-t border-slate-100">
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Minimal uang muka (DP) adalah Rp 5.000.000 per jamaah. Pelunasan dapat dilakukan paling lambat 30 hari sebelum tanggal keberangkatan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="faq-item bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm transition hover:border-slate-300">
                    <button type="button" 
                            class="faq-trigger w-full flex justify-between items-center text-left font-bold text-slate-900 cursor-pointer text-sm sm:text-base focus:outline-none focus-visible:ring-2 focus-visible:ring-[#5e0078]/40 focus-visible:ring-offset-2 rounded-lg group select-none"
                            aria-expanded="false" 
                            aria-controls="faq-ans-3" 
                            id="faq-btn-3">
                        <span class="group-hover:text-[#5e0078] transition-colors duration-200">Bagaimana jika jamaah lansia membutuhkan kursi roda?</span>
                        <span class="faq-icon text-[#5e0078] group-hover:opacity-80 transition shrink-0 ml-4">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                    <div id="faq-ans-3" class="faq-panel" role="region" aria-labelledby="faq-btn-3">
                        <div>
                            <div class="faq-content-inner pt-3 mt-3 border-t border-slate-100">
                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                                    Tim kami menyediakan fasilitas pendampingan dan penyediaan kursi roda di tanah suci dengan koordinasi muthowif khusus agar ibadah tetap lancar dan khusyuk.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- FOOTER (Sesuai Konfigurasi Terkini Proyek) -->
    <footer class="bg-[#240030] text-purple-200 text-sm pt-16 pb-8 border-t border-purple-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 pb-12 border-b border-purple-900/50">
                <!-- Col 1: Brand -->
                <div class="lg:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#5e0078] to-[#8f20ad] flex items-center justify-center text-white font-extrabold text-sm shadow-md">
                            AS
                        </div>
                        <span class="text-xl font-bold text-white tracking-wide">Amanah Safar</span>
                    </div>
                    <p class="text-xs text-purple-300/80 leading-relaxed max-w-sm">
                        Biro perjalanan ibadah Umroh dan Haji Khusus resmi berizin Kemenag RI. Berkomitmen memberikan pelayanan yang amanah, transparan, dan sesuai sunnah.
                    </p>
                    <div class="flex items-center gap-3 text-white text-base pt-2">
                        <a href="https://www.instagram.com/amanahsafar?dlrf=MW0xc3Z3eWJ3aW81aA%3D%3D&utm_source=qr" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-[#5e0078] flex items-center justify-center transition" title="Instagram Amanah Safar"><i class="fab fa-instagram"></i></a>
                        <a href="https://youtube.com/@amanahsafar?si=hQ4MVv1Hn5aC_jZB" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-[#5e0078] flex items-center justify-center transition" title="YouTube Amanah Safar"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.tiktok.com/@amanahsafar?is_from_webapp=1&sender_device=pc" target="_blank" rel="noopener noreferrer" class="w-9 h-9 rounded-lg bg-white/10 hover:bg-[#5e0078] flex items-center justify-center transition" title="TikTok Amanah Safar"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Col 2: Navigasi -->
                <div class="space-y-3">
                    <h4 class="font-bold text-white text-sm">Navigasi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="<?= base_url('/') ?>" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="<?= base_url('/catalog') ?>" class="hover:text-white transition">Katalog Paket</a></li>
                        <li><a href="#keunggulan" class="hover:text-white transition">Keunggulan</a></li>
                        <li><a href="#program" class="hover:text-white transition">Program Safar</a></li>
                        <li><a href="#faq" class="hover:text-white transition">FAQ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Portal Anggota -->
                <div class="space-y-3">
                    <h4 class="font-bold text-white text-sm">Portal Sistem</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="<?= base_url('/login') ?>" class="hover:text-white transition">Masuk Akun</a></li>
                        <li><a href="<?= base_url('/register') ?>" class="hover:text-white transition">Registrasi Mitra</a></li>
                        <li><a href="<?= base_url('/dashboard') ?>" class="hover:text-white transition">Area Dashboard</a></li>
                        <li><a href="<?= base_url('/agreement') ?>" class="hover:text-white transition">Syarat &amp; Akad</a></li>
                    </ul>
                </div>

                <!-- Col 4: Kontak -->
                <div class="space-y-3">
                    <h4 class="font-bold text-white text-sm">Kantor Pusat</h4>
                    <ul class="space-y-2.5 text-xs text-purple-300/90">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-amber-400 mt-1"></i>
                            <span>Gedung Graha Pool Lantai 3 unit 303-304, RT.03/RW.03, Ciwaringin, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16124</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone-alt text-amber-400"></i>
                            <span>(021) 7890-1234</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope text-amber-400"></i>
                            <span>info@amanahsafar.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-purple-300/70 gap-4">
                <p>&copy; <?= date('Y') ?> PT Amanah Tangguh Mandiri. All Rights Reserved. | Amanah Safar Digital Platform.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

</div>

<!-- FLOATING WHATSAPP CS BUTTON (Always Fixed to Viewport) -->
<?php 
$waCsUrl = !empty($settings['whatsapp_cs_url']) ? trim($settings['whatsapp_cs_url']) : '';
if (empty($waCsUrl)) {
    $csWaNumber = !empty($settings['contact_whatsapp']) ? preg_replace('/[^0-9]/', '', $settings['contact_whatsapp']) : '6281234567890';
    if (strpos($csWaNumber, '0') === 0) {
        $csWaNumber = '62' . substr($csWaNumber, 1);
    }
    $waCsUrl = 'https://wa.me/' . $csWaNumber . '?text=' . urlencode('Halo CS Amanah Safar, saya ingin konsultasi mengenai program umroh / haji.');
}
?>
<div class="fixed bottom-12 right-6 z-[9999] flex items-center group" style="position: fixed !important; z-index: 9999 !important;">
    <!-- Tooltip Label on Hover -->
    <span class="hidden sm:inline-block opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0 mr-3 px-3.5 py-2 rounded-xl bg-slate-900/90 backdrop-blur-md text-white text-xs font-semibold shadow-xl whitespace-nowrap pointer-events-none border border-slate-700/50">
        Hubungi CS Kami 👋
    </span>

    <!-- Floating Circular WhatsApp Button -->
    <a href="<?= esc($waCsUrl) ?>" target="_blank" rel="noopener noreferrer" 
       class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#25D366] text-white flex items-center justify-center shadow-[0_10px_25px_rgba(37,211,102,0.45)] hover:shadow-[0_15px_35px_rgba(37,211,102,0.65)] hover:scale-110 active:scale-95 transition-all duration-300 focus:outline-none"
       aria-label="Hubungi CS via WhatsApp">
        <!-- Gentle pulse ring effect -->
        <span class="absolute -inset-1 rounded-full bg-[#25D366]/40 animate-ping pointer-events-none"></span>
        
        <!-- WhatsApp Icon -->
        <i class="fab fa-whatsapp text-3xl sm:text-4xl text-white relative z-10 leading-none"></i>
    </a>
</div>

<!-- NAVBAR SMOOTH SCROLL & ACTIVE STATE CONTROLLER -->
<script>
(function() {
    const header = document.getElementById('mainHeader');
    const desktopLinks = document.querySelectorAll('.nav-link-item');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');
    const allNavLinks = [...desktopLinks, ...mobileLinks];
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileDrawer = document.getElementById('mobileMenuDrawer');
    const mobileIcon = document.getElementById('mobileMenuIcon');

    // Section definitions matching all navbar anchor targets
    const sectionIds = ['hero', 'keunggulan', 'program', 'fasilitas', 'testimoni', 'faq'];
    const sections = sectionIds
        .map(id => document.getElementById(id))
        .filter(el => el !== null);

    // 1. Navbar Elevated Shadow on Scroll
    function updateHeaderShadow() {
        if (!header) return;
        if (window.scrollY > 20) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    window.addEventListener('scroll', updateHeaderShadow, { passive: true });
    updateHeaderShadow();

    // 2. Set Active Menu Link Helper
    function setActiveNavLink(targetId) {
        desktopLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === '#' + targetId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });

        mobileLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === '#' + targetId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    // 3. Smooth Scroll Navigation for all Navbar Anchor Links
    let isProgrammaticScrolling = false;
    let scrollTimeout = null;

    allNavLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (!href || !href.startsWith('#')) return;

            const targetId = href.substring(1);
            const targetEl = document.getElementById(targetId);
            if (!targetEl) return;

            e.preventDefault();

            // Close mobile drawer if open
            if (mobileDrawer && !mobileDrawer.classList.contains('hidden')) {
                toggleMobileMenu(false);
            }

            // Immediately give snappy user feedback on clicked menu item
            setActiveNavLink(targetId);
            isProgrammaticScrolling = true;

            // Compute exact target position compensating for sticky header
            const headerHeight = header ? header.offsetHeight : 80;
            const elementRect = targetEl.getBoundingClientRect();
            const elementTop = elementRect.top + window.pageYOffset;
            const scrollTarget = Math.max(0, elementTop - headerHeight);

            window.scrollTo({
                top: scrollTarget,
                behavior: 'smooth'
            });

            // Update browser history hash without sudden jump
            if (history.pushState) {
                history.pushState(null, null, '#' + targetId);
            }

            // Re-enable IntersectionObserver after smooth scroll completes
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                isProgrammaticScrolling = false;
            }, 800);
        });
    });

    // 4. Active Section Detection with IntersectionObserver
    if ('IntersectionObserver' in window && sections.length > 0) {
        const observerOptions = {
            root: null,
            rootMargin: '-20% 0px -55% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            if (isProgrammaticScrolling) return;

            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setActiveNavLink(entry.target.id);
                }
            });
        }, observerOptions);

        sections.forEach(s => observer.observe(s));

        // When scrolled to the very top, always activate Beranda (#hero)
        window.addEventListener('scroll', () => {
            if (isProgrammaticScrolling) return;
            if (window.scrollY < 80) {
                setActiveNavLink('hero');
            }
        }, { passive: true });
    }

    // 5. Mobile Drawer Toggle Handler
    function toggleMobileMenu(forceState) {
        if (!mobileDrawer) return;
        const isCurrentlyOpen = !mobileDrawer.classList.contains('hidden');
        const shouldOpen = typeof forceState === 'boolean' ? forceState : !isCurrentlyOpen;

        if (shouldOpen) {
            mobileDrawer.classList.remove('hidden');
            if (mobileIcon) {
                mobileIcon.classList.remove('fa-bars');
                mobileIcon.classList.add('fa-times');
            }
            if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'true');
        } else {
            mobileDrawer.classList.add('hidden');
            if (mobileIcon) {
                mobileIcon.classList.remove('fa-times');
                mobileIcon.classList.add('fa-bars');
            }
            if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
        }
    }

    if (mobileBtn) {
        mobileBtn.addEventListener('click', () => toggleMobileMenu());
    }

    // 6. FAQ Accordion Smooth Motion Controller
    const faqTriggers = document.querySelectorAll('.faq-trigger');
    faqTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const item = trigger.closest('.faq-item');
            if (!item) return;
            const isOpen = item.classList.contains('is-open');

            if (isOpen) {
                item.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });
})();
</script>

<?= $this->endSection() ?>
