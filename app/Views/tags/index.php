<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div style="max-width:560px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
        <h1 style="font-size:1.125rem;font-weight:600;color:var(--text);">Kelola Tag</h1>
    </div>

    <div class="card" style="padding:20px;margin-bottom:20px;">
        <h2 style="font-size:0.875rem;font-weight:600;color:var(--text);margin-bottom:14px;">Tag baru</h2>
        <form method="POST" action="<?= base_url('tags') ?>" style="display:flex;gap:10px;">
            <?= csrf_field() ?>
            <input type="text" name="name" placeholder="Nama tag" required style="flex:1;">
            <button type="submit" class="btn btn-primary" style="flex-shrink:0;">
                <i class="fa-solid fa-plus"></i>Tambah
            </button>
        </form>
    </div>

    <?php if (empty($tags)): ?>
    <div class="empty-state" style="padding:48px 24px;">
        <div class="empty-icon"><i class="fa-solid fa-tags"></i></div>
        <p style="color:var(--dim);font-size:0.875rem;margin:0;">Belum ada tag. Buat tag pertama di atas.</p>
    </div>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:6px;">
        <?php foreach ($tags as $tag): ?>
        <div class="card" style="padding:12px 16px;border-radius:8px;">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                <div style="display:flex;align-items:center;gap:10px;min-width:0;">
                    <span style="font-size:0.9375rem;color:var(--text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= esc($tag['name']) ?></span>
                    <span style="font-size:0.75rem;color:var(--dim);flex-shrink:0;"><?= $tag['project_count'] ?> proyek</span>
                </div>
                <div style="display:flex;gap:6px;align-items:center;flex-shrink:0;">
                    <button onclick="toggleEditForm(<?= $tag['id'] ?>)" class="btn btn-ghost btn-sm btn-icon" title="Ubah nama">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <form method="POST" action="<?= base_url('tags/' . $tag['id'] . '/delete') ?>" onsubmit="return confirm('Hapus tag ini? Tag akan dilepas dari semua proyek.')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus tag">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div id="edit-form-<?= $tag['id'] ?>" style="display:none;padding:14px 16px;background:rgba(59,130,246,0.05);border:1px solid rgba(59,130,246,0.25);border-radius:8px;">
            <form method="POST" action="<?= base_url('tags/' . $tag['id'] . '/update') ?>" style="display:flex;gap:8px;">
                <?= csrf_field() ?>
                <input type="text" name="name" value="<?= esc($tag['name']) ?>" required style="flex:1;">
                <button type="submit" class="btn btn-primary" style="flex-shrink:0;">
                    <i class="fa-solid fa-floppy-disk"></i>Simpan
                </button>
                <button type="button" onclick="toggleEditForm(<?= $tag['id'] ?>)" class="btn btn-ghost" style="flex-shrink:0;">
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
    const card = document.getElementById('edit-form-' + id);
    const isHidden = card.style.display === 'none';
    card.style.display = isHidden ? 'block' : 'none';
    if (isHidden) {
        const input = card.querySelector('input[type="text"]');
        if (input) input.focus();
    }
}
</script>
<?= $this->endSection() ?>
