<?php
/**
 * Simple Table Component (Styling Only)
 * 
 * @param array $headers Array of strings for table headers
 * @param array $rows Array of associative arrays matching header keys
 */

$headers = $headers ?? [];
$rows = $rows ?? [];
?>

<div class="overflow-x-auto rounded-md border border-border">
    <table class="min-w-full divide-y divide-border">
        <thead class="bg-background">
            <tr>
                <?php foreach ($headers as $key => $label): ?>
                    <th scope="col" class="px-3 py-3 text-left text-xs font-semibold text-textSecondary uppercase tracking-wider whitespace-nowrap">
                        <?= htmlspecialchars($label) ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody class="bg-surface divide-y divide-border">
            <?php if (empty($rows)): ?>
                <tr>
                    <td colspan="<?= count($headers) ?>" class="px-3 py-8 text-center text-sm text-textSecondary">
                        Tidak ada data yang tersedia.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($rows as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <?php foreach ($headers as $key => $label): ?>
                            <td class="whitespace-nowrap px-3 py-3 text-sm text-textPrimary">
                                <?= isset($row[$key]) ? $row[$key] : '-' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>