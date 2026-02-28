<?php
// Laad de data in
$jsonData = file_get_contents('data/tiers.json');
$tiers = json_decode($jsonData, true);

// Pak het eerste item uit de lijst (ons certificaat)
$certificaat = reset($tiers);
?>

<section id="collectie" class="section bg-soft">
    <div class="container text-center">
        <span class="label">Beperkte Uitgave</span>
        <h2>Claim Uw Aandeel</h2>
        <p style="max-width: 600px; margin: 0 auto 40px; color: #64748b;">
            Zodra de Million-Euro Mirage is voltooid, worden er geen nieuwe certificaten meer uitgegeven.
        </p>

        <div class="single-pricing-wrapper">
            <div class="price-box featured">
                <div class="popular">Slechts één per persoon</div>

                <h4><?php echo $certificaat['title']; ?></h4>
                <p><?php echo $certificaat['description']; ?></p>

                <div class="amount"><?php echo number_format($certificaat['price'], 2, ',', '.'); ?></div>

                <ul class="features">
                    <?php foreach ($certificaat['features'] as $feature): ?>
                        <li><?php echo $feature; ?></li>
                    <?php endforeach; ?>
                </ul>

                <a href="bestellen.php?pakket=<?php echo $certificaat['id']; ?>" class="btn-main highlight">
                    Reserveer Mijn Certificaat
                </a>

                <p style="font-size: 0.75rem; color: #94a3b8; margin-top: 20px;">
                    * Beperkt tot 1 claim per e-mailadres om eerlijke verdeling te garanderen.
                </p>
            </div>
        </div>
    </div>
</section>