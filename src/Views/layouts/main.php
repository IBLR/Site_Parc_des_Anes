<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

    <meta
        name="description"
        content="<?= htmlspecialchars($meta['description'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
    >

    <link rel="icon" href="<?= BASE_URL ?>/assets/img/branding/logo.ico" type="image/x-icon">

    <meta
        name="author"
        content="<?= htmlspecialchars($meta['author'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
    >

    <meta
        name="robots"
        content="<?= htmlspecialchars($meta['robots'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
    >

    <link
        rel="canonical"
        href="<?= htmlspecialchars($canonicalUrl, ENT_QUOTES, 'UTF-8') ?>"
    >

    <?php // Internal Style ?>
    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/main.css">
    <?php foreach ($internalStyles ?? [] as $style): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/styles/pages/<?= htmlspecialchars($style) ?>">
    <?php endforeach; ?>


    
    <?php // External Style for Leaflet map ?>
    <?php foreach ($externalStyles ?? [] as $style): ?>
        <link 
            rel="stylesheet"
            href="<?= htmlspecialchars($style['href']) ?>"
            <?= $style['integrity'] ?? '' ?>
            <?= $style['crossorigin'] ?? '' ?>
        >
    <?php endforeach; ?>

    <?php // Internals Scripts ?>
    <script>
        const DEV_MODE = <?= DEV_MODE ? 'true' : 'false' ?>;
    </script>
    <script src="<?= BASE_URL ?>/scripts/utilities.js" defer></script>
    <script src="<?= BASE_URL ?>/scripts/header.js" defer></script>
    <script src="<?= BASE_URL ?>/scripts/burger.js" defer></script>
    <?php foreach ($internalScripts ?? [] as $script): ?>
        <script src="<?= BASE_URL ?>/scripts/<?= htmlspecialchars($script) ?>" defer></script>
    <?php endforeach; ?>

    <?php // External Script for Leaflet map ?>
    <?php foreach ($externalScripts ?? [] as $script): ?>
        <script 
            src="<?= htmlspecialchars($script['src']) ?>"
            <?= $script['integrity'] ?? '' ?>
            <?= $script['crossorigin'] ?? '' ?>
            defer>
        </script>
    <?php endforeach; ?>
</head>

<body class="light-theme">
    <?php if (empty($hideHeader)): ?>
        <?= $header ?>
    <?php endif; ?>

    <?= $content ?>

    <?= $footer ?>
</body>

</html>