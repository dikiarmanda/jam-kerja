<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center gap-2.5">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight m-0" style="color:var(--text);">Riwayat Sesi Kerja</h1>
            <span class="badge"><?= count($entries) ?></span>
        </div>
        <p class="text-xs sm:text-sm mt-1 mb-0" style="color:var(--text-muted);">
            Daftar seluruh sesi timer otomatis dan pencatatan manual dari semua proyek.
        </p>
    </div>

    <a href="<?= base_url('sessions/create' . ($selectedProjectId ? '?project_id=' . $selectedProjectId : '')) ?>" class="btn btn-primary btn-sm shrink-0">
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Sesi Manual</span>
    </a>
</div>

<!-- Project Filter Pills -->
<?php if (!empty($projects)): ?>
<div class="flex items-center gap-2 mb-6 flex-wrap">
    <span class="text-xs font-semibold mr-1" style="color:var(--text-muted);">
        <i class="fa-solid fa-filter" style="font-size:0.6875rem;"></i> Filter:
    </span>
    <a href="<?= base_url('sessions') ?>" class="filter-tab <?= !$selectedProjectId ? 'active' : '' ?>">
        <span>Semua Proyek</span>
    </a>
    <?php foreach ($projects as $proj): ?>
    <a href="<?= base_url('sessions?project_id=' . $proj['id']) ?>" class="filter-tab <?= (int)$selectedProjectId === (int)$proj['id'] ? 'active' : '' ?>">
        <span><?= esc($proj['name']) ?></span>
    </a>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Entries List -->
<?php if (empty($entries)): ?>
<div class="empty-state">
    <div class="empty-icon"><i class="fa-regular fa-clock"></i></div>
    <h3 class="text-base font-semibold mb-1" style="color:var(--text);">Belum Ada Sesi Tercatat</h3>
    <p style="color:var(--text-muted);font-size:0.875rem;max-width:380px;margin:0 auto 20px auto;">
        Mulai jalankan timer dari dashboard atau tambahkan sesi kerja lampau secara manual.
    </p>
    <a href="<?= base_url('sessions/create' . ($selectedProjectId ? '?project_id=' . $selectedProjectId : '')) ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>Tambah Sesi Manual
    </a>
</div>
<?php else: ?>
<div class="flex flex-col gap-2.5">
    <?php foreach ($entries as $entry): ?>
    <?php $isRunning = $entry['ended_at'] === null; ?>
    <div class="session-row <?= $isRunning ? 'running' : '' ?>">
        <div class="flex items-center gap-3 sm:gap-4 flex-wrap min-w-0 flex-1">
            <!-- Project Link -->
            <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="inline-flex items-center gap-1.5 font-semibold text-sm transition-colors hover:underline" style="color:var(--accent);text-decoration:none;">
                <i class="fa-regular fa-folder" style="font-size:0.8125rem;"></i>
                <span class="truncate max-w-xs"><?= esc($entry['project_name']) ?></span>
            </a>

            <!-- Running indicator -->
            <?php if ($isRunning): ?>
            <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold" style="background:var(--green-subtle);border:1px solid var(--green-border);color:var(--green-text);">
                <span class="pulse-dot"></span>
                <span>BERJALAN</span>
            </div>
            <?php endif; ?>

            <!-- Start Time -->
            <div class="flex items-center gap-1.5 text-xs">
                <span style="color:var(--text-dim);">Mulai:</span>
                <span class="font-mono font-medium" style="color:var(--text);">
                    <?= date('d/m/Y H:i', strtotime($entry['started_at'])) ?>
                </span>
            </div>

            <!-- End Time & Duration -->
            <?php if (!$isRunning): ?>
            <div class="flex items-center gap-1.5 text-xs">
                <span style="color:var(--text-dim);">Selesai:</span>
                <span class="font-mono font-medium" style="color:var(--text);">
                    <?= date('d/m/Y H:i', strtotime($entry['ended_at'])) ?>
                </span>
            </div>

            <div class="font-mono font-bold px-2 py-0.5 rounded text-xs" style="background:var(--surface);border:1px solid var(--border);color:var(--text);">
                <?= format_duration((int)$entry['duration_seconds']) ?>
            </div>
            <?php endif; ?>

            <!-- Note -->
            <?php if (!empty($entry['note'])): ?>
            <div class="flex items-center gap-1 text-xs truncate max-w-md italic" style="color:var(--text-muted);">
                <i class="fa-regular fa-comment-dots" style="font-size:0.75rem;color:var(--text-dim);"></i>
                <span class="truncate">&ldquo;<?= esc($entry['note']) ?>&rdquo;</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-1.5 shrink-0">
            <a href="<?= base_url('sessions/' . $entry['id'] . '/edit') ?>" class="btn btn-ghost btn-icon btn-sm" title="Edit sesi">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form method="POST" action="<?= base_url('sessions/' . $entry['id'] . '/delete') ?>" onsubmit="return confirm('Hapus catatan sesi ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus sesi">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
