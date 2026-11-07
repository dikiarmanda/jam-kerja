<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$activeEntry = $activeEntry ?? null;
?>

<?php if ($activeEntry): ?>
<?php
    $elapsedSec = time() - strtotime($activeEntry['started_at']);
?>
<div id="active-timer-bar" class="mb-8 p-5 sm:p-6 rounded-2xl relative overflow-hidden" style="background:var(--card);border:2px solid var(--green);box-shadow:0 0 25px rgba(16,185,129,0.15), var(--shadow-md);">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="flex items-center gap-4 min-w-0">
            <div class="pulse-dot-container" style="width:28px;height:28px;">
                <span class="pulse-dot-ring"></span>
                <span class="pulse-dot" style="width:12px;height:12px;"></span>
            </div>
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <span class="badge badge-green" style="font-size:0.6875rem;letter-spacing:0.05em;">TIMER BERJALAN</span>
                    <span style="font-size:0.75rem;color:var(--text-dim);" class="hide-mobile">Mulai <?= date('H:i', strtotime($activeEntry['started_at'])) ?></span>
                </div>
                <h2 class="text-base sm:text-lg font-bold truncate m-0" style="color:var(--text);">
                    <?= esc($activeEntry['project_name'] ?? '') ?>
                </h2>
            </div>
        </div>

        <div class="flex items-center justify-between md:justify-end gap-5">
            <div id="running-clock" class="font-mono" style="font-size:2.25rem;font-weight:700;color:var(--green);letter-spacing:-0.03em;line-height:1;"
                 data-started="<?= $activeEntry['started_at'] ?>"
                 data-elapsed="<?= $elapsedSec ?>">
                <?= format_duration($elapsedSec) ?>
            </div>
            <form method="POST" action="<?= base_url('timer/stop/' . $activeEntry['id']) ?>" id="stop-form">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-stop" style="padding:10px 20px;">
                    <i class="fa-solid fa-stop" style="font-size:0.875rem;"></i>
                    <span>Berhenti</span>
                </button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Section Header & Filter -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-2.5">
        <h1 class="text-xl sm:text-2xl font-bold tracking-tight m-0" style="color:var(--text);">Proyek</h1>
        <span class="badge" style="font-size:0.75rem;"><?= count($projects) ?></span>
    </div>

    <!-- Tag Filter Pills -->
    <div class="flex items-center gap-1.5 flex-wrap">
        <a href="<?= base_url('/') ?>" class="filter-tab <?= !$activeTag ? 'active' : '' ?>">
            <i class="fa-solid fa-layer-group" style="font-size:0.75rem;"></i>
            <span>Semua</span>
        </a>
        <?php foreach ($tags as $tag): ?>
        <a href="<?= base_url('?tag=' . $tag['id']) ?>" class="filter-tab <?= (int)$activeTag === (int)$tag['id'] ? 'active' : '' ?>">
            <i class="fa-solid fa-tag" style="font-size:0.6875rem;"></i>
            <span><?= esc($tag['name']) ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if (empty($projects)): ?>
<div class="empty-state">
    <div class="empty-icon"><i class="fa-regular fa-folder-open"></i></div>
    <h3 class="text-base font-semibold mb-2" style="color:var(--text);">Belum Ada Proyek</h3>
    <p style="color:var(--text-muted);font-size:0.875rem;max-width:380px;margin:0 auto 24px auto;">
        Buat proyek pertama Anda untuk mulai melacak waktu kerja dan estimasi pendapatan secara akurat.
    </p>
    <a href="<?= base_url('projects/create') ?>" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i>Buat Proyek Pertama
    </a>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
    <?php foreach ($projects as $project): ?>
    <?php
        $isActive = $activeEntry && (int)$activeEntry['project_id'] === (int)$project['id'];
        $totalSec = (int)$project['total_seconds'];
        $value    = calc_value($totalSec, (int)$project['hourly_rate']);
    ?>
    <div class="card card-hoverable <?= $isActive ? 'card-active' : '' ?>" style="padding:22px;display:flex;flex-direction:column;gap:16px;">
        <!-- Card Header -->
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0 flex-1">
                <a href="<?= base_url('projects/' . $project['id']) ?>" class="block font-semibold text-base truncate transition-colors" style="color:var(--text);text-decoration:none;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text)'">
                    <?= esc($project['name']) ?>
                </a>
                <?php if ($project['client']): ?>
                <div class="flex items-center gap-1.5 text-xs mt-1 truncate" style="color:var(--text-muted);">
                    <i class="fa-regular fa-building" style="font-size:0.75rem;"></i>
                    <span class="truncate"><?= esc($project['client']) ?></span>
                </div>
                <?php endif; ?>
            </div>
            <?php if ($isActive): ?>
            <div class="flex items-center gap-1.5 shrink-0">
                <span class="pulse-dot"></span>
                <span style="font-size:0.6875rem;color:var(--green);font-weight:700;letter-spacing:0.04em;">AKTIF</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Tags -->
        <?php if (!empty($project['tags'])): ?>
        <div class="flex gap-1.5 flex-wrap">
            <?php foreach ($project['tags'] as $tag): ?>
            <span class="tag-pill">
                <i class="fa-solid fa-tag" style="font-size:0.625rem;"></i>
                <?= esc($tag['name']) ?>
            </span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Stat Row -->
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border-radius:10px;overflow:hidden;border:1px solid var(--border);">
            <div style="background:var(--surface);padding:12px 14px;">
                <div style="font-size:0.6875rem;color:var(--text-muted);margin-bottom:4px;font-weight:500;display:flex;align-items:center;gap:5px;">
                    <i class="fa-regular fa-clock" style="font-size:0.6875rem;"></i>
                    <span>Total waktu</span>
                </div>
                <div class="font-mono" id="total-<?= $project['id'] ?>" style="font-size:1.1875rem;font-weight:700;color:var(--text);"
                     data-project-id="<?= $project['id'] ?>"
                     data-total-seconds="<?= $totalSec ?>"
                     data-is-active="<?= $isActive ? 'true' : 'false' ?>">
                    <?= format_duration($totalSec) ?>
                </div>
            </div>
            <div style="background:var(--surface);padding:12px 14px;">
                <div style="font-size:0.6875rem;color:var(--text-muted);margin-bottom:4px;font-weight:500;display:flex;align-items:center;gap:5px;">
                    <i class="fa-solid fa-coins" style="font-size:0.6875rem;"></i>
                    <span>Estimasi nilai</span>
                </div>
                <div class="font-mono" id="value-<?= $project['id'] ?>" style="font-size:1.0625rem;font-weight:600;color:var(--accent);"
                     data-rate="<?= (int)$project['hourly_rate'] ?>">
                    <?= format_rupiah($value) ?>
                </div>
            </div>
        </div>

        <!-- Card Actions -->
        <div class="flex items-center gap-2 pt-1 mt-auto">
            <?php if ($isActive): ?>
            <form method="POST" action="<?= base_url('timer/stop/' . $activeEntry['id']) ?>" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-stop w-full">
                    <i class="fa-solid fa-stop" style="font-size:0.8125rem;"></i>Berhenti
                </button>
            </form>
            <?php else: ?>
            <form method="POST" action="<?= base_url('timer/start/' . $project['id']) ?>" class="flex-1 timer-start-form">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-start w-full">
                    <i class="fa-solid fa-play" style="font-size:0.8125rem;"></i>Mulai
                </button>
            </form>
            <?php endif; ?>

            <a href="<?= base_url('projects/' . $project['id']) ?>" class="btn btn-ghost btn-icon" title="Lihat detail proyek">
                <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.875rem;"></i>
            </a>
            <a href="<?= base_url('projects/' . $project['id'] . '/edit') ?>" class="btn btn-ghost btn-icon" title="Edit proyek">
                <i class="fa-solid fa-pen-to-square" style="font-size:0.875rem;"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
(function() {
    const runningClock = document.getElementById('running-clock');
    let globalElapsed = runningClock ? parseInt(runningClock.dataset.elapsed, 10) : 0;

    function pad(n) { return String(n).padStart(2, '0'); }
    function formatDuration(s) {
        const h = Math.floor(s / 3600);
        const m = Math.floor((s % 3600) / 60);
        const sec = s % 60;
        return pad(h) + ':' + pad(m) + ':' + pad(sec);
    }

    const activeProjectId = <?= $activeEntry ? $activeEntry['project_id'] : 'null' ?>;

    function tick() {
        globalElapsed++;

        if (runningClock) {
            runningClock.textContent = formatDuration(globalElapsed);
        }

        if (activeProjectId) {
            const totalEl = document.getElementById('total-' + activeProjectId);
            const valueEl = document.getElementById('value-' + activeProjectId);
            if (totalEl) {
                const base = parseInt(totalEl.dataset.totalSeconds, 10);
                const rate = parseInt(valueEl ? valueEl.dataset.rate : '0', 10);
                const current = base - <?= $activeEntry ? (time() - strtotime($activeEntry['started_at'])) : 0 ?> + globalElapsed;
                totalEl.textContent = formatDuration(Math.max(0, current));
                if (valueEl && rate > 0) {
                    const val = Math.round((current / 3600) * rate);
                    valueEl.textContent = 'Rp ' + val.toLocaleString('id-ID');
                }
            }
        }
    }

    if (runningClock) {
        setInterval(tick, 1000);
    }

    document.querySelectorAll('.timer-start-form').forEach(function(form) {
        form.addEventListener('submit', function() {
            const btn = form.querySelector('button');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:0.8125rem;"></i> Memulai…';
            }
        });
    });
})();
</script>
<?= $this->endSection() ?>
