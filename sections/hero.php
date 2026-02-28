<?php
$sales = json_decode(file_get_contents('data/sales.json'), true);
$available = $sales['total_available'] - $sales['certificates_sold'];
$tiers = file_get_contents('data/tiers.json');
$tiers = json_decode($tiers, true);
$price = $tiers['certificaat']['price'];
?>
<header class="hero" id="hero">
    <div class="container">
        <span class="badge red-alert">Nog slechts <?php echo number_format($available, 0, ',', '.'); ?> certificaten beschikbaar</span>
        <h1>De wereldvoorraad zuivere lucht <br><span class="gradient-text">raakt onherroepelijk op.</span></h1>
        <p class="hero-sub">OxyPure stelt de laatste atmosferische reserves veilig. Claim uw persoonlijk Certificaat van Puurheid voordat de Mirage voltooid is.</p>
        <div class="hero-actions">
            <a href="bestellen.php?pakket=certificaat" class="btn-main">Claim Uw Aandeel (€<?php echo number_format($price, 0, ',', '.'); ?>)</a>
            <a href="#waarom" class="btn-secondary">De Noodzaak</a>
        </div>
    </div>
</header>