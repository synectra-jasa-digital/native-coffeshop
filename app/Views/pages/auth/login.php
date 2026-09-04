<div class="w-full max-w-md bg-surface p-8 rounded-lg shadow-md border border-border">
    <div class="text-center mb-8">
        <div class="mx-auto h-12 w-12 bg-primary rounded-md flex items-center justify-center mb-4">
            <span class="text-white font-bold text-2xl leading-none">G</span>
        </div>
        <h1 class="text-2xl font-bold text-textPrimary">Good Coffee POS</h1>
        <p class="text-sm text-textSecondary mt-2">Silakan masuk untuk melanjutkan</p>
    </div>

    <!-- Error Alert -->
    <?php if (\App\Core\Session::hasFlash('error') || \App\Core\Session::hasFlash('info') || \App\Core\Session::hasFlash('success')): ?>
        <!-- Handled by Layout Dialog -->
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/login" method="POST" class="space-y-6">
        <div>
            <?= $this->component('form-input', [
                'name' => 'username',
                'label' => 'Username',
                'placeholder' => 'Masukkan username Anda',
                'attributes' => ['required' => true, 'autofocus' => true]
            ]) ?>
        </div>

        <div>
            <?= $this->component('form-input', [
                'type' => 'password',
                'name' => 'password',
                'label' => 'Password',
                'placeholder' => '••••••••',
                'attributes' => ['required' => true]
            ]) ?>
            
            <div class="flex justify-end mt-2">
                <a href="#" class="text-sm font-medium text-primary hover:text-primary-hover transition-colors duration-200 cursor-pointer">Lupa password?</a>
            </div>
        </div>

        <div class="pt-2">
            <?= $this->component('button', [
                'type' => 'submit',
                'text' => 'Masuk',
                'class' => 'w-full',
                'size' => 'lg'
            ]) ?>
        </div>
    </form>
    
    <div class="mt-8 text-center text-xs text-textSecondary">
        &copy; <?= date('Y') ?> Good Coffee. All rights reserved.
    </div>
</div>