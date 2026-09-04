<!-- Form Pengguna -->
<div class="flex flex-col h-full w-full">
    <!-- Page Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= BASE_URL ?>/users" class="text-textSecondary hover:text-primary transition-colors bg-surface p-2 rounded-md border border-border shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-textPrimary"><?= htmlspecialchars($title) ?></h1>
            <p class="text-sm text-textSecondary mt-1">Kelola data pengguna dan perannya di dalam sistem.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-surface rounded-lg border border-border shadow-sm p-6 flex-1">
        <form method="POST" action="<?= BASE_URL ?>/users/save<?= $user ? '/' . $user['id'] : '' ?>" class="space-y-6 w-full">
            
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Nama Lengkap -->
                <div class="col-span-2 sm:col-span-1">
                    <label for="name" class="block text-sm font-medium text-textSecondary mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" required value="<?= htmlspecialchars($user['name'] ?? '') ?>" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                </div>

                <!-- Username -->
                <div class="col-span-2 sm:col-span-1">
                    <label for="username" class="block text-sm font-medium text-textSecondary mb-1">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" id="username" required value="<?= htmlspecialchars($user['username'] ?? '') ?>" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                </div>

                <!-- Email -->
                <div class="col-span-2">
                    <label for="email" class="block text-sm font-medium text-textSecondary mb-1">Alamat Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                </div>

                <!-- Role -->
                <div class="col-span-2 sm:col-span-1">
                    <label for="role_id" class="block text-sm font-medium text-textSecondary mb-1">Role/Peran <span class="text-danger">*</span></label>
                    <select name="role_id" id="role_id" required class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                        <option value="">Pilih Role...</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= ($user['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-textSecondary mt-1">Menentukan hak akses sistem</p>
                </div>

                <!-- Status -->
                <div class="col-span-2 sm:col-span-1">
                    <label for="status" class="block text-sm font-medium text-textSecondary mb-1">Status Akun</label>
                    <select name="status" id="status" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2 cursor-pointer">
                        <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="border-t border-border pt-6">
                <h3 class="text-base font-bold text-textPrimary mb-4">Keamanan</h3>
                
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-textSecondary mb-1">
                        Password <?= !$user ? '<span class="text-danger">*</span>' : '(Kosongkan jika tidak ingin mengubah)' ?>
                    </label>
                    <input type="password" name="password" id="password" <?= !$user ? 'required' : '' ?> minlength="6" class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 border-border text-textPrimary focus:border-primary focus:ring-primary border px-3 py-2">
                    <p class="text-xs text-textSecondary mt-1">Minimal 6 karakter</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="border-t border-border pt-6 flex items-center justify-end gap-3">
                <a href="<?= BASE_URL ?>/users" class="inline-flex items-center justify-center font-medium rounded-md px-4 py-2 text-sm bg-surface text-textPrimary hover:bg-background border border-border focus:ring-border shadow-sm cursor-pointer transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-hover focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary transition-all duration-200">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>