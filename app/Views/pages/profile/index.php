<!-- Halaman Profil Pengguna -->
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header Page -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-0.5">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>
    </div>

    <!-- Alert Messages (handled by Layout Dialog/Flash) -->

    <!-- Profile Overview Card -->
    <div class="bg-surface rounded-xl border border-border shadow-sm p-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
        <div class="w-24 h-24 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center text-primary font-bold text-3xl shrink-0 shadow-inner overflow-hidden relative group">
            <?php if (!empty($user['avatar_url'])): ?>
                <img id="overview-avatar" src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="<?= htmlspecialchars($user['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <span id="overview-initial" class="text-primary font-bold text-3xl leading-none"><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
                <img id="overview-avatar" src="" alt="Preview Avatar" class="hidden w-full h-full object-cover">
            <?php endif; ?>
        </div>
        <div class="flex-1 text-center sm:text-left space-y-2">
            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                <h2 class="text-xl font-bold text-textPrimary"><?= htmlspecialchars($user['name']) ?></h2>
                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-primary-light text-primary border border-primary/20 w-max mx-auto sm:mx-0">
                    <?= htmlspecialchars($user['role_name']) ?>
                </span>
            </div>
            <p class="text-sm text-textSecondary flex items-center justify-center sm:justify-start gap-2">
                <svg class="w-4 h-4 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Username: <span class="font-medium text-textPrimary">@<?= htmlspecialchars($user['username']) ?></span>
            </p>
            <p class="text-sm text-textSecondary flex items-center justify-center sm:justify-start gap-2">
                <svg class="w-4 h-4 text-textSecondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Email: <span class="font-medium text-textPrimary"><?= !empty($user['email']) ? htmlspecialchars($user['email']) : '-' ?></span>
            </p>
        </div>
    </div>

    <!-- Edit Profile Form Card -->
    <div class="bg-surface rounded-xl border border-border shadow-sm overflow-hidden">
        <div class="p-4 border-b border-border bg-gray-50/80">
            <h2 class="text-base font-bold text-textPrimary">Edit Informasi Profil</h2>
            <p class="text-xs text-textSecondary">Perbarui informasi pribadi & foto profil akun Anda.</p>
        </div>

        <form method="POST" action="<?= BASE_URL ?>/profile/save" enctype="multipart/form-data" class="p-6 space-y-6">
            
            <!-- Avatar Upload Field -->
            <div class="p-4 bg-gray-50 rounded-xl border border-border space-y-3">
                <label class="block text-sm font-medium text-textPrimary">Foto Profil (Avatar)</label>
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <div class="w-16 h-16 rounded-xl bg-surface border border-border flex items-center justify-center overflow-hidden shrink-0">
                        <?php if (!empty($user['avatar_url'])): ?>
                            <img id="form-avatar-preview" src="<?= htmlspecialchars($user['avatar_url']) ?>" alt="Avatar" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div id="form-avatar-placeholder" class="text-textSecondary font-bold text-xl"><?= strtoupper(substr($user['name'], 0, 1)) ?></div>
                            <img id="form-avatar-preview" src="" alt="Preview Avatar" class="hidden w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 space-y-2 text-center sm:text-left">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-xs font-semibold rounded-lg hover:bg-primary-hover transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                <span><?= !empty($user['avatar_url']) ? 'Ganti Foto Avatar' : 'Pilih Foto Avatar' ?></span>
                                <input type="file" name="avatar" id="avatar_input" accept="image/png, image/jpeg, image/webp, image/gif" class="hidden" onchange="previewUserAvatar(this)">
                            </label>

                            <?php if (!empty($user['avatar_url'])): ?>
                                <label class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-50 text-red-600 border border-red-200 text-xs font-semibold rounded-lg hover:bg-red-100 transition cursor-pointer">
                                    <input type="checkbox" name="remove_avatar" value="1" class="rounded text-red-600 focus:ring-red-500">
                                    <span>Hapus Foto Currently</span>
                                </label>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-textSecondary">Format yang didukung: PNG, JPG, WEBP. Maksimal 2MB.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-medium text-textSecondary mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required 
                           value="<?= htmlspecialchars($user['name']) ?>" 
                           class="w-full px-4 py-2.5 border border-border rounded-lg text-sm text-textPrimary focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-colors">
                </div>

                <!-- Username (Readonly) -->
                <div>
                    <label for="username" class="block text-sm font-medium text-textSecondary mb-1">Username (Tidak dapat diubah)</label>
                    <input type="text" 
                           id="username" 
                           value="<?= htmlspecialchars($user['username']) ?>" 
                           disabled 
                           class="w-full px-4 py-2.5 border border-border bg-gray-100 rounded-lg text-sm text-textSecondary font-mono cursor-not-allowed">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-textSecondary mb-1">Alamat Email</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           placeholder="contoh@domain.com" 
                           value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                           class="w-full px-4 py-2.5 border border-border rounded-lg text-sm text-textPrimary focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-colors">
                </div>

                <!-- Role (Readonly) -->
                <div>
                    <label for="role" class="block text-sm font-medium text-textSecondary mb-1">Hak Akses / Role</label>
                    <input type="text" 
                           id="role" 
                           value="<?= htmlspecialchars($user['role_name']) ?>" 
                           disabled 
                           class="w-full px-4 py-2.5 border border-border bg-gray-100 rounded-lg text-sm text-textSecondary cursor-not-allowed">
                </div>
            </div>

            <!-- Password Change Section -->
            <div class="border-t border-border pt-6 mt-6 space-y-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="text-base font-bold text-textPrimary">Ubah Password Akun</h3>
                </div>
                <p class="text-xs text-textSecondary">Kosongkan bidang password di bawah ini jika tidak ingin mengubah password akun Anda.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-textSecondary mb-1">Password Saat Ini</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               placeholder="••••••••" 
                               class="w-full px-4 py-2 border border-border rounded-lg text-sm text-textPrimary focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label for="new_password" class="block text-xs font-semibold text-textSecondary mb-1">Password Baru (Min. 6 karakter)</label>
                        <input type="password" 
                               id="new_password" 
                               name="new_password" 
                               placeholder="••••••••" 
                               class="w-full px-4 py-2 border border-border rounded-lg text-sm text-textPrimary focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-xs font-semibold text-textSecondary mb-1">Konfirmasi Password Baru</label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               placeholder="••••••••" 
                               class="w-full px-4 py-2 border border-border rounded-lg text-sm text-textPrimary focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                    </div>
                </div>
            </div>

            <!-- Submit Action -->
            <div class="border-t border-border pt-4 flex justify-end gap-3">
                <a href="<?= BASE_URL ?>" class="px-5 py-2.5 bg-surface text-textPrimary border border-border rounded-lg text-sm font-medium hover:bg-background transition-colors cursor-pointer">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-bold hover:bg-primary-hover shadow-sm transition-colors cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewUserAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const overviewImg = document.getElementById('overview-avatar');
            const overviewInitial = document.getElementById('overview-initial');
            const formImg = document.getElementById('form-avatar-preview');
            const formPlaceholder = document.getElementById('form-avatar-placeholder');

            if (overviewImg) {
                overviewImg.src = e.target.result;
                overviewImg.classList.remove('hidden');
            }
            if (overviewInitial) {
                overviewInitial.classList.add('hidden');
            }

            if (formImg) {
                formImg.src = e.target.result;
                formImg.classList.remove('hidden');
            }
            if (formPlaceholder) {
                formPlaceholder.classList.add('hidden');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
