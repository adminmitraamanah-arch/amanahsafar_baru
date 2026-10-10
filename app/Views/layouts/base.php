<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Amanah Safar' ?></title>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#5e0078',
                            50: '#fbf5fd',
                            100: '#f6eafb',
                            200: '#edd5f7',
                            300: '#dfb2f1',
                            400: '#ca82e6',
                            500: '#8f20ad',
                            600: '#5e0078', // Warna dominan #5e0078
                            700: '#4c0062',
                            800: '#3b004c',
                            900: '#280034',
                            950: '#17001e',
                        },
                        blue: {
                            50: '#fbf5fd',
                            100: '#f6eafb',
                            200: '#edd5f7',
                            300: '#dfb2f1',
                            400: '#ca82e6',
                            500: '#8f20ad',
                            600: '#5e0078', // Warna dominan #5e0078
                            700: '#4c0062',
                            800: '#3b004c',
                            900: '#280034',
                            950: '#17001e',
                        },
                        indigo: {
                            50: '#faf3fc',
                            100: '#f5e4fa',
                            200: '#ebcaf5',
                            300: '#dda5ee',
                            400: '#c876e2',
                            500: '#8f20ad',
                            600: '#73139b',
                            700: '#5e0078', // Warna dominan #5e0078
                            800: '#450059',
                            900: '#320040',
                            950: '#1f0028',
                        },
                        slate: {
                            700: '#3f0452',
                            800: '#290035',
                            900: '#1b0024',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --ease-out: cubic-bezier(0, 0, 0.2, 1);
            --ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
            --ease-enter: cubic-bezier(0.16, 1, 0.3, 1);
            --dur-fast: 160ms;
            --dur-normal: 220ms;
            --dur-page: 260ms;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        /* Page Transition: Smooth fade-in (pure opacity to avoid breaking position: fixed viewport) */
        @keyframes pageFadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .page-fade-in {
            animation: pageFadeIn var(--dur-page) var(--ease-enter) forwards;
        }

        /* Micro-interactions: Buttons */
        .btn-interactive {
            transition: all var(--dur-fast) var(--ease-out);
            position: relative;
            user-select: none;
        }
        .btn-interactive:hover {
            transform: translateY(-1px);
        }
        .btn-interactive:active {
            transform: scale(0.98) translateY(0);
        }
        .btn-interactive:focus-visible {
            outline: 2px solid #5e0078;
            outline-offset: 2px;
        }
        .btn-interactive:disabled,
        .btn-interactive[disabled] {
            pointer-events: none;
            opacity: 0.6;
            transform: none !important;
        }

        /* Micro-interactions: Cards */
        .card-interactive {
            transition: transform var(--dur-normal) var(--ease-out), 
                        box-shadow var(--dur-normal) var(--ease-out), 
                        border-color var(--dur-normal) var(--ease-out);
        }
        .card-interactive:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px -8px rgba(94, 0, 120, 0.12);
        }

        /* Micro-interactions: Table Rows */
        .table-row-interactive {
            transition: background-color var(--dur-fast) var(--ease-out);
        }
        .table-row-interactive:hover {
            background-color: rgba(251, 245, 253, 0.7);
        }

        /* Micro-interactions: Inputs */
        input:not([type="checkbox"]):not([type="radio"]), 
        select, 
        textarea {
            transition: border-color var(--dur-fast) var(--ease-out), 
                        box-shadow var(--dur-fast) var(--ease-out),
                        background-color var(--dur-fast) var(--ease-out);
        }

        /* Smooth scrollbars */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #fbf5fd; }
        ::-webkit-scrollbar-thumb { background: #dfb2f1; border-radius: 4px; transition: background 0.2s; }
        ::-webkit-scrollbar-thumb:hover { background: #5e0078; }

        /* Accessibility: Respect Prefers Reduced Motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
                transform: none !important;
            }
            .page-fade-in {
                animation: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#faf7fb] text-slate-900 antialiased page-fade-in">
    <?= $this->renderSection('content') ?>
</body>
</html>
