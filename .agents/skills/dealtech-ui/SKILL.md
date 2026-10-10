---
name: dealtech-ui
description: Use DealTech UI public component library as templates and reference design system (Elements, Sections, Pages). Use when building modern React/TSX or HTML/CSS landing pages, hero sections, pricing cards, testimonials, CTAs, headers, footers, or applying #PakaiAiTapiBerasaBukanAi clean design guidelines.
---

# DealTech UI Component Skill

Skill panduan dan pustaka referensi komponen UI dari [DealTech UI](https://github.com/Deal-Tech/dealtech-ui-for-public-component) (`tech.mudahdeal.com`).

Prinsip utama: **`#PakaiAiTapiBerasaBukanAi`** — Menghindari tampilan layout AI yang generik dengan mengacu pada struktur section & element terkurasi, berkarakter, mandiri, dan responsif.

---

## 1. Katalog Komponen Tersedia (di `references/`)

Komponen lengkap tersimpan di directori [`.agents/skills/dealtech-ui/references/`](./references):

### Elements (`references/elements/`)
Unit terkecil, mandiri:
- **Button**: `ButtonV1`
- **Card**: `PriceCardV1`
- **Footer**: `SimpleFooterV1`, `SimpleFooterV2`
- **Header**: `SimpleHeaderV1`, `SimpleHeaderV2`
- **Search**: `SearchV1`, `SearchV2`
- **TabButtons**: `StyleV1`
- **text**: `HeadingStrokeV1`, `ParagrafV1`

### Sections (`references/sections/`)
Blok halaman siap pakai:
- **Hero**: `SectionV1` s/d `SectionV13`
- **Features**: `CardListV1` s/d `CardListV4`
- **About**: `AboutV1` s/d `AboutV6`
- **CTA**: `CTAV1` s/d `CTAV6`
- **PriceList**: `PricelistV1` s/d `PricelistV5`
- **Testimonial**: `ReviewV1` s/d `ReviewV7`
- **FAQ**: `FAQV1` s/d `FAQV5`
- **Contact**: `ContactV1` s/d `ContactV5`
- **Service**: `ServiceV1` s/d `ServiceV3`
- **Compare**: `SectionCompareV1` s/d `SectionCompareV3`
- **Chekout**: `ChekoutV1`

### Pages (`references/pages/`)
Halaman lengkap hasil integrasi section dan element.

---

## 2. Standar Desain & Aturan Kode DealTech UI

Saat membuat atau mengadaptasi komponen baru:

1. **Struktur Mandiri**: Satu komponen dalam satu folder tersendiri. Section tidak boleh bergantung pada file eksternal yang sulit dipindah.
2. **Tipografi & Warna**:
   - Font: `Inter`
   - Aksen: Biru (`#0066FF` / brand blue)
   - Background utama: Bersih (putih/neutral soft)
3. **Shadow & Depth**:
   - Opasitas `box-shadow` dan `drop-shadow` maksimal **10% (`0.1`)** agar tidak tampak kotor/berlebihan.
4. **Ikon**:
   - Gunakan ikon SVG (seperti `lucide-react` / SVG clean). Hindari emoji sebagai icon tombol atau navigasi.
5. **Spacing & Ukuran Standar**:
   - Judul Hero: `clamp(1.5rem, 3.1vw, 2.25rem)` (maks desktop `36px`).
   - Judul non-Hero: `clamp(1.5rem, 3.1vw, 2rem)` (maks desktop `32px`).
   - Padding section desktop & tablet (>640px): `45px 0`.
   - Padding section mobile (≤640px): `36px 0 32px`.
   - Lebar container desktop: `min(1160px, calc(100% - 44px))`.
   - Lebar container mobile: `min(1160px, calc(100% - 36px))`.
   - Gap 2 kolom: `clamp(36px, 5vw, 72px)`.
   - Jarak judul ke deskripsi: `9px`.
   - Jarak deskripsi ke tombol CTA: `26px`.
   - Gap antarkartu: `10px` (desktop), `8px` (mobile).
   - Padding kartu: `22px 20px 18px` (desktop), `19px 16px` (mobile).

---

## 3. Cara Menggunakan Skill Ini

1. **Pilih referensi**: Cari komponen/section yang diinginkan di `references/sections/` atau `references/elements/`.
2. **Lihat implementasi**: Buka file `.tsx` dan `.css` dari komponen tersebut untuk melihat arsitektur markup dan stylenya.
3. **Adaptasikan ke proyek**: Salin dan sesuaikan konten, warna tema, atau bahasa pemrograman target (PHP / React / Blade / Vue / Plain HTML).
