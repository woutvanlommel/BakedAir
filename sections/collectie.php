<?php
// Laad de data in
$jsonData = file_get_contents('data/tiers.json');
$tiers = json_decode($jsonData, true);
?>

<section id="collectie" class="section bg-soft">
    <div class="container text-center">
        <span class="label">Exclusieve Reeks</span>
        <h2>Kies uw persoonlijke atmosfeer</h2>
        <p style="max-width: 600px; margin: 0 auto; color: #64748b;">
            Verwen uzelf met een moment van pure digitale sereniteit.
        </p>

        <div class="pricing-grid">
            <?php foreach ($tiers as $tier): ?>
                <div class="price-box <?php echo $tier['featured'] ? 'featured' : ''; ?>">
                    <?php if ($tier['featured']): ?>
                        <div class="popular">Meest Begeerd</div>
                    <?php endif; ?>

                    <h4><?php echo $tier['title']; ?></h4>
                    <p><?php echo $tier['description']; ?></p>

                    <div class="amount"><?php echo $tier['price']; ?></div>

                    <ul class="features">
                        <?php foreach ($tier['features'] as $feature): ?>
                            <li><?php echo $feature; ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="bestellen.php?pakket=<?php echo $tier['id']; ?>"
                        class="btn-card <?php echo $tier['featured'] ? 'highlight' : ''; ?>">
                        <?php echo ($tier['price'] > 45) ? 'Word Elite' : 'Claim Moment'; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>