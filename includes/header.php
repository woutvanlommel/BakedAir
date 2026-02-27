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

    $base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
    // Laad de navigatie data in
    $navJson = file_get_contents('data/navigation.json');
    $navItems = json_decode($navJson, true);
    ?>

    <nav class="navbar">
        <div class="container nav-flex">
            <a href="<?php echo $base_url; ?>index.php/#hero" class="nav-brand">Oxy<span>Pure</span></a>

            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">&#9776;</label>

            <ul class="nav-links">
                <?php foreach ($navItems as $item): ?>
                    <li>
                        <?php
                        // Check of de URL een externe link is (begint met http)
                        $is_external = (strpos($item['url'], 'http') === 0);

                        // Als het een interne link is én geen anchor (#), zet de base_url er voor
                        // Als het een anchor is (#waarom), laten we het zoals het is voor onepager-scrollen
                        $final_url = $item['url'];
                        if (!$is_external && strpos($item['url'], '#') !== 0) {
                            $final_url = $base_url . $item['url'];
                        }
                        ?>
                        <a href="<?php echo $final_url; ?>"
                            class="<?php echo $item['is_cta'] ? 'nav-cta' : ''; ?>"
                            <?php if ($is_external): ?>
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