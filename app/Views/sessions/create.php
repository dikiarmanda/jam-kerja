<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$backUrl = $selectedProjectId ? base_url('projects/' . $selectedProjectId) : base_url('sessions');
$defaultStart = old('started_at') ?: date('Y-m-d\TH:i', strtotime('-1 hour'));
$defaultEnd   = old('ended_at') !== null ? old('ended_at') : date('Y-m-d\TH:i');
?>

<div style="max-width:520px;">
    <div style="margin-bottom:28px;">
        <a href="<?= $backUrl ?>" class="back-link" style="margin-bottom:14px;display:inline-flex;">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>Kembali
        </a>
        <h1 style="font-size:1.25rem;font-weight:600;color:var(--text);">Catat Sesi Manual</h1>
        <div style="font-size:0.875rem;color:var(--dim);margin-top:3px;">Tambahkan sesi kerja yang belum tercatat oleh timer sistem.</div>
    </div>

    <form method="POST" action="<?= base_url('sessions') ?>">
        <?= csrf_field() ?>
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="field-group">
                <label class="field-label">Proyek <span style="color:var(--red);">*</span></label>
                <select name="project_id" required>
                    <option value="">-- Pilih Proyek --</option>
                    <?php foreach ($projects as $proj): ?>
                    <option value="<?= $proj['id'] ?>" <?= ((int)(old('project_id') ?: $selectedProjectId) === (int)$proj['id']) ? 'selected' : '' ?>>
                        <?= esc($proj['name']) ?><?= $proj['client'] ? ' (' . esc($proj['client']) . ')' : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field-group">
                <label class="field-label">Waktu mulai <span style="color:var(--red);">*</span></label>
                <input type="datetime-local" name="started_at" class="font-mono"
                       value="<?= esc($defaultStart) ?>" required>
            </div>

            <div class="field-group">
                <label class="field-label">
                    Waktu selesai
                    <span style="color:var(--dim);font-weight:400;font-size:0.75rem;margin-left:4px;">kosongkan jika masih berjalan</span>
                </label>
                <input type="datetime-local" name="ended_at" class="font-mono"
                       value="<?= esc($defaultEnd) ?>">
            </div>

            <div class="field-group">
                <label class="field-label">Catatan</label>
                <input type="text" name="note" value="<?= esc(old('note')) ?>" placeholder="Catatan singkat pekerjaan (opsional)">
            </div>

            <div style="display:flex;gap:10px;padding-top:4px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>Simpan sesi
                </button>
                <a href="<?= $backUrl ?>" class="btn btn-ghost">
                    <i class="fa-solid fa-xmark"></i>Batal
                </a>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
