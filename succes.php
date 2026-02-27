<?php
// Bovenin success.php, na het laden van de sessie
$to = $_SESSION['klant_email'];
$subject = "Uw Certificaat van Puurheid - OxyPure";

// De inhoud van de mail voor de "normale mens"
$message = "
<html>
<head><title>OxyPure Bevestiging</title></head>
<body>
    <h2>Gefeliciteerd, " . $_SESSION['klant_naam'] . "!</h2>
    <p>Uw aankoop van <strong>" . $_SESSION['gekozen_pakket']['title'] . "</strong> is succesvol verwerkt.</p>
    <p>U heeft zojuist bijgedragen aan de Million-Euro Mirage. De wereld ademt weer een stukje exclusiever dankzij u.</p>
    <p>Download uw asset hier: <a href='https://jouwdomein.be/assets/puurheid.zip'>Uw Digitale Atmosfeer</a></p>
    <br>
    <p>Met zuivere groet,<br>Het OxyPure Team</p>
</body>
</html>
";

// Headers voor HTML mail
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: no-reply@oxypure.be" . "\r\n";

// Verstuur de mail
mail($to, $subject, $message, $headers);
?>

<div class="cert-box">
    <h3>CERTIFICAAT VAN PUURHEID</h3>
    <p>Status: <span style="color: var(--blue);">KLAAR VOOR DOWNLOAD</span></p>
    <br>
    <a href="assets/certificaat.pdf" download class="btn-main">
        Download Uw Asset (.pdf)
    </a>
</div>