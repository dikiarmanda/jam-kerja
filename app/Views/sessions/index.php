<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <div>
        <h1 style="font-size:1.25rem;font-weight:600;color:var(--text);margin:0;">Sesi Kerja</h1>
        <div style="font-size:0.875rem;color:var(--dim);margin-top:2px;">Daftar seluruh riwayat sesi timer dan sesi manual.</div>
    </div>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
        <a href="<?= base_url('sessions/create' . ($selectedProjectId ? '?project_id=' . $selectedProjectId : '')) ?>" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>Tambah sesi manual
        </a>
    </div>
</div>

<?php if (!empty($projects)): ?>
<div style="margin-bottom:20px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <span style="font-size:0.8125rem;color:var(--dim);">Filter proyek:</span>
    <div style="display:flex;gap:6px;flex-wrap:wrap;">
        <a href="<?= base_url('sessions') ?>" class="filter-tab <?= !$selectedProjectId ? 'active' : '' ?>">Semua</a>
        <?php foreach ($projects as $proj): ?>
        <a href="<?= base_url('sessions?project_id=' . $proj['id']) ?>" class="filter-tab <?= (int)$selectedProjectId === (int)$proj['id'] ? 'active' : '' ?>">
            <?= esc($proj['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (empty($entries)): ?>
<div class="empty-state">
    <div class="empty-icon"><i class="fa-regular fa-clock"></i></div>
    <p style="color:var(--dim);font-size:0.9375rem;margin-bottom:20px;margin-top:0;">Belum ada sesi kerja tercatat.</p>
    <a href="<?= base_url('sessions/create') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>Tambah sesi manual
    </a>
</div>
<?php else: ?>
<div style="display:flex;flex-direction:column;gap:6px;">
    <?php foreach ($entries as $entry): ?>
    <?php $isRunning = $entry['ended_at'] === null; ?>
    <div class="session-row <?= $isRunning ? 'running' : '' ?>">
        <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
            <a href="<?= base_url('projects/' . $entry['project_id']) ?>" style="font-weight:600;font-size:0.875rem;color:var(--accent);text-decoration:none;">
                <?= esc($entry['project_name']) ?>
            </a>

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
            <span style="font-size:0.8125rem;color:var(--dim);font-style:italic;">“<?= esc($entry['note']) ?>”</span>
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
