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
    <!-- Compiled Static Tailwind CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.min.css') ?>">
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
