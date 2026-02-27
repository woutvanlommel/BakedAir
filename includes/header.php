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

    // Functie om de huidige pagina te bepalen
    function current_page()
    {
        return basename($_SERVER['PHP_SELF']);
    }
    ?>

    <nav class="navbar">
        <div class="container nav-flex">
            <a href="<?php echo (current_page() === 'index.php') ? '#hero' : $base_url . 'index.php'; ?>" class="nav-brand">
                Oxy<span>Pure</span>
            </a>

            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">&#9776;</label>

            <ul class="nav-links">
                <?php foreach ($navItems as $item): ?>
                    <li>
                        <?php
                        $is_external = (strpos($item['url'], 'http') === 0);
                        $is_anchor = (strpos($item['url'], '#') === 0);
                        $on_home = (current_page() === 'index.php');

                        $final_url = $item['url'];

                        if (!$is_external) {
                            if ($is_anchor) {
                                // Als we NIET op de home zijn, moet #waarom veranderen in index.php#waarom
                                if (!$on_home) {
                                    $final_url = $base_url . 'index.php' . $item['url'];
                                } else {
                                    // Op de home zelf laten we het gewoon #waarom voor soepel scrollen
                                    $final_url = $item['url'];
                                }
                            } else {
                                // Voor gewone pagina's zoals contact.php altijd de base_url gebruiken
                                $final_url = $base_url . $item['url'];
                            }
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