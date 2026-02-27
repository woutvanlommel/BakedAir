<?php
// Laad de reviews in
$reviewsJson = file_get_contents('data/reviews.json');
$reviews = json_decode($reviewsJson, true);
?>

<section id="ervaringen" class="section">
    <div class="container">
        <div class="text-center mb-50">
            <span class="label">Klantverhalen</span>
            <h2>Wat onze community ervaart</h2>
            <p style="color: #64748b; max-width: 500px; margin: 0 auto;">
                Ontdek waarom honderden levensgenieters al kozen voor de digitale zuiverheid van OxyPure.
            </p>
        </div>

        <div class="testimonial-grid">
            <?php foreach ($reviews as $review): ?>
                <div class="testimonial">
                    <div class="stars">
                        <?php
                        // Genereer sterren op basis van de rating
                        echo str_repeat('★', $review['rating']);
                        ?>
                    </div>
                    <p>"<?php echo $review['quote']; ?>"</p>
                    <cite>
                        <span class="cite-name"><?php echo $review['name']; ?></span>
                        <span class="cite-title"><?php echo $review['title']; ?></span>
                    </cite>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>