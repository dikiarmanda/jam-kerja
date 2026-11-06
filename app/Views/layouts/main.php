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
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;1,400&family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="nav-glass sticky top-0 z-50">
    <div style="max-width:1100px;" class="mx-auto px-5 py-3 flex items-center justify-between">
        <a href="<?= base_url('/') ?>" class="font-mono font-bold tracking-tight" style="color:var(--accent);font-size:1.0625rem;text-decoration:none;letter-spacing:-0.01em;">
            jam·kerja
        </a>
        <div class="flex items-center gap-1">
            <a href="<?= base_url('/') ?>" class="nav-link"><i class="fa-solid fa-folder-open" style="font-size:0.8125rem;margin-right:5px;"></i>Proyek</a>
            <a href="<?= base_url('tags') ?>" class="nav-link"><i class="fa-solid fa-tags" style="font-size:0.8125rem;margin-right:5px;"></i>Tag</a>
            <a href="<?= base_url('projects/create') ?>" class="btn btn-primary" style="padding:6px 14px;font-size:0.8125rem;margin-left:8px;">
                <i class="fa-solid fa-plus"></i>Proyek baru
            </a>
        </div>
    </div>
</nav>

<main style="max-width:1100px;" class="mx-auto px-5 py-8">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="flash flash-success">
            <i class="fa-solid fa-circle-check" style="margin-top:1px;flex-shrink:0;"></i>
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="flash flash-error">
            <i class="fa-solid fa-circle-exclamation" style="margin-top:1px;flex-shrink:0;"></i>
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</main>

<?= $this->renderSection('scripts') ?>
</body>
</html>
