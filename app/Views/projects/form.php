<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$isEdit = !empty($project);
$title  = $isEdit ? 'Edit Proyek' : 'Proyek Baru';
$action = $isEdit ? base_url('projects/' . $project['id'] . '/update') : base_url('projects');
$selectedTags = $isEdit ? (array)$project['tags'] : [];
?>

<div style="max-width:640px;" class="mx-auto">
    <!-- Back Link -->
    <div class="mb-5">
        <a href="<?= $isEdit ? base_url('projects/' . $project['id']) : base_url('/') ?>" class="back-link">
            <i class="fa-solid fa-arrow-left" style="font-size:0.75rem;"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Form Card -->
    <div class="card p-6 sm:p-8">
        <div class="flex items-center gap-3 mb-6 pb-4" style="border-bottom:1px solid var(--border);">
            <div style="width:38px;height:38px;background:var(--accent-subtle);border:1px solid var(--accent-border);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--accent-text);">
                <i class="fa-solid <?= $isEdit ? 'fa-pen-to-square' : 'fa-folder-plus' ?>" style="font-size:1rem;"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight m-0" style="color:var(--text);"><?= $title ?></h1>
                <div class="text-xs mt-0.5" style="color:var(--text-muted);">
                    <?= $isEdit ? 'Perbarui detail data proyek dan rate per jam' : 'Buat proyek baru untuk mulai mencatat jam kerja' ?>
                </div>
            </div>
        </div>

        <?php if (!empty($errors)): ?>
        <div class="flash flash-error flex-col gap-1.5 mb-6">
            <?php foreach ($errors as $err): ?>
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation shrink-0"></i>
                <span><?= esc($err) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= $action ?>" id="project-form">
            <?= csrf_field() ?>
            <div class="flex flex-col gap-5">
                <div class="field-group">
                    <label class="field-label">
                        <span>Nama Proyek</span>
                        <span class="text-xs" style="color:var(--red);">* wajib</span>
                    </label>
                    <input type="text" name="name" value="<?= esc($isEdit ? $project['name'] : old('name')) ?>" placeholder="contoh: Website Redesign PT Maju" required autofocus>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Nama Klien / Perusahaan</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">(opsional)</span>
                    </label>
                    <input type="text" name="client" value="<?= esc($isEdit ? $project['client'] : old('client')) ?>" placeholder="contoh: PT Maju Bersama">
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Deskripsi Proyek</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">(opsional)</span>
                    </label>
                    <textarea name="description" rows="3" placeholder="Tuliskan catatan atau ruang lingkup proyek..."><?= esc($isEdit ? $project['description'] : old('description')) ?></textarea>
                </div>

                <div class="field-group">
                    <label class="field-label">
                        <span>Rate Per Jam (IDR)</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">Digunakan untuk estimasi nilai proyek</span>
                    </label>
                    <div class="relative flex items-center">
                        <span class="absolute left-3.5 text-xs font-semibold select-none" style="color:var(--text-dim);">Rp</span>
                        <input type="number" name="hourly_rate" value="<?= esc($isEdit ? $project['hourly_rate'] : old('hourly_rate', 0)) ?>" min="0" placeholder="0" class="font-mono" style="padding-left:36px;">
                    </div>
                </div>

                <?php if (!empty($tags)): ?>
                <div class="field-group">
                    <label class="field-label">
                        <span>Kategori / Tag</span>
                        <span class="text-xs font-normal" style="color:var(--text-dim);">Pilih satu atau lebih tag</span>
                    </label>
                    <div class="flex flex-wrap gap-2 p-3.5 rounded-xl" style="background:var(--bg-alt);border:1px solid var(--border);">
                        <?php foreach ($tags as $tag): ?>
                        <?php $checked = in_array((int)$tag['id'], array_map('intval', $selectedTags)); ?>
                        <label class="inline-flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-lg border text-xs font-medium transition-all"
                               style="background:<?= $checked ? 'var(--accent-subtle)' : 'var(--surface)' ?>;border-color:<?= $checked ? 'var(--accent-border)' : 'var(--border)' ?>;color:<?= $checked ? 'var(--accent-text)' : 'var(--text)' ?>;">
                            <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" <?= $checked ? 'checked' : '' ?>
                                   style="width:auto;margin:0;accent-color:var(--accent);"
                                   onchange="this.parentElement.style.background = this.checked ? 'var(--accent-subtle)' : 'var(--surface)'; this.parentElement.style.borderColor = this.checked ? 'var(--accent-border)' : 'var(--border)'; this.parentElement.style.color = this.checked ? 'var(--accent-text)' : 'var(--text)';">
                            <span><?= esc($tag['name']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="flex items-center justify-between gap-3 pt-3 flex-wrap" style="border-top:1px solid var(--border);">
                    <div class="flex items-center gap-2.5">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span><?= $isEdit ? 'Simpan Perubahan' : 'Buat Proyek' ?></span>
                        </button>
                        <a href="<?= $isEdit ? base_url('projects/' . $project['id']) : base_url('/') ?>" class="btn btn-ghost">
                            <i class="fa-solid fa-xmark"></i>
                            <span>Batal</span>
                        </a>
                    </div>

                    <?php if ($isEdit): ?>
                    <button type="button" class="btn btn-danger" onclick="if(confirm('Hapus proyek ini beserta semua catatan sesinya? Tindakan ini tidak dapat dibatalkan.')) { document.getElementById('delete-project-form').submit(); }">
                        <i class="fa-solid fa-trash"></i>
                        <span>Hapus Proyek</span>
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <?php if ($isEdit): ?>
        <form id="delete-project-form" method="POST" action="<?= base_url('projects/' . $project['id'] . '/delete') ?>" style="display:none;">
            <?= csrf_field() ?>
        </form>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
