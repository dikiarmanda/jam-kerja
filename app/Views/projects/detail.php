<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$totalSec    = (int)$project['total_seconds'];
$value       = calc_value($totalSec, (int)$project['hourly_rate']);
$activeEntry = $project['active_entry'] ?? null;
?>

<!-- Back link -->
<div class="mb-5">
    <a href="<?= base_url('/') ?>" class="back-link">
        <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>
        <span>Kembali ke semua proyek</span>
    </a>
</div>

<!-- Project Header Card -->
<div class="card p-6 mb-8" style="border-radius:16px;">
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-5">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 flex-wrap mb-2">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight m-0" style="color:var(--text);">
                    <?= esc($project['name']) ?>
                </h1>
                <?php if ($activeEntry): ?>
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold" style="background:var(--green-subtle);border:1px solid var(--green-border);color:var(--green-text);">
                    <span class="pulse-dot"></span>
                    <span>TIMER AKTIF</span>
                </div>
                <?php endif; ?>
            </div>

            <?php if ($project['client']): ?>
            <div class="flex items-center gap-2 text-sm mb-3" style="color:var(--text-muted);">
                <i class="fa-regular fa-building" style="font-size:0.8125rem;"></i>
                <span class="font-medium"><?= esc($project['client']) ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($project['description'])): ?>
            <p class="text-sm mt-2 mb-3 leading-relaxed" style="color:var(--text-muted);max-width:700px;">
                <?= nl2br(esc($project['description'])) ?>
            </p>
            <?php endif; ?>

            <?php if (!empty($project['tags'])): ?>
            <div class="flex gap-1.5 flex-wrap mt-3">
                <?php foreach ($project['tags'] as $tag): ?>
                <span class="tag-pill">
                    <i class="fa-solid fa-tag" style="font-size:0.625rem;"></i>
                    <?= esc($tag['name']) ?>
                </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Header Actions -->
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
            <?php if ($activeEntry): ?>
            <form method="POST" action="<?= base_url('timer/stop/' . $activeEntry['id']) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-stop">
                    <i class="fa-solid fa-stop" style="font-size:0.8125rem;"></i>
                    <span>Berhenti Timer</span>
                </button>
            </form>
            <?php else: ?>
            <form method="POST" action="<?= base_url('timer/start/' . $project['id']) ?>">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-start">
                    <i class="fa-solid fa-play" style="font-size:0.8125rem;"></i>
                    <span>Mulai Timer</span>
                </button>
            </form>
            <?php endif; ?>

            <a href="<?= base_url('projects/' . $project['id'] . '/edit') ?>" class="btn btn-ghost">
                <i class="fa-solid fa-pen-to-square" style="font-size:0.8125rem;"></i>
                <span>Edit Proyek</span>
            </a>
        </div>
    </div>
</div>

<!-- Stat Cards Grid -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-9">
    <div class="stat-card">
        <div class="stat-label">
            <i class="fa-regular fa-clock" style="font-size:0.8125rem;color:var(--accent);"></i>
            <span>Total Waktu</span>
        </div>
        <div class="stat-value text-2xl" id="project-detail-total"
             data-total-seconds="<?= $totalSec ?>"
             data-rate="<?= (int)$project['hourly_rate'] ?>">
            <?= format_duration($totalSec) ?>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-label">
            <i class="fa-solid fa-coins" style="font-size:0.8125rem;color:var(--amber);"></i>
            <span>Rate Per Jam</span>
        </div>
        <div class="stat-value text-xl">
            <?= format_rupiah((int)$project['hourly_rate']) ?>
        </div>
    </div>

    <div class="stat-card" style="background:var(--accent-subtle);border-color:var(--accent-border);">
        <div class="stat-label" style="color:var(--accent-text);">
            <i class="fa-solid fa-sack-dollar" style="font-size:0.8125rem;"></i>
            <span>Estimasi Nilai</span>
        </div>
        <div class="stat-value text-xl" id="project-detail-value" style="color:var(--accent-text);">
            <?= format_rupiah($value) ?>
        </div>
    </div>
</div>

<!-- Sessions Section Header -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
    <div class="flex items-center gap-2.5">
        <h2 class="text-lg font-bold tracking-tight m-0" style="color:var(--text);">Riwayat Sesi Kerja</h2>
        <span class="badge"><?= count($project['entries']) ?> sesi</span>
    </div>
    <a href="<?= base_url('sessions/create?project_id=' . $project['id']) ?>" class="btn btn-ghost btn-sm">
        <i class="fa-solid fa-plus" style="font-size:0.75rem;"></i>
        <span>Tambah Sesi Manual</span>
    </a>
</div>

<!-- Sessions List -->
<?php if (empty($project['entries'])): ?>
<div class="empty-state" style="padding:48px 24px;">
    <div class="empty-icon"><i class="fa-solid fa-timeline"></i></div>
    <h3 class="text-base font-semibold mb-1" style="color:var(--text);">Belum Ada Sesi</h3>
    <p style="color:var(--text-muted);font-size:0.875rem;margin:0 0 20px 0;">Mulai timer atau catat sesi manual pekerjaan Anda.</p>
    <a href="<?= base_url('sessions/create?project_id=' . $project['id']) ?>" class="btn btn-primary btn-sm">
        <i class="fa-solid fa-plus"></i>Tambah Sesi Manual
    </a>
</div>
<?php else: ?>
<div class="flex flex-col gap-2.5">
    <?php foreach ($project['entries'] as $entry): ?>
    <?php $isRunning = $entry['ended_at'] === null; ?>
    <div class="session-row <?= $isRunning ? 'running' : '' ?>">
        <div class="flex items-center gap-3 sm:gap-5 flex-wrap min-w-0 flex-1">
            <?php if ($isRunning): ?>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold" style="background:var(--green-subtle);border:1px solid var(--green-border);color:var(--green-text);">
                <span class="pulse-dot"></span>
                <span>BERJALAN</span>
            </div>
            <?php endif; ?>

            <div class="flex items-center gap-2 text-xs sm:text-sm">
                <span style="color:var(--text-dim);font-weight:500;">Mulai</span>
                <span class="font-mono font-medium" style="color:var(--text);">
                    <?= date('d M Y, H:i', strtotime($entry['started_at'])) ?>
                </span>
            </div>

            <?php if (!$isRunning): ?>
            <div class="flex items-center gap-2 text-xs sm:text-sm">
                <span style="color:var(--text-dim);font-weight:500;">Selesai</span>
                <span class="font-mono font-medium" style="color:var(--text);">
                    <?= date('d M Y, H:i', strtotime($entry['ended_at'])) ?>
                </span>
            </div>

            <div class="font-mono font-bold px-2.5 py-0.5 rounded text-sm" style="background:var(--surface);border:1px solid var(--border);color:var(--text);">
                <?= format_duration((int)$entry['duration_seconds']) ?>
            </div>
            <?php else: ?>
            <span class="font-mono font-bold px-2.5 py-0.5 rounded text-sm text-green-600 dark:text-green-400" id="running-detail-entry" data-started="<?= $entry['started_at'] ?>">
                <?= format_duration(time() - strtotime($entry['started_at'])) ?>
            </span>
            <?php endif; ?>

            <?php if (!empty($entry['note'])): ?>
            <div class="flex items-center gap-1.5 text-xs text-muted truncate max-w-md" style="color:var(--text-muted);">
                <i class="fa-regular fa-comment-dots" style="font-size:0.75rem;color:var(--text-dim);"></i>
                <span class="truncate italic">&ldquo;<?= esc($entry['note']) ?>&rdquo;</span>
            </div>
            <?php endif; ?>
        </div>

        <div class="flex items-center gap-1.5 shrink-0">
            <a href="<?= base_url('sessions/' . $entry['id'] . '/edit') ?>" class="btn btn-ghost btn-icon btn-sm" title="Edit sesi">
                <i class="fa-solid fa-pen-to-square"></i>
            </a>
            <form method="POST" action="<?= base_url('sessions/' . $entry['id'] . '/delete') ?>" onsubmit="return confirm('Hapus sesi ini secara permanen?')">
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

<?= $this->section('scripts') ?>
<?php if ($activeEntry): ?>
<script>
(function() {
    function pad(n) { return String(n).padStart(2, '0'); }
    function formatDuration(s) {
        const h = Math.floor(s / 3600);
        const m = Math.floor((s % 3600) / 60);
        const sec = s % 60;
        return pad(h) + ':' + pad(m) + ':' + pad(sec);
    }

    const runningEl = document.getElementById('running-detail-entry');
    const totalEl = document.getElementById('project-detail-total');
    const valueEl = document.getElementById('project-detail-value');

    let baseSeconds = totalEl ? parseInt(totalEl.dataset.totalSeconds, 10) : 0;
    const rate = totalEl ? parseInt(totalEl.dataset.rate, 10) : 0;
    const startedTime = runningEl ? new Date("<?= date('Y-m-d\TH:i:s', strtotime($activeEntry['started_at'])) ?>").getTime() : null;

    if (startedTime) {
        setInterval(function() {
            const now = Date.now();
            const sessionSec = Math.floor((now - startedTime) / 1000);
            if (runningEl) {
                runningEl.textContent = formatDuration(Math.max(0, sessionSec));
            }
            if (totalEl) {
                baseSeconds++;
                totalEl.textContent = formatDuration(baseSeconds);
                if (valueEl && rate > 0) {
                    const val = Math.round((baseSeconds / 3600) * rate);
                    valueEl.textContent = 'Rp ' + val.toLocaleString('id-ID');
                }
            }
        }, 1000);
    }
})();
</script>
<?php endif; ?>
<?= $this->endSection() ?>
