<?php include __DIR__ . '/../layout/navbar.php'; ?>

<div class="transport-shell">
    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash-message <?= htmlspecialchars($_SESSION['flash']['type']) ?>">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <section class="transport-hero">
        <div>
            <p class="dashboard-kicker">System Profile</p>
            <h1>Settings</h1>
            <p>Atur nama aplikasi dan informasi profil sistem yang tampil di halaman utama.</p>
        </div>
    </section>

    <section class="location-layout">
        <article class="transport-form-card">
            <div class="section-title">
                <h2>Identitas Aplikasi</h2>
                <span>Profile</span>
            </div>

            <form method="POST" action="/IkiNet/app/controllers/SettingController.php?action=update" class="transport-form">
                <input type="hidden" name="whatsapp_enabled" value="<?= $settings['whatsapp_enabled'] === '1' ? '1' : '' ?>">
                <input type="hidden" name="whatsapp_api_url" value="<?= htmlspecialchars($settings['whatsapp_api_url']) ?>">
                <input type="hidden" name="whatsapp_api_token" value="<?= htmlspecialchars($settings['whatsapp_api_token']) ?>">
                <input type="hidden" name="whatsapp_admin_number" value="<?= htmlspecialchars($settings['whatsapp_admin_number']) ?>">

                <label>
                    <span>Nama Aplikasi</span>
                    <input type="text" name="app_name" value="<?= htmlspecialchars($settings['app_name']) ?>" required>
                </label>

                <label>
                    <span>Nama Instansi</span>
                    <input type="text" name="institution_name" value="<?= htmlspecialchars($settings['institution_name']) ?>">
                </label>

                <label>
                    <span>Kontak Admin</span>
                    <input type="text" name="admin_contact" value="<?= htmlspecialchars($settings['admin_contact']) ?>">
                </label>

                <div class="form-actions">
                    <button type="submit">Simpan Settings</button>
                </div>
            </form>
        </article>

        <article class="transport-form-card">
            <div class="section-title">
                <h2>Integrasi WhatsApp</h2>
                <span>API</span>
            </div>

            <form method="POST" action="/IkiNet/app/controllers/SettingController.php?action=update" class="transport-form">
                <input type="hidden" name="app_name" value="<?= htmlspecialchars($settings['app_name']) ?>">
                <input type="hidden" name="institution_name" value="<?= htmlspecialchars($settings['institution_name']) ?>">
                <input type="hidden" name="admin_contact" value="<?= htmlspecialchars($settings['admin_contact']) ?>">

                <label>
                    <span>Aktifkan WhatsApp API</span>
                    <input type="checkbox" name="whatsapp_enabled" value="1" <?= $settings['whatsapp_enabled'] === '1' ? 'checked' : '' ?>>
                </label>

                <label>
                    <span>Endpoint API</span>
                    <input type="url" name="whatsapp_api_url" value="<?= htmlspecialchars($settings['whatsapp_api_url']) ?>" placeholder="https://api.fonnte.com/send">
                </label>

                <label>
                    <span>Token API</span>
                    <input type="password" name="whatsapp_api_token" value="<?= htmlspecialchars($settings['whatsapp_api_token']) ?>" placeholder="Token dari dashboard Fonnte">
                </label>

                <label>
                    <span>Nomor WhatsApp Admin</span>
                    <input type="text" name="whatsapp_admin_number" value="<?= htmlspecialchars($settings['whatsapp_admin_number']) ?>" placeholder="Contoh: 6281234567890">
                </label>

                <div class="form-actions">
                    <button type="submit">Simpan WhatsApp API</button>
                </div>
            </form>
        </article>

        <article class="dashboard-card">
            <div class="section-title">
                <h2>Preview</h2>
                <span>Tampilan</span>
            </div>

            <div class="settings-list">
                <p><strong>Nama aplikasi</strong><span><?= htmlspecialchars($settings['app_name']) ?></span></p>
                <p><strong>Instansi</strong><span><?= htmlspecialchars($settings['institution_name']) ?></span></p>
                <p><strong>Kontak admin</strong><span><?= htmlspecialchars($settings['admin_contact']) ?></span></p>
                <p><strong>WhatsApp API</strong><span><?= $settings['whatsapp_enabled'] === '1' ? 'Aktif' : 'Nonaktif' ?></span></p>
            </div>
        </article>
    </section>
</div>
