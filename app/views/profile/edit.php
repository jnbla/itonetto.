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
            <p class="dashboard-kicker">Profil Pengguna</p>
            <h1>Atur Profil Anda</h1>
            <p>Perbarui username, email, dan password untuk akun Anda.</p>
        </div>
    </section>

    <section class="location-layout">
        <article class="transport-form-card">
            <div class="section-title">
                <h2>Data Akun</h2>
                <span>Profil</span>
            </div>

            <form method="POST" action="/IkiNet/app/controllers/ProfileController.php?action=update" class="transport-form">
                <label>
                    <span>Username</span>
                    <input type="text" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
                </label>

                <label>
                    <span>Email</span>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                </label>

                <label>
                    <span>Password Baru</span>
                    <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengganti">
                </label>

                <label>
                    <span>Konfirmasi Password</span>
                    <input type="password" name="confirm_password" placeholder="Ulangi password baru">
                </label>

                <div class="form-actions">
                    <button type="submit">Simpan Profil</button>
                    <a href="/IkiNet/app/controllers/BookingController.php?action=user" class="button secondary">Kembali</a>
                </div>
            </form>
        </article>
    </section>
</div>
