<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OxyPure | Atmosferische Perfectie</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</head>

<body>

    <?php
    // Laad de navigatie data in
    $navJson = file_get_contents('data/navigation.json');
    $navItems = json_decode($navJson, true);
    ?>

    <nav class="navbar">
        <div class="container nav-flex">
            <a href="/#hero" class="nav-brand">Oxy<span>Pure</span></a>

            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">&#9776;</label>

            <ul class="nav-links">
                <?php foreach ($navItems as $item): ?>
                    <li>
                        <a href="<?php echo $item['url']; ?>"
                            class="<?php echo $item['is_cta'] ? 'nav-cta' : ''; ?>"
                            <?php if ($item['is_cta']): ?>
                            target="_blank"
                            rel="noopener noreferrer"
                            <?php endif; ?>>
                            <?php echo $item['name']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </nav>