<?php
/**
 * Form Input Component
 * 
 * @param string $name Input name attribute
 * @param string $id Input ID (defaults to name)
 * @param string $type Input type (text, email, password, number, etc.) - default: 'text'
 * @param string $label Input label
 * @param string $value Input value
 * @param string $placeholder Input placeholder
 * @param string $error Error message string if any
 * @param string $class Additional CSS classes
 * @param array $attributes Additional HTML attributes
 */

$name = $name ?? '';
$id = $id ?? $name;
$type = $type ?? 'text';
$label = $label ?? '';
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$error = $error ?? '';
$class = $class ?? '';
$attributes = $attributes ?? [];

$hasError = !empty($error);

// Build attributes string
$attrString = '';
foreach ($attributes as $key => $val) {
    if (is_numeric($key)) {
        $attrString .= " $val"; // e.g., 'required', 'disabled'
    } else {
        $attrString .= " $key=\"" . htmlspecialchars($val) . "\"";
    }
}
?>

<div class="w-full">
    <?php if ($label): ?>
        <label for="<?= htmlspecialchars($id) ?>" class="block text-sm font-medium text-textSecondary mb-1">
            <?= htmlspecialchars($label) ?>
        </label>
    <?php endif; ?>
    
    <div class="relative">
        <input 
            type="<?= htmlspecialchars($type) ?>" 
            name="<?= htmlspecialchars($name) ?>" 
            id="<?= htmlspecialchars($id) ?>" 
            value="<?= htmlspecialchars($value) ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            class="block w-full rounded-md shadow-sm sm:text-sm transition-colors duration-200 focus:outline-none focus:ring-1 focus:border-primary
                <?= $hasError 
                    ? 'border-danger text-danger focus:ring-danger' 
                    : 'border-border text-textPrimary focus:ring-primary' 
                ?> border px-3 py-2 <?= $class ?>"
            <?= $attrString ?>
        >
        
        <?php if ($hasError): ?>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <svg class="h-5 w-5 text-danger" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($hasError): ?>
        <p class="mt-1 text-sm text-danger font-medium"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</div>