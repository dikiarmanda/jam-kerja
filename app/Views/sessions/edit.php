<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:520px;">
    <div style="margin-bottom:28px;">
        <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="back-link" style="margin-bottom:14px;display:inline-flex;">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>Kembali ke proyek
        </a>
        <h1 style="font-size:1.125rem;font-weight:600;color:var(--text);">Edit sesi</h1>
        <?php if ($project): ?>
        <div style="font-size:0.875rem;color:var(--dim);margin-top:3px;"><?= esc($project['name']) ?></div>
        <?php endif; ?>
    </div>

    <form method="POST" action="<?= base_url('sessions/' . $entry['id'] . '/update') ?>">
        <?= csrf_field() ?>
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="field-group">
                <label class="field-label">Waktu mulai</label>
                <input type="datetime-local" name="started_at" class="font-mono"
                       value="<?= date('Y-m-d\TH:i', strtotime($entry['started_at'])) ?>" required>
            </div>
            <div class="field-group">
                <label class="field-label">
                    Waktu selesai
                    <span style="color:var(--dim);font-weight:400;font-size:0.75rem;margin-left:4px;">kosongkan jika masih berjalan</span>
                </label>
                <input type="datetime-local" name="ended_at" class="font-mono"
                       value="<?= $entry['ended_at'] ? date('Y-m-d\TH:i', strtotime($entry['ended_at'])) : '' ?>">
            </div>
            <div class="field-group">
                <label class="field-label">Catatan</label>
                <input type="text" name="note" value="<?= esc($entry['note']) ?>" placeholder="Catatan singkat (opsional)">
            </div>
            <div style="display:flex;gap:10px;padding-top:4px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>Simpan
                </button>
                <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="btn btn-ghost">
                    <i class="fa-solid fa-xmark"></i>Batal
                </a>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
