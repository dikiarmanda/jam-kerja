<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:560px;" class="mx-auto">
    <!-- Back Link -->
    <div class="mb-5">
        <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="back-link">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>
            <span>Kembali ke proyek</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6 pb-4" style="border-bottom:1px solid var(--border);">
            <div style="width:38px;height:38px;background:var(--accent-subtle);border:1px solid var(--accent-border);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--accent-text);">
                <i class="fa-solid fa-pen-to-square" style="font-size:1rem;"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight m-0" style="color:var(--text);">Edit sesi</h1>
                <?php if ($project): ?>
                <div class="text-xs mt-0.5" style="color:var(--text-muted);">
                    Proyek: <span class="font-semibold text-accent" style="color:var(--accent);"><?= esc($project['name']) ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <form method="POST" action="<?= base_url('sessions/' . $entry['id'] . '/update') ?>">
            <?= csrf_field() ?>
            <div class="flex flex-col gap-5">
                <?php if (!empty($projects)): ?>
                <div class="field-group">
                    <label class="field-label">
                        <span>Pilih Proyek</span>
                        <span class="text-xs" style="color:var(--red);">* wajib</span>
                    </label>
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
                    <label class="field-label">
                        <span>Waktu Mulai</span>
                        <span class="text-xs" style="color:var(--red);">* wajib</span>
                    </label>
                    <input type="datetime-local" name="started_at" class="font-mono"
                           value="<?= date('Y-m-d\TH:i', strtotime($entry['started_at'])) ?>" required>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Waktu Selesai</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">(kosongkan jika timer masih berjalan)</span>
                    </label>
                    <input type="datetime-local" name="ended_at" class="font-mono"
                           value="<?= $entry['ended_at'] ? date('Y-m-d\TH:i', strtotime($entry['ended_at'])) : '' ?>">
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Catatan</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">(opsional)</span>
                    </label>
                    <input type="text" name="note" value="<?= esc($entry['note']) ?>" placeholder="Catatan pekerjaan">
                </div>

                <div class="flex items-center gap-2.5 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                    <a href="<?= base_url('projects/' . $entry['project_id']) ?>" class="btn btn-ghost">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
