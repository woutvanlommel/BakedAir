<?php
session_start();

// 1. Haal de data op om te weten welk pakket gekozen is
$jsonData = file_get_contents('data/tiers.json');
$tiers = json_decode($jsonData, true);

// 2. Welk pakket is aangeklikt?
$pakket_id = $_GET['pakket'] ?? null;

if ($pakket_id && isset($tiers[$pakket_id])) {
    // Sla de keuze op in de sessie
    $_SESSION['gekozen_pakket'] = $tiers[$pakket_id];
} else {
    // Geen geldig pakket? Terug naar de home
    header('Location: index.php');
    exit();
}

include('includes/header.php');
?>

<section class="section">
    <div class="container" style="max-width: 600px; padding-top: 100px;">
        <div class="text-center mb-50">
            <span class="label">Uw Selectie: <?php echo $_SESSION['gekozen_pakket']['title']; ?></span>
            <h2>Bijna klaar voor uw nieuwe atmosfeer</h2>
            <p>Vul uw gegevens in zodat we uw digitale zuurstof-certificaat correct kunnen registreren.</p>
        </div>

        <form action="verwerken.php" method="POST" class="order-form">
            <div class="form-group">
                <label for="naam">Volledige Naam</label>
                <input type="text" id="naam" name="naam" required placeholder="Bijv. Pieter Janssens">
            </div>

            <div class="form-group">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" required placeholder="naam@voorbeeld.be">
            </div>

            <div class="order-summary" style="background: #f0f7ff; padding: 20px; border-radius: 15px; margin: 20px 0;">
                <p><strong>Pakket:</strong> <?php echo $_SESSION['gekozen_pakket']['title']; ?></p>
                <p><strong>Totaalprijs:</strong> €<?php echo $_SESSION['gekozen_pakket']['price']; ?></p>
            </div>

            <button type="submit" class="btn-main" style="width: 100%; border: none; cursor: pointer;">
                Bevestig & Ga naar Betaling
            </button>
        </form>
    </div>
</section>

<?php include('includes/footer.php'); ?>