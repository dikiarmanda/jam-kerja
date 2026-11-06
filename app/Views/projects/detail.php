<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$totalSec    = (int)$project['total_seconds'];
$value       = calc_value($totalSec, (int)$project['hourly_rate']);
$activeEntry = $project['active_entry'] ?? null;
?>

<div style="margin-bottom:28px;">
    <a href="<?= base_url('/') ?>" class="back-link" style="margin-bottom:16px;display:inline-flex;">
        <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>Semua proyek
    </a>
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <div>
            <h1 style="font-size:1.375rem;font-weight:600;color:var(--text);line-height:1.3;"><?= esc($project['name']) ?></h1>
            <?php if ($project['client']): ?>
            <div style="font-size:0.875rem;color:var(--dim);margin-top:3px;"><?= esc($project['client']) ?></div>
            <?php endif; ?>
            <?php if (!empty($project['tags'])): ?>
            <div style="display:flex;gap:5px;flex-wrap:wrap;margin-top:10px;">
                <?php foreach ($project['tags'] as $tag): ?>
                <span class="tag-pill"><?= esc($tag['name']) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <a href="<?= base_url('projects/' . $project['id'] . '/edit') ?>" class="btn btn-ghost">
            <i class="fa-solid fa-pen-to-square"></i>Edit proyek
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px;margin-bottom:36px;">
    <div class="stat-card">
        <div class="stat-label"><i class="fa-regular fa-clock" style="margin-right:5px;"></i>Total waktu</div>
        <div class="stat-value" style="font-size:1.625rem;"><?= format_duration($totalSec) ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label"><i class="fa-solid fa-coins" style="margin-right:5px;"></i>Rate per jam</div>
        <div class="stat-value" style="font-size:1.125rem;"><?= format_rupiah((int)$project['hourly_rate']) ?></div>
    </div>
    <div class="stat-card" style="border-color:rgba(59,130,246,0.3);background:rgba(59,130,246,0.05);">
        <div class="stat-label"><i class="fa-solid fa-sack-dollar" style="margin-right:5px;"></i>Estimasi nilai</div>
        <div class="stat-value" style="font-size:1.125rem;color:var(--accent);"><?= format_rupiah($value) ?></div>
    </div>
</div>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
    <h2 style="font-size:0.9375rem;font-weight:600;color:var(--text);">Riwayat sesi</h2>
    <span style="font-size:0.8125rem;color:var(--dim);"><?= count($project['entries']) ?> sesi</span>
</div>

<?php if (empty($project['entries'])): ?>
<div class="empty-state" style="padding:48px 24px;">
    <div class="empty-icon"><i class="fa-solid fa-timeline"></i></div>
    <p style="color:var(--dim);font-size:0.875rem;margin:0;">Belum ada sesi. Mulai timer dari dashboard.</p>
</div>
<?php else: ?>
<div style="display:flex;flex-direction:column;gap:6px;">
    <?php foreach ($project['entries'] as $entry): ?>
    <?php $isRunning = $entry['ended_at'] === null; ?>
    <div class="session-row <?= $isRunning ? 'running' : '' ?>">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <?php if ($isRunning): ?>
            <div style="display:flex;align-items:center;gap:6px;">
                <span class="pulse-dot"></span>
                <span style="font-size:0.6875rem;color:var(--green);font-weight:600;">BERJALAN</span>
            </div>
            <?php endif; ?>
            <div style="display:flex;align-items:center;gap:5px;">
                <span style="font-size:0.75rem;color:var(--dim);">Mulai</span>
                <span class="font-mono" style="font-size:0.8125rem;color:var(--text);"><?= date('d/m/Y H:i', strtotime($entry['started_at'])) ?></span>
            </div>
            <?php if (!$isRunning): ?>
            <div style="display:flex;align-items:center;gap:5px;">
                <span style="font-size:0.75rem;color:var(--dim);">Selesai</span>
                <span class="font-mono" style="font-size:0.8125rem;color:var(--text);"><?= date('d/m/Y H:i', strtotime($entry['ended_at'])) ?></span>
            </div>
            <span class="font-mono" style="font-size:0.9375rem;font-weight:600;color:var(--text);"><?= format_duration((int)$entry['duration_seconds']) ?></span>
            <?php endif; ?>
            <?php if ($entry['note']): ?>
            <span style="font-size:0.8125rem;color:var(--dim);font-style:italic;"><?= esc($entry['note']) ?></span>
            <?php endif; ?>
        </div>
        <div style="display:flex;gap:6px;">
            <a href="<?= base_url('sessions/' . $entry['id'] . '/edit') ?>" class="btn btn-ghost btn-sm btn-icon" title="Edit sesi">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form method="POST" action="<?= base_url('sessions/' . $entry['id'] . '/delete') ?>" onsubmit="return confirm('Hapus sesi ini?')">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus sesi">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
