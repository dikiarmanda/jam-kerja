<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$backUrl = $selectedProjectId ? base_url('projects/' . $selectedProjectId) : base_url('sessions');
$defaultStart = old('started_at') ?: date('Y-m-d\TH:i', strtotime('-1 hour'));
$defaultEnd   = old('ended_at') !== null ? old('ended_at') : date('Y-m-d\TH:i');
?>

<div style="max-width:560px;" class="mx-auto">
    <!-- Back Link -->
    <div class="mb-5">
        <a href="<?= $backUrl ?>" class="back-link">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6 pb-4" style="border-bottom:1px solid var(--border);">
            <div style="width:38px;height:38px;background:var(--accent-subtle);border:1px solid var(--accent-border);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--accent-text);">
                <i class="fa-solid fa-clock-rotate-left" style="font-size:1rem;"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight m-0" style="color:var(--text);">Catat Sesi Manual</h1>
                <div class="text-xs mt-0.5" style="color:var(--text-muted);">
                    Tambahkan log jam kerja lampau yang belum tercatat oleh timer
                </div>
            </div>
        </div>

        <form method="POST" action="<?= base_url('sessions') ?>">
            <?= csrf_field() ?>
            <div class="flex flex-col gap-5">
                <div class="field-group">
                    <label class="field-label">
                        <span>Pilih Proyek</span>
                        <span class="text-xs" style="color:var(--red);">* wajib</span>
                    </label>
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
                    <label class="field-label">
                        <span>Waktu Mulai</span>
                        <span class="text-xs" style="color:var(--red);">* wajib</span>
                    </label>
                    <input type="datetime-local" name="started_at" class="font-mono"
                           value="<?= esc($defaultStart) ?>" required>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Waktu Selesai</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">(kosongkan jika timer sedang aktif)</span>
                    </label>
                    <input type="datetime-local" name="ended_at" class="font-mono"
                           value="<?= esc($defaultEnd) ?>">
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Catatan Pekerjaan</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">(opsional)</span>
                    </label>
                    <input type="text" name="note" value="<?= esc(old('note')) ?>" placeholder="contoh: Mengerjakan fitur autentikasi dan perbaikan bug">
                </div>

                <div class="flex items-center gap-2.5 pt-3" style="border-top:1px solid var(--border);">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan Sesi</span>
                    </button>
                    <a href="<?= $backUrl ?>" class="btn btn-ghost">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Batal</span>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
