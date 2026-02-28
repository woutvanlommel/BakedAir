<?php
session_start();

// 1. Data ophalen uit tiers.json
$jsonData = file_get_contents('data/tiers.json');
$tiers = json_decode($jsonData, true);

// Gebruik het specifieke certificaat
$pakket = $tiers['certificaat'] ?? reset($tiers);

if ($pakket) {
    $_SESSION['gekozen_pakket'] = $pakket;
} else {
    header('Location: index.php');
    exit();
}

// 2. Error handling
$error_msg = "";
if (isset($_GET['error']) && $_GET['error'] === 'already_claimed') {
    $error_msg = "Dit e-mailadres is reeds gekoppeld aan een certificaat. Per persoon is slechts één claim toegestaan.";
}

include('includes/header.php');
?>

<section class="section"">
    <div class=" order-form-container">

    <?php if ($error_msg): ?>
        <div class="alert-error">
            ⚠️ <?php echo $error_msg; ?>
        </div>
    <?php endif; ?>

    <div class="order-header">
        <span class="badge red-alert">Officiële Registratie</span>
        <h2 class="order-title">Claim Uw Eigendom</h2>
        <p class="order-subtitle">Vul uw gegevens in om uw unieke Certificaat van Puurheid te genereren.</p>
    </div>

    <div class="order-card">
        <form action="verwerken.php" method="POST">
            <div class="form-info">
                <div class="form-group">
                    <label for="naam">Volledige Naam</label>
                    <input type="text" id="naam" name="naam" required placeholder="Uw naam voor op het certificaat">
                </div>
                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" id="email" name="email" required placeholder="bijv. naam@domein.be">
                    <small class="input-hint">* Maximaal één certificaat per uniek e-mailadres.</small>
                </div>
            </div>

            <div class="order-summary">
                <div class="summary-row">
                    <strong><?php echo $_SESSION['gekozen_pakket']['title']; ?></strong>
                </div>
                <div class="summary-total">
                    <span>Totaalprijs:</span>
                    <strong>€<?php echo number_format($_SESSION['gekozen_pakket']['price'], 2, ',', '.'); ?></strong>
                </div>
            </div>

            <button type="submit" class="btn-submit-order highlight">
                Bevestig & Ga naar Betaling
            </button>
        </form>
        <p class="security-note">Beveiligde betaling via Stripe SSL-encryptie.</p>
    </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>