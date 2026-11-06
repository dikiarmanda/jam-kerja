<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$activeEntry = $activeEntry ?? null;
?>

<?php if ($activeEntry): ?>
<?php
    $elapsedSec = time() - strtotime($activeEntry['started_at']);
?>
<div id="active-timer-bar" style="background:linear-gradient(135deg,rgba(16,185,129,0.07) 0%,rgba(16,185,129,0.03) 100%);border:1px solid rgba(16,185,129,0.3);border-radius:12px;padding:20px 24px;margin-bottom:32px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:14px;">
        <div style="display:flex;align-items:center;gap:8px;">
            <span class="pulse-dot"></span>
            <span style="font-size:0.75rem;color:var(--green);font-weight:600;letter-spacing:0.04em;">BERJALAN</span>
        </div>
        <div style="width:1px;height:16px;background:var(--border);"></div>
        <div style="font-size:0.9375rem;color:var(--text);"><?= esc($activeEntry['project_name'] ?? '') ?></div>
    </div>
    <div style="display:flex;align-items:center;gap:20px;">
        <div id="running-clock" class="font-mono" style="font-size:2.5rem;font-weight:700;color:var(--green);letter-spacing:-0.03em;line-height:1;"
             data-started="<?= $activeEntry['started_at'] ?>"
             data-elapsed="<?= $elapsedSec ?>">
            <?= format_duration($elapsedSec) ?>
        </div>
        <form method="POST" action="<?= base_url('timer/stop/' . $activeEntry['id']) ?>" id="stop-form">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-stop">
                <i class="fa-solid fa-stop" style="font-size:0.75rem;"></i>Berhenti
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:12px;">
    <h1 style="font-size:1.125rem;font-weight:600;color:var(--text);">Proyek</h1>
    <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
        <a href="<?= base_url('/') ?>" class="filter-tab <?= !$activeTag ? 'active' : '' ?>">Semua</a>
        <?php foreach ($tags as $tag): ?>
        <a href="<?= base_url('?tag=' . $tag['id']) ?>" class="filter-tab <?= (int)$activeTag === (int)$tag['id'] ? 'active' : '' ?>">
            <?= esc($tag['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<?php if (empty($projects)): ?>
<div class="empty-state">
    <div class="empty-icon"><i class="fa-regular fa-clock"></i></div>
    <p style="color:var(--dim);font-size:0.9375rem;margin-bottom:20px;margin-top:0;">Belum ada proyek.<br>Buat proyek pertama untuk mulai mencatat waktu.</p>
    <a href="<?= base_url('projects/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Buat proyek</a>
</div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:14px;">
    <?php foreach ($projects as $project): ?>
    <?php
        $isActive = $activeEntry && (int)$activeEntry['project_id'] === (int)$project['id'];
        $totalSec = (int)$project['total_seconds'];
        $value    = calc_value($totalSec, (int)$project['hourly_rate']);
    ?>
    <div class="card <?= $isActive ? 'card-active' : '' ?>" style="padding:20px;display:flex;flex-direction:column;gap:14px;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
            <div style="min-width:0;">
                <a href="<?= base_url('projects/' . $project['id']) ?>" style="font-size:0.9375rem;font-weight:600;color:var(--text);text-decoration:none;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" onmouseover="this.style.color='#7EB3F5'" onmouseout="this.style.color='var(--text)'"><?= esc($project['name']) ?></a>
                <?php if ($project['client']): ?>
                <div style="font-size:0.8125rem;color:var(--dim);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($project['client']) ?></div>
                <?php endif; ?>
            </div>
            <?php if ($isActive): ?>
            <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                <span class="pulse-dot" style="width:6px;height:6px;"></span>
                <span style="font-size:0.6875rem;color:var(--green);font-weight:600;">AKTIF</span>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($project['tags'])): ?>
        <div style="display:flex;gap:5px;flex-wrap:wrap;">
            <?php foreach ($project['tags'] as $tag): ?>
            <span class="tag-pill"><?= esc($tag['name']) ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1px;background:var(--border);border-radius:8px;overflow:hidden;">
            <div style="background:var(--surface);padding:12px 14px;">
                <div style="font-size:0.6875rem;color:var(--dim);margin-bottom:4px;font-weight:500;">Total waktu</div>
                <div class="font-mono" id="total-<?= $project['id'] ?>" style="font-size:1.1875rem;font-weight:700;color:var(--text);"
                     data-project-id="<?= $project['id'] ?>"
                     data-total-seconds="<?= $totalSec ?>"
                     data-is-active="<?= $isActive ? 'true' : 'false' ?>">
                    <?= format_duration($totalSec) ?>
                </div>
            </div>
            <div style="background:var(--surface);padding:12px 14px;">
                <div style="font-size:0.6875rem;color:var(--dim);margin-bottom:4px;font-weight:500;">Estimasi nilai</div>
                <div class="font-mono" id="value-<?= $project['id'] ?>" style="font-size:1rem;font-weight:600;color:var(--accent);"
                     data-rate="<?= (int)$project['hourly_rate'] ?>">
                    <?= format_rupiah($value) ?>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:8px;">
            <?php if ($isActive): ?>
            <form method="POST" action="<?= base_url('timer/stop/' . $activeEntry['id']) ?>" style="flex:1;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-stop" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-stop" style="font-size:0.75rem;"></i>Berhenti
                </button>
            </form>
            <?php else: ?>
            <form method="POST" action="<?= base_url('timer/start/' . $project['id']) ?>" style="flex:1;" class="timer-start-form">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-start" style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-play" style="font-size:0.75rem;"></i>Mulai
                </button>
            </form>
            <?php endif; ?>
            <a href="<?= base_url('projects/' . $project['id'] . '/edit') ?>" class="btn btn-ghost btn-icon" title="Edit proyek">
                <i class="fa-solid fa-pen-to-square"></i>
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
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size:0.75rem;"></i>Memulai…';
            }
        });
    });
})();
</script>
<?= $this->endSection() ?>
