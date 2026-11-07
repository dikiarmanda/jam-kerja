<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Jam Kerja' ?></title>
    <link rel="stylesheet" href="<?= base_url('css/app.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function() {
            try {
                const storedTheme = localStorage.getItem('jamkerja_theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="flex flex-col min-h-screen">

<?php
$uri = uri_string();
$isProjects = ($uri === '' || str_starts_with($uri, 'projects'));
$isSessions = str_starts_with($uri, 'sessions');
$isTags     = str_starts_with($uri, 'tags');
?>

<header class="nav-glass sticky top-0 z-50">
    <div style="max-width:1120px;" class="mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between gap-4">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5 text-decoration-none group" style="text-decoration:none;">
            <div style="width:34px;height:34px;background:var(--accent);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#ffffff;box-shadow:0 2px 8px rgba(37,99,235,0.3);flex-shrink:0;">
                <i class="fa-solid fa-stopwatch" style="font-size:0.9375rem;"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-mono font-bold tracking-tight" style="color:var(--text);font-size:1.125rem;line-height:1.2;">
                    jam<span style="color:var(--accent);">·</span>kerja
                </span>
                <span style="font-size:0.6875rem;color:var(--text-dim);line-height:1;font-weight:500;">Pelacak Waktu Proyek</span>
            </div>
        </a>

        <div class="flex items-center gap-1.5 sm:gap-2">
            <nav class="flex items-center gap-1">
                <a href="<?= base_url('/') ?>" class="nav-link <?= $isProjects ? 'active' : '' ?>">
                    <i class="fa-solid fa-folder-open" style="font-size:0.875rem;"></i>
                    <span class="hide-mobile">Proyek</span>
                </a>
                <a href="<?= base_url('sessions') ?>" class="nav-link <?= $isSessions ? 'active' : '' ?>">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size:0.875rem;"></i>
                    <span class="hide-mobile">Sesi</span>
                </a>
                <a href="<?= base_url('tags') ?>" class="nav-link <?= $isTags ? 'active' : '' ?>">
                    <i class="fa-solid fa-tags" style="font-size:0.875rem;"></i>
                    <span class="hide-mobile">Tag</span>
                </a>
            </nav>

            <div style="width:1px;height:24px;background:var(--border);margin:0 4px;" class="hide-mobile"></div>

            <!-- Theme Toggle -->
            <button type="button" id="theme-toggle" class="theme-toggle-btn" title="Ganti mode tampilan (Light / Dark)" aria-label="Ganti mode tampilan">
                <i class="fa-solid fa-sun" id="theme-toggle-icon" style="font-size:0.9375rem;"></i>
            </button>

            <!-- New Project Button -->
            <a href="<?= base_url('projects/create') ?>" class="btn btn-primary btn-sm" style="font-weight:600;">
                <i class="fa-solid fa-plus"></i>
                <span class="hide-mobile">Proyek Baru</span>
            </a>
        </div>
    </div>
</header>

<main style="max-width:1120px;" class="w-full mx-auto px-4 sm:px-6 py-6 sm:py-8 flex-1">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success" id="flash-success">
            <i class="fa-solid fa-circle-check" style="margin-top:2px;font-size:1.1rem;flex-shrink:0;"></i>
            <div style="flex:1;"><?= esc(session()->getFlashdata('success')) ?></div>
            <button type="button" onclick="document.getElementById('flash-success').remove()" style="background:none;border:none;color:inherit;cursor:pointer;opacity:0.6;padding:2px;" title="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error" id="flash-error">
            <i class="fa-solid fa-circle-exclamation" style="margin-top:2px;font-size:1.1rem;flex-shrink:0;"></i>
            <div style="flex:1;"><?= esc(session()->getFlashdata('error')) ?></div>
            <button type="button" onclick="document.getElementById('flash-error').remove()" style="background:none;border:none;color:inherit;cursor:pointer;opacity:0.6;padding:2px;" title="Tutup">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>

<footer class="mt-auto py-6" style="border-top:1px solid var(--border);">
    <div style="max-width:1120px;" class="mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs" style="color:var(--text-dim);">
        <div class="flex items-center gap-2">
            <span class="font-mono font-semibold" style="color:var(--text-muted);">jam·kerja</span>
            <span>&bull;</span>
            <span style="color:var(--text-dim);">Pelacak Jam Kerja & Estimasi Pendapatan</span>
        </div>
        <div class="flex items-center gap-4">
            <span style="color:var(--text-dim);">&copy; <?= date('Y') ?> Jam Kerja</span>
        </div>
    </div>
</footer>

<script>
(function() {
    const toggleBtn = document.getElementById('theme-toggle');
    const icon = document.getElementById('theme-toggle-icon');
    
    function updateThemeIcon() {
        const isDark = document.documentElement.classList.contains('dark');
        if (icon) {
            icon.className = isDark ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
        }
    }
    
    updateThemeIcon();

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('jamkerja_theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('jamkerja_theme', 'dark');
            }
            updateThemeIcon();
            window.dispatchEvent(new Event('themechange'));
        });
    }
})();
</script>

<?= $this->renderSection('scripts') ?>
</body>
</html>
