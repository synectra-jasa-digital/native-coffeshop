<?php
/**
 * Badge Component
 * 
 * @param string $text The text inside the badge
 * @param string $variant 'success', 'warning', 'danger', 'info', 'neutral'
 */

$text = $text ?? '';
$variant = $variant ?? 'neutral';

$classes = [
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-amber-100 text-amber-800',
    'danger'  => 'bg-red-100 text-red-800',
    'info'    => 'bg-blue-100 text-blue-800',
    'neutral' => 'bg-gray-100 text-gray-800'
];

$variantClass = $classes[$variant] ?? $classes['neutral'];
?>

<span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium <?= $variantClass ?>">
    <?= htmlspecialchars($text) ?>
</span>