<?php
// 1. Data ophalen en decoderen
$navigationData = file_get_contents('data/navigation.json');
$navigationLinks = json_decode($navigationData, true);

// 2. Omgevingsvariabelen bepalen voor correcte links
$base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\') . '/';
$on_home = (basename($_SERVER['PHP_SELF']) === 'index.php');
?>

<footer class="footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <div class="nav-logo">Oxy<span>Pure</span></div>
            <p>Pioniers in atmosferische digitalisering sinds 2026.</p>
        </div>

        <div class="footer-links">
            <h5>Navigatie</h5>
            <ul>
                <?php foreach ($navigationLinks as $link): ?>
                    <?php
                    // Logica voor anchors (#) vanaf subpagina's
                    $final_url = $link['url'];
                    if (strpos($link['url'], '#') === 0 && !$on_home) {
                        $final_url = $base_url . 'index.php' . $link['url'];
                    } elseif (strpos($link['url'], 'http') !== 0 && strpos($link['url'], '#') !== 0) {
                        $final_url = $base_url . $link['url'];
                    }
                    ?>
                    <li>
                        <a href="<?php echo $final_url; ?>">
                            <?php echo $link['name']; ?> </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="footer-legal">
            <h5>Juridisch</h5>
            <p>OxyPure is een satirisch project voor SyntraPXL. Geen echte lucht gegarandeerd.</p>
            <p>&copy; <?php echo date('Y'); ?> Junior Full Stack Dev Portfolio.</p>
        </div>
    </div>
</footer>

</body>

</html>