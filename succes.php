<?php
session_start();
require_once('includes/env_loader.php');

$session_id = $_GET['session_id'] ?? null;

// 1. Veiligheidscheck: is er een session_id en kennen we de klant nog?
if (!$session_id || !isset($_SESSION['klant_email'])) {
    header('Location: index.php');
    exit();
}

// 2. Verifieer de betaling bij Stripe via cURL
$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/checkout/sessions/$session_id");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERPWD, $stripe_secret_key . ':');
$result = json_decode(curl_exec($ch), true);
curl_close($ch);

// 3. Alleen doorgaan als de status echt 'paid' is
if (!isset($result['payment_status']) || $result['payment_status'] !== 'paid') {
    header('Location: index.php?error=payment_failed');
    exit();
}

// 4. Update de Million-Euro Mirage (sales.json)
$salesFile = 'data/sales.json';
if (file_exists($salesFile)) {
    $salesData = json_decode(file_get_contents($salesFile), true);
    $salesData['total_revenue'] += $_SESSION['gekozen_pakket']['price'];
    file_put_contents($salesFile, json_encode($salesData, JSON_PRETTY_PRINT));
}

// 5. Verstuur de bevestigingsmail
$to = $_SESSION['klant_email'];
$subject = "Uw Certificaat van Puurheid - OxyPure";
$message = "
<html>
<body style='font-family: Arial, sans-serif;'>
    <h2>Gefeliciteerd, " . htmlspecialchars($_SESSION['klant_naam']) . "!</h2>
    <p>Uw aankoop van <strong>" . $_SESSION['gekozen_pakket']['title'] . "</strong> is succesvol verwerkt.</p>
    <p>U heeft zojuist bijgedragen aan de Million-Euro Mirage.</p>
    <p>Download uw asset hier: <a href='" . $_ENV['BASE_URL'] . "assets/puurheid.zip'>Uw Digitale Atmosfeer</a></p>
    <br>
    <p>Met zuivere groet,<br>Het OxyPure Team</p>
</body>
</html>";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type:text/html;charset=UTF-8\r\n";
$headers .= "From: OxyPure <no-reply@oxypure.be>\r\n";

mail($to, $subject, $message, $headers);

// Sla de naam even op voor de HTML en maak de sessie daarna leeg om double-counting te voorkomen
$klant_naam = $_SESSION['klant_naam'];
unset($_SESSION['klant_naam']);
unset($_SESSION['klant_email']);

include('includes/header.php');
?>

<section class="section text-center" style="padding-top: 150px;">
    <div class="container" style="max-width: 600px;">
        <div class="success-card" style="background: white; padding: 50px; border-radius: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.05);">
            <h1 class="gradient-text">Betaling Geslaagd!</h1>
            <p>Welkom bij de elite, <strong><?php echo htmlspecialchars($klant_naam); ?></strong>.</p>

            <div class="cert-box" style="border: 2px dashed var(--blue); padding: 30px; margin: 30px 0; border-radius: 20px;">
                <h3>CERTIFICAAT VAN PUURHEID</h3>
                <p>Status: <span style="color: var(--blue); font-weight: bold;">OFFICIEEL GEREGISTREERD</span></p>
                <br>
                <a href="assets/certificaat.pdf" download class="btn-main" style="display: inline-block; text-decoration: none;">
                    Download Uw Asset (.pdf)
                </a>
            </div>

            <a href="index.php" style="color: #64748b; text-decoration: none;">Terug naar de homepagina</a>
        </div>
    </div>
</section>

<?php include('includes/footer.php'); ?>