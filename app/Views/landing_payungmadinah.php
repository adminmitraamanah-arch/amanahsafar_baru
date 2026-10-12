<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex flex-col font-sans" style="background-color: #faf7f1; color: #141416;">

    <!-- Top Announcement Bar (Tetap sesuai tampilan awal) -->
    <div class="bg-[#3e004f] text-purple-100 text-xs py-2 px-4 text-center font-medium border-b border-purple-900/50">
        <div class="max-w-7xl mx-auto flex items-center justify-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#5e0078] text-white text-[11px] font-semibold">
                <i class="fas fa-star text-amber-300 text-[10px]"></i> Resmi & Terpercaya
            </span>
            <span>Pendaftaran Umroh & Haji Musim 1448 H Telah Dibuka. Kuota Terbatas!</span>
            <a href="<?= base_url('/catalog') ?>" class="underline hover:text-white font-semibold ml-1">Lihat Jadwal →</a>
        </div>
    </div>

    <!-- Navigation Header (Tetap sesuai tampilan awal) -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-purple-100 shadow-sm transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center group">
                    <img src="<?= base_url('assets/images/logo-purple.png') ?>" alt="Amanah Safar" class="h-10 sm:h-11 w-auto object-contain transition group-hover:scale-105">
                </a>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-700">
                    <a href="#hero" class="text-[#5e0078] font-semibold hover:text-[#5e0078] transition">Beranda</a>
                    <a href="#keunggulan" class="hover:text-[#5e0078] transition">Keunggulan</a>
                    <a href="#program" class="hover:text-[#5e0078] transition">Paket Program</a>
                    <a href="#jadwal" class="hover:text-[#5e0078] transition">Jadwal</a>
                    <a href="#manasik" class="hover:text-[#5e0078] transition">Manasik</a>
                    <a href="#alur" class="hover:text-[#5e0078] transition">Cara Daftar</a>
                    <a href="#faq" class="hover:text-[#5e0078] transition">FAQ</a>
                </nav>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <?php if (session('logged_in')): ?>
                        <a href="<?= base_url('/dashboard') ?>" class="px-5 py-2.5 rounded-xl bg-[#5e0078] text-white text-sm font-semibold hover:bg-[#4c0062] shadow-sm hover:shadow-md transition flex items-center gap-2">
                            <i class="fas fa-columns text-xs"></i> Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" class="px-4 py-2.5 text-sm font-semibold text-gray-700 hover:text-[#5e0078] transition">
                            Masuk
                        </a>
                        <a href="<?= base_url('/register') ?>" class="px-5 py-2.5 rounded-xl bg-[#5e0078] text-white text-sm font-semibold hover:bg-[#4c0062] shadow-sm hover:shadow-md transition">
                            Daftar Mitra
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Style Custom Inspired by Payung Madinah -->
    <style>
        .pm-canvas { background: radial-gradient(120% 80% at 50% 0, #ffffff 0, #faf7f1 55%, #f1ebdf 100%); }
        .bplate {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: 0 30px 60px -25px rgba(60, 40, 10, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.9);
        }
        .btn-gold {
            background: linear-gradient(135deg, #d4af37, #c59b27 55%, #a67c1e);
            color: #ffffff;
            box-shadow: 0 12px 30px -12px rgba(184, 145, 48, 0.75);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .btn-gold:hover {
            background: linear-gradient(135deg, #dfbc45, #d2a731 55%, #b58925);
            transform: translateY(-2px);
            box-shadow: 0 16px 36px -12px rgba(184, 145, 48, 0.85);
        }
        .btn-ghost-gold {
            border: 1.5px solid rgba(197, 155, 39, 0.5);
            color: #3b2a12;
            background: rgba(255, 255, 255, 0.6);
            transition: all 0.3s ease;
        }
        .btn-ghost-gold:hover {
            border-color: #c59b27;
            background: #ffffff;
            color: #5e0078;
            transform: translateY(-2px);
        }
        .bprice-tag {
            background: linear-gradient(120deg, rgba(201, 163, 95, 0.2), rgba(201, 163, 95, 0.08));
            border: 1px solid rgba(197, 155, 39, 0.4);
        }
        .marquee-track {
            display: flex;
            align-items: center;
            gap: 3.5rem;
            width: max-content;
            animation: marquee 35s linear infinite;
        }
        .marquee-track:hover { animation-play-state: paused; }
        @keyframes marquee {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }
        .pcard-pm {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.65));
            border: 1px solid rgba(255, 255, 255, 0.95);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 20px 40px -25px rgba(60, 40, 10, 0.12);
            backdrop-filter: blur(12px);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .pcard-pm:hover {
            transform: translateY(-6px);
            border-color: #ffffff;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 30px 50px -20px rgba(94, 0, 120, 0.18);
        }
        .tile-pm {
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.6));
            border: 1px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 14px 30px -20px rgba(60, 40, 10, 0.1);
            transition: all 0.3s ease;
        }
        .tile-pm:hover {
            transform: translateY(-3px);
            border-color: rgba(197, 155, 39, 0.5);
            box-shadow: 0 20px 36px -18px rgba(60, 40, 10, 0.18);
        }
        .stepper-num {
            transition: all 0.3s ease;
        }
        .step-item:hover .stepper-num {
            transform: scale(1.08);
            background: #5e0078;
            color: #ffffff;
            border-color: #5e0078;
        }
    </style>

    <!-- Hero Section (Payung Madinah Style) -->
    <section id="hero" class="relative overflow-hidden pt-6 pb-16 lg:pb-24 pm-canvas">
        <!-- Visual Backdrop / Banner Image -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[340px] sm:h-[440px] lg:h-[500px] border border-stone-200">
                <img src="https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?auto=format&fit=crop&w=1920&q=80" 
                     alt="Masjid Nabawi Madinah & Ka'bah" 
                     class="w-full h-full object-cover object-center filter brightness-[0.88]">
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-black/15"></div>

                <!-- Text Badge Over Banner -->
                <div class="absolute top-6 left-6 sm:top-10 sm:left-10 z-10">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-amber-200 text-xs font-semibold uppercase tracking-wider">
                        <i class="fas fa-kaaba text-amber-300"></i>
                        <span>Amanah Safar · Umroh &amp; Haji Resmi</span>
                    </span>
                </div>

                <!-- Bottom Quote Inside Banner -->
                <div class="absolute bottom-6 left-6 right-6 sm:bottom-10 sm:left-10 max-w-2xl text-white">
                    <p class="text-xs sm:text-sm text-stone-300 font-medium tracking-wide uppercase mb-1">Penyelenggara Berizin Kemenag RI</p>
                    <h2 class="text-2xl sm:text-4xl font-extrabold leading-tight text-white drop-shadow">
                        Perjalanan Paling Suci, Fasilitas Nyaman, Dibimbing Penuh Berkah.
                    </h2>
                </div>
            </div>

            <!-- Floating Hero Plate (Payung Madinah Iconic Floating Card) -->
            <div class="relative -mt-16 sm:-mt-20 z-20 max-w-6xl mx-auto">
                <div class="bplate rounded-3xl p-6 sm:p-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <!-- Left Copy -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-amber-800">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Hotel Bintang 5 Dekat Pelataran Masjid</span>
                        </div>
                        <h1 class="text-2xl sm:text-4xl font-extrabold text-stone-900 tracking-tight leading-snug">
                            Umroh Nyaman &amp; Plus Destinasi Sejarah. Mulai Rp 30 Jutaan.
                        </h1>
                        <p class="text-stone-600 text-sm sm:text-base leading-relaxed">
                            Akomodasi hotel sejarak langkah kaki dari pelataran Masjidil Haram, didampingi Ustadz alumni Timur Tengah, dengan kepastian seat dan fasilitas eksekutif.
                        </p>
                    </div>

                    <!-- Right Action Box -->
                    <div class="lg:col-span-5 lg:pl-8 lg:border-l lg:border-stone-200/80 space-y-4">
                        <div class="bprice-tag inline-flex items-center gap-3 px-4 py-2 rounded-full">
                            <b class="text-stone-900 font-extrabold text-base">Mulai Rp 30,9 Jt</b>
                            <span class="text-xs font-medium text-amber-800">Seat terbatas musim ini</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="https://wa.me/6281234567890?text=Halo%20Amanah%20Safar,%20saya%20ingin%20konsultasi%20paket%20umroh" target="_blank" rel="noopener noreferrer" 
                               class="btn-gold px-6 py-3.5 rounded-full font-bold text-sm text-center flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp text-base"></i>
                                <span>Konsultasi Segera</span>
                            </a>
                            <a href="#program" class="btn-ghost-gold px-6 py-3.5 rounded-full font-semibold text-sm text-center flex items-center justify-center">
                                Lihat Program
                            </a>
                        </div>

                        <!-- Rating & Trust Mini -->
                        <div class="pt-4 border-t border-stone-200/60 flex items-center gap-4 text-xs text-stone-600">
                            <div class="flex text-amber-400 text-sm">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <span class="font-bold text-stone-900">4.9 / 5</span>
                            <span>•</span>
                            <span class="font-semibold text-purple-900">PPIU Resmi</span>
                            <span>•</span>
                            <span>1.500+ Jamaah</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Airlines Marquee Section -->
    <section class="border-y border-stone-200/80 bg-stone-100/70 py-6 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 mb-3 text-center">
            <p class="text-xs font-bold uppercase tracking-widest text-amber-900/80">Maskapai Penerbangan Resmi &amp; Terpercaya</p>
        </div>
        <div class="marquee-track px-6">
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> SAUDIA AIRLINES</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> GARUDA INDONESIA</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> EMIRATES</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> QATAR AIRWAYS</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> TURKISH AIRLINES</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> ETIHAD AIRWAYS</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> OMAN AIR</span>
            <!-- Repeat for seamless scroll -->
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> SAUDIA AIRLINES</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> GARUDA INDONESIA</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> EMIRATES</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> QATAR AIRWAYS</span>
            <span class="text-base font-extrabold text-stone-600 tracking-wider flex items-center gap-2"><i class="fas fa-plane text-amber-600"></i> TURKISH AIRLINES</span>
        </div>
    </section>

    <!-- Keunggulan Section (USP Grid) -->
    <section id="keunggulan" class="py-20 bg-stone-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                    Keunggulan Layanan
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                    Umroh yang Nyaman Diiringi oleh <br class="hidden sm:inline"> Fasilitas Lengkap &amp; Profesional
                </h2>
                <p class="text-stone-600 text-sm sm:text-base">
                    Standar kenyamanan terbaik yang menyertai setiap keberangkatan Anda bersama keluarga tercinta.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-800 flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Hotel Dekat Pelataran</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Akomodasi setaraf bintang 5 di pelataran Masjidil Haram dan Masjid Nabawi, sangat memudahkan jamaah lansia tanpa perlu shuttle bus.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-800 flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-train"></i>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Kereta Cepat Haramain</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Fasilitas perjalanan antarkota Mekkah dan Madinah dengan kereta cepat modern Haramain High Speed Railway (bebas lelah jalan darat).
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-800 flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Pembimbing Berilmu</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Didampingi Ustadz pembimbing alumni universitas terkemuka Timur Tengah, memandu ibadah secara khidmat, runut, dan sesuai sunnah.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/15 border border-amber-500/30 text-amber-800 flex items-center justify-center text-2xl font-bold">
                        <i class="fas fa-suitcase-rolling"></i>
                    </div>
                    <h3 class="text-lg font-bold text-stone-900">Perlengkapan Lengkap</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Koper premium, seragam eksklusif, kain ihram/mukena, tas selempang, buku panduan, hingga air zamzam resmi siap dibawa pulang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Program / Pilihan Paket Section (Payung Madinah Style) -->
    <section id="program" class="py-20" style="background: linear-gradient(180deg, rgba(241,235,223,0), rgba(241,235,223,0.85) 30%, rgba(241,235,223,0.85) 70%, rgba(241,235,223,0));">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-white/70 px-4 py-1.5 rounded-full border border-stone-200">
                        Program Pilihan
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight mt-3">
                        Pilih Destinasi Perjalanan Anda
                    </h2>
                    <p class="text-stone-600 text-sm sm:text-base mt-2 max-w-2xl">
                        Semua program sudah termasuk tiket pesawat internasional PP, visa resmi, hotel dekat masjid, makan 3x sehari menu Indonesia, dan pembimbing.
                    </p>
                </div>
                <a href="<?= base_url('/catalog') ?>" class="btn-ghost-gold px-6 py-3 rounded-full text-sm font-bold inline-flex items-center gap-2 self-start md:self-auto">
                    <span>Buka Semua Katalog</span>
                    <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            <!-- Dynamic & Featured Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Program 1: Umroh Plus Thaif -->
                <div class="pcard-pm rounded-3xl overflow-hidden flex flex-col group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80" 
                             alt="Thaif" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute top-4 left-4 px-3 py-1 rounded-full bg-amber-400 text-stone-950 font-bold text-xs uppercase tracking-wider shadow">
                            Favorit
                        </span>
                        <span class="absolute bottom-4 left-4 px-3.5 py-1 rounded-full bg-black/40 backdrop-blur-md text-white font-bold text-xs border border-white/20">
                            Thaif
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-stone-900 group-hover:text-[#5e0078] transition">Umroh Plus Thaif</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-xs text-stone-500">mulai</span>
                                <b class="text-2xl font-extrabold text-stone-900">Rp 30,9 Jt</b>
                            </div>
                            <p class="text-xs text-stone-600 mt-2 flex items-center gap-2">
                                <i class="far fa-calendar-alt text-amber-700"></i> 9 s.d 12 Hari Perjalanan
                            </p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">City Tour Thaif</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Kereta Gantung</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Haramain Train</span>
                            </div>
                        </div>
                        <a href="<?= base_url('/catalog') ?>" class="btn-ghost-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Tanya Jadwal &amp; Seat
                        </a>
                    </div>
                </div>

                <!-- Program 2: Umroh Plus Al-Ula -->
                <div class="pcard-pm rounded-3xl overflow-hidden flex flex-col group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1578895210405-907db486c111?auto=format&fit=crop&w=800&q=80" 
                             alt="Al-Ula" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute bottom-4 left-4 px-3.5 py-1 rounded-full bg-black/40 backdrop-blur-md text-white font-bold text-xs border border-white/20">
                            Al-Ula
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-stone-900 group-hover:text-[#5e0078] transition">Umroh Plus Al-Ula</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-xs text-stone-500">mulai</span>
                                <b class="text-2xl font-extrabold text-stone-900">Rp 31,9 Jt</b>
                            </div>
                            <p class="text-xs text-stone-600 mt-2 flex items-center gap-2">
                                <i class="far fa-calendar-alt text-amber-700"></i> 10 s.d 12 Hari Perjalanan
                            </p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Batu Gajah</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Madain Saleh</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Haramain Train</span>
                            </div>
                        </div>
                        <a href="<?= base_url('/catalog') ?>" class="btn-ghost-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Tanya Jadwal &amp; Seat
                        </a>
                    </div>
                </div>

                <!-- Program 3: Umroh Plus Dubai -->
                <div class="pcard-pm rounded-3xl overflow-hidden flex flex-col group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=800&q=80" 
                             alt="Dubai" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute bottom-4 left-4 px-3.5 py-1 rounded-full bg-black/40 backdrop-blur-md text-white font-bold text-xs border border-white/20">
                            Dubai
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-stone-900 group-hover:text-[#5e0078] transition">Umroh Plus Dubai</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-xs text-stone-500">mulai</span>
                                <b class="text-2xl font-extrabold text-stone-900">Rp 38,9 Jt</b>
                            </div>
                            <p class="text-xs text-stone-600 mt-2 flex items-center gap-2">
                                <i class="far fa-calendar-alt text-amber-700"></i> 10 s.d 12 Hari Perjalanan
                            </p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Dubai 2 Malam</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Burj Khalifa</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Desert Safari</span>
                            </div>
                        </div>
                        <a href="<?= base_url('/catalog') ?>" class="btn-ghost-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Tanya Jadwal &amp; Seat
                        </a>
                    </div>
                </div>

                <!-- Program 4: Umroh Plus Turki -->
                <div class="pcard-pm rounded-3xl overflow-hidden flex flex-col group">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1527838832700-5059252407fa?auto=format&fit=crop&w=800&q=80" 
                             alt="Turki" 
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <span class="absolute bottom-4 left-4 px-3.5 py-1 rounded-full bg-black/40 backdrop-blur-md text-white font-bold text-xs border border-white/20">
                            Turki
                        </span>
                    </div>
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="text-lg font-bold text-stone-900 group-hover:text-[#5e0078] transition">Umroh Plus Turki</h3>
                            <div class="mt-2 flex items-baseline gap-1">
                                <span class="text-xs text-stone-500">mulai</span>
                                <b class="text-2xl font-extrabold text-stone-900">Rp 42,9 Jt</b>
                            </div>
                            <p class="text-xs text-stone-600 mt-2 flex items-center gap-2">
                                <i class="far fa-calendar-alt text-amber-700"></i> 12 s.d 15 Hari Perjalanan
                            </p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Blue Mosque</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Bosphorus Cruise</span>
                                <span class="text-[11px] font-semibold bg-stone-100 text-stone-700 px-2.5 py-1 rounded-full">Hagia Sophia</span>
                            </div>
                        </div>
                        <a href="<?= base_url('/catalog') ?>" class="btn-ghost-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Tanya Jadwal &amp; Seat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Custom CTA Banner Inside Program -->
            <div class="mt-12 pcard-pm p-6 sm:p-8 rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-6 border border-white">
                <div>
                    <h3 class="text-lg font-extrabold text-stone-900">Belum yakin program mana yang paling tepat untuk keluarga?</h3>
                    <p class="text-xs sm:text-sm text-stone-600 mt-1">Konsultasikan perkiraan bulan keberangkatan dan jumlah jamaah. Kami siapkan rincian terbaik.</p>
                </div>
                <a href="https://wa.me/6281234567890?text=Halo%20Amanah%20Safar,%20mohon%20rekomendasi%20program%20umroh%20terbaik" target="_blank" rel="noopener noreferrer" 
                   class="btn-gold px-7 py-3 rounded-full text-xs font-bold whitespace-nowrap">
                    Minta Saran Petugas
                </a>
            </div>
        </div>
    </section>

    <!-- Jadwal Keberangkatan (Table List Style Payung Madinah) -->
    <section id="jadwal" class="py-20 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-12 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                    Jadwal Keberangkatan
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Pilih Tanggalnya, Kami Kunci Seat Anda
                </h2>
                <p class="text-stone-600 text-sm sm:text-base">
                    Jadwal resmi musim 1448 H. Harga per jamaah untuk kamar quad (sekamar berempat), sudah mencakup tiket pesawat PP, visa, hotel, makan 3x, dan manasik.
                </p>
                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-950 text-xs font-semibold">
                    <i class="fas fa-bookmark text-amber-600"></i>
                    <span>DP Rp 10 Juta mengunci seat, pelunasan paling lambat 1 bulan sebelum jadwal terbang</span>
                </div>
            </div>

            <!-- Schedule Rows -->
            <div class="space-y-4">
                <!-- Row 1 -->
                <div class="pcard-pm p-6 rounded-2xl grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    <div class="md:col-span-3">
                        <span class="text-2xl font-black text-stone-900">22 Okt <small class="text-xs font-bold text-stone-500">2026</small></span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase">Confirmed</span>
                            <span class="text-xs text-stone-500">12 Hari</span>
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-1">
                        <b class="text-base text-stone-900 block font-bold">Umroh Reguler Plus Thaif</b>
                        <p class="text-xs text-stone-600 flex items-center gap-2">
                            <i class="fas fa-plane text-stone-400"></i> Saudia Airlines (Direct Flight Jakarta - Jeddah)
                        </p>
                    </div>
                    <div class="md:col-span-3 text-left md:text-right">
                        <span class="text-xs text-stone-500 block">Kamar Quad (Platinum Bintang 5)</span>
                        <b class="text-xl font-extrabold text-[#5e0078]">Rp 32.500.000</b>
                    </div>
                    <div class="md:col-span-2">
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20seat%20Umroh%2022%20Oktober" target="_blank" rel="noopener noreferrer" 
                           class="btn-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Booking Seat
                        </a>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="pcard-pm p-6 rounded-2xl grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    <div class="md:col-span-3">
                        <span class="text-2xl font-black text-stone-900">14 Nov <small class="text-xs font-bold text-stone-500">2026</small></span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">Tersedia</span>
                            <span class="text-xs text-stone-500">10 Hari</span>
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-1">
                        <b class="text-base text-stone-900 block font-bold">Umroh Spesial Kereta Cepat</b>
                        <p class="text-xs text-stone-600 flex items-center gap-2">
                            <i class="fas fa-plane text-stone-400"></i> Garuda Indonesia (Direct Jakarta - Madinah)
                        </p>
                    </div>
                    <div class="md:col-span-3 text-left md:text-right">
                        <span class="text-xs text-stone-500 block">Kamar Quad (Pullman Zamzam)</span>
                        <b class="text-xl font-extrabold text-[#5e0078]">Rp 33.900.000</b>
                    </div>
                    <div class="md:col-span-2">
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20seat%20Umroh%2014%20November" target="_blank" rel="noopener noreferrer" 
                           class="btn-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Booking Seat
                        </a>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="pcard-pm p-6 rounded-2xl grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                    <div class="md:col-span-3">
                        <span class="text-2xl font-black text-stone-900">20 Des <small class="text-xs font-bold text-stone-500">2026</small></span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-800 uppercase">Libur Akhir Tahun</span>
                            <span class="text-xs text-stone-500">12 Hari</span>
                        </div>
                    </div>
                    <div class="md:col-span-4 space-y-1">
                        <b class="text-base text-stone-900 block font-bold">Umroh Liburan Keluarga &amp; Dubai</b>
                        <p class="text-xs text-stone-600 flex items-center gap-2">
                            <i class="fas fa-plane text-stone-400"></i> Emirates Airlines
                        </p>
                    </div>
                    <div class="md:col-span-3 text-left md:text-right">
                        <span class="text-xs text-stone-500 block">Kamar Quad (Full Executive)</span>
                        <b class="text-xl font-extrabold text-[#5e0078]">Rp 39.500.000</b>
                    </div>
                    <div class="md:col-span-2">
                        <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20booking%20seat%20Umroh%2020%20Desember" target="_blank" rel="noopener noreferrer" 
                           class="btn-gold w-full py-2.5 rounded-full text-center text-xs font-bold block">
                            Booking Seat
                        </a>
                    </div>
                </div>
            </div>

            <!-- Hotel Details Dropdown Accordion -->
            <div class="mt-8">
                <details class="pcard-pm rounded-2xl p-6 group cursor-pointer">
                    <summary class="font-bold text-stone-900 flex justify-between items-center text-sm sm:text-base">
                        <span><i class="fas fa-hotel text-amber-600 mr-2"></i> Pilihan Hotel &amp; Akomodasi per Kelas (Setaraf)</span>
                        <i class="fas fa-chevron-down text-xs text-stone-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <div class="mt-6 pt-6 border-t border-stone-200/60 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs sm:text-sm text-stone-600 leading-relaxed">
                        <div>
                            <b class="text-stone-900 block font-bold mb-1">Makkah Platinum (Bintang 5)</b>
                            <p>Pullman Zamzam, Makkah Tower, Swissotel Al Maqam, Jabal Omar Address, atau setaraf di ring 1 pelataran.</p>
                        </div>
                        <div>
                            <b class="text-stone-900 block font-bold mb-1">Makkah Silver (Bintang 4)</b>
                            <p>Al Shohada, Anjum Makkah, Marriott Makkah, Makarem Ajyad, atau setaraf (300-500m).</p>
                        </div>
                        <div>
                            <b class="text-stone-900 block font-bold mb-1">Madinah (Semua Kelas)</b>
                            <p>Grand Plaza Madinah, Al Saha, Concorde Dar Al Khair, Al Rawda Royal Inn, atau setaraf dekat Raudhah.</p>
                        </div>
                    </div>
                </details>
            </div>
        </div>
    </section>

    <!-- Manasik Umroh Akbar Section -->
    <section id="manasik" class="py-20 pm-canvas border-t border-stone-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-5 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                        Manasik Umroh Akbar
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight leading-tight">
                        Persiapan Sempurna Dimulai dari Sini
                    </h2>
                    <p class="text-stone-600 text-sm sm:text-base leading-relaxed">
                        Sebelum berangkat, seluruh jamaah mengikuti Manasik Umroh Akbar di ballroom hotel berbintang: dibekali ilmu syar’i, simulasi thawaf dan sa'i, serta temu ramah dengan Muthawwif pendamping.
                    </p>

                    <ul class="space-y-4 pt-2">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-800 flex items-center justify-center font-bold text-xs mt-0.5">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <b class="text-stone-900 text-sm block">Materi Manasik Lengkap &amp; Syar'i</b>
                                <span class="text-xs text-stone-600">Disampaikan langsung oleh Ustadz berpengalaman alumni Timur Tengah.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-800 flex items-center justify-center font-bold text-xs mt-0.5">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <b class="text-stone-900 text-sm block">Simulasi Praktek Thawaf &amp; Sa'i</b>
                                <span class="text-xs text-stone-600">Praktek memakai kain ihram yang benar dan alur tahallul langsung.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-800 flex items-center justify-center font-bold text-xs mt-0.5">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <b class="text-stone-900 text-sm block">Tanya Jawab Tuntas &amp; Ramah Tamah</b>
                                <span class="text-xs text-stone-600">Konsultasi kondisi kesehatan dan kebutuhan khusus sebelum terbang.</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="lg:col-span-7">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-3xl overflow-hidden shadow-lg h-64 sm:h-72">
                            <img src="https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=800&q=80" 
                                 alt="Manasik Jamaah" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="rounded-3xl overflow-hidden shadow-lg h-64 sm:h-72 mt-8">
                            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80" 
                                 alt="Kajian Manasik" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cara Daftar (4 Langkah Sederhana) -->
    <section id="alur" class="py-20 bg-stone-50/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                    Cara Daftar
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Empat Langkah, Semuanya Ditemani
                </h2>
                <p class="text-stone-600 text-sm sm:text-base">
                    Kemudahan pendaftaran tanpa ribet, dibantu petugas customer service hingga selesai.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="step-item pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="stepper-num w-12 h-12 rounded-full bg-white border-2 border-stone-300 text-amber-800 font-extrabold flex items-center justify-center text-base shadow">
                        1
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 block">Langkah 1</span>
                    <h3 class="text-lg font-bold text-stone-900">Konsultasi Rencana</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Hubungi admin via WhatsApp, ceritakan perkiraan tanggal dan jumlah jamaah. Kami kirimkan rincian jadwal dan biaya.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="step-item pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="stepper-num w-12 h-12 rounded-full bg-white border-2 border-stone-300 text-amber-800 font-extrabold flex items-center justify-center text-base shadow">
                        2
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 block">Langkah 2</span>
                    <h3 class="text-lg font-bold text-stone-900">Kunci Seat &amp; DP</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Uang muka (DP) langsung mengunci nomor kursi penerbangan Anda. Sisanya dapat dicicil bertahap tanpa bunga.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="step-item pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="stepper-num w-12 h-12 rounded-full bg-white border-2 border-stone-300 text-amber-800 font-extrabold flex items-center justify-center text-base shadow">
                        3
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 block">Langkah 3</span>
                    <h3 class="text-lg font-bold text-stone-900">Manasik Akbar</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Sebelum hari keberangkatan, Anda mengikuti Manasik Akbar: tata cara niat, thawaf, sa'i, dan pembagian seragam &amp; koper.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="step-item pcard-pm p-7 rounded-3xl space-y-4">
                    <div class="stepper-num w-12 h-12 rounded-full bg-white border-2 border-stone-300 text-amber-800 font-extrabold flex items-center justify-center text-base shadow">
                        4
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-700 block">Langkah 4</span>
                    <h3 class="text-lg font-bold text-stone-900">Terbang Khusyuk</h3>
                    <p class="text-xs sm:text-sm text-stone-600 leading-relaxed">
                        Temu jamaah di lounge bandara, didampingi Tour Leader sejak Jakarta hingga hotel di Makkah &amp; Madinah.
                    </p>
                </div>
            </div>

            <!-- Start CTA Button -->
            <div class="mt-12 text-center">
                <a href="https://wa.me/6281234567890?text=Halo%20Amanah%20Safar,%20saya%20ingin%20memulai%20langkah%201%20konsultasi%20umroh" target="_blank" rel="noopener noreferrer" 
                   class="btn-gold px-8 py-4 rounded-full font-bold text-sm inline-flex items-center gap-2">
                    <i class="fab fa-whatsapp text-lg"></i>
                    <span>Mulai dari Langkah 1: Konsultasi Sekarang</span>
                </a>
                <p class="text-xs text-stone-500 mt-2">Layanan gratis dan tanpa ikatan apapun.</p>
            </div>
        </div>
    </section>

    <!-- Transparansi Biaya (Include & Exclude Section) -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-12 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                    Rincian Transparan
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Tidak Ada Biaya Tersembunyi di Hari Keberangkatan
                </h2>
                <p class="text-stone-600 text-sm sm:text-base">
                    Kami mencantumkan fasilitas secara terbuka sejak awal agar ibadah Anda tenang dan terencana.
                </p>
            </div>

            <!-- Inclusions Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="tile-pm p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-800 flex items-center justify-center text-xl font-bold flex-none">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <div>
                        <b class="text-stone-900 text-sm block font-bold">Tiket Pesawat PP</b>
                        <span class="text-xs text-stone-500">Penerbangan maskapai internasional</span>
                    </div>
                </div>

                <div class="tile-pm p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-800 flex items-center justify-center text-xl font-bold flex-none">
                        <i class="fas fa-passport"></i>
                    </div>
                    <div>
                        <b class="text-stone-900 text-sm block font-bold">Visa Umroh Resmi</b>
                        <span class="text-xs text-stone-500">Pengurusan langsung oleh tim pusat</span>
                    </div>
                </div>

                <div class="tile-pm p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-800 flex items-center justify-center text-xl font-bold flex-none">
                        <i class="fas fa-hotel"></i>
                    </div>
                    <div>
                        <b class="text-stone-900 text-sm block font-bold">Hotel Sesuai Program</b>
                        <span class="text-xs text-stone-500">Bintang 5 / 4 dekat pelataran masjid</span>
                    </div>
                </div>

                <div class="tile-pm p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-800 flex items-center justify-center text-xl font-bold flex-none">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <b class="text-stone-900 text-sm block font-bold">Makan 3x Sehari</b>
                        <span class="text-xs text-stone-500">Menu masakan khas nusantara</span>
                    </div>
                </div>

                <div class="tile-pm p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-800 flex items-center justify-center text-xl font-bold flex-none">
                        <i class="fas fa-bus-alt"></i>
                    </div>
                    <div>
                        <b class="text-stone-900 text-sm block font-bold">Transportasi Bus AC</b>
                        <span class="text-xs text-stone-500">Bus pariwisata eksekutif terbaru</span>
                    </div>
                </div>

                <div class="tile-pm p-5 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/15 text-amber-800 flex items-center justify-center text-xl font-bold flex-none">
                        <i class="fas fa-wine-bottle"></i>
                    </div>
                    <div>
                        <b class="text-stone-900 text-sm block font-bold">Air Zamzam Resmi</b>
                        <span class="text-xs text-stone-500">Air zamzam 5 liter dibawa pulang</span>
                    </div>
                </div>
            </div>

            <!-- Exclusions Note Box -->
            <div class="mt-8 p-6 rounded-2xl bg-stone-900 text-stone-300 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-stone-800 text-amber-400 flex items-center justify-center text-lg flex-none">
                        <i class="fas fa-info"></i>
                    </div>
                    <div>
                        <b class="text-white text-sm block font-semibold">Belum Termasuk dalam Paket Utama:</b>
                        <span class="text-xs text-stone-400">Pembuatan paspor pribadi, vaksin meningitis/polio, pengeluaran laundry pribadi, dan kelebihan bagasi.</span>
                    </div>
                </div>
                <span class="text-xs text-amber-400 font-semibold uppercase tracking-wider whitespace-nowrap">
                    Transparansi 100%
                </span>
            </div>
        </div>
    </section>

    <!-- Testimoni / Ulasan Jamaah -->
    <section class="py-20 pm-canvas border-t border-stone-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                    Ulasan Jamaah
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Kata Mereka yang Sudah Berangkat
                </h2>
                <p class="text-stone-600 text-sm sm:text-base">
                    Pengalaman nyata keluarga jamaah yang merasakan kenyamanan ibadah bersama Amanah Safar.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="pcard-pm p-8 rounded-3xl flex flex-col justify-between space-y-6">
                    <p class="text-stone-700 text-sm leading-relaxed italic">
                        “Pelayanan Amanah Safar sangat luar biasa. Hotel benar-benar dekat di pelataran Ka'bah, sehingga orang tua kami yang lansia tidak kelelahan setiap ingin shalat berjamaah di masjid. Sangat recommended!”
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-200/60">
                        <div class="w-10 h-10 rounded-full bg-amber-600 text-white flex items-center justify-center font-bold text-sm">
                            AL
                        </div>
                        <div>
                            <b class="text-stone-900 text-sm block">Anita Lestari</b>
                            <span class="text-xs text-stone-500">Jamaah Umroh Plus Thaif</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="pcard-pm p-8 rounded-3xl flex flex-col justify-between space-y-6">
                    <p class="text-stone-700 text-sm leading-relaxed italic">
                        “Manasik akbarnya sangat detail dan interaktif. Kami dibimbing dari nol sampai paham rukun dan sunnahnya. Makanan hotel menu nusantara cocok sekali dengan lidah kami. Terima kasih tim Amanah Safar.”
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-200/60">
                        <div class="w-10 h-10 rounded-full bg-purple-700 text-white flex items-center justify-center font-bold text-sm">
                            HN
                        </div>
                        <div>
                            <b class="text-stone-900 text-sm block">H. Hendra Kurniawan</b>
                            <span class="text-xs text-stone-500">Jamaah Umroh Eksekutif</span>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="pcard-pm p-8 rounded-3xl flex flex-col justify-between space-y-6">
                    <p class="text-stone-700 text-sm leading-relaxed italic">
                        “Selain sebagai jamaah, saya juga terdaftar sebagai mitra. Transparansi pembagian komisi dan ketersediaan materi promosi sangat membantu saya mengajak keluarga dan relasi ke Baitullah.”
                    </p>
                    <div class="flex items-center gap-3 pt-4 border-t border-stone-200/60">
                        <div class="w-10 h-10 rounded-full bg-emerald-700 text-white flex items-center justify-center font-bold text-sm">
                            FN
                        </div>
                        <div>
                            <b class="text-stone-900 text-sm block">Fauziah Nur</b>
                            <span class="text-xs text-stone-500">Mitra Amanah Safar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-stone-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-bold uppercase tracking-widest text-amber-800 bg-amber-100/60 px-4 py-1.5 rounded-full border border-amber-200">
                    Pertanyaan Populer
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-stone-900 tracking-tight">
                    Sebelum Anda Mendaftar
                </h2>
                <p class="text-stone-600 text-sm sm:text-base">
                    Jawaban atas pertanyaan yang paling sering diajukan seputar paket safar dan kepastian keberangkatan.
                </p>
            </div>

            <div class="max-w-4xl mx-auto space-y-4">
                <!-- FAQ 1 -->
                <details class="pcard-pm p-6 rounded-2xl group cursor-pointer">
                    <summary class="font-bold text-stone-900 flex justify-between items-center text-sm sm:text-base">
                        <span>Apakah Amanah Safar resmi dan terdaftar di Kemenag?</span>
                        <i class="fas fa-chevron-down text-xs text-stone-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-stone-600 leading-relaxed border-t border-stone-200/60 pt-4">
                        Ya, Amanah Safar beroperasi dengan izin resmi PPIU Kementerian Agama RI. Anda dapat melakukan verifikasi izin legalitas secara mandiri melalui portal resmi SIMPU Kemenag.
                    </p>
                </details>

                <!-- FAQ 2 -->
                <details class="pcard-pm p-6 rounded-2xl group cursor-pointer">
                    <summary class="font-bold text-stone-900 flex justify-between items-center text-sm sm:text-base">
                        <span>Berapa nominal DP untuk mengunci kursi keberangkatan?</span>
                        <i class="fas fa-chevron-down text-xs text-stone-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-stone-600 leading-relaxed border-t border-stone-200/60 pt-4">
                        Cukup dengan uang muka (DP) sebesar Rp 10.000.000 per jamaah untuk mengunci seat penerbangan. Pelunasan sisa biaya dapat dicicil dan diselesaikan selambat-lambatnya 1 bulan sebelum tanggal terbang.
                    </p>
                </details>

                <!-- FAQ 3 -->
                <details class="pcard-pm p-6 rounded-2xl group cursor-pointer">
                    <summary class="font-bold text-stone-900 flex justify-between items-center text-sm sm:text-base">
                        <span>Bagaimana pendampingan bagi jamaah lansia atau kebutuhan khusus?</span>
                        <i class="fas fa-chevron-down text-xs text-stone-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-stone-600 leading-relaxed border-t border-stone-200/60 pt-4">
                        Setiap rombongan didampingi oleh Tour Leader dan Muthawwif bersertifikat. Lokasi hotel yang berada di ring 1 pelataran masjid meminimalkan jarak jalan kaki, dan tim kami siap memfasilitasi kursi roda jika dibutuhkan.
                    </p>
                </details>

                <!-- FAQ 4 -->
                <details class="pcard-pm p-6 rounded-2xl group cursor-pointer">
                    <summary class="font-bold text-stone-900 flex justify-between items-center text-sm sm:text-base">
                        <span>Bagaimana cara mendaftar sebagai mitra agen Amanah Safar?</span>
                        <i class="fas fa-chevron-down text-xs text-stone-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="mt-4 text-xs sm:text-sm text-stone-600 leading-relaxed border-t border-stone-200/60 pt-4">
                        Anda dapat mendaftar melalui tautan Daftar Mitra pada menu atas dengan memasukkan kode voucher kemitraan yang Anda miliki, lalu melengkapi proses verifikasi data akun.
                    </p>
                </details>
            </div>
        </div>
    </section>

    <!-- Konsultasi / Form Bottom CTA Section -->
    <section class="py-20 bg-stone-900 text-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <span class="text-xs font-bold uppercase tracking-widest text-amber-400 bg-white/10 px-4 py-1.5 rounded-full border border-white/20">
                        Mulai dari Sini
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                        Niatkan Hari Ini, Berangkat dengan Tenang.
                    </h2>
                    <p class="text-stone-300 text-sm sm:text-base leading-relaxed max-w-xl">
                        Ceritakan rencana ibadah Anda. Customer service resmi kami siap membalas dan memberikan konsultasi lengkap jadwal dan biaya.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="https://wa.me/6281234567890?text=Halo%20Amanah%20Safar,%20saya%20ingin%20konsultasi%20paket%20ibadah" target="_blank" rel="noopener noreferrer" 
                           class="btn-gold px-8 py-4 rounded-full font-bold text-sm inline-flex items-center gap-2">
                            <i class="fab fa-whatsapp text-lg"></i>
                            <span>Chat WhatsApp Petugas</span>
                        </a>
                        <a href="<?= base_url('/catalog') ?>" class="px-8 py-4 rounded-full bg-white/10 hover:bg-white/20 font-bold text-sm border border-white/20 transition">
                            Lihat Jadwal Lengkap
                        </a>
                    </div>
                </div>

                <!-- Fast Info Card -->
                <div class="lg:col-span-5 bg-white/5 border border-white/15 rounded-3xl p-8 backdrop-blur-md space-y-4">
                    <h3 class="text-lg font-bold text-amber-300">Kantor Layanan Konsultasi</h3>
                    <p class="text-xs text-stone-300 leading-relaxed">
                        Silakan berkunjung langsung ke kantor representatif kami untuk konsultasi tatap muka:
                    </p>
                    <div class="space-y-3 pt-2 text-xs text-stone-300">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-amber-400 mt-1"></i>
                            <span>Gedung Graha Pool Lantai 3 unit 303-304, RT.03/RW.03, Ciwaringin, Kecamatan Bogor Tengah, Kota Bogor, Jawa Barat 16124</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone-alt text-amber-400"></i>
                            <span>(021) 7890-1234</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-amber-400"></i>
                            <span>info@amanahsafar.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (Sesuai Konfigurasi Terkini Proyek) -->
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
                        <li><a href="#jadwal" class="hover:text-white transition">Jadwal Keberangkatan</a></li>
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
                <p>&copy; <?= date('Y') ?> Amanah Safar. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition">Syarat &amp; Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

</div>
<?= $this->endSection() ?>
