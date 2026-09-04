<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-bg-app text-text-primary antialiased min-h-screen flex flex-col">

    <!-- Flash Messages (Optional) -->
    <?php $flashSuccess = \App\Core\Session::getFlash('success'); ?>
    <?php $flashError = \App\Core\Session::getFlash('error'); ?>
    
    <?php if ($flashSuccess): ?>
        <div class="bg-primary text-white p-4 text-center">
            <?= htmlspecialchars($flashSuccess) ?>
        </div>
    <?php endif; ?>

    <?php if ($flashError): ?>
        <div class="bg-danger text-white p-4 text-center">
            <?= htmlspecialchars($flashError) ?>
        </div>
    <?php endif; ?>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        <?= $slot ?>
    </main>

</body>
</html>
