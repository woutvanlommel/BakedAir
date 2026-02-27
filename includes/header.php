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
            <a href="index.php" class="nav-brand">Oxy<span>Pure</span></a>

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


    <header class="hero" id="hero">
        <div class="container">
            <span class="badge">Nu Beschikbaar in de Cloud</span>
            <h1>Upgrade uw atmosfeer <br><span class="gradient-text">met één klik.</span></h1>
            <p class="hero-sub">Waarom zou u genoegen nemen met gratis lucht als u kunt kiezen voor de exclusiviteit van gedigitaliseerde zuurstof?</p>
            <div class="hero-actions">
                <a href="#collectie" class="btn-main">Ontdek de Collectie</a>
                <a href="#waarom" class="btn-secondary">Hoe werkt het?</a>
            </div>
        </div>
    </header>