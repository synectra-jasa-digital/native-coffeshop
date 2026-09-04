<?php
/**
 * Button Component
 * 
 * @param string $type Button type ('button', 'submit', 'reset') - default: 'button'
 * @param string $variant Visual variant ('primary', 'secondary', 'danger', 'accent') - default: 'primary'
 * @param string $size Button size ('sm', 'md', 'lg') - default: 'md'
 * @param string $text Button text
 * @param string $icon Optional SVG icon (HTML string)
 * @param string $class Additional CSS classes
 * @param array $attributes Additional HTML attributes
 */

$type = $type ?? 'button';
$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$text = $text ?? '';
$icon = $icon ?? '';
$class = $class ?? '';
$attributes = $attributes ?? [];

// Base classes - smooth transitions (150-300ms) and explicit cursor pointer
$baseClasses = "inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200 ease-in-out cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed";

// Size classes
$sizeClasses = [
    'sm' => 'px-3 py-1.5 text-xs',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-5 py-2.5 text-base',
];

// Variant classes (Matching rules/design.md)
$variantClasses = [
    'primary' => 'bg-primary text-white hover:bg-primary-hover focus:ring-primary shadow-sm border border-transparent',
    'secondary' => 'bg-surface text-primary hover:bg-background border border-primary focus:ring-primary shadow-sm',
    'danger' => 'bg-danger text-white hover:bg-red-700 focus:ring-danger shadow-sm border border-transparent',
];

$finalClass = sprintf(
    "%s %s %s %s",
    $baseClasses,
    $sizeClasses[$size] ?? $sizeClasses['md'],
    $variantClasses[$variant] ?? $variantClasses['primary'],
    $class
);

// Build attributes string
$attrString = '';
foreach ($attributes as $key => $value) {
    if (is_numeric($key)) {
        $attrString .= " $value"; // for boolean attributes like 'disabled'
    } else {
        $attrString .= " $key=\"" . htmlspecialchars($value) . "\"";
    }
}
?>

<button type="<?= htmlspecialchars($type) ?>" class="<?= $finalClass ?>" <?= $attrString ?>>
    <?php if ($icon): ?>
        <span class="mr-2 -ml-1"><?= $icon ?></span>
    <?php endif; ?>
    <?= htmlspecialchars($text) ?>
</button>