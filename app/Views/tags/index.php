<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:620px;" class="mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <div style="width:40px;height:40px;background:var(--accent-subtle);border:1px solid var(--accent-border);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--accent-text);">
            <i class="fa-solid fa-tags" style="font-size:1.125rem;"></i>
        </div>
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-bold tracking-tight m-0" style="color:var(--text);">Kelola Tag & Kategori</h1>
                <span class="badge"><?= count($tags) ?></span>
            </div>
            <p class="text-xs sm:text-sm mt-0.5 mb-0" style="color:var(--text-muted);">
                Organisir proyek Anda berdasarkan kategori atau tipe pekerjaan
            </p>
        </div>
    </div>

    <!-- Create Tag Card -->
    <div class="card p-5 sm:p-6 mb-6">
        <h2 class="text-xs font-bold uppercase tracking-wider mb-3" style="color:var(--text-muted);">
            <i class="fa-solid fa-plus mr-1" style="font-size:0.6875rem;"></i>Tambah Tag Baru
        </h2>
        <form method="POST" action="<?= base_url('tags') ?>" class="flex gap-2.5">
            <?= csrf_field() ?>
            <div class="relative flex-1">
                <input type="text" name="name" placeholder="Nama tag (contoh: Frontend, Desain, Mobile)" required>
            </div>
            <button type="submit" class="btn btn-primary shrink-0">
                <i class="fa-solid fa-plus"></i>
                <span class="hide-mobile">Tambah</span>
            </button>
        </form>
    </div>

    <!-- Tags List -->
    <?php if (empty($tags)): ?>
    <div class="empty-state">
        <div class="empty-icon"><i class="fa-solid fa-tags"></i></div>
        <h3 class="text-base font-semibold mb-1" style="color:var(--text);">Belum Ada Tag</h3>
        <p style="color:var(--text-muted);font-size:0.875rem;margin:0;">
            Buat tag pertama pada formulir di atas untuk mengelompokkan proyek Anda.
        </p>
    </div>
    <?php else: ?>
    <div class="flex flex-col gap-2.5">
        <?php foreach ($tags as $tag): ?>
        <div class="card p-3.5 sm:p-4 card-hoverable" id="tag-card-<?= $tag['id'] ?>">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div style="width:30px;height:30px;border-radius:8px;background:var(--accent-subtle);display:flex;align-items:center;justify-content:center;color:var(--accent-text);font-size:0.75rem;flex-shrink:0;">
                        <i class="fa-solid fa-tag"></i>
                    </div>
                    <span class="font-semibold text-sm truncate" style="color:var(--text);"><?= esc($tag['name']) ?></span>
                    <span class="badge" style="font-size:0.6875rem;"><?= $tag['project_count'] ?> proyek</span>
                </div>

                <div class="flex items-center gap-1.5 shrink-0">
                    <button type="button" onclick="toggleEditForm(<?= $tag['id'] ?>)" class="btn btn-ghost btn-icon btn-sm" title="Ubah nama tag">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <form method="POST" action="<?= base_url('tags/' . $tag['id'] . '/delete') ?>" onsubmit="return confirm('Hapus tag ini? Tag akan dilepas dari semua proyek yang menggunakannya.')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-icon btn-sm" title="Hapus tag">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Inline Edit Form -->
        <div id="edit-form-<?= $tag['id'] ?>" class="p-4 rounded-xl" style="display:none;background:var(--accent-subtle);border:1px solid var(--accent-border);">
            <form method="POST" action="<?= base_url('tags/' . $tag['id'] . '/update') ?>" class="flex items-center gap-2">
                <?= csrf_field() ?>
                <input type="text" name="name" value="<?= esc($tag['name']) ?>" required style="background:var(--surface);">
                <button type="submit" class="btn btn-primary shrink-0" title="Simpan perubahan">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span class="hide-mobile">Simpan</span>
                </button>
                <button type="button" onclick="toggleEditForm(<?= $tag['id'] ?>)" class="btn btn-ghost shrink-0" title="Batal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleEditForm(id) {
    const editForm = document.getElementById('edit-form-' + id);
    const isHidden = editForm.style.display === 'none';
    editForm.style.display = isHidden ? 'block' : 'none';
    if (isHidden) {
        const input = editForm.querySelector('input[type="text"]');
        if (input) input.focus();
    }
}
</script>
<?= $this->endSection() ?>
