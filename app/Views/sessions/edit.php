<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:520px;">
    <div style="margin-bottom:28px;">
        <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="back-link" style="margin-bottom:14px;display:inline-flex;">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>Kembali ke proyek
        </a>
        <h1 style="font-size:1.25rem;font-weight:600;color:var(--text);">Edit sesi</h1>
        <?php if ($project): ?>
        <div style="font-size:0.875rem;color:var(--dim);margin-top:3px;"><?= esc($project['name']) ?></div>
        <?php endif; ?>
    </div>

    <form method="POST" action="<?= base_url('sessions/' . $entry['id'] . '/update') ?>">
        <?= csrf_field() ?>
        <div style="display:flex;flex-direction:column;gap:20px;">
            <?php if (!empty($projects)): ?>
            <div class="field-group">
                <label class="field-label">Proyek <span style="color:var(--red);">*</span></label>
                <select name="project_id" required>
                    <?php foreach ($projects as $proj): ?>
                    <option value="<?= $proj['id'] ?>" <?= ((int)$entry['project_id'] === (int)$proj['id']) ? 'selected' : '' ?>>
                        <?= esc($proj['name']) ?><?= $proj['client'] ? ' (' . esc($proj['client']) . ')' : '' ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="field-group">
                <label class="field-label">Waktu mulai <span style="color:var(--red);">*</span></label>
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
            <div style="display:flex;gap:10px;padding-top:4px;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i>Simpan perubahan
                </button>
                <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="btn btn-ghost">
                    <i class="fa-solid fa-xmark"></i>Batal
                </a>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
