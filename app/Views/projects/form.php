<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = !empty($project);
$title  = $isEdit ? 'Edit Proyek' : 'Proyek Baru';
$action = $isEdit ? base_url('projects/' . $project['id'] . '/update') : base_url('projects');
$selectedTags = $isEdit ? (array)$project['tags'] : [];
?>

<div style="max-width:600px;">
    <div style="margin-bottom:28px;">
        <a href="<?= base_url('/') ?>" class="back-link" style="margin-bottom:14px;display:inline-flex;">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>Kembali
        </a>
        <h1 style="font-size:1.25rem;font-weight:600;color:var(--text);"><?= $title ?></h1>
    </div>

    <?php if (!empty($errors)): ?>
    <div class="flash flash-error" style="flex-direction:column;gap:4px;">
        <?php foreach ($errors as $err): ?>
        <div style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-circle-exclamation" style="flex-shrink:0;"></i><?= esc($err) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div style="display:flex;flex-direction:column;gap:20px;">
            <div class="field-group">
                <label class="field-label">Nama proyek <span style="color:var(--red);">*</span></label>
                <input type="text" name="name" value="<?= esc($isEdit ? $project['name'] : old('name')) ?>" placeholder="Nama proyek" required>
            </div>
            <div class="field-group">
                <label class="field-label">Klien</label>
                <input type="text" name="client" value="<?= esc($isEdit ? $project['client'] : old('client')) ?>" placeholder="Nama klien (opsional)">
            </div>
            <div class="field-group">
                <label class="field-label">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi singkat (opsional)"><?= esc($isEdit ? $project['description'] : old('description')) ?></textarea>
            </div>
            <div class="field-group">
                <label class="field-label">Rate per jam (Rp)</label>
                <input type="number" name="hourly_rate" value="<?= esc($isEdit ? $project['hourly_rate'] : old('hourly_rate', 0)) ?>" min="0" placeholder="0" class="font-mono">
            </div>

            <?php if (!empty($tags)): ?>
            <div class="field-group">
                <label class="field-label">Tag</label>
                <div style="display:flex;flex-wrap:wrap;gap:8px;padding:14px;background:var(--surface);border:1px solid var(--border);border-radius:8px;">
                    <?php foreach ($tags as $tag): ?>
                    <?php $checked = in_array((int)$tag['id'], array_map('intval', $selectedTags)); ?>
                    <label style="display:flex;align-items:center;gap:7px;cursor:pointer;padding:5px 12px;border-radius:6px;border:1px solid <?= $checked ? 'rgba(59,130,246,0.4)' : 'var(--border)' ?>;background:<?= $checked ? 'rgba(59,130,246,0.08)' : 'transparent' ?>;transition:all 0.15s;" onmouseover="this.style.borderColor='#3d4d66'" onmouseout="this.style.borderColor='<?= $checked ? 'rgba(59,130,246,0.4)' : 'var(--border)' ?>'">
                        <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" <?= $checked ? 'checked' : '' ?>
                               style="width:auto;accent-color:var(--accent);">
                        <span style="font-size:0.8125rem;color:var(--text);"><?= esc($tag['name']) ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div style="display:flex;gap:10px;padding-top:4px;flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i><?= $isEdit ? 'Simpan perubahan' : 'Buat proyek' ?>
                </button>
                <a href="<?= base_url('/') ?>" class="btn btn-ghost">
                    <i class="fa-solid fa-xmark"></i>Batal
                </a>
                <?php if ($isEdit): ?>
                <form method="POST" action="<?= base_url('projects/' . $project['id'] . '/delete') ?>" style="margin-left:auto;" onsubmit="return confirm('Hapus proyek ini beserta semua sesinya?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i>Hapus proyek
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
